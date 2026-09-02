# Indonesian only (MVP)

UI copy, validation messages, auth messages, and date/time localization are **Bahasa Indonesia only**. No language switcher (DEC-025).

When implementing (source, not this `.ai/` pass):

* `APP_LOCALE=id` and `APP_FALLBACK_LOCALE=id` in `.env` / `.env.example`
* `config/app.php` defaults for `locale` and `fallback_locale` must be `id`
* Publish/use Laravel `id` language files so framework strings are Indonesian

Do not add English product strings in `resources/js`. Do not add an i18n toggle.
