<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'category';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'id',
        'title',
        'thumbnail',
        'is_notify',
        'min_count',
        'created_by',
        'created_at',
        'updated_by',
        'active',
    ];

}
