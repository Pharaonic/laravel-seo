## Custom Elements

Add an element, or replace a built-in one, in the `elements` option. An element extends `SingleElement` for one value or `MultipleElement` for a list. Its key is its name, its tag name, and the base of its directives.

```php title="app/SEO/ThemeColor.php"
namespace App\SEO;

use Pharaonic\Laravel\SEO\Classes\Elements\SingleElement;

class ThemeColor extends SingleElement
{
    //
}
```

```php title="config/pharaonic/seo.php"
'default' => [
    'theme-color' => '#4f46e5',
],

'elements' => [
    'theme-color' => \App\SEO\ThemeColor::class,
],
```

```html title="Output" no-copy
<meta name="theme-color" content="#4f46e5">
```

A `SingleElement` renders `<meta name="{name}" content="{value}">`. Override `render()` for another tag, and escape the value with `e()`:

```php title="app/SEO/Manifest.php"
class Manifest extends SingleElement
{
    public function render()
    {
        return $this->get() ? '<link rel="manifest" href="'.e($this->get()).'">' : '';
    }
}
```

The element is read as `seo()->{'theme-color'}`, and gets the `@seoThemeColor` directive. A `MultipleElement` also gets a singular directive, such as `@seoTheme` for `themes`, which calls an `add()` method that you define.

:::info Errors
A class in `elements` that extends neither base class throws an `InvalidArgumentException` when the manager is created.
:::
