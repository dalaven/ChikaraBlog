<?php

namespace App\Controllers;

use App\Models\UserModel;


class Enrollment extends BaseController
{

    function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        return json_encode($this->userModel->findAll());
    }

    public function register()
    {
        $data = $this->request->getJSON(true);

        try {
            if (!$this->userModel->save($data)) {
                $response['errors'] = $this->userModel->validation->getErrors();
            }

            $message = "<html>Cordial saludo de parte de ChikaraOficial,
            <br>
            Mediante este correo extendemos nuestros agradecimientos por su interés en nuestro cursos básicos del idioma japonés, así mismo los invitamos a conectarse el día sábado 29 de enero a las 6.00pm Colombia a nuestro live por Twitch, YouTube o Facebook, en donde abarcaremos toda la información de nuestros cursos y responderemos todas sus inquietudes.
            <br>
            ¡Los esperamos!</hmtl>";
            $email = \Config\Services::email();
            $email->setFrom('matriculas@chikaraoficial.org', 'Titulo');
            $email->setTo($data['USER_email']);
            $email->setSubject('Asunto');
            $email->setMessage($message);
            $email->send();
            $email->printDebugger(['headers']);
        } catch (\Exception $e) {
            $response['error'] = $e->getMessage();
        }

        return json_encode($response);
    }
}
