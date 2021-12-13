<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Terms extends MY_Controller {

    function __construct() {
        parent::__construct();

        /** user */
        $user = $this->ion_auth->user()->row();
        $this->user_name = $user->full_name;

        $this->load->model('backend_model', 'backend');
    }

    /** terms */
    public function index() {
        $this->data['title'] = 'Terms & Conditions';

        $this->data['row'] = $this->backend->get_table_row('settings', array('id' => 1));
        $this->data['content'] = 'admin/terms';
        $this->load->view('admin/main', $this->data);
    }

    /** save */
    public function save_terms() {
        $this->form_validation->set_rules('terms_and_conditions', 'Terms & Conditions', 'trim|required');

        header('Content-Type: application/json');
        if ($this->form_validation->run() === TRUE) {
            $dataPost = array(
                'terms_and_conditions' => $this->input->post('terms_and_conditions'),
                'updated_on' => time()
            );

            $this->backend->update_data('settings', array('id' => 1), $dataPost);
            echo json_encode(['status' => true, 'message' => 'Terms has been added updated.']);
        } else {
            $error = ['terms_and_conditions' => form_error('terms_and_conditions')];
            echo json_encode(['status' => false, 'data' => ['error' => $error], 'message' => '']);
        }
    }

}
