<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ProgramsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('programs');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Fees', [
            'foreignKey' => 'program_id',
            'dependent' => true
        ]);

        $this->belongsTo('NetworksRepricing', [
            'foreignKey' => 'network_id',
            'className' => 'NetworksRepricing',
            'joinType' => 'LEFT'
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('p_type')
            ->maxLength('p_type', 255)
            ->allowEmptyString('p_type')
            ->inList('p_type', ['default', 'agent'], 'Please select a valid program type');

        $validator
            ->integer('network_id')
            ->allowEmptyString('network_id');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['network_id'], 'NetworksRepricing'));
        return $rules;
    }
}
