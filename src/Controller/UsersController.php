<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Routing\Router;
use Cake\I18n\DateTime;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\MethodNotAllowedException;
use Psr\Http\Message\UploadedFileInterface;
use Cake\Http\Cookie\Cookie;
use Cake\Database\Expression\QueryExpression;


class UsersController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
    }

    public function registration()
    {
        $this->viewBuilder()->setLayout('pages');
        $layoutTitle = 'ERISAQuote Pro. - Signup';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'SIGN UP',
            'URL' => [
                'Home' => $url,
                'Sign up' => ''
            ]
        ];

        // Check if user is already logged in
        if ($this->request->getSession()->read('ERISAQuote Pro.Users.role') === 'Member') {
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }

        $countriesTable = $this->fetchTable('Countries');
        $countryList = $this->fetchTable('Countries')
            ->find('list', keyField: 'id', valueField: 'name')
            ->where(['status' => 1])
            ->order(['name' => 'ASC'])
            ->toArray();

        if ($this->request->is('post'))
        {
            $usersTable = $this->fetchTable('Users');
            $connection = ConnectionManager::get('default');
            $connection->begin();

            try {
                $userByEmail = $usersTable->find()
                    ->where(['Users.email' => $this->request->getData('email')])
                    ->first();

                $userByContactNumber = $usersTable->find()
                    ->where(['Users.contactNumber' => $this->request->getData('contactNumber')])
                    ->first();

                if ($userByEmail) {
                    $this->Flash->error(__('Your email address is already registered.'));
                    return $this->redirect(['controller' => 'Users', 'action' => 'signup']);
                }

                if ($userByContactNumber) {
                    $this->Flash->error(__('Your phone is already registered.'));
                    return $this->redirect(['controller' => 'Users', 'action' => 'signup']);
                }

                $userEntity = $usersTable->newEmptyEntity();
                $userEntity = $usersTable->patchEntity($userEntity, $this->request->getData(), ['validate' => false]);
                $userEntity->username = $this->request->getData('email');
                $userEntity->dob = $this->formatDateString($this->request->getData('dob'));
                $userEntity->status = '0';
                $userEntity->role = 'Member';
                $userEntity->device = 'WEB';

                $verificationToken = $this->random_password(50);
                $userEntity->verification_token = $verificationToken;

                $sessionToken = $this->random_password(50);
                $this->request->getSession()->write('session_token', $sessionToken);

                if ($usersTable->save($userEntity)) {
                    $to = $this->request->getData('email');
                    $subject = "Welcome to ERISAQuote Pro.! Please verify your email.";
                    $verificationLink = Router::url([
                        'controller' => 'Users',
                        'action' => 'verifyEmail',
                        $verificationToken,
                        $sessionToken
                    ], true);

                    $message = "Dear " . ucfirst($this->request->getData('firstName')) . " " .
                        ucfirst($this->request->getData('lastName')) . ",";
                    $message .= "<br>Welcome to ERISAQuote Pro.! We're delighted to have you as a new member of our platform.";
                    $message .= "<br>Please verify your email by clicking the link below:";
                    $message .= "<br><br><a href='" . $verificationLink . "' style='display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;'>Verify Email</a>";
                    $message .= "<br><br><strong>Note:</strong> Please open this link in the same browser to ensure proper verification.";

                    $this->phpemail($to, $subject, $message);

                    $connection->commit();
                    $this->Flash->success(__('You registered successfully. Please check your email to verify your account.'));
                    return $this->redirect(['controller' => 'Users', 'action' => 'login']);
                }

                throw new \Exception('Unable to register. Please try again.');
            } catch (\Exception $e) {
                $connection->rollback();
                $this->Flash->error(__('An error occurred while signing up. Please try again. ' . $e->getMessage()));
            }
        }

        $this->set(compact('layoutTitle', 'breadcum', 'countryList'));
    }

    public function login()
    {
        $this->viewBuilder()->setLayout('home');
        $layoutTitle = 'ERISAQuote Pro - Sign In';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'SIGN IN',
            'URL' => [
                'Home' => $url,
                'Sign In' => $url . 'users/login'
            ]
        ];

        // Check if user is already logged in
        $session = $this->request->getSession();
        if ($session->read('ERISAQuoteProSession.Users.role') === 'Member') {
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }

        if ($this->request->is('post')) {
            $usersTable = $this->fetchTable('Users');
            $user = $usersTable->find()
                ->where(['Users.email' => $this->request->getData('email')])
                ->first();

            if ($user) {
                $passwordHasher = new DefaultPasswordHasher();
                if ($passwordHasher->check($this->request->getData('password'), $user->password)) {
                    if ($user->status == 1) {
                        $session->renew();
                        $session->write('ERISAQuoteProSession.Users', $user);

                        if ($this->request->getData('keep_signed_in')) {
                            $this->response = $this->response->withCookie(new Cookie(
                                'user_id', // name
                                $user->id, // value
                                new \DateTime('+30 days'), // expires
                                '/', // path
                                true, // secure
                                true  // httpOnly
                            ));
                        }

                        $this->Flash->success(__('You have been logged in successfully.'));
                        return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
                    } else {
                        $resendLink = Router::url(['controller' => 'Users', 'action' => 'resendVerificationEmail'], true);
                        $this->Flash->error(__('Your email address is not verified. Please check your email for the verification link or <a href="' . $resendLink . '">click here to resend the verification email</a>.'));
                    }
                } else {
                    $this->Flash->error(__('Incorrect Password.'));
                }
            } else {
                $this->Flash->error(__('Incorrect Email Address.'));
            }
        }

        $this->set(compact('layoutTitle', 'breadcum'));
        return null; // Explicit return for non-redirect cases
    }


    public function logout()
    {
        $session = $this->request->getSession();
        if (empty($session->read('ERISAQuoteProSession.Users.id'))) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $usersTable = $this->fetchTable('Users');
        $userId = $session->read('ERISAQuoteProSession.Users.id');

       

        // Clear cookies
        // $this->response = $this->response
        //     ->withExpiredCookie(new Cookie('user_id', '', new \DateTime('now -1 day'))) // Set to expire 1 day ago
        //     ->withExpiredCookie(new Cookie('timezone', '', new \DateTime('now -1 day'))); // Set to expire 1 day ago

        // Clear session data
        $session->delete('ERISAQuoteProSession.Users');
        //$session->delete('Config.timezone');

        $this->Flash->success(__('You have been logged out.'));
        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }

    public function editprofile()
    {
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'ERISAQuote Pro - My account';
        $url = \Cake\Routing\Router::url('/', true);
        $breadcum = [
            'Title' => 'DASHBOARD',
            'URL' => [
                'Home' => $url,
                'DASHBOARD' => $url . 'users/dashboard'
            ]
        ];
        $session = $this->request->getSession();
        if ($session->read('ERISAQuoteProSession.Users.role') != 'Member') {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }

        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->find()
            ->where(['Users.id' => $session->read('ERISAQuoteProSession.Users.id')])
            ->first();

        if(empty($user)){
            $this->Flash->error(__('You have not permission to access.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        if ($this->request->is('post')) {

            $user = $usersTable->patchEntity(
                $user,
                $this->request->getData(),
                ['validate' => false]
            );

            if ($usersTable->save($user)) {
                $session->renew();
                $session->write('ERISAQuoteProSession.Users', $user);
                $this->Flash->success(__('Profile updated successfully.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'editprofile']);
            } else {
                $this->Flash->error(__('Please correct the errors below.'));
            }
        }
        $this->set(compact('layoutTitle', 'breadcum', 'user'));
    }

    public function changepassword()
    {
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'ERISAQuote Pro. - Changepassword';
       
        $session = $this->request->getSession();
        if ($session->read('ERISAQuoteProSession.Users.role') != 'Member') {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }

        $usersTable = $this->fetchTable('Users');
        $users = $usersTable->find()
            ->where(['Users.id' => $session->read('ERISAQuoteProSession.Users.id')])
            ->first();

        if(empty($users)){
            $this->Flash->error(__('You have not permission to access.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        if ($this->request->is('post')) {
            if( $this->request->getData('newPassword') !=  $this->request->getData('confirmNewPassword')){
                $this->Flash->error(__('New password and confirm password not match.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'changepassword']);
            }
            $passwordHasher = new DefaultPasswordHasher();
			$check = $passwordHasher->check($this->request->getData('oldPassword'), $users->password);
			if($check){
				$users = $usersTable->patchEntity($users, ['password'=> $this->request->getData('newPassword')],['validate' => false]);
				$usersTable->save($users);

                $this->Flash->success(__('password changed successfully.'));
            } else {
                $this->Flash->error(__('Old password not match.'));
            }
        }
        
    }

	public function dashboard()
    {
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'ERISAQuote Pro. - Dashboard';
        $url = \Cake\Routing\Router::url('/', true);
        $breadcum = [
            'Title' => 'DASHBOARD',
            'URL' => [
                'Home' => $url,
                'DASHBOARD' => $url . 'users/dashboard'
            ]
        ];
        
        //session check for login
        $session = $this->request->getSession();
        if ($session->read('ERISAQuoteProSession.Users.role') != 'Member') {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        


        //return null; // Explicit return for non-redirect cases
    }

    public function addquotingRequest()
    {
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'ERISAQuote Pro. - Dashboard';
        
        if($this->request->getQuery('programid') == ""){
            $this->Flash->error(__('Please select a program first.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'programChoose']);
        }
        //session check for login
        $session = $this->request->getSession();
        if ($session->read('ERISAQuoteProSession.Users.role') != 'Member') {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        $censusTable = $this->fetchTable('Census');
        $groupsTable = $this->fetchTable('Quotgroups');
        $group_list = $groupsTable->find('list',['id','group_name'])->where(['Quotgroups.user_id'=>$session->read('ERISAQuoteProSession.Users.id'),'Quotgroups.status'=>1])->toArray();

        $networksTable = $this->fetchTable('NetworksRepricing');
        $network_list = $networksTable->find('list',['id','name'])->where(['NetworksRepricing.status'=>1])->toArray();
        
        $lossPlansTable = $this->fetchTable('LossPlans');
        $loss_plans_list = $lossPlansTable->find()->where(['LossPlans.status'=>1])->toArray();
        
        $benifitPlansTable = $this->fetchTable('BenifitPlans');
        $benifit_plans_list = $benifitPlansTable->find()->where(['BenifitPlans.status'=>1])->toArray();
        
        //pr($benifit_plans_list); die;
        // if ($this->request->is('post')) {
        //     pr($this->request->getData());
        //     die;
            
        // }
        $RequestQuotsTable = $this->fetchTable('RequestQuots');
        $RequestQuots = $RequestQuotsTable->newEmptyEntity();

        if ($this->request->is('post')) {
            // pr($this->request->getData());
            // die;

            $Requesy_D = $this->request->getData();
            $Requesy_D['user_id'] = $session->read('ERISAQuoteProSession.Users.id');
            $Requesy_D['status'] = 1;
            $Requesy_D['networking_id'] = ($this->request->getData('quote_request_networks')) ? implode(',', $this->request->getData('quote_request_networks')) : null;
            $Requesy_D['loss_plan'] = ($this->request->getData('loose')) ? implode(',', $this->request->getData('loose')) : null;
            $Requesy_D['benifit_plan'] = ($this->request->getData('benifit_plans')) ? implode(',', $this->request->getData('benifit_plans')) : null;
            $Requesy_D['program_id'] = $this->request->getQuery('programid');
            $RequestQuots = $RequestQuotsTable->patchEntity(
                $RequestQuots,
                $Requesy_D,
                ['validate' => false]
            );

            if ($RequestQuotsTable->save($RequestQuots)) {
                
                $file = $this->request->getData('census_file');
                if ($file && $file->getError() === UPLOAD_ERR_OK) {
                    $filename = time().'_'.$file->getClientFilename();
                    $targetPath = WWW_ROOT . 'img/uploads/census/' . $filename;

                    $census_data = [
                        'request_id'=> $RequestQuots->id,
                        'user_id' => $session->read('ERISAQuoteProSession.Users.id'),
                        'xl_file' => $filename
                    ];
                    $census = $censusTable->newEmptyEntity();
                    $census = $censusTable->patchEntity(
                        $census,
                        $census_data,
                        ['validate' => false]);
                    $censusTable->save($census);
                    $file->moveTo($targetPath);
                }

                $this->Flash->success(__('Quote requested successfully.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'quotingRequest']);

            } else {

                $this->Flash->error(__('Please correct the errors below.'));
            }
        }

        $this->set(compact('layoutTitle','group_list','network_list','loss_plans_list','benifit_plans_list'));
        //return null; // Explicit return for non-redirect cases
    }

    public function quotingRequest()
    {
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'ERISAQuote Pro. - Dashboard';
       
        //session check for login
        $session = $this->request->getSession();
        if ($session->read('ERISAQuoteProSession.Users.role') != 'Member') {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        


        //return null; // Explicit return for non-redirect cases
    }

    public function quotingDetail()
    {
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'ERISAQuote Pro. - Quoting Details';
        //die('dd');
        //session check for login
        $session = $this->request->getSession();
        if ($session->read('ERISAQuoteProSession.Users.role') != 'Member') {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        


        //return null; // Explicit return for non-redirect cases
    }

    public function programChoose()
    {
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'Choose Program - ERISAQuote Pro';
        
        //session check for login
        $session = $this->request->getSession();
        if ($session->read('ERISAQuoteProSession.Users.role') != 'Member') {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        
        $NetworksRepricing = $this->fetchTable(alias: 'NetworksRepricing');
        $BenifitPlans = $this->fetchTable(alias: 'BenifitPlans');

        $programTable = $this->fetchTable(alias: 'Programs');
        $program_list = $programTable->find()->where(['Programs.status'=>1])->toArray();
        foreach($program_list as $program){
            $count = $NetworksRepricing->find()->where(["FIND_IN_SET($program->id, program_id)"])->count();
            $program->networks = $count;

            $benifit_plans_count = $BenifitPlans->find()->where(["FIND_IN_SET($program->id, program_id)"])->count();
            $program->benifit_plans = $benifit_plans_count;
        }
        //pr($program_list); die;

        $this->set(compact('layoutTitle', 'program_list'));
    }


    public function group()
    {
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'ERISAQuote Pro. - Quoting Details';

        $session = $this->request->getSession();
        if ($session->read('ERISAQuoteProSession.Users.role') != 'Member') {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        // Get the table
        $groupsTable = $this->fetchTable('Quotgroups');
        $group_data = $groupsTable->find()->where(['Quotgroups.user_id'=>$session->read('ERISAQuoteProSession.Users.id'),'Quotgroups.status'=>1]);
        // Use the built-in paginate() method
        $groups = $this->paginate($group_data, [
            'limit' => 10, // items per page
            'order' => ['Quotgroups.id' => 'desc']
        ]);

        // Pass to view
        $this->set(compact('layoutTitle', 'groups'));
    }

    public function groupAdd()
    { 
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'ERISAQuote Pro. - Add New Group';
        $url = \Cake\Routing\Router::url('/', true);
        $breadcum = [
            'Title' => 'ADD NEW GROUP',
            'URL' => [
                'Home' => $url,
                'Groups' => $url . 'users/group'
            ]
        ];

        $session = $this->request->getSession();
        if ($session->read('ERISAQuoteProSession.Users.role') != 'Member') {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        $groupsTable = $this->fetchTable('Quotgroups');
        $group = $groupsTable->newEmptyEntity();

        if ($this->request->is('post')) {
            $group_D = $this->request->getData();
            $group_D['user_id'] = $session->read('ERISAQuoteProSession.Users.id');
            $group = $groupsTable->patchEntity(
                $group,
                $group_D,
                ['validate' => false]
            );

            if ($groupsTable->save($group)) {

                $this->Flash->success(__('Group added successfully.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'group']);

            } else {

                $this->Flash->error(__('Please correct the errors below.'));
            }
        }

        $this->set(compact('layoutTitle', 'breadcum', 'group'));
    }

    public function groupedit($id)
    {
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'Edit Group - ERISAQuote Pro';
        $url = \Cake\Routing\Router::url('/', true);
        $breadcum = [
            'Title' => 'ADD NEW GROUP',
            'URL' => [
                'Home' => $url,
                'Groups' => $url . 'users/group'
            ]
        ];

        $session = $this->request->getSession();
        if ($session->read('ERISAQuoteProSession.Users.role') != 'Member') {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        $groupsTable = $this->fetchTable('Quotgroups');
        $group = $groupsTable->find()->where(['Quotgroups.user_id'=>$session->read('ERISAQuoteProSession.Users.id'),'Quotgroups.id'=>$id])->first();
        if(empty($group)){
            return $this->redirect(['controller' => 'Users', 'action' => 'group']);
        }
        if ($this->request->is('post')) {
            $group_D = $this->request->getData();
            $group_D['user_id'] = $session->read('ERISAQuoteProSession.Users.id');
            $group = $groupsTable->patchEntity(
                $group,
                $group_D,
                ['validate' => false]
            );

            if ($groupsTable->save($group)) {

                $this->Flash->success(__('Group updated successfully.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'group']);

            } else {

                $this->Flash->error(__('Please correct the errors below.'));
            }
        }

        $this->set(compact('layoutTitle', 'breadcum', 'group'));
    }
    

    public function groupDetails($id)
    {
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'ERISAQuote Pro. - Quoting Details';
        if($id == ""){
           return $this->redirect(['controller' => 'Users', 'action' => 'group']);
        }
        //session check for login
        $session = $this->request->getSession();
        if ($session->read('ERISAQuoteProSession.Users.role') != 'Member') {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }

        $groupsTable = $this->fetchTable('Quotgroups');
        $group_data = $groupsTable->find()->where(['Quotgroups.id'=>$id])->first();
        $this->set(compact('layoutTitle', 'group_data'));
    
    }



}
