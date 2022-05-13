<?php

namespace App\Models;

use App\Entities\Term;
use App\Models\ORMModel;

class TermModel extends ORMModel
{
    protected $table = 'terms';
    protected $prefixField = 'TERM';

    protected $returnType = Term::class;
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        "TERM_PK",
        "TERM_name",
        "TERM_start",
        "TERM_end",
        "TERM_enable"
    ];
}
