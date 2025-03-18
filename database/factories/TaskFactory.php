<?php

namespace Database\Factories;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        

        return [
            'uuid' => Str::uuid(), 
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(), 
            'status' => 'pending', 
            'user_id' => User::factory(), 
        ];
        
    }
}
