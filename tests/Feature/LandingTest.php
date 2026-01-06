<?php

use function Pest\Laravel\get;

it('can see landing page', function () {
    $res = get(route('home'));
    $res->assertOk()->assertSee('Sistem Pengelolaan Masjid');
});

