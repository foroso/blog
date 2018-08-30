<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/25
 * Time: 16:11
 */

if (!function_exists('b64dec')) {
    function b64dec($b64)
    {
        $map = array(
            '0' => 0, '1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9,
            'A' => 10, 'B' => 11, 'C' => 12, 'D' => 13, 'E' => 14, 'F' => 15, 'G' => 16, 'H' => 17, 'I' => 18, 'J' => 19,
            'K' => 20, 'L' => 21, 'M' => 22, 'N' => 23, 'O' => 24, 'P' => 25, 'Q' => 26, 'R' => 27, 'S' => 28, 'T' => 29,
            'U' => 30, 'V' => 31, 'W' => 32, 'X' => 33, 'Y' => 34, 'Z' => 35, 'a' => 36, 'b' => 37, 'c' => 38, 'd' => 39,
            'e' => 40, 'f' => 41, 'g' => 42, 'h' => 43, 'i' => 44, 'j' => 45, 'k' => 46, 'l' => 47, 'm' => 48, 'n' => 49,
            'o' => 50, 'p' => 51, 'q' => 52, 'r' => 53, 's' => 54, 't' => 55, 'u' => 56, 'v' => 57, 'w' => 58, 'x' => 59,
            'y' => 60, 'z' => 61, '_' => 62, ':' => 63
        );
        $dec = strval(0);
        $len = strlen($b64);
        // echo $len;die();
        for ($i = 0; $i < $len; $i++) {
            $b = strval($map[$b64{$i}]);
            // echo $b,"\n\r";
            if ($b === NULL) {
                return FALSE;
            }
            // $j = $len - $i - 1;
            $j = bcsub($len, $i);
            $j = bcsub($j, 1);
            if ($j == 0) {
                $result = $b;
            } else {
                $aa = bcmul(6, $j);
                $bb = bcsub($aa, 1);
                // $cc = 2 << $bb;
                $bbb = bcadd($bb, 1);
                $cc = bcpow(2, $bbb);
                $result = bcmul($cc, $b);

                // echo $result, "\n\r";
            }
            $dec = bcadd($dec, $result);
        }
        //var_dump($dec);
        return $dec;
        //return number_format($dec, 0, '', '');
    }
}


if (!function_exists('decb64')) {
    /**
     * 10进制转换成64进制
     * @param $dec
     * @return bool|string
     */
    function decb64($dec)
    {
        if ($dec < 0) {
            return FALSE;
        }
        $map = array(
            0 => '0', 1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 6 => '6', 7 => '7', 8 => '8', 9 => '9',
            10 => 'A', 11 => 'B', 12 => 'C', 13 => 'D', 14 => 'E', 15 => 'F', 16 => 'G', 17 => 'H', 18 => 'I', 19 => 'J',
            20 => 'K', 21 => 'L', 22 => 'M', 23 => 'N', 24 => 'O', 25 => 'P', 26 => 'Q', 27 => 'R', 28 => 'S', 29 => 'T',
            30 => 'U', 31 => 'V', 32 => 'W', 33 => 'X', 34 => 'Y', 35 => 'Z', 36 => 'a', 37 => 'b', 38 => 'c', 39 => 'd',
            40 => 'e', 41 => 'f', 42 => 'g', 43 => 'h', 44 => 'i', 45 => 'j', 46 => 'k', 47 => 'l', 48 => 'm', 49 => 'n',
            50 => 'o', 51 => 'p', 52 => 'q', 53 => 'r', 54 => 's', 55 => 't', 56 => 'u', 57 => 'v', 58 => 'w', 59 => 'x',
            60 => 'y', 61 => 'z', 62 => '_', 63 => ':',
        );
        $b64 = '';
        do {
            $a = bcmod($dec, 64);
            $b64 = $map[$a] . $b64;
            $dec = bcdiv($dec, 64);
        } while ($dec >= 1);
        return $b64;
    }
}