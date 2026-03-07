<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class LoosePlansTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('loss_plans');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->scalar('type')
            ->maxLength('type', 255)
            ->allowEmptyString('type');

        $validator
            ->scalar('plan_type')
            ->maxLength('plan_type', 255)
            ->allowEmptyString('plan_type');

        $validator
            ->scalar('deductible_in')
            ->decimal('deductible_in', 2)
            ->allowEmptyString('deductible_in');

        $validator
            ->scalar('deductible_out')
            ->decimal('deductible_out', 2)
            ->allowEmptyString('deductible_out');

        $validator
            ->scalar('deductible_co_insurance')
            ->decimal('deductible_co_insurance', 2)
            ->allowEmptyString('deductible_co_insurance');

        $validator
            ->scalar('deductible_co_insurance_out')
            ->decimal('deductible_co_insurance_out', 2)
            ->allowEmptyString('deductible_co_insurance_out');

        $validator
            ->scalar('deductible_oop_in')
            ->decimal('deductible_oop_in', 2)
            ->allowEmptyString('deductible_oop_in');

        $validator
            ->scalar('deductible_oop_out')
            ->decimal('deductible_oop_out', 2)
            ->allowEmptyString('deductible_oop_out');

        $validator
            ->scalar('deductible_oop_includes_deductible_in')
            ->boolean('deductible_oop_includes_deductible_in')
            ->allowEmptyString('deductible_oop_includes_deductible_in');

        $validator
            ->scalar('deductible_oop_includes_deductible_out')
            ->boolean('deductible_oop_includes_deductible_out')
            ->allowEmptyString('deductible_oop_includes_deductible_out');

        $validator
            ->scalar('rx_copay_generic')
            ->decimal('rx_copay_generic', 2)
            ->allowEmptyString('rx_copay_generic');

        $validator
            ->scalar('rx_copay_formulary')
            ->decimal('rx_copay_formulary', 2)
            ->allowEmptyString('rx_copay_formulary');

        $validator
            ->scalar('rx_copay_non_formulary')
            ->decimal('rx_copay_non_formulary', 2)
            ->allowEmptyString('rx_copay_non_formulary');

        $validator
            ->scalar('rx_copay_specialty')
            ->decimal('rx_copay_specialty', 2)
            ->allowEmptyString('rx_copay_specialty');

        $validator
            ->scalar('rx_covers_specialty')
            ->boolean('rx_covers_specialty')
            ->allowEmptyString('rx_covers_specialty');

        $validator
            ->scalar('rx_deductible')
            ->decimal('rx_deductible', 2)
            ->allowEmptyString('rx_deductible');

        $validator
            ->scalar('rx_max_copay')
            ->decimal('rx_max_copay', 2)
            ->allowEmptyString('rx_max_copay');

        $validator
            ->scalar('rx_max_out_of_pocket')
            ->decimal('rx_max_out_of_pocket', 2)
            ->allowEmptyString('rx_max_out_of_pocket');

        $validator
            ->scalar('plan_level')
            ->maxLength('plan_level', 255)
            ->allowEmptyString('plan_level');

        $validator
            ->scalar('plan_category')
            ->maxLength('plan_category', 255)
            ->allowEmptyString('plan_category');

        $validator
            ->scalar('plan_tier')
            ->maxLength('plan_tier', 255)
            ->allowEmptyString('plan_tier');

        $validator
            ->scalar('plan_network')
            ->maxLength('plan_network', 255)
            ->allowEmptyString('plan_network');

        $validator
            ->scalar('coinsurance')
            ->decimal('coinsurance', 2)
            ->allowEmptyString('coinsurance');

        $validator
            ->integer('status')
            ->requirePresence('status', 'create')
            ->notEmptyString('status')
            ->inList('status', [0, 1], 'Status must be either 0 (Inactive) or 1 (Active)');

        return $validator;
    }
}
