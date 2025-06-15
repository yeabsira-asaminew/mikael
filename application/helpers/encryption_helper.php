<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('encrypt_id')) {
    function encrypt_id($student_id) {
        $key = 'senbet'; // Use a strong and secret key
        $iv = substr(hash('sha256', 'sebet32'), 0, 16); // Initialization vector
        return openssl_encrypt($student_id, 'AES-256-CBC', $key, 0, $iv);
    }
}

if (!function_exists('decrypt_id')) {
    function decrypt_id($encrypted_id) {
        $key = 'senbet'; // Use the same key as encryption
        $iv = substr(hash('sha256', 'sebet32'), 0, 16); // Same IV as encryption
        return openssl_decrypt($encrypted_id, 'AES-256-CBC', $key, 0, $iv);
    }
}
