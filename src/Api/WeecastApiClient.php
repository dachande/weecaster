<?php

/**
 * This file is part of the Weecast application
 *
 * (c) Daniel Schultheis <d.schultheis@kabel-salat.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dachande\Weecast\Api;

use Cake\Core\Configure;
use JJG\Ping;

/**
 * The server component interacts with the Weecast server to retrieve playlists.
 *
 * @author Daniel Schultheis <d.schultheis@kabel-salat.net>
 */
class WeecastApiClient
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
    }

    /**
     * Check server reachability.
     *
     * The method returns the latency to the server or false if the server is
     * not reachable or timed out.
     *
     * @return int|false
     */
    protected function serverIsReachable()
    {
        $hostname = Configure::read('Server.hostname');
        $port = Configure::read('Server.port');
        $timeout = Configure::read('Server.timeout');

        $ping = new Ping($hostname);
        $ping->setPort($port);
        $ping->setTimeout($timeout);

        $latency = $ping->ping('fsockopen');

        return $latency;
    }

    protected function query($endpoint, $method = 'GET')
    {
        $requestUrl = $this->getURI() . '/' . $endpoint;
        $result = $this->client->request($method, $requestUrl);
    }

    /**
     * Get remote server URI.
     *
     * @return string
     */
    protected function getURI()
    {
        $protocol = Configure::read('Server.protocol');
        $hostname = Configure::read('Server.hostname');
        $port     = Configure::read('Server.port');

        return $protocol . '://' . $hostname . ':' . $port;
    }
}
