<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MY_Controller {

    function __construct() {
        parent::__construct();

        /** user */
        $user = $this->ion_auth->user()->row();
        $this->user_name = $user->full_name;

        $this->load->model('backend_model', 'backend');
    }

    /** news */
    public function index() {
        $this->data['title'] = 'Settings';

        $this->data['row'] = $this->backend->get_table_row('settings', array('id' => 1));
        $this->data['content'] = 'admin/settings';
        $this->load->view('admin/main', $this->data);
    }

    /** save settings */
    public function save_settings() {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('designation', 'Designation', 'trim|required');
        $this->form_validation->set_rules('contact', 'Contact', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('slogan', 'Slogan', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        $this->form_validation->set_rules('disclaimer', 'Disclaimer', 'trim|required');
        $this->form_validation->set_rules('linkedin', 'Linkedin', 'trim|required');
        $this->form_validation->set_rules('google', 'Google', 'trim|required');
        $this->form_validation->set_rules('zillow', 'Zillow', 'trim|required');
        $this->form_validation->set_rules('website', 'Website', 'trim|required');
        $this->form_validation->set_rules('apply_link', 'Apply Link', 'trim|required');

        header('Content-Type: application/json');
        if ($this->form_validation->run() === TRUE) {
            $file_name = $this->input->post('file_name');

            if (!empty($_FILES['image']['name'])) {
                $file_name = $this->profile_image();
            }

            $dataPost = array(
                'profile_image' => $file_name,
                'name' => $this->input->post('name'),
                'designation' => $this->input->post('designation'),
                'slogan' => $this->input->post('slogan'),
                'contact' => $this->input->post('contact'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'disclaimer' => $this->input->post('disclaimer'),
                'linkedin' => $this->input->post('linkedin'),
                'google' => $this->input->post('google'),
                'zillow' => $this->input->post('zillow'),
                'website' => $this->input->post('website'),
                'apply_link' => $this->input->post('apply_link'),
                'updated_on' => time()
            );

            $this->backend->update_data('settings', array('id' => 1), $dataPost);
            echo json_encode(['status' => true, 'message' => 'Settign has been added updated.']);
        } else {
            $error = ['name' => form_error('name'), 'designation' => form_error('designation'), 'contact' => form_error('contact'),
                'email' => form_error('email'), 'slogan' => form_error('slogan'), 'address' => form_error('address'), 'disclaimer' => form_error('disclaimer'),
                'linkedin' => form_error('linkedin'), 'google' => form_error('google'), 'zillow' => form_error('zillow'), 'website' => form_error('website'),
                'apply_link' => form_error('apply_link')];
            echo json_encode(['status' => false, 'data' => ['error' => $error], 'message' => '']);
        }
    }

    private function profile_image() {
        $configImage['upload_path'] = PROFILE_PICTURE_PATH;
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

    public function delete_banner() {
        $ID = $this->input->post('id');
        if (empty($ID) || !isset($ID)) {
            echo json_encode(['status' => false, 'message' => 'Failed! Please try again']);
        }

        $isDelete = $this->banner->delete($ID);
        if ($isDelete) {
            echo json_encode(['status' => true, 'message' => 'Data has been deleted successfully']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Failed! Please try again']);
        }
    }

    public function banner_position() {
        $position = $this->input->post('position');

        $i = 1;
        foreach ($position as $ID) {
            $this->banner->update(array('position' => $i), $ID);
            $i++;
        }
    }

}
