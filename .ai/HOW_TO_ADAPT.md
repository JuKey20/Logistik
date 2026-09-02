# How to Adapt This Baseline

> Copy this baseline into a **new** project, then replace placeholders with facts from that project's source code.

**This repository is already adapted** (baseline `1.0.0`, 2026-09-01). Do not re-run the placeholder workflow here. Follow [`DECISION_LOG.md`](./DECISION_LOG.md) (including DEC-029), [`PROJECT.md`](./PROJECT.md), and [`knowledge/mvp-lock.md`](./knowledge/mvp-lock.md).

Baseline version: `1.0.0`

This file is the primary operating guide for first-time adoption.

---

# What this baseline is

An engineering starting point: constraints, recommended architecture, reusable patterns, and AI workflow.

# What this baseline is not

* A description of a finished product
* A required technology stack
* A set of business rules
* A license to invent domain facts
* An automatic upgrade that may overwrite a project's `.ai/`

---

# Adopted baseline record

After copy, record the version in `PROJECT.md`:

```text
{{BASELINE_VERSION}}        → 1.0.0
{{BASELINE_ADOPTED_DATE}}   → YYYY-MM-DD
```

Later baseline releases must **not** be copied over project-specific `.ai/` without a manual review. Merge knowledge; do not overwrite domain documentation.

---

# Placeholder index

Fill these during adaptation. Until then they are unknown facts, not defaults.

| Placeholder | Canonical file |
| --- | --- |
| `{{BASELINE_VERSION}}` | PROJECT.md (set to `1.0.0` on first adoption) |
| `{{BASELINE_ADOPTED_DATE}}` | PROJECT.md |
| `{{PROJECT_NAME}}` | PROJECT.md |
| `{{APPLICATION_DOMAIN}}` | PROJECT.md |
| `{{PROJECT_PURPOSE}}` | PROJECT.md |
| `{{TARGET_USERS}}` | PROJECT.md |
| `{{MODULES}}` | PROJECT.md |
| `{{BUSINESS_CONTEXT}}` | PROJECT.md |
| `{{OUT_OF_SCOPE}}` | PROJECT.md |
| `{{SUCCESS_METRICS}}` | PROJECT.md |
| `{{NON_FUNCTIONAL_REQUIREMENTS}}` | PROJECT.md |
| `{{FRONTEND_STACK}}` | TECH_STACK.md |
| `{{BACKEND_STACK}}` | TECH_STACK.md |
| `{{DATABASE}}` | TECH_STACK.md |
| `{{AUTH_STACK}}` | TECH_STACK.md |
| `{{AUTHORIZATION_MODEL}}` | TECH_STACK.md |
| `{{DATA_FETCHING_STRATEGY}}` | TECH_STACK.md |
| `{{HTTP_CLIENT}}` | TECH_STACK.md |
| `{{LOCAL_RUNTIME}}` | TECH_STACK.md |
| `{{FIXED_DOMAIN_VALUES}}` | TECH_STACK.md |
| `{{NOTIFICATION_CHANNELS}}` | TECH_STACK.md |
| `{{QUALITY_GATE}}` | TECH_STACK.md |

Other files may *reference* these placeholders. They are not a second source of truth.

---

# Adaptation workflow

## 1. Copy baseline to the new project

Copy this folder to `.ai/` in the target repository.

Keep the existing application source code unchanged during copy.

## 2. Inspect source code

Read the actual application before filling templates:

* Entry points, routes, and folder layout
* Auth and authorization implementation
* Persistence and migrations
* Frontend feature layout
* Tests and quality gates

Do not assume the recommended stack is already in use.

## 3. Determine actual tech stack

Fill `TECH_STACK.md` from evidence in the code and lockfiles.

Mark technologies as **Required** only when the project has already chosen them. Recommendations from this baseline stay labeled **Recommended**.

## 4. Determine actual architecture

Compare the codebase to `architecture/`.

* If it matches a recommended pattern, keep the pattern and cite real files in `knowledge/`.
* If it differs, document the actual architecture. Do not rewrite the code to match the baseline unless the team asks.
* Significant deviations belong in `DECISION_LOG.md`.

## 5. Populate `PROJECT.md`

Replace every `{{NAME}}` placeholder with project facts. Do not leave marketing fiction. If a fact is unknown, write `Decision Required` and stop inventing it.

## 6. Populate `TECH_STACK.md`

Fill Project Technology. Leave Engineering Standard in place unless the project explicitly supersedes it.

## 7. Generate or update `knowledge/`

Map real folders and files. `knowledge/` follows the codebase, not the plan.

Record absences explicitly (for example: no job queue yet) so AI does not search for missing layers.

## 8. Generate `modules/`

Create a folder per implemented module. Use [`modules/_template/README.md`](./modules/_template/README.md) as a **recommended** documentation structure.

Do not create every template file for a simple module. Write only the sections that match the module's complexity.

## 9. Identify Decision Required

Anything that is still ambiguous — authorization model, HTTP client, notification channels, runtime — must be listed and asked. Do not guess.

Write accepted answers as `DEC-XXX` in `DECISION_LOG.md`. Numbering starts at `DEC-001` for this project.

## 10. Verify documentation against actual code

If documentation and code disagree, the code is the current behavior. Update the documentation, or change the code only when the team intends to.

## 11. Final consistency and security audit

Before treating adaptation as complete:

* No leftover placeholders that should have been filled
* No secrets, credentials, PII, or private hostnames in `.ai/`
* Internal documentation links resolve
* No duplicate or contradictory source of truth
* `{{BASELINE_VERSION}}` is recorded

---

# Rules for AI during adaptation

* Baseline is a starting point, not project fact.
* Do not invent business rules.
* Do not copy examples from another product domain into this project.
* Do not add dependencies only because they appear as Recommended.
* Do not treat Action, Repository, or Notification as mandatory **during first-time copy of this baseline into an unrelated project**. In **this** repo they are already decided: Action **Required** (DEC-022), Repository **not used** (DEC-023), Notification **out of MVP** (DEC-010).
* Stop and ask when evidence is missing.

---

# After adaptation

Day-to-day work follows [`AGENTS.md`](./AGENTS.md) and [`RULES.md`](./RULES.md).

This file remains as the adoption playbook. It is not replaced by module documentation.
