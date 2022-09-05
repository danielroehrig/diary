<?php
/**
 * Create your routes in here. The name is the lowercase name of the controller
 * without the controller part, the stuff after the hash is the method.
 * e.g. page#index -> OCA\Diary\Controller\PageController->index()
 *
 * The controller class has to be registered in the application.php file since
 * it's instantiated in there
 */
return [
    'routes' => [
        ['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
        ['name' => 'page#index', 'url' => '/date/{date}', 'verb' => 'GET', 'postfix' => 'catchAll'],
        ['name' => 'page#get_entry', 'url' => '/entry/{date}', 'verb' => 'GET'],
        ['name' => 'page#get_last_entries', 'url' => '/entries/{amount}', 'verb' => 'GET'],
        ['name' => 'page#update_entry', 'url' => '/entry/{date}', 'verb' => 'PUT'],
        ['name' => 'export#get_markdown', 'url' => '/export/markdown', 'verb' => 'GET'],
        ['name' => 'export#get_pdf', 'url' => '/export/pdf', 'verb' => 'GET'],
    ]
];
