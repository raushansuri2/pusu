<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class RequestQuotsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('request_quots');
        //$this->setDisplayField('plan_name');
        $this->setPrimaryKey('id');

       $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'LEFT'
        ]);

        $this->belongsTo('Programs', [
            'foreignKey' => 'program_id',
            'joinType' => 'LEFT'
        ]);
         $this->belongsTo('Quotgroups', [
            'foreignKey' => 'group_id',
            'joinType' => 'LEFT'
        ]);
    }

    // public function validationDefault(Validator $validator): Validator
    // {
    //     $validator
    //         ->scalar('plan_name')
    //         ->maxLength('plan_name', 255)
    //         ->requirePresence('plan_name', 'create')
    //         ->notEmptyString('plan_name');

    //     return $validator;
    // }
}
