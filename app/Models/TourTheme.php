<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourTheme extends Model
{
    use HasFactory;

    protected $table = 'tour_themes';

    protected $fillable = [
        'theme_name',
        'theme_images',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
