<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTimeEntriesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'project_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'started_at'       => ['type' => 'DATETIME'],
            'ended_at'         => ['type' => 'DATETIME', 'null' => true],
            'duration_seconds' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'note'             => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('project_id', 'projects', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('time_entries');
    }

    public function down()
    {
        $this->forge->dropTable('time_entries');
    }
}
