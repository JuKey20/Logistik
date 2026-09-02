# Money

> Whole Rupiah only. Decision: DEC-019.

## Domain

Harga kesepakatan, nominal aktual, bonus/tip tim (and any other financial amount) are **integers without sen**. Unit: Rupiah.

## Schema

* Allowed: `BIGINT` (preferred) or `DECIMAL(p, 0)`
* **Forbidden:** `FLOAT`, `DOUBLE`, any floating binary type, `DECIMAL` with scale other than 0

Choose a type that will not overflow large B2B values. Do not use PHP `int` 32-bit assumptions in the database — the column is `BIGINT`.

## Application

* Cast and calculate as integers
* Do not convert through `float` / `double` in PHP or in JS `number` math that can lose integer precision for large B2B totals
