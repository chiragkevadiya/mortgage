<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Email_table extends CI_Migration {

    public function __construct() {
        parent::__construct();
        $this->load->dbforge();
    }

    public function up() {
        $this->dbforge->drop_table('email', TRUE);

        $this->dbforge->add_field([
            'id' => [
                'type' => 'MEDIUMINT',
                'constraint' => '8',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'name_of_template' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE
            ],
            'subject' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ],
            'message' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'updated_on' => [
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => TRUE,
            ]
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('email');

        // Dumping data for table 'info'
        $data = [
            [
                'name_of_template' => 'mortgage_calculation'
            ],
            [
                'name_of_template' => 'loan_compare'
            ],
            [
                'name_of_template' => 'refinance_saving'
            ],
            [
                'name_of_template' => 'amortization_calc'
            ],
            [
                'name_of_template' => 'payoff_calc'
            ]
        ];

        $this->db->insert_batch('email', $data);
    }

    public function down() {
        $this->dbforge->drop_table('email', TRUE);
    }

}
