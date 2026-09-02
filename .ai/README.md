# AI Baseline v1.0.0

> Portable engineering knowledge for AI-assisted product development.

| Field | Value |
| --- | --- |
| Baseline version | `1.0.0` |
| Created | 2026-08-25 |
| Last updated | 2026-08-25 |
| Purpose | Engineering starting point for new projects. Not a copy of any product domain. |

This folder is **AI-DOS**: a living documentation system for developers and AI coding assistants.

It is **not** a description of a finished product. After copy, the adopting project must fill project context from its own source code. See [`HOW_TO_ADAPT.md`](./HOW_TO_ADAPT.md).

Do not overwrite a project's `.ai/` with a newer baseline automatically. Adaptation is a deliberate, reviewed process.

---

# Purpose

* Keep AI grounded in documented architecture and constraints.
* Reduce hallucination and invented business rules.
* Keep backend and frontend implementation consistent.
* Record engineering decisions so they can be traced.
* Give every new project a reusable starting point.

---

# Core Principles

1. Documentation First
2. Business Driven Development
3. Consistency Over Cleverness
4. Simplicity First
5. Small Incremental Changes
6. Living Documentation
7. Single Source of Truth
8. Convention Over Configuration
9. Security by Default
10. Avoid Over-Engineering

---

# Source of Truth Hierarchy

Two tracks. Process docs do not override accepted product or architecture decisions.

**Process and safety:**

```text
AGENTS.md              → how the AI operates
RULES.md               → mandatory safety and quality constraints
```

**Product and architecture facts:**

```text
DECISION_LOG.md        → accepted project decisions (highest for facts)
knowledge/mvp-lock.md  → locked MVP brief
PROJECT.md             → product capabilities and domain context
architecture/          → structure (DEC-029)
TECH_STACK.md          → chosen technology
conventions/           → coding contracts
patterns/              → how to implement recurring shapes
playbooks/             → operational procedures
modules/               → domain documentation
knowledge/             → map of actual code vs intent
source code            → current behavior; does not redefine locked requirements
```

Conflict order for product/architecture: **Accepted Decision > locked PRD > architecture guideline > existing implementation**.

`README.md` is an index. It does not override `RULES.md` or accepted DECs.

---

# Documentation Structure

```text
.ai/                          ← after adaptation, this baseline lives here
├── README.md
├── HOW_TO_ADAPT.md
├── AGENTS.md
├── RULES.md
├── PROJECT.md
├── TECH_STACK.md
├── DECISION_LOG.md
├── GLOSSARY.md
├── CHANGELOG.md
├── architecture/
├── conventions/
├── patterns/
├── playbooks/
├── testing/
├── knowledge/
├── modules/
├── sprint/
└── memory/
```

---

# Reading Order

1. [`README.md`](./README.md) (this file)
2. [`HOW_TO_ADAPT.md`](./HOW_TO_ADAPT.md) if this is a new adoption
3. [`AGENTS.md`](./AGENTS.md)
4. [`PROJECT.md`](./PROJECT.md)
5. [`TECH_STACK.md`](./TECH_STACK.md)
6. [`architecture/README.md`](./architecture/README.md)
7. [`RULES.md`](./RULES.md)
8. [`knowledge/README.md`](./knowledge/README.md) before editing unfamiliar code
9. The module currently being changed
10. Related patterns and playbooks

---

# Documentation Workflow

Requirement → Planning → Documentation → Implementation → Testing → Verification → Documentation update → Done

Update documentation when a change affects business rules, API, database, UI flow, workflow, modules, architecture, or technical decisions.

---

# Definition of Done

A change is done only when:

* The requirement is met.
* Business rules match the agreed documentation.
* Backend and frontend work (when both are in scope).
* Tests pass.
* Quality gates required by the project pass.
* Documentation harvest is complete.
* No undocumented technical debt was introduced on purpose.

---

# Guiding Principle

Source code shows **how** the system works. Documentation explains **why** it was built that way. They must stay aligned.

This baseline is an engineering starting point. Project facts live in `PROJECT.md`, `TECH_STACK.md`, `modules/`, and `knowledge/` after adaptation.
