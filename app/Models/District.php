<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $table = 'district';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'title',
        'description',
        'created_at',
        'active',
    ];
}
