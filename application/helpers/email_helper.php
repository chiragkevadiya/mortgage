<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function send_mail($to, $subject, $html, $attch = null) {
    $CI = & get_instance();

    //Load email library
    $CI->load->library('email');

    //SMTP & mail configuration
    $config = array(
        'smtp_host' => 'smtp.ionos.com',
        'smtp_port' => '587',
        'smtp_user' => 'isaac@mortgagecalculator4u.com',
        '_smtp_auth' => TRUE,
        'smtp_pass' => 'Isaac@862#',
        'smtp_crypto' => 'tls',
        'protocol' => 'smtp',
        'mailtype' => 'html',
        'charset' => 'utf-8',
        'wordwrap' => TRUE
    );

    $CI->email->initialize($config);
    $CI->email->set_newline("\r\n");

    $CI->email->from('isaac@mortgagecalculator4u.com', 'Mortgagecalculator4u.com');
    $CI->email->to($to);
    $CI->email->subject($subject);
    $CI->email->message($html);
    if ($attch) {
        $CI->email->attach($attch);
    }
    if ($CI->email->send()) {
        return true;
    } else {
        return false;
    }
}
