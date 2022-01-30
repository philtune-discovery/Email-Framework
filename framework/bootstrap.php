<?php

use App\App;
use NodeBuilder\NodeBuilder;

include __DIR__ . '/console.php';
include __DIR__ . '/autoloader.php';
include __DIR__ . '/helpers.php';
include __DIR__ . '/definitions.php';

include __DIR__ . './../functions.php';

App::setResourcePath(LOCAL_RESOURCE_PATH);
App::output(view('pages/home'));

// write prod views
App::setResourcePath(PROD_RESOURCE_PATH);
App::writeToFile(view('pages/home'), 'dist/email.htm');
