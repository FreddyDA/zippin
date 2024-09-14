<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Exception;

class TestMongoConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:mongo-connection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the MongoDB connection';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            // Intentar realizar una consulta simple
            $databases = DB::connection('mongodb')->getMongoClient()->listDatabases();
            $this->info('Conexión a MongoDB satisfactoria.');
            foreach ($databases as $database) {
                $this->info('Base de datos: ' . $database->getName());
            }
        } catch (Exception $e) {
            $this->error('Error al conectar a MongoDB: ' . $e->getMessage());
        }

        return 0;
    }
}