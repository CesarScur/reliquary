# Plan: Landing Page Relic Section Redesign

## Overview
Replace the static "Relic Classes" section with two new dynamic features:
1. **Featured Relics Showcase** - Display highlighted relics with images
2. **Statistics Dashboard** - Show collection metrics

## Current State
- Location: `templates/home/landing.html.twig` (lines 30-57)
- Static content explaining First, Second, Third class relics
- Links to filtered relic index pages

---

## Feature 1: Featured Relics Showcase
- [x] Implementation: Instead of featured relics, a **Featured Saints** carousel was implemented, which includes the **Saint of the Day**.

### Backend Changes

#### 1.1 Update HomeController
- [x] File: `src/Controller/HomeController.php`
- [x] Add query to fetch featured/recent saints.
- [x] Pass `featuredSaints` and `saintOfDay` variables to template.

#### 1.2 Optional: Add Featured Flag to Relic Entity
- [x] Implemented `is_featured` on `Saint` entity instead.

### Frontend Changes

#### 1.3 Update Landing Template
- [x] File: `templates/home/landing.html.twig`
- [x] Added `featured-saints` section with carousel.

#### 1.4 Add Styles
- [x] Added carousel and card styles.

#### 1.5 Add Translations
- [x] Added keys for landing page.

---

## Feature 2: Statistics Dashboard

### Backend Changes

#### 2.1 Create Statistics Service (Optional)
- [ ] File: `src/Service/StatisticsService.php`
- [ ] Methods to calculate and cache stats
- [ ] Consider caching for performance

#### 2.2 Update HomeController
- [ ] Add statistics queries:
  - [ ] Total approved relics count
  - [ ] Total saints with relics count
  - [ ] Total countries/locations count
  - [ ] Total contributors count (optional)
- [ ] Pass `stats` array to template

#### 2.3 Add Repository Methods
- [ ] File: `src/Repository/RelicRepository.php`
  - [ ] `countApproved(): int`
  - [ ] `countDistinctCountries(): int`
- [ ] File: `src/Repository/SaintRepository.php`
  - [ ] `countWithRelics(): int`

### Frontend Changes

#### 2.4 Update Landing Template
- [ ] Add statistics section (can be above or below featured relics)

#### 2.5 Add Styles
- [ ] Style `.stats-section` with prominent numbers
- [ ] Consider animated counter effect (optional JS)

#### 2.6 Add Translations
- [ ] Add keys: `stats.relics`, `stats.saints`, `stats.countries`

---

## Implementation Order

1. **Phase 1: Statistics Dashboard** (simpler, no new entities)
   - Add repository count methods
   - Update controller
   - Add template section and styles
   - Add translations

2. **Phase 2: Featured Relics Showcase**
   - Update controller with relic query
   - Add template section and styles
   - Add translations

3. **Phase 3: Polish & Optional Enhancements**
   - Add caching for statistics
   - Add `isFeatured` flag for manual curation
   - Add animated counters
   - A/B test placement (above/below hero)

---

## Testing Checklist

- [ ] Statistics display correct counts
- [ ] Featured relics show with images
- [ ] Links navigate to correct relic/saint pages
- [ ] Responsive design works on mobile
- [ ] Translations work for all supported locales
- [ ] Performance acceptable (consider caching if slow)

---

## Decision: Keep or Remove Relic Classes?

**Options:**
1. **Replace entirely** - Remove relic classes section, use space for new features
2. **Move to footer or separate page** - Keep educational content accessible elsewhere
3. **Combine** - Show stats inline with relic class cards (e.g., "First Class - 234 relics")

**Recommendation:** Option 3 - Combine stats with relic classes for a hybrid approach that's both educational and dynamic.

[Update 2024]: The Relic Classes section was kept as educational entry points. Feature 1 (Featured Saints) is already implemented in the landing page carousel. Statistics Dashboard is still pending.
