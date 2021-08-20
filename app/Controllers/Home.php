<?php
namespace App\Controllers;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\Controller;
use App\Models\Users;


class Home extends BaseController
{
	
	function __construct() {
		$this->Users = new Users();
    }
	
	public function index()
	{
		return view('index');
	}
	public function demo()
	{
		return view('demo');
	}
	public function users()
	{
		$userModel = new \App\Models\Users();
		$data = $userModel->listar();
		echo json_encode($data);
		exit;
	}

	public function registrar()
	{
		
		if($this->request->getVar('USER_names')||$this->request->getVar('USER_lastnames')||$this->request->getVar('USER_email')||$this->request->getVar('USER_address')||$this->request->getVar('USER_telephone')){
			$data = [
			'USER_names' => $this->request->getVar('USER_names'),
			'USER_identification' => 10,
			'USER_username' => $this->request->getVar('USER_names').'.'.$this->request->getVar('USER_lastnames'),
			'USER_lastnames' => $this->request->getVar('USER_lastnames'),
			'USER_password' => 'chikara',
			'USER_email' => $this->request->getVar('USER_email'),
			'USER_address' => $this->request->getVar('USER_address'),
			'USER_telephone' => $this->request->getVar('USER_telephone'),
			'USER_date_create' => date('h-i-s G:i:s'),
			'USER_date_update' => date('h-i-s G:i:s'),
			'USER_PK_create' => 1,
			'USER_PK_update' => 1,
			'USER_FK_type_identification' => 1,
			'USER_FK_state' => 2,
			'USER_FK_gender' => 1,
		];
		if($query = $this->Users->registrar($data)){
			echo json_encode (array('title'=>'Usuario registrado exitosamente',
			'msg'=> 'Enhorabuena '.$data['USER_names'].' ya estas inscrito en  CHiKARA',
			'state'=>'Success',
			'alert'=>'true' 
			));
		}else{
			echo json_encode (array('title'=>'Error al crear usuario',
			'msg'=>'Surgio un error al momento del registro, porfavor vuelve a validar' ,
			'state'=>'danger', 
			'alert'=>'true'
			));
		}
	}else{
		echo json_encode (array('title'=>'Error al crear usuario',
			'msg'=>'porfavor validar que todos los campos esten completamente diligenciados' ,
			'state'=>'danger', 
			'alert'=>'true'
			));
	}
		exit;
	}
	
}
