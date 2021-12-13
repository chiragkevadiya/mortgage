<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_News_table extends CI_Migration {

    public function up() {
        $this->dbforge->add_field([
            'newsid' => [
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => TRUE
            ],
            'file_name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ],
            'file_path' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ],
            'file_name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ],
            'file_path' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ],
            'news_date' => [
                'type' => 'DATE',
                'null' => TRUE
            ],
            'news_time' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE
            ],
            'created_on' => [
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => TRUE,
            ],
            'updated_on' => [
                'type' => 'INT',
                'constraint' => '11',
                'unsigned' => TRUE,
            ],
            'active' => [
                'type' => 'TINYINT',
                'constraint' => '1',
                'unsigned' => TRUE
            ]
        ]);

        $this->dbforge->add_key('newsid', TRUE);
        $this->dbforge->create_table('news');
    }

    public function down() {
        $this->dbforge->drop_table('news');
    }

}
