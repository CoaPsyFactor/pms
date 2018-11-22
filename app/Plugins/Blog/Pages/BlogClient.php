<?php
/**
 * Created by PhpStorm.
 * User: zvekete
 * Date: 12.10.2016.
 * Time: 21.30
 */

namespace App\Plugins\Blog\Pages;


use App\Helpers\Plugin\PluginPage;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;


class BlogClient extends PluginPage
{

    public function listCategories(Request $request)
    {
        return response()->view('blog.client.categories', ['categories' => BlogCategory::all()]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function category(Request $request, $lang, $categoryId, $page = 1, $limit = 25)
    {
        $posts = BlogPost::where(['category_id' => $categoryId, 'active' => true]);

        $posts = $posts->orderBy('id', 'DESC')->get()->forPage($page, $limit);

        return response()->view('blog.client.posts', ['posts' => $posts]);
    }

    /**
     * @return array
     */
    protected function registerRoutes()
    {
        return [
            'blog/categories' => [
                'name' => 'blogCategories',
                'callback' => 'listCategories',
                'method' => 'get'
            ],
            'blog/category/{categoryId}/{page?}/{limit?}' => [
                'name' => 'blogCategory',
                'callback' => 'category',
                'method' => 'get'
            ]
        ];
    }

    /**
     * @return int
     */
    protected function pluginPageType()
    {
        return PluginPage::GUEST_PAGE;
    }
}