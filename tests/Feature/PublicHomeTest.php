<?php

use function Pest\Laravel\get;

it('shows the public home', function(){
    get(route('home'))->assertOk()->assertSee('Sistem Pengelolaan');
});

