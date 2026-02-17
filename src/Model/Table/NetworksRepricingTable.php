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

        return $validator;
    }
}
