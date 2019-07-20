<?php

namespace WebConsole\Extension\Controllers;




use App\Http\Controllers\Controller;
use WebConsole\Extension\Facades\WebConsole;

/**
 * 更新控制器
 * Class WebConsoleController
 * @author wanghouting
 */
class WebConsoleController extends Controller
{

    public function index() {
        WebConsole::init();
    }

}