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
            ->scalar('plan_name')
            ->maxLength('plan_name', 255)
            ->requirePresence('plan_name', 'create')
            ->notEmptyString('plan_name');

        $validator
            ->scalar('program_id')
            ->allowEmptyString('program_id');

        $validator
            ->scalar('Spec_Deductible')
            ->numeric('Spec_Deductible')
            ->allowEmptyString('Spec_Deductible');

        $validator
            ->scalar('Spec_Contract')
            ->maxLength('Spec_Contract', 255)
            ->allowEmptyString('Spec_Contract');

        $validator
            ->scalar('Aggregating_Spec_Deductible')
            ->numeric('Aggregating_Spec_Deductible')
            ->allowEmptyString('Aggregating_Spec_Deductible');

        $validator
            ->scalar('Agg_Contract')
            ->maxLength('Agg_Contract', 255)
            ->allowEmptyString('Agg_Contract');

        $validator
            ->scalar('Agg_Corridor')
            ->numeric('Agg_Corridor')
            ->allowEmptyString('Agg_Corridor');

        $validator
            ->scalar('Commission')
            ->numeric('Commission')
            ->allowEmptyString('Commission');

        $validator
            ->integer('status')
            ->requirePresence('status', 'create')
            ->notEmptyString('status')
            ->inList('status', [0, 1], 'Status must be either 0 (Inactive) or 1 (Active)');

        return $validator;
    }
}
