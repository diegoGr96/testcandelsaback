<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = array(
            [
                'user_id' => 1, 'title' => "Hi I'm Diego Garcia", 'body' =>
                "Hi I'm Diego Garcia. \n Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce congue mi vitae orci sollicitudin fringilla. Donec malesuada, quam vel dictum fermentum, nibh diam placerat lectus, nec facilisis lectus ante nec nulla. In tempus quis neque accumsan dignissim. Morbi ut nunc risus. Nam sit amet tempor odio. Nulla ac lorem at odio fringilla scelerisque. Ut metus orci, finibus ut dictum sit amet, suscipit nec diam. In iaculis nisi odio. Fusce molestie turpis ipsum, at commodo mauris suscipit eget. Nullam convallis quam sem, a commodo sapien maximus a. Morbi at ante ut lorem maximus suscipit. Sed in sodales magna, vitae pulvinar mauris. Aenean vehicula finibus dolor, semper scelerisque risus accumsan ac. Mauris quis metus non lorem blandit venenatis. Aenean massa lacus, bibendum a posuere non, accumsan nec dolor. Sed consequat nisi eu orci luctus vehicula.",
                'active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')
            ],

            [
                'user_id' => 2, 'title' => "Hi I'm Jennifer Pich", 'body' =>
                "Hi I'm Jennifer Pich. \n Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce congue mi vitae orci sollicitudin fringilla. Donec malesuada, quam vel dictum fermentum, nibh diam placerat lectus, nec facilisis lectus ante nec nulla. In tempus quis neque accumsan dignissim. Morbi ut nunc risus. Nam sit amet tempor odio. Nulla ac lorem at odio fringilla scelerisque. Ut metus orci, finibus ut dictum sit amet, suscipit nec diam. In iaculis nisi odio. Fusce molestie turpis ipsum, at commodo mauris suscipit eget. Nullam convallis quam sem, a commodo sapien maximus a. Morbi at ante ut lorem maximus suscipit. Sed in sodales magna, vitae pulvinar mauris. Aenean vehicula finibus dolor, semper scelerisque risus accumsan ac. Mauris quis metus non lorem blandit venenatis. Aenean massa lacus, bibendum a posuere non, accumsan nec dolor. Sed consequat nisi eu orci luctus vehicula.",
                'active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')
            ],

            [
                'user_id' => 3, 'title' => "Hi I'm Albert Aroca", 'body' => "Hi I'm Albert Aroca. \n Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce congue mi vitae orci sollicitudin fringilla. Donec malesuada, quam vel dictum fermentum, nibh diam placerat lectus, nec facilisis lectus ante nec nulla. In tempus quis neque accumsan dignissim. Morbi ut nunc risus. Nam sit amet tempor odio. Nulla ac lorem at odio fringilla scelerisque. Ut metus orci, finibus ut dictum sit amet, suscipit nec diam. In iaculis nisi odio. Fusce molestie turpis ipsum, at commodo mauris suscipit eget. Nullam convallis quam sem, a commodo sapien maximus a. Morbi at ante ut lorem maximus suscipit. Sed in sodales magna, vitae pulvinar mauris. Aenean vehicula finibus dolor, semper scelerisque risus accumsan ac. Mauris quis metus non lorem blandit venenatis. Aenean massa lacus, bibendum a posuere non, accumsan nec dolor. Sed consequat nisi eu orci luctus vehicula.",
                'active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')
            ],
        );

        DB::table('posts')->insert($posts);
    }
}
