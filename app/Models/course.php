<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;    use HasTranslations;

    public $translatable = ['title', 'description'];

    protected $fillable = [
        'title',
        'description',
        'image_url',
        'price',
        'level',
        'total_seats',
        'available_seats',
        'rating',
        'duration',
        'instructor_id',
        'category_id'
    ];


    protected $casts = [
        'title' => 'array',
        'description' => 'array',
    ];

    /**
     * Relationships
     */
    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function booking()
    {
        return $this->hasMany(Booking::class);
    }
}
