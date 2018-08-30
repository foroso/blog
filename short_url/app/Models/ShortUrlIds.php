<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class ShortUrlIds extends CommonModel
{
    //
    protected $table = 'sjd_short_url_ids';


    public function addShort($data)
    {
        DB::beginTransaction();
        $shortUrlIds = new self();
        $shortUrlIds->save();
        $insertId = $shortUrlIds->id;
        $dbnumer = $this->getTables($insertId);
        if ($dbnumer < 0) {
            DB::rollBack();
            return false;
        }
        $shortcode = base64_encode($insertId);
        $shortDbObj = $this->getClassObj($dbnumer);
        $shortDbObj->url = $data['url'];
        $shortDbObj->short = $shortcode;
        $shortDbObj->ids_id = $insertId;
        $shortDbObj->status = 1;
        $shortDbObj->save();
        DB::commit();
        return $shortcode;
    }


}
