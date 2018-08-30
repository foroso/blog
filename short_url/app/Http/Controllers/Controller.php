<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    //

    public function format_data($data, $code = 200, $msg = 'ok')
    {
        $result = array(
            'code' => $code,
            'data' => $data,
            'msg' => $msg
        );
        return response()->json($result);
    }

    public function format_model_data($data)
    {
        $msg = '';
        if (isset($data['msg'])) {
            $msg = $data['msg'];
        } else {
            $msg = 'ok';
        }
        return $this->format_data($data['data'], $data['code'], $msg);
    }


    public function error($errcode, $errmsg)
    {
        return response()->json([
            'errcode' => $errcode,
            'errmsg' => $errmsg
        ], 401);
    }

}
