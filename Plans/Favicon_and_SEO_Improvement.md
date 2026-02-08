# Favicon and SEO Improvement Plan

This plan outlines the steps to align the Reliquary project with Google's favicon best practices and resolve indexing issues.

## 1. Current Issues
- **Crawler Blockage**: `public/robots.txt` currently disallows all crawlers, preventing Google from fetching the favicon and site metadata.
- **Favicon Compliance**: The current implementation uses a single high-resolution PNG. Google recommends specific sizes and formats for optimal display across devices and search results.
- **Indexing**: Search results show "No information available" due to robots.txt restrictions.

## 2. Proposed Changes

### A. Update `public/robots.txt`
Change the policy to allow Googlebot and other crawlers to index the site.
- **Action**: Update `public/robots.txt` to `Allow: /`.

### B. Favicon Assets Generation
Google's guidelines for favicons:
- The favicon must be a multiple of 48px square (e.g., 48x48, 96x96, 144x144).
- SVG files are also supported and recommended for scalability.
- Provide a `manifest.webmanifest` for PWA and Android support.
- Provide an `apple-touch-icon` for iOS (180x180).

**Required Sizes:**
- `favicon.ico` (contains 16x16, 32x32, 48x48)
- `favicon-32x32.png`
- `favicon-16x16.png`
- `apple-touch-icon.png` (180x180)
- `android-chrome-192x192.png`
- `android-chrome-512x512.png`

### C. Update Metadata in `templates/base.html.twig`
Update the `<head>` section to include all relevant relations.

```html
<!-- Favicons -->
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
<link rel="manifest" href="{{ asset('site.webmanifest') }}">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="theme-color" content="#ffffff">
```

## 3. Implementation Steps

1.  [x] **Modify `public/robots.txt`**:
    - [x] Remove `Disallow: /` and replace with `Allow: /`.
2.  [x] **Generate Assets**:
    - [x] Use `assets/images/relic.png` as the source to generate the various sizes.
    - [x] Place generated files in `public/` (or manage via AssetMapper if preferred, but root-level files are better for crawler discovery).
3.  [x] **Create `public/site.webmanifest`**:
    - [x] Define app name, icons, and theme colors.
4.  [x] **Update `templates/base.html.twig`** (now `templates/landing_base.html.twig`):
    - [x] Replace the current single icon link with the comprehensive list.
5.  [x] **Verification**:
    - [x] Use Google Search Console (if available) to request a re-crawl.
    - [x] Verify that `https://santasreliquias.com.br/favicon.ico` is accessible.

## 4. Google's Specific Requirements Checklist
- [x] Favicon is a multiple of 48px square.
- [x] Favicon URL is stable (don't change the URL frequently).
- [x] Favicon is not inappropriate (pornography, hate symbols, etc.).
- [x] Favicon is representative of the brand.
