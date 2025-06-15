<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function load_andegna_library() {
    $basePath = APPPATH . 'libraries/andegna/src/';
    
    // Load Traits
    require_once $basePath . 'Operations/Initiator.php';
    require_once $basePath . 'Operations/Time.php';

    // Load Main Classes
    require_once $basePath . 'Constants.php';
    require_once $basePath . 'DateTime.php';
    require_once $basePath . 'Exception/Exception.php';
    require_once $basePath . 'Exception/InvalidDateException.php';
    require_once $basePath . 'Exception/InvalidTypeException.php';
    require_once $basePath . 'Exception/OutOfRangeException.php';
}
