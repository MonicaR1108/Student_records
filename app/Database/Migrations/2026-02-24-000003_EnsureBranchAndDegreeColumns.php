<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EnsureBranchAndDegreeColumns extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('students')) {
            return;
        }

        $hasCourse = $this->db->query("SHOW COLUMNS FROM `students` LIKE 'course'")->getRowArray() !== null;
        $hasBranch = $this->db->query("SHOW COLUMNS FROM `students` LIKE 'branch'")->getRowArray() !== null;
        $hasDegree = $this->db->query("SHOW COLUMNS FROM `students` LIKE 'degree'")->getRowArray() !== null;

        if ($hasCourse && ! $hasBranch) {
            $this->db->query('ALTER TABLE `students` CHANGE `course` `branch` VARCHAR(100) NULL');
            $hasBranch = true;
        }

        if (! $hasDegree) {
            $position = $hasBranch ? 'AFTER `branch`' : '';
            $this->db->query("ALTER TABLE `students` ADD `degree` VARCHAR(100) NULL {$position}");
        }
    }

    public function down()
    {
        if (! $this->db->tableExists('students')) {
            return;
        }

        $hasBranch = $this->db->query("SHOW COLUMNS FROM `students` LIKE 'branch'")->getRowArray() !== null;
        $hasCourse = $this->db->query("SHOW COLUMNS FROM `students` LIKE 'course'")->getRowArray() !== null;
        $hasDegree = $this->db->query("SHOW COLUMNS FROM `students` LIKE 'degree'")->getRowArray() !== null;

        if ($hasDegree) {
            $this->db->query('ALTER TABLE `students` DROP COLUMN `degree`');
        }

        if ($hasBranch && ! $hasCourse) {
            $this->db->query('ALTER TABLE `students` CHANGE `branch` `course` VARCHAR(100) NULL');
        }
    }
}

