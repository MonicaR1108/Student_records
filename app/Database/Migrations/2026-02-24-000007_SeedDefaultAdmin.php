<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SeedDefaultAdmin extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('admins')) {
            return;
        }

        $existing = $this->db->table('admins')->where('email', 'admin@college.edu')->get()->getRowArray();
        if ($existing) {
            return;
        }

        $this->db->table('admins')->insert([
            'name'     => 'Portal Admin',
            'email'    => 'admin@college.edu',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
        ]);
    }

    public function down()
    {
        if (! $this->db->tableExists('admins')) {
            return;
        }

        $this->db->table('admins')->where('email', 'admin@college.edu')->delete();
    }
}
