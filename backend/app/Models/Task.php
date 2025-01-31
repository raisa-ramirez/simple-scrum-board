<?php

namespace App\Models;

use App\Models\State;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;
    
    public function category(): HasOne
    {
        return $this->hasOne(Category::class);
    }

    public function state(): HasOne
    {
        return $this->hasOne(State::class);
    }
}
