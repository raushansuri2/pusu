<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Authentication\PasswordHasher\DefaultPasswordHasher;

class UsersTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');

        // Define relationships
        $this->belongsTo('Countries', [
            'className' => 'App\Model\Table\CountriesTable',
            'foreignKey' => 'countryId',
        ]);

        $this->belongsTo('States', [
            'className' => 'App\Model\Table\StatesTable',
            'foreignKey' => 'stateId',
        ]);

        $this->belongsTo('Cities', [
            'className' => 'App\Model\Table\CitiesTable',
            'foreignKey' => 'cityId',
        ]);

       
    }

    public function validationUser (Validator $validator): Validator
    {
        $validator
            ->notEmptyString('email', 'Please enter your email.')
            ->add('email', 'validFormat', [
                'rule' => 'email',
                'message' => 'Please enter a valid email',
            ])
            ->add('email', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'message' => 'Email already exists',
            ]);

        $validator->notEmptyString('gender', 'Please select a gender.');

        $validator->notEmptyString('firstName', 'Please enter your first name.')
            ->add('firstName', 'minLength', [
                'rule' => ['minLength', 3],
                'message' => 'First name must be at least three characters long.',
            ])
            ->add('firstName', 'maxLength', [
                'rule' => ['maxLength', 50],
                'message' => 'First name exceeds maximum fifty character limit.',
            ]);

        $validator->notEmptyString('password', 'Please enter a password.')
            ->add('password', 'minLength', [
                'rule' => ['minLength', 8],
                'message' => 'Password must be at least eight characters long.',
            ])
            ->add('password', 'maxLength', [
                'rule' => ['maxLength', 25],
                'message' => 'Password exceeds maximum twenty-five character limit.',
            ]);

        $validator->notEmptyString('confirm_password', 'Please re-type your password.')
            ->add('confirm_password', 'minLength', [
                'rule' => ['minLength', 8],
                'message' => 'Confirm password must be at least eight characters long.',
            ])
            ->add('confirm_password', 'maxLength', [
                'rule' => ['maxLength', 25],
                'message' => 'Confirm password exceeds maximum twenty-five character limit.',
            ])
            ->add('confirm_password', [
                'match' => [
                    'rule' => ['compareWith', 'password'],
                    'last' => true,
                    'message' => 'Confirm password does not match with password.',
                ],
            ]);

        $validator->add('profile_picture', [
            'validExtension' => [
                'rule' => function ($value) {
                    $ext = pathinfo($value['name'], PATHINFO_EXTENSION);
                    return in_array(strtolower($ext), ['jpg', 'jpeg', 'png']);
                },
                'message' => 'Please select jpg, jpeg, or png only.',
            ],
        ])->allowEmptyFile('profile_picture');

        return $validator;
    }

    public function validationPassword(Validator $validator): Validator
    {
        $validator
            ->add('old_password', 'custom', [
                'rule' => function ($value, $context) {
                    $user = $this->get($context['data']['id']);
                    return $user && (new DefaultPasswordHasher)->check($value, $user->password);
                },
                'message' => 'The old password is not correct!',
            ])
            ->notEmptyString('old_password');

        $validator
            ->add('password1', [
                'length' => [
                    'rule' => ['minLength', 6],
                    'message' => 'Password should be minimum six characters.',
                ],
            ])
            ->add('password1', [
                'match' => [
                    'rule' => ['compareWith', 'password2'],
                    'message' => 'Password does not match with confirm password.',
                ],
            ])
            ->notEmptyString('password1');

        $validator
            ->add('password2', [
                'length' => [
                    'rule' => ['minLength', 6],
                    'message' => 'Password should be minimum six characters.',
                ],
            ])
            ->add('password2', [
                'match' => [
                    'rule' => ['compareWith', 'password1'],
                    'message' => 'Confirm password does not match with password.',
                ],
            ])
            ->notEmptyString('password2');

        return $validator;
    }

    public function validationEditProfile(Validator $validator): Validator
    {
        $validator->notEmptyString('contactNumber', 'Please enter a contact number.')
            ->add('contactNumber', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'message' => 'Phone number already exists.',
            ]);

        return $validator;
    }
}
