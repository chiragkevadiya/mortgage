<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Settings_table extends CI_Migration {

    public function __construct() {
        parent::__construct();
        $this->load->dbforge();
    }

    public function up() {
        $this->dbforge->drop_table('settings', TRUE);

        $this->dbforge->add_field([
            'id' => [
                'type' => 'MEDIUMINT',
                'constraint' => '8',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'profile_image' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE
            ],
            'designation' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE
            ],
            'slogan' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ],
            'contact' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE
            ],
            'address' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ],
            'disclaimer' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'linkedin' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ],
            'google' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ],
            'zillow' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ],
            'website' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ],
            'apply_link' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ],
            'terms_and_conditions' => [
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
        $this->dbforge->create_table('settings');

        // Dumping data for table 'info'
        $data = [
            'name' => 'Isaac Weiser',
            'designation' => 'Branch Manager NMLS 19003',
            'slogan' => 'CREATING HAPPY CLIENTS, ONE AT A TIME, YOU ARE NEXT!',
            'contact' => '+1(718)541-2872',
            'email' => 'isaac@fortfunding.com',
            'address' => '65 Whipple Street Brookly NY 11206 0. 718.782.0400 C.718.541.2872',
            'disclaimer' => 'Registered Mortgage Broker New York State Department of Financial Services. NMLS #39463. Licence #A003656 All loan arranged through third party providers.'
        ];

        $this->db->insert('settings', $data);
    }

    public function down() {
        $this->dbforge->drop_table('settings', TRUE);
    }

}
