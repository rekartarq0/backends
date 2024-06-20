<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = ['created_at_relable'];
    // Append the table Created At Relable
    
    public function getCreatedAtRelableAttribute(){
        return $this->created_at?->diffForHumans();
    }
    public function user(){
        return $this->belongsTo(User::class);
    }


}
