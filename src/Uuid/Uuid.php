<?php

namespace Dachande\Weecast\Uuid;

use Cake\Core\Configure;
use Symfony\Component\Console\Exception\InvalidArgumentException;

class Uuid
{
    protected $uuid = null;

    public function __construct($uuid = null)
    {
        if ($uuid == null) {
            $this->uuid = Configure::read('App.defaultUuid');
        } else {
            $this->uuid = $this->validate($uuid);
        }
    }

    protected function validate($uuid)
    {
        if (!preg_match(Configure::read('App.uuidRegexp'), $uuid)) {
            throw new InvalidArgumentException(sprintf('Invalid uuid specified "%s"', $uuid));
        }

        return $uuid;
    }

    public function get()
    {
        return $this->uuid;
    }
}
