<?php

namespace App\Models;

use App\Models\Config as SystemConfig;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImportData extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'email', 'phone_number', 'gender', 'dob'];
    protected $encrypt = ['name', 'email', 'phone_number', 'gender', 'dob'];

    public static function boot()
    {
        parent::boot();
        
        static::addGlobalScope('unique_email', function ($builder) {
            $builder->whereNotNull('email');
        });
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encrypt)) {
            $encryptionKey = SystemConfig::getEncryptionKey();
            $encrypter = new \Illuminate\Encryption\Encrypter($encryptionKey, Config::get('app.cipher') );
            $encrypted = $encrypter->encrypt($value);
            $this->attributes[$key] = $encrypted;
        } else {
            parent::setAttribute($key, $value);
        }
    }

    public function getAttribute($key)
    {
        if (in_array($key, $this->encrypt) && !empty($this->attributes[$key])) {
            $encryptionKey = SystemConfig::getEncryptionKey();
            $encrypter = new \Illuminate\Encryption\Encrypter($encryptionKey, Config::get('app.cipher') );
            $decrypted = $encrypter->decrypt( $this->attributes[$key] );
            return $decrypted;
        }

        return parent::getAttribute($key);
    }
}
