<?php

namespace App\Traits;

trait Encrypted {

    public function getIdTokenAttribute()
    {
        return encrypt_id_info($this->id);
    }
    
}