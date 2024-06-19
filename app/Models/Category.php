<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = ['created_at_relable', 'full_path_image_relable'];
    // Append the category Created At Relable
    public function getCreatedAtRelableAttribute()
    {
        return $this->created_at?->diffForHumans();
    }
    public function getFullPathImageRelableAttribute()
    {
        return env('APP_URL') . 'category_img/' . $this->image;
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function food(){
        return $this->hasMany(Food::class);
    }
}
