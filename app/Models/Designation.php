<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    use HasFactory;
    protected $table = 'designation';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'id',
        'title',
        'description',
        'designation_level_id',
        'created_by',
        'created_at',
        'active',
    ];
}
