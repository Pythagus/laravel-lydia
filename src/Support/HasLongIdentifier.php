<?php

namespace Pythagus\LaravelLydia\Support;

use Illuminate\Support\Str;

/**
 * Help the class needing a long
 * identifier to manage it.
 * 
 * @property string long_id
 * 
 * @author Damien MOLINA
 */
trait HasLongIdentifier {

    /**
     * Long key in the database.
     *
     * @var string
     */
    protected $longKey = 'long_id' ;

    /**
     * Size of the long key.
     *
     * @var integer
     */
    protected $longKeyLength = 50 ;

    /**
     * Change the route key name for the pack.
     *
     * @return string
     */
    public function getRouteKeyName() {
        return $this->longKey ;
    }

    /**
     * Get the long key length.
     *
     * @return integer
     */
    public function getLongKeyLength() {
        return $this->longKeyLength ;
    }

    /**
     * Generate a unique long id for the
     * current model.
     *
     * @return void
     */
    public function generateLongId() {
        do {
            $key = Str::random($this->longKeyLength) ;
        } while(self::query()->where('long_id', $key)->exists()) ;

        $this->long_id = $key ;
    }
}