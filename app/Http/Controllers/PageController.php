<?php

namespace App\Http\Controllers;

use App\Helpers\Plugin\PluginWidgetParser;
use App\Models\NavigationLink;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /** @var PluginWidgetParser */
    private $pageContentParser;

    /**
     * PageController constructor.
     * @param PluginWidgetParser $pageContentParser
     */
    public function __construct(PluginWidgetParser $pageContentParser)
    {
        $this->pageContentParser = $pageContentParser;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function navigationLinkRender(Request $request)
    {
        /** @var NavigationLink $navigationLink */
        $navigationLink = $request->get('navigation_link');

        if (false === $navigationLink->isInternal()) {
            return redirect($navigationLink->value);
        }

        $page = Page::getActivePageWithId($navigationLink->value);

        if (false === $page instanceof Page) {
            return response()->view('errors.404', ['type' => Page::class], 404);
        }

        return $this->getPageResponseView($page);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function renderPage(Request $request)
    {
        return $this->getPageResponseView($request->get('page'));
    }

    /**
     * @param Page $page
     * @return \Illuminate\Http\Response
     */
    private function getPageResponseView(Page $page)
    {
        $page->content = $this->pageContentParser->parsePageContent($page->content);

        return response()->view('partial.page', ['page' => $page]);
    }
}
