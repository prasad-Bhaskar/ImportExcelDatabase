<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppWallpeper extends Model
{
    use HasFactory;
    protected $table = 'app_wallpaper';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'wallpaper_path', 
        'thumbnail',  
        'created_at',
        'active'
    ];
}
