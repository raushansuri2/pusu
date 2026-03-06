<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class CensusTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('census');
        //$this->setDisplayField('plan_name');
        $this->setPrimaryKey('id');
        
        // $this->hasMany('Programs', [
        //     'foreignKey' => 'benifit_plan_id',
        //     'dependent' => true
        // ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('plan_name')
            ->maxLength('plan_name', 255)
            ->requirePresence('plan_name', 'create')
            ->notEmptyString('plan_name');

        return $validator;
    }
}
