## Troubleshooting

### Nothing is printed in the head

`@seo` prints only elements that have a value. Check that the layout contains `@seo`, and that the values are set before the layout renders: in a controller, or in a child view that extends the layout.

### A tag shows `&amp;` or `&quot;`

Values are HTML-escaped when they are rendered. Pass the plain text: `Tom & Jerry`, not `Tom &amp; Jerry`.

### "Element ... not found."

The element name, or a key returned by a model's `seo()`, isn't a built-in or configured element. Use the names in [Elements](#elements), `open_graph` or `twitter`, or add the element in [Custom Elements](#custom-elements).

### "Class ... must extends SingleElement or MultipleElement."

A class in the `elements` option doesn't extend one of the two base classes.

### My published configuration is ignored

The configuration file is `config/pharaonic/seo.php`. A file published by 2.x at `config/Pharaonic/seo.php` is still read, and wins over the new one: move its values and delete it.

### A directive name clashes with my application

Use the `seo`-prefixed directives. The unprefixed 2.x directives, such as `@title`, are deprecated aliases: a directive with the same name that your application registers in a service provider replaces the alias.
