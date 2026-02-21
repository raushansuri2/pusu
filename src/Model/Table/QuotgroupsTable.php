<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class QuotgroupsTable extends Table
{
    public static function defaultConnectionName(): string
    {
        return 'default';
    }

    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('quotgroups');
        $this->setDisplayField('group_name');
        $this->setPrimaryKey('id');

        // $this->belongsTo('Users', [
        //     'foreignKey' => 'user_id',
        //     'joinType' => 'LEFT'
        // ]);

        // $this->belongsTo('States', [
        //     'foreignKey' => 'state_id',
        //     'joinType' => 'LEFT'
        // ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('ggroup_name   ')
            ->maxLength('group_name', 255)
            ->requirePresence('group_name', 'create')
            ->notEmptyString('group_name');

        $validator
            ->scalar('SIC_Code')
            ->maxLength('SIC_Code', 50)
            ->requirePresence('SIC_Code', 'create')
            ->notEmptyString('SIC_Code');

        $validator
            ->scalar('address1')
            ->maxLength('address1', 255)
            ->requirePresence('address1', 'create')
            ->notEmptyString('address1');

        $validator
            ->scalar('address2')
            ->maxLength('address2', 255)
            ->allowEmptyString('address2');

    
        $validator
            ->scalar('city')
            ->maxLength('city', 100)
            ->requirePresence('city', 'create')
            ->notEmptyString('city');

        $validator
            ->scalar('state_name')
            ->maxLength('state_name', 50)
            ->requirePresence('state_name', 'create')
            ->notEmptyString('state_name');

        
        $validator
            ->scalar('zip')
            ->maxLength('zip', 20)
            ->requirePresence('zip', 'create')
            ->notEmptyString('zip');

        return $validator;
    }

    // public function buildRules(RulesChecker $rules): RulesChecker
    // {
    //     $rules->add($rules->existsIn(['state_id'], 'States'));
    //     $rules->add($rules->existsIn(['user_id'], 'Users'));
    //     return $rules;
    // }
}
