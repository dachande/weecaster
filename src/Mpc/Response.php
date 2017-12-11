<?php

namespace Dachande\Weecast\Mpc;

/**
 * Mpc Response Object
 */
class Response
{
    /**
     * @var string
     */
    const RESPONSE_OK = 'OK';

    /**
     * @var string
     */
    const RESPONSE_ERROR = 'ACK';

    /**
     * @var array
     */
    protected $data = [];

    /**
     * Set response data.
     *
     * @param array $data
     * @return void
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * Add response data.
     *
     * @param string $data
     * @return void
     */
    public function addData(string $data)
    {
        $this->data[] = preg_replace('/\n$/', '', $data);
    }

    /**
     * Get response data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Parse response data and check for errors.
     *
     * @return void
     * @throws \Dachande\Weecast\Mpc\Exception\InvalidResponseException
     * @throws \Dachande\Weecast\Mpc\Exception\MpcResponseException
     */
    public function parseData()
    {
        $mpdServerResponse = $this->data[0];
        if (!preg_match('/^' . static::RESPONSE_OK . '/', $mpdServerResponse)) {
            throw new \Dachande\Weecast\Mpc\Exception\InvalidResponseException(
                'Remote server did not identify as MPD.'
            );
        }

        foreach ($this->data as $data) {
            if (preg_match('/^' . static::RESPONSE_ERROR . '/', $data)) {
                list ($error, $code, $command, $message) = explode(' ', $data, 4);
                preg_match('/\[([^@]*)@([^\]]*)\]/', $code, $codeSplit);

                throw new \Dachande\Weecast\Mpc\Exception\MpcResponseException(
                    $message,
                    (int)$codeSplit[1]
                );
            }
        }
    }
}
