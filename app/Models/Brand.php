<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model 
{
    use HasFactory;

    protected $table = 'brand';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'id',
        'value',       
        'created_by',
        'created_at',
        'active',
    ];
}