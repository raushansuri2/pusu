<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class FeesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('fees');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Programs', [
            'foreignKey' => 'program_id',
            'joinType' => 'LEFT'
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name')
            ->add('name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->decimal('value')
            ->allowEmptyString('value')
            ->add('value', 'validFormat', [
                'rule' => 'decimal',
                'message' => 'Please enter a valid decimal value'
            ]);

        $validator
            ->scalar('value_type')
            ->maxLength('value_type', 255)
            ->allowEmptyString('value_type')
            ->inList('value_type', ['flat', 'percentage']);

        $validator
            ->boolean('is_editable')
            ->allowEmptyString('is_editable');

        $validator
            ->boolean('is_applied_to_premium')
            ->allowEmptyString('is_applied_to_premium');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['name']));
        return $rules;
    }
}
