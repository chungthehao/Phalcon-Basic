<?php

/**
 * https://docs.phalcon.io/4.0/en/tutorial-basic#autoloader
 */
use Phalcon\Loader;

/**
 * https://docs.phalcon.io/4.0/en/tutorial-basic#factory-default
 */
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Url;

/**
 * https://docs.phalcon.io/4.0/en/tutorial-basic#handling-the-application-request
 */
use Phalcon\Mvc\Application;

/**
 * https://docs.phalcon.io/4.0/en/tutorial-basic#setting-a-database-connection
 */
use Phalcon\Db\Adapter\Pdo\Mysql;


define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
// ...

$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
    ]
);

$loader->register();

// Create a DI
$container = new FactoryDefault();

$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');

        return $view;
    }
);


$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');

        return $url;
    }
);

$container->set(
    'db',
    function () {
        return new Mysql(
            [
                'host'     => '127.0.0.1',
                'username' => 'root',
                'password' => '',
                'dbname'   => 'phalcon_tut_basic',
            ]
        );
    }
);

$application = new Application($container);

try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}