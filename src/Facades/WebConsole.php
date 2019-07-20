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
     * @method static void init();
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \WebConsole\Extension\Support\WebConsole::class;
    }
}