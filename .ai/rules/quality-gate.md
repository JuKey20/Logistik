# Quality gate invocation

The project quality gate is **`composer ci:check`**. That command is the single source of truth (DEC-011, `TECH_STACK.md`).

CI/CD workflows must run that one command after a hybrid PHP + Node.js setup. Do not split the gate into `npm run check`, `npm run types:check`, `composer test`, Pint, PHPStan, or Pest as separate CI steps.

A PHP-only or Node-only job is not a complete quality gate.
