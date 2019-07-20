<?php
namespace WebConsole\Extension\Support;

use  WebConsole\Extension\Support\WebConsoleRPCServer;

/**
 * @author wanghouting
 * Class WebConsole
 */
class WebConsole{

    private  $config = [];
    private  $is_configured = false;


    public function init(){
        $this->config = config('webconsole');
        $NO_LOGIN = $this->config['no_login'];
        $this->is_configured = ( $NO_LOGIN || count($this->config['account']) >= 1) ? true : false;

        // Processing request
        if (array_key_exists('REQUEST_METHOD', $_SERVER) && $_SERVER['REQUEST_METHOD'] == 'POST') {
            $rpc_server = new WebConsoleRPCServer();
            $rpc_server->Execute();
        }else if (!$this->is_configured) {
            return view("vendor.webconsole.error");
        }else{
            return view("vendor.webconsole.main",compact('NO_LOGIN'));
        }
    }

}