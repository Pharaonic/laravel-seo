<?php

namespace Pharaonic\Laravel\SEO;

use Illuminate\Database\Eloquent\Model;

/**
 * SEO Class
 * SEO Generator Helper for Laravel
 */
class SEO
{
    private $charset, $title, $description, $keywords,
        $author, $copyrights,
        $robots, $canonical,
        $prev, $next,
        $alternates = [], $images = [], $metas = [], $open_graph = [], $twitter = [];

    /**
     * Getting Default Tags
     *
     * @return string
     */
    private function generateDefault()
    {
        $output = '';

        // charset
        if ($this->charset) $output .= '<meta charset="' . $this->charset . '">' . PHP_EOL;

        // Metas [Custom]
        foreach ($this->metas as $meta)
            $output .= '<meta name="' . $meta[0] . '" content="' . $meta[1] . '">' . PHP_EOL;

        // Title
        if ($this->title) $output .= '<title>' . $this->title . '</title>' . PHP_EOL;

        // META with Names
        foreach (['description', 'keywords', 'author', 'copyrights', 'robots'] as $n)
            if ($this->{$n}) $output .= '<meta name="' . $n . '" content="' . $this->{$n} . '">' . PHP_EOL;

        // META with Linkes
        foreach (['canonical', 'prev', 'next'] as $n)
            if ($this->{$n}) $output .= '<link rel="' . $n . '" href="' . $this->{$n} . '">' . PHP_EOL;

        // Alternates
        foreach ($this->alternates as $alt)
            $output .= '<link rel="alternate" href="' . $alt[1] . '" hreflang="' . str_replace('.', '-', strtolower($alt[0])) . '">' . PHP_EOL;

        return $output;
    }

    /**
     * Getting Open-Graph Tags
     *
     * @return string
     */
    private function generateOpenGraph()
    {
        $output = '<meta property="og:url" content="' . url()->current() . '">' . PHP_EOL;

        // Open-Graphs [Custom]
        foreach ($this->open_graph as $og)
            $output .= '<meta property="og:' . $og[0] . '" content="' . $og[1] . '">' . PHP_EOL;

        // Title & Description
        if ($this->title) $output .= '<meta property="og:title" content="' . $this->title . '">' . PHP_EOL;
        if ($this->description) $output .= '<meta property="og:description" content="' . $this->description . '">' . PHP_EOL;

        // Alternates
        $output .= '<meta property="og:locale" content="' . app()->getLocale() . '">' . PHP_EOL;
        foreach ($this->alternates as $alt)
            $output .= '<meta property="og:locale:alternate" content="' . str_replace('.', '_', $alt[0]) . '">' . PHP_EOL;

        // Images
        foreach ($this->images as $img)
            $output .= '<meta property="og:image" content="' . $img . '">' . PHP_EOL;


        return $output;
    }

    /**
     * Getting Twitter Tags
     *
     * @return string
     */
    private function generateTwitter()
    {
        $output = '';

        // Twitter [Custom]
        foreach ($this->twitter as $tw)
            $output .= '<meta property="twitter:' . $tw[0] . '" content="' . $tw[1] . '">' . PHP_EOL;

        // Title & Description
        if ($this->title) $output .= '<meta property="twitter:title" content="' . $this->title . '">' . PHP_EOL;
        if ($this->description) $output .= '<meta property="twitter:description" content="' . $this->description . '">' . PHP_EOL;


        // Images
        if (count($this->images) == 1) {
            $output .= '<meta property="twitter:image" content="' . $this->images[0] . '">' . PHP_EOL;
        } else {
            foreach ($this->images as $index => $img)
                $output .= '<meta property="twitter:image' . $index . '" content="' . $img . '">' . PHP_EOL;
        }

        return $output;
    }

    /**
     * Preparing Model Value
     *
     * @param Model $model
     * @param string $name
     * @param string $column
     * @return void
     */
    private function prepareValue(Model $model, string $name, string $column)
    {
        $value = $model->{$column};
        if (!$value) return;
        switch (gettype($value)) {
            case 'object':
                switch (get_class($value)) {
                    case 'Pharaonic\Laravel\Uploader\Upload':
                    case 'Pharaonic\Laravel\Files\File':
                        $this->setImage($value->url);
                        break;
                    case 'Illuminate\Support\Collection':
                        if ($column == 'images' && in_array('Pharaonic\Laravel\Images\HasImages', class_uses($model)))
                            foreach ($value as $img)
                                $this->setImage($img->url);
                        break;
                }
                break;
            default:
                $this->{'set' . ucfirst($name)}($value);
                break;
        }
    }

    /**
     * Generate from Model
     *
     * @param Model $model
     * @return void
     */
    public function model(Model $model)
    {
        if (method_exists($model, 'toSEO')) {
            $data = $model->toSEO();

            // Columns
            if (isset($data['columns']) && is_array($data['columns']))
                foreach ($data['columns'] as $name => $column)
                    $this->prepareValue($model, $name, $column);

            // Meta
            if (isset($data['meta']) && is_array($data['meta']))
                foreach ($data['meta'] as $name => $value)
                    $this->setMeta($name, $value);

            // Open-Graph
            if (isset($data['og']) && is_array($data['og']))
                foreach ($data['og'] as $name => $value)
                    $this->setOG($name, $value);

            // Open-Graph
            if (isset($data['twitter']) && is_array($data['twitter']))
                foreach ($data['twitter'] as $name => $value)
                    $this->setTwitter($name, $value);
        } else {
            throw new \Exception('You have to include toSEO method in the Model.');
        }
    }

    /**
     * SEO Generator
     *
     * @param string $type
     * @return string
     */
    public function generate(string $type = null)
    {
        switch ($type) {
            case 'default':
                $output = $this->generateDefault();
                break;
            case 'twitter':
                $output = $this->generateTwitter();
                break;
            case 'open-graph':
                $output = $this->generateOpenGraph();
                break;
            default:
                $output = $this->generateDefault();
                $output .= $this->generateOpenGraph();
                $output .= $this->generateTwitter();
                break;
        }

        return $output;
    }

    /**
     * Get the value of title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */
    public function setDescription($description)
    {
        $this->description = $description;
        // View::share()

        return $this;
    }

    /**
     * Get the value of keywords
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set the value of keywords
     *
     * @return  self
     */
    public function setKeywords($keywords)
    {
        if (is_array($keywords)) $keywords = implode(', ', $keywords);
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * Get the value of author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set the value of author
     *
     * @return  self
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get the value of copyrights
     */
    public function getCopyrights()
    {
        return $this->copyrights;
    }

    /**
     * Set the value of copyrights
     *
     * @return  self
     */
    public function setCopyrights($copyrights)
    {
        $this->copyrights = $copyrights;

        return $this;
    }

    /**
     * Get the value of robots
     */
    public function getRobots()
    {
        return $this->robots;
    }

    /**
     * Set the value of robots
     *
     * @return  self
     */
    public function setRobots($robots)
    {
        $this->robots = $robots;

        return $this;
    }

    /**
     * Get the value of canonical
     */
    public function getCanonical()
    {
        return $this->canonical;
    }

    /**
     * Set the value of canonical
     *
     * @return  self
     */
    public function setCanonical($canonical)
    {
        $this->canonical = $canonical;

        return $this;
    }

    /**
     * Get the value of prev
     */
    public function getPrev()
    {
        return $this->prev;
    }

    /**
     * Set the value of prev
     *
     * @return  self
     */
    public function setPrev($prev)
    {
        $this->prev = $prev;

        return $this;
    }

    /**
     * Get the value of next
     */
    public function getNext()
    {
        return $this->next;
    }

    /**
     * Set the value of next
     *
     * @return  self
     */
    public function setNext($next)
    {
        $this->next = $next;

        return $this;
    }

    /**
     * Get the value of alternates
     */
    public function getAlternates()
    {
        return $this->alternates;
    }

    /**
     * Set the value of alternates
     *
     * @return  self
     */
    public function setAlternate($lang, $link)
    {
        $this->alternates[] = [$lang, $link];

        return $this;
    }

    /**
     * Get the value of Metas
     */
    public function getMetas()
    {
        return $this->metas;
    }

    /**
     * Set the value of Metas
     *
     * @return  self
     */
    public function setMeta($name, $content)
    {
        $this->metas[] = [$name, $content];

        return $this;
    }

    /**
     * Get the value of Charset
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * Set the value of Charset
     *
     * @return  self
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;

        return $this;
    }

    /**
     * Get the value of OG
     */
    public function getOG()
    {
        return $this->open_graph;
    }

    /**
     * Set the value of OG
     *
     * @return  self
     */
    public function setOG($name, $content)
    {
        $this->open_graph[] = [$name, $content];

        return $this;
    }

    /**
     * Get the value of Twitter
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set the value of Twitter
     *
     * @return  self
     */
    public function setTwitter($name, $content)
    {
        $this->twitter[] = [$name, $content];

        return $this;
    }

    /**
     * Get the value of Images
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set the value of images
     *
     * @return  self
     */
    public function setImage($src)
    {
        $this->images[] = $src;

        return $this;
    }
}
