## Examples

### 1. Site-wide Defaults

Set what every page shares once, so pages only set what's their own.

```php title="config/pharaonic/seo.php"
'default' => [
    'charset' => 'UTF-8',
    'viewport' => 'width=device-width, initial-scale=1.0',
    'robots' => 'index, follow',

    'open_graph' => [
        'type' => 'website',
        'site_name' => 'Pharaonic',
    ],

    'twitter' => [
        'card' => 'summary_large_image',
        'site' => 'pharaonic',
    ],
],
```

### 2. A Blog Post from Its Model

- ===Model

  ```php title="app/Post.php"
  class Post extends Model implements SEOContract
  {
      public function seo(): array
      {
          return [
              'title' => $this->title.' | Blog',
              'description' => $this->excerpt,
              'images' => [$this->cover_url],
              'canonical' => route('posts.show', $this),
              'open_graph' => ['type' => 'article'],
          ];
      }
  }
  ```

- ===Controller

  ```php title="app/Http/Controllers/PostController.php"
  public function show(Post $post)
  {
      seo()->model($post);

      return view('posts.show', compact('post'));
  }
  ```

- ===Layout

  ```blade title="resources/views/layouts/app.blade.php"
  <head>
      @seo
  </head>
  ```

### 3. A Multilingual Page

List every translation of the page; the Open Graph card lists them as alternate locales too.

```php title="app/Http/Controllers/AboutController.php"
foreach (['en', 'ar', 'fr'] as $locale) {
    seo()->alternates->add($locale, url("$locale/about"));
}
```

### 4. Hide a Page from Search Engines

```blade title="resources/views/account/settings.blade.php"
@seoRobots('noindex, nofollow')
```

### 5. Tags in a Queued Mail or Job

Each queued job starts with a fresh manager, so tags set by one job never show up in the next one.

```php title="app/Jobs/RenderPreview.php"
public function handle()
{
    seo()->title->set($this->page->title);

    Storage::put("previews/{$this->page->id}.html", view('preview')->render());
}
```
