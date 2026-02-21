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

        // // Check cookie-based login
        // if ($this->request->getCookie('user_id')) {
        //     $usersTable = $this->fetchTable('Users');
        //     try {
        //         $user = $usersTable->get(primaryKey: $this->request->getCookie('user_id'));

        //         $session->renew();
        //         $session->write('ERISAQuoteProSession.Users', $user);
        //         return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        //     } catch (RecordNotFoundException $e) {
        //         // Invalid cookie, proceed to manual login
        //     }
        // }

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
        if (empty($session->read('ERISAQuote Pro.Users.id'))) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $usersTable = $this->fetchTable('Users');
        $userId = $session->read('ERISAQuote Pro.Users.id');

        // Update last login time with error handling
        try {
            $user = $usersTable->get($userId);
            $user->lastLogin = DateTime::now()->i18nFormat('yyyy-MM-dd HH:mm:ss');
            if (!$usersTable->save($user)) {
                // Log the failure if needed, but don’t interrupt logout
            }
        } catch (RecordNotFoundException $e) {
            // If user not found, proceed with logout anyway
        }

        // Clear cookies
        $this->response = $this->response
            ->withExpiredCookie(new Cookie('user_id', '', new \DateTime('now -1 day'))) // Set to expire 1 day ago
            ->withExpiredCookie(new Cookie('timezone', '', new \DateTime('now -1 day'))); // Set to expire 1 day ago

        // Clear session data
        $session->delete('ERISAQuote Pro.Users');
        $session->delete('Config.timezone');

        $this->Flash->success(__('You have been logged out.'));
        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }

    public function editprofile()
    {
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'ERISAQuote Pro. - My account';
        $url = \Cake\Routing\Router::url('/', true);
        $breadcum = [
            'Title' => 'DASHBOARD',
            'URL' => [
                'Home' => $url,
                'DASHBOARD' => $url . 'users/dashboard'
            ]
        ];
        //die('dd');
        //$session = $this->request->getSession();
        //$userId = $session->read('ERISAQuote Pro.Users.id');


        //return null; // Explicit return for non-redirect cases
    }

    public function changepassword()
    {
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'ERISAQuote Pro. - Changepassword';
        $url = \Cake\Routing\Router::url('/', true);
        $breadcum = [
            'Title' => 'DASHBOARD',
            'URL' => [
                'Home' => $url,
                'DASHBOARD' => $url . 'users/dashboard'
            ]
        ];
        //die('dd');
        //$session = $this->request->getSession();
        //$userId = $session->read('ERISAQuote Pro.Users.id');


        //return null; // Explicit return for non-redirect cases
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
        //die('dd');
        //$session = $this->request->getSession();
        //$userId = $session->read('ERISAQuote Pro.Users.id');


        //return null; // Explicit return for non-redirect cases
    }

    public function addquotingRequest()
    {
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'ERISAQuote Pro. - Dashboard';
        //die('dd');
        //$session = $this->request->getSession();
        //$userId = $session->read('ERISAQuote Pro.Users.id');


        //return null; // Explicit return for non-redirect cases
    }

    public function quotingRequest()
    {
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'ERISAQuote Pro. - Dashboard';
        //die('dd');
        //$session = $this->request->getSession();
        //$userId = $session->read('ERISAQuote Pro.Users.id');


        //return null; // Explicit return for non-redirect cases
    }

    public function quotingDetail()
    {
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'ERISAQuote Pro. - Quoting Details';
        //die('dd');
        //$session = $this->request->getSession();
        //$userId = $session->read('ERISAQuote Pro.Users.id');


        //return null; // Explicit return for non-redirect cases
    }

    public function programChoose()
    {
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'ERISAQuote Pro. - Quoting Details';
        //die('dd');
        //$session = $this->request->getSession();
        //$userId = $session->read('ERISAQuote Pro.Users.id');


        //return null; // Explicit return for non-redirect cases
    }


    public function group()
    {
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'ERISAQuote Pro. - Quoting Details';

        // Get the table
        $groupsTable = $this->fetchTable('Quotgroups');
        $group_data = $groupsTable->find()->where(['Quotgroups.status'=>1]);
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

        $groupsTable = $this->fetchTable('Quotgroups');
        $group = $groupsTable->newEmptyEntity();

        if ($this->request->is('post')) {

            $group = $groupsTable->patchEntity(
                $group,
                $this->request->getData(),
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
    

    public function groupDetails($id)
    {
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'ERISAQuote Pro. - Quoting Details';
        if($id == ""){
           return $this->redirect(['controller' => 'Users', 'action' => 'group']);
        }
        $groupsTable = $this->fetchTable('Quotgroups');
        $group_data = $groupsTable->find()->where(['Quotgroups.id'=>$id])->first();
        $this->set(compact('layoutTitle', 'group_data'));
    
    }



}
