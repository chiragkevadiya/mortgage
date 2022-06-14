<?php

class News_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_news() {
        $this->db->select('n.newsid');
        $this->db->from('news as n');

        $this->db->where(array('n.active' => 0));
        if ($this->input->post('search')) {
            $search = strtolower($this->input->post('search'));
            $this->db->group_start();
            $this->db->or_like('LOWER(n.title)', $search);
            $this->db->or_like('LOWER(n.description)', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    function get_all_news($limit, $start) {
        $base_url = base_url(NEWS_IMAGE);
        $this->db->select("n.*, CONCAT('$base_url', n.file_name) as file_path, DATE_FORMAT(n.news_date, '%a, %b %d %Y') as news_date");
        $this->db->from('news as n');

        $this->db->where(array('n.active' => 0));
        if ($this->input->post('search')) {
            $search = strtolower($this->input->post('search'));
            $this->db->group_start();
            $this->db->or_like('LOWER(n.title)', $search);
            $this->db->or_like('LOWER(n.description)', $search);
            $this->db->group_end();
        }

        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }

        $this->db->order_by('n.newsid', 'desc');
        $res = $this->db->get()->result();

        if (count($res) > 0) {
            return ['status' => TRUE, 'data' => $res];
        } else {
            return ['status' => FALSE];
        }
    }

    function all_users() {
        $this->db->select('u.device_token');
        $this->db->from('users as u');

        $this->db->where(array('u.active' => 1));
        $this->db->where('u.device_token is NOT NULL', NULL, FALSE);

        $res = $this->db->get()->result();

        if (count($res) > 0) {
            $tokens = array();
            foreach ($res as $row) {
                $tokens[] = $row->device_token;
            }

            return ['status' => TRUE, 'data' => $tokens];
        } else {
            return ['status' => FALSE];
        }
    }

}
