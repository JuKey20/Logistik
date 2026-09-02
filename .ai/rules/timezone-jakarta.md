# Timezone: Asia/Jakarta

`config/app.php` **`timezone` = `Asia/Jakarta`** is the **single source of truth** (DEC-026).

Use it for Eloquent `created_at`/`updated_at`, scheduling, audit timestamps, exports, and UI.

Do **not** mix UTC (PHP default, MySQL session, or Carbon `utc()`) with Jakarta wall time. That 7-hour skew is a defect.

Implementation must change `'timezone' => 'UTC'` in `config/app.php` (not done in the `.ai/` sandbox pass).
