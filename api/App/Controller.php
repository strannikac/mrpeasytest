<?php 

namespace App;

use Model\UserModel;
use Helper\Locale;
use stdClass;

class Controller {
    protected array $allowedMethods = ['GET', 'POST', 'PUT'];

    protected array $arrBool = [0,1];

    protected Request $request;
    protected object $data;
    protected object $content;
    protected array $error = [];

    protected Locale $locale;

    protected $defaults = [];
    protected $statusSuccess = 'success';
    protected $statusFail = 'error';

    protected UserModel $userModel;

    public function __construct(Request $request) {
        $this->locale = new Locale();

        $this->request = $request;

        $this->data = new stdClass();
        $this->content = new stdClass();

        $this->userModel = new UserModel();

        if (!in_array($this->request->method, $this->allowedMethods)) {
            $this->error[] = $this->locale->get('ERR_1001');
            return;
        }
    }

    public function getResponse(): Response {
        return new Response($this->data, $this->content, $this->error);
    }
}

?>