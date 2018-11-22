<?php
/**
 * Created by PhpStorm.
 * User: azivanovic
 * Date: 10/10/16
 * Time: 2:46 PM
 */

namespace App\Plugins\Blog;

use App\Helpers\Plugin\PluginWidget;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Collection;

class BlogWidget implements PluginWidget
{

    const DEFAULT_POST_COUNT = 10;

    /**
     * @param int $categoryId
     * @param int $count
     * @return Collection
     */
    public function latest($categoryId = 0, $count = BlogWidget::DEFAULT_POST_COUNT)
    {

        if ($categoryId) {
            $posts = BlogCategory::find($categoryId)->posts->reverse()->forPage(1, $count);
        } else {
            $posts = BlogPost::all()->reverse()->forPage(1, $count);
        }

        return view('blog.widget.posts', ['posts' => $posts]);
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response|BlogPost
     */
    public function post($id)
    {
        $post = BlogPost::find($id);

        if (false === $post instanceof BlogPost) {
            return response()->view('errors.404', ['type' => BlogPost::class], 404)->throwResponse();
        }

        return view('blog.widget.post', ['post' => $post]);
    }

    /**
     * @param int $id
     * @param int $page
     * @param int $count
     * @return Collection
     */
    public function category($id, $page = 1, $count = BlogWidget::DEFAULT_POST_COUNT)
    {
        $posts = BlogCategory::find($id)->posts->forPage($page, $count);

        return view('blog.posts', ['posts' => $posts]);
    }
}