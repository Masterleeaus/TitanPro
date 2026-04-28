<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class EsoftMultiPageTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            ['eSoft Template Library', 'template-esoft', 'esoft.template-gallery', 'Browse all imported eSoft homepage, single-page, and inner-page templates.'],
            ['eSoft Home', 'template-esoft-home', 'esoft.index', 'Original eSoft homepage layout.'],
            ['eSoft Home 1', 'template-esoft-home-1', 'esoft.index', 'Original eSoft homepage layout 1.'],
            ['eSoft Home 2', 'template-esoft-home-2', 'esoft.demo.index2', 'Original eSoft homepage layout 2.'],
            ['eSoft Home 3', 'template-esoft-home-3', 'esoft.demo.index3', 'Original eSoft homepage layout 3.'],
            ['eSoft Home 4', 'template-esoft-home-4', 'esoft.demo.index4', 'Original eSoft homepage layout 4.'],
            ['eSoft Home 5', 'template-esoft-home-5', 'esoft.demo.index5', 'Original eSoft homepage layout 5.'],
            ['eSoft Home 6', 'template-esoft-home-6', 'esoft.demo.index6', 'Original eSoft homepage layout 6.'],
            ['eSoft Home 7', 'template-esoft-home-7', 'esoft.demo.index7', 'Original eSoft homepage layout 7.'],
            ['eSoft Home 8', 'template-esoft-home-8', 'esoft.demo.index8', 'Original eSoft homepage layout 8.'],
            ['eSoft Home 9', 'template-esoft-home-9', 'esoft.demo.index9', 'Original eSoft homepage layout 9.'],
            ['eSoft Demo 2', 'template-esoft-demo-2', 'esoft.demo.index2', 'Original eSoft demo layout 2.'],
            ['eSoft Demo 3', 'template-esoft-demo-3', 'esoft.demo.index3', 'Original eSoft demo layout 3.'],
            ['eSoft Demo 4', 'template-esoft-demo-4', 'esoft.demo.index4', 'Original eSoft demo layout 4.'],
            ['eSoft Demo 5', 'template-esoft-demo-5', 'esoft.demo.index5', 'Original eSoft demo layout 5.'],
            ['eSoft Demo 6', 'template-esoft-demo-6', 'esoft.demo.index6', 'Original eSoft demo layout 6.'],
            ['eSoft Demo 7', 'template-esoft-demo-7', 'esoft.demo.index7', 'Original eSoft demo layout 7.'],
            ['eSoft Demo 8', 'template-esoft-demo-8', 'esoft.demo.index8', 'Original eSoft demo layout 8.'],
            ['eSoft Demo 9', 'template-esoft-demo-9', 'esoft.demo.index9', 'Original eSoft demo layout 9.'],
            ['eSoft Single Page 1', 'template-esoft-single-1', 'esoft.single.index1', 'Original eSoft single page layout 1.'],
            ['eSoft Single Page 2', 'template-esoft-single-2', 'esoft.single.index2', 'Original eSoft single page layout 2.'],
            ['eSoft Single Page 3', 'template-esoft-single-3', 'esoft.single.index3', 'Original eSoft single page layout 3.'],
            ['eSoft Single Page 4', 'template-esoft-single-4', 'esoft.single.index4', 'Original eSoft single page layout 4.'],
            ['eSoft Single Page 5', 'template-esoft-single-5', 'esoft.single.index5', 'Original eSoft single page layout 5.'],
            ['eSoft Single Page 6', 'template-esoft-single-6', 'esoft.single.index6', 'Original eSoft single page layout 6.'],
            ['eSoft Single Page 7', 'template-esoft-single-7', 'esoft.single.index7', 'Original eSoft single page layout 7.'],
            ['eSoft Single Page 8', 'template-esoft-single-8', 'esoft.single.index8', 'Original eSoft single page layout 8.'],
            ['eSoft Single Page 9', 'template-esoft-single-9', 'esoft.single.index9', 'Original eSoft single page layout 9.'],
            ['eSoft About', 'template-esoft-about', 'esoft.pages.about', 'Original eSoft about page.'],
            ['eSoft Features', 'template-esoft-features', 'esoft.pages.features', 'Original eSoft features page.'],
            ['eSoft Pricing', 'template-esoft-pricing', 'esoft.pages.pricing', 'Original eSoft pricing page.'],
            ['eSoft Contact', 'template-esoft-contact', 'esoft.pages.contact', 'Original eSoft contact page.'],
            ['eSoft Download', 'template-esoft-download', 'esoft.pages.download', 'Original eSoft download page.'],
            ['eSoft Testimonials', 'template-esoft-testimonial', 'esoft.pages.testimonial', 'Original eSoft testimonial page.'],
            ['eSoft Blog', 'template-esoft-blog', 'esoft.blogs.blog', 'Original eSoft blog listing page.'],
            ['eSoft Blog Detail', 'template-esoft-blog-details', 'esoft.blogs.details', 'Original eSoft blog detail page.'],
            ['eSoft Login', 'template-esoft-login', 'esoft.auth.login', 'Original eSoft login page.'],
            ['eSoft Account', 'template-esoft-account', 'esoft.auth.account', 'Original eSoft account page.'],
            ['eSoft Forgot Password', 'template-esoft-forgot', 'esoft.auth.forgot', 'Original eSoft forgot password page.'],
            ['eSoft Reset Password', 'template-esoft-reset', 'esoft.auth.reset', 'Original eSoft reset password page.'],
            ['eSoft Verify', 'template-esoft-verify', 'esoft.auth.verify', 'Original eSoft verification page.'],
        ];

        foreach ($pages as [$title, $slug, $templateView, $summary]) {
            $payload = [
                'title' => $title,
                'summary' => $summary,
                'meta_title' => $title,
                'meta_description' => $summary,
                'status' => 'published',
                'website_content' => [
                    [
                        'type' => 'RichTextBlock',
                        'data' => [
                            'heading' => $title,
                            'body' => '<p>This CMS record renders: <strong>'.$templateView.'</strong>.</p>',
                        ],
                    ],
                ],
                'published_at' => now(),
            ];

            if (Schema::hasColumn('cms_pages', 'template_view')) {
                $payload['template_view'] = $templateView;
            }

            CmsPage::query()->updateOrCreate(['slug' => $slug], $payload);
        }
    }
}
