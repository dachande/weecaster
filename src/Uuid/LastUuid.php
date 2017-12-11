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
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;

/**
 * The last-uuid file handler that interacts with a temporary file to store,
 * retrieve and compare uuids.
 *
 * @author Daniel Schultheis <d.schultheis@kabel-salat.net>
 */
class LastUuid
{
    public static function get()
    {
        $lastUuidFoldername = Configure::read('App.tempFolder');
        $lastUuidFilename = Configure::read('App.lastUuidFilename');

        // Create temporary folder if it does not exist
        $lastUuidFolder = new Folder($lastUuidFoldername, true, 0755);

        // Get lastUuid file
        $lastUuidFile = new File($lastUuidFoldername . DS . $lastUuidFilename, false);

        if ($lastUuidFile->exists() === true) {
            $lastUuid = $lastUuidFile->read();
            return new Uuid($lastUuid);
        }

        return new Uuid();
    }

    public static function set(Uuid $uuid)
    {
        $lastUuidFoldername = Configure::read('App.tempFolder');
        $lastUuidFilename = Configure::read('App.lastUuidFilename');

        // Create temporary folder if it does not exist
        $lastUuidFolder = new Folder($lastUuidFoldername, true, 0755);

        // Get lastUuid filename
        $lastUuidFile = new File($lastUuidFoldername . DS . $lastUuidFilename, true);

        $lastUuidFile->write($uuid->get());
    }

    public static function compare(Uuid $uuid)
    {
        $lastUuid = static::get();

        if ($uuid->get() === $lastUuid->get()) {
            return true;
        }

        return false;
    }
}
