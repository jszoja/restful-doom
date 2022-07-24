<?php

require "vendor/autoload.php";

use Symfony\Component\Console\Application;
use App\Command\Play;


$application = new Application();

// ... register commands
$application->add(new Play());

$application->run();