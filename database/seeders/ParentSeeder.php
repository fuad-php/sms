<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ParentModel;

class ParentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users with parent role
        $parentUsers = User::where('role', 'parent')->get();
        
        foreach ($parentUsers as $user) {
            // Check if parent record already exists
            if (!$user->parent) {
                ParentModel::create([
                    'user_id' => $user->id,
                    'occupation' => 'Not specified',
                    'workplace' => 'Not specified',
                    'is_active' => true,
                ]);
            }
        }
    }
}
