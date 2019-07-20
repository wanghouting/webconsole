<?php
namespace WebConsole\Extension\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class WebConsole
 * @package WebConsole\Extension\Facades
 */
class WebConsole extends  Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \WebConsole\Extension\Support\WebConsole::class;
    }
}