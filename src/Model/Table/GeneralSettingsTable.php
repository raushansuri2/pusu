<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class GeneralSettingsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('general_settings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->decimal('default_ppo_network_discount')
            ->allowEmptyString('default_ppo_network_discount')
            ->add('default_ppo_network_discount', 'validFormat', [
                'rule' => 'decimal',
                'message' => 'Please enter a valid decimal value'
            ]);

        $validator
            ->decimal('manual_discretion_specific_rates')
            ->allowEmptyString('manual_discretion_specific_rates')
            ->add('manual_discretion_specific_rates', 'validFormat', [
                'rule' => 'decimal',
                'message' => 'Please enter a valid decimal value'
            ]);

        $validator
            ->decimal('manual_discretion_aggregate_rates')
            ->allowEmptyString('manual_discretion_aggregate_rates')
            ->add('manual_discretion_aggregate_rates', 'validFormat', [
                'rule' => 'decimal',
                'message' => 'Please enter a valid decimal value'
            ]);

        $validator
            ->decimal('minimum_experience_rtm_specific_rates')
            ->allowEmptyString('minimum_experience_rtm_specific_rates')
            ->add('minimum_experience_rtm_specific_rates', 'validFormat', [
                'rule' => 'decimal',
                'message' => 'Please enter a valid decimal value'
            ]);

        $validator
            ->decimal('minimum_experience_rtm_aggregate_factors')
            ->allowEmptyString('minimum_experience_rtm_aggregate_factors')
            ->add('minimum_experience_rtm_aggregate_factors', 'validFormat', [
                'rule' => 'decimal',
                'message' => 'Please enter a valid decimal value'
            ]);

        return $validator;
    }
}
