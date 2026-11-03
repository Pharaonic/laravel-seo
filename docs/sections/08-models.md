## Eloquent Models

A model that implements `SEOContract` returns its SEO data from `seo()`. The keys are element names, plus `open_graph` and `twitter` for the cards.

```php title="app/Models/Post.php"
use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\SEO\Contracts\SEOContract;

class Post extends Model implements SEOContract
{
    public function seo(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->excerpt,
            'keywords' => $this->tags->pluck('name')->all(),
            'images' => [$this->cover_url],
            'open_graph' => [
                'type' => 'article',
            ],
            'twitter' => [
                'card' => 'summary_large_image',
            ],
        ];
    }
}
```

Apply it in the controller:

```php title="app/Http/Controllers/PostController.php"
public function show(Post $post)
{
    seo()->model($post);

    return view('posts.show', compact('post'));
}
```

- A `null` value is skipped, so the element keeps its default.
- Numbers are turned into strings.
- Card attributes call the matching setter: `site_name` calls `setSiteName()`.
- An unknown key, or a model that doesn't implement `SEOContract`, throws an `InvalidArgumentException`.
