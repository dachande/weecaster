<?php

namespace Dachande\Weecast\Application;

use Symfony\Component\Console\Application as ConsoleApplication;
use Cake\Core\Configure;

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
