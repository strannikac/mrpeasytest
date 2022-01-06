<?php 

namespace App;

/**
 * Class Response
 */
class Response {

    private string $controller = '';
    private string $action = '';

    private ?object $data = null;
    private ?object $content = null;
    private string $status = 'error';
    private array $error = [];

    public function __construct(?object $data = null, ?object $content = null, array $error = []) {
        $this->data = $data;
        $this->content = $content;
        $this->error = $error;

        if (empty($this->error)) {
            $this->status = 'success';
        }
    }

    public function setController(string $controller): void 
    {
        $this->controller = $controller;
    }

    public function setAction(string $action): void 
    {
        $this->action = $action;
    }

    public function getController(): string 
    {
        return $this->controller;
    }

    public function getAction(): string 
    {
        return $this->action;
    }

    /**
     * show response for app (json string or html)
     * @return void
     */
    public function show(): void
    {
        if (!empty($this->content->html)) {
            echo $this->configureHtml();
            exit;
        }

        $json = [
            'controller' => $this->controller, 
            'action' => $this->action, 
            'time' => date('Y-m-d H:i:s'), 
            'data' => $this->data, 
            'error' => $this->error, 
            'status' => $this->status
        ];

        echo json_encode($json);
        exit;
    }

    /**
     * configure html (if needed)
     * @return string configured html
     */
    private function configureHtml(): string 
    {
        return $this->content->html;
    }
}

?>