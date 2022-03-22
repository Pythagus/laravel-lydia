<?php

namespace Pythagus\LaravelLydia\Support;

/**
 * Trait used to describe a stateful object.
 * 
 * @property string state
 * 
 * @author Damien MOLINA
 */
trait HasState {

    /**
     * Determine whether the current object has
     * a confirmed state.
     *
     * @return boolean
     */
    abstract public function isConfirmed() ;

    /**
     * Determine whether the current object
     * has the given state.
     *
     * @param string|array $state
     * @return boolean
     */
    public function hasState($state) {
        if(! is_array($state)) {
            $state = [$state] ;
        }

        return in_array($this->state, $state) ;
    }
}