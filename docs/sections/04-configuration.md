## Configuration

The configuration lives in `config/pharaonic/seo.php` after you publish it.

```php title="config/pharaonic/seo.php"
return [
    'default' => [
        'charset' => 'UTF-8',
        // 'viewport' => 'width=device-width, initial-scale=1.0',

        'open_graph' => [
            'type' => 'website',
            // 'site_name' => 'Site Name',
        ],

        'twitter' => [
            'card' => 'summary',
            // 'site' => '@site',
            // 'creator' => '@creator',
        ],
    ],

    'elements' => [
        // 'charset' => \App\SEO\Charset::class,
    ],
];
```

### Defaults

`default` holds the starting value of any element, keyed by its name. A list element such as `keywords` takes an array or a comma-separated string.

```php title="config/pharaonic/seo.php"
'default' => [
    'charset' => 'UTF-8',
    'viewport' => 'width=device-width, initial-scale=1.0',
    'author' => 'Pharaonic',
    'robots' => 'index, follow',
    'keywords' => ['laravel', 'packages'],
],
```

| Key | Description |
| --- | --- |
| `default.<element>` | Starting value of an element, such as `title` or `robots` |
| `default.open_graph.type` | `og:type` |
| `default.open_graph.site_name` | `og:site_name` |
| `default.twitter.card` | `twitter:card` |
| `default.twitter.site` | `twitter:site`, with or without the `@` |
| `default.twitter.creator` | `twitter:creator`, with or without the `@` |
| `elements` | Extra elements, or replacements for built-in ones. See [Custom Elements](#custom-elements) |
