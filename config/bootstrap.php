<?php

// Load paths
require __DIR__ . '/paths.php';

use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;

// Load configuration
try {
    Configure::config('default', new PhpConfig());
    Configure::load('app', 'default', false);
} catch (\Exception $e) {
    exit($e->getMessage() . "\n");
}

// Set app name and version
Configure::write('App.name', 'Weecast');
Configure::write('App.version', '0.2.0');
