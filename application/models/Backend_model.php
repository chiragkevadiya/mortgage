<?php

class Backend_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * insert row into table
     */
    public function insert_data($table, $data) {
        $this->db->insert($table, $data);
        return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
    }

    /**
     * insert batch data into table
     */
    public function insert_batch($table, $data = array()) {
        $insert = $this->db->insert_batch($table, $data);
        return $insert ? true : false;
    }

    /**
     * get single row from table
     */
    public function get_table_row($table, $condition_arr) {
        $this->db->select('*');
        $this->db->from($table);
        if ($condition_arr) {
            $this->db->where($condition_arr);
        }
        return $this->db->get()->row();
    }

    // check row exist or not
    function check_row_exist($table, $condition) {
        $this->db->where($condition);
        $this->db->select('*');
        $query = $this->db->get($table);
        $row_count = $query->num_rows();

        if ($row_count > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * delete row from table
     */
    public function delete_data($table, $condition) {
        $this->db->where($condition);
        $this->db->delete($table);

        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * count number of rows.
     */
    public function count_table_rows($table, $condition = NULL) {
        $this->db->select("*");
        $this->db->from($table);

        if ($condition) {
            $this->db->where($condition);
        }

        return $this->db->count_all_results();
    }

    /**
     * update table row
     */
    public function update_data($table, $condition, $data) {
        if ($condition) {
            $this->db->update($table, $data, $condition);
        } else {
            $this->db->update($table, $data);
        }

        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * get multiple rows
     */
    public function get_table($table, $condition = NULL, $order = NULL, $column = NULL) {
        ($column != NULL) ? $this->db->select($column) : $this->db->select("*");
        $this->db->from($table);

        if ($condition) {
            $this->db->where($condition);
        }

        if ($order) {
            $this->db->order_by($order['column'], $order['order']);
        }

        return $this->db->get()->result();
    }

    function get_user_count() {
        $this->db->select('u.id');
        $this->db->from('users as u');
        $this->db->join('users_groups as ug', 'ug.user_id = u.id');
        $this->db->join('groups as g', 'g.id = ug.group_id');

        $this->db->where('g.name !=', 'admin');

        if ($this->input->post('search')) {
            $search = strtolower($this->input->post('search'));
            $this->db->group_start();

            $this->db->or_like('LOWER(u.full_name)', $search);
            $this->db->or_like('LOWER(u.email)', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    function get_user_all($limit, $start) {
        $this->db->select('u.id, u.username, u.active, u.email, u.full_name, u.phone, u.profile_picture, u.profile_url, FROM_UNIXTIME(u.created_on) as created_on, g.name as user_role');
        $this->db->from('users as u');
        $this->db->join('users_groups as ug', 'ug.user_id = u.id');
        $this->db->join('groups as g', 'g.id = ug.group_id');

        $this->db->where('g.name !=', 'admin');

        if ($this->input->post('search')) {
            $search = strtolower($this->input->post('search'));
            $this->db->group_start();

            $this->db->or_like('LOWER(u.full_name)', $search);
            $this->db->or_like('LOWER(u.email)', $search);
            $this->db->group_end();
        }

        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }

        $this->db->order_by('u.id', 'desc');
        $res = $this->db->get()->result();

        if (count($res) > 0) {
            return ['status' => TRUE, 'data' => $res];
        } else {
            return ['status' => FALSE];
        }
    }
    
    function getPreRequest() {
        $this->db->select('p.id');
        $this->db->from('prequal_request as p');
        $this->db->join('users as u', 'p.user_id = u.id');

        if ($this->input->post('search')) {
            $search = strtolower($this->input->post('search'));
            $this->db->group_start();

            $this->db->or_like('LOWER(p.clientName)', $search);
            $this->db->or_like('LOWER(p.clientPhone)', $search);
            $this->db->or_like('LOWER(p.clientEmail)', $search);
            $this->db->or_like('LOWER(p.state)', $search);
            $this->db->or_like('LOWER(u.full_name)', $search);
            $this->db->or_like('LOWER(u.email)', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    function getAllPreRequest($limit, $start) {
        $this->db->select('p.*, u.email, u.full_name, u.phone, u.profile_picture, u.profile_url, FROM_UNIXTIME(u.created_on) as created_on, g.name as user_role');
        $this->db->from('prequal_request as p');
        $this->db->join('users as u', 'p.user_id = u.id');
        $this->db->join('users_groups as ug', 'ug.user_id = u.id');
        $this->db->join('groups as g', 'g.id = ug.group_id');

        if ($this->input->post('search')) {
            $search = strtolower($this->input->post('search'));
            $this->db->group_start();

            $this->db->or_like('LOWER(p.clientName)', $search);
            $this->db->or_like('LOWER(p.clientPhone)', $search);
            $this->db->or_like('LOWER(p.clientEmail)', $search);
            $this->db->or_like('LOWER(p.state)', $search);
            $this->db->or_like('LOWER(u.full_name)', $search);
            $this->db->or_like('LOWER(u.email)', $search);
            $this->db->group_end();
        }

        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }

        $this->db->order_by('p.id', 'desc');
        $res = $this->db->get()->result();

        if (count($res) > 0) {
            return ['status' => TRUE, 'data' => $res];
        } else {
            return ['status' => FALSE];
        }
    }
    
    function getRefinance() {
        $this->db->select('r.id');
        $this->db->from('refinance_request as r');
        $this->db->join('users as u', 'r.user_id = u.id');

        if ($this->input->post('search')) {
            $search = strtolower($this->input->post('search'));
            $this->db->group_start();

            $this->db->or_like('LOWER(r.name)', $search);
            $this->db->or_like('LOWER(r.phone)', $search);
            $this->db->or_like('LOWER(r.email)', $search);
            $this->db->or_like('LOWER(u.full_name)', $search);
            $this->db->or_like('LOWER(u.email)', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    function getAllRefinance($limit, $start) {
        $this->db->select('r.*, u.email, u.full_name, u.phone, u.profile_picture, u.profile_url, FROM_UNIXTIME(u.created_on) as created_on, g.name as user_role');
        $this->db->from('refinance_request as r');
        $this->db->join('users as u', 'r.user_id = u.id');
        $this->db->join('users_groups as ug', 'ug.user_id = u.id');
        $this->db->join('groups as g', 'g.id = ug.group_id');

        if ($this->input->post('search')) {
            $search = strtolower($this->input->post('search'));
            $this->db->group_start();

            $this->db->or_like('LOWER(r.name)', $search);
            $this->db->or_like('LOWER(r.phone)', $search);
            $this->db->or_like('LOWER(r.email)', $search);
            $this->db->or_like('LOWER(u.full_name)', $search);
            $this->db->or_like('LOWER(u.email)', $search);
            $this->db->group_end();
        }

        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }

        $this->db->order_by('r.requestId', 'desc');
        $res = $this->db->get()->result();

        if (count($res) > 0) {
            return ['status' => TRUE, 'data' => $res];
        } else {
            return ['status' => FALSE];
        }
    }
}
