<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function ethiopian_to_gregorian($ethiopian_date) {
    list($year, $month, $day) = explode('-', $ethiopian_date);
    
    // Convert Ethiopian date to Gregorian
    $jd = cal_to_jd(CAL_GREGORIAN, 9, 11, 1582) + ( ( (int)$year - 1 ) * 365 ) + ((int)($year / 4)) + ( ($month - 1) * 30 ) + $day;
    return jd_to_gregorian($jd);
}

function gregorian_to_ethiopian($gregorian_date) {
    list($year, $month, $day) = explode('-', $gregorian_date);
    
    // Approximate conversion (can be refined)
    $ethiopian_year = $year - 8;
    $ethiopian_month = ($month > 9) ? $month - 8 : $month + 4;
    $ethiopian_day = $day;

    return sprintf('%04d-%02d-%02d', $ethiopian_year, $ethiopian_month, $ethiopian_day);
}
?>
