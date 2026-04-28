<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFactory;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EsoftTemplateController extends Controller
{
    public function root(): View
    {
        return view('esoft.index');
    }

    public function firstLevel(string $any): View
    {
        return $this->render([$any]);
    }

    public function secondLevel(string $first, string $second): View
    {
        return $this->render([$first, $second]);
    }

    public function thirdLevel(string $first, string $second, string $third): View
    {
        return $this->render([$first, $second, $third]);
    }

    protected function render(array $segments): View
    {
        $view = 'esoft.'.implode('.', $segments);

        if (ViewFactory::exists($view)) {
            return view($view);
        }

        $pageView = 'esoft.pages.'.implode('.', $segments);

        if (ViewFactory::exists($pageView)) {
            return view($pageView);
        }

        throw new NotFoundHttpException;
    }
}
