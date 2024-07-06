<?php

namespace Pharaonic\Laravel\SEO\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\SEO\Contracts\SEOContract;

class Article extends Model implements SEOContract
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'description', 'keywords'];

    /**
     * Get SEO Data
     *
     * @return array
     */
    public function seo(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'keywords' => $this->keywords,
            'twitter' => [
                'card' => 'app'
            ]
        ];
    }
}