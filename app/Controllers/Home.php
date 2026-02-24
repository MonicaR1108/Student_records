<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('home');
    }

    public function dbtest()
    {
        try {
            $db = \Config\Database::connect();
            $db->query('SELECT 1');

            echo '<h2>Database connected successfully!</h2>';
        } catch (\Throwable $e) {
            echo '<h2>Database connection failed!</h2>';
            echo '<pre>';
            echo esc($e->getMessage());
            echo '</pre>';
        }
    }
}
