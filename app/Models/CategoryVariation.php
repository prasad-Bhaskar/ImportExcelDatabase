<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryVariation extends Model
{
    use HasFactory;

    protected $table = 'category_variations';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'id',
        'category_id',
        'variation_id',
        'created_at',
        'updated_at',
        'active',
    ];

}
