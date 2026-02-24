<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateStudentsForPortal extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('students')) {
            return;
        }

        if ($this->db->fieldExists('roll_no', 'students') && ! $this->db->fieldExists('register_number', 'students')) {
            $this->db->query('ALTER TABLE `students` CHANGE `roll_no` `register_number` VARCHAR(50) NOT NULL');
        }

        if (! $this->db->fieldExists('father_name', 'students')) {
            $this->db->query('ALTER TABLE `students` ADD `father_name` VARCHAR(100) NULL AFTER `name`');
        }

        if (! $this->db->fieldExists('mother_name', 'students')) {
            $this->db->query('ALTER TABLE `students` ADD `mother_name` VARCHAR(100) NULL AFTER `father_name`');
        }

        if (! $this->db->fieldExists('phone', 'students')) {
            $this->db->query('ALTER TABLE `students` ADD `phone` VARCHAR(15) NULL AFTER `email`');
        }

        if (! $this->db->fieldExists('photo', 'students')) {
            $this->db->query('ALTER TABLE `students` ADD `photo` VARCHAR(255) NULL AFTER `register_number`');
        }

        if ($this->db->fieldExists('register_number', 'students')) {
            $index = $this->db->query("SHOW INDEX FROM `students` WHERE Column_name = 'register_number' AND Non_unique = 0")->getRowArray();
            if (! $index) {
                $this->db->query('ALTER TABLE `students` ADD UNIQUE (`register_number`)');
            }
        }
    }

    public function down()
    {
        if (! $this->db->tableExists('students')) {
            return;
        }

        if ($this->db->fieldExists('photo', 'students')) {
            $this->db->query('ALTER TABLE `students` DROP COLUMN `photo`');
        }

        if ($this->db->fieldExists('phone', 'students')) {
            $this->db->query('ALTER TABLE `students` DROP COLUMN `phone`');
        }

        if ($this->db->fieldExists('mother_name', 'students')) {
            $this->db->query('ALTER TABLE `students` DROP COLUMN `mother_name`');
        }

        if ($this->db->fieldExists('father_name', 'students')) {
            $this->db->query('ALTER TABLE `students` DROP COLUMN `father_name`');
        }

        if ($this->db->fieldExists('register_number', 'students') && ! $this->db->fieldExists('roll_no', 'students')) {
            $this->db->query('ALTER TABLE `students` CHANGE `register_number` `roll_no` VARCHAR(50) NOT NULL');
        }
    }
}
