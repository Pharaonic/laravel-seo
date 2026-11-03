---
name: SEO

action:
  label: View on Packagist
  href: "{package.packagistUrl}"

views: components.packages

breadcrumbs:
  - label: Home
    href: route:home
  - label: Packages
    href: route:packages.index
  - label: "{technology.name} Packages"
    href: "url:/packages/{technology.slug}"
  - label: "{package.name}"

card:
  topic: seo
  icon: search
  tags: seo meta tags open-graph twitter-cards canonical hreflang blade eloquent
  description: Titles, meta tags, canonical and alternate links, Open Graph and Twitter cards for Laravel, set from controllers, Eloquent models or Blade and printed with one directive.

seo:
  title: "{package.fullName} - Meta Tags, Open Graph and Twitter Cards"
  description: "{package.name} is a Laravel package for page titles, meta tags, canonical and hreflang links, Open Graph and Twitter cards. {package.downloadsShort}+ downloads, {package.license} licensed."
  keywords: laravel seo, meta tags, open graph, twitter cards, canonical, hreflang, blade
  author: Pharaonic
  images:
    - "{package.cover}"
  openGraph:
    type: website
    siteName: Pharaonic
  twitter:
    card: summary_large_image

schema:
  "@type": SoftwareSourceCode
  name: "{package.name}"
  description: "{package.name} is a Laravel package for page titles, meta tags, canonical and hreflang links, Open Graph and Twitter cards."
  image: "{package.cover}"
  codeRepository: "{package.githubUrl}"
  programmingLanguage: PHP
  runtimePlatform: "{technology.name}"
  version: "{package.version}"
  datePublished: "{package.publishedAt}"
  dateModified: "{package.updatedAt}"
  license: "https://opensource.org/licenses/{package.license}"
  isAccessibleForFree: true
  sameAs:
    - "{package.githubUrl}"
    - "{package.packagistUrl}"
  author:
    "@id": url:/#organization
  publisher:
    "@id": url:/#organization
  interactionStatistic:
    "@type": InteractionCounter
    interactionType: https://schema.org/DownloadAction
    userInteractionCount: "{package.downloads}"
---
