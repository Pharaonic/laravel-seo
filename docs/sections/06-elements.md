## Elements

Each element renders one tag, or one tag per value for lists. Elements without a value render nothing.

| Element | Renders |
| --- | --- |
| `charset` | `<meta charset="...">` |
| `viewport` | `<meta name="viewport" content="...">` |
| `title` | `<title>...</title>` |
| `description` | `<meta name="description" content="...">` |
| `keywords` | `<meta name="keywords" content="a, b">` |
| `author` | `<meta name="author" content="...">` |
| `robots` | `<meta name="robots" content="...">` |
| `copyright` | `<meta name="copyright" content="...">` |
| `next` | `<link rel="next" href="...">` |
| `prev` | `<link rel="prev" href="...">` |
| `alternates` | `<link rel="alternate" hreflang="..." href="...">` per language |
| `images` | No tag of its own: the Open Graph and Twitter cards use it |
| `canonical` | `<link rel="canonical" href="...">` |

### Single Values

`set()` replaces the value:

```php
seo()->robots->set('noindex, nofollow');
```

### Lists

`keywords`, `images` and `alternates` hold lists. `set()` replaces the list, `add()` appends to it, and `remove()` drops one entry by its position.

```php
seo()->keywords->set(['laravel', 'seo']);
seo()->keywords->set('laravel, seo');      // the same list
seo()->keywords->add('meta tags');
seo()->keywords->remove(0);
seo()->keywords->get();                    // ["seo", "meta tags"]
```

`alternates` takes a language and a URL for each entry:

```php
seo()->alternates->add('en', url('en/about'));
seo()->alternates->add('ar', url('ar/about'));
```

```html title="Output" no-copy
<link rel="alternate" hreflang="en" href="https://example.com/en/about">
<link rel="alternate" hreflang="ar" href="https://example.com/ar/about">
```

### Pagination

Point search engines at the neighbouring pages of a paginator:

```php
$posts = Post::latest()->paginate();

seo()->prev->set((string) $posts->previousPageUrl());
seo()->next->set((string) $posts->nextPageUrl());
```

An empty string renders nothing, so the first and the last page get only one link.

:::warning Escaping
Every value is HTML-escaped when it is rendered. Pass plain text, not HTML.
:::
