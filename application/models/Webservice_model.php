<?php

class Webservice_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function json_decode() {
        $data = json_decode(file_get_contents('php://input'), TRUE);
        if ($data) {
            $this->form_validation->set_data($data);
        }

        return $data;
    }

    /**
     * insert row into table
     */
    public function insert_data($table, $data) {
        $this->db->insert($table, $data);
        return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
    }

    /** table row */
    public function get_table_row($table, $condition_arr) {
        $this->db->select('*');
        $this->db->from($table);
        if ($condition_arr) {
            $this->db->where($condition_arr);
        }
        return $this->db->get()->row();
    }

    /** delete row from table */
    public function delete_data($table, $condition) {
        $this->db->where($condition);
        $this->db->delete($table);

        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
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
}
