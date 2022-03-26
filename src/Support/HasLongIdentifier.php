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
     * Size of the long identifier.
     *
     * @var integer
     */
    protected $longIdentifierLength = 50 ;

    /**
     * Change the route key name for the pack.
     *
     * @return string
     */
    public function getRouteKeyName() {
        return 'long_id' ;
    }

    /**
     * Get the long key length.
     *
     * @return integer
     */
    public function getLongIdentifierLength() {
        return $this->longIdentifierLength ;
    }

    /**
     * Generate a unique long id for the
     * current model.
     *
     * @return void
     */
    public function generateLongId() {
        do {
            $key = Str::random($this->longIdentifierLength) ;
        } while(self::query()->where('long_id', $key)->exists()) ;

        $this->long_id = $key ;
    }

    /**
     * Find the instance identified by the given
     * long identifier.
     * 
     * @return static
     */
    public static function findOrFailByLongId(string $long_id) {
        return static::query()->where('long_id', $long_id)->firstOrFail() ;
    }

    /**
     * Find the instance identified by the given
     * long identifier.
     * 
     * @return static|null
     */
    public static function findByLongId(string $long_id) {
        return static::query()->where('long_id', $long_id)->first() ;
    }
}