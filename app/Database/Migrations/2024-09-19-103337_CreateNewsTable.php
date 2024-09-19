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
                'auto_increment' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ],
            'introduction' => [
                'type' => 'VARCHAR',
                'constraint' => '500',
                'null' => true,
            ],
            'body' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'author_id' => [
                'type' => 'INT',
                'null' => true,
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
