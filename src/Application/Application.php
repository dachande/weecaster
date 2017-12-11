<?php

/**
 * This file is part of the Weecast application
 *
 * (c) Daniel Schultheis <d.schultheis@kabel-salat.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dachande\Weecast\Application;

use Symfony\Component\Console\Application as ConsoleApplication;
use Cake\Core\Configure;

/**
 * The main application class that initializes the Symfony console application
 *
 * @author Daniel Schultheis <d.schultheis@kabel-salat.net>
 */
class Application
{
    public function registerCommands()
    {
        return [
            new \Dachande\Weecast\Console\Command\PlayCommand,
            // new \Dachande\Weecast\Console\Command\PauseCommand,
        ];
    }

    public function run()
    {
        $consoleApplication = new ConsoleApplication(Configure::read('App.name'), Configure::read('App.version'));
        $consoleApplication->addCommands($this->registerCommands());
        $consoleApplication->run();
    }
}
