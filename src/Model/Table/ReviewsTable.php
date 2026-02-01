<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;
//use Cake\ORM\RulesChecker;
 
class ReviewsTable extends Table
{
    public function initialize(array $config): void
    {
        
		$this->belongsTo('Reviewfroms', [
			'className' => 'Users',
			//'bindingKey' => ['clientId'],
			'foreignKey' => ['reviewFrom']
		]);
		
		$this->belongsTo('Reviewtos', [
			'className' => 'Users',
			//'bindingKey' => ['clientId'],
			'foreignKey' => ['reviewTo']
		]);
		
		$this->belongsTo('Usersinformations', [
		    'className' => 'Usersinformations',
            'foreignKey' => 'reviewTo',
        ]);
		
    }
    
    public function validationCustomer(Validator $validator)
    {
       
		$validator->notEmpty('question', 'Please enter question.');
		$validator->notEmpty('answer', 'Please enter answer.');
		$validator->notEmpty('designation', 'Please enter feature designation.');
        $validator->add('image', [      
				'image' => [                    
					'rule'=>  function($value){
						$ext = explode(".", $value);
						$extension = strtolower(end($ext));
						
						if (in_array(trim($extension), array('jpg', 'jpeg', 'png', 'gif'))) {							
							return true;
						}
						return false;
					},
					'message'=>'Please select jpg, jpeg, gif and png only.',
                ]
		])->notEmpty('image');
        return $validator;
    }
    
    
    
	
    
    
    
    
    
    
   

}
