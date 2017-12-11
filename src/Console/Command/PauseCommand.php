<?php

/**
 * This file is part of the Weecast application
 *
 * (c) Daniel Schultheis <d.schultheis@kabel-salat.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dachande\Weecast\Console\Command;

/**
 * This code describes the "pause" console command.
 *
 * @author Daniel Schultheis <d.schultheis@kabel-salat.net>
 */
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
