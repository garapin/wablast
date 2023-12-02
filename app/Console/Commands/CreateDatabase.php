<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateDatabase extends Command
{
    protected $signature = 'db:create';

    protected $description = 'Create the database';

    public function handle()
    {
        $schemaName = config('database.connections.mysql.database');
        $charset = config('database.connections.mysql.charset', 'utf8mb4');
        $collation = config('database.connections.mysql.collation', 'utf8mb4_unicode_ci');

        config(['database.connections.mysql.database' => null]);

        $query = "CREATE DATABASE IF NOT EXISTS $schemaName CHARACTER SET $charset COLLATE $collation;";
        DB::statement($query);

        config(['database.connections.mysql.database' => $schemaName]);

        $this->info("Database $schemaName created successfully.");
    }
}
