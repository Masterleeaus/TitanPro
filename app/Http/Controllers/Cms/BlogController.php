<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Support\Cms\TomatoBlogBridge;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogController extends Controller
{
    public function index(): View
    {
        return view('cms.blog.index', [
            'posts' => TomatoBlogBridge::posts(),
            'stats' => TomatoBlogBridge::stats(),
        ]);
    }

    public function show(string $slug): View
    {
        $post = TomatoBlogBridge::findBySlug($slug);

        if (! $post) {
            throw new NotFoundHttpException;
        }

        return view('cms.blog.show', [
            'post' => $post,
            'stats' => TomatoBlogBridge::stats(),
        ]);
    }
}
