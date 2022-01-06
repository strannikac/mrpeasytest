<?php

namespace App;

class ProfileController extends Controller 
{
    private int $tokenLifeTime = 600;

    private ?object $userEntity;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        if (empty($this->request->params->get->token)) {
            $this->error[] = $this->locale->get('ERR_1003');
            $this->data->showLogin = true;
            return;
        }

        $this->userEntity = $this->userModel->selectByToken($this->request->params->get->token);
        if (!isset($this->userEntity->id)) {
            $this->error[] = $this->locale->get('ERR_1003');
            $this->data->showLogin = true;
            return;
        }
    }

    /**
     * Method main - get data
     * @return void
     */
    public function main(): void 
    {
        $now = time();

        if ($this->request->method == 'GET') {
            if (($now - $this->userEntity->lastUpdate) > $this->tokenLifeTime) {
                $this->error[] = $this->locale->get('ERR_2001');
                $this->data->showLogin = true;
            } else {
                $this->data->token = $this->userEntity->token;
                $this->data->counter = $this->userEntity->counter;
            }
        } else {
            $this->error[] = $this->locale->get('ERR_1001');
        }
    }

    /**
     * Counter
     * @return void
     */
    public function counter(): void
    {
        if ($this->request->method == 'PUT') {
            $this->userModel->updateCounter(($this->userEntity->counter + 1), $this->userEntity->id);
        } else {
            $this->error[] = $this->locale->get('ERR_1001');
        }
    }

    /**
     * Exit
     * @return void
     */
    public function exit(): void
    {
        if ($this->request->method == 'PUT') {
            $this->userModel->updateToken('', $this->userEntity->id, 0);
        } else {
            $this->error[] = $this->locale->get('ERR_1001');
        }
    }
}

?>