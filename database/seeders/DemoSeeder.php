<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create primary student user
        $student = User::firstOrCreate(
            ['email' => 'student@tuntas.test'],
            [
                'name' => 'Student Demo',
                'password' => bcrypt('password'),
            ]
        );

        // 2. Create student categories
        $academic = Category::firstOrCreate([
            'user_id' => $student->id,
            'name' => 'Academic & Coursework',
        ], [
            'color' => '#3b82f6', // blue
        ]);

        $project = Category::firstOrCreate([
            'user_id' => $student->id,
            'name' => 'Final Year Project',
        ], [
            'color' => '#6366f1', // indigo
        ]);

        $personal = Category::firstOrCreate([
            'user_id' => $student->id,
            'name' => 'Personal & Admin',
        ], [
            'color' => '#10b981', // emerald
        ]);

        // 3. Create sample tasks for the student demonstrating all business states
        // In-progress task
        Task::firstOrCreate([
            'user_id' => $student->id,
            'title' => 'Complete Laravel Architectural Overview',
        ], [
            'category_id' => $project->id,
            'description' => 'Document the Eloquent models, relationships, and user ownership rules.',
            'status' => 'in_progress',
            'priority' => 'high',
            'due_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
        ]);

        // Pending task
        Task::firstOrCreate([
            'user_id' => $student->id,
            'title' => 'Review Distributed Systems Lecture Slides',
        ], [
            'category_id' => $academic->id,
            'description' => 'Read chapters 4 and 5 on consensus algorithms and raft protocols.',
            'status' => 'pending',
            'priority' => 'medium',
            'due_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
        ]);

        // Overdue task
        Task::firstOrCreate([
            'user_id' => $student->id,
            'title' => 'Submit Tuition Proof of Payment',
        ], [
            'category_id' => $personal->id,
            'description' => 'Upload scanned transaction slip to the university student portal.',
            'status' => 'pending',
            'priority' => 'high',
            'due_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
        ]);

        // Completed task
        Task::firstOrCreate([
            'user_id' => $student->id,
            'title' => 'Register Elective Subjects for Semester 2',
        ], [
            'category_id' => $academic->id,
            'description' => 'Selected Machine Learning and Cloud Infrastructure electives.',
            'status' => 'completed',
            'priority' => 'medium',
            'due_date' => Carbon::now()->subDays(4)->format('Y-m-d'),
            'completed_at' => Carbon::now()->subDays(3),
        ]);

        // 4. Create a second user to verify cross-user isolation
        $otherUser = User::firstOrCreate(
            ['email' => 'other@tuntas.test'],
            [
                'name' => 'Other Student',
                'password' => bcrypt('password'),
            ]
        );

        $otherCategory = Category::firstOrCreate([
            'user_id' => $otherUser->id,
            'name' => 'Private Studies',
        ], [
            'color' => '#8b5cf6',
        ]);

        Task::firstOrCreate([
            'user_id' => $otherUser->id,
            'title' => 'Private Research Note',
        ], [
            'category_id' => $otherCategory->id,
            'description' => 'Must not be visible or accessible to user 1.',
            'status' => 'pending',
            'priority' => 'low',
            'due_date' => Carbon::now()->addWeek()->format('Y-m-d'),
        ]);
    }
}
