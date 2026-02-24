<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGenderToStudents extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('students')) {
            return;
        }

        if (! $this->db->fieldExists('gender', 'students')) {
            $this->db->query("ALTER TABLE `students` ADD `gender` VARCHAR(10) NULL AFTER `mother_name`");
        }
    }

    public function down()
    {
        if (! $this->db->tableExists('students')) {
            return;
        }

        if ($this->db->fieldExists('gender', 'students')) {
            $this->db->query('ALTER TABLE `students` DROP COLUMN `gender`');
        }
    }
}
