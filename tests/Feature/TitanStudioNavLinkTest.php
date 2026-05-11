<?php

test('app sidebar includes titanstudio product switcher link', function () {
    $sidebar = file_get_contents(resource_path('js/components/AppSidebar.vue'));

    expect($sidebar)->toContain("title: 'TitanStudio Panel'")
        ->and($sidebar)->toContain("href: '/titanstudio'");
});
