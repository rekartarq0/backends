<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = ['created_at_relable', 'full_path_image_relable'];
    // Append the food Created At Relable
    public function getCreatedAtRelableAttribute(){
        return $this->created_at?->diffForHumans();
    }
    public function getFullPathImageRelableAttribute(){
        return env('APP_URL').'food_img/'.$this->image;
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function quantity(){
        return $this->hasMany(Quantity::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }

}
