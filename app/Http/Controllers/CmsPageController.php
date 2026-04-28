<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View as ViewFactory;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CmsPageController extends Controller
{
    public function home(): View
    {
        return $this->renderSlug('home');
    }

    public function show(string $slug): View
    {
        return $this->renderSlug($slug);
    }

    protected function renderSlug(string $slug): View
    {
        $page = CmsPage::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (! $page || (method_exists($page, 'isPublished') && ! $page->isPublished())) {
            throw new NotFoundHttpException;
        }

        $settings = Schema::hasTable('platform_settings')
            ? DB::table('platform_settings')->first()
            : null;

        $mappedTemplate = $this->esoftTemplateForSlug($slug);

        if ($mappedTemplate && ViewFactory::exists($mappedTemplate)) {
            return view($mappedTemplate, [
                'page' => $page,
                'settings' => $settings,
            ]);
        }

        $templateView = (string) ($page->template_view ?? '');

        if ($templateView !== '' && ViewFactory::exists($templateView)) {
            return view($templateView, [
                'page' => $page,
                'settings' => $settings,
            ]);
        }

        return view('cms.page', [
            'page' => $page,
            'settings' => $settings,
        ]);
    }

    protected function esoftTemplateForSlug(string $slug): ?string
    {
        return [
            'template-esoft' => 'esoft.template-gallery',

            // FIXED: previously pointed to esoft.index (gallery layout)
            'template-esoft-home' => 'esoft.demo.index1',

            'template-esoft-home-1' => 'esoft.demo.index1',
            'template-esoft-home-2' => 'esoft.demo.index2',
            'template-esoft-home-3' => 'esoft.demo.index3',
            'template-esoft-home-4' => 'esoft.demo.index4',
            'template-esoft-home-5' => 'esoft.demo.index5',
            'template-esoft-home-6' => 'esoft.demo.index6',
            'template-esoft-home-7' => 'esoft.demo.index7',
            'template-esoft-home-8' => 'esoft.demo.index8',
            'template-esoft-home-9' => 'esoft.demo.index9',

            'template-esoft-demo-2' => 'esoft.demo.index2',
            'template-esoft-demo-3' => 'esoft.demo.index3',
            'template-esoft-demo-4' => 'esoft.demo.index4',
            'template-esoft-demo-5' => 'esoft.demo.index5',
            'template-esoft-demo-6' => 'esoft.demo.index6',
            'template-esoft-demo-7' => 'esoft.demo.index7',
            'template-esoft-demo-8' => 'esoft.demo.index8',
            'template-esoft-demo-9' => 'esoft.demo.index9',

            'template-esoft-single-1' => 'esoft.single.index1',
            'template-esoft-single-2' => 'esoft.single.index2',
            'template-esoft-single-3' => 'esoft.single.index3',
            'template-esoft-single-4' => 'esoft.single.index4',
            'template-esoft-single-5' => 'esoft.single.index5',
            'template-esoft-single-6' => 'esoft.single.index6',
            'template-esoft-single-7' => 'esoft.single.index7',
            'template-esoft-single-8' => 'esoft.single.index8',
            'template-esoft-single-9' => 'esoft.single.index9',

            'template-esoft-about' => 'esoft.pages.about',
            'template-esoft-features' => 'esoft.pages.features',
            'template-esoft-pricing' => 'esoft.pages.pricing',
            'template-esoft-contact' => 'esoft.pages.contact',
            'template-esoft-download' => 'esoft.pages.download',
            'template-esoft-testimonial' => 'esoft.pages.testimonial',

            'template-esoft-blog' => 'esoft.blogs.blog',
            'template-esoft-blog-details' => 'esoft.blogs.details',

            'template-esoft-login' => 'esoft.auth.login',
            'template-esoft-account' => 'esoft.auth.account',
            'template-esoft-forgot' => 'esoft.auth.forgot',
            'template-esoft-reset' => 'esoft.auth.reset',
            'template-esoft-verify' => 'esoft.auth.verify',
        ][$slug] ?? null;
    }
}