## Open Graph & Twitter

Both cards reuse the page's `title`, `description` and `images`, so you set them once.

### Open Graph

`seo()->openGraph` renders, in this order:

| Tag | Value |
| --- | --- |
| `og:url` | The current URL |
| `og:locale` | The application locale |
| `og:locale:alternate` | Each `alternates` language, with dots replaced by underscores |
| `og:type` | `setType()`, `website` by default |
| `og:site_name` | `setSiteName()` |
| `og:title` | The `title` element |
| `og:description` | The `description` element |
| `og:image` | Each entry of `images` |

```php
seo()->openGraph->setType('article')->setSiteName('Pharaonic');
seo()->openGraph->getType();       // "article"
```

### Twitter

`seo()->twitter` renders `twitter:card`, `twitter:site`, `twitter:creator`, `twitter:title`, `twitter:description` and `twitter:image`. A Twitter card takes one image, so it uses the first entry of `images`.

```php
seo()->twitter->setCard('summary_large_image')
    ->setSite('pharaonic')
    ->setCreator('@raggi');

seo()->twitter->getSite();         // "@pharaonic"
seo()->twitter->getCreator();      // "@raggi"
```

Usernames get exactly one `@`, whether you pass it or not. A getter returns `null` until its value is set.
