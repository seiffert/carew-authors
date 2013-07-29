Authors plugin for [Carew](http://github.com/carew/carew)
============================================================

This plugin enables you to use Carew with multiple authors. In every article, you can set the author
in the article's metadata. When you execute Carew's build command, special author index documents will
be generated for every author.

Installation
------------

Install it with composer:

```
composer require seiffert/carew-authors:dev-master
```

Then configure in `config.yml`

```
engine:
    extensions:
        - Carew\Plugin\Authors\AuthorsExtension

authors:
    ego:
        name: Paul
        email: paul.seiffert@gmail.com
    ego2:
        name: Paul
        email: paul.seiffert@gmail.com
```

Create a template `authors.html.twig` in your `layouts` directory with the following content:

    {% extends 'vendor/seiffert/carew-authors/Carew/Plugin/Authors/layouts/authors.html.twig' %}

In this template, you can customize the rendering of the author's index document.

Usage
-----

**In your posts:** specify the post's author like this:

    ---
    layout: post
    title:  This is pure awesomeness!
    author: seiffert
    ---

Now, when you build your blog using Carew's build command, a index file `authors/seiffert.html` will
be generated listing all posts of that author.
Also, the post Documents that have an author in their metadata, will be extended with an `Author` object
that holds all information configured in the `config.yml` as described above (in the example this are the
keys `name` and `email`.

When rendering a post in a template, you can access this information like this:

    <a href="/authors/{{ document.metadatas.author.handle }}.html">
        {{ document.metadatas.author.name }}
    </a>

