<?php

namespace AHAbid\EloquentCassandra\Repository;

use Illuminate\Database\Migrations\DatabaseMigrationRepository as BaseDatabaseMigrationRepository;

class DatabaseMigrationRepository extends BaseDatabaseMigrationRepository
{
    /**
     * Get the completed migrations.
     *
     * @return array
     */
    public function getRan()
    {
        return $this->table()
            ->get()
            ->sortBy('batch')
            ->pluck('migration')
            ->all();
    }

    /**
     * Create the migration repository data store.
     *
     * @return void
     */
    public function createRepository()
    {
        $schema = $this->getConnection()->getSchemaBuilder();

        $schema->create($this->table, function ($table) {
            // The migrations table is responsible for keeping track of which of the
            // migrations have actually run for the application. We'll create the
            // table to hold the migration file's path as well as the batch ID.
            $table->uuid('id');
            $table->string('migration');
            $table->integer('batch');
            $table->primary('id');
        });
    }

    /**
     * Log that a migration was run.
     *
     * @param  string  $file
     * @param  int  $batch
     * @return void
     */
    public function log($file, $batch)
    {
        $record = [
            'id' => $this->getConnection()->raw('uuid()'),
            'migration' => $file,
            'batch' => $batch
        ];

        $this->table()->insert($record);
    }
}