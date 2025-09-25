<?php
// app/Models/Dog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dog extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', // <-- ¡DEBE ESTAR AHÍ!
    ];
}