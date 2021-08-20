<?php

namespace App\Models;
use CodeIgniter\Model;
use Config\Database;


class Users extends Model
{
    
    protected $table = 'users';
    

    public function listar(){
       
        return $query = $this->db->query("SELECT * FROM users;")->getResult();
    }

    public function registrar($data){
         if ($query = $this->db->table('users')->insert($data)){
             return true;
        }else{
            return false;
        }
    }
}