## Blade Directives

Every element has a directive that sets it, and list elements have a second one that adds to them. `@seo` prints all the tags.

```blade title="resources/views/posts/show.blade.php"
@extends('layouts.app')

@seoTitle($post->title)
@seoDescription($post->excerpt)
@seoImage($post->cover_url)
@seoOpenGraphType('article')

@section('content')
    ...
@endsection
```

A child view runs before its layout, so values set in the view are ready when `@seo` renders in the layout's `<head>`.

| Directive | Calls |
| --- | --- |
| `@seo` | Prints every tag |
| `@seoCharset(...)` | `seo()->charset->set(...)` |
| `@seoViewport(...)` | `seo()->viewport->set(...)` |
| `@seoTitle(...)` | `seo()->title->set(...)` |
| `@seoDescription(...)` | `seo()->description->set(...)` |
| `@seoKeywords(...)` / `@seoKeyword(...)` | `seo()->keywords->set(...)` / `add(...)` |
| `@seoAuthor(...)` | `seo()->author->set(...)` |
| `@seoRobots(...)` | `seo()->robots->set(...)` |
| `@seoCopyright(...)` | `seo()->copyright->set(...)` |
| `@seoNext(...)` / `@seoPrev(...)` | `seo()->next->set(...)` / `seo()->prev->set(...)` |
| `@seoAlternates(...)` / `@seoAlternate($lang, $url)` | `seo()->alternates->set(...)` / `add(...)` |
| `@seoImages(...)` / `@seoImage(...)` | `seo()->images->set(...)` / `add(...)` |
| `@seoCanonical(...)` | `seo()->canonical->set(...)` |
| `@seoOpenGraphType(...)` | `seo()->openGraph->setType(...)` |
| `@seoOpenGraphSiteName(...)` | `seo()->openGraph->setSiteName(...)` |
| `@seoTwitterCard(...)` | `seo()->twitter->setCard(...)` |
| `@seoTwitterSite(...)` | `seo()->twitter->setSite(...)` |
| `@seoTwitterCreator(...)` | `seo()->twitter->setCreator(...)` |

:::warning Deprecated Directives
The 2.x directives without the `seo` prefix, such as `@title` and `@keyword`, still work but are deprecated. They are removed in the first line that supports PHP 8.6.
:::
