<?php

test('app sidebar includes groundzero and zeropay product switcher links', function () {
    $sidebar = file_get_contents(resource_path('js/components/AppSidebar.vue'));

    expect($sidebar)->toContain("title: 'GroundZero Panel'")
        ->and($sidebar)->toContain("href: '/groundzero'")
        ->and($sidebar)->toContain("title: 'ZeroPay Panel'")
        ->and($sidebar)->toContain("href: '/zeropay'");
});

test('groundzero and zeropay panel roles match product switcher access gates', function () {
    $panels = config('titan_panels.panels');

    expect($panels)->toHaveKey('groundzero')
        ->and($panels['groundzero']['roles'])->toBe(['owner', 'admin', 'dispatcher', 'bookkeeper'])
        ->and($panels)->toHaveKey('zeropay')
        ->and($panels['zeropay']['roles'])->toBe(['owner', 'admin', 'bookkeeper']);
});

test('product switcher uses url active-state logic', function () {
    $navMain = file_get_contents(resource_path('js/components/NavMain.vue'));

    expect($navMain)->toContain(':is-active="urlIsActive(item.href, page.url)"');
});
