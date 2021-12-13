<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class News extends MY_Controller {

    function __construct() {
        parent::__construct();

        /** user */
        $user = $this->ion_auth->user()->row();
        $this->user_name = $user->full_name;

        $this->load->model('backend_model', 'backend');
        $this->load->model('news_model', 'news');

        $config['num_links'] = 10;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = 'Next &gt;';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '&lt; Prev';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="javascript:void(0)">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');
        $this->pagination->initialize($config);
    }

    /** news */
    public function index() {
        $this->data['title'] = 'List of news';
        $this->data['content'] = 'admin/news/news';
        $this->load->view('admin/main', $this->data);
    }

    public function get_news($id) {
        $count_res = $this->news->get_news();

        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = site_url('news/get_news/');
        $config['total_rows'] = $count_res;
        $config['uri_segment'] = 3;
        $perpage = $config['per_page'] = $this->input->post('perpage');

        $this->pagination->initialize($config);
        $res = $this->news->get_all_news($perpage, $id);
        $res['links'] = '';

        if ($count_res > $perpage) {
            $res['links'] = $this->pagination->create_links();
        }

        $res['total_rows'] = $count_res;
        header('Content-Type: application/json');
        echo json_encode($res);
    }

    /** add */
    public function addnew($newsid = 0) {
        if ($newsid > 0) {
            $this->data['news'] = $this->common->get_table_row('news', array('newsid' => $newsid, 'active' => 0));
            if ($this->data['news'] == null) {
                redirect('news/index');
            }

            $this->data['title'] = 'Edit News';
        } else {
            $this->data['title'] = 'Add News';
        }

        $this->data['content'] = 'admin/news/add';
        $this->load->view('admin/main', $this->data);
    }

    /** save news */
    public function save_news() {
        $this->form_validation->set_rules('title', 'News Title', 'trim|required');
        if (empty($_FILES['image']['name'])) {
            $this->form_validation->set_rules('image', 'Image', 'required');
        }
        $this->form_validation->set_rules('description', 'Description', 'trim|required');

        header('Content-Type: application/json');
        if ($this->form_validation->run() === TRUE) {
            if ($this->input->post('newsid') == null) {
                $file_name = $this->news_image();

                $dataPost = array(
                    'file_name' => $file_name,
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description'),
                    'news_date' => date('Y-m-d'),
                    'news_time' => date('h:i A'),
                    'created_on' => time()
                );

                $this->backend->insert_data('news', $dataPost);
                echo json_encode(['status' => true, 'message' => 'News has been added successfully.', 'redirect' => site_url('news/index')]);
            } else {
                /** edit function */
            }
        } else {
            $error = ['title' => form_error('title'), 'image' => form_error('image'), 'description' => form_error('description')];
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
