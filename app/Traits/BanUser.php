<?php

namespace App\Traits;

trait BanUser{
    public function scopeActive($query){
        $query->where('banned','=',0);
    }
    public function scopeBanned($query){
        $query->where('banned','=',1);
    }
}
