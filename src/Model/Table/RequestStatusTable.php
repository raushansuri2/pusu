<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class RequestStatusTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('request_status');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('RequestQuots', [
            'foreignKey' => 'request_id',
            'joinType' => 'LEFT'
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'LEFT'
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->integer('request_id')
            ->requirePresence('request_id', 'create')
            ->notEmptyString('request_id');

        $validator
            ->integer('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id');

        $validator
            ->integer('status')
            ->requirePresence('status', 'create')
            ->notEmptyString('status');

        $validator
            ->scalar('message')
            ->allowEmptyString('message');

        $validator
            ->dateTime('created')
            ->allowEmptyDateTime('created');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['request_id'], 'RequestQuots'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
