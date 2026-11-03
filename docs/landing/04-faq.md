---
view: components.home.faq
badge: FAQ
title: "{package.name}"
highlight: Questions
subtitle: "Quick answers about installing and using {package.name}."
---

## What is {package.name}?

{card.description} It's a free, open-source {technology.name} package by Pharaonic.

## How do I install {package.name}?

Run `composer require {package.composer}` in your project's root directory.

## What does {package.name} require?

The latest release requires {package.requiresText}.

## Can I set the tags from a Blade view?

Yes. Every tag has a directive, such as `@seoTitle` or `@seoImage`, and a child view runs before its layout, so `@seo` in the layout prints what the view set.

## Can I add my own meta tags?

Yes. Extend `SingleElement` or `MultipleElement` and add the class to the `elements` option of the configuration; it renders with the others and gets its own directive.

## Does it work with Laravel Octane and queues?

Yes. Each request and each queued job starts with a fresh set of tags.

## Is {package.name} free to use?

Yes. {package.name} is open source under the {package.license} license, so you can use it in personal and commercial projects.

## Where can I find the {package.name} documentation?

Read the [{package.name} documentation]({package.docsUrl}) for setup, configuration, and usage examples.

## How do I report a bug or contribute to {package.name}?

Open an issue or a pull request on [GitHub]({package.githubUrl}), or ask in the [Pharaonic Discord](https://discord.gg/XQG9RhvEvf).
