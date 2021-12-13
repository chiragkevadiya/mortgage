<?php

class Email_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_email() {
        $this->db->select('e.emailid');
        $this->db->from('email as e');

        if ($this->input->post('search')) {
            $search = strtolower($this->input->post('search'));
            $this->db->group_start();
            $this->db->or_like('LOWER(e.subject)', $search);
            $this->db->or_like('LOWER(e.name_of_template)', $search);
            $this->db->or_like('LOWER(e.message)', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    function get_all_email($limit, $start) {
        $this->db->select("e.*");
        $this->db->from('email as e');

        if ($this->input->post('search')) {
            $search = strtolower($this->input->post('search'));
            $this->db->group_start();
            $this->db->or_like('LOWER(e.subject)', $search);
            $this->db->or_like('LOWER(e.name_of_template)', $search);
            $this->db->or_like('LOWER(e.message)', $search);
            $this->db->group_end();
        }

        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }

        $this->db->order_by('e.id', 'asc');
        $res = $this->db->get()->result();

        if (count($res) > 0) {
            return ['status' => TRUE, 'data' => $res];
        } else {
            return ['status' => FALSE];
        }
    }

}
