<?php
namespace App\Controllers;
class Home extends BaseController
{
	public function index()
	{
		return json_encode("CHIKARA API");
	}
	
}
