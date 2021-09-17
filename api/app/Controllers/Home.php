<?php
namespace App\Controllers;
use App\Models\Users;


class Home extends BaseController
{
	
	function __construct() {
		$this->Users = new Users();
    }
	
	public function index()
	{
		return json_encode("CHIKARA API");
	}
	
}
