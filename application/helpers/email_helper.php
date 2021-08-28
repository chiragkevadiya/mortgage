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
        'mailtype' => 'html',
        'protocol' => 'smtp',
        'smtp_host' => 'smtp.sendgrid.net',
        'smtp_port' => '587',
        'smtp_user' => 'apikey',
        'smtp_pass' => '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',
        'charset' => 'iso-8859-1'
    );

    $CI->email->initialize($config);
    $CI->email->set_newline("\r\n");

    $CI->email->from(EMAIL, 'Mortgage4u');
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
