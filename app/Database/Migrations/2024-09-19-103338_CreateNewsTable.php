<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNewsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,  // Match this with 'id' in the 'users' table
                'auto_increment' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'introduction' => [
                'type' => 'VARCHAR',
                'constraint' => '500',
                'null' => true,
            ],
            'body' => [
                'type' => 'TEXT',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => date('Y-m-d H:i:s'),
            ],
            'author_id' => [
                'type' => 'INT',
                'unsigned' => true,  // Ensure this is unsigned to match 'id' in 'users'
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('author_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('news');
    }

    public function down()
    {
        $this->forge->dropTable('news');
    }
}
