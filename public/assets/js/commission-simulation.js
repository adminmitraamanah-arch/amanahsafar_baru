/* Read-only preview of CommissionModel::generateCommissionsForOrder, for one pax.
 * Keep transaction rules in CommissionModel unchanged. Tests guard preview behavior.
 */
(function () {
    'use strict';
    function simulate(values) {
        if (!Array.isArray(values) || values.length !== 4) {
            return { error: 'Perlu diperiksa: konfigurasi komisi tidak valid.' };
        }
        const parsed = values.map(value => {
            if (value == null || String(value).trim() === '') return 0;
            return Number(value);
        });
        if (parsed.some(num => !Number.isFinite(num) || num < 0)) {
            return { error: 'Perlu diperiksa: lengkapi nominal dengan angka nol atau positif.' };
        }
        const [rawMitra, rawManager, rawCabang, rawPool] = parsed;
        const mitra = rawMitra;
        const manager = rawManager;
        const cabang = rawCabang;
        const componentSum = mitra + manager + cabang;
        const pool = rawPool > 0 ? rawPool : componentSum;
        if (![pool, componentSum].every(Number.isSafeInteger)) {
            return { error: 'Perlu diperiksa: gunakan nominal rupiah bulat dalam rentang angka yang aman.' };
        }
        const row = (role, amount, source, tone) => ({ role, amount, source, tone });
        const variant = (label, rows) => {
            const total = rows.reduce((sum, entry) => sum + entry.amount, 0);
            return { label, rows, total, difference: total - pool };
        };
        const direct = (role) => row(role, mitra, 'Jatah mitra langsung', 'mitra');
        const parentVariants = role => [
            variant('Dibina langsung oleh cabang', [direct(role), row('Cabang', manager + cabang, 'Jatah manager + cabang', 'cabang')]),
            variant('Di bawah Pusat / tanpa pembina', [direct(role), ...(pool > mitra ? [row('Pusat', pool - mitra, 'Sisa pool setelah jatah mitra', 'pusat')] : [])])
        ];
        return {
            pool,
            warning: '',
            scenarios: [
                { title: 'Closing Langsung Cabang / Pusat', description: 'Cabang closing sendiri menerima seluruh pool. Closing langsung Pusat belum tersedia pada alur order saat ini.', variants: [variant('Cabang closing sendiri', [row('Cabang', pool, '100% total pool', 'cabang')])] },
                { title: 'Mitra Mandiri (Tanpa Manager)', description: 'Mitra closing tanpa manager di atasnya. Penerima bagian lainnya mengikuti pembina.', variants: parentVariants('Mitra') },
                { title: 'Mitra Binaan Cabang (Ada Manager)', description: 'Cabang → Manager → Mitra yang closing.', variants: [variant('', [direct('Mitra'), row('Manager', manager, 'Jatah pembinaan manager', 'manager'), row('Cabang', cabang, 'Jatah cabang', 'cabang')])] },
                { title: 'Mitra Binaan Pusat (Ada Manager)', description: 'Manager pembina tidak berada di bawah cabang. Bagian cabang dialihkan ke Pusat.', variants: [variant('', [direct('Mitra'), row('Manager', manager, 'Jatah pembinaan manager', 'manager'), row('Pusat', cabang, 'Jatah cabang/Pusat', 'pusat')])] },
                { title: 'Closing Manager Mitra', description: 'Sesuai sistem saat ini, manager yang closing sendiri hanya mendapat jatah mitra langsung.', variants: parentVariants('Manager yang closing') }
            ]
        };
    }
    if (typeof module !== 'undefined' && module.exports) module.exports = simulate;
    if (typeof document === 'undefined') return;

    function mount() {
        const root = document.getElementById('commissionSimulation');
        if (!root) return;
        const inputs = ['komisiMitraInput', 'komisiManagerInput', 'komisiCabangInput', 'totalPoolInput'].map(id => document.getElementById(id));
        const detailMode = root.dataset.simulationMode === 'detail';
        if (!detailMode && inputs.some(input => !input)) return;
        const money = value => 'Rp ' + value.toLocaleString('id-ID', { maximumFractionDigits: 2 });
        function element(tag, className, text) {
            const node = document.createElement(tag);
            node.className = className;
            if (text !== undefined) node.textContent = text;
            return node;
        }
        function render(values) {
            const result = simulate(detailMode ? values : inputs.map(input => input.value));
            const content = root.querySelector('[data-simulation-cards]');
            const notice = root.querySelector('[data-simulation-notice]');
            notice.textContent = result.error && detailMode
                ? 'Perlu diperiksa: konfigurasi komisi paket belum lengkap atau tidak valid. Periksa pengaturan paket.'
                : result.error || result.warning || '';
            notice.hidden = !notice.textContent;
            root.querySelector('[data-simulation-pool]').textContent = result.error ? 'Pool perlu diperiksa' : money(result.pool) + ' / jemaah';
            content.replaceChildren();
            if (result.error) return;
            result.scenarios.forEach((scenario, index) => {
                const card = element('article', 'commission-sim-card');
                card.append(element('h4', '', (index + 1) + '. ' + scenario.title), element('p', 'commission-sim-description', scenario.description));
                scenario.variants.forEach(distribution => {
                    const group = element('div', 'commission-sim-variant');
                    if (distribution.label) group.append(element('p', 'commission-sim-label', distribution.label));
                    const recipients = element('dl', 'commission-sim-recipients');
                    distribution.rows.forEach(recipient => {
                        const entry = element('div', 'commission-sim-recipient commission-sim-' + recipient.tone);
                        entry.append(element('dt', '', recipient.role), element('dd', 'commission-sim-amount', money(recipient.amount)), element('dd', 'commission-sim-source', recipient.source));
                        recipients.append(entry);
                    });
                    const valid = Math.abs(distribution.difference) <= 0.01;
                    const footer = element('div', 'commission-sim-validation');
                    footer.append(element('span', '', 'Total ' + money(distribution.total) + ' · Pool ' + money(result.pool)),
                        element('strong', valid ? 'commission-sim-valid' : 'commission-sim-invalid', valid ? 'Sesuai' : 'Tidak sesuai · Selisih ' + money(Math.abs(distribution.difference))));
                    group.append(recipients, footer);
                    card.append(group);
                });
                content.append(card);
            });
        }
        if (detailMode) {
            const showPreview = preview => {
                root.querySelector('[data-simulation-package]').textContent = preview.name || '';
                render(preview.values);
            };
            root.addEventListener('commission-preview', event => showPreview(event.detail));
            // A user may open the modal before this deferred script finishes loading.
            if (root.commissionPreview) showPreview(root.commissionPreview);
            return;
        }
        // Form's inline input handler updates its pool first. We only read its draft.
        inputs.forEach(input => input.addEventListener('input', render));
        inputs.slice(0, 3).forEach(input => {
            input.addEventListener('blur', () => {
                if (input.value.trim() === '') {
                    input.value = '0';
                    if (typeof calculateCommissionPool === 'function') calculateCommissionPool();
                    render();
                }
            });
        });
        inputs[0].form?.addEventListener('reset', () => setTimeout(render, 0));
        render();
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', mount);
    else mount();
})();
