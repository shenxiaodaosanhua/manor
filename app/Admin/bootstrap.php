<?php

use App\Admin\Extensions\Show\LogList;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Show;

Encore\Admin\Form::forget(['map', 'editor']);

Show::extend('logList', LogList::class);
Admin::navbar(function (\Encore\Admin\Widgets\Navbar $navbar) {
    $navbar->right(new \App\Admin\Extensions\Nav\ClearCache());
});
