<?php

namespace Dachande\Weecast\Mpc;

/**
 * Mpc CommandList object
 */
class CommandList
{
    /**
     * @var array
     */
    protected $commands;

    /**
     * @var \Dachande\Weecast\Mpc\Request
     */
    protected $request;

    /**
     * @var \Dachande\Weecast\Mpc\Response
     */
    protected $response;

    /**
     * Create command.
     * @param \Dachande\Weecast\Mpc\Request $request
     * @param \Dachande\Weecast\Mpc\Response $response
     * @param array $commands
     */
    public function __construct(
        \Dachande\Weecast\Mpc\Request $request,
        \Dachande\Weecast\Mpc\Response $response,
        array $commands = []
    ) {
        $this->request = $request;
        $this->response = $response;

        foreach ($commands as $command) {
            $this->addCommand($command);
        }
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
     * Add command to command list.
     *
     * @param \Dachande\Weecast\Mpc\Command $command
     * @return void
     */
    public function addCommand(\Dachande\Weecast\Mpc\Command $command)
    {
        $this->commands[] = $command;
    }

    /**
     * Execute command list.
     *
     * @return \Dachande\Weecast\Mpc\Response
     */
    public function execute()
    {
        $keys = array_keys($this->commands);
        $lastKey = end($keys);
        foreach ($this->commands as $key => $command) {
            $chainCommand = ($key == $lastKey) ? false : true;

            /**
             * @var \Dachande\Weecast\Mpc\Command $command
             */
            $command->setRequest($this->request);
            $command->setResponse($this->response);
            $command->execute($chainCommand);
        }

        return $this->response;
    }
}
