<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservations extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = ['created_at_relable'];
    // Append the reservation Created At Relable
    
    public function getCreatedAtRelableAttribute(){
        return $this->created_at?->diffForHumans();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function table(){
        return $this->belongsTo(Table::class);
    }
   
}
