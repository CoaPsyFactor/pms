<?php
/**
 * Created by PhpStorm.
 * User: zvekete
 * Date: 11.10.2016.
 * Time: 01.13
 */

namespace App\Http\Controllers;

use App\Helpers\Controllers\AdminControllerHelper;
use App\Helpers\NavigationLinks;
use App\Helpers\Plugin\PluginManager;
use App\Models\Page;
use App\Models\Plugin;
use Illuminate\Http\Request;

use App\ErrorCodes\PluginManager as PluginManagerErrorCode;

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
     * @return \Illuminate\Http\Response
     */
    public function plugins(Request $request)
    {
        return response()->view('admin.plugins', ['plugins' => Plugin::all()]);
    }

    public function pages(Request $request)
    {
        return response()->view('admin.pages', ['pages' => Page::all()]);
    }

    /**
     * @param Request $request
     * @param string $lang
     * @param int $pageId
     * @return \Illuminate\Http\Response
     */
    public function editPage(Request $request, $lang, $pageId)
    {
        $page = Page::find($pageId);

        if (false === is_object($page) || false === $page->exists) {
            return response()->view('errors.404', ['type' => Page::class], 404);
        }

        return response()->view('admin.page.edit', ['lang' => app()->getLocale(), 'page' => $page]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function newPage()
    {
        return response()->view('admin.page.edit', ['lang' => app()->getLocale()]);
    }

    /**
     * @param Request $request
     * @param string $lang
     * @param int $pageId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function createPage(Request $request, $lang, $pageId = 0)
    {
        $validatorRules = [
            'title' => 'required|min:3|max:64'
        ];

        $validator = \Validator::make($request->toArray(), $validatorRules);

        if ($validator->fails()) {
            $request->session()->flash('page.create.error', implode(', ', $validator->errors()->all()));

            return response()->redirectToRoute('admin.page.new', ['lang' => app()->getLocale()]);
        }

        if ($pageId) {
            return $this->adminControllerHelper->updatePage($pageId, $request);

        }

        return $this->adminControllerHelper->createNewPage($request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function installPlugin(Request $request)
    {
        $validatorRules = ['plugin' => "required|mimes:zip|max:{$this->adminControllerHelper->getMaxFileSize()}"];

        $validator = \Validator::make($request->toArray(), $validatorRules);

        if ($validator->fails()) {
            $request->session()->flash('plugin.install.error', implode(', ', $validator->errors()->all()));

            return response()->redirectToRoute('admin.plugins', ['lang' => app()->getLocale()]);
        }

        $plugin = $request->file('plugin');

        if (UPLOAD_ERR_OK !== $plugin->getError()) {
            $request->session()->flash('plugin.install.error', trans("admin.plugin.install.error.{$plugin->getError()}"));

            return response()->redirectToRoute('admin.plugins', ['lang' => app()->getLocale()]);
        }

        $savedPlugin = $plugin->move(storage_path('uploads'), $plugin->getClientOriginalName());

        $installStatus = $this->pluginManager->installPlugin($savedPlugin->getRealPath());

        if (PluginManagerErrorCode::ERROR_NONE === $installStatus) {
            $request->session()->flash('plugin.install.success', trans('admin.plugin.install.success'));

            return response()->redirectToRoute('admin.plugins', ['lang' => app()->getLocale()]);
        }

        $request->session()->flash('plugin.install.error', trans("admin.plugin.installation.error.{$installStatus}"));

        return response()->redirectToRoute('admin.plugins', ['lang' => app()->getLocale()]);
    }

    public function navigationLinks(Request $request)
    {
        $navigationLinks = NavigationLinks::getFormattedNavigationLinks()->sortBy('id')->sortBy('parent');

        return response()->view('admin.navigation', ['navigationLinks' => $navigationLinks]);
    }
}