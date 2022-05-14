<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CourseModel;
use App\Models\TermModel;
use App\Models\EnrollModel;

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

    public function getCourses($available = 1)
    {
        $course = new CourseModel();
        $term = new TermModel();

        $terms = $term->where('TERM_enable', 1)->find();
        $courses = [];
        foreach ($terms as $t) {
            $courses = array_merge($courses, $course->where('COUR_term', $t->TERM_PK)->find());
        }
        return json_encode($courses);
    }

    public function searchUser()
    {
        $data = $this->request->getJSON(true);

        if ($result = $this->userModel->where('USER_email', $data['USER_email'])->where('USER_identification', $data['USER_identification'])->first()) {
            return json_encode($result->USER_PK);
        }
        return json_encode(false);
    }

    public function register()
    {
        $data = $this->request->getJSON(true);

        try {
            if (!$this->userModel->save($data)) {
                $response['errors'] = $this->userModel->validation->getErrors();
            } else {
                $response = $this->userModel->getInsertID();
            }
        } catch (\Exception $e) {
            $response['error'] = $e->getMessage();
        }

        return json_encode($response);
    }


    public function saveCourse()
    {
        $data = $this->request->getJSON(true);

        $course = new CourseModel();

        $course = $course->find($data['USER_course']);

        $messageLink = "";

        if ($course->COUR_PK == 6) {
            $messageLink = '<p>(TEMA: katakana) Horario: 8:00 P.M. – 10:00 P.M </p>
            <div style="flex: auto; display: flex; gap: 10px; margin: auto; justify-content: center;">
            <a href="https://chat.whatsapp.com/BDu7BXORAQsFAqsVzEsRGr" target="_blank"><button style=" background-color: green; font-size: 30px; color: white; margin: 10px; gap: 10px; border-radius: 15px; border: azure;">¡¡click aqui!!</button></a>
            </div>';
        }


        if ($course->COUR_PK == 7) {
            $messageLink = '<p>(TEMA: hiragana) Horario: 6:00 P.M. – 8:00 P.M </p>
            <div style=" flex: auto; display: flex; gap: 10px; margin: auto; justify-content: center; ">
            <a href="https://chat.whatsapp.com/KZvpSWAf1jaIq6OxSNSqOD" target="_blank"><button style=" background-color: green; font-size: 30px; color: white; margin: 10px; gap: 10px; border-radius: 15px; border: azure;">¡¡click aqui!!</button></a>
            </div>';
        }

        if ($course->COUR_PK == 8) {
            $messageLink = '<p>(TEMA: kanji) Horario: 6:00 P.M. – 8:00 P.M </p>
            <div style=" flex: auto; display: flex; gap: 10px; margin: auto; justify-content: center; ">
                    <a href="https://chat.whatsapp.com/HERpE2qeBSeFx0EzCuDIks" target="_blank"><button style=" background-color: green;font-size: 30px;color: white;margin: 10px;gap: 10px;border-radius: 15px;border: azure;">¡¡click aqui!!</button></a>
                </div>';
        }

        if ($course->COUR_PK == 9) {
            $messageLink = '<p>(TEMA: Gramatica) Horario: 6:00 P.M. – 8:00 P.M </p>
            <div style="flex: auto;display: flex;gap: 10px;margin: auto;justify-content: center;">
            <a href="https://chat.whatsapp.com/C5dyjcsqRs5Bd07oUjbY6R" target="_blank"><button style="background-color: green;font-size: 30px;color: white;margin: 10px;gap: 10px;border-radius: 15px;border: azure;">¡¡click aqui!!</button></a>
            </div>';
        }
        if ($course->COUR_PK == 10) {
            $messageLink = '<p>(TEMA: Coreano) Horario: 8:00 P.M. – 10:00 P.M </p>
            <div style=" flex: auto; display: flex; gap: 10px; margin: auto; justify-content: center;">
            <a href="https://chat.whatsapp.com/EvqQ01dXQID9nswIAgwflu" target="_blank"><button style=" background-color: green; font-size: 30px; color: white; margin: 10px; gap: 10px; border-radius: 15px; border: azure;">¡¡click aqui!!</button></a>
            </div>';
        }

        if ($course->COUR_PK == 11) {
            $messageLink = '<p>(TEMA: Frances) Horario: 8:00 P.M. – 10:00 P.M </p>
            <div style=" flex: auto; display: flex; gap: 10px; margin: auto; justify-content: center;">
            <a href="https://chat.whatsapp.com/EvqQ01dXQID9nswIAgwflu" target="_blank"><button style=" background-color: green; font-size: 30px; color: white; margin: 10px; gap: 10px; border-radius: 15px; border: azure;">¡¡click aqui!!</button></a>
            </div>';
        }

        $dataFormat = [
            'NRMT_user' => intval($data['USER_PK']),
            'NRMT_course'    => intval($data['USER_course']),
        ];

        $enroll = new EnrollModel();


        $validOneEnroll = $enroll->where('NRMT_user', $data['USER_PK'])->where('NRMT_course', $data['USER_course'])->findAll();

        if (count($validOneEnroll) === 0) {
            try {
                if ($enroll->save($dataFormat)) {
                    $message = '<div style=" max-width: 600px; margin: auto; border: black solid 1px; border-radius: 5px; padding: 15px; ">
                    <div class="" style=" display: flex; align-items: center; ">
                    <img src="http://chikaraoficial.org/res/images/chikara_logo.png" width="124">
                    <h1 style=" flex: auto; text-align: center;">
                    ようこそ!!
                    </h1>
                    </div>
                    <div>
                    <p></p>
                    <h4 style="text-align: center;">Reciba un cordial saludo de la organización Chikara</h4>
                    <p></p>
                    <p></p>
                    <h4 style="text-align: justify;">
                    Gracias por inscribirte al curso ' . $course->COUR_name . ', te esperamos este sábado 5 de febrero del 2022 para tomar tu primera
                    clase. <br>
                    Recuerda que los horarios en este correo están basados en la hora Colombia (GMT-5), por lo cual tienes que
                    validar la diferencia horaria con tu país.<br>
                    Para el manejo del grupo es necesario unirse al siguiente grupo de WhatsApp, este grupo funcionara para el
                    manejo interno del grupo (horario de clase, dudas, inquietudes, sugerencias)
                    </h4>
                    <p></p>
                    <p></p>
                    <h3 style=" font-weight: 900;">PARA UNIRTE AL GRUPO DE WHATSAPP DA CLICK EN EL SIGUIENTE BOTON:</h3>
                    ' . $messageLink . '
                    </div>
                    </div>';
                    $email = \Config\Services::email();
                    $email->setFrom('matriculas@chikaraoficial.org', 'Chikara Organización');
                    $email->setTo($data['USER_email']);
                    $email->setSubject('Inscripción curso chikara');
                    $email->setMessage($message);
                    $email->send();
                    $email->printDebugger(['headers']);
                    $response['suscribe'] = "Ya inscrito";
                }
            } catch (\Exception $e) {
                $response['error'] = $e->getMessage();
            }
        } else {
            $response['noRegister'] = "Ya existe una inscripción en este curso";
        }
        return json_encode($response);
    }
}
