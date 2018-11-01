<?php
require '../../vendor/autoload.php';

$app = new \Sherpa\App\App(true);

$app->addDeclaration(\Sherpa\Rest\Declaration::class);
$app->addDeclaration(\Sherpa\Rest\Declarations\DoctrineDeclarations::class);

$app->set('project.namespace', '');
$app->set('project.src', '');

$map = $app->getMap();
