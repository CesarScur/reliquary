# Plan: Post-Launch SEO & UI Refinements

This plan covers necessary items that were noted as pending but are critical for a production-ready application.

## 1. Favicon Asset Generation [HIGH PRIORITY]
The metadata links and webmanifest are in place, but the physical files are missing or incomplete in the `public/` directory.
- [ ] Generate standard favicon assets from `assets/images/relic.png`:
    - `public/favicon.ico` (multi-resolution)
    - `public/favicon-32x32.png`
    - `public/favicon-16x16.png`
    - `public/apple-touch-icon.png` (180x180)
    - `public/android-chrome-192x192.png`
    - `public/android-chrome-512x512.png`
- [ ] Verify they are correctly served and recognized by browsers.

## 2. Automated Sitemap Generation [HIGH PRIORITY]
A sitemap is essential for search engines to discover all relics and saints.
- [ ] Install `presta/sitemap-bundle`: `docker compose exec app composer require presta/sitemap-bundle`.
- [ ] Configure the bundle in `config/packages/presta_sitemap.yaml`.
- [ ] Implement a `SitemapListener` to add all approved Relics and non-incomplete Saints to the sitemap.
- [ ] Set up a cron job or manual command to regenerate the sitemap: `bin/console presta:sitemap:dump`.

## 3. Statistics Dashboard [MEDIUM PRIORITY]
Provide dynamic metrics on the landing page.
- [ ] Implement `App\Service\StatisticsService` to calculate:
    - Total approved relics
    - Total saints with relics
    - Total countries/locations
- [ ] Integrate these stats into `HomeController::landing`.
- [ ] Display the stats on the landing page (e.g., in the Relic Classes section).

## 4. Enhanced Multi-language Metadata [MEDIUM PRIORITY]
Ensure all SEO tags are fully localized.
- [ ] Add `hreflang` tags to `landing_base.html.twig` for all supported locales.
- [ ] Verify that `og:locale` and `twitter` tags are correctly translated.
