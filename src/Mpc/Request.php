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

use Kraken\Ipc\Socket\SocketInterface;

/**
 * The MPC request object.
 *
 * @author Daniel Schultheis <d.schultheis@kabel-salat.net>
 */
class Request
{
    /**
     * @var \Kraken\Ipc\Socket\SocketInterface
     */
    protected $socket;

    /**
     * @var array
     */
    protected $commands = [];

    /**
     * Create request.
     *
     * @param \Kraken\Ipc\Socket\SocketInterface $socket
     */
    public function __construct(SocketInterface $socket)
    {
        $this->socket = $socket;
    }

    /**
     * Add command to the command chain.
     *
     * @param string $command
     * @return void
     */
    public function chain(string $command)
    {
        array_push($this->commands, $command);
    }

    /**
     * Get next command from command chain
     *
     * @return string
     */
    public function unchain()
    {
        return array_shift($this->commands);
    }

    /**
     * Send request through socket.
     *
     * @param \Dachande\Weecast\Mpc\Response $response
     * @param string $command
     * @return void
     */
    public function send(\Dachande\Weecast\Mpc\Response $response, string $command)
    {
        // Add command to chain
        $this->chain($command);

        $socket = $this->socket;
        $loop = $socket->getLoop();

        // Add event to receive server response
        $socket->on('data', function (SocketInterface $client, $data) use ($loop, $response) {
            // Store received data in response
            $response->addData($data);

            // If server finished sending data, close socket
            if (preg_match('/.*OK$/', trim($data)) || preg_match('/^ACK.*/', trim($data))) {
                $command = $this->unchain();
                if ($command != null) {
                     $client->write($command);
                } else {
                    $client->close();
                }
            }
        });

        // Add event to stop loop when socket is closed
        $socket->on('close', function () use ($loop, $response) {
            $loop->stop();
        });

        // Add event to send command to server when event loop starts.
        $loop->onStart(function () use ($socket) {
            $command = $this->unchain();
            if ($command != null) {
                 $socket->write($command);
            } else {
                $socket->close();
            }
        });

        // Start event loop
        $loop->start();
    }
}
