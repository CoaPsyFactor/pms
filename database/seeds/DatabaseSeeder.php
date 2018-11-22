<?php

/**
 * Created by PhpStorm.
 * User: zvekete
 * Date: 8.10.2016.
 * Time: 18.32
 */
class DatabaseSeeder extends \Illuminate\Database\Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
           [
               'name' => 'Coa',
               'email' => 'coapsyfactor@gmail.com',
               'password' => password_hash('test', PASSWORD_BCRYPT)
           ]
        ]);

        DB::table('navigation_links')->insert([
            [
                'title' => 'Home',
                'slug' => 'home',
                'value' => 1,
                'is_active' => true,
                'internal' => true,
                'parent' => 0,
                'sort' => 0
            ],
            [
                'title' => 'Google',
                'slug' => 'google',
                'value' => 'https://www.google.com/',
                'is_active' => 1,
                'internal' => false,
                'parent' => 0,
                'sort' => 2
            ],
            [
                'title' => 'Blog',
                'slug' => 'blog',
                'value' => 0,
                'is_active' => true,
                'internal' => true,
                'parent' => 0,
                'sort' => 1
            ],
            [
                'title' => 'Latest',
                'slug' => 'blog/latest',
                'value' => 2,
                'is_active' => true,
                'internal' => true,
                'parent' => 3,
                'sort' => 0
            ]
        ]);

        DB::table('pages')->insert([
            [
                'title' => 'Page Management System - Home',
                'content' => 'Welcome!'
            ],
            [
                'title' => 'Page Management System - Latest Posts',
                'content' => '{{Blog latest}}'
            ]
        ]);

        DB::table('blog_categories')->insert([
            [
                'name' => 'Fashion'
            ],
            [
                'name' => 'Vehicles'
            ]
        ]);

        DB::table('blog_posts')->insert([
            [
                'title' => 'Loreal Makeup',
                'content' => 'Bla bla truc truc',
                'user_id' => 1,
                'active' => true,
                'category_id' => 1,
            ],
            [
                'title' => 'BMW M3',
                'content' => 'Fastes car ever!',
                'user_id' => 1,
                'active' => true,
                'category_id' => 2,
            ],
        ]);

        DB::table('plugins')->insert([
            [
                'base_class' => \App\Plugins\Blog\BlogPlugin::class,
                'name' => 'Blog',
                'active' => true,
            ]
        ]);
    }
}