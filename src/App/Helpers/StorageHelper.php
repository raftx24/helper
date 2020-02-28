<?php

namespace Raftx24\Helper\App\Helpers;

class StorageHelper
{
    public static function createStorageFolder($folderName)
    {
        if (!is_dir(storage_path($folderName))) {
            mkdir(storage_path($folderName));
            chmod(storage_path($folderName), 0777);
        }
    }
}
