<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUsernameToAdmins extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('admins')) {
            return;
        }

        if (! $this->db->fieldExists('username', 'admins')) {
            $this->db->query('ALTER TABLE `admins` ADD `username` VARCHAR(50) NULL AFTER `name`');
        }

        $admins = $this->db->table('admins')->get()->getResultArray();
        foreach ($admins as $admin) {
            if (! empty($admin['username'])) {
                continue;
            }

            $base = strtolower(trim((string) ($admin['name'] ?? 'admin')));
            if ($base === '') {
                $base = 'admin';
            }
            $base = preg_replace('/[^a-z0-9_]/', '_', $base) ?: 'admin';
            $candidate = $base;
            $suffix = 1;

            while ($this->db->table('admins')->where('username', $candidate)->where('id !=', $admin['id'])->countAllResults() > 0) {
                $candidate = $base . '_' . $suffix;
                $suffix++;
            }

            $this->db->table('admins')->where('id', $admin['id'])->update([
                'username' => $candidate,
            ]);
        }

        $hasUnique = $this->db->query("SHOW INDEX FROM `admins` WHERE Column_name = 'username' AND Non_unique = 0")->getRowArray();
        if (! $hasUnique) {
            $this->db->query('ALTER TABLE `admins` ADD UNIQUE (`username`)');
        }

        $this->db->query('ALTER TABLE `admins` MODIFY `username` VARCHAR(50) NOT NULL');
        if ($this->db->fieldExists('email', 'admins')) {
            $this->db->query('ALTER TABLE `admins` MODIFY `email` VARCHAR(100) NULL');
        }
    }

    public function down()
    {
        if (! $this->db->tableExists('admins')) {
            return;
        }

        if ($this->db->fieldExists('username', 'admins')) {
            $index = $this->db->query("SHOW INDEX FROM `admins` WHERE Key_name = 'admins_username_unique'")->getRowArray();
            if ($index) {
                $this->db->query('ALTER TABLE `admins` DROP INDEX `admins_username_unique`');
            }

            $this->db->query('ALTER TABLE `admins` DROP COLUMN `username`');
        }

        if ($this->db->fieldExists('email', 'admins')) {
            $this->db->query('ALTER TABLE `admins` MODIFY `email` VARCHAR(100) NOT NULL');
        }
    }
}
