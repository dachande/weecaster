<?php

/**
 * This file is part of the Weecast application
 *
 * (c) Daniel Schultheis <d.schultheis@kabel-salat.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dachande\Weecast\Uuid;

use Cake\Core\Configure;
use Symfony\Component\Console\Exception\InvalidArgumentException;

/**
 * An uuid object.
 *
 * @author Daniel Schultheis <d.schultheis@kabel-salat.net>
 */
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
