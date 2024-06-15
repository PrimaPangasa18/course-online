<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    // Cara pertama dalam mempersiapkan mass assignment
    protected $fillable = [
        'name',
        'slug',
        'icon',

    ];

    // Cara kedua
    // Cara kedua ini dapat memasukkan data apa saja yang berada di table category
    // User dapat memasukkan data apa saja yang membahayakan sistem
    // protected $guarded = [
    //     'id',
    // ];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
