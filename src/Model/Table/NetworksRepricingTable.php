<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class NetworksRepricingTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('networks_repricing');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        
        $this->hasMany('Programs', [
            'foreignKey' => 'network_id',
            'dependent' => true
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
            ->scalar('programs')
            ->allowEmptyString('programs');

        $validator
            ->integer('status')
            ->requirePresence('status', 'create')
            ->notEmptyString('status')
            ->inList('status', [0, 1], 'Status must be either 0 (Inactive) or 1 (Active)');

        return $validator;
    }
}
