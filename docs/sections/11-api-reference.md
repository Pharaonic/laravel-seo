## API Reference

### Manager

`seo()` returns `Pharaonic\Laravel\SEO\Classes\Manager`, one per request or queued job.

| Member | Description | Returns |
| --- | --- | --- |
| `->{element}` | The element, such as `title`, `openGraph` or `twitter`. Throws `InvalidArgumentException` when unknown | element |
| `model(Model $model)` | Applies the data of a model that implements `SEOContract` | `void` |
| `render()` | Every tag, one per line | `string` |
| `toArray()` | The content of every rendered tag and the `images` list by name, not escaped | `array` |
| `getDirectives()` | The directive of every element, by name | `array` |

### Single Elements

`charset`, `viewport`, `title`, `description`, `author`, `robots`, `copyright`, `next`, `prev`, `canonical`, and custom `SingleElement` classes.

| Method | Description | Returns |
| --- | --- | --- |
| `set(string $value)` | Replaces the value | `static` |
| `get()` | The value | `?string` |
| `clear()` | Removes the value | `static` |
| `getName()` | The element name | `string` |
| `render()` | The tag, or an empty string | `string` |

### List Elements

`keywords`, `images`, `alternates`, and custom `MultipleElement` classes.

| Method | Description | Returns |
| --- | --- | --- |
| `set(array\|string $value)` | Replaces the list. A string is split on `, ` | `static` |
| `add(string $value)` | Appends to `keywords` or `images` | `static` |
| `add(string $lang, string $url)` | Appends to `alternates` | `static` |
| `remove(int $index)` | Removes an entry and reindexes the list | `static` |
| `get()` | The list | `?array` |
| `clear()` | Removes the list | `static` |
| `getName()` | The element name | `string` |

### Open Graph

| Method | Description | Returns |
| --- | --- | --- |
| `setType(string $type)` | `og:type` | `static` |
| `getType()` | `og:type` | `?string` |
| `setSiteName(string $siteName)` | `og:site_name` | `static` |
| `getSiteName()` | `og:site_name` | `?string` |
| `render()` | The Open Graph tags | `string` |

### Twitter

| Method | Description | Returns |
| --- | --- | --- |
| `setCard(string $card)` | `twitter:card` | `static` |
| `getCard()` | `twitter:card` | `?string` |
| `setSite(string $site)` | `twitter:site`, prefixed with one `@` | `static` |
| `getSite()` | `twitter:site` | `?string` |
| `setCreator(string $creator)` | `twitter:creator`, prefixed with one `@` | `static` |
| `getCreator()` | `twitter:creator` | `?string` |
| `render()` | The Twitter card tags | `string` |

### SEOContract

| Method | Description | Returns |
| --- | --- | --- |
| `seo()` | Element name => value, plus `open_graph` and `twitter` => attribute => value | `array` |
