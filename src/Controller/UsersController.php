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

require_once WWW_ROOT . 'simplexlsx-master/src/SimpleXLSX.php';
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

        $userId = $session->read('ERISAQuoteProSession.Users.id');

        // Get statistics for dashboard cards
        $requestQuotsTable = $this->fetchTable('RequestQuots');

        // Instant Quotes Available - requests with status indicating quotes are ready
        $instantQuotesCount = $requestQuotsTable->find()
            ->where([
                'RequestQuots.user_id' => $userId,
                'RequestQuots.status IN' => [1, 6, 7] // Assuming 2=Illustrative Quote Ready, 3=Final Quote Ready, 4=Quote Ready
            ])
            ->count();

        // Pending Decision - requests waiting for group decision
        $pendingDecisionCount = $requestQuotsTable->find()
            ->where([
                'RequestQuots.user_id' => $userId,
                'RequestQuots.status' => 2 // 2=pending Decison
            ])
            ->count();

        // Sold - requests marked as sold
        $soldCount = $requestQuotsTable->find()
            ->where([
                'RequestQuots.user_id' => $userId,
                'RequestQuots.status' => 3 // 3=Sold
            ])
            ->count();

        // Lost - requests marked as lost
        $lostCount = $requestQuotsTable->find()
            ->where([
                'RequestQuots.user_id' => $userId,
                'RequestQuots.status' => 4 // 4=Lost
            ])
            ->count();

        // Get recent groups with activity (last 3 groups)
        $recentGroups = $requestQuotsTable->find()
            ->select(['Quotgroups.id', 'Quotgroups.group_name', 'Quotgroups.city', 'Quotgroups.state_name'])
            ->distinct(['Quotgroups.id'])
            ->contain(['Quotgroups'])
            ->where(['RequestQuots.user_id' => $userId])
            ->order(['RequestQuots.id' => 'DESC'])
            ->limit(3)
            ->toArray();

        // Get quote requests for each recent group
        $groupsWithRequests = [];
        foreach ($recentGroups as $groupData) {
            if (!empty($groupData->quotgroup)) {
                $groupId = $groupData->quotgroup->id;

                $quoteRequests = $requestQuotsTable->find()
                    ->where([
                        'RequestQuots.user_id' => $userId,
                        'RequestQuots.group_id' => $groupId
                    ])
                    ->order(['RequestQuots.id' => 'DESC'])
                    ->limit(5)
                    ->toArray();

                $groupsWithRequests[] = [
                    'group' => $groupData->quotgroup,
                    'requests' => $quoteRequests
                ];
            }
        }

        $this->set(compact(
            'layoutTitle',
            'breadcum',
            'instantQuotesCount',
            'pendingDecisionCount',
            'soldCount',
            'lostCount',
            'groupsWithRequests'
        ));
    }


    public function editquotingRequest($id=null)
    {
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'Edit Request-ERISAQuote Pro';

        //session check for login
        $session = $this->request->getSession();
        if ($session->read('ERISAQuoteProSession.Users.role') != 'Member') {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }

        $RequestQuotsTable = $this->fetchTable('RequestQuots');
        $RequestQuots = $RequestQuotsTable->find()->where(['RequestQuots.id'=>$id])->first();

        if (empty($RequestQuots)) {
            $this->Flash->error(__('Quote request not found.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'quotingRequest']);
        }

        // Check if user owns this request
        if ($RequestQuots->user_id != $session->read('ERISAQuoteProSession.Users.id')) {
            $this->Flash->error(__('You do not have permission to edit this request.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'quotingRequest']);
        }

        $programID = $RequestQuots->program_id;
        $censusTable = $this->fetchTable('Census');
        $groupsTable = $this->fetchTable('Quotgroups');
        $group_list = $groupsTable->find('list',['id','group_name'])->where(['Quotgroups.user_id'=>$session->read('ERISAQuoteProSession.Users.id'),'Quotgroups.status'=>1])->toArray();

        $networksTable = $this->fetchTable('NetworksRepricing');
        $network_list = $networksTable->find('list',['id','name'])->where(['NetworksRepricing.status'=>1, 'FIND_IN_SET('.$programID.', NetworksRepricing.program_id)'])->toArray();

        $lossPlansTable = $this->fetchTable('LoosePlans');
        $loss_plans_list = $lossPlansTable->find()->where(['LoosePlans.status'=>1, 'FIND_IN_SET('.$programID.', LoosePlans.program_id)'])->toArray();

        $benifitPlansTable = $this->fetchTable('BenifitPlans');
        $benifit_plans_list = $benifitPlansTable->find()->where(['BenifitPlans.status'=>1, 'FIND_IN_SET('.$programID.', BenifitPlans.program_id)'])->toArray();

        $feesTable = $this->fetchTable('Fees');
        $fees_list = $feesTable->find()->where(['Fees.status'=>1, 'FIND_IN_SET('.$programID.', Fees.program_id)'])->toArray();

        // Get fees data from database
        $feesData = [];
        if (!empty($RequestQuots->Broke_Fee)) {
            $feesData = json_decode($RequestQuots->Broke_Fee, true);
        }

        // Get existing census data
        $existingCensus = $censusTable->find()->where(['Census.request_id' => $id])->first();

        if ($this->request->is('post')) {
            $filename2 = "";
            $Requesy_D = $this->request->getData();

            // Validate census file - it cannot be blank for new requests
            $censusFile = $this->request->getData('census_file');
            $attach_file = $this->request->getData('attach_file');
            if (empty($existingCensus) && (!$censusFile || $censusFile->getError() !== UPLOAD_ERR_OK)) {
                $this->Flash->error(__('Census file is required and cannot be blank.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'editquotingRequest', $id]);
            }

            $Requesy_D['user_id'] = $session->read('ERISAQuoteProSession.Users.id');
            $Requesy_D['status'] = 1;
            $Requesy_D['networking_id'] = ($this->request->getData('quote_request_networks')) ? implode(',', $this->request->getData('quote_request_networks')) : null;
            $Requesy_D['loss_plan'] = ($this->request->getData('loose')) ? implode(',', $this->request->getData('loose')) : null;
            $Requesy_D['benifit_plan'] = ($this->request->getData('benifit_plans')) ? implode(',', $this->request->getData('benifit_plans')) : null;
            $Requesy_D['program_id'] = $RequestQuots->program_id; // Keep existing program_id
            $Requesy_D['Broke_Fee']= json_encode($this->request->getData('fees'));

            $file2 = $this->request->getData('attach_file');
            if ($file2 && $file2->getError() === UPLOAD_ERR_OK) {
                $filename2 = time().'_'.$file2->getClientFilename();
                $targetPath2 = WWW_ROOT . 'img/uploads/request_quote/' . $filename2;
                $Requesy_D['group_upload'] = $filename2;
            }

            // Patch the existing entity instead of creating new one
            $RequestQuots = $RequestQuotsTable->patchEntity(
                $RequestQuots,
                $Requesy_D,
                ['validate' => false]
            );

            if ($RequestQuots = $RequestQuotsTable->save($RequestQuots)) {

                // Handle census file upload
                if ($censusFile && $censusFile->getError() === UPLOAD_ERR_OK) {
                    $filename = time().'_'.$censusFile->getClientFilename();
                    $targetPath = WWW_ROOT . 'img/uploads/census/' . $filename;

                    if ($existingCensus) {
                        // Update existing census record
                        $existingCensus = $censusTable->patchEntity(
                            $existingCensus,
                            ['xl_file' => $filename],
                            ['validate' => false]
                        );
                        $censusTable->save($existingCensus);
                    } else {
                        // Create new census record
                        $census_data = [
                            'request_id'=> $RequestQuots->id,
                            'user_id' => $session->read('ERISAQuoteProSession.Users.id'),
                            'xl_file' => $filename
                        ];
                        $census = $censusTable->newEmptyEntity();
                        $census = $censusTable->patchEntity(
                            $census,
                            $census_data,
                            ['validate' => false]
                        );
                        $censusTable->save($census);
                    }
                    $censusFile->moveTo($targetPath);
                }

                // Upload attachment file
                if ($attach_file && $attach_file->getError() === UPLOAD_ERR_OK) {
                    $filename = time().'_'.$attach_file->getClientFilename();
                    $targetPath = WWW_ROOT . 'img/uploads/census/' . $filename;


                    if ($existingCensus) {
                        // Update existing census record
                        $existingCensus = $censusTable->patchEntity(
                            $existingCensus,
                            ['xl_file' => $filename],
                            ['validate' => false]
                        );
                        $censusTable->save($existingCensus);
                    } else {
                        // Create new census record
                        $census_data = [
                            'request_id'=> $RequestQuots->id,
                            'user_id' => $session->read('ERISAQuoteProSession.Users.id'),
                            'xl_file' => $filename
                        ];
                        $census = $censusTable->newEmptyEntity();
                        $census = $censusTable->patchEntity(
                            $census,
                            $census_data,
                            ['validate' => false]
                        );
                        $censusTable->save($census);
                    }
                    $attach_file->moveTo($targetPath);
                }

                $this->Flash->success(__('Quote request updated successfully.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'quotingRequest']);

            } else {
                $this->Flash->error(__('Please correct the errors below.'));
            }
        }

        $this->set(compact('layoutTitle','group_list','network_list','loss_plans_list','benifit_plans_list', 'fees_list', 'RequestQuots', 'existingCensus', 'feesData'));
    }

    public function addquotingRequest()
    {
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'Add Request-ERISAQuote Pro';

        if($this->request->getQuery('programid') == ""){
            $this->Flash->error(__('Please select a program first.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'programChoose']);
        }
        $programID = $this->request->getQuery('programid');
        //session check for login
        $session = $this->request->getSession();
        if ($session->read('ERISAQuoteProSession.Users.role') != 'Member') {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }
        $censusTable = $this->fetchTable('Census');
        $groupsTable = $this->fetchTable('Quotgroups');
        $group_list = $groupsTable->find('list',['id','group_name'])->where(['Quotgroups.user_id'=>$session->read('ERISAQuoteProSession.Users.id'),'Quotgroups.status'=>1])->toArray();

        $networksTable = $this->fetchTable('NetworksRepricing');
        $network_list = $networksTable->find('list',['id','name'])->where(['NetworksRepricing.status'=>1, 'FIND_IN_SET('.$programID.', NetworksRepricing.program_id)'])->toArray();

        $lossPlansTable = $this->fetchTable('LoosePlans');
        $loss_plans_list = $lossPlansTable->find()->where(['LoosePlans.status'=>1, 'FIND_IN_SET('.$programID.', LoosePlans.program_id)'])->toArray();

        $benifitPlansTable = $this->fetchTable('BenifitPlans');
        $benifit_plans_list = $benifitPlansTable->find()->where(['BenifitPlans.status'=>1, 'FIND_IN_SET('.$programID.', BenifitPlans.program_id)'])->toArray();

        $feesTable = $this->fetchTable('Fees');
        $fees_list = $feesTable->find()->where(['Fees.status'=>1, 'FIND_IN_SET('.$programID.', Fees.program_id)'])->toArray();

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
            $filename2 = "";
            $Requesy_D = $this->request->getData();
            $Requesy_D['user_id'] = $session->read('ERISAQuoteProSession.Users.id');
            $Requesy_D['status'] = 1;
            $Requesy_D['networking_id'] = ($this->request->getData('quote_request_networks')) ? implode(',', $this->request->getData('quote_request_networks')) : null;
            $Requesy_D['loss_plan'] = ($this->request->getData('loose')) ? implode(',', $this->request->getData('loose')) : null;
            $Requesy_D['benifit_plan'] = ($this->request->getData('benifit_plans')) ? implode(',', $this->request->getData('benifit_plans')) : null;
            $Requesy_D['program_id'] = $this->request->getQuery('programid');
            $Requesy_D['Broke_Fee']= json_encode($this->request->getData('fees'));


            //pr($Requesy_D); die;
            $RequestQuots = $RequestQuotsTable->patchEntity(
                $RequestQuots,
                $Requesy_D,
                ['validate' => false]
            );

            if ($RequestQuots = $RequestQuotsTable->save($RequestQuots)) {

                $file = $this->request->getData('census_file');
                if ($file && $file->getError() === UPLOAD_ERR_OK) {
                    $filename = time().'_'.$file->getClientFilename();
                    $targetPath = WWW_ROOT . 'img/uploads/census/' . $filename;

                    $census_data = [
                        'request_id'=> $RequestQuots->id,
                        'user_id' => $session->read('ERISAQuoteProSession.Users.id'),
                        'xl_file' => $filename,
                        'type'=> 'Census'
                    ];
                    $census = $censusTable->newEmptyEntity();
                    $census = $censusTable->patchEntity(
                        $census,
                        $census_data,
                        ['validate' => false]);
                    $censusTable->save($census);
                    $file->moveTo($targetPath);
                }

                //upload files
                $file2 = $this->request->getData('attach_file');
                if ($file2 && $file2->getError() === UPLOAD_ERR_OK) {
                    $filename2 = time().'_'.$file2->getClientFilename();
                    $targetPath2 = WWW_ROOT . 'img/uploads/census/' . $filename2;

                     $census_data2 = [
                        'request_id'=> $RequestQuots->id,
                        'user_id' => $session->read('ERISAQuoteProSession.Users.id'),
                        'xl_file' => $filename2,
                        'type'=> 'Attachment'
                    ];
                    $census = $censusTable->newEmptyEntity();
                    $census = $censusTable->patchEntity(
                        $census,
                        $census_data2,
                        ['validate' => false]);
                    $censusTable->save($census);
                    $file2->moveTo($targetPath2);
                }

                $this->Flash->success(__('Quote requested successfully.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'quotingRequest']);

            } else {

                $this->Flash->error(__('Please correct the errors below.'));
            }
        }

        $this->set(compact('layoutTitle','group_list','network_list','loss_plans_list','benifit_plans_list', 'fees_list'));
        //return null; // Explicit return for non-redirect cases
    }

    public function quotingRequest()
    {
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'Request List-ERISAQuote Pro';

        // Session check for login
        $session = $this->request->getSession();

        if ($session->read('ERISAQuoteProSession.Users.role') != 'Member') {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }

        $keyword = $this->request->getQuery('keyword');
        $hideExpired = $this->request->getQuery('hide_expired');
        $status = $this->request->getQuery('status');

        // Initialize conditions
        $conditions = [];

        // User condition
        $conditions['RequestQuots.user_id'] = $session->read('ERISAQuoteProSession.Users.id');

        // Status filter condition - handle both array and single values
        if (!empty($status)) {
            if (is_array($status)) {
                // If status is an array, take the first non-empty value
                $status = reset($status);
            }
            $conditions['RequestQuots.status'] = (int)$status;
        }

        // Search condition
        if (!empty($keyword)) {
            if (isset($conditions['OR'])) {
                $conditions['OR'][] = ['Quotgroups.group_name LIKE' => '%' . trim($keyword) . '%'];
            } else {
                $conditions['OR'] = [
                    ['Quotgroups.group_name LIKE' => '%' . trim($keyword) . '%']
                ];
            }
        }

        // Hide expired condition
        if ($hideExpired != 0 || !isset($hideExpired)) {
            $conditions['RequestQuots.Policy_Effective_Date >='] = date('Y-m-d');
        }
        if ($hideExpired == 1 && isset($hideExpired)) {
            $conditions['RequestQuots.Policy_Effective_Date >='] = date('Y-m-d');
        }

        // Fetch table
        $requestQuotTable = $this->fetchTable('RequestQuots');

        $query = $requestQuotTable->find()
            ->where($conditions)
            ->contain(['Users', 'Quotgroups', 'Programs']);

        // Configure paginator
        $this->paginate = [
            'limit' => 10,
            'order' => ['RequestQuots.id' => 'DESC']
        ];

        // Pagination
        $request_quote_list = $this->paginate($query);

        $this->set(compact('request_quote_list', 'layoutTitle'));
    }

    public function quotingDetail($id=null)
    {
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'ERISAQuote Pro. - Quoting Details';
        //die('dd');
        //session check for login
        $session = $this->request->getSession();
        if ($session->read('ERISAQuoteProSession.Users.role') != 'Member') {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }

        $RequestQuotsTable = $this->fetchTable('RequestQuots');
        $RequestQuots = $RequestQuotsTable->find()->where(['RequestQuots.id'=>$id])->contain(['Users','Quotgroups','Programs'])->first();

        // Get census data for this request
        $censusTable = $this->fetchTable('Census');
        $censusData = $censusTable->find()->where(['Census.request_id' => $id])->toArray();

        // Get network details
        $networksDetails = [];
        if (!empty($RequestQuots->networking_id)) {
            $networksTable = $this->fetchTable('NetworksRepricing');
            $networkIds = explode(',', $RequestQuots->networking_id);
            $networksDetails = $networksTable->find()->where(['NetworksRepricing.id IN' => $networkIds])->toArray();
        }

        // Get loss plan details
        $lossPlansDetails = [];
        if (!empty($RequestQuots->loss_plan)) {
            $lossPlansTable = $this->fetchTable('LoosePlans');
            $lossPlanIds = explode(',', $RequestQuots->loss_plan);
            $lossPlansDetails = $lossPlansTable->find()->where(['LoosePlans.id IN' => $lossPlanIds])->toArray();
        }

        // Get benefit plan details
        $benefitPlansDetails = [];
        if (!empty($RequestQuots->benifit_plan)) {
            $benefitPlansTable = $this->fetchTable('BenifitPlans');
            $benefitPlanIds = explode(',', $RequestQuots->benifit_plan);
            $benefitPlansDetails = $benefitPlansTable->find()->where(['BenifitPlans.id IN' => $benefitPlanIds])->toArray();
        }

        // Get fees data
        $feesData = [];
        if (!empty($RequestQuots->Broke_Fee)) {
            $feesData = json_decode($RequestQuots->Broke_Fee, true);
        }
        // Get timeline data from request_status table
        $RequestStatusTable = $this->fetchTable('RequestStatus');
        $timelineData = $RequestStatusTable->find()
            ->where(['RequestStatus.request_id' => $id])
            ->contain(['Users'])
            ->order(['RequestStatus.created' => 'DESC'])
            ->toArray();

        $file_name = '';
        if(!empty($censusData)){
            foreach($censusData as $census) {
                if($census->type == 'Census') {
                    $file_name = $census->xl_file;
                }
            }
        }

        $filePath = WWW_ROOT . 'img/uploads/census/'.$file_name; // your Excel file path
        $file_counts = [];

        // Only parse the file if it exists and $file_name is not empty
        if (!empty($file_name) && file_exists($filePath)) {
            if ($xlsx = \Shuchkin\SimpleXLSX::parse($filePath)) {
                $rows = $xlsx->rows();

                foreach ($rows as $i => $row) {
                    if ($i < 4) continue; // skip header

                    $val = $row[7] ?? null; // column G (index 6)
                    if ($val) {
                        if (!isset($file_counts[$val])) {
                            $file_counts[$val] = 0;
                        }
                        $file_counts[$val]++;
                    }
                }
            }
        }
        //pr($file_counts); die;

        $this->set(compact('file_name','file_counts','RequestQuots', 'layoutTitle', 'censusData', 'networksDetails', 'lossPlansDetails', 'benefitPlansDetails', 'feesData', 'timelineData'));
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
        $layoutTitle = 'ERISAQuote Pro - Group Details';

        if($id == ""){
           return $this->redirect(['controller' => 'Users', 'action' => 'group']);
        }

        //session check for login
        $session = $this->request->getSession();
        if ($session->read('ERISAQuoteProSession.Users.role') != 'Member') {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }

        // Get group details
        $groupsTable = $this->fetchTable('Quotgroups');
        $group = $groupsTable->find()
            ->where(['Quotgroups.id' => $id, 'Quotgroups.user_id' => $session->read('ERISAQuoteProSession.Users.id')])
            ->first();

        if (empty($group)) {
            $this->Flash->error(__('Group not found.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'group']);
        }

        // Get quote requests for this group
        $requestQuotsTable = $this->fetchTable('RequestQuots');
        $quoteRequests = $requestQuotsTable->find()
            ->where(['RequestQuots.group_id' => $id])
            ->contain(['Users', 'Quotgroups', 'Programs'])
            ->order(['RequestQuots.id' => 'DESC'])
            ->toArray();

        // Get census data for all quote requests in this group
        $censusTable = $this->fetchTable('Census');
        $censusData = [];

        if (!empty($quoteRequests)) {
            $quoteRequestIds = array_column($quoteRequests, 'id');
            $censusData = $censusTable->find()
                ->where(['Census.request_id IN' => $quoteRequestIds])
                ->order(['Census.request_id' => 'DESC', 'Census.id' => 'DESC'])
                ->toArray();
        }

        $this->set(compact('layoutTitle', 'group', 'quoteRequests', 'censusData'));
    }


    public function updateStatus($id = null, $status = null){
        // Validate parameters
        if ($id === null || $status === null) {
            $this->Flash->error(__('Invalid request parameters.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'quotingRequest']);
        }

        $session = $this->getRequest()->getSession();

        // Check if user is logged in
        if (!$session->check('ERISAQuoteProSession.Users.id')) {
            $this->Flash->error(__('Please login to continue.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Get the request quote
        $RequestQuotsTable = $this->fetchTable('RequestQuots');
        $RequestQuots = $RequestQuotsTable->find()->where(['RequestQuots.id'=>$id])->first();

        if (empty($RequestQuots)) {
            $this->Flash->error(__('Quote request not found.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'quotingRequest']);
        }

        // Check if user owns this request
        if ($RequestQuots->user_id != $session->read('ERISAQuoteProSession.Users.id')) {
            $this->Flash->error(__('You do not have permission to update this request.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'quotingRequest']);
        }

        // Update the status in request_quots table
        $RequestQuots = $RequestQuotsTable->patchEntity($RequestQuots, ['status' => $status]);

        if ($RequestQuotsTable->save($RequestQuots)) {
            // Add new record to request_status table
            try {
                $RequestStatusTable = $this->fetchTable('RequestStatus');
                $requestStatus = $RequestStatusTable->newEmptyEntity();

                $statusData = [
                    'request_id' => $id,
                    'user_id' => $session->read('ERISAQuoteProSession.Users.id'),
                    'status' => (int)$status,
                    'message' => 'Status updated to ' . $status
                ];

                $requestStatus = $RequestStatusTable->patchEntity($requestStatus, $statusData, ['validate' => false]);

                if ($RequestStatusTable->save($requestStatus)) {
                    //$this->Flash->success(__('Status updated successfully.'));
                } else {
                    // Debug: Log the errors
                    $errors = $requestStatus->getErrors();
                    $this->Flash->error(__('Failed to save status history. Errors: ' . json_encode($errors)));
                }
            } catch (\Exception $e) {
                $this->Flash->error(__('Database error: ' . $e->getMessage()));
            }
        } else {
            $this->Flash->error(__('Failed to update status.'));
        }

        return $this->redirect(['controller' => 'Users', 'action' => 'quotingDetail', $id]);
    }


}
