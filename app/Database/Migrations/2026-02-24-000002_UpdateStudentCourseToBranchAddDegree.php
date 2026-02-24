<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateStudentCourseToBranchAddDegree extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('students')) {
            if ($this->db->fieldExists('course', 'students') && ! $this->db->fieldExists('branch', 'students')) {
                $this->db->query('ALTER TABLE `students` CHANGE `course` `branch` VARCHAR(100) NULL');
            }

            if (! $this->db->fieldExists('degree', 'students')) {
                $this->db->query('ALTER TABLE `students` ADD `degree` VARCHAR(100) NULL AFTER `branch`');
            }
        }
    }

    public function down()
    {
        if ($this->db->tableExists('students')) {
            if ($this->db->fieldExists('degree', 'students')) {
                $this->db->query('ALTER TABLE `students` DROP COLUMN `degree`');
            }

            if ($this->db->fieldExists('branch', 'students') && ! $this->db->fieldExists('course', 'students')) {
                $this->db->query('ALTER TABLE `students` CHANGE `branch` `course` VARCHAR(100) NULL');
            }
        }
    }
}

