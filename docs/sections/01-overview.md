:::badges
- Laravel Package {color=blue}
- {release.label} {color=green}
- {package.license} License {color=purple}
:::

# SEO

SEO gives every page of your Laravel application its title, meta tags, canonical and alternate links, Open Graph and Twitter cards. Set them from a controller, from an Eloquent model or from a Blade view, then print them all with one `@seo` directive in your layout.

:::features
### Meta Tags {icon="code"}
Charset, viewport, title, description, keywords, author, robots and copyright.

### Links {icon="globe"}
Canonical, next and previous pages, and alternate languages with hreflang.

### Social Cards {icon="share"}
Open Graph and Twitter cards built from the same title, description and images.

### Eloquent Models {icon="database"}
A model returns its SEO data once, and one call applies it to the page.

### Blade Directives {icon="code-brackets"}
Set any tag from a view, and render everything with a single directive.

### Escaped Output {icon="shield-check"}
Every value is HTML-escaped, so content from users is safe to use.
:::

:::info Quick Tip
Set the values that rarely change, such as the charset, the viewport and the Twitter site, once in the configuration file.
:::
