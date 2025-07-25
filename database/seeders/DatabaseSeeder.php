<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@anakt.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create Regular User
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'user@anakt.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // Create Additional Users
        $user2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        $user3 = User::create([
            'name' => 'Bob Johnson',
            'email' => 'bob@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // Create sample posts
        Post::create([
            'title' => 'Welcome to ANAKT Blog',
            'content' => "Welcome to ANAKT Blog, your new favorite destination for insightful articles, creative stories, and engaging discussions!\n\nOur platform brings together writers from all walks of life to share their thoughts, experiences, and expertise. Whether you're here to read inspiring content or to share your own stories, you'll find a welcoming community ready to engage with your ideas.\n\nWhat makes ANAKT Blog special? We believe in the power of authentic voices and diverse perspectives. Every post goes through a careful review process to ensure quality content while maintaining the unique voice of each author.\n\nFeel free to explore our latest posts, and don't forget to create your account to start sharing your own stories with our growing community!",
            'status' => 'approved',
            'approved_at' => now()->subDays(2),
            'user_id' => $user->id,
        ]);

        Post::create([
            'title' => 'The Future of Web Development',
            'content' => "Web development is evolving at an unprecedented pace. From the early days of static HTML pages to today's dynamic, interactive web applications, we've come a long way.\n\nModern web development involves a complex ecosystem of frameworks, libraries, and tools. React, Vue, Angular, and Svelte are revolutionizing how we build user interfaces, while backend technologies like Node.js, Python's Django and Flask, and PHP's Laravel continue to power the server-side logic.\n\nThe rise of cloud computing has also transformed how we deploy and scale applications. Services like AWS, Google Cloud, and Azure make it easier than ever to build globally distributed applications.\n\nLooking ahead, we can expect to see more emphasis on performance, accessibility, and user experience. The web is becoming more inclusive, and developers are increasingly focused on creating applications that work for everyone.\n\nWhat exciting developments do you see on the horizon for web development?",
            'status' => 'approved',
            'approved_at' => now()->subDays(1),
            'user_id' => $user->id,
        ]);

        Post::create([
            'title' => 'Tips for Productive Remote Work',
            'content' => "Remote work has become the new normal for many professionals. While it offers flexibility and eliminates commuting, it also presents unique challenges.\n\nHere are some proven strategies for staying productive while working from home:\n\n1. Create a dedicated workspace - Having a specific area for work helps maintain boundaries between personal and professional life.\n\n2. Establish a routine - Start your day at the same time and follow a consistent schedule.\n\n3. Take regular breaks - Use techniques like the Pomodoro method to maintain focus and avoid burnout.\n\n4. Stay connected with colleagues - Regular video calls and team meetings help maintain relationships and collaboration.\n\n5. Invest in good equipment - A comfortable chair, proper lighting, and reliable internet connection are essential.\n\n6. Set boundaries - Learn to 'switch off' after work hours to maintain work-life balance.\n\nWhat tips would you add to this list? Share your remote work experiences in the comments!",
            'status' => 'pending',
            'user_id' => $user2->id,
        ]);

        Post::create([
            'title' => 'The Art of Minimalist Design',
            'content' => "Less is more - this principle has guided designers for decades, and minimalist design continues to be one of the most influential movements in visual arts and user interface design.\n\nMinimalist design focuses on simplicity, functionality, and the elimination of unnecessary elements. It's not just about making things look clean; it's about creating designs that are intuitive and user-friendly.\n\nKey principles of minimalist design include:\n\n- White space utilization\n- Limited color palettes\n- Clean typography\n- Functional elements\n- Clear hierarchy\n\nMany successful companies have embraced minimalist design in their products and branding. Apple is perhaps the most famous example, with their clean product designs and simple marketing materials.\n\nMinimalism isn't just a design trend; it's a philosophy that values clarity and purpose over decoration and complexity.",
            'status' => 'approved',
            'approved_at' => now()->subHours(6),
            'user_id' => $user3->id,
        ]);

        Post::create([
            'title' => 'Getting Started with Laravel',
            'content' => "Laravel is one of the most popular PHP frameworks for web development. It provides an elegant syntax and powerful features that make building web applications a joy.\n\nKey features of Laravel include:\n\n- Eloquent ORM for database interactions\n- Blade templating engine\n- Artisan command-line interface\n- Built-in authentication and authorization\n- Robust routing system\n- Migration system for database schema management\n\nWhether you're building a simple blog or a complex web application, Laravel provides the tools you need to get started quickly and scale effectively.",
            'status' => 'approved',
            'approved_at' => now()->subHours(3),
            'user_id' => $user2->id,
        ]);

        Post::create([
            'title' => 'Understanding Database Design',
            'content' => "Good database design is crucial for any application. It affects performance, scalability, and maintainability of your system.\n\nKey principles of database design:\n\n- Normalization to reduce redundancy\n- Proper indexing for performance\n- Clear naming conventions\n- Appropriate data types\n- Foreign key relationships\n\nTaking time to design your database properly will save you countless hours of refactoring later.",
            'status' => 'pending',
            'user_id' => $user3->id,
        ]);

        echo "Database seeded successfully!\n";
        echo "Admin Login: admin@anakt.com / admin123\n";
        echo "User Login: user@anakt.com / user123\n";
    }
}