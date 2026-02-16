<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateBenifitPlans extends AbstractMigration
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
        $table = $this->table('benifit_plans');
        $table
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'comment' => 'Primary key'
            ])
            ->addColumn('plan_name', 'string', [
                'limit' => 255,
                'null' => false,
                'comment' => 'Plan Name'
            ])
            ->addColumn('individual_deductible', 'decimal', [
                'default' => '0.00',
                'null' => true,
                'precision' => 10,
                'scale' => 2,
                'comment' => 'Individual Deductible'
            ])
            ->addColumn('individual_oop', 'decimal', [
                'default' => '0.00',
                'null' => true,
                'precision' => 10,
                'scale' => 2,
                'comment' => 'Individual OOP'
            ])
            ->addColumn('family_deductible', 'decimal', [
                'default' => '0.00',
                'null' => true,
                'precision' => 10,
                'scale' => 2,
                'comment' => 'Family Deductible'
            ])
            ->addColumn('family_oop', 'decimal', [
                'default' => '0.00',
                'null' => true,
                'precision' => 10,
                'scale' => 2,
                'comment' => 'Family OOP'
            ])
            ->addColumn('coinsurance', 'decimal', [
                'default' => '0.00',
                'null' => true,
                'precision' => 10,
                'scale' => 2,
                'comment' => 'Coinsurance'
            ])
            ->addColumn('oop_maximum', 'decimal', [
                'default' => '0.00',
                'null' => true,
                'precision' => 10,
                'scale' => 2,
                'comment' => 'OOP Maximum'
            ])
            ->addColumn('oop_includes_deductible', 'boolean', [
                'default' => false,
                'null' => true,
                'comment' => 'OOP Includes Deductible'
            ])
            ->addColumn('rx_copay_generic', 'decimal', [
                'default' => '0.00',
                'null' => true,
                'precision' => 10,
                'scale' => 2,
                'comment' => 'Rx Copay Generic'
            ])
            ->addColumn('rx_copay_formulary', 'boolean', [
                'default' => false,
                'null' => true,
                'comment' => 'Rx Copay Formulary'
            ])
            ->addColumn('rx_copay_non_formulary', 'boolean', [
                'default' => false,
                'null' => true,
                'comment' => 'Rx Copay Non-Formulary'
            ])
            ->addColumn('rx_covers_specialty', 'boolean', [
                'default' => false,
                'null' => true,
                'comment' => 'Rx covers specialty'
            ])
            ->addColumn('rx_copay_specialty', 'string', [
                'limit' => 255,
                'null' => true,
                'comment' => 'Rx copay specialty'
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
