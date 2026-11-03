---
view: components.packages.quick-look
title: A quick look
subtitle: Set the tags in a controller, print them in the layout.
file: app/Http/Controllers/PostController.php
language: php
code: |
  public function show(Post $post)
  {
      seo()->title->set($post->title);
      seo()->description->set($post->excerpt);
      seo()->images->add($post->cover_url);
      seo()->canonical->set(route('posts.show', $post));
      seo()->openGraph->setType('article');

      return view('posts.show', compact('post'));
  }

  // resources/views/layouts/app.blade.php: <head> @seo </head>
---
