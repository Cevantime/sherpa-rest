<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Zend\Diactoros\ServerRequestFactory;

require __DIR__.'/../init.php';

$app->boot();

return ConsoleRunner::createHelperSet($app->get('doctrine.manager'));
