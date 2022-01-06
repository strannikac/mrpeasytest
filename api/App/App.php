<?php

namespace App;

use Helper\Locale;

class App {

    private $locale = [];

    public function __construct() 
    {
        session_start();

        $this->locale = new Locale();

        $this->run();
    }

    private function run(): void 
    {
        $response = null;
        $request = new Request();

        if(!empty($request->error)) {
            //request error
            $response = new Response(null, null, [$this->locale->get($request->error)]);
        }

        if(empty($response)) {
            $class = $request->classPath;
            $controller = new $class($request);

            $action = $request->action;
            $controller->$action();

            $response = $controller->getResponse();
        }

        $response->getController($response->setController($request->controller));
        $response->getAction($response->setAction($request->action));

        $response->show();
    }
}

?>