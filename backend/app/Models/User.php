<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use App\Models\Category;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\hasOne;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\belongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
   
    public function rol(): belongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    
    public function categories(): belongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
    
    public function tasks(): HasManyThrough
    {
        return $this->hasManyThrough(Task::class, Category::class);
    }

    public function getUsers($sortBy, $searchBy, $itemsPerPage){
        return User::orderBy($sortBy['key'], $sortBy['order'])
        ->where('name', 'LIKE', "%{$searchBy}%")
        ->with('rol')
        ->paginate($itemsPerPage);        
    }
}
