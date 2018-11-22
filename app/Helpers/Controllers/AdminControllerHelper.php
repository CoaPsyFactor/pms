<?php
/**
 * Created by PhpStorm.
 * User: azivanovic
 * Date: 10/24/16
 * Time: 1:43 PM
 */

namespace App\Helpers\Controllers;


use App\Helpers\NavigationLinks;
use App\Models\NavigationLink;
use App\Models\Page;
use App\Models\Plugin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Helpers\Plugin\PluginPage;

class AdminControllerHelper
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createNewPage(Request $request)
    {
        $page = new Page();

        $page->title = $request->get('title', '');

        $page->content = $request->get('content', '');

        $page->created_at = Carbon::now()->toDateTimeString();

        if (false === $page->save()) {
            $request->session()->flash('page.create.error', trans('page.admin.error.saving'));

            return response()->redirectToRoute('admin.page.new', ['lang' => app()->getLocale()]);
        }

        $request->session()->flash('page.create.success', trans('page.admin.error.none'));

        return response()->redirectToRoute('admin.page.edit', ['lang' => app()->getLocale(), 'id' => $page->id]);
    }

    /**
     *
     * @param int $pageId
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function updatePage($pageId, Request $request)
    {
        $page = Page::find($pageId);

        if (false === is_object($page) || false === $page->exists) {
            return response()->view('errors.404', ['type' => Page::class], 404);
        }

        $page->title = $request->get('title', '');

        $page->content = $request->get('content', '');

        $page->updated_at = Carbon::now()->toDateTimeString();

        if (false === $page->save()) {
            $request->session()->flash('page.create.error', trans('page.admin.error.saving'));

            return response()->redirectToRoute('admin.page.new', ['lang' => app()->getLocale()]);
        }

        $request->session()->flash('page.create.success', trans('page.admin.error.none'));

        return response()->redirectToRoute('admin.page.edit', ['lang' => app()->getLocale(), 'id' => $page->id]);
    }

    /**
     * @return int
     */
    public function getMaxFileSize()
    {
        $maxKiloBytes = ((int) ini_get('upload_max_filesize')) * 1024;

        return $maxKiloBytes;
    }


    /**
     * @param PluginPage $page
     * @return array
     */
    public function getPluginPageData(PluginPage $page)
    {
        $routes = $page->getRoutes();

        $parsedRoutes = [
            'get' => [],
            'post' => []
        ];

        foreach ($routes as $route => $routeData) {
            $parsedRoutes[$routeData['method']][] = [
                'route' => $route,
                'routeData' => $routeData
            ];
        }

        return $parsedRoutes;
    }

    /**
     * @param Request $request
     * @return Plugin
     */
    public function validateAndGetPlugin(Request $request)
    {
        if (false === $request->has('id')) {
            return response()->json(['message' => trans('admin.missingParamId')], 400)->throwResponse();
        }

        $plugin = Plugin::find($request->get('id'));

        if (false === is_object($plugin) || false === $plugin->exists) {
            return response()->json(['message' => trans('admin.pluginNotFound')], 404)->throwResponse();
        }

        if (false === class_exists($plugin->base_class)) {
            return response()->json(['message' => trans('admin.pluginBaseClassMissing')], 500)->throwResponse();
        }

        return $plugin;
    }

    /**
     *
     */
    public function makeNavigationLabels()
    {
//        $navigationLinks = NavigationLink::all();

//        NavigationLinks::formatNavigationLinks($)
    }
}