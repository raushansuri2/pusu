<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateGeneralSettings extends AbstractMigration
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
        $table = $this->table('general_settings');
        $table
            ->addColumn('default_ppo_network_discount', 'decimal', [
                'default' => '0.00',
                'null' => true,
                'precision' => 10,
                'scale' => 2,
                'comment' => 'Default PPO Network Discount'
            ])
            ->addColumn('manual_discretion_specific_rates', 'decimal', [
                'default' => '0.70',
                'null' => true,
                'precision' => 10,
                'scale' => 2,
                'comment' => 'Manual Discretion: Specific Rates'
            ])
            ->addColumn('manual_discretion_aggregate_rates', 'decimal', [
                'default' => '0.70',
                'null' => true,
                'precision' => 10,
                'scale' => 2,
                'comment' => 'Manual Discretion: Aggregate Rates'
            ])
            ->addColumn('minimum_experience_rtm_specific_rates', 'decimal', [
                'default' => '0.80',
                'null' => true,
                'precision' => 10,
                'scale' => 2,
                'comment' => 'Minimum Experience RTM: Specific Rates'
            ])
            ->addColumn('minimum_experience_rtm_aggregate_factors', 'decimal', [
                'default' => '0.90',
                'null' => true,
                'precision' => 10,
                'scale' => 2,
                'comment' => 'Minimum Experience RTM: Aggregate Factors'
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
