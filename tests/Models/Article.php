<?php

namespace Pharaonic\Laravel\SEO\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\SEO\Contracts\SEOContract;

class Article extends Model implements SEOContract
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * Get SEO Data
     *
     * @return array<string, mixed>
     */
    public function seo(): array
    {
        return $this->getAttributes() + [
            'twitter' => [
                'card' => 'app',
            ],
        ];
    }
}
