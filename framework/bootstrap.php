<?php

use App\App;
use DOM\DOM;

include 'console.php';
include 'autoloader.php';
include 'helpers.php';

include 'definitions.php';

include 'functions.php';

App::setResourcePath(LOCAL_RESOURCE_PATH);
App::output(view('pages/home'));

// write prod views
App::setResourcePath(PROD_RESOURCE_PATH);
App::writeToFile(view('pages/home'), 'dist/email.htm');
