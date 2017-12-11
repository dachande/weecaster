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

use Dachande\Weecast\Uuid\Uuid;
use Dachande\Weecast\Uuid\LastUuid;
use Dachande\Weecast\Mpc\Mpc;
use Kraken\Loop\Loop;
use Kraken\Loop\Model\SelectLoop;
use Kraken\Ipc\Socket\Socket;

/**
 * This code describes the "play" console command.
 *
 * @author Daniel Schultheis <d.schultheis@kabel-salat.net>
 */
class PlayCommand extends Command
{
    protected $signature = 'play
                            {uuid : The RFID tag uuid}';

    protected $description = 'Play a playlist by its associated RFID tag uuid';

    protected $help =
        'This command accepts a RFID tag uuid as an argument and uses this uuid ' .
        'to play the associated playlist. The playlist will either be ' .
        'retrieved from a remote server or alternatively loaded locally ' .
        'if the remote server cannot be reached.';

    public function __construct()
    {
        parent::__construct();
    }

    protected function handle()
    {
        // Get rfid tag uuid
        $uuid = new Uuid($this->argument('uuid'));

        Mpc::play();

        $this->output->success('Command executed.');

        // $this->output->success('Play command triggered.');
        // $response = Mpc::hasAdminAccess();
        // print var_export($response, 1);
    }
}
