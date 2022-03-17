<?php

use Pythagus\LaravelLydia\Lydia;

if(! function_exists('lydia')) {
    /**
     * Get a lydia instance.
     * 
     * @return Lydia
     */
    function lydia() {
        return new Lydia() ;
    }
}