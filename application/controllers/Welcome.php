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

    public function news($newsid = 0) {
        if ($newsid == 0) {
            show_error('Data not found');
        }

        $this->data['news'] = $this->backend->get_table_row('news', array('newsid' => $newsid));
        $this->load->view('admin/news/news_details', $this->data);
    }

    public function refinance_offer_request() {
        $this->load->view('auth/email/refinance_offer_request');
    }

}
