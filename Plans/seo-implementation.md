# SEO Implementation Plan for Reliquary

Based on the `remove-base-template.md` plan, SEO should be implemented in `landing_base.html.twig` (the new primary template) and `admin_base.html.twig`.

---

## Current State of `landing_base.html.twig`

**Present:**
- ✅ `lang` attribute on `<html>` tag
- ✅ `charset` and `viewport` meta tags
- ✅ Dynamic `<title>` block
- ✅ Favicon

**Missing:**
- ❌ Meta description
- ❌ Open Graph tags
- ❌ Twitter Card tags
- ❌ Canonical URL
- ❌ Structured data (JSON-LD)
- ❌ Robots meta tag
- ❌ Hreflang tags

---

## Implementation Steps

### Step 1: Add SEO Meta Blocks to `landing_base.html.twig`
- [x] Add after line 6 (after `<title>`):
```twig
{% block meta_description %}<meta name="description" content="{{ 'meta.description'|trans({}, 'landing') }}">{% endblock %}
{% block meta_robots %}<meta name="robots" content="index, follow">{% endblock %}
{% block canonical %}{% endblock %}
```

### Step 2: Add Open Graph Tags
- [x] Add Open Graph meta tags:
```twig
{% block og_tags %}
<meta property="og:title" content="{% block og_title %}{{ block('title') }}{% endblock %}">
<meta property="og:description" content="{% block og_description %}{{ 'meta.description'|trans({}, 'landing') }}{% endblock %}">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ app.request.uri }}">
<meta property="og:image" content="{% block og_image %}{{ absolute_url(asset('/images/relic.png')) }}{% endblock %}">
<meta property="og:site_name" content="Reliquary">
<meta property="og:locale" content="{{ app.request.locale }}">
{% endblock %}
```

### Step 3: Add Twitter Card Tags
- [x] Add Twitter Card meta tags:
```twig
{% block twitter_tags %}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ block('og_title') }}">
<meta name="twitter:description" content="{{ block('og_description') }}">
<meta name="twitter:image" content="{{ block('og_image') }}">
{% endblock %}
```

### Step 4: Add Structured Data Block
- [x] Add JSON-LD structured data with SearchAction and SiteNavigationElement for sitelinks:
```twig
{% block structured_data %}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "Reliquary",
    "url": "{{ url('app_home') }}",
    "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ url('app_home') }}search?q={search_term_string}",
        "query-input": "required name=search_term_string"
    }
}
</script>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "SiteNavigationElement",
    "name": ["Relíquias", "Santos", "Locais", "Sobre"],
    "url": [
        "{{ url('app_relic_index') }}",
        "{{ url('app_saint_index') }}",
        "{{ url('app_location_index') }}",
        "{{ url('app_about') }}"
    ]
}
</script>
{% endblock %}
```

### Step 5: Add Hreflang Tags Block
- [x] Add hreflang block for multilingual support:
```twig
{% block hreflang %}{% endblock %}
```

### Step 6: Add Translations
- [x] Add to `translations/landing.en.yaml`:
```yaml
meta:
    description: "Discover and explore religious relics and saints from around the world. A comprehensive database of sacred artifacts and their histories."
```
- [x] Add translations for other supported languages (pt, es, it, etc.)

### Step 7: Override SEO in Child Templates
- [x] Update `relic/show.html.twig`:
```twig
{% block meta_description %}<meta name="description" content="{{ relic.description|slice(0, 160) }}">{% endblock %}
{% block og_title %}{{ relic.name }} - Reliquary{% endblock %}
{% block og_description %}{{ relic.description|slice(0, 200) }}{% endblock %}
{% block og_image %}{% if relic.images|length > 0 %}{{ absolute_url(image_url(relic.images|first.filename)) }}{% else %}{{ parent() }}{% endif %}{% endblock %}
{% block structured_data %}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Thing",
    "name": "{{ relic.name }}",
    "description": "{{ relic.description|slice(0, 200) }}"
}
</script>
{% endblock %}
```

- [x] Update `saint/show.html.twig` with similar overrides

### Step 8: Create/Update `robots.txt` and `sitemap.xml`
- [x] Ensure `public/robots.txt` exists with proper directives
- [x] Consider adding a sitemap generator command or bundle (e.g., `presta/sitemap-bundle`)

---

## Priority Order

| Priority | Task | Impact |
|----------|------|--------|
| **High** | Meta description + Open Graph | Immediate SEO/social impact |
| **Medium** | Twitter Cards + Canonical URLs | Social sharing + duplicate prevention |
| **Lower** | Structured data + Hreflang + Sitemap | Rich snippets + i18n SEO |

---

## Testing Checklist

- [x] Validate meta tags with browser dev tools
- [x] Test Open Graph with [Facebook Sharing Debugger](https://developers.facebook.com/tools/debug/)
- [x] Test Twitter Cards with [Twitter Card Validator](https://cards-dev.twitter.com/validator)
- [x] Validate structured data with [Google Rich Results Test](https://search.google.com/test/rich-results)
- [x] Check robots.txt accessibility
- [x] Verify sitemap.xml is valid and accessible (Pending automated generator)
