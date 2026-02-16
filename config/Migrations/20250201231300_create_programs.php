<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreatePrograms extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('programs');
        $table
            ->addColumn('name', 'string', [
                'limit' => 255,
                'null' => false,
                'comment' => 'Program name'
            ])
            ->addColumn('private_program', 'boolean', [
                'default' => false,
                'null' => true,
                'comment' => 'Whether the program is private'
            ])
            ->addColumn('status', 'enum', [
                'default' => 'active',
                'null' => true,
                'values' => ['active', 'inactive', 'pending'],
                'comment' => 'Program status'
            ])
            ->addColumn('created', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
                'null' => false,
            ])
            ->addColumn('modified', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
                'null' => false,
                'update' => 'CURRENT_TIMESTAMP',
            ])
            ->create();
    }
}
