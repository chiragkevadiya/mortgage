<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('backend_model', 'backend');
    }

    public function index() {
        $this->load->view('welcome');
    }
    
    public function refinance_offer_request() {
        $this->load->view('auth/email/refinance_offer_request');
    }

}
