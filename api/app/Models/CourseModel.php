<?php

namespace App\Models;

use App\Entities\Course;
use App\Models\ORMModel;

class CourseModel extends ORMModel
{
    protected $table = 'course';
    protected $prefixField = 'COUR';

    protected $returnType = Course::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        "COUR_PK",
        "COUR_name",
        "COUR_description",
        "COUR_term",
    ];
}
