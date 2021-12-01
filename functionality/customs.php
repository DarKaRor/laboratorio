<?php
    # Place for custom functions.

    function get_int($num){
        if (is_int($num)) return int($num);
        return $num;
    }

    function is_set_empty($keys,$method){
        foreach($keys as $key) if(!isset($method[$key]) || empty($method[$key])) return FALSE;
        return TRUE;
    }

    function rand_color() {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }

    function set_error($s){
        global $error,$errorMsg;
        $error = TRUE;
        $errorMsg = $s;
    }

?>