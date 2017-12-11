<?php

/**
 * This file is part of the Weecast application
 *
 * (c) Daniel Schultheis <d.schultheis@kabel-salat.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
