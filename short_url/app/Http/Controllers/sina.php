<?php
/**
 * Created by PhpStorm.
 * User: kongdao01
 * Date: 2018/8/28
 * Time: 19:34
 */


$info = array(
    'REDIS_HOST' => 'r-bp13cec7dc3eed54.redis.rds.aliyuncs.com',
    'REDIS_PORT' => '6379',
    'REDIS_AUTH' => 'ik2x%YBGQM&M',
);

//实例化redis
$redis = new Redis();
//连接
$redis->connect($info['REDIS_HOST'], $info['REDIS_PORT']);
$redis->auth($info['REDIS_AUTH']);
$res = $redis->get('redis');


$url = urlencode('https://www.shangjiadao.com/');

$key = '1329565195';

$baseurl = 'http://api.t.sina.com.cn/short_url/shorten.json?source=' . $key . '&url_long=' . $url;

//curl 请求生产短链
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseurl);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$strRes = curl_exec($ch);
curl_close($ch);
$arrResponse = json_decode($strRes, true);

//        var_dump($arrResponse);die;

/**
 * sina_short_url
 * 值为1 ， 新浪短链服务故障
 * 值为0 ， 新浪短链服务正常
 */
//如果请求失败，更改redis中新浪短链标记的值
if (isset($arrResponse->error) || !isset($arrResponse[0]['url_long']) || $arrResponse[0]['url_long'] == '') {
    $redis->set('sina_short_url', 1);
} else {
    $redis->set('sina_short_url', 0);
}