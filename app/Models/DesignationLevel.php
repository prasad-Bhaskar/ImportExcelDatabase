<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignationLevel extends Model
{
    use HasFactory;
    protected $table = 'designation_level';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = true;
    public $timestamps = false;
    // protected $fillable = [
    //     'id',
    //     'title',
    //     'description',
    //     'level',
    //     'active',
    // ];
}
