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

            $message = '<div style="
            max-width: 600px;
            margin: auto;
            border: black solid 1px;
            border-radius: 5px;
            padding: 15px;
        ">
            <div class="" style="
            display: flex;
            align-items: center;
        ">
                <img src="http://chikaraoficial.org/res/images/chikara_logo.png" width="124">
                <h1 style="
            flex: auto;
            text-align: center;
        ">
                    おめでとう!!
                </h1>
            </div>
            <div>
                <p>
                </p>
                <h4 style="text-align: center;">Cordial saludo de parte de ChikaraOficial</h4>
                <p></p>
                <p class="">
                </p>
                <h4 style="
            text-align: justify;
        ">Mediante este correo extendemos nuestros agradecimientos por su
                    interés en nuestros cursos básicos del idioma japonés,
                    así mismo los invitamos a conectarse el día sábado 29 de enero a las 6.00pm
                    Colombia a nuestro live por Twitch, YouTube o Facebook, en donde abarcaremos toda la
                    información
                    de nuestros cursos y responderemos todas sus inquietudes.</h4>
                <p></p>
                <p></p>
                <h4>¡Te esperamos!</h4>
                <p></p>
                <p></p>
                <div style="
                flex: auto;
                display: flex;
                gap: 10px;
                margin: auto;
                justify-content: center;
            "> <a href="https://www.twitch.tv/chikaraoficial" target="_blank"><img
                            src="https://pnggrid.com/wp-content/uploads/2021/05/Twitch-Logo-Transparent-Image-768x803.png" width="50" ></a>
                    <a href="https://www.facebook.com/ChikaraOficial" target="_blank"><img
                            src="http://chikaraoficial.org/res/images/facebook.svg" width="50" ></a>
                    <a href="https://www.youtube.com/channel/UCG51dRdn45UfK58rMeqrCPw" target="_blank"><img
                            src="http://chikaraoficial.org/res/images/youtube.svg" width="50"></a></div></div></div>';
            $email = \Config\Services::email();
            $email->setFrom('matriculas@chikaraoficial.org', 'Chikara Organización');
            $email->setTo($data['USER_email']);
            $email->setSubject('Pre-inscripción chikara');
            $email->setMessage($message);
            $email->send();
            $email->printDebugger(['headers']);
        } catch (\Exception $e) {
            $response['error'] = $e->getMessage();
        }

        return json_encode($response);
    }
}
