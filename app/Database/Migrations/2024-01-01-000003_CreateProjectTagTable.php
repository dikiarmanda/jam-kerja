<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProjectTagTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'project_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'tag_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);
        $this->forge->addPrimaryKey(['project_id', 'tag_id']);
        $this->forge->addForeignKey('project_id', 'projects', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tag_id', 'tags', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('project_tag');
    }

    public function down()
    {
        $this->forge->dropTable('project_tag');
    }
}
