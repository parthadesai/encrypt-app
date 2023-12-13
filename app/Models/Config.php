<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    public static function getEncryptionKey()
    {
        $configName = 'encryption_key';
        $encryptionKey = self::where('data_key', $configName)->value('data_value');

        return $encryptionKey;
    }
}
