<?php

namespace Database\Seeders;

use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Task;
use App\Models\User;
use App\Models\State;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::factory()->create(['name' => 'Administrator']);
        Role::factory()->create(['name' => 'User']);

        State::factory()->create(['name' => 'Pendiente']);        
        State::factory()->create(['name' => 'En Progreso']);        
        State::factory()->create(['name' => 'Hecho']); 

        User::factory(2)->create();
        Category::factory(4)->create()->each(function($category){
            $category->user()->attach(2);
        });
        Task::factory(5)->create();
    }
}
