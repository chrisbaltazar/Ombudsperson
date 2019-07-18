<?php

namespace App\Traits;

use Illuminate\Support\Facades\Crypt;

trait EncryptableModel
{
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (in_array($key, $this->encryptable)) {
            $value = Crypt::decrypt($value);
        }
        
        return $value;
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encryptable)) {
            $value = Crypt::encrypt($value);
        }

        return parent::setAttribute($key, $value);
    }
    
    public function toArray()
    {
        $array = parent::toArray();

        foreach ($array as $key => $attribute) {
            
            if (in_array($key, $this->encryptable) && $attribute) {
                $array[$key] = $this->getAttribute($key);
            }
            
        }
        
        return $array;
    }
}