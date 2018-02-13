<?php

use Illuminate\Database\Seeder;

class LeadboxSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Populate the database with dummy data following the cascade of this system
         * --Skipping Team implementation for now
         *
         *               Team1      Team2
         *                _|___     _|_
         *              /      \  /    \
         *             /        \/      \
         *           User1     User2   User3
         *         /      \
         *   BlogGroup1  BlogGroup2
         *       _|_
         *      /   \
         *     /     \
         *   Blog1  Blog2
         *    _|___
         *   /     \
         *  /       \
         *Leadbox1  Leadbox2
         *
         */

        // Create teams
        $teams = factory(App\Team::class, 10)->create();

        // Create users
        $users = factory(App\User::class, 10)->create();

        // iterate users
        $users->each(function($user) {

            // Create multiple BlogGroups for user
            $blogGroups = factory(App\BlogGroup::class, 4)->create([

                'user_id' => $user->id

            ]);

            // Iterate blog groups of user
            $blogGroups->each(function($blogGroup) {

                // Create multiple blogs per BlogGroup per user
                $blogs = factory(App\Blog::class, 5)->create([
                    'user_id' => $blogGroup->user->id,
                    'blog_group_id' => $blogGroup->id
                ]);

                // Iterate blogs of blogGroup
                $blogs->each(function($blog) {

                    // Create multiple Leadboxes per Blog per BlogGroup per user
                    factory(App\Leadbox::class, 5)->create([
                        'user_id' => $blog->blogGroup->user->id,
                        'blog_id' => $blog->id
                    ]);

                });

            });

        });
    }
}
