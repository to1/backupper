<?php

namespace to1\backupper\Facades;

use Illuminate\Support\Facades\Facade;

class backupper extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'backupper';
    }
}
