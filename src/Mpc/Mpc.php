<?php

/**
 * This file is part of the Weecast application
 *
 * (c) Daniel Schultheis <d.schultheis@kabel-salat.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dachande\Weecast\Mpc;

use Kraken\Loop\Loop;
use Kraken\Loop\Model\SelectLoop;
use Kraken\Ipc\Socket\Socket;
use Cake\Core\Configure;

/**
 * The main MPC class that can execute and send any possible command to a MPD server.
 *
 * @author Daniel Schultheis <d.schultheis@kabel-salat.net>
 */
class Mpc
{
    /**
     * Magic method to send commands to MPD server.
     *
     * @param string $command
     * @param array  $arguments
     * @return \Dachande\Weecast\Mpc\Response
     */
    public static function __callStatic(string $command, array $arguments)
    {
        // Initialize event loop and socket connection
        $loop = new Loop(new SelectLoop);
        $socket = new Socket('tcp://' . Configure::read('Mpd.host') . ':' . Configure::read('Mpd.port'), $loop);

        // Create new command list
        $commandList = new CommandList(
            new Request($socket),
            new Response
        );

        if (Configure::read('Mpd.authenticate') === true) {
            // Add password command for authentication
            $commandList->addCommand(new Command('password', [Configure::read('Mpd.password')]));
        }

        // Add command and execute
        $commandList->addCommand(new Command($command, $arguments));
        $commandList->execute();

        // Get and check response
        $response = $commandList->getResponse();
        $response->parseData();
        $response = $response->getData();

        return $response[sizeof($response) - 1];
    }

    /**
     * Parses the response from the MPD server.
     *
     * @param  string $response
     * @return string
     */
    protected static function parseResponse($response)
    {
        $response = preg_split('/\n/', $response);

        $keyedResponse = [];

        // Parse each result line
        foreach ($response as $single) {
            // Check for delimiter splitting key and value
            if (strpos($single, ': ') === false || $single === 'OK') {
                continue;
            }

            // Split
            list($key, $value) = explode(': ', $single);

            // Skip empty key/value
            if (!strlen($key) || !strlen($value)) {
                continue;
            }

            // Check if key is used multiple times
            if (array_key_exists($key, $keyedResponse)) {
                if (!is_array($keyedResponse[$key])) {
                    // Convert to indexed array
                    $currentValue = $keyedResponse[$key];
                    $keyedResponse[$key] = [];
                    $keyedResponse[$key][] = $currentValue;
                    $keyedResponse[$key][] = $value;
                } else {
                    $keyedResponse[$key][] = $value;
                }
            } else {
                $keyedResponse[$key] = $value;
            }
        }

        return $keyedResponse;
    }

    /**
     * Checks if admin access on the MPD server is available.
     *
     * @return bool
     */
    public static function hasAdminAccess()
    {
        $response = static::commands();
        $commands = static::parseResponse($response);

        return (in_array('update', $commands['command'])) ? true : false;
    }
}
