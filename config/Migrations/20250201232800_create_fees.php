<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateFees extends AbstractMigration
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
        $table = $this->table('fees');
        $table
            ->addColumn('program_id', 'integer', [
                'null' => true,
                'comment' => 'Related to programs table'
            ])
            ->addColumn('name', 'string', [
                'limit' => 255,
                'null' => false,
                'comment' => 'Fee name'
            ])
            ->addColumn('value', 'decimal', [
                'default' => '0.00',
                'null' => false,
                'precision' => 10,
                'scale' => 2,
                'comment' => 'Fee value'
            ])
            ->addColumn('value_type', 'string', [
                'null' => true,
                'limit' => 255,
                'comment' => 'percentage of PEPM'
            ])
            ->addColumn('is_editable', 'boolean', [
                'default' => true,
                'null' => true,
                'comment' => 'Whether the fee is editable'
            ])
            ->addColumn('is_applied_to_premium', 'boolean', [
                'default' => false,
                'null' => true,
                'comment' => 'Whether fee is applied to premium'
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
            ->addForeignKey(
                'program_id',
                'programs',
                'id',
                [
                    'delete' => 'SET_NULL',
                    'update' => 'NO_ACTION',
                    'constraint' => 'fk_fees_programs'
                ]
            )
            ->create();
    }
}
