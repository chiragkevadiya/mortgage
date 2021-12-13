<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Email extends MY_Controller {

    function __construct() {
        parent::__construct();

        /** user */
        $user = $this->ion_auth->user()->row();
        $this->user_name = $user->full_name;

        $this->load->model('backend_model', 'backend');
        $this->load->model('email_model');
    }

    /** news */
    public function index() {
        $this->data['title'] = 'List of email';
        $this->data['content'] = 'admin/email/email';
        $this->load->view('admin/main', $this->data);
    }

    public function get_email($id) {
        $count_res = $this->email_model->get_email();

        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = site_url('email/get_email/');
        $config['total_rows'] = $count_res;
        $config['uri_segment'] = 3;
        $perpage = $config['per_page'] = $this->input->post('perpage');

        $this->pagination->initialize($config);
        $res = $this->email_model->get_all_email($perpage, $id);
        $res['links'] = '';

        if ($count_res > $perpage) {
            $res['links'] = $this->pagination->create_links();
        }

        $res['total_rows'] = $count_res;
        header('Content-Type: application/json');
        echo json_encode($res);
    }

    /** add */
    public function addnew($id = 0) {
        if ($id > 0) {
            $this->data['row'] = $this->backend->get_table_row('email', array('id' => $id));
            if ($this->data['row'] == null) {
                redirect('email/index');
            }

            $this->data['title'] = 'Edit Email';
        } else {
            $this->data['title'] = 'Add Email';
        }

        $this->data['content'] = 'admin/email/add';
        $this->load->view('admin/main', $this->data);
    }

    /** save */
    public function save_email() {
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');

        header('Content-Type: application/json');
        if ($this->form_validation->run() === TRUE) {
            $dataPost = array(
                'subject' => $this->input->post('subject'),
                'message' => $this->input->post('message'),
                'updated_on' => time()
            );

            $this->backend->update_data('email', array('id' => $this->input->post('id')), $dataPost);
            echo json_encode(['status' => true, 'message' => 'Email has been updated successfully.', 'redirect' => site_url('email/index')]);
        } else {
            $error = ['subject' => form_error('subject'), 'message' => form_error('message')];
            echo json_encode(['status' => false, 'data' => ['error' => $error], 'message' => '']);
        }
    }

    private function news_image() {
        $configImage['upload_path'] = NEWS_IMAGE;
        $configImage['max_size'] = 500;
        $configImage['allowed_types'] = 'jpg|jpeg|png|gif';
        $configImage['overwrite'] = FALSE;
        $configImage['remove_spaces'] = TRUE;
        $image_name = random_string('numeric', 10);
        $configImage['file_name'] = $image_name;

        $this->load->library('upload', $configImage);
        $this->upload->initialize($configImage);

        if (!$this->upload->do_upload('image')) {
            header('Content-Type: application/json');
            $error = ['image' => $this->upload->display_errors()];
            echo json_encode(['status' => false, 'data' => ['error' => $error], 'message' => '']);
            exit();
        } else {
            $image_data = $this->upload->data();
            return $image_data['file_name'];
        }
    }

    public function insert_file() {
        if (isset($_FILES['upload']['name'])) {
            $file = $_FILES['upload']['tmp_name'];
            $file_name = $_FILES['upload']['name'];
            $file_name_array = explode(".", $file_name);
            $extension = end($file_name_array);
            $new_image_name = rand() . '.' . $extension;
            chmod('upload', 0777);
            $allowed_extension = array("jpg", "gif", "png");
            if (in_array($extension, $allowed_extension)) {
                move_uploaded_file($file, NEWS_IMAGE . $new_image_name);
                $function_number = $_GET['CKEditorFuncNum'];
                $url = base_url(NEWS_IMAGE . $new_image_name);
                $message = '';
                echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($function_number, '$url', '$message');</script>";
            }
        }
    }

    public function delete_news() {
        $ID = $this->input->post('id');
        if (empty($ID) || !isset($ID)) {
            echo json_encode(['status' => false, 'message' => 'Failed! Please try again']);
        }

        $isDelete = $this->backend->update_data('news', array('newsid' => $ID), array('active' => 1));
        if ($isDelete) {
            echo json_encode(['status' => true, 'message' => 'News has been deleted successfully']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Failed! Please try again']);
        }
    }

}
