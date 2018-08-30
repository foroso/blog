<?php

namespace App\Http\Controllers;


use App\Models\ShortUrl0;
use App\Models\ShortUrlIds;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class IndexController extends Controller
{
    //

    public function showRdi(Request $request, $key)
    {

        $boolean = true;
//        Redis::setex('site_name', 10, 'Lumen的redis');
//        Cache::put('a', '我是测试', 2000);
//        Cache::store('redis')->put('site_name', 'Lumen测试', 10);
        $url = Cache::get($key);
        if (empty($url)) {
            $ids = base64_decode($key);
            if ($ids > 0) {
                $shortIds = new ShortUrlIds();
                $shortObj = $shortIds->getClassObj($ids);
                $shortRow = $shortObj->where(['ids_id' => $ids])
                    ->first([
                        'short',
                        'url',
                    ]);
                if ($shortRow) {
                    $url = $shortRow->url;
                    $shortUrl = $shortRow->short;
                    $time = config('url.redis_short_url_key_time');
                    Cache::put($shortUrl, $url, $time);;
                }
            } else {
                $boolean = false;
            }
        }

        if (!$boolean) {
            $url = 'http://www.shangjiadao.com';
        }
        $url = htmlspecialchars_decode($url);
        return redirect($url,301);
    }

    public function saveRdi(Request $request)
    {

        $url = $request->input('url');
        if (empty($url)) {
            $this->format_data('', 400, 'empty url');
        }

        $sina_short_url_server = Cache::get('sina_short_url');

        if($sina_short_url_server){
            $url = htmlspecialchars($url);
            $shortUrl = Cache::get($url);
            if (!$shortUrl) {
                $shortUrlIds = new ShortUrlIds();
                $shortUrl = $shortUrlIds->addShort([
                    'url' => $url,
                ]);

                if (!empty($shortUrl)) {
                    $time = config('url.redis_short_url_key_time');
                    Cache::put($shortUrl, $url, $time);
                    $savetime = config('url.redis_short_url_save_key_time');
                    Cache::put($url, $shortUrl, $savetime);
                    return $this->format_data(['url' => env('SHORT_URL').$shortUrl], 0);
                }
                return $this->format_model_data(["code" => 400, "data" => [], "msg" => "短链添加失败"]);
            } else {
                return $this->format_data(['url' => env('SHORT_URL').$shortUrl], 0);
            }
        }else{
            $shortUrl = $this->sina_short_url($url);
            return $this->format_data(['url' => $shortUrl], 0);
        }
    }


    /**
     * 新浪短链接
     * @param $url
     * @return mixed
     */
    public function sina_short_url($url){
        $url = urlencode($url);

        $key = '1329565195';

        $baseurl = 'http://api.t.sina.com.cn/short_url/shorten.json?source='.$key.'&url_long='.$url;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$baseurl);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $strRes = curl_exec($ch);
        curl_close($ch);
        $arrResponse = json_decode($strRes,true);

        //如果请求失败，更新redis中新浪短链标记的值, 1:故障， 0:正常
        if (isset($arrResponse->error) || !isset($arrResponse[0]['url_long']) || $arrResponse[0]['url_long'] == ''){
            Cache::forever('sina_short_url', 1);
            return $this->format_model_data(["code" => 400, "data" => [], "msg" => "短链生成失败"]);
        }else{
            return $arrResponse[0]['url_short'];
        }
    }


    
}
