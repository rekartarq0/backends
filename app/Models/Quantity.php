<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quantity extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = ['created_at_relable'];
    // Append the quantity Created At Relable
    public function getCreatedAtRelableAttribute(){
        return $this->created_at?->diffForHumans();
    }

    public function food(){
        return $this->belongsTo(Food::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    
}
