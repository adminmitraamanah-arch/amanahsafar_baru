# AGENTS.md

This file provides guidance to AI coding agents (Antigravity, Claude Code, Cursor, Copilot, etc.) working on this project (`amanahsafar`).

Before working on this project, read and follow [rules.md](rules.md) for scope limits, evidence-based changes, required local commits, and explicit user confirmation before every GitHub push or pull. For any user interface (UI/UX) creation, modification, or design tasks, read and strictly follow [rules_ui.md](rules_ui.md). Apply skills within these boundaries.


Skills from [addyosmani/agent-skills](https://github.com/addyosmani/agent-skills) and [nextlevelbuilder/ui-ux-pro-max-skill](https://github.com/nextlevelbuilder/ui-ux-pro-max-skill) are installed in `.agents/skills/`.

## OpenCode Integration

OpenCode uses a **skill-driven execution model** powered by the `skill` tool and this repository's `/skills` directory.

### Core Rules

- If a task matches a skill, you MUST invoke it
- Skills are located in `.agents/skills/<skill-name>/SKILL.md`
- Never implement directly if a skill applies
- Always follow the skill instructions exactly (do not partially apply them)

### Intent → Skill Mapping

The agent should automatically map user intent to skills:

- **Requirements & Ideation**:
  - Unclear / underspecified requests → `interview-me`
  - Ideation, brainstorming & concept divergence/convergence → `idea-refine`
  - Meta-skill discovery & orchestration → `using-agent-skills`
  - Context setup & agent rule configuration → `context-engineering`

- **Architecture, Planning & Specs**:
  - Feature / new functionality PRD & specifications → `spec-driven-development`
  - Planning / task breakdown & sequencing → `planning-and-task-breakdown`
  - Quality thresholds & project constraints contract → `constraint-driven-development`
  - Stress-testing plans & adversarial cross-examination → `doubt-driven-development`
  - API, schema, or interface design → `api-and-interface-design`
  - Architecture decisions & ADR documentation → `documentation-and-adrs`

- **Implementation & Code Construction**:
  - Official documentation verification & source-backed coding → `source-driven-development`
  - Step-by-step implementation in thin vertical slices → `incremental-implementation`
  - Test-driven development (Red-Green-Refactor) → `test-driven-development`
  - Database schema migrations, sunsetting & deprecations → `deprecation-and-migration`

- **UI/UX, Styling & Frontend**:
  - UI/UX intelligence, color palettes, fonts & UX guidelines → `ui-ux-pro-max`
  - Utility styling & component implementation (Tailwind CSS, shadcn) → `ui-styling`
  - Accessible, responsive frontend implementation → `frontend-ui-engineering`
  - Design tokens & systematic specifications → `design-system`

- **Testing, Quality & Debugging**:
  - Bug / failure / unexpected behavior → `debugging-and-error-recovery`
  - Real browser DOM, console & network testing → `browser-testing-with-devtools`
  - Multi-axis code review before merging → `code-review-and-quality`
  - Refactoring & simplification for clarity → `code-simplification`

- **Security, Performance & Operations (DevOps)**:
  - Security audit, OWASP hardening & auth safety → `security-and-hardening`
  - Performance optimization (queries, frontend, backend) → `performance-optimization`
  - Logging, metrics, tracing & observability → `observability-and-instrumentation`
  - Git branching, atomic commits, releases & changelogs → `git-workflow-and-versioning`
  - CI/CD pipeline & automated build setup → `ci-cd-and-automation`
  - Production readiness, deployment & rollback planning → `shipping-and-launch`

### Lifecycle Mapping (Implicit Commands)

OpenCode and agent systems follow this lifecycle:

- IDEATE & INTERVIEW → `interview-me`, `idea-refine`, `using-agent-skills`
- DEFINE → `spec-driven-development`, `constraint-driven-development`
- PLAN → `planning-and-task-breakdown`, `api-and-interface-design`, `doubt-driven-development`
- BUILD → `source-driven-development` + `incremental-implementation` + `test-driven-development` (+ UI/UX skills for frontend)
- VERIFY → `debugging-and-error-recovery`, `browser-testing-with-devtools`, `security-and-hardening`, `performance-optimization`
- REVIEW → `code-review-and-quality`, `code-simplification`, `documentation-and-adrs`
- SHIP → `git-workflow-and-versioning`, `ci-cd-and-automation`, `shipping-and-launch`

### Execution Model

For every request:

1. Determine if any skill applies (even 1% chance)
2. Invoke the appropriate skill using the `skill` tool
3. Follow the skill workflow strictly
4. Only proceed to implementation after required steps (spec, plan, etc.) are complete

### Anti-Rationalization

The following thoughts are incorrect and must be ignored:

- "This is too small for a skill"
- "I can just quickly implement this"
- "I’ll gather context first"

Correct behavior:

- Always check for and use skills first

This ensures OpenCode behaves similarly to Claude Code with full workflow enforcement.

## Orchestration: Personas, Skills, and Commands

This repo has three composable layers. They have different jobs and should not be confused:

- **Skills** (`skills/<name>/SKILL.md`) — workflows with steps and exit criteria. The *how*. Mandatory hops when an intent matches.
- **Personas** (`agents/<role>.md`) — roles with a perspective and an output format. The *who*.
- **Slash commands** (`.claude/commands/*.md`) — user-facing entry points. The *when*. The orchestration layer.

Composition rule: **the user (or a slash command) is the orchestrator. Personas do not invoke other personas.** A persona may invoke skills.

The only multi-persona orchestration pattern this repo endorses is **parallel fan-out with a merge step** — used by `/ship` to run `code-reviewer`, `security-auditor`, and `test-engineer` concurrently and synthesize their reports. Do not build a "router" persona that decides which other persona to call; that's the job of slash commands and intent mapping.

See [docs/agents.md](docs/agents.md) for the decision matrix and [references/orchestration-patterns.md](references/orchestration-patterns.md) for the full pattern catalog.

**Claude Code interop:** the personas in `agents/` work as Claude Code subagents (auto-discovered from this plugin's `agents/` directory) and as Agent Teams teammates (referenced by name when spawning). Two platform constraints align with our rules: subagents cannot spawn other subagents, and teams cannot nest. Plugin agents silently ignore the `hooks`, `mcpServers`, and `permissionMode` frontmatter fields.

## Creating a New Skill

> **Before you start:** run the pre-flight checks in [CONTRIBUTING.md](CONTRIBUTING.md#before-proposing-a-new-skill), search the catalog, check open PRs (`gh pr list --state open`), confirm the idea fits [docs/skill-anatomy.md](docs/skill-anatomy.md), and justify the gap in your PR description. Most new-skill ideas overlap an existing skill or an open PR; prefer extending an existing skill over adding a near-duplicate. CONTRIBUTING.md is the single source of truth for this workflow.

Skills in this repo are markdown-first: each lives at `skills/<kebab-case-name>/SKILL.md` with YAML frontmatter (`name`, `description`) and follows the section anatomy (Overview, When to Use, Process, Common Rationalizations, Red Flags, Verification). Add a `scripts/` directory only when the skill ships runnable helpers; most skills are markdown only, and there are no per-skill zip packages.

For the full format, naming conventions, frontmatter rules, supporting-file thresholds, and writing principles, see [docs/skill-anatomy.md](docs/skill-anatomy.md), the single source of truth for skill structure. Do not restate that guidance here, link to it.
