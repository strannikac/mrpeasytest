<?php

namespace App;

use Helper\System;

class MainController extends Controller 
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * Method main - show form or profile
     * @return void
     */
    public function main(): void 
    {
        if (!empty($this->request->params->get->token)) {
            header('Location: ' . _URL_ . 'profile/');
            return;
        }

        $this->login();
    }

    /**
     * Login
     * @return void
     */
    public function login(): void
    {
        if ($this->request->method != 'POST' || empty($this->request->params->post->username) || empty($this->request->params->post->password)) {
            $this->error[] = $this->locale->get('ERR_1003');
            return;
        }

        $token = System::createToken();

        $userRow = $this->userModel->selectByUsername($this->request->params->post->username);

        if (isset($userRow->id)) {
            if (password_verify($this->request->params->post->password, $userRow->password)) {
                $this->userModel->updateToken($token, $userRow->id, time());
                $this->data->token = $token;
                $this->data->counter = $userRow->counter;
            } else {
                $this->error[] = $this->locale->get('ERR_2000');
            }
        } else {
            $passwordHash = System::hash($this->request->params->post->password);

            $this->userModel->insertRow($this->request->params->post->username, $passwordHash, $token);
            $this->data->token = $token;
            $this->data->counter = 0;
        }
    }
}

?>