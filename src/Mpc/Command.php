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

/**
 * A single command that can be send to a MPD server.
 *
 * @author Daniel Schultheis <d.schultheis@kabel-salat.net>
 */
class Command
{
    /**
     * @var \Dachande\Weecast\Mpc\Request
     */
    protected $request;

    /**
     * @var \Dachande\Weecast\Mpc\Response
     */
    protected $response;

    /**
     * @var string
     */
    private $method;

    /**
     * @var array
     */
    private $arguments;

    /**
     * Create command.
     * @param string $method
     * @param array $arguments
     * @param \Dachande\Weecast\Mpc\Request|null $request
     * @param \Dachande\Weecast\Mpc\Response|null $response
     */
    public function __construct(
        string $method,
        array $arguments = [],
        \Dachande\Weecast\Mpc\Request $request = null,
        \Dachande\Weecast\Mpc\Response $response = null
    ) {
        $this->method = $method;
        $this->arguments = $this->condense($arguments);
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Set request object.
     *
     * @param \Dachande\Weecast\Mpc\Request $request
     * @return void
     */
    public function setRequest(\Dachande\Weecast\Mpc\Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get request object.
     *
     * @return \Dachande\Weecast\Mpc\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Set response object.
     *
     * @param \Dachande\Weecast\Mpc\Response $response
     * @return void
     */
    public function setResponse(\Dachande\Weecast\Mpc\Response $response)
    {
        $this->response = $response;
    }

    /**
     * Get response object.
     *
     * @return \Dachande\Weecast\Mpc\Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Execute command.
     *
     * @param bool $chainCommand
     * @return \Dachande\Weecast\Mpc\Response
     */
    public function execute($chainCommand = false)
    {
        if ($this->request == null) {
            throw new \Dachande\Weecast\Mpc\Exception\CommandException('Request object missing.');
        }

        if ($this->response == null) {
            throw new \Dachande\Weecast\Mpc\Exception\CommandException('Response object missing.');
        }

        $command = trim($this->method . ' ' . $this->getPreparedArguments($this->arguments)) . "\n";
        if ($chainCommand === true) {
            $this->request->chain($command);
        } else {
            $this->request->send($this->response, $command);
        }

        return $this->getResponse();
    }

    /**
     * Flattens and converts command argument list
     *
     * @param array $arguments
     * @return array
     */
    private function condense(array $arguments)
    {
        $result = [];

        foreach (array_values($arguments) as $value) {
            if (is_scalar($value)) {
                $result[] = $value;
            } elseif (is_array($value)) {
                $result = array_merge($result, $this->condense($value));
            } elseif (is_object($value)) {
                $result = array_merge($result, $this->condense((array)$value));
            } else {
                throw new \Exception("Unrecognized object type");
            }
        }

        return $result;
    }

    /**
     * Prepare arguments for command execution.
     *
     * @param array $arguments
     * @return string
     */
    private function getPreparedArguments(array $arguments)
    {
        array_walk($arguments, function (&$value, $key) {
            $value = str_replace('"', '\"', $value);
            $value = str_replace("'", "\\'", $value);
            $value = '"' . $value . '"';
        });

        return implode(' ', $arguments);
    }
}
