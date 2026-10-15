<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\CommissionModel;
use App\Models\WithdrawalModel;
use App\Models\UserModel;

class Commission extends BaseController
{
    protected $commissionModel;
    protected $withdrawalModel;
    protected $userModel;

    public function __construct()
    {
        $this->commissionModel = new CommissionModel();
        $this->withdrawalModel = new WithdrawalModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if (!session('logged_in')) {
            return redirect()->to('/login');
        }

        $userId = (int)session('user_id');
        $db = \Config\Database::connect();

        // Saldo Potensi & Saldo Siap Cair
        $potensiKomisi = $this->commissionModel->getPotensiKomisiByUser($userId);
        $saldoSiapCair = $this->commissionModel->getSaldoSiapCairByUser($userId);

        // Akumulasi total komisi yang telah sukses dicairkan
        $ditarikRow = $db->table('withdrawals')
            ->where('user_id', $userId)
            ->where('status', 'selesai')
            ->selectSum('nominal')
            ->get()
            ->getRowArray();
        $totalDitarik = (float)($ditarikRow['nominal'] ?? 0);

        // Akumulasi penarikan yang sedang antri proses
        $pendingWdRow = $db->table('withdrawals')
            ->where('user_id', $userId)
            ->where('status', 'menunggu_transfer')
            ->selectSum('nominal')
            ->get()
            ->getRowArray();
        $totalPendingWd = (float)($pendingWdRow['nominal'] ?? 0);

        // Ambil data rekening bank dari verifikasi terbaru
        $verification = $db->table('verifications')
            ->where('user_id', $userId)
            ->orderBy('id', 'DESC')
            ->get()
            ->getRowArray();

        // Mutasi Ledger Buku Bank
        $filterStatus = $this->request->getGet('status') ?? 'all';
        $mutasi = $this->commissionModel->getMutasiLedgerByUser($userId, $filterStatus);

        // Riwayat Penarikan Dana
        $withdrawals = $this->withdrawalModel->getByUser($userId);

        $data = [
            'title' => 'Buku Mutasi & Komisi',
            'potensi_komisi' => $potensiKomisi,
            'saldo_siap_cair' => $saldoSiapCair,
            'total_ditarik' => $totalDitarik,
            'total_pending_wd' => $totalPendingWd,
            'bank_info' => $verification,
            'filter_status' => $filterStatus,
            'mutasi' => $mutasi,
            'withdrawals' => $withdrawals,
        ];

        return view('member/commissions', $data);
    }

    public function withdraw()
    {
        if (!session('logged_in')) {
            return redirect()->to('/login');
        }

        if (!$this->request->is('post')) {
            return redirect()->to('/app/commissions');
        }

        $userId = (int)session('user_id');
        $nominalStr = (string)$this->request->getPost('nominal');
        $nominal = (float)str_replace(['.', ','], ['', '.'], $nominalStr);

        $saldoSiapCair = $this->commissionModel->getSaldoSiapCairByUser($userId);

        if ($nominal <= 0) {
            return redirect()->back()->with('error', 'Nominal penarikan tidak valid.');
        }

        if ($nominal < 50000) {
            return redirect()->back()->with('error', 'Batas minimal penarikan komisi adalah Rp 50.000.');
        }

        if ($nominal > $saldoSiapCair) {
            return redirect()->back()->with('error', 'Nominal penarikan (Rp ' . number_format($nominal, 0, ',', '.') . ') melebihi saldo komisi siap cair Anda (Rp ' . number_format($saldoSiapCair, 0, ',', '.') . ').');
        }

        $namaBank = trim((string)$this->request->getPost('nama_bank'));
        $nomorRekening = trim((string)$this->request->getPost('nomor_rekening'));
        $namaPemilik = trim((string)$this->request->getPost('nama_pemilik_rekening'));

        if (empty($namaBank) || empty($nomorRekening) || empty($namaPemilik)) {
            return redirect()->back()->with('error', 'Informasi rekening bank tujuan penarikan wajib dilengkapi.');
        }

        $noPenarikan = $this->withdrawalModel->generateWithdrawalNumber();

        $this->withdrawalModel->insert([
            'no_penarikan' => $noPenarikan,
            'user_id' => $userId,
            'nominal' => $nominal,
            'nama_bank' => $namaBank,
            'nomor_rekening' => $nomorRekening,
            'nama_pemilik_rekening' => $namaPemilik,
            'status' => 'menunggu_transfer',
        ]);

        return redirect()->to('/app/commissions')->with('success', 'Permintaan penarikan komisi ' . $noPenarikan . ' sebesar Rp ' . number_format($nominal, 0, ',', '.') . ' berhasil dikirim. Admin akan segera memproses transfer ke rekening Anda.');
    }
}
