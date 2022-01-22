<?php

namespace App\Entities;

use App\Entities\CoreEntity;

class User extends CoreEntity
{
    protected $primaryKey = 'USER_PK';

    //protected $dates = ['USER_created_at', 'USER_updated_at'];

    protected $relationKey = [];
}
