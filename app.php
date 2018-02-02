<?php
use Symfony\Component\Console\Application;

require './vendor/autoload.php';


$app = new Application();
$app->add(new \App\Command\Import());
$app->run();

