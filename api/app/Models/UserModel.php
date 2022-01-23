<?php

namespace App\Models;

use App\Entities\User;
use App\Models\ORMModel;
use \App\Libraries\Hasher;

class UserModel extends ORMModel
{
  protected $table = 'users';
  protected $prefixField = 'USER';

  protected $returnType = User::class;
  protected $useSoftDeletes = false;

  protected $allowedFields = [
    "USER_PK",
    "USER_identification",
    "USER_type_identification",
    "USER_name",
    "USER_lastname",
    "USER_indicative",
    "USER_phone",
    "USER_country",
    "USER_birthday",
    "USER_email"
  ];

  protected $validationRules = [
    'USER_email'         => 'required|valid_email|is_unique[users.USER_email]',
    'USER_identification'         => 'required|is_unique[users.USER_identification]',
  ];
}
