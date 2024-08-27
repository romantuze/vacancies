<?php

namespace App\Repositories;

use App\Models\Work;
use Carbon\Carbon;
use Auth;

class WorksRepository
{

    public function getWorks($status)
    {
        $works = Work::where(Work::FIELD_STATUS,$status)->get();
        return $works;
    }

    public function createWork(array $data){
        $work = Work::create($data);
        return $work;
    }


    public function createFileWork(array $data){ 
    }

}
