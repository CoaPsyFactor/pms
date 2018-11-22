<?php
/**
 * Created by PhpStorm.
 * User: azivanovic
 * Date: 10/11/16
 * Time: 6:37 PM
 */

namespace App\Http\Controllers\Api;

use App\Helpers\Controllers\AdminControllerHelper;
use App\Helpers\Plugin\PluginManager;
use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

use App\ErrorCodes\PluginManager as PluginManagerErrorCode;
use App\Helpers\Plugin\Plugin as PluginHelper;

class AdminController extends Controller
{
    /** @var PluginManager */
    private $pluginManager;

    /** @var AdminControllerHelper */
    private $adminControllerHelper;

    /**
     * AdminController constructor.
     * @param PluginManager $pluginManager
     */
    public function __construct(PluginManager $pluginManager, AdminControllerHelper $adminControllerHelper)
    {
        $this->pluginManager = $pluginManager;

        $this->adminControllerHelper = $adminControllerHelper;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function togglePluginStatus(Request $request)
    {
        $plugin = $this->adminControllerHelper->validateAndGetPlugin($request);

        $plugin->active = !$plugin->active;

        if ($plugin->save()) {
            return response()->json(['message' => trans('admin.settingsSaved'), 'active' => $plugin->active]);
        }

        return response()->json(['message' => trans('admin.settingsNotSaved')], 500);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPluginPages(Request $request)
    {
        $plugin = $this->adminControllerHelper->validateAndGetPlugin($request);

        /** @var PluginHelper $pluginBaseClass */
        $pluginBaseClass = new $plugin->base_class;

        if (false === $pluginBaseClass instanceof PluginHelper) {
            return response()->json(['message' => trans('admin.pluginInvalidBaseClass')], 500);
        }

        $pages = $pluginBaseClass->registerPages();

        if (false === is_array($pages)) {
            return response()->json(['pages' => []]);
        }

        $data = [
            'pages' => []
        ];

        foreach ($pages as $page) {
            $data['pages'][(new \ReflectionClass($page))->getShortName()] = array_merge(['type' => $page->getPluginPageType()], $this->adminControllerHelper->getPluginPageData($page));
        }

        return response()->json(['data' => $data]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uninstallPlugin(Request $request)
    {
        $pluginId = $request->get('id', 0);

        $status = $this->pluginManager->uninstallPlugin($pluginId);

        if (PluginManagerErrorCode::ERROR_NONE === $this->pluginManager->uninstallPlugin($pluginId)) {
            return response()->json(['message' => trans('admin.plugin.uninstall.success')]);
        }

        return response()->json(['message' => trans("admin.plugin.uninstall.error.{$status}")], 500);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function togglePageStatus(Request $request)
    {
        $pageId = $request->get('id', null);

        if (null === $pageId) {
            return response()->json(['message' => trans('admin.missingParamId')], 400);
        }

        $page = Page::find($pageId);

        if (false === is_object($page) || false === $page->exists) {
            return response()->json(['message' => trans('admin.pageNotFound')], 404);
        }

        $page->active = !$page->active;

        if ($page->save()) {
            return response()->json(['message' => trans('admin.settingsSaved'), 'id' => $page->id, 'active' => (bool) $page->active]);
        }

        return response()->json(['message' => trans('admin.settingsNotSaved')]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removePage(Request $request)
    {
        if (false === $request->has('id')) {
            return response()->json(['message' => trans('admin.missingParamId')], 400);
        }

        $page = Page::find($request->get('id'));

        if (false === is_object($page) || false === $page->exists) {
            return response()->json(['message' => trans('page.notFound')], 404);
        }

        if ($page->delete()) {
            return response()->json(['message' => trans('page.removed'), 'id' => $page->id]);
        }

        return response()->json(['message' => trans('page.admin.error.removing')], 500);
    }

    public function getNavigationLinks()
    {

    }
}