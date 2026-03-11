<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class RequestQuotesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('request_quots');
        $this->setDisplayField('id');
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

        $this->belongsTo('LoosePlans', [
            'foreignKey' => 'loss_plan',
            'propertyName' => 'loosePlan',
            'joinType' => 'LEFT'
        ]);

        $this->belongsTo('BenifitPlans', [
            'foreignKey' => 'benifit_plan',
            'propertyName' => 'benifitPlan',
            'joinType' => 'LEFT'
        ]);

        $this->belongsTo('NetworksRepricing', [
            'foreignKey' => 'networking_id',
            'propertyName' => 'network',
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
            ->scalar('email')
            ->maxLength('email', 255)
            ->requirePresence('email', 'create')
            ->notEmptyString('email')
            ->email('email');

        $validator
            ->scalar('phone')
            ->maxLength('phone', 50)
            ->allowEmptyString('phone');

        $validator
            ->scalar('company_name')
            ->maxLength('company_name', 255)
            ->allowEmptyString('company_name');

        $validator
            ->scalar('contact_person')
            ->maxLength('contact_person', 255)
            ->allowEmptyString('contact_person');

        $validator
            ->scalar('product_type')
            ->maxLength('product_type', 255)
            ->allowEmptyString('product_type');

        $validator
            ->scalar('coverage_amount')
            ->maxLength('coverage_amount', 255)
            ->allowEmptyString('coverage_amount');

        $validator
            ->scalar('zip_code')
            ->maxLength('zip_code', 20)
            ->allowEmptyString('zip_code');

        $validator
            ->scalar('state')
            ->maxLength('state', 100)
            ->allowEmptyString('state');

        $validator
            ->scalar('message')
            ->allowEmptyString('message');

        $validator
            ->integer('status')
            ->requirePresence('status', 'create')
            ->notEmptyString('status')
            ->inList('status', [0, 1], 'Status must be either 0 (Pending) or 1 (Processed)');

        return $validator;
    }
}
