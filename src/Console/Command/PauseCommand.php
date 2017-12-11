<?php

namespace Dachande\Weecast\Console\Command;

class PauseCommand extends Command
{
    protected $signature = 'pause';

    protected $description = 'Pause/unpause the currently playing track';

    protected $help =
        'If a track is currently playing it will be paused when issuing this ' .
        'command. The track will be unpaused/resumed when this command is ' .
        'being issued again.';

    public function __construct()
    {
        parent::__construct();
    }

    protected function handle()
    {
    }
}
