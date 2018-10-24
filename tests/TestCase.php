<?php

namespace Tests;

use Carbon\Carbon;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use PHPUnit\Framework\TestCase as Base;
use Tests\Models\Comment;
use Tests\Models\Country;
use Tests\Models\Post;
use Tests\Models\Role;
use Tests\Models\Tag;
use Tests\Models\User;

abstract class TestCase extends Base
{
    protected function setUp()
    {
        parent::setUp();

        $capsule = new Capsule;
        $capsule->addConnection([
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'port' => '3306',
            'database' => 'test',
            'username' => 'root',
            'password' => 'password',
            'unix_socket' => '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        Capsule::schema()->dropAllTables();

        Capsule::schema()->create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });

        Capsule::schema()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('country_id');
        });

        Capsule::schema()->create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->timestamps();
        });

        Capsule::schema()->create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('commentable');
            $table->timestamps();
        });

        Capsule::schema()->create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });

        Capsule::schema()->create('role_user', function (Blueprint $table) {
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('user_id');
        });

        Capsule::schema()->create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });

        Capsule::schema()->create('taggables', function (Blueprint $table) {
            $table->unsignedInteger('tag_id');
            $table->morphs('taggable');
        });

        Model::unguarded(function () {
            Country::create();
            Country::create();

            User::create(['country_id' => 1]);
            User::create(['country_id' => 2]);

            Post::create(['user_id' => 1, 'created_at' => new Carbon('2018-01-01 00:00:01')]);
            Post::create(['user_id' => 1, 'created_at' => new Carbon('2018-01-01 00:00:02')]);
            Post::create(['user_id' => 1, 'created_at' => new Carbon('2018-01-01 00:00:03')]);
            Post::create(['user_id' => 2, 'created_at' => new Carbon('2018-01-01 00:00:04')]);
            Post::create(['user_id' => 2, 'created_at' => new Carbon('2018-01-01 00:00:05')]);
            Post::create(['user_id' => 2, 'created_at' => new Carbon('2018-01-01 00:00:06')]);

            Comment::create([
                'commentable_type' => Post::class,
                'commentable_id' => 1,
                'created_at' => new Carbon('2018-01-01 00:00:01'),
            ]);
            Comment::create([
                'commentable_type' => Post::class,
                'commentable_id' => 1,
                'created_at' => new Carbon('2018-01-01 00:00:02'),
            ]);
            Comment::create([
                'commentable_type' => Post::class,
                'commentable_id' => 1,
                'created_at' => new Carbon('2018-01-01 00:00:03'),
            ]);
            Comment::create([
                'commentable_type' => Post::class,
                'commentable_id' => 2,
                'created_at' => new Carbon('2018-01-01 00:00:04'),
            ]);
            Comment::create([
                'commentable_type' => Post::class,
                'commentable_id' => 2,
                'created_at' => new Carbon('2018-01-01 00:00:05'),
            ]);
            Comment::create([
                'commentable_type' => Post::class,
                'commentable_id' => 2,
                'created_at' => new Carbon('2018-01-01 00:00:06'),
            ]);

            Role::create(['created_at' => new Carbon('2018-01-01 00:00:01')]);
            Role::create(['created_at' => new Carbon('2018-01-01 00:00:02')]);
            Role::create(['created_at' => new Carbon('2018-01-01 00:00:03')]);
            Role::create(['created_at' => new Carbon('2018-01-01 00:00:04')]);
            Role::create(['created_at' => new Carbon('2018-01-01 00:00:05')]);
            Role::create(['created_at' => new Carbon('2018-01-01 00:00:06')]);

            Capsule::table('role_user')->insert([
                ['role_id' => 1, 'user_id' => 1],
                ['role_id' => 2, 'user_id' => 1],
                ['role_id' => 3, 'user_id' => 1],
                ['role_id' => 4, 'user_id' => 2],
                ['role_id' => 5, 'user_id' => 2],
                ['role_id' => 6, 'user_id' => 2],
            ]);

            Tag::create(['created_at' => new Carbon('2018-01-01 00:00:01')]);
            Tag::create(['created_at' => new Carbon('2018-01-01 00:00:02')]);
            Tag::create(['created_at' => new Carbon('2018-01-01 00:00:03')]);
            Tag::create(['created_at' => new Carbon('2018-01-01 00:00:04')]);
            Tag::create(['created_at' => new Carbon('2018-01-01 00:00:05')]);
            Tag::create(['created_at' => new Carbon('2018-01-01 00:00:06')]);

            Capsule::table('taggables')->insert([
                ['tag_id' => 1, 'taggable_type' => Post::class, 'taggable_id' => 1],
                ['tag_id' => 2, 'taggable_type' => Post::class, 'taggable_id' => 1],
                ['tag_id' => 3, 'taggable_type' => Post::class, 'taggable_id' => 1],
                ['tag_id' => 4, 'taggable_type' => Post::class, 'taggable_id' => 2],
                ['tag_id' => 5, 'taggable_type' => Post::class, 'taggable_id' => 2],
                ['tag_id' => 6, 'taggable_type' => Post::class, 'taggable_id' => 2],
            ]);
        });
    }
}
