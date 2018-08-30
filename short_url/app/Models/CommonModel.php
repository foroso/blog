<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CommonModel extends  Model
{
    protected $dateFormat = 'U';
    protected $table_short_prefix = 'ShortUrl';


    protected function getTables($ids) {
        if ($ids < 0) return -1;
        $dbnumer = $ids%10;
        if ($dbnumer < 0) return -1;
       return  $dbnumer;
    }

    protected function getClassObj($dbnumber)  {
        $objName = "App\\Models\\".$this->table_short_prefix.$dbnumber;
        $obj  = new $objName;
        return  $obj;
    }

}
