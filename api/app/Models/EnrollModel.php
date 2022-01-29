<?php

namespace App\Models;

use App\Entities\Enroll;
use App\Models\ORMModel;

class CourseModel extends ORMModel
{
    protected $table = 'enrollments';
    protected $prefixField = 'NRMT';

    protected $returnType = Enroll::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        "NRMT_PK",
        "NRMT_user",
        "NRMT_course"
    ];
}
