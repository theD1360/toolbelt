<?php
/**
 * Created by PhpStorm.
 * User: diego
 * Date: 7/3/17
 * Time: 7:55 PM
 */

if (! function_exists('hash_pw')) {
    function hash_pw ($pw) {
        return hash("sha256", getenv('PW_SALT') . md5($pw)) ;
    }

}