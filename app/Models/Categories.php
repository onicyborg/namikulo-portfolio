<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasUuids;

    protected $table = 'categories';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function portfolios()
    {
        return $this->hasMany(Portfolios::class);
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonials::class);
    }
}
