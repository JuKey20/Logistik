# Financial column types

Money is **whole Rupiah** (no sen). In migrations, financial columns **must** be `BIGINT` or `DECIMAL(p, 0)`.

**Never** use `FLOAT` or `DOUBLE` for money.

PHP must not use `float` for these amounts. Details: [`../conventions/money.md`](../conventions/money.md), DEC-019.
