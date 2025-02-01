<?php

namespace App\Models;

use App\Models\State;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category_id',
        'state_id'
    ];
    
    public function category(): BelongsTo
    {
        return $this->BelongsTo(Category::class);
    }

    public function state(): BelongsTo
    {
        return $this->BelongsTo(State::class);
    }
}
