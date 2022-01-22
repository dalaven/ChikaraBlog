<?php
namespace App\Controllers;
use App\Models\UserModel;


class Enrollment extends BaseController
{
	
	function __construct() {
        $this->userModel = new UserModel();
    }
	
	public function index()
	{
        return json_encode($this->userModel->findAll());
	}
	
    public function register(){
        $data = $this->request->getJSON(true);
        
        return json_encode($this->userModel->save($data));
    }
}
