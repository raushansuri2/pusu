<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;
//use Cake\ORM\RulesChecker;
 
class PostsTable extends Table
{
    public function initialize(array $config): void
    {
        
		$this->belongsTo('Users', [
            'className' => 'Users',
            //'bindingKey' => ['clientId'],
			'foreignKey' => ['userId']
        ]);
        
		$this->belongsTo('Categories', [
            'className' => 'Categories',
            //'bindingKey' => ['clientId'],
			'foreignKey' => ['categoryId']
        ]);
    }
    
    public function validationCustomer(Validator $validator)
    {
       
		$validator->notEmpty('categoryId', 'Please select category.');
		$validator->notEmpty('postTitle', 'Please enter post title.');
		$validator->notEmpty('description', 'Please enter description.');
        $validator->add('image_1', [      
				'image_1' => [                    
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
