<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {

    function __construct() {
        parent::__construct();

        $user = $this->ion_auth->user()->row();
        $this->user_id = $user->id;
        $this->load->model('backend_model', 'backend');

        $this->load->library("pagination");

        $config['num_links'] = 10;
        $config['full_tag_open'] = '<ul class="pagination pagination-sm">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="javascript:void(0)">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');
        $this->pagination->initialize($config);
    }

    public function index() {
        $this->data['title'] = 'Dashboard';

        $this->data['total_active_user'] = $this->ion_auth->where('active', '1')->users()->num_rows();
        $this->data['total_delete_user'] = $this->ion_auth->where('active', '0')->users()->num_rows();

        $this->data['products'] = 12;
        $this->data['invoices'] = 20;
        $this->data['content'] = 'admin/welcome_message';
        $this->load->view('admin/main', $this->data);
    }
    
    /** cal_email */
    public function cal_email() {
        $this->load->view('auth/email/mortgage_calculation');
    }

    /** users */
    public function users() {
        $this->data['title'] = 'Users';

        $this->data['content'] = 'admin/users';
        $this->load->view('admin/main', $this->data);
    }

    /** user list */
    public function get_user_list($id) {
        $count_res = $this->backend->get_user_count();

        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = site_url('admin/get_user_list/');
        $config['total_rows'] = $count_res;
        $config['uri_segment'] = 3;
        $perpage = $config['per_page'] = $this->input->post('perpage');

        $this->pagination->initialize($config);
        $res = $this->backend->get_user_all($perpage, $id);
        $res['links'] = '';

        if ($count_res > $perpage) {
            $res['links'] = $this->pagination->create_links();
        }

        $res['total_rows'] = $count_res;
        header('Content-Type: application/json');
        echo json_encode($res);
    }

    /** delete user */
    public function delete_user() {
        $id = (int) $this->input->post('id');

        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->ion_auth->deactivate($id);
            header('Content-Type: application/json');
            echo json_encode(['status' => true, 'message' => strip_tags($this->ion_auth->messages())]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['status' => false, 'data' => ['error' => new stdClass(), 'message' => 'You must be an administrator to view this page.']]);
        }
    }

    /** activate */
    public function active_user() {
        $id = (int) $this->input->post('id');
        $activation = FALSE;

        if ($this->ion_auth->is_admin()) {
            $activation = $this->ion_auth->activate($id);
        }

        if ($activation) {
            header('Content-Type: application/json');
            echo json_encode(['status' => true, 'message' => strip_tags($this->ion_auth->messages())]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['status' => false, 'data' => ['error' => new stdClass(), 'message' => strip_tags($this->ion_auth->errors())]]);
        }
    }

    /** Change password */
    public function change_password() {
        $this->data['title'] = 'Change Password';

        $user = $this->ion_auth->user()->row();
        $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
        $this->data['user_id'] = ['name' => 'user_id', 'id' => 'user_id', 'type' => 'hidden', 'value' => $user->id];

        // view
        $this->data['content'] = 'admin/change_password';
        $this->load->view('admin/main', $this->data);
    }

    public function save_password() {
        $this->form_validation->set_rules('old', 'Old Password', 'required');
        $this->form_validation->set_rules('new', 'New Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
        $this->form_validation->set_rules('new_confirm', 'Confirm Password', 'required');

        if ($this->form_validation->run() === TRUE) {
            $identity = $this->session->userdata('identity');

            $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

            if ($change) {
                echo json_encode(['status' => true, 'message' => $this->ion_auth->messages(), 'redirect' => site_url('admin/change_password')]);
            } else {
                $error = ['old' => form_error('old'), 'new' => form_error('new'), 'new_confirm' => form_error('new_confirm')];
                echo json_encode(['status' => false, 'data' => ['error' => $error], 'message' => $this->ion_auth->errors()]);
            }
        } else {
            $error = ['old' => form_error('old'), 'new' => form_error('new'), 'new_confirm' => form_error('new_confirm')];
            echo json_encode(['status' => false, 'data' => ['error' => $error], 'message' => 'Validation error']);
        }
    }

    /** pre-approval request */
    public function prequal_request() {
        $this->data['title'] = 'Pre-Qualification Request';

        $this->data['content'] = 'admin/prequal_request';
        $this->load->view('admin/main', $this->data);
    }

    /** get prequal_request */
    public function get_prequal_request($id) {
        $count_res = $this->backend->getPreRequest();

        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = site_url('admin/get_prequal_request/');
        $config['total_rows'] = $count_res;
        $config['uri_segment'] = 3;
        $perpage = $config['per_page'] = $this->input->post('perpage');

        $this->pagination->initialize($config);
        $res = $this->backend->getAllPreRequest($perpage, $id);
        $res['links'] = '';

        if ($count_res > $perpage) {
            $res['links'] = $this->pagination->create_links();
        }

        $res['total_rows'] = $count_res;
        header('Content-Type: application/json');
        echo json_encode($res);
    }
    
    /** refinance request */
    public function refinance_request() {
        $this->data['title'] = 'Refinance Request';

        $this->data['content'] = 'admin/refinance_request';
        $this->load->view('admin/main', $this->data);
    }

    /** get refinance_request */
    public function get_refinance_request($id) {
        $count_res = $this->backend->getRefinance();

        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = site_url('admin/get_refinance_request/');
        $config['total_rows'] = $count_res;
        $config['uri_segment'] = 3;
        $perpage = $config['per_page'] = $this->input->post('perpage');

        $this->pagination->initialize($config);
        $res = $this->backend->getAllRefinance($perpage, $id);
        $res['links'] = '';

        if ($count_res > $perpage) {
            $res['links'] = $this->pagination->create_links();
        }

        $res['total_rows'] = $count_res;
        header('Content-Type: application/json');
        echo json_encode($res);
    }
}
