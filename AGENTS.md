# skeeks-brand package development

Shared brand layer of SkeekS product sites. Read README.md first.

## Boundaries

- This is a layer, not a theme. Do not add a Theme class, do not extend or require
  `cms-theme-unify-v2`/`theme-unify-shop`, jQuery or Bootstrap. Sites keep their own
  themes and plug the layer in.
- Keep it light: every page pays for what a bundle registers. Each feature has its own
  small AssetBundle registered by its widget or only on pages that use it. Do not
  create a global "brand.css/brand.js" that every page loads.
- Add a component here only when at least two product sites use the same markup and
  behavior. Site navigation, texts, page layouts and page-only styles stay in projects;
  shared page blocks, tiles and the hero scene live here (README "Блоки страниц").
- Block class names are a contract with CMS texts (description_full via REST): never
  rename or drop one without migrating those texts.
- `Products` is the single source of product names, links and accents. A product
  without a confirmed URL has no link; never invent URLs.
- Reference look and behavior: skeeks-platform.ru (`common/themes/platform`).
- The site shell (`SiteHeader` + `ShellAsset`) owns header, search, drawer and the product
  mark. Sites pass data and may override tokens in their own `:root`; package tokens are
  declared with `:where(:root)` so they never win. Do not copy shell CSS back into a site.
- The footer (`SiteFooter` + `FooterAsset`) also owns the cookie notice look; the notice markup belongs to skeeks/cms LegalComponent, so only CSS lives here.

## Assets

- `brand/favicons` and `brand/logos` are the canonical brand files with their
  generators; their READMEs own the rules. Generators write only into their folder.
- Favicons are installed through the standard CMS site setting, not hard-coded
  links in layouts (brand/favicons/README.md).

## Verification

Check every consumer site locally at 320, 390, 768, 1024 and 1440 px: no overlap,
no horizontal scroll, no JS errors, keyboard and Escape behavior, no focus ring
after a mouse click.
