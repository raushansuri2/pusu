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

    public function signup()
    {
        $this->viewBuilder()->setLayout('pages');
        $layoutTitle = 'Ritevet - Signup';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'SIGN UP',
            'URL' => [
                'Home' => $url,
                'Sign up' => ''
            ]
        ];

        // Check if user is already logged in
        if ($this->request->getSession()->read('RitevetUsers.role') === 'Member') {
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
                    $subject = "Welcome to RiteVet! Please verify your email.";
                    $verificationLink = Router::url([
                        'controller' => 'Users',
                        'action' => 'verifyEmail',
                        $verificationToken,
                        $sessionToken
                    ], true);
                    
                    $message = "Dear " . ucfirst($this->request->getData('firstName')) . " " . 
                        ucfirst($this->request->getData('lastName')) . ",";
                    $message .= "<br>Welcome to RiteVet! We're delighted to have you as a new member of our platform.";
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
        $this->viewBuilder()->setLayout('pages');
        $layoutTitle = 'Ritevet - Sign In';
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
        if ($session->read('RitevetUsers.role') === 'Member') {
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }

        // Check cookie-based login
        if ($this->request->getCookie('user_id')) {
            $usersTable = $this->fetchTable('Users');
            try {
                $user = $usersTable->get($this->request->getCookie('user_id'), contain: [
                    'Cities',
                    'States',
                    'Countries',
                    'Usersinformations'
                ]);

                $session->renew();
                $session->write('RitevetUsers', $user);
                return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
            } catch (RecordNotFoundException $e) {
                // Invalid cookie, proceed to manual login
            }
        }

        if ($this->request->is('post')) {
            $usersTable = $this->fetchTable('Users');
            $user = $usersTable->find()
                ->where(['Users.email' => $this->request->getData('email')])
                ->contain(['Cities', 'States', 'Countries', 'Usersinformations'])
                ->first();

            if ($user) {
                $passwordHasher = new DefaultPasswordHasher();
                if ($passwordHasher->check($this->request->getData('password'), $user->password)) {
                    if ($user->status == 1) {
                        $session->renew();
                        $session->write('RitevetUsers', $user);

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

                        // Update latitude/longitude based on IP
                        $clientIP = $this->request->clientIp();
                        $apiUrl = "http://ip-api.com/json/{$clientIP}";
                        $response = file_get_contents($apiUrl);
                        if ($response !== false) {
                            $data = json_decode($response, true);
                            if ($data && $data['status'] === 'success') {
                                $usersTable->updateAll(
                                    ['latitude' => $data['lat'], 'longitude' => $data['lon']],
                                    ['id' => $user->id]
                                );
                            }
                        }

                        // Handle timezone
                        if ($timezone = $this->request->getData('timezone')) {
                            $session->write('Config.timezone', $timezone);
                            
                            // Create a new cookie with positional parameters
                            $this->response = $this->response->withCookie(new Cookie(
                                'timezone', // name
                                $timezone,  // value
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
    
    public function forgotpassword() 
    {
        $this->viewBuilder()->setLayout('pages');
        $layoutTitle = 'Ritevet - Forgot Password';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'Forgot Password',
            'URL' => [
                'Home' => $url,
                'Sign In' => $url . 'users/login',
                'Forgot Password' => $url . '/users/forgotpassword'
            ]
        ];
            
        // Redirect if user is already logged in
        if ($this->request->getSession()->read('RitevetUsers.id')) {
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
        
        // Handle form submission
        if ($this->request->is('post')) 
        {
            $usersTable = $this->fetchTable('Users');
            $user = $usersTable->find()
                ->where(['Users.email' => $this->request->getData('email')])
                ->first();
    
            if (empty($user)) {
                $this->Flash->error(__('This email does not exist.'));
            } else {
                $token = $this->random_password(50);
                $resetUrl = Router::url(['controller' => 'Users', 'action' => 'reset', $token], true);
    
                // Update the user's password token
                $user->passwordToken = $token;
                if ($usersTable->save($user)) {
                    // Prepare email
                    $to = $user->email;
                    $subject = "Ritevet App - Reset Password";
                    $message = "Dear " . ucfirst($user->firstName) ." ". ucfirst($user->lastName).",<br>";
                    $message .= "To reset your password, please <a href='" . $resetUrl . "'>click here</a>.";
                    
                    // Send email using PHPMailer
                    if ($this->phpemail($to, $subject, $message)) {
                        $this->Flash->success(__('Reset password link has been successfully sent to your registered email.'));
                    } else {
                        $this->Flash->error(__('There was an error sending the email. Please try again.'));
                    }
                    
                    // Redirect to a different page or show a success message
                    return $this->redirect(['controller' => 'Users', 'action' => 'forgotpassword']);
                } else {
                    $this->Flash->error(__('Unable to save the password token. Please try again.'));
                }
            }
        }
        
        $this->set(compact('breadcum', 'layoutTitle'));
    }

    public function logout()
    {
        $session = $this->request->getSession();
        if (empty($session->read('RitevetUsers.id'))) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $usersTable = $this->fetchTable('Users');
        $userId = $session->read('RitevetUsers.id');

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
        $session->delete('RitevetUsers');
        $session->delete('Config.timezone');

        $this->Flash->success(__('You have been logged out.'));
        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }
	
	public function dashboard()
    {
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'Ritevet - Dashboard';
        $url = \Cake\Routing\Router::url('/', true);
        $breadcum = [
            'Title' => 'DASHBOARD',
            'URL' => [
                'Home' => $url,
                'DASHBOARD' => $url . 'users/dashboard'
            ]
        ];

        $session = $this->request->getSession();
        $userId = $session->read('RitevetUsers.id');

        if (!empty($userId)) {
            $usersTable = $this->fetchTable('Users');
            $user = $usersTable->get($userId, contain: [
                'Cities',
                'States',
                'Countries',
                'Usersinformations'
            ]);
            $countryName = $user->country->name ?? 'N/A'; // Extract country name
            $this->set(compact('layoutTitle', 'breadcum', 'user', 'countryName'));
        } else {
            if ($userIdFromCookie = $this->request->getCookie('user_id')) {
                $usersTable = $this->fetchTable('Users');
                try {
                    $user = $usersTable->get($userIdFromCookie, contain: [
                        'Cities',
                        'States',
                        'Countries',
                        'Usersinformations'
                    ]);
                    $countryName = $user->country->name ?? 'N/A'; // Extract country name
                    $session->renew();
                    $session->write('RitevetUsers', $user);
                    $session->write('Config.timezone', $this->request->getCookie('timezone'));
                    $this->set(compact('layoutTitle', 'breadcum', 'user', 'countryName'));
                } catch (\Cake\Datasource\Exception\RecordNotFoundException $e) {
                    $this->Flash->error(__('User not found. Please log in again.'));
                    return $this->redirect(['controller' => 'Users', 'action' => 'login']);
                }
            } else {
                $this->Flash->error(__('Your session has expired. Please log in again.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'login']);
            }
        }
        return null; // Explicit return for non-redirect cases
    }

    public function veterinarianRegister()
    {
        $this->viewBuilder()->setLayout('pages');
        $layoutTitle = 'Ritevet - Veterinarian Register';
        $url = Router::url('/', true);
        $sendEmail = 1;
        $breadcum = [
            'Title' => 'Veterinarian Registration',
            'URL' => [
                'Home' => $url,
                'Dashboard' => $url . 'users/dashboard',
                'Veterinarian Registration' => ''
            ]
        ];
        
        if ($this->request->getSession()->read('RitevetUsers.id') == '') {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }
        
        $videoChatAvailabilityTable = $this->fetchTable('Videochatavailability');
        $mobileAvailabilityTable = $this->fetchTable('Mobileavailability');
        $usersInformationsTable = $this->fetchTable('Usersinformations');
        $typeOfServicesTable = $this->fetchTable('Typeofservices');
        $typeOfBusinessTable = $this->fetchTable('Typeofbusines');
        $multiLicensesTable = $this->fetchTable('Multilicenses');
        $typeOfPetsTable = $this->fetchTable('Typeofpets');
        $statesTable = $this->fetchTable('States');
        $imagesTable = $this->fetchTable('Images');
        
        $typeOfPets = $typeOfPetsTable->find()->where(['Typeofpets.status' => 1])->toArray();
        $typeOfBusiness = $typeOfBusinessTable->find()->where(['Typeofbusines.status' => 1, 'Typeofbusines.type' => '2'])->toArray();
        $mobileAvail = $mobileAvailabilityTable->find()->where(['Mobileavailability.userId' => $this->request->getSession()->read('RitevetUsers.id')])->first();
        // pr($mobileAvail);exit;
        $UD = $this->Users->find()->where(['Users.id' => $this->request->getSession()->read('RitevetUsers.id')])->first();
        $stateList = $statesTable->find('list')->where(['States.country_id' => $UD->countryId])->toArray();
        
        $STATEOPTION = '<option value="">Select State</option><option value="1">Andaman and Nicobar Islands</option>';
        if ($stateList) {
            foreach ($stateList as $VSAK => $vaaS) {
                $STATEOPTION .= '<option value="' . $VSAK . '">' . $vaaS . '</option>';
            }
        }
        
        if ($this->request->is('post')) 
        {
            $vetProfileImage = $this->request->getData('profile_picture');
            
            $oldfilename = !empty($UD->profile_picture) ? $UD->profile_picture : null;
            
            if ($vetProfileImage instanceof \Psr\Http\Message\UploadedFileInterface && $vetProfileImage->getClientFilename()) {
                $DATA['profile_picture'] = $this->uploadImage($vetProfileImage, WWW_ROOT . 'img/uploads/users/');
                $USER = $this->Users->patchEntity($UD, $DATA, ['validate' => false]);
                
                if ($this->Users->save($USER)) {
                    if ($oldfilename) {
                        $oldFilePath = WWW_ROOT . 'img/uploads/users/' . $oldfilename;
                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath);
                        }
                    }
                }
            }
            
            $multiLicenses = [];
            foreach ($this->request->getData('LicenseNo') as $KEY => $VALL) {
                if ($VALL != '') {
                    $multiLicenses[$KEY]['LicenseNo'] = $VALL;
                    $multiLicenses[$KEY]['stateId'] = $this->request->getData('stateId')[$KEY];
                }
            }
            
            $postData = $this->request->getData();
            // pr($postData);exit;
            $postData['typeOfPets'] = implode(",", $this->request->getData('typeofPet'));
            $postData['typeOfBusiness'] = implode(",", $this->request->getData('typeOfBusiness'));

            if (!in_array(1, explode(',', $postData['typeOfPets']))) {
                $postData['dog_type'] = null;
            } else {
                $postData['dog_type'] = implode(",", $postData['dog_type']);
            }
            
            if ($this->request->getData('american_board_certified') == 1 && $this->request->getData('american_board_certified_option')) {
                $postData['american_board_certified_option'] = implode(",", $this->request->getData('american_board_certified_option'));
            } else {
                $postData['american_board_certified_option'] = null;
            }
            
            $postData['ACName'] = !empty($this->request->getData('ACName')) ? $this->request->getData('ACName') : null;
            $postData['BankName'] = !empty($this->request->getData('BankName')) ? $this->request->getData('BankName') : null;
            $postData['AccountNo'] = !empty($this->request->getData('AccountNo')) ? $this->request->getData('AccountNo') : null;
            $postData['swiftNumber'] = !empty($this->request->getData('swiftNumber')) ? $this->request->getData('swiftNumber') : null;
            $postData['RoutingNo'] = !empty($this->request->getData('RoutingNo')) ? $this->request->getData('RoutingNo') : null;
            $postData['accountType'] = !empty($this->request->getData('accountType')) ? $this->request->getData('accountType') : null;

            if (in_array(2, explode(',', $postData['typeOfBusiness']))) {
                $postData['MessageChat'] = 2;
                $postData['AudioChat'] = 1;
                $postData['videoChat'] = 1;
            }
            if (in_array(3, explode(',', $postData['typeOfBusiness']))) {
                $postData['MessageChat'] = 2;
                $postData['AudioChat'] = 1;
                $postData['videoChat'] = 2;
            }
            
            $user = $usersInformationsTable->find()->where(['Usersinformations.userId' => $postData['userId']])->first();
                    
            if (empty($user)) {
                $sendEmail = 1;
                $user = $usersInformationsTable->newEmptyEntity();
                $user = $usersInformationsTable->patchEntity($user, $postData, ['validate' => false]);
                $user['added_from'] = 'WEB';
                $user['created'] = date('Y-m-d H:i:s');
                $user['verifyAdmin'] = 0;
            } else {
                $sendEmail = 0;
                $user = $usersInformationsTable->patchEntity($user, $postData, ['validate' => false]);
                $user['verifyAdmin'] = ($user['verifyAdmin'] == 0) ? 0 : 1;
            }
            
            $user['modified'] = date('Y-m-d H:i:s');
            
            if ($usersInformationsTable->save($user)) {
                if (count($multiLicenses) > 0) {
                    $multiLicensesTable->deleteAll(['Multilicenses.UTYPE' => $user->UTYPE, 'Multilicenses.userId' => $user->userId, 'Multilicenses.userinformationId' => $user->id]);
                    foreach ($multiLicenses as $val) {
                        $DATA2['UTYPE'] = $user->UTYPE;
                        $DATA2['userId'] = $user->userId;
                        $DATA2['userinformationId'] = $user->id;
                        $DATA2['stateId'] = $val['stateId'];
                        $DATA2['licenceNo'] = $val['LicenseNo'];
                        
                        $licences = $multiLicensesTable->newEmptyEntity();
                        $licences = $multiLicensesTable->patchEntity($licences, $DATA2, ['validate' => false]);
                        $licences['created'] = date('Y-m-d H:i:s');
                        $licences['status'] = '1';
                        $licences['added_from'] = 'WEB';
                        
                        $multiLicensesTable->save($licences);
                    }
                }
                
                $workdayArray = $this->request->getData('workdayarray');
                if (is_array($workdayArray) && count($workdayArray) > 0) {
                    $videoChatAvailabilityTable->deleteAll(['Videochatavailability.userId' => $this->request->getSession()->read('RitevetUsers.id')]);
                    $this->setVideoChatAvailability();
                } else {
                    $videoChatAvailabilityTable->deleteAll(['Videochatavailability.userId' => $this->request->getSession()->read('RitevetUsers.id')]);
                }
                
                $mobileWorkdayArray = $this->request->getData('mobileworkdayarray');
                if (is_array($mobileWorkdayArray) && count($mobileWorkdayArray) > 0) {
                    // pr('m');exit;
                    $this->setMobileAvailability();
                } else {
                    $mobileAvailabilityTable->deleteAll(['Mobileavailability.userId' => $this->request->getSession()->read('RitevetUsers.id')]);
                }

                if (!empty($this->request->getData('uploadTranscript'))) {
                    $this->uploadImages($user, $this->request);
                }
                
                if (!empty($this->request->getData('uploadLicense'))) {
                    $this->uploadImages($user, $this->request);
                }
                
                if (!empty($this->request->getData('BImage'))) {
                    $this->uploadImages($user, $this->request);
                }

                if (!empty($this->request->getData('uploadDocument'))) {
                    $this->uploadImages($user, $this->request);
                }
                
                if ($sendEmail == 1) {
                    $this->Flash->success(__('Thank you for registering as a veterinarian with Rite Vet. Please check your email and follow the steps to complete your background check. Once your background check clears your account will be activated.'));
                    
                    $to = $UD->email;
                    $subject = "Welcome to RiteVet!";
                    $message = "Dear " . ucfirst($UD->firstName) . ',';
                    if ($postData['UTYPE'] == 2) {
                        $message .= "<br>Thank you for registering as a veterinarian with RiteVet.<br>
                        Please click on this link to complete your background check: <br>
                        <a href='https://verifiedfirst.info/Uy1wug'>Complete Background Check</a><br>
                        And on the following link to complete your license verification: <br> 
                        <a href='https://verifiedfirst.info/wy1r2G'>Complete License Verification</a><br>
                        Once your background check results are complete we will activate your account.<br>
                        This process may take 1-7 days. If you have any questions or concerns, feel free to reach out to us at:<br>
                        Email: ritevet@ritevet.com<br>
                        Phone: (240)748-8088<br>
                        Available Monday - Sunday, from 7:00 pm - 10:00 pm (US Eastern Standard Time)<br>";
                        
                        $message .= "Thank you,<br>RiteVet Team<br>";
                    }
                    $this->phpemail($to, $subject, $message);
                } else {
                    $this->Flash->success(__('Your information has been updated successfully.'));
                }
                
                $usr = $this->Users->find()->where(['Users.id' => $this->request->getSession()->read('RitevetUsers.id')])->contain(['Usersinformations'])->first();
                $userdetail['RitevetUsers'] = $usr;
                $this->request->getSession()->write($userdetail);

                return $this->redirect(['controller' => 'Users', 'action' => 'veterinarianRegister']);
            }
        }
        
        $MobileArray = [];
        if ($mobileAvail && $mobileAvail->MON == 1) {
            $MobileArray[] = 'MON';
        }
        if ($mobileAvail && $mobileAvail->TUE == 1) {
            $MobileArray[] = 'TUE';
        }
        if ($mobileAvail && $mobileAvail->WED == 1) {
            $MobileArray[] = 'WED';
        }
        if ($mobileAvail && $mobileAvail->THU == 1) {
            $MobileArray[] = 'THU';
        }
        if ($mobileAvail && $mobileAvail->FRI == 1) {
            $MobileArray[] = 'FRI';
        }
        if ($mobileAvail && $mobileAvail->SAT == 1) {
            $MobileArray[] = 'SAT';
        }
        if ($mobileAvail && $mobileAvail->SUN == 1) {
            $MobileArray[] = 'SUN';
        }
        
        if ($mobileAvail) {
            $mobileAvail->mobileworkdayarray = $MobileArray;
        }
        
        $usersINfor = $usersInformationsTable->find()
            ->where(['Usersinformations.userId' => $this->request->getSession()->read('RitevetUsers.id'), 'Usersinformations.UTYPE' => 2])
            ->contain(['Images', 'Multilicenses' => ['States'], 'Videochatavailability'])->first();
            
        $this->set(compact('mobileAvail', 'UD', 'STATEOPTION', 'stateList', 'usersINfor', 'layoutTitle', 'breadcum', 'typeOfPets', 'typeOfBusiness'));
    }
    
    public function otherPetServiceRegister()
    {
        $this->viewBuilder()->setLayout('pages');

        // Layout variables
        $layoutTitle = 'Ritevet - Other Pet Service Registration';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'Other Pet Service Registration',
            'URL' => [
                'Home' => $url,
                'Dashboard' => $url . 'users/dashboard',
                'Other Pet Service Registration' => ''
            ]
        ];

        // Check if user is logged in
        $session = $this->request->getSession();
        $userId = $session->read('RitevetUsers.id');
        if (empty($userId)) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Load models
        $homeservicesquestionsTable = $this->fetchTable('Homeservicesquestions');
        $videochatavailabilityTable = $this->fetchTable('Videochatavailability');
        $typeofservicesrateTable = $this->fetchTable('Typeofservicesrate');
        $usersinformationsTable = $this->fetchTable('Usersinformations');
        $homeservicesinfoTable = $this->fetchTable('Homeservicesinfo');
        $typeofservicesTable = $this->fetchTable('Typeofservices');
        $typeofpetsTable = $this->fetchTable('Typeofpets');
        $usersTable = $this->fetchTable('Users');

        $sendEmail = 0;

        // Fetch user data
        try {
            $UD = $usersTable->find()
                ->where(['Users.id' => $userId])
                ->firstOrFail();
        } catch (RecordNotFoundException $e) {
            $this->Flash->error(__('User not found. Please log in again.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Fetch additional data
        $typeOfPets = $typeofpetsTable->find()
            ->where(['Typeofpets.status' => 1])
            ->all()
            ->toArray();

        $typeOfServices = $typeofservicesTable->find()
            ->where(['Typeofservices.status' => 1, 'Typeofservices.UTYPE' => 3])
            ->all()
            ->toArray();

        $rates = $typeofservicesrateTable->find()
            ->where(['Typeofservicesrate.userId' => $userId])
            ->order(['Typeofservicesrate.id' => 'ASC'])
            ->all()
            ->toArray();

        $videoChatAvailCount = $videochatavailabilityTable->find()
            ->where(['Videochatavailability.userId' => $userId])
            ->count();

        $homeServicesQuestions = $homeservicesquestionsTable->find()
            ->where(['Homeservicesquestions.status' => 1])
            ->order(['Homeservicesquestions.id' => 'ASC'])
            ->all();

        $homeServicesInfo = $homeservicesinfoTable->find()
            ->where(['Homeservicesinfo.userId' => $userId])
            ->all();

        $homeServicesInfoCount = $homeservicesinfoTable->find()
            ->where(['Homeservicesinfo.userId' => $userId])
            ->count();

        // Handle POST request
        if ($this->request->is('post')) 
        {
            $requestData = $this->request->getData();

            // Handle profile picture upload
            $serProvProfileImage = $this->request->getData('profile_picture');
            if ($serProvProfileImage && $serProvProfileImage->getSize() > 0) {
                $uploadPath = WWW_ROOT . 'img' . DS . 'uploads' . DS . 'users' . DS;

                // Ensure directory exists and is writable
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                if (!is_writable($uploadPath)) {
                    $this->Flash->error(__('Upload directory is not writable. Please check server permissions.'));
                } else {
                    $newFilename = $this->uploadImage($serProvProfileImage, $uploadPath);

                    if ($newFilename) {
                        if (!empty($UD->profile_picture)) {
                            $oldFilePath = $uploadPath . $UD->profile_picture;
                            if (file_exists($oldFilePath)) {
                                unlink($oldFilePath);
                            }
                        }
                        $data = ['profile_picture' => $newFilename];
                        $userEntity = $usersTable->patchEntity($UD, $data);
                        if ($usersTable->save($userEntity)) {
                            $UD->profile_picture = $newFilename;
                        } else {
                            $this->Flash->error(__('Failed to save profile picture to database.'));
                        }
                    } else {
                        $this->Flash->error(__('Failed to upload profile picture to directory. Check file type, size, or permissions.'));
                    }
                }
            }

            // Process form data
            $requestData['TypeOfService'] = implode(',', $requestData['typeofService'] ?? []);
            $requestData['typeOfPets'] = implode(',', $requestData['typeofPet'] ?? []);

            if (!in_array(1, explode(',', $requestData['typeOfPets']))) {
                $requestData['dog_type'] = null;
            } else {
                $requestData['dog_type'] = implode(',', $requestData['dog_type'] ?? []);
            }
            
            // Set nullable fields
            $requestData['ACName'] = !empty($this->request->getData('ACName')) ? $this->request->getData('ACName') : null;
            $requestData['BankName'] = !empty($this->request->getData('BankName')) ? $this->request->getData('BankName') : null;
            $requestData['AccountNo'] = !empty($this->request->getData('AccountNo')) ? $this->request->getData('AccountNo') : null;
            $requestData['swiftNumber'] = !empty($this->request->getData('swiftNumber')) ? $this->request->getData('swiftNumber') : null;
            $requestData['RoutingNo'] = !empty($this->request->getData('RoutingNo')) ? $this->request->getData('RoutingNo') : null;
            $requestData['accountType'] = !empty($this->request->getData('accountType')) ? $this->request->getData('accountType') : null;
            
            
            $requestData['AudioChat'] = 1;
            $requestData['videoChat'] = in_array(16, $requestData['typeofService'] ?? []) ? 2 : 1;
            $requestData['MessageChat'] = 2;

            // Update or create user information
            $user = $usersinformationsTable->find()
                ->where(['Usersinformations.userId' => $userId])
                ->first();

            if (!$user) {
                $sendEmail = 1;
                $user = $usersinformationsTable->newEntity($requestData, ['validate' => false]);
                $user->userId = $userId;
                $user->added_from = 'WEB';
                $user->created = DateTime::now();
                $user->verifyAdmin = 0;
            } else {
                $sendEmail = 0;
                $user = $usersinformationsTable->patchEntity($user, $requestData, ['validate' => false]);
                $user->verifyAdmin = $user->verifyAdmin == 0 ? 0 : 1;
            }

            $user->modified = DateTime::now();

            if ($usersinformationsTable->save($user)) {
                // Handle certificate uploads
                if (!empty($requestData['EX_CERT']) && method_exists($this, 'uploadImages')) {
                    $this->uploadImages($user, $this->request);
                }

                // Handle video chat availability and service rates
                if (!empty($requestData['typeofService'])) {
                    if (in_array(16, $requestData['typeofService'])) {
                        $videochatavailabilityTable->deleteAll(['Videochatavailability.userId' => $userId]);
                        if (!empty($requestData['workdayarray']) && method_exists($this, 'setVideoChatAvailability')) {
                            $this->setVideoChatAvailability();
                        }
                    } else {
                        $videochatavailabilityTable->deleteAll(['Videochatavailability.userId' => $userId]);
                    }

                    $typeofservicesrateTable->deleteAll(['Typeofservicesrate.userId' => $userId]);
                    foreach ($requestData['typeofService'] as $val) {
                        $serviceData = [
                            'userId' => $userId,
                            'typeofservice_id' => $val,
                            'added_from' => 'WEB',
                            'created' => DateTime::now()
                        ];

                        if ($val != 16) {
                            $rate = $requestData['rate'][$val] ?? 0;
                            $serviceData['rate'] = $rate;
                            $serviceData['tax'] = $rate * 0.16;
                            $serviceData['final_rate'] = ceil($rate + ($rate * 0.16));
                            $serviceData['service_work_days'] = implode(',', $requestData["serviceWorkday_{$val}"] ?? []);
                        } else {
                            $rate = $requestData['price'] ?? 0;
                            $serviceData['rate'] = $rate;
                            $serviceData['tax'] = $rate * 0.16;
                            $serviceData['final_rate'] = $rate + ($rate * 0.16);
                            $serviceData['service_work_days'] = implode(',', $requestData['workdayarray'][0] ?? []);
                        }

                        $serviceRate = $typeofservicesrateTable->newEntity($serviceData, ['validate' => false]);
                        $typeofservicesrateTable->save($serviceRate);
                    }
                }

                // Handle home services questions
                $homeServiceTypes = [7, 8, 9, 10, 11, 12, 14, 15];
                if (array_intersect($homeServiceTypes, $requestData['typeofService'] ?? [])) {
                    if (method_exists($this, 'setHomeServicesQuestions')) {
                        $this->setHomeServicesQuestions();
                    }
                } else {
                    $homeservicesinfoTable->deleteAll(['Homeservicesinfo.userId' => $userId]);
                }

                // Update session data
                $usr = $usersTable->find()
                    ->where(['Users.id' => $userId])
                    ->contain(['Usersinformations'])
                    ->firstOrFail();
                $session->write('RitevetUsers', $usr);

                // Send email if new registration
                if ($sendEmail) {
                    $this->Flash->success(__('Thank you for registering as a Pet Service Provider with Rite Vet. Please check your email and follow the steps to complete your background check. Once your background check clears your account will be activated.'));

                    $to = $UD->email;
                    $subject = 'Welcome to RiteVet!';
                    $message = "Dear " . ucfirst($UD->firstName) . ",";
                    $message .= "<br>Thank you for registering as a Pet Service Provider with RiteVet.<br>
                        Please click on this link to complete your background check: <br>
                        <a href='https://verifiedfirst.info/Uy1wug'>Complete Background Check</a><br>
                        Once your background check results are complete we will activate your account.<br>
                        This process may take 1-7 days. If you have any questions or concerns, feel free to reach out to us at:<br>
                        Email: ritevet@ritevet.com<br>
                        Phone: (240)748-8088<br>
                        Available Monday - Sunday, from 7:00 pm - 10:00 pm (US Eastern Standard Time)<br>";
                    $message .= "Thank you,<br>RiteVet Team<br>";

                    if (method_exists($this, 'phpemail')) {
                        $this->phpemail($to, $subject, $message);
                    } else {
                        $this->Flash->warning(__('Email sending not implemented. Please configure email settings.'));
                    }
                } else {
                    $this->Flash->success(__('Your information has been updated successfully.'));
                }
            } else {
                $this->Flash->error(__('There was an error saving your information. Please try again.'));
            }
        }

        // Fetch user information for the view
        $usersINfor = $usersinformationsTable->find()
            ->where([
                'Usersinformations.userId' => $userId,
                'Usersinformations.UTYPE' => 3
            ])
            ->contain(['Videochatavailability', 'Images'])
            ->first();

        // Set view variables
        $this->set(compact(
            'UD',
            'videoChatAvailCount',
            'usersINfor',
            'layoutTitle',
            'breadcum',
            'typeOfPets',
            'typeOfServices',
            'rates',
            'homeServicesQuestions',
            'homeServicesInfo',
            'homeServicesInfoCount'
        ));

        return null;
    }
    
    public function petParentRegister()
    {
        $email = 0;
        
        if (empty($this->getRequest()->getSession()->read('RitevetUsers.id'))) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $usersInformationsTable = $this->fetchTable('Usersinformations');
        $user = $usersInformationsTable->find()
            ->where(['Usersinformations.userId' => $this->getRequest()->getSession()->read('RitevetUsers.id')])
            ->first();

        if (empty($user)) {
            $user = $usersInformationsTable->newEmptyEntity();
        } else {
            $email = 1;
        }

        $data = [
            'userId' => $this->getRequest()->getSession()->read('RitevetUsers.id'),
            'UTYPE' => 1
        ];

        $user = $usersInformationsTable->patchEntity($user, $data, ['validate' => false]);
        $user->added_from = 'WEB';
        $user->created = date('Y-m-d H:i:s');
        $user->modified = date('Y-m-d H:i:s');

        if ($usersInformationsTable->save($user)) {
            $this->Flash->success(__('Thank you for registering as a Pet Parent with RiteVet. You can start using the RiteVet platforms.'));

            // Fetch Users table using fetchTable
            $usersTable = $this->fetchTable('Users');
            $userDt = $usersTable->find()
                ->where(['Users.id' => $this->getRequest()->getSession()->read('RitevetUsers.id')])
                ->contain(['Usersinformations'])
                ->first();

            $this->getRequest()->getSession()->write(['RitevetUsers' => $userDt]);

            $to = $userDt->email;
            $subject = "Welcome to RiteVet!";
            $message = "Dear " . ucfirst($userDt->firstName) . ",";
            $message .= "<br>Thank you for registering as a Pet Parent with RiteVet.<br>
                        You can start using the RiteVet platforms.<br>
                        If you have any questions or concerns, feel free to reach out to us at:<br>
                        Email: ritevet@ritevet.com<br>
                        Phone: (240)748-8088<br>
                        Available Monday - Sunday, from 7:00 pm - 10:00 pm (US Eastern Standard Time)<br>
                        Thank you,<br>
                        RiteVet Team<br>";
            
            if($email == 0) {
                $this->phpemail($to, $subject, $message);
            }
            
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        } else {
            $this->Flash->error(__('Unable to register you as a Pet Parent. Please try again.'));
        }
    }
    
    public function veterinary()
    {
        $this->viewBuilder()->setLayout('pages');
        $layoutTitle = 'Ritevet - Veterinary Search';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'Veterinary Search',
            'URL' => [
                'Home' => $url,
                'Find Veterinarian' => $url . 'users/request-service',
                'Veterinary Search' => ''
            ]
        ];
    
        // Check if user is logged in
        if (empty($this->request->getSession()->read('RitevetUsers.id'))) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }
    
        // Default redirect if typeofbusiness is not set
        if (empty($this->request->getQuery('typeofbusiness'))) {
            return $this->redirect([
                'controller' => 'Users',
                'action' => 'veterinary',
                '?' => ['typeofbusiness' => 2]
            ]);
        }
    
        // Fetch tables
        $videoChatTable = $this->fetchTable('Videochatavailability');
        $usersInfoTable = $this->fetchTable('Usersinformations');
        $typeOfBusinessTable = $this->fetchTable('Typeofbusines');
        $typeOfPetsTable = $this->fetchTable('Typeofpets');
        $usersTable = $this->fetchTable('Users');
    
        // Fetch data
        $typeOfBusiness = $typeOfBusinessTable->find('list')
            ->where(['Typeofbusines.status' => 1, 'Typeofbusines.type' => 2])
            ->toArray();
    
        $typeOfPets = $typeOfPetsTable->find()
            ->where(['Typeofpets.status' => 1])
            ->toArray();
    
        $userLatitude = $this->request->getSession()->read('RitevetUsers.latitude');
        $userLongitude = $this->request->getSession()->read('RitevetUsers.longitude');
    
        $conditions = [
            'Usersinformations.UTYPE' => 2,
            'Usersinformations.verifyAdmin' => 1
        ];
    
        // Build the query
        $query = $usersInfoTable->find('all')
            ->contain([
                'Users',
                'Mobileavailability',
                'Videochatavailability' => function ($q) {
                    return $q->where(['Videochatavailability.userId IS NOT NULL']);
                },
                'Reviews' => function ($q) {
                    return $q->where(['Reviews.reviewTo IS NOT NULL', 'Reviews.status' => 1]);
                }
            ])
            ->leftJoinWith('Videochatavailability')
            ->leftJoinWith('Reviews')
            ->where($conditions)
            ->distinct(['Usersinformations.id'])
            ->group(['Usersinformations.id']);
    
        if ($this->request->is('get')) {
            // Get query parameters
            $typeofbusiness = $this->request->getQuery('typeofbusiness');
            $checkedPetTypes = (array) $this->request->getQuery('petType');
            $checkedServiceType = $this->request->getQuery('serviceType');
            $zipCode = $this->request->getQuery('zipCode');
            $requestServiceDate = $this->request->getQuery('request_service_date');
            $dayOfWeek = $requestServiceDate ? strtoupper((new \DateTime($requestServiceDate))->format('D')) : null;
            $checkedDogSize = (array) $this->request->getQuery('dogSize');
    
            // Pet type filter
            if (!empty($checkedPetTypes)) {
                $pattern = implode('|', array_map('preg_quote', $checkedPetTypes));
                $conditions[] = ['Usersinformations.typeOfPets REGEXP' => "(^|,)$pattern(,|$)"];
            }
    
            // Business type filter
            if (!empty($typeofbusiness)) {
                $conditions[] = ["FIND_IN_SET(:typeofbusiness, Usersinformations.typeOfBusiness)"];
                $query->bind(':typeofbusiness', $typeofbusiness, 'string');
            }
    
            // Dog size filter
            if (!empty($checkedDogSize)) {
                $pattern = implode('|', array_map('preg_quote', $checkedDogSize));
                $conditions[] = ['Usersinformations.dog_type REGEXP' => "(^|,)$pattern(,|$)"];
            }
    
            // Availability filter
            if (!empty($requestServiceDate) && !empty($dayOfWeek)) {
                if ($typeofbusiness == 2) {
                    $conditions[] = ["Mobileavailability.$dayOfWeek" => 1];
                }
                if ($typeofbusiness == 3) {
                    $conditions[] = ["Videochatavailability.$dayOfWeek" => 1];
                }
            }
    
            // Update query with conditions
            $query->where($conditions);
    
            // Distance filter using Haversine formula
            if (!empty($zipCode) && is_numeric($userLatitude) && is_numeric($userLongitude)) {
                $query->select([
                    'distance' => $query->newExpr(
                        "6371 * acos(
                            cos(radians(:lat)) * 
                            cos(radians(Users.latitude)) * 
                            cos(radians(Users.longitude) - radians(:lon)) + 
                            sin(radians(:lat)) * 
                            sin(radians(Users.latitude))
                        )"
                    )
                ])
                ->bind(':lat', $userLatitude, 'float')
                ->bind(':lon', $userLongitude, 'float')
                ->having(['distance <=' => 30]);
            }
        }
    
        // Paginate the results with options
        try {
            $users = $this->paginate($query, [
                'limit' => 6,
                'order' => ['Usersinformations.id' => 'ASC']
            ]);
        } catch (\Exception $e) {
            $this->Flash->error(__('Unable to load results. Please try again. Error: ' . $e->getMessage()));
            $users = [];
        }
    
        $this->set(compact('layoutTitle', 'breadcum', 'typeOfBusiness', 'typeOfPets', 'users'));
    }
	
	public function veterinarydetail($id = null, $typeOfBu = null)
    {
        $this->viewBuilder()->setLayout('profile');
        $layoutTitle = 'Ritevet - Veterinary Details';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'Veterinary Details',
            'URL' => [
                'Home' => $url,
                'Veterinary Details' => ''
            ]
        ];

        if (empty($this->getRequest()->getSession('RitevetUsers.id'))) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        if (empty($typeOfBu)) {
            return $this->redirect(['controller' => 'Users', 'action' => 'veterinary', '?' => ['typeofbusiness' => 2]]);
        }

        // Load required models
        $videoChatAvailabilityTable = $this->fetchTable('Videochatavailability');
        $mobileAvailabilityTable = $this->fetchTable('Mobileavailability');
        $usersInformationsTable = $this->fetchTable('Usersinformations');
        $typeOfBusinesTable = $this->fetchTable('Typeofbusines');
        $typeOfPetsTable = $this->fetchTable('Typeofpets');
        $requestsTable = $this->fetchTable('Requests');
        $reviewsTable = $this->fetchTable('Reviews');
        $usersTable = $this->fetchTable('Users');
        $ordersTable = $this->fetchTable('Orderes');
        $cartsTable = $this->fetchTable('Carts');
        $productsTable = $this->fetchTable('Products');

        // Fetch data
        $typeOfBusiness = $typeOfBusinesTable->find()
            ->where(['Typeofbusines.status' => 1, 'Typeofbusines.type' => 2])
            ->toArray();

        $typeOfPets = $typeOfPetsTable->find()
            ->where(['Typeofpets.status' => 1])
            ->toArray();

        $users = $usersInformationsTable->find()
            ->where(['Usersinformations.id' => base64_decode($id)])
            ->contain(['Users' => ['Countries', 'States', 'Cities']])
            ->first();

        if (!$users) {
            throw new \Cake\Datasource\Exception\RecordNotFoundException('User information not found.');
        }

        $videoChatAvail = $videoChatAvailabilityTable->find()
            ->where(['Videochatavailability.userId' => $users->userId])
            ->toArray();

        $mobileAvail = $mobileAvailabilityTable->find()
            ->where(['Mobileavailability.userId' => $users->userId])
            ->first();

        // Calculate disabled days for virtual business
        $disabledDaysForVirtualBusiness = [];
        if (!empty($videoChatAvail)) {
            $disabledDaysForVirtualBusiness = $this->getDisabledDays($videoChatAvail); // Ensure this method exists
        }

        // Calculate disabled days for mobile business
        $disabledDaysForMobileBusiness = [];
        if (!empty($mobileAvail->id)) {
            $days = [
                'SAT' => 6,
                'SUN' => 0,
                'MON' => 1,
                'TUE' => 2,
                'WED' => 3,
                'THU' => 4,
                'FRI' => 5
            ];
            foreach ($days as $day => $index) {
                if ($mobileAvail->$day != 1) {
                    $disabledDaysForMobileBusiness[] = $index;
                }
            }
        }

        // Count requests
        $requests = $requestsTable->find()
            ->where([
                'Requests.serviceProvider' => $users->userId,
                'Requests.sender' => $this->getRequest()->getSession()->read('RitevetUsers.id'),
                'Requests.status' => 5
            ])
            ->count();

        // Count orders
        $orderCount = $ordersTable->find()
            ->contain(['Carts' => ['Products']])
            ->where([
                'Orderes.userId' => $this->getRequest()->getSession()->read('RitevetUsers.id'),
                'Orderes.orderStatus' => 3,
                'Products.userId' => $users->userId
            ])
            ->count();

        if ($this->getRequest()->is('post')) {
            $reviewTo = $users->userId;
            $reviewFrom = $this->getRequest()->getSession()->read('RitevetUsers.id');

            $reviewData = [
                'reviewTo' => $reviewTo,
                'reviewFrom' => $reviewFrom,
                'message' => $this->getRequest()->getData('message'),
                'star' => $this->getRequest()->getData('star'),
                'status' => 1,
                'added_from' => 'WEB'
            ];

            $reviewEntity = $reviewsTable->newEmptyEntity();
            $reviewEntity = $reviewsTable->patchEntity($reviewEntity, $reviewData, ['validate' => false]);

            if ($reviewsTable->save($reviewEntity)) {
                // Calculate average rating
                $avgRatingQuery = $reviewsTable->find()
                    ->select(['averagerating' => $reviewsTable->find()->func()->avg('star')])
                    ->where(['reviewTo' => $reviewTo]);

                $avgRatingResult = $avgRatingQuery->first();
                $averageRating = $avgRatingResult ? round($avgRatingResult->averagerating, 1) : 0;

                // Update user's average rating
                $userEntity = $usersTable->get($reviewTo);
                $userEntity = $usersTable->patchEntity($userEntity, ['AVGRating' => $averageRating], ['validate' => false]);

                if (!$usersTable->save($userEntity)) {
                    $this->Flash->error(__('Unable to update user rating.'));
                }
            } else {
                $this->Flash->error(__('Unable to save your review. Please try again.'));
            }

            return $this->redirect(['controller' => 'Users', 'action' => 'veterinarydetail', $id, $typeOfBu]);
        }

        $query = $reviewsTable->find()
            ->where([
                'Reviews.reviewTo' => $users->userId,
                'Reviews.status' => 1
            ])
            ->contain(['Reviewfroms']); // Moved contain here

        $totalReviewCount = $query->count();
        $reviews = $this->paginate($query, [
            'limit' => 5,
            'order' => ['Reviews.created' => 'DESC']
        ]);

        $this->set(compact(
            'totalReviewCount',
            'layoutTitle',
            'breadcum',
            'typeOfBusiness',
            'typeOfPets',
            'users',
            'videoChatAvail',
            'mobileAvail',
            'reviews',
            'typeOfBu',
            'disabledDaysForVirtualBusiness',
            'disabledDaysForMobileBusiness',
            'requests',
            'orderCount'
        ));

        return null; // Explicit return for non-redirect cases
    }
    
	public function request()
    {
        $this->viewBuilder()->setLayout('pages');
        $layoutTitle = 'Ritevet - Request Service';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'Request Service',
            'URL' => [
                'Home' => $url,
                'Dashboard' => $url . 'users/dashboard',
                'Request Service' => $url . 'users/request',
            ]
        ];

        // Check if the user is logged in
        if ($this->getRequest()->getSession()->read('RitevetUsers.id') === null) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $this->set(compact('layoutTitle', 'breadcum'));
    }

    public function requestService()
    {
        $this->viewBuilder()->setLayout('pages');
        $layoutTitle = 'Ritevet - Find Veterinarian';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'Find Veterinarian',
            'URL' => [
                'Home' => $url,
                'Request Service' => $url . 'users/request',
                'Find Veterinarian' => $url . 'users/request-service'
            ]
        ];

        // Check if the user is logged in
        if ($this->getRequest()->getSession()->read('RitevetUsers.id') === null) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $this->set(compact('layoutTitle', 'breadcum'));
    }
	
	public function requestOther()
    {
        $this->viewBuilder()->setLayout('pages');
        $layoutTitle = 'Ritevet - Service Provider';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'Find Service Provider',
            'URL' => [
                'Home' => $url,
                'Request Service' => $url . 'users/request',
                'Find Service Provider' => $url . 'users/request-other'
            ]
        ];

        // Check if the user is logged in
        if ($this->request->getSession()->read('RitevetUsers.id') === null) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Fetch tables
        $typeofservicesrateTable = $this->fetchTable('Typeofservicesrate');
        $usersinformationsTable = $this->fetchTable('Usersinformations');
        $typeofservicesTable = $this->fetchTable('Typeofservices');
        $typeofpetsTable = $this->fetchTable('Typeofpets');
        $reviewsTable = $this->fetchTable('Reviews');
        $usersTable = $this->fetchTable('Users');

        // Fetch data
        $typeOfServices = $typeofservicesTable->find()
            ->where(['Typeofservices.status' => 1, 'Typeofservices.UTYPE' => 3])
            ->toArray();

        $typeOfPets = $typeofpetsTable->find()
            ->where(['Typeofpets.status' => 1])
            ->toArray();

        $userLatitude = $this->request->getSession()->read('RitevetUsers.latitude');
        $userLongitude = $this->request->getSession()->read('RitevetUsers.longitude');

        $conditions = [
            'Usersinformations.UTYPE' => 3,
            'Usersinformations.verifyAdmin' => 1,
        ];

        // Initialize query
        $query = $usersinformationsTable->find('all')
            ->contain([
                'Users',
                'Typeofservicesrate',
                'Videochatavailability',
                'Reviews' => function ($q) {
                    return $q->where(['Reviews.status' => 1]);
                }
            ])
            ->leftJoinWith('Videochatavailability')
            ->leftJoinWith('Typeofservicesrate')
            ->where($conditions)
            ->group(['Usersinformations.id']);

        $typeOfServicesPer = null;

        if ($this->request->is('get')) {
            // Get query parameters
            $checkedPetTypes = (array) $this->request->getQuery('petType', []);
            $checkedServiceType = $this->request->getQuery('serviceType');
            $zipCode = $this->request->getQuery('zipCode');
            $city = $this->request->getQuery('city');
            $state = $this->request->getQuery('state');
            $frequency = $this->request->getQuery('frequency');
            $startDate = $this->request->getQuery('startDate');
            $requestServiceDate = $this->formatDateString($this->request->getQuery('request_service_date'));
            $dayOfWeek = $requestServiceDate ? strtoupper(date('D', strtotime($requestServiceDate))) : null;
            $checkedDogSize = (array) $this->request->getQuery('dogSize', []);

            // Conditions based on user input
            if (!empty($checkedPetTypes)) {
                $pattern = implode('|', array_map('preg_quote', $checkedPetTypes));
                $conditions[] = ['typeOfPets REGEXP' => "(^|,)$pattern(,|$)"];
            }

            if (!empty($checkedServiceType)) {
                $conditions[] = "FIND_IN_SET(:serviceType, Usersinformations.TypeOfService)";
                $query->bind(':serviceType', $checkedServiceType, 'string');
            }

            if (!empty($checkedDogSize)) {
                $pattern = implode('|', array_map('preg_quote', $checkedDogSize));
                $conditions[] = ['dog_type REGEXP' => "(^|,)$pattern(,|$)"];
            }

            if (!empty($requestServiceDate)) {
                if ($checkedServiceType == 16) {
                    $conditions[] = ['Videochatavailability.' . $dayOfWeek => 1];
                } else {
                    $conditions[] = [
                        'UPPER(Typeofservicesrate.service_work_days) LIKE' => '%' . $dayOfWeek . '%',
                        'Typeofservicesrate.typeofservice_id' => $checkedServiceType
                    ];
                }
            }

            if (!empty($startDate)) {
                $startDate = urldecode($startDate);
                $dateArray = explode(',', $startDate);
                foreach ($dateArray as $date) {
                    $date = trim($date);
                    $dateParts = explode('-', $date);
                    if (count($dateParts) == 3) {
                        $formattedDate = $dateParts[2] . '-' . str_pad($dateParts[0], 2, '0', STR_PAD_LEFT) . '-' . str_pad($dateParts[1], 2, '0', STR_PAD_LEFT);
                        $start_date = strtotime($formattedDate);
                        $day_of_week = strtoupper(date('D', $start_date));
                        $conditions[] = ['UPPER(Typeofservicesrate.service_work_days) LIKE' => '%' . $day_of_week . '%'];
                        $conditions[] = ['Typeofservicesrate.typeofservice_id' => $checkedServiceType];
                    }
                }
            }

            // Update query with conditions
            $query->where($conditions);

            // Distance filter using Haversine formula
            if (!empty($zipCode) && is_numeric($userLatitude) && is_numeric($userLongitude)) {
                $query->select([
                    'distance' => $query->newExpr(
                        "6371 * acos(
                            cos(radians(:lat)) * 
                            cos(radians(Users.latitude)) * 
                            cos(radians(Users.longitude) - radians(:lon)) + 
                            sin(radians(:lat)) * 
                            sin(radians(Users.latitude))
                        )"
                    )
                ])
                ->bind(':lat', $userLatitude, 'float')
                ->bind(':lon', $userLongitude, 'float')
                ->having(['distance <=' => 30]);
            }

            // Fetch typeOfServicesPer if service type is selected
            if (!empty($checkedServiceType)) {
                $typeOfServicesPer = $typeofservicesTable->find()
                    ->select(['per'])
                    ->where([
                        'Typeofservices.status' => 1,
                        'Typeofservices.UTYPE' => 3,
                        'Typeofservices.id' => $checkedServiceType
                    ])
                    ->first();
            }
        }

        // Paginate the results
        $users = $this->paginate($query, [
            'limit' => 6,
            'order' => ['Usersinformations.id' => 'ASC']
        ]);

        $this->set(compact('layoutTitle', 'breadcum', 'typeOfServices', 'typeOfPets', 'users', 'typeOfServicesPer'));
    }
	
	public function serviceProviderDetail($id = null)
    {
        $this->viewBuilder()->setLayout('profile');
        $layoutTitle = 'Ritevet - Service Provider Details';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'Service Provider Details',
            'URL' => [
                'Home' => $url,
                'Service Provider Details' => ''
            ]
        ];

        if (empty($this->request->getSession()->read('RitevetUsers.id'))) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Fetch tables
        $videoChatTable = $this->fetchTable('Videochatavailability');
        $typeOfServicesRateTable = $this->fetchTable('Typeofservicesrate');
        $usersInfoTable = $this->fetchTable('Usersinformations');
        $homeServicesInfoTable = $this->fetchTable('Homeservicesinfo');
        $typeOfServicesTable = $this->fetchTable('Typeofservices');
        $typeOfBusinessTable = $this->fetchTable('Typeofbusines');
        $typeOfPetsTable = $this->fetchTable('Typeofpets');
        $requestsTable = $this->fetchTable('Requests');
        $reviewsTable = $this->fetchTable('Reviews');
        $usersTable = $this->fetchTable('Users');
        $ordersTable = $this->fetchTable('Orderes');
        $cartsTable = $this->fetchTable('Carts');
        $productsTable = $this->fetchTable('Products');

        // Fetch data
        $typeOfServices = $typeOfServicesTable->find()
            ->where(['Typeofservices.status' => 1, 'Typeofservices.UTYPE' => 3])
            ->toArray();

        $typeOfPets = $typeOfPetsTable->find()
            ->where(['Typeofpets.status' => 1])
            ->toArray();

        $users = $usersInfoTable->find()
            ->where(['Usersinformations.id' => base64_decode($id)])
            ->contain(['Users' => ['Countries', 'States', 'Cities']])
            ->firstOrFail();

        $homeServicesInfo = $homeServicesInfoTable->find()
            ->where(['Homeservicesinfo.userId' => $users->userId])
            ->contain(['Homeservicesquestions'])
            ->order(['Homeservicesinfo.id' => 'ASC'])
            ->toArray();

        $typeOfServicesRates = $typeOfServicesRateTable->find()
            ->where(['Typeofservicesrate.userId' => $users->userId])
            ->order(['Typeofservicesrate.id' => 'ASC'])
            ->toArray();

        $videoChatAvail = $videoChatTable->find()
            ->where(['Videochatavailability.userId' => $users->userId])
            ->toArray();

        $disabledDaysForVirtualService = [];
        if (!empty($videoChatAvail)) {
            $disabledDaysForVirtualService = $this->getDisabledDays($videoChatAvail);
        }

        $disabledDaysForService = [];
        foreach ($typeOfServicesRates as $rate) {
            $workDays = explode(',', $rate->service_work_days);
            $serviceDisabledDays = [];
            $allDays = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
            foreach ($allDays as $day) {
                if (!in_array(strtoupper($day), $workDays)) {
                    $dayIndex = array_search($day, $allDays);
                    $serviceDisabledDays[] = $dayIndex;
                }
            }
            $disabledDaysForService[$rate->typeofservice_id] = $serviceDisabledDays;
        }

        $requests = $requestsTable->find()
            ->where([
                'Requests.serviceProvider' => $users->userId,
                'Requests.sender' => $this->request->getSession()->read('RitevetUsers.id'),
                'Requests.status' => 5
            ])
            ->count();

        $orderCount = $ordersTable->find()
            ->contain(['Carts' => ['Products']])
            ->where([
                'Orderes.userId' => $this->request->getSession()->read('RitevetUsers.id'),
                'Orderes.orderStatus' => 3,
                'Products.userId' => $users->userId
            ])
            ->count();

        if ($this->request->is('post')) {
            $reviewTo = $users->userId;
            $reviewFrom = $this->request->getSession()->read('RitevetUsers.id');

            $reviewData = [
                'reviewTo' => $reviewTo,
                'reviewFrom' => $reviewFrom,
                'message' => $this->request->getData('message'),
                'star' => $this->request->getData('star'),
                'status' => 1,
                'added_from' => 'WED',
            ];

            $reviewEntity = $reviewsTable->newEntity($reviewData, ['validate' => false]);
            
            if ($reviewsTable->save($reviewEntity)) {
                $avgRatingQuery = $reviewsTable->find()
                    ->select(['averagerating' => $reviewsTable->find()->func()->avg('star')])
                    ->where(['reviewTo' => $reviewTo])
                    ->first();

                $averageRating = $avgRatingQuery ? round($avgRatingQuery->averagerating, 1) : 0;

                $userEntity = $usersTable->get($reviewTo);
                $userEntity->AVGRating = $averageRating;

                if (!$usersTable->save($userEntity)) {
                    $this->Flash->error(__('Unable to update user rating.'));
                }
            } else {
                $this->Flash->error(__('Unable to save your review. Please try again.'));
            }

            return $this->redirect(['controller' => 'Users', 'action' => 'serviceProviderDetail', $id]);
        }

        $query = $reviewsTable->find()
            ->where(['Reviews.reviewTo' => $users->userId, 'Reviews.status' => 1])
            ->contain(['Reviewfroms']);

        $totalReviewCount = $query->count();
        $reviews = $this->paginate($query, [
            'limit' => 5,
            'order' => ['Reviews.created' => 'DESC']
        ]);

        $this->set(compact(
            'totalReviewCount',
            'layoutTitle',
            'breadcum',
            'users',
            'typeOfServices',
            'typeOfPets',
            'reviews',
            'homeServicesInfo',
            'videoChatAvail',
            'typeOfServicesRates',
            'disabledDaysForService',
            'disabledDaysForVirtualService',
            'requests',
            'orderCount'
        ));
    }
	
	public function getserviceworkdays()
    {
        // Ensure this is an AJAX request
        $this->request->allowMethod(['post', 'ajax']);

        // Check if user is logged in
        if (empty($this->request->getSession()->read('RitevetUsers.id'))) {
            throw new UnauthorizedException('User must be logged in');
            // Alternatively, for redirect: return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Get data from request
        $serviceId = $this->request->getData('service_id');
        $userId = $this->request->getData('user_id');

        // Set response format to JSON
        $this->response = $this->response->withType('application/json');
        $response = ['Status' => 'Success'];

        if ($serviceId != 16) {
            $typeOfServicesRateTable = $this->fetchTable('Typeofservicesrate');
            $serviceWorkDays = $typeOfServicesRateTable->find()
                ->where([
                    'Typeofservicesrate.userId' => $userId,
                    'Typeofservicesrate.typeofservice_id' => $serviceId
                ])
                ->first();

            $response['service_work_days'] = $serviceWorkDays->service_work_days ?? '';
        } else {
            $videoChatAvailabilityTable = $this->fetchTable('Videochatavailability');
            $serviceWorkDays = $videoChatAvailabilityTable->find()
                ->where(['Videochatavailability.userId' => $userId])
                ->toArray();

            $workDays = [];
            foreach ($serviceWorkDays as $serviceWorkDay) {
                $days = [];
                if ($serviceWorkDay->SAT == 1) $days[] = 'SAT';
                if ($serviceWorkDay->SUN == 1) $days[] = 'SUN';
                if ($serviceWorkDay->MON == 1) $days[] = 'MON';
                if ($serviceWorkDay->TUE == 1) $days[] = 'TUE';
                if ($serviceWorkDay->WED == 1) $days[] = 'WED';
                if ($serviceWorkDay->THU == 1) $days[] = 'THU';
                if ($serviceWorkDay->FRI == 1) $days[] = 'FRI';

                $workDays = array_merge($workDays, $days);
            }

            $workDays = array_unique($workDays);
            $response['service_work_days'] = implode(',', $workDays);
        }

        // Send JSON response
        return $this->response->withStringBody(json_encode($response));
    }
    
    public function getbusinessworkdays()
    {
        if ($this->request->allowMethod(['post', 'ajax'])) 
        {
            $this->response = $this->response->withType('application/json');
            $userId = $this->request->getData('user_id');
            $typeOfBu = $this->request->getData('service_id');

            if ($typeOfBu == 3) {
                $videoChatAvailabilityTable = $this->fetchTable('Videochatavailability');
                $businessWorkDays = $videoChatAvailabilityTable->find()
                    ->where(['Videochatavailability.userId' => $userId])
                    ->toArray();

                $workDays = [];
                foreach ($businessWorkDays as $businessWorkDay) {
                    $days = [];
                    if ($businessWorkDay->SAT == 1) $days[] = 'SAT';
                    if ($businessWorkDay->SUN == 1) $days[] = 'SUN';
                    if ($businessWorkDay->MON == 1) $days[] = 'MON';
                    if ($businessWorkDay->TUE == 1) $days[] = 'TUE';
                    if ($businessWorkDay->WED == 1) $days[] = 'WED';
                    if ($businessWorkDay->THU == 1) $days[] = 'THU';
                    if ($businessWorkDay->FRI == 1) $days[] = 'FRI';
                    $workDays = array_merge($workDays, $days);
                }
                $workDays = array_unique($workDays);
                return $this->response->withStringBody(json_encode(implode(',', $workDays)));
            } else {
                $mobileAvailabilityTable = $this->fetchTable('Mobileavailability');
                $businessWorkDays = $mobileAvailabilityTable->find()
                    ->where(['Mobileavailability.userId' => $userId])
                    ->first();

                if ($businessWorkDays && $businessWorkDays->id) {
                    $days = [];
                    if ($businessWorkDays->SAT == 1) $days[] = 'SAT';
                    if ($businessWorkDays->SUN == 1) $days[] = 'SUN';
                    if ($businessWorkDays->MON == 1) $days[] = 'MON';
                    if ($businessWorkDays->TUE == 1) $days[] = 'TUE';
                    if ($businessWorkDays->WED == 1) $days[] = 'WED';
                    if ($businessWorkDays->THU == 1) $days[] = 'THU';
                    if ($businessWorkDays->FRI == 1) $days[] = 'FRI';
                    return $this->response->withStringBody(json_encode(implode(',', $days)));
                }
            }
            return $this->response->withStringBody(json_encode(''));
        }
    }
    
    public function makeRequestToServiceProvider()
    {
        if (!$this->request->getSession()->read('RitevetUsers.id')) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $typeofServicesTable = $this->fetchTable('Typeofservices');
        $typeofBusinessTable = $this->fetchTable('Typeofbusines');
        $requestsTable = $this->fetchTable('Requests');
        $usersTable = $this->fetchTable('Users');

        if ($this->request->is('post')) 
        {
            $requestData = array_filter($this->request->getData(), fn($value) => !empty($value));

            $timezone = $requestData['timezone'] ?? null;
            $typeOfBu = $requestData['typeOfBu'] ?? null;

            $datesString = $requestData['startDate'] ?? '';
            $dates = explode(',', $datesString);
            $formattedDates = '';
            foreach ($dates as $date) {
                $date = trim($date);
                if (!empty($date)) {
                    [$month, $day, $year] = explode('-', $date);
                    $formattedDate = sprintf('%s-%s-%s', $year, $month, $day);
                    $formattedDates .= $formattedDates ? ',' . $formattedDate : $formattedDate;
                }
            }

            $errors = [];
            if (empty($requestData['service_id'])) {
                $errors[] = 'Please select a service.';
            }

            if (in_array($requestData['service_id'], [16, 3])) {
                $requestedServiceDate = $this->formatDateString($requestData['requestedServiceDate'] ?? '');
                $timeSlot = $requestData['time_slot'] ?? null;

                $existingRequest = $requestsTable->find()
                    ->select(['id', 'sender', 'status'])
                    ->where([
                        'OR' => [
                            [
                                'Requests.requested_service_date' => $requestedServiceDate,
                                'Requests.time_slot' => $timeSlot,
                                'Requests.status !=' => '3'
                            ],
                            [
                                'Requests.service_id' => $requestData['service_id'],
                                'Requests.sender' => $this->request->getSession()->read('RitevetUsers.id'),
                                'Requests.requested_service_date' => $requestedServiceDate,
                                'Requests.time_slot' => $timeSlot,
                                'Requests.status !=' => '3'
                            ]
                        ]
                    ])
                    ->first();

                if ($existingRequest) {
                    if ($existingRequest->sender == $this->request->getSession()->read('RitevetUsers.id')) {
                        $errors[] = 'You have already made a request for this date and time slot.';
                    } else {
                        $errors[] = 'This time slot is already booked by another user for the selected date.';
                    }
                }
            }

            if ($requestData['service_id'] == 2 || in_array($requestData['service_id'], range(7, 15))) {
                $requestedServiceDate = $this->formatDateString($requestData['requestedServiceDate'] ?? '');
                $prefereTimes = $requestData['prefere_times'] ?? null;

                if (empty($requestedServiceDate)) {
                    $multiDates = $formattedDates;
                    $datesArray = array_map('trim', explode(',', $multiDates));

                    foreach ($datesArray as $date) {
                        if (!empty($date)) {
                            $existingRequest = $requestsTable->find()
                                ->where([
                                    'Requests.sender' => $this->request->getSession()->read('RitevetUsers.id'),
                                    'Requests.prefere_times' => $prefereTimes,
                                    'Requests.service_id' => $requestData['service_id'],
                                    'Requests.status !=' => '3',
                                    'OR' => [
                                        'Requests.requested_service_date' => $date,
                                        'Requests.multi_date LIKE' => '%' . $date . '%'
                                    ]
                                ])
                                ->first();

                            if ($existingRequest) {
                                $errors[] = 'You have already made a request for one of the selected dates and preferred times.';
                                break;
                            }
                        }
                    }
                } else {
                    $existingRequest = $requestsTable->find()
                        ->where([
                            'Requests.sender' => $this->request->getSession()->read('RitevetUsers.id'),
                            'Requests.prefere_times' => $prefereTimes,
                            'Requests.service_id' => $requestData['service_id'],
                            'Requests.status !=' => '3',
                            'OR' => [
                                'Requests.requested_service_date' => $requestedServiceDate,
                                'Requests.multi_date LIKE' => '%' . $requestedServiceDate . '%'
                            ]
                        ])
                        ->first();

                    if ($existingRequest) {
                        $errors[] = 'You have already made a request for this date and preferred times.';
                    }
                }
            }

            if (!empty($errors)) {
                $this->Flash->error(implode('<br>', $errors), ['escape' => false]);
                return $this->redirect([
                    'action' => $requestData['UTYPE'] == 3 ? 'serviceProviderDetail' : 'veterinarydetail',
                    base64_encode($requestData['serviceProviderId']),
                    $typeOfBu
                ]);
            }

            $request = $requestsTable->newEmptyEntity();
            $request = $requestsTable->patchEntity($request, [
                'serviceProvider' => $requestData['userId'],
                'sender' => $this->request->getSession()->read('RitevetUsers.id'),
                'UTYPE' => $requestData['UTYPE'],
                'service_id' => $requestData['service_id'],
                'time_slot' => $requestData['time_slot'] ?? null,
                'time_slot_UTC' => isset($requestData['time_slot']) ? $this->convertTimeSlotToUTC($requestData['time_slot'], $timezone) : null,
                'frequency' => $requestData['frequency'] ?? null,
                'requested_service_date' => $this->formatDateString($requestData['requestedServiceDate'] ?? ''),
                'multi_date' => $formattedDates ?: null,
                'status' => 0,
                'sender_address' => $requestData['address'] ?? null,
                'sender_zipcode' => $requestData['zipcode'] ?? null,
                'pet_type' => $requestData['pet_type'] ?? null,
                'dog_size' => $requestData['dog_size'] ?? null,
                'prefere_times' => $requestData['prefere_times'] ?? null,
                'comment' => $requestData['comment'] ?? null,
                'added_from' => 'WEB'
            ]);

            if ($requestsTable->save($request)) {
                $sender = $usersTable->find()
                    ->where(['Users.id' => $this->request->getSession()->read('RitevetUsers.id')])
                    ->firstOrFail();
                $provider = $usersTable->find()
                    ->where(['Users.id' => $requestData['userId']])
                    ->firstOrFail();
                $service = $requestData['UTYPE'] == 3
                    ? $typeofServicesTable->find()->where(['Typeofservices.id' => $requestData['service_id']])->firstOrFail()
                    : $typeofBusinessTable->find()->where(['Typeofbusines.id' => $requestData['service_id']])->firstOrFail();

                $to = $provider->email;
                $subject = "Ritevet Booking Request";
                $message = "Dear " . ucfirst($provider->firstName) . " " . ucfirst($provider->lastName) . "<br>";
                $message .= "We hope this email finds you well.<br>";
                $message .= "You have received a new service request from " . $sender->firstName . " for " . $service->name . ". Below are the details of the request:<br>";
                $message .= $requestData['UTYPE'] == 3 ? "<strong>Service Name</strong>: " . $service->name . "<br>" : "<strong>Business Name</strong>: " . $service->name . "<br>";
                $message .= "<strong>Booking Date: </strong>: " . (!empty($requestData['requestedServiceDate']) ? $requestData['requestedServiceDate'] : $requestData['startDate']) . "<br>";
                if ($request->time_slot_UTC) {
                    $message .= "<strong>Appointment Time</strong>: " . $request->time_slot_UTC . " UTC Time<br><br>";
                }
                $message .= "You can view the request and respond to the user by logging into your account on our platform.<br><br>";
                $message .= "Thank you for using our platform, and we look forward to your response.<br>";
                $message .= "Best regards,<br>Ritevet Team";

                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";

                $this->phpemail($to, $subject, $message);

                $this->Flash->success('Request has been sent successfully.');
                return $this->redirect([
                    'action' => $requestData['UTYPE'] == 3 ? 'serviceProviderDetail' : 'veterinarydetail',
                    base64_encode($requestData['serviceProviderId']),
                    $typeOfBu
                ]);
            }

            $this->Flash->error('Error sending request');
            return $this->redirect([
                'action' => $requestData['UTYPE'] == 3 ? 'serviceProviderDetail' : 'veterinarydetail',
                base64_encode($requestData['serviceProviderId']),
                $typeOfBu
            ]);
        }
    }
    
    public function requestedappointments()
    {
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'Ritevet - Requested Appointments';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'Requested Appointments',
            'URL' => [
                'Home' => $url,
                'Dashboard' => $url . 'users/dashboard',
                'Requested Appointments' => $url . 'users/requestedappointments/'
            ]
        ];

        // Check if user is logged in
        if (empty($this->request->getSession()->read('RitevetUsers.id'))) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Use fetchTable to get the Requests table
        $requestsTable = $this->fetchTable('Requests');

        // Base pagination settings
        $this->paginate = [
            'limit' => 8,
            'order' => [
                'Requests.created' => 'DESC'
            ],
        ];

        // Base condition
        $conditions = [
            'Requests.serviceProvider' => $this->request->getSession()->read('RitevetUsers.id')
        ];

        // Build the query with contain
        $query = $requestsTable->find('all')
            ->contain(['SenderUsers', 'ServiceProviderUsers', 'Bookingservices'])
            ->where($conditions);

        // Handle GET request with status filter
        if ($this->request->is('get')) {
            $status = $this->request->getQuery('status');
            if (!empty($status)) {
                $query->where(['Requests.status' => $status]);
            }
        }

        // Paginate the query
        $requests = $this->paginate($query);

        // Process each request
        foreach ($requests as $request) {
            $updatedAt = $request->modified;
            $currentTime = time();
            $updatedAtTime = strtotime($updatedAt);
            $diffInHours = ($currentTime - $updatedAtTime) / 3600;

            // Check if requested_service_date is empty
            if (empty($request->requested_service_date)) {
                $multiDates = explode(',', $request->multi_date);
                $firstDate = trim($multiDates[0]);
                $requestedServiceDate = strtotime($firstDate);
            } else {
                $requestedServiceDate = strtotime($request->requested_service_date);
            }

            // Update status if the service date has passed
            if ($currentTime > $requestedServiceDate && ($request->status == 0 || $request->status == 1)) {
                $request->status = 3;
                $request->modified = date('Y-m-d H:i:s');
                $requestsTable->save($request);
                continue;
            }

            // Update status if 48 hours have passed since modification
            if ($diffInHours >= 48 && ($request->status == 0 || $request->status == 1)) {
                $request->status = 3;
                $request->modified = date('Y-m-d H:i:s');
                $requestsTable->save($request);
            }
        }

        // Set variables for the view
        $this->set(compact('layoutTitle', 'breadcum', 'requests'));
    }
    
    public function requestedappointmentsdetail($id = null)
    {
        $this->viewBuilder()->setLayout('login');

        $layoutTitle = 'Ritevet - Requested Appointments Details';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'Requested Appointments Details',
            'URL' => [
                'Home' => $url,
                'Requested Appointments' => $url . 'users/requestedappointments/',
                'Requested Appointments Detail' => ''
            ]
        ];

        // Check if user is logged in
        if (empty($this->request->getSession()->read('RitevetUsers.id'))) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Validate $id
        if (empty($id)) {
            return $this->redirect(['controller' => 'Users', 'action' => 'requestedappointments']);
        }

        // Load tables using fetchTable
        $requestsTable = $this->fetchTable('Requests');
        $bookingsTable = $this->fetchTable('Bookings');

        // Define conditions
        $conditions = [
            'Requests.id' => $id,
            'Requests.serviceProvider' => $this->request->getSession()->read('RitevetUsers.id'),
        ];

        // Fetch request with associations
        $request = $requestsTable->find()
            ->where($conditions)
            ->contain([
                'SenderUsers' => ['Usersinformations'],
                'ServiceProviderUsers' => ['Usersinformations'],
                'Bookingservices' => function ($q) {
                    return $q->where(['Bookingservices.status' => '1']);
                },
                'Bookingbusiness' => function ($q) {
                    return $q->where(['Bookingbusiness.status' => '1']);
                },
                'Typeofpets' => function ($q) {
                    return $q->where(['Typeofpets.status' => '1']);
                }
            ])
            ->first();

        // Redirect if no request found
        if (!$request) {
            return $this->redirect(['controller' => 'Users', 'action' => 'requestedappointments']);
        }

        // Check payment and chat eligibility
        $chat = false;
        $booking = $bookingsTable->find()
            ->where([
                'Bookings.request_id' => $id,
                'OR' => [
                    'Bookings.vendorId' => $this->request->getSession()->read('RitevetUsers.id'),
                    'Bookings.userId' => $this->request->getSession()->read('RitevetUsers.id')
                ]
            ])
            ->first();

        if ($booking && $request->status == 4) {
            $chat = true;
        }

        // Set variables for the view
        $this->set(compact('layoutTitle', 'breadcum', 'request', 'chat'));
    }

    public function sentappointments()
    {
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'Ritevet - Appointments Sent';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'Appointments Sent',
            'URL' => [
                'Home' => $url,
                'Dashboard' => $url . 'users/dashboard',
                'Appointments Sent' => $url . 'users/sentappointments/'
            ]
        ];

        // Check if user is logged in
        if (empty($this->request->getSession()->read('RitevetUsers.id'))) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Load the Requests table
        $requestsTable = $this->fetchTable('Requests');

        // Base pagination settings
        $this->paginate = [
            'limit' => 8,
            'order' => [
                'Requests.created' => 'DESC'
            ],
        ];

        // Base condition
        $conditions = [
            'Requests.sender' => $this->request->getSession()->read('RitevetUsers.id')
        ];

        // Build the query with contain
        $query = $requestsTable->find('all')
            ->contain(['SenderUsers', 'ServiceProviderUsers', 'Bookingservices'])
            ->where($conditions);

        // Handle GET request with status filter
        if ($this->request->is('get')) {
            $status = $this->request->getQuery('status');
            if (!empty($status)) {
                $query->where(['Requests.status' => $status]);
            }
        }

        // Paginate the query
        $requests = $this->paginate($query);

        // Process each request
        foreach ($requests as $request) {
            $updatedAt = $request->modified;
            $currentTime = time();
            $updatedAtTime = strtotime($updatedAt);
            $diffInHours = ($currentTime - $updatedAtTime) / 3600;

            // Check if requested_service_date is empty
            if (empty($request->requested_service_date)) {
                $multiDates = explode(',', $request->multi_date);
                $firstDate = trim($multiDates[0]);
                $requestedServiceDate = strtotime($firstDate);
            } else {
                $requestedServiceDate = strtotime($request->requested_service_date);
            }

            // Update status if the service date has passed
            if ($currentTime > $requestedServiceDate && ($request->status == 0 || $request->status == 1)) {
                $request->status = 3;
                $request->modified = date('Y-m-d H:i:s');
                $requestsTable->save($request);
                continue;
            }

            // Update status if 48 hours have passed since modification
            if ($diffInHours >= 48 && ($request->status == 0 || $request->status == 1)) {
                $request->status = 3;
                $request->modified = date('Y-m-d H:i:s');
                $requestsTable->save($request);
            }
        }

        // Set variables for the view
        $this->set(compact('layoutTitle', 'breadcum', 'requests'));
    }
    
    public function sentappointmentsdetail($id = null)
    {
        $this->viewBuilder()->setLayout('login');

        $layoutTitle = 'Ritevet - Appointments Sent Details';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'Appointments Sent Details',
            'URL' => [
                'Home' => $url,
                'Appointments Sent' => $url . 'users/sentappointments/',
                'Appointments Sent Detail' => ''
            ]
        ];

        // Check if user is logged in
        if (empty($this->request->getSession()->read('RitevetUsers.id'))) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Validate $id
        if (empty($id)) {
            return $this->redirect(['controller' => 'Users', 'action' => 'sentappointments']);
        }

        // Load tables using fetchTable
        $requestsTable = $this->fetchTable('Requests');
        $bookingsTable = $this->fetchTable('Bookings');

        // Define conditions
        $conditions = [
            'Requests.id' => $id,
            'Requests.sender' => $this->request->getSession()->read('RitevetUsers.id'),
        ];

        // Fetch request with associations
        $request = $requestsTable->find()
            ->where($conditions)
            ->contain([
                'ServiceProviderUsers' => ['Usersinformations'],
                'SenderUsers',
                'Videochatavailability',
                'Mobileavailability',
                'Bookingservices' => function ($q) {
                    return $q->where(['Bookingservices.status' => '1']);
                },
                'Bookingbusiness' => function ($q) {
                    return $q->where(['Bookingbusiness.status' => '1']);
                },
                'Servicerates' => function ($q) {
                    return $q->where(['Servicerates.userId = ServiceProviderUsers.id']);
                },
                'Typeofpets' => function ($q) {
                    return $q->where(['Typeofpets.status' => '1']);
                }
            ])
            ->first();

        // Redirect if no request found
        if (!$request) {
            return $this->redirect(['controller' => 'Users', 'action' => 'sentappointments']);
        }

        // Check payment and chat eligibility
        $chat = false;
        $booking = $bookingsTable->find()
            ->where([
                'Bookings.request_id' => $id,
                'OR' => [
                    'Bookings.vendorId' => $this->request->getSession()->read('RitevetUsers.id'),
                    'Bookings.userId' => $this->request->getSession()->read('RitevetUsers.id')
                ]
            ])
            ->first();

        if ($booking && $request->status == 4) {
            $chat = true;
        }

        // Set variables for the view
        $this->set(compact('layoutTitle', 'breadcum', 'request', 'chat'));
    }
	
	public function acceptrequestedappointments($id = null)
    {
        // Check if user is logged in
        if (empty($this->request->getSession()->read('RitevetUsers.id'))) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Load Requests table
        $requestsTable = $this->fetchTable('Requests');

        // Fetch the request
        $request = $requestsTable->find()
            ->where([
                'Requests.id' => $id,
                'Requests.serviceProvider' => $this->request->getSession()->read('RitevetUsers.id')
            ])
            ->contain([
                'SenderUsers',
                'ServiceProviderUsers',
                'Videochatavailability',
                'Mobileavailability',
                'Bookingservices' => function ($q) {
                    return $q->where(['Bookingservices.status' => '1']);
                },
                'Bookingbusiness' => function ($q) {
                    return $q->where(['Bookingbusiness.status' => '1']);
                },
                'Servicerates' => function ($q) {
                    return $q->where(['Servicerates.userId' => $this->request->getSession()->read('RitevetUsers.id')]);
                }
            ])
            ->first();

        // Check if request exists and is in pending state (status 0)
        if ($request && $request->status == 0) {
            // Update the request status
            $requestsTable->patchEntity($request, [
                'status' => 1,
                'modified' => date('Y-m-d H:i:s'),
            ]);
            if ($requestsTable->save($request)) {
                // Determine the request date
                $requestDate = !empty($request->requested_service_date)
                    ? $request->requested_service_date
                    : (!empty($request->multi_date) ? $request->multi_date : '');

                // Adjust rate for multi-day bookings
                if (!empty($request->multi_date) && $request->frequency === 'Multi-Day') {
                    $datesArray = explode(',', $request->multi_date);
                    $numberOfDates = count($datesArray);
                    if ($request->servicerate) {
                        $request->servicerate->final_rate = $request->servicerate->final_rate * $numberOfDates;
                    }
                }

                // Build payment URL
                $url = Router::url('/', true);
                $urlParams = [
                    base64_encode($request->service_provider_user->id),
                    base64_encode($request->sender_user->id),
                    base64_encode($request->UTYPE),
                ];

                if ($request->UTYPE == 3) {
                    if ($request->bookingservice->id == 16) {
                        $urlParams = array_merge($urlParams, [
                            base64_encode($request->bookingservice->id),
                            base64_encode($request->bookingservice->name),
                            base64_encode($request->video_chat_availability->fees ?? 0),
                            base64_encode($request->video_chat_availability->price ?? 0),
                            base64_encode($request->video_chat_availability->total_price ?? 0),
                            base64_encode($requestDate),
                        ]);
                    } else {
                        $urlParams = array_merge($urlParams, [
                            base64_encode($request->bookingservice->id),
                            base64_encode($request->bookingservice->name),
                            base64_encode($request->servicerate->tax ?? 0),
                            base64_encode($request->servicerate->rate ?? 0),
                            base64_encode($request->servicerate->final_rate ?? 0),
                            base64_encode($requestDate),
                        ]);
                    }
                } elseif ($request->UTYPE == 2) {
                    if ($request->bookingbusines->id == 3) {
                        $urlParams = array_merge($urlParams, [
                            base64_encode($request->bookingbusines->id),
                            base64_encode($request->bookingbusines->name),
                            base64_encode($request->video_chat_availability->fees ?? 0),
                            base64_encode($request->video_chat_availability->price ?? 0),
                            base64_encode($request->video_chat_availability->total_price ?? 0),
                            base64_encode($requestDate),
                        ]);
                    } else {
                        $urlParams = array_merge($urlParams, [
                            base64_encode($request->bookingbusines->id),
                            base64_encode($request->bookingbusines->name),
                            base64_encode($request->mobileavailability->fees ?? 0),
                            base64_encode($request->mobileavailability->mobileprice ?? 0),
                            base64_encode($request->mobileavailability->total_price ?? 0),
                            base64_encode($requestDate),
                        ]);
                    }
                }

                $paymentUrl = $url . 'users/payment/' . implode('/', $urlParams);
                $paymentUrl = htmlspecialchars(trim($paymentUrl));

                // Send email notification
                if (!empty($request->sender_user->id)) {
                    $to = $request->sender_user->email;
                    $subject = "Ritevet Request Accepted";
                    $message = "Dear " . ucfirst($request->sender_user->firstName);
                    $message .= '<br>Your appointment has been accepted.';
                    $message .= $request->UTYPE == 3
                        ? '<br><strong>Service Name</strong>: ' . h($request->bookingservice->name ?? '')
                        : '<br><strong>Business Name</strong>: ' . h($request->bookingbusines->name ?? '');
                    $message .= '<br><strong>Booking Date</strong>: ' . h(
                        !empty($request->requested_service_date)
                            ? date('F jS, Y', strtotime($request->requested_service_date))
                            : (!empty($request->multi_date) ? date('F jS, Y', strtotime(explode(',', $request->multi_date)[0])) : '')
                    );
                    $message .= '<br><a href="' . $paymentUrl . '" target="_blank" style="margin-left: 270px; margin-top: 10px; display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-align: center; text-decoration: none; border-radius: 5px; font-weight: bold; transition: background-color 0.3s;">Click here to pay</a>';

                    $this->phpemail($to, $subject, $message);
                }

                $this->Flash->success(__('Request accepted successfully.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'requestedappointmentsdetail', $request->id]);
            } else {
                $this->Flash->error(__('Failed to accept request.'));
            }
        } else {
            $this->Flash->error(__('Request not available or already processed.'));
        }

        return $this->redirect(['controller' => 'Users', 'action' => 'requestedappointmentsdetail', $id]);
    }
	
	public function rejectrequestedappointments($id = null)
    {
        // Check if user is logged in
        if (empty($this->request->getSession()->read('RitevetUsers.id'))) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Load Requests table
        $requestsTable = $this->fetchTable('Requests');

        // Fetch the request
        $request = $requestsTable->find()
            ->where([
                'Requests.id' => $id,
                'OR' => [
                    'Requests.serviceProvider' => $this->request->getSession()->read('RitevetUsers.id'),
                    'Requests.sender' => $this->request->getSession()->read('RitevetUsers.id'),
                ],
            ])
            ->contain(['SenderUsers', 'ServiceProviderUsers', 'Bookingservices'])
            ->first();

        // Check if request exists and is in pending state (status 0)
        if ($request && $request->status == 0) {
            // Update the request status
            $requestsTable->patchEntity($request, [
                'status' => 2,
                'modified' => date('Y-m-d H:i:s'),
            ]);

            if ($requestsTable->save($request)) {
                // Send email notification if sender exists
                if (!empty($request->sender_user->id)) {
                    $to = $request->sender_user->email;
                    $subject = "Ritevet Request Rejected";
                    $message = "Dear " . ucfirst($request->sender_user->firstName);
                    $message .= '<br>Your appointment has been rejected.';
                    $message .= $request->UTYPE == 3
                        ? '<br><strong>Service Name</strong>: ' . h($request->bookingservice->name ?? '')
                        : '<br><strong>Business Name</strong>: ' . h($request->bookingbusines->name ?? '');
                    $message .= '<br><strong>Booking Date</strong>: ' . h(
                        !empty($request->requested_service_date)
                            ? date('F jS, Y', strtotime($request->requested_service_date))
                            : (!empty($request->multi_date) ? date('F jS, Y', strtotime(explode(',', $request->multi_date)[0])) : '')
                    );

                    $this->phpemail($to, $subject, $message);
                }

                $this->Flash->success(__('Booking rejected successfully.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'requestedappointmentsdetail', $request->id]);
            } else {
                $this->Flash->error(__('Failed to reject request.'));
            }
        } else {
            $this->Flash->error(__('Request not available or already processed.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
    }
	
	public function cancelrequestedappointments($id = null)
    {
        // Check if user is logged in
        if (empty($this->request->getSession()->read('RitevetUsers.id'))) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Load tables
        $requestsTable = $this->fetchTable('Requests');
        $usersInformationsTable = $this->fetchTable('Usersinformations');

        // Fetch the request
        $request = $requestsTable->find()
            ->where([
                'Requests.id' => $id,
                'OR' => [
                    'Requests.serviceProvider' => $this->request->getSession()->read('RitevetUsers.id'),
                    'Requests.sender' => $this->request->getSession()->read('RitevetUsers.id'),
                ],
            ])
            ->contain(['SenderUsers', 'ServiceProviderUsers', 'Bookingservices'])
            ->first();

        // Check if request exists and is in an acceptable state (status 1 or 4)
        if ($request && in_array($request->status, [1, 4])) {
            // Handle refund logic for status 4
            if ($request->status == 4) {
                $firstDate = !empty($request->multi_date)
                    ? trim(explode(',', $request->multi_date)[0])
                    : trim($request->requested_service_date);

                $requestedDate = new \DateTime($firstDate);
                $currentDate = new \DateTime();
                $interval = $currentDate->diff($requestedDate);

                // Process refund regardless of time, but check for cancellation count update
                $refundResponse = $this->refundMoney($request->id);
                if ($refundResponse['status'] !== 'success') {
                    $this->Flash->error(__('Refund failed: ' . $refundResponse['msg']));
                    return $this->redirect(['controller' => 'Users', 'action' => 'requestedappointmentsdetail', $request->id]);
                }

                // Update cancellation count if within 24 hours
                if ($interval->days == 0 && $interval->h < 24) {
                    $serviceProviderData = $usersInformationsTable->find()
                        ->where(['Usersinformations.userId' => $request->service_provider_user->id])
                        ->first();

                    if ($serviceProviderData) {
                        $usersInformationsTable->patchEntity($serviceProviderData, [
                            'cancellation_times' => $serviceProviderData->cancellation_times + 1,
                            'modified' => date('Y-m-d H:i:s'),
                        ]);
                        $usersInformationsTable->save($serviceProviderData);
                    }
                }
            }

            // Update the request status
            $requestsTable->patchEntity($request, [
                'status' => 3,
                'cancelled_by' => $this->request->getSession()->read('RitevetUsers.id'),
                'cancelled_time' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ]);

            if ($requestsTable->save($request)) {
                // Send email notification if sender exists
                if (!empty($request->sender_user->id)) {
                    $to = $request->sender_user->email;
                    $subject = "Ritevet Request Cancelled";
                    $message = "Dear " . ucfirst($request->sender_user->firstName);
                    $message .= !empty($request->requested_service_date)
                        ? '<br>Your appointment with ' . h($request->service_provider_user->firstName) . ' ' . h($request->service_provider_user->lastName) . ' on ' . date('F jS, Y', strtotime($request->requested_service_date)) . ' has been canceled.'
                        : '<br>Your appointment with ' . h($request->service_provider_user->firstName) . ' ' . h($request->service_provider_user->lastName) . ' on ' . date('F jS, Y', strtotime(explode(',', $request->multi_date)[0])) . ' has been canceled.';
                    $message .= '<br><br>Thanks,';

                    $this->phpemail($to, $subject, $message);
                }

                $this->Flash->success(__('Booking canceled successfully.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'requestedappointmentsdetail', $request->id]);
            } else {
                $this->Flash->error(__('Failed to cancel request.'));
            }
        } else {
            $this->Flash->error(__('Request not available or cannot be canceled.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
    }
	
	public function cancelsentappointments($id = null)
    {
        // Check if user is logged in
        if (empty($this->request->getSession()->read('RitevetUsers.id'))) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Load tables
        $requestsTable = $this->fetchTable('Requests');
        $usersInformationsTable = $this->fetchTable('Usersinformations');
        $bookingsTable = $this->fetchTable('Bookings');

        // Fetch the request
        $request = $requestsTable->find()
            ->where([
                'Requests.id' => $id,
                'Requests.sender' => $this->request->getSession()->read('RitevetUsers.id'),
            ])
            ->contain(['SenderUsers', 'ServiceProviderUsers', 'Bookingservices'])
            ->first();

        // Check if request exists and is in an acceptable state (status 0, 1, or 4)
        if ($request && in_array($request->status, [0, 1, 4])) {
            // Handle refund or wallet update for status 4
            if ($request->status == 4) {
                $firstDate = !empty($request->multi_date)
                    ? trim(explode(',', $request->multi_date)[0])
                    : trim($request->requested_service_date);

                $requestedDate = new \DateTime($firstDate);
                $currentDate = new \DateTime();
                $interval = $currentDate->diff($requestedDate);

                if ($interval->days == 0 && $interval->h < 24) {
                    // Cancellation within 24 hours, money goes to provider
                    $serviceProviderData = $usersInformationsTable->find()
                        ->where(['Usersinformations.userId' => $request->service_provider_user->id])
                        ->first();
                    $requestBooking = $bookingsTable->find()
                        ->where(['Bookings.request_id' => $id])
                        ->first();

                    if ($serviceProviderData && $requestBooking) {
                        $usersInformationsTable->patchEntity($serviceProviderData, [
                            'wallet' => $serviceProviderData->wallet + $requestBooking->totalAmount,
                            'modified' => date('Y-m-d H:i:s'),
                        ]);
                        $usersInformationsTable->save($serviceProviderData);
                    }
                } else {
                    // Cancellation more than 24 hours, process refund
                    $refundResponse = $this->refundMoney($request->id);
                    if ($refundResponse['status'] !== 'success') {
                        $this->Flash->error(__('Refund failed: ' . $refundResponse['msg']));
                        return $this->redirect(['controller' => 'Users', 'action' => 'sentappointmentsdetail', $request->id]);
                    }
                }
            }

            // Update the request status
            $requestsTable->patchEntity($request, [
                'status' => 3,
                'cancelled_by' => $this->request->getSession()->read('RitevetUsers.id'),
                'cancelled_time' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ]);

            if ($requestsTable->save($request)) {
                // Send email notification if service provider exists
                if (!empty($request->service_provider_user->id)) {
                    $to = $request->service_provider_user->email;
                    $subject = "Ritevet Request Cancelled";
                    $message = "Dear " . ucfirst($request->service_provider_user->firstName);
                    $message .= !empty($request->requested_service_date)
                        ? '<br>Your appointment with ' . h($request->sender_users->firstName) . ' ' . h($request->sender_users->lastName) . ' on ' . date('F jS, Y', strtotime($request->requested_service_date)) . ' has been canceled.'
                        : '<br>Your appointment with ' . h($request->sender_users->firstName) . ' ' . h($request->sender_users->lastName) . ' on ' . date('F jS, Y', strtotime(explode(',', $request->multi_date)[0])) . ' has been canceled.';
                    $message .= '<br><br>Thanks,';

                    $this->phpemail($to, $subject, $message);
                }

                $this->Flash->success(__('Booking canceled successfully.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'sentappointmentsdetail', $request->id]);
            } else {
                $this->Flash->error(__('Failed to cancel request.'));
            }
        } else {
            $this->Flash->error(__('Request not available or cannot be canceled.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
    }
	
	private function refundMoney($requestId)
    {
        // Check if the user is logged in
        if (empty($this->request->getSession()->read('RitevetUsers.id'))) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Load tables
        $bookingsTable = $this->fetchTable('Bookings');
        $bookingsRefundedTable = $this->fetchTable('BookingsRefunded');

        $data = ['status' => 'fail', 'msg' => ''];

        try {
            // Attempt to find the booking data
            $bookingData = $bookingsTable->find()
                ->where(['Bookings.request_id' => $requestId])
                ->first();

            if (!$bookingData) {
                throw new \Exception('Booking not found for the given request ID.');
            }

            $chargeId = $bookingData->tokenId;
            $amount = $bookingData->totalAmount * 100; // Amount to refund in cents

            // Prepare the data for the refund request
            $fields = [
                'action'   => 'refund',
                'chargeId' => $chargeId, // The charge ID to refund
            ];

            // Define the refund URL
            $Furl = Router::url('/', true) . "strip_master/strip_master/StripeServices.php";

            // Initialize cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $Furl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/x-www-form-urlencoded',
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));

            // Execute the cURL request
            $result = curl_exec($ch);

            // Check for cURL errors
            if (curl_errno($ch)) {
                throw new \Exception('cURL error: ' . curl_error($ch));
            }

            curl_close($ch);
            $response = json_decode($result, true);

            // Check if response is valid
            if (!$response) {
                throw new \Exception('Invalid response from refund service.');
            }

            // Check the response status
            if (isset($response['status']) && $response['status'] === 'success') {
                $refundData = $bookingsRefundedTable->newEntity([
                    'bookingId' => $bookingData->id,
                    'refundId' => $response['refundId'] ?? null,
                    'chargeId' => $response['charge'] ?? $chargeId,
                    'amount' => ($response['amount'] ?? $amount) / 100,
                    'currency' => $response['currency'] ?? 'USD',
                    'status' => $response['status'],
                    'added_from' => 'WEB',
                    'created' => date('Y-m-d H:i:s'),
                ]);

                // Save the refund data
                if ($bookingsRefundedTable->save($refundData)) {
                    $bookingData = $bookingsTable->patchEntity($bookingData, ['status' => 3]);
                    if ($bookingsTable->save($bookingData)) {
                        $data['status'] = 'success';
                        $data['msg'] = 'Refund processed successfully.';
                        $data['refundId'] = $response['refundId'] ?? null;
                    } else {
                        throw new \Exception('Failed to update booking status.');
                    }
                } else {
                    throw new \Exception('Failed to save refund data.');
                }
            } else {
                throw new \Exception($response['msg'] ?? 'Refund failed due to an unknown error.');
            }
        } catch (\Exception $e) {
            $data['status'] = 'fail';
            $data['msg'] = 'An error occurred while processing the refund: ' . $e->getMessage();
        }

        return $data;
    }
    
	public function completerequestedappointments($id = null)
    {
        // Check if user is logged in
        if (empty($this->request->getSession()->read('RitevetUsers.id'))) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Load tables
        $requestsTable = $this->fetchTable('Requests');
        $usersInformationsTable = $this->fetchTable('Usersinformations');
        $bookingsTable = $this->fetchTable('Bookings');

        // Fetch the request
        $request = $requestsTable->find()
            ->where([
                'Requests.id' => $id,
                'Requests.serviceProvider' => $this->request->getSession()->read('RitevetUsers.id'),
            ])
            ->contain(['SenderUsers', 'ServiceProviderUsers', 'Bookingservices'])
            ->first();

        // Check if request exists and is in an acceptable state (status 4 or 6)
        if ($request && in_array($request->status, [4, 6])) {
            // Update the request status
            $requestsTable->patchEntity($request, [
                'status' => 5,
                'modified' => date('Y-m-d H:i:s'),
            ]);

            if ($requestsTable->save($request)) {
                // Update user wallet
                $serviceProviderData = $usersInformationsTable->find()
                    ->where(['Usersinformations.userId' => $request->service_provider_user->id])
                    ->first();
                $requestBooking = $bookingsTable->find()
                    ->where(['Bookings.request_id' => $id])
                    ->first();

                if ($serviceProviderData && $requestBooking) {
                    $usersInformationsTable->patchEntity($serviceProviderData, [
                        'wallet' => $serviceProviderData->wallet + $requestBooking->serviceFee,
                        'modified' => date('Y-m-d H:i:s'),
                    ]);
                    if (!$usersInformationsTable->save($serviceProviderData)) {
                        $this->Flash->error(__('Failed to update provider wallet.'));
                        return $this->redirect(['controller' => 'Users', 'action' => 'requestedappointmentsdetail', $request->id]);
                    }
                }

                // Send email notification if sender exists
                if (!empty($request->sender_user->id)) {
                    $to = $request->sender_user->email;
                    $subject = "Ritevet Request Completed";
                    $message = "Dear " . ucfirst($request->sender_user->firstName);
                    $message .= !empty($request->requested_service_date)
                        ? '<br>Your appointment with ' . h($request->service_provider_user->firstName) . ' ' . h($request->service_provider_user->lastName) . ' on ' . date('F jS, Y', strtotime($request->requested_service_date)) . ' has been completed.'
                        : '<br>Your appointment with ' . h($request->service_provider_user->firstName) . ' ' . h($request->service_provider_user->lastName) . ' on ' . date('F jS, Y', strtotime(explode(',', $request->multi_date)[0])) . ' has been completed.';
                    $message .= '<br><br>Thanks,';

                    $this->phpemail($to, $subject, $message);
                }

                $this->Flash->success(__('Booking completed successfully.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'requestedappointmentsdetail', $request->id]);
            } else {
                $this->Flash->error(__('Failed to complete request.'));
            }
        } else {
            $this->Flash->error(__('Request not available or cannot be completed.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
    }
	
	public function noShow($id = null)
    {
        // Check if the user is logged in
        if ($this->request->getSession()->read('RitevetUsers.id') === null) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Fetch the necessary tables
        $requestsTable = $this->fetchTable('Requests');

        // Fetch the request
        $request = $requestsTable->find()
            ->where(['Requests.id' => $id])
            ->andWhere(['Requests.sender' => $this->request->getSession()->read('RitevetUsers.id')])
            ->contain(['SenderUsers', 'ServiceProviderUsers', 'Bookingservices'])
            ->first();

        // Check if the request exists and its status
        if ($request && ($request->status == 4 || $request->status == 5)) {
            // Update the request status
            $request->status = 6;
            $request->modified = date('Y-m-d H:i:s'); // Use date function for datetime
            $requestsTable->save($request);

            // Send email to the service provider
            if (!empty($request->service_provider_user->id)) {
                $to = $request->service_provider_user->email;
                $subject = "Ritevet Request Noshow";
                $message = "Dear " . ucfirst($request->service_provider_user->firstName);
                if (empty($request->requested_service_date)) {
                    $message .= '<br>About your appointment with ' . $request->sender_user->firstName . ' ' . $request->sender_user->lastName . ' is on ' . date('F jS, Y', strtotime($request->multi_date)) . ', you didn\'t show.';
                } else {
                    $message .= '<br>About your appointment with ' . $request->sender_user->firstName . ' ' . $request->sender_user->lastName . ' is on ' . date('F jS, Y', strtotime($request->requested_service_date)) . ', you didn\'t show.';
                }
                $message .= '<br><br>We need to know the reason.';
                $message .= '<br><br>Thanks,';
                $this->phpemail($to, $subject, $message);
            }

            $this->Flash->success(__('Booking updated successfully.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'sentappointmentsdetail', $request->id]);
        } else {
            $this->Flash->error(__('Request not available.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
    }
	
    public function payment($service_provider_id, $user_id, $utype, $service_id, $service_name, $adminFee, $service_fee, $TOTALAMOUNT, $bookDate)
    {
        $this->viewBuilder()->setLayout('pages');
        $layoutTitle = 'Ritevet - Payment';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'Payment',
            'URL' => [
                'Home' => $url,
                'Payment' => $url . 'payment'
            ]
        ];

        $session = $this->request->getSession();
        $currentUserId = $session->read('RitevetUsers.id');

        if (empty($currentUserId)) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        if ($currentUserId != base64_decode($user_id)) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Decode parameters
        $service_provider_id = base64_decode($service_provider_id);
        $user_id = base64_decode($user_id);
        $utype = base64_decode($utype);
        $service_id = base64_decode($service_id);
        $service_name = base64_decode($service_name);
        $adminFee = base64_decode($adminFee);
        $service_fee = base64_decode($service_fee);
        $TOTALAMOUNT = base64_decode($TOTALAMOUNT);
        $bookDate = base64_decode($bookDate);

        // Load Requests table and find the request
        $requestsTable = $this->fetchTable('Requests');
        $request = $requestsTable->find()
            ->where([
                'Requests.serviceProvider' => $service_provider_id,
                'Requests.sender' => $user_id,
                'Requests.UTYPE' => $utype,
                'Requests.service_id' => $service_id,
                'Requests.status' => 1, // Only allow unpaid requests
            ])
            ->where(function (QueryExpression $exp, \Cake\ORM\Query\SelectQuery $query) use ($bookDate) {
                return $exp->or([
                    'Requests.requested_service_date' => $bookDate,
                    $query->func()->find_in_set([$bookDate, 'Requests.multi_date'])
                ]);
            })
            ->first();

        if (!$request) {
            $this->Flash->error(__('This request is invalid or already paid.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }

        $logginUserZipCode = $request->sender_zipcode ?? $session->read('RitevetUsers.zipCode') ?? '';

        $saleTax = 0;
        $total = 0;
        $paidAmount = 0;

        if ($this->request->is('post')) {
            try {
                // Start database transaction
                $connection = ConnectionManager::get('default');
                $connection->begin();

                $data = $this->request->getData();
                $saleTax = floatval($data['saleTax'] ?? 0);
                $total = floatval($data['total'] ?? 0);
                $paidAmount = floatval($TOTALAMOUNT) + $saleTax;

                if (abs($paidAmount - $total) > 0.01) { // Allow for small float differences
                    throw new \Exception('Total price mismatch.');
                }

                $totalAmount = $paidAmount * 100; // Convert to cents

                // Prepare payment entity
                $bookingsTable = $this->fetchTable('Bookings');
                $payment = $bookingsTable->newEntity([
                    'request_id' => $request->id,
                    'vendorId' => $service_provider_id,
                    'userId' => $currentUserId,
                    'UTYPE' => $utype,
                    'typeOfServices' => ($utype == 3) ? $service_id : null,
                    'typeofbusinessId' => ($utype == 2) ? $service_id : null,
                    'serviceFee' => floatval($service_fee),
                    'adminFee' => floatval($adminFee),
                    'sale_tax' => $saleTax,
                    'totalAmount' => $totalAmount / 100,
                    'currency' => 'USD', // Default, updated later after payment
                    'bookingDate' => date('Y-m-d', strtotime($bookDate)),
                    'status' => 2,
                    'tokenId' => '', // Placeholder
                    'transactionId' => '', // Placeholder
                    'payment_mode' => 'Test', // Placeholder
                    'paymentThrough' => 'WEB',
                    'card_holder_name' => $data['name'] ?? '',
                    'created' => date('Y-m-d H:i:s'),
                    'modified' => date('Y-m-d H:i:s'),
                ]);

                if ($payment->hasErrors()) {
                    throw new \Exception('Invalid payment data: ' . json_encode($payment->getErrors()));
                }

                // Process payment via Stripe
                $fields = [
                    'action' => 'charge',
                    'name' => $data['name'] ?? '',
                    'stripeToken' => $data['stripeToken'] ?? '',
                    'amount' => $totalAmount,
                    'userId' => $currentUserId,
                    'description' => 'Payment for userId: ' . $currentUserId . ' - Service: ' . $service_name,
                ];

                $Furl = $url . "strip_master/strip_master/StripeServices.php";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $Furl);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/x-www-form-urlencoded',
                ]);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));

                $result = curl_exec($ch);
                if ($result === false) {
                    throw new \Exception('Payment gateway error: ' . curl_error($ch));
                }
                curl_close($ch);

                $DecondeResult = json_decode($result, true);
                if (!$DecondeResult || !isset($DecondeResult['status']) || $DecondeResult['status'] !== 'success') {
                    $errorMessage = $DecondeResult['message'] ?? 'Unknown error occurred during payment processing';
                    throw new \Exception('Payment failed: ' . $errorMessage);
                }

                // Update payment entity with Stripe details
                $payment->tokenId = $DecondeResult['tokenId'] ?? '';
                $payment->transactionId = $DecondeResult['transactionID'] ?? '';
                $payment->payment_mode = $DecondeResult['livemode'] ? 'Live' : 'Test';
                $payment->currency = strtoupper($DecondeResult['currency'] ?? 'USD');

                // Save payment
                if (!$bookingsTable->save($payment)) {
                    throw new \Exception('Failed to save payment record.');
                }

                // Update request status
                $request = $requestsTable->patchEntity($request, [
                    'status' => 4,
                    'modified' => date('Y-m-d H:i:s'),
                ]);
                if (!$requestsTable->save($request)) {
                    throw new \Exception('Failed to update request status.');
                }

                // Send email notification
                $usersTable = $this->fetchTable('Users');
                $serviceProviderData = $usersTable->find()
                    ->where(['Users.id' => $service_provider_id])
                    ->first();

                if ($serviceProviderData) {
                    $to = $serviceProviderData->email;
                    $subject = "Ritevet Request Paid";
                    $message = "Dear " . ucfirst($serviceProviderData->firstName) . ",";
                    $message .= "<br>Payment has been successfully received from the client for the booking.";
                    $message .= "<br><strong>Service Name</strong>: " . h($service_name);
                    $message .= "<br><strong>Payment Amount</strong>: $" . number_format($paidAmount, 2);
                    $message .= "<br><strong>Payment Date</strong>: " . h($payment->created);
                    $message .= "<br>Thank you for providing your services. Please ensure to deliver the services as scheduled.";
                    $this->phpemail($to, $subject, $message);
                }

                $connection->commit();
                $this->Flash->success(__('Your payment was successful.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);

            } catch (\Exception $e) {
                $connection->rollback();
                $this->Flash->error(__('Payment processing failed: ' . $e->getMessage()));
                return $this->redirect($this->referer());
            }
        }

        $this->set(compact('logginUserZipCode', 'layoutTitle', 'breadcum', 'request', 'service_provider_id', 'user_id', 'utype', 'service_id', 'service_name', 'adminFee', 'service_fee', 'TOTALAMOUNT', 'bookDate'));
    }

    public function earnamount()
    {
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'Ritevet - Earnings';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'Earnings',
            'URL' => [
                'Home' => $url,
                'Dashboard' => $url . 'users/dashboard',
                'Earnings' => $url . 'users/earnamount'
            ]
        ];

        // Check if the user is logged in
        if ($this->request->getSession()->read('RitevetUsers.id') === null) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Fetch the necessary tables
        $usersInformationsTable = $this->fetchTable('Usersinformations');
        $cashoutsTable = $this->fetchTable('Cashouts');
        $usersTable = $this->fetchTable('Users');

        $userId = $this->request->getSession()->read('RitevetUsers.id');
        $user = $usersTable->find()->where(['id' => $userId])->first();
        $userInfo = $usersInformationsTable->find()->where(['userId' => $userId])->first();

        // Function to calculate cashout amounts
        $calculateCashoutAmount = function ($type) use ($cashoutsTable, $userId) {
            $query = $cashoutsTable->find()
                ->where(['Cashouts.type' => $type, 'Cashouts.userId' => $userId]);
            $result = $query->select(['sumamount' => $query->func()->sum('approved_amount')])->first();
            return round($result && isset($result->sumamount) ? $result->sumamount : 0, 2); // Default to 0 if no result
        };

        // Calculate cashout amounts for different types
        $product_cashout_amount = $calculateCashoutAmount(1);
        $vateti_cashout_amount = $calculateCashoutAmount(2);
        $other_pet_cashout_amount = $calculateCashoutAmount(3);

        $this->set(compact('layoutTitle', 'breadcum', 'user', 'userInfo', 'vateti_cashout_amount', 'other_pet_cashout_amount', 'product_cashout_amount'));
    }
	
	public function cashoutrequest($type)
    {
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'Ritevet - Cashout';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'Cashout',
            'URL' => [
                'Home' => $url,
                'Earnings' => $url . 'users/earnamount',
                'Cashout' => $url . 'users/cashoutrequest'
            ]
        ];

        // Check if the user is logged in
        if ($this->request->getSession()->read('RitevetUsers.id') === null) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Fetch the necessary tables
        $usersInformationsTable = $this->fetchTable('Usersinformations');
        $cashoutsTable = $this->fetchTable('Cashouts');
        $usersTable = $this->fetchTable('Users');

        // Fetch user information based on the cashout type
        $userId = $this->request->getSession()->read('RitevetUsers.id');
        $getInfo = $usersInformationsTable->find()
            ->where(['userId' => $userId])
            ->contain(['Users'])
            ->first();

        // Validate bank account details
        if ((empty($getInfo->AccountNo) || empty($getInfo->RoutingNo))) {
            $this->Flash->error(__('Please fill the bank account details to send the cashout request.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'earnamount']);
        }

        if ($this->request->is('post')) {
            $this->request = $this->request->withData('userId', $userId);
            $this->request = $this->request->withData('type', $type);

            $cashout = $cashoutsTable->newEmptyEntity();
            $cashout = $cashoutsTable->patchEntity($cashout, $this->request->getData(), ['validate' => false]);
            $cashout->added_from = 'WED';

            if ($cashoutsTable->save($cashout)) {
                // Send notification to admin
                $to = 'support@ritevet.com';
                $subject = "Ritevet Cashout";
                $message = "Dear Admin,<br>";
                $message .= "One user has requested a cashout amount of <strong>$" . $this->request->getData('requested_amount') . "</strong>.<br>";
                $message .= "Please check details in the admin section and do the needful.";

                $this->phpemail($to, $subject, $message);

                $this->Flash->success(__('Cashout request has been sent successfully.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'earnamount']);
            }
        }

        $this->set(compact('layoutTitle', 'breadcum', 'type', 'getInfo'));
    }
    
	public function setVideoChatAvailability()
    {
        if ($this->request->is('post')) 
        {
            $userId = $this->request->getSession()->read('RitevetUsers.id');
            $utype = $this->request->getData('UTYPE');
            $timeSlotDuration = $this->request->getData('time_slot_duration');
            $price = $this->request->getData('price');
            $ADMINFEES = ($price * 0.16);
            $totalPrice = ceil($ADMINFEES + $price);

            $startTimes = $this->request->getData('startTime');
            $endTimes = $this->request->getData('endTime');
            $workdayArrays = $this->request->getData('workdayarray');
            
            // pr($this->request->getData());exit;
            // Fetch the Videochatavailability table
            $videoChatAvailabilityTable = $this->fetchTable('Videochatavailability');
            $existingSettings = $videoChatAvailabilityTable->find()->where(['userId' => $userId])->all();

            // Loop through each workday array
            foreach ($workdayArrays as $index => $workdayArray) {
                $startTime = $startTimes[$index];
                $endTime = $endTimes[$index];

                // Check if existing setting exists for this time slot
                $existingSetting = null;
                foreach ($existingSettings as $setting) {
                    if ($setting->startTime == date('H:i', strtotime($startTime)) && $setting->endTime == date('H:i', strtotime($endTime))) {
                        $existingSetting = $setting;
                        break;
                    }
                }

                // Create or update the setting
                if ($existingSetting) {
                    $setting = $existingSetting;
                } else {
                    $setting = $videoChatAvailabilityTable->newEmptyEntity();
                }

                $setting->userId = $userId;
                $setting->UTYPE = $utype;
                $setting->startTime = date('H:i', strtotime($startTime));
                $setting->endTime = date('H:i', strtotime($endTime));
                $setting->time_slot_duration = $timeSlotDuration;
                $setting->price = $price;
                $setting->fees = $ADMINFEES;
                $setting->total_price = $totalPrice;
                $setting->added_from = 'WED';

                // Update workdays
                $setting->MON = 0;
                $setting->TUE = 0;
                $setting->WED = 0;
                $setting->THU = 0;
                $setting->FRI = 0;
                $setting->SAT = 0;
                $setting->SUN = 0;

                foreach ($workdayArray as $workday) {
                    $setting->{$workday} = 1;
                }

                // Save the setting
                if ($existingSetting) {
                    $setting->modified = date('Y-m-d H:i:s');
                } else {
                    $setting->created = date('Y-m-d H:i:s');
                    $setting->modified = date('Y-m-d H:i:s');
                }

                $videoChatAvailabilityTable->save($setting);
            }
        }
    }
    
    public function deleteAvailability($id)
    {
        // Fetch the Videochatavailability table
        $videoChatAvailabilityTable = $this->fetchTable('Videochatavailability');

        // Find the availability record
        $availability = $videoChatAvailabilityTable->find()
            ->where(['Videochatavailability.id' => $id, 'Videochatavailability.userId' => $this->request->getSession()->read('RitevetUsers.id')])
            ->first();

        if ($availability) {
            // Delete the availability record
            if ($videoChatAvailabilityTable->delete($availability)) {
                $this->Flash->success(__('Availability has been deleted.'));
            } else {
                $this->Flash->error(__('Availability could not be deleted. Please, try again.'));
            }
        } else {
            $this->Flash->error(__('Availability not found or you do not have permission to delete it.'));
        }

        // Redirect based on the UTYPE
        if (isset($availability->UTYPE) && $availability->UTYPE == 2) {
            return $this->redirect(['controller' => 'Users', 'action' => 'veterinarianRegister']);
        } else {
            return $this->redirect(['controller' => 'Users', 'action' => 'otherPetServiceRegister']);
        }
    }
	
	public function setMobileAvailability()
    {
        if ($this->request->is('post')) 
        {
            $userId = $this->request->getSession()->read('RitevetUsers.id');
            $mobilePrice = $this->request->getData('mobileprice');
            $fees = $mobilePrice * 0.16;
            $totalPrice = ceil($fees + $mobilePrice);

            $data = [
                'userId' => $userId,
                'mobileprice' => $mobilePrice,
                'fees' => $fees,
                'total_price' => $totalPrice,
                'MON' => 0,
                'TUE' => 0,
                'WED' => 0,
                'THU' => 0,
                'FRI' => 0,
                'SAT' => 0,
                'SUN' => 0,
            ];

            $workDays = $this->request->getData('mobileworkdayarray');

            // Set workdays based on the provided array
            if (!empty($workDays)) {
                foreach ($workDays as $val) {
                    $day = strtoupper($val);
                    if (isset($data[$day])) {
                        $data[$day] = 1;
                    }
                }
            }

            // Fetch the Mobileavailability table
            $mobileAvailabilityTable = $this->fetchTable('Mobileavailability');
            $settings = $mobileAvailabilityTable->find()
                ->where(['userId' => $userId])
                ->first();

            if (empty($settings)) {
                $settings = $mobileAvailabilityTable->newEmptyEntity();
                $settings = $mobileAvailabilityTable->patchEntity($settings, $data, ['validate' => false]);
                $settings->added_from = 'WED';
                $settings->created = date('Y-m-d H:i:s');
                $settings->modified = date('Y-m-d H:i:s');
            } else {
                $settings = $mobileAvailabilityTable->patchEntity($settings, $data, ['validate' => false]);
                $settings->modified = date('Y-m-d H:i:s');
            }

            // Save the settings
            if ($mobileAvailabilityTable->save($settings)) {
                // $this->Flash->success(__('Mobile availability has been saved successfully.'));
            } else {
                $this->Flash->error(__('Unable to save mobile availability. Please try again.'));
            }
        }
    }
	
	public function setHomeServicesQuestions()
    {
        // Fetch the Homeservicesinfo table
        $homeservicesinfoTable = $this->fetchTable('Homeservicesinfo');

        // Get the current user ID from the session
        $userId = $this->request->getSession()->read('RitevetUsers.id');

        // Fetch existing home services records for the user
        $homeServices = $homeservicesinfoTable->find()
            ->where(['Homeservicesinfo.userId' => $userId])
            ->all();

        // Delete existing records if they exist
        if (!$homeServices->isEmpty()) {
            $homeservicesinfoTable->deleteAll(['Homeservicesinfo.userId' => $userId]);
        }

        // Process request data
        $requestData = $this->request->getData();
        if (empty($requestData)) {
            return; // Exit if no data is provided
        }

        // Iterate through request data to find answer fields
        foreach ($requestData as $key => $value) {
            if (strpos($key, 'answer_') === 0) { // Check if key starts with 'answer_'
                $questionId = str_replace('answer_', '', $key);
                $answer = $value;

                // Skip if answer is empty or invalid
                if (empty($answer) || !is_string($answer)) {
                    continue;
                }

                // Prepare data for the new entity
                $data = [
                    'userId' => $userId,
                    'question_id' => $questionId,
                    'answer' => $answer,
                    'added_from' => 'WEB',
                    'created' => date('Y-m-d H:i:s'),
                    'modified' => date('Y-m-d H:i:s')
                ];

                // Create and save the new entity
                $answerEntity = $homeservicesinfoTable->newEntity($data, ['validate' => false]);
                if (!$homeservicesinfoTable->save($answerEntity)) {
                }
            }
        }
    }
	
	public function chats($id = null)
    {
        // Set layout and title
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'Ritevet - Chats';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'Chats',
            'URL' => [
                'Home' => $url,
                'Chats' => ''
            ]
        ];

        // Check if the user is logged in
        if ($this->request->getSession()->read('RitevetUsers.id') === null) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Redirect if no appointment ID is provided
        if (empty($id)) {
            return $this->redirect(['controller' => 'Users', 'action' => 'requestedappointments']);
        }

        // Fetch the necessary tables
        $bookingsTable = $this->fetchTable('Bookings');
        $usersTable = $this->fetchTable('Users');

        // Define the condition for fetching the booking
        $condition = [
            "Bookings.request_id" => $id,
            "OR" => [
                "Bookings.vendorId" => $this->request->getSession()->read('RitevetUsers.id'),
                "Bookings.userId" => $this->request->getSession()->read('RitevetUsers.id')
            ]
        ];

        // Fetch the booking record
        $booking = $bookingsTable->find()
            ->where($condition)
            ->contain(['Users', 'Vendors' => ['Usersinformations']])
            ->first();

        // Determine the user details based on the booking
        if ($booking->userId == $this->request->getSession()->read('RitevetUsers.id')) {
            $usersD = $usersTable->find()->where(['Users.id' => $booking->vendorId])->first();
        } else {
            $usersD = $usersTable->find()->where(['Users.id' => $booking->userId])->first();
        }

        // Fetch sender and receiver avatars
        $senderAvt = $usersTable->find()->where(['Users.id' => $this->request->getSession()->read('RitevetUsers.id')])->first();
        $receiverAvt = $usersTable->find()->where(['Users.id' => $booking->vendorId])->first();

        // Prepare user lists (if needed)
        $userLists = [];

        // Set variables for the view
        $this->set(compact('layoutTitle', 'breadcum', 'usersD', 'userLists', 'receiverAvt', 'senderAvt'));
    }
    
    public function allchats()
    {
        // Set layout and title
        $this->viewBuilder()->setLayout('login');
        $layoutTitle = 'Ritevet - All Chats';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'All Chats',
            'URL' => [
                'Home' => $url,
                'All Chats' => ''
            ]
        ];

        // Check if the user is logged in
        if ($this->request->getSession()->read('RitevetUsers.id') === null) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Fetch the necessary tables
        $bookingsTable = $this->fetchTable('Bookings');
        $usersTable = $this->fetchTable('Users');

        // Define the condition for fetching the booking
        $condition = [
            "Bookings.request_id" => 1, // This should be adjusted based on your logic
            "OR" => [
                "Bookings.vendorId" => $this->request->getSession()->read('RitevetUsers.id'),
                "Bookings.userId" => $this->request->getSession()->read('RitevetUsers.id')
            ]
        ];

        // Fetch the booking record
        $booking = $bookingsTable->find()
            ->where($condition)
            ->contain(['Users', 'Vendors' => ['Usersinformations']])
            ->first();

        // Determine the user details based on the booking
        if ($booking && $booking->userId == $this->request->getSession()->read('RitevetUsers.id')) {
            $usersD = $usersTable->find()->where(['Users.id' => $booking->vendorId])->first();
        } else {
            $usersD = $usersTable->find()->where(['Users.id' => $booking->userId])->first();
        }

        // Fetch sender and receiver avatars
        $senderAvt = $usersTable->find()->where(['Users.id' => $this->request->getSession()->read('RitevetUsers.id')])->first();
        $receiverAvt = $booking ? $usersTable->find()->where(['Users.id' => $booking->vendorId])->first() : null;

        // Prepare user lists (if needed)
        $userLists = [];

        // Set variables for the view
        $this->set(compact('layoutTitle', 'breadcum', 'usersD', 'userLists', 'receiverAvt', 'senderAvt'));
    }
    
    public function saveChatMessagesToDB()
    {
        if ($this->request->is('post')) 
        {
            $chatsenderId = $this->request->getData('chatsenderId');
            $chatReceiverId = $this->request->getData('chatReceiverId');
            $messageText = $this->request->getData('messageText');
            $chatDate = $this->request->getData('chatDateTime');

            // Prepare the chat message data
            $chatData = [
                'sender_id' => $chatsenderId,
                'receiver_id' => $chatReceiverId,
                'message' => $messageText,
                'readMsg' => '0',
                'created' => $chatDate // Set created date directly here
            ];

            // Fetch the Chats table
            $chatsTable = $this->fetchTable('Chats');
            
            // Create a new entity and patch the data
            $chat = $chatsTable->newEntity($chatData);

            // Attempt to save the chat message
            if ($chatsTable->save($chat)) {
                // Chat message saved successfully
                $response = [
                    'status' => 'success',
                    'data' => $chat
                ];
            } else {
                // Error saving chat message
                $response = [
                    'status' => 'fail',
                    'data' => []
                ];
            }

            // Return the response as JSON
            echo json_encode($response);
            exit;
        }
    }
    
    public function getDisabledDays($videoChatAvail) 
    {
        $disabledDays = [];
    
        foreach (['SAT', 'SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI'] as $day) {
            $allZero = true;
            foreach ($videoChatAvail as $availability) {
                if ($availability->$day != 0) {
                    $allZero = false;
                    break;
                }
            }
            if ($allZero) {
                switch ($day) {
                    case 'SAT':
                        $disabledDays[] = 6;
                        break;
                    case 'SUN':
                        $disabledDays[] = 0;
                        break;
                    case 'MON':
                        $disabledDays[] = 1;
                        break;
                    case 'TUE':
                        $disabledDays[] = 2;
                        break;
                    case 'WED':
                        $disabledDays[] = 3;
                        break;
                    case 'THU':
                        $disabledDays[] = 4;
                        break;
                    case 'FRI':
                        $disabledDays[] = 5;
                        break;
                }
            }
        }
    
        return $disabledDays;
    }
    
    public function divideTimeIntoSlots()
    {
        // Allow only POST requests
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException(__('Method not allowed'));
        }

        $userId = $this->request->getData('userid'); // Use getData() to access request data
        $day = $this->request->getData('day');

        $videoChatAvailabilityTable = $this->fetchTable('Videochatavailability'); // Fetch the Videochatavailability table
        $videoChatAvail = $videoChatAvailabilityTable->find()
            ->where(['Videochatavailability.userId' => $userId])
            ->toArray();

        $slots = [];

        foreach ($videoChatAvail as $availability) {
            if ($availability->$day == 1) {
                $startTime = strtotime($availability->startTime);
                $endTime = strtotime($availability->endTime);
                $timeSlotDuration = $availability->time_slot_duration * 60; // convert minutes to seconds

                $availabilitySlots = [];
                while ($startTime < $endTime) {
                    $slotEnd = $startTime + $timeSlotDuration;

                    $availabilitySlots[] = date('g:i A', $startTime) . ' - ' . date('g:i A', $slotEnd); // concatenate with dash

                    $startTime = $slotEnd;
                }

                $slots = array_merge($slots, $availabilitySlots); // Merge the slots into a single array
            }
        }

        // Return JSON response
        return $this->response->withType('application/json')
                              ->withStringBody(json_encode($slots));
    }
    
    public function uploadImage($file, $uploadPath, $thumbWidth = 150, $thumbHeight = 150)
    {
        // Validate file input
        if ($file === null || !$file instanceof UploadedFileInterface) {
            return null;
        }

        if ($file->getError() !== UPLOAD_ERR_OK) {
            return null;
        }

        $originalName = $file->getClientFilename();
        if (empty($originalName)) {
            return null;
        }

        // Validate MIME type (only JPEG/JPG and PNG allowed)
        $allowedTypes = ['image/jpeg', 'image/png'];
        $mimeType = $file->getClientMediaType();
        if (!in_array($mimeType, $allowedTypes)) {
            return null; // Invalid type (not JPEG or PNG)
        }

        // Sanitize and generate unique filename
        $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\.\-_]/', '', basename($originalName));
        $fullPath = rtrim($uploadPath, DS) . DS . $filename;

        // Ensure upload directory exists and is writable
        if (!is_dir($uploadPath)) {
            if (!mkdir($uploadPath, 0755, true) || !is_dir($uploadPath)) {
                return null;
            }
        }

        if (!is_writable($uploadPath)) {
            return null;
        }

        // Move the file temporarily to process it
        $tempPath = $uploadPath . 'temp_' . $filename;
        try {
            $file->moveTo($tempPath);

            // Resize the image
            if (extension_loaded('imagick') && class_exists('Imagick')) {
                $image = new \Imagick($tempPath);
                $image->thumbnailImage($thumbWidth, $thumbHeight, true); // Maintain aspect ratio
                $image->writeImage($fullPath);
                $image->clear();
                $image->destroy();
            } elseif (extension_loaded('gd')) {
                $imageInfo = getimagesize($tempPath);
                if ($imageInfo === false) {
                    unlink($tempPath);
                    return null;
                }

                $mime = $imageInfo['mime'];
                switch ($mime) {
                    case 'image/jpeg':
                        $source = imagecreatefromjpeg($tempPath);
                        break;
                    case 'image/png':
                        $source = imagecreatefrompng($tempPath);
                        break;
                    default:
                        unlink($tempPath);
                        return null; // Shouldn't happen due to earlier MIME check, but included for safety
                }

                if (!$source) {
                    unlink($tempPath);
                    return null;
                }

                $width = imagesx($source);
                $height = imagesy($source);
                $aspectRatio = $width / $height;
                $thumbAspect = $thumbWidth / $thumbHeight;

                // Calculate new dimensions to fit within thumbWidth x thumbHeight
                if ($aspectRatio > $thumbAspect) {
                    $newWidth = $thumbWidth;
                    $newHeight = (int)($thumbWidth / $aspectRatio);
                } else {
                    $newHeight = $thumbHeight;
                    $newWidth = (int)($thumbHeight * $aspectRatio);
                }

                $resized = imagecreatetruecolor($newWidth, $newHeight);

                // Preserve transparency for PNG
                if ($mime === 'image/png') {
                    imagealphablending($resized, false);
                    imagesavealpha($resized, true);
                    $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
                    imagefill($resized, 0, 0, $transparent);
                }

                imagecopyresampled(
                    $resized,
                    $source,
                    0, 0, 0, 0,
                    $newWidth,
                    $newHeight,
                    $width,
                    $height
                );

                // Save the resized image
                switch ($mime) {
                    case 'image/jpeg':
                        imagejpeg($resized, $fullPath, 85); // 85% quality
                        break;
                    case 'image/png':
                        imagepng($resized, $fullPath, 8); // Compression level 8
                        break;
                }

                imagedestroy($source);
                imagedestroy($resized);
            } else {
                unlink($tempPath);
                return null; // No image processing extension available
            }

            // Clean up temporary file
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }

            chmod($fullPath, 0644);
            return $filename;
        } catch (\Exception $e) {
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
            return null;
        }
    }
    
    function formatDateString($dateString=null) 
    {
        if ($dateString === null || empty($dateString)) {
            return null;
        }
        
        // assumes the date string is in mm-dd-yyyy format
        list($month, $day, $year) = explode('-', $dateString);
        $formattedDate = sprintf('%s-%s-%s', $year, $month, $day);
        return $formattedDate;
    }
	
	private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        // Haversine formula to calculate the distance between two points on the Earth
        $earthRadius = 6371; // Earth radius in kilometers
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c; // Distance in kilometers
    }

    function getClientIP() 
    {
        $ip = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    
	public function editprofile()
    {
        $this->viewBuilder()->setLayout('login');

        // Initialize layout variables
        $layoutTitle = 'Ritevet - Edit Profile';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'Edit Profile',
            'URL' => [
                'Home' => $url,
                'Dashboard' => $url . 'users/dashboard',
                'Edit Profile' => $url . 'editprofile'
            ]
        ];

        // Check if user is logged in
        $session = $this->request->getSession();
        $userId = $session->read('RitevetUsers.id');
        if (empty($userId)) {
            $this->Flash->error(__('Please login to continue.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'signup']);
        }

        // Load table instances
        $usersTable = $this->fetchTable('Users');
        $countriesTable = $this->fetchTable('Countries');
        $statesTable = $this->fetchTable('States');
        $citiesTable = $this->fetchTable('Cities');

        // Fetch user data
        try {
            $user = $usersTable->get($userId);
        } catch (RecordNotFoundException $e) {
            $this->Flash->error(__('User not found.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'signup']);
        }

        // Prepare dropdown lists with named arguments
        $countryList = $countriesTable->find('list')
            ->select(['id', 'name'])
            ->where(['status' => 1])
            ->order(['name' => 'ASC'])
            ->toArray();

        $stateList = $user->countryId ? $statesTable->find('list')
            ->select(['id', 'name'])
            ->where(['country_id' => $user->countryId])
            ->order(['name' => 'ASC'])
            ->toArray() : [];

        $cityList = $user->stateId ? $citiesTable->find('list')
            ->select(['id', 'name'])
            ->where(['state_id' => $user->stateId])
            ->order(['name' => 'ASC'])
            ->toArray() : [];

        if ($this->request->is(['post', 'put'])) {
            try {
                // Validate contact number uniqueness
                $contactNumber = $this->request->getData('contactNumber');
                if ($usersTable->exists([
                    'contactNumber' => $contactNumber,
                    'id !=' => $userId
                ])) {
                    throw new \Exception('Phone number already exists.');
                }

                // Handle file uploads
                $profileImage = $this->request->getData('profile_picture');
                $idImage = $this->request->getData('profile_ID_image');
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                $maxFileSize = 2 * 1024 * 1024; // 2MB

                $data = $this->request->getData();

                // Validate and process profile image
                if ($profileImage && $profileImage->getSize() > 0) {
                    if (!in_array($profileImage->getClientMediaType(), $allowedTypes)) {
                        throw new \Exception('Profile picture must be JPEG or JPG or PNG.');
                    }
                    if ($profileImage->getSize() > $maxFileSize) {
                        throw new \Exception('Profile picture must not exceed 2MB.');
                    }
                    
                    $newProfileImage = $this->uploadImage(
                        $profileImage,
                        WWW_ROOT . 'img/uploads/users/'
                    );

                    if ($newProfileImage) {
                        $oldProfileImage = $user->profile_picture;
                        $data['profile_picture'] = $newProfileImage;
                        if ($oldProfileImage && file_exists(WWW_ROOT . 'img/uploads/users/' . $oldProfileImage)) {
                            unlink(WWW_ROOT . 'img/uploads/users/' . $oldProfileImage);
                        }
                    }
                } else {
                    unset($data['profile_picture']);
                }

                // Validate and process ID image
                if ($idImage && $idImage->getSize() > 0) {
                    if (!in_array($idImage->getClientMediaType(), $allowedTypes)) {
                        throw new \Exception('ID image must be JPEG or JPG or PNG.');
                    }
                    if ($idImage->getSize() > $maxFileSize) {
                        throw new \Exception('ID image must not exceed 2MB.');
                    }

                    $newIdImage = $this->uploadImage(
                        $idImage,
                        WWW_ROOT . 'img/uploads/users/usersIdImage/'
                    );

                    if ($newIdImage) {
                        $oldIdImage = $user->profile_ID_image;
                        $data['profile_ID_image'] = $newIdImage;
                        if ($oldIdImage && file_exists(WWW_ROOT . 'img/uploads/users/usersIdImage/' . $oldIdImage)) {
                            unlink(WWW_ROOT . 'img/uploads/users/usersIdImage/' . $oldIdImage);
                        }
                    }
                } else {
                    unset($data['profile_ID_image']);
                }

                // Patch entity with request data
                $user = $usersTable->patchEntity($user, $data, [
                    'validate' => false,
                    'fieldList' => [
                        'firstName',
                        'lastName',
                        'contactNumber',
                        'countryId',
                        'stateId',
                        'cityId',
                        'address',
                        'zipCode',
                        'profile_picture',
                        'profile_ID_image'
                    ]
                ]);

                $user->modified = new DateTime();

                if ($usersTable->save($user)) {
                    $this->Flash->success(__('Profile updated successfully.'));
                    return $this->redirect(['action' => 'editprofile']);
                }

                throw new \Exception('Unable to update profile. Please try again.');

            } catch (\Exception $e) {
                $this->Flash->error(__($e->getMessage()));
            }
        }

        $this->set(compact('layoutTitle', 'breadcum', 'user', 'countryList', 'stateList', 'cityList'));
    }
	
	public function changepassword()
    {
        $this->viewBuilder()->setLayout('pages');
        $layoutTitle = 'Ritevet - Change Password';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'CHANGE PASSWORD',
            'URL' => [
                'Home' => $url,
                'Dashboard' => $url . 'users/dashboard',
                'Change Password' => $url . 'users/changepassword'
            ]
        ];

        // Check if the user is logged in
        if ($this->request->getSession()->read('RitevetUsers.id') === null) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Check if the new password is the same as the old password
        if (trim($this->request->getData('oldpassword')) === trim($this->request->getData('password'))) {
            $this->Flash->error(__('New password should be different from old password'));
            return $this->redirect(['controller' => 'Users', 'action' => 'editprofile']);
        }

        // Fetch the Users table
        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->find()
            ->where(['Users.id' => $this->request->getSession()->read('RitevetUsers.id')])
            ->first();

        if ($this->request->is('post')) {
            // Check the old password
            $hasher = new DefaultPasswordHasher();
            $check = $hasher->check($this->request->getData('oldpassword'), $user->password);

            if ($check) {
                // Update the password
                $user = $usersTable->patchEntity($user, ['password' => $this->request->getData('password')], ['validate' => false]);
                if ($usersTable->save($user)) {
                    $this->Flash->success(__('Password updated successfully.'));
                } else {
                    $this->Flash->error(__('Unable to update password. Please try again.'));
                }
                return $this->redirect(['controller' => 'Users', 'action' => 'editprofile']);
            } else {
                $this->Flash->error(__('Current password does not match.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'editprofile']);
            }
        }

        $this->set(compact('layoutTitle', 'breadcum', 'user'));
    }
	
    public function reset($token)
    {
        $this->viewBuilder()->setLayout('pages');
        $layoutTitle = 'Ritevet - Reset Password';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'Reset Password',
            'URL' => [
                'Home' => $url,
                'Forgot Password' => $url . 'users/forgotpassword',
                'Reset Password' => $url . 'users/reset',
            ]
        ];

        // Redirect logged-in users to the dashboard
        if ($this->request->getSession()->read('RitevetUsers.id')) {
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }

        // Check if token is provided
        if (!empty($token)) {
            $this->set('token', $token);

            // Handle form submission
            if ($this->request->is('post')) {
                $data = $this->request->getData(); // Use $this->request->getData()
                $password1 = $data['password'];
                $password2 = $data['Cpassword'];

                // Validate passwords
                if ($password1 !== $password2) {
                    $this->Flash->error(__('Confirm password does not match.'));
                } elseif (strlen($password1) < 6) {
                    $this->Flash->error(__('Password length must be at least 6 characters.'));
                } else {
                    // Fetch the Users table and find user by token
                    $usersTable = $this->fetchTable('Users');
                    $user = $usersTable->find()
                        ->where(['Users.passwordToken' => $token])
                        ->first();

                    if ($user) {
                        $user->password = $password1;
                        $user->passwordToken = null;

                        if ($usersTable->save($user)) {
                            // Prepare email
                            $to = $user->email;
                            $subject = "Ritevet App - Reset Password";
                            $message = "Dear " . ucfirst($user->firstName) . " " . ucfirst($user->lastName) . ",<br>";
                            $message .= "Your password has been reset successfully.";

                            // Send email using PHPMailer
                            if ($this->phpemail($to, $subject, $message)) {
                                $this->Flash->success(__('Password reset successfully.'));
                            } else {
                                $this->Flash->error(__('There was an error sending the email. Please try again.'));
                            }

                            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
                        } else {
                            $this->Flash->error(__('Unable to reset password. Please try again.'));
                        }
                    } else {
                        $this->Flash->error(__('This reset token has expired.'));
                    }
                }
            }
        } else {
            $this->Flash->error(__('This reset token has expired.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }

        $this->set(compact('breadcum', 'layoutTitle'));
    }
    
    public function verifyEmail($token = null, $sessionToken = null)
    {
        // Check if the session has the verification token
        if (!$this->request->getSession()->check('session_token')) {
            $this->Flash->error(__('Your session has expired. Please verify your email again.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Fetch the Users table
        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->find()
            ->where(['verification_token' => $token])
            ->first();

        // Get the session token
        $storedSessionToken = $this->request->getSession()->read('session_token');

        if ($user) {
            // Check if the session token matches
            if ($storedSessionToken === $sessionToken) {
                $user->status = 1; // Set status to 1 (verified)
                $user->verification_token = null; // Clear the verification token

                if ($usersTable->save($user)) {
                    // Clear the session token after successful verification
                    $this->request->getSession()->delete('session_token');

                    $this->Flash->success(__('Your email has been verified successfully. You can now log in.'));
                    return $this->redirect(['controller' => 'Users', 'action' => 'login']);
                } else {
                    $this->Flash->error(__('Unable to verify your email. Please try again.'));
                }
            } else {
                $this->Flash->error(__('Session token does not match. Please try again.'));
            }
        } else {
            $this->Flash->error(__('Invalid verification token.'));
        }

        return $this->redirect(['controller' => 'Users', 'action' => 'signup']);
    }
    
    public function resendVerificationEmail()
    {
        $this->viewBuilder()->setLayout('pages');
        $layoutTitle = 'Ritevet - Verify Email';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'Verify Email',
            'URL' => [
                'Home' => $url,
                'Sign In' => $url . 'users/login',
                'Verify Email' => $url . 'users/resend-verification-email'
            ]
        ];

        if ($this->request->is('post')) {
            $email = $this->request->getData('email');

            // Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->Flash->error(__('Please enter a valid email address.'));
                return $this->redirect(['action' => 'resendVerificationEmail']);
            }

            // Fetch the Users table
            $usersTable = $this->fetchTable('Users');
            $user = $usersTable->find()->where(['email' => $email])->first();

            if ($user) {
                if ($user->status) {
                    $this->Flash->error(__('Your email address is already verified.'));
                } else {
                    // Rate limiting: Check if the user has requested a verification email recently
                    $lastSent = $user->last_verification_email_sent ? $user->last_verification_email_sent : null;
                    if ($lastSent && (time() - strtotime($lastSent) < 3600)) { // 1 hour limit
                        $this->Flash->error(__('You can only request a verification email once every hour.'));
                    } else {
                        // Generate a new verification token
                        $verificationToken = $this->random_password(50);
                        $user->verification_token = $verificationToken;
                        $user->last_verification_email_sent = date('Y-m-d H:i:s'); // Update the timestamp

                        if ($usersTable->save($user)) { // Save the new token to the database
                            // Generate a random session token
                            $sessionToken = $this->random_password(50);
                            $this->request->getSession()->write('session_token', $sessionToken); // Store it in the session

                            // Send verification email
                            $verificationLink = Router::url(['controller' => 'Users', 'action' => 'verifyEmail', $verificationToken, $sessionToken], true);
                            $to = $email;
                            $subject = "Verify your email.";
                            $message = "Dear " . ucfirst($user->firstName) . " " . ucfirst($user->lastName) . ',';
                            $message .= "<br>Welcome to RiteVet! We're delighted to have you as a new member of our platform.";
                            $message .= "<br>Please verify your email by clicking the link below:";
                            $message .= "<br><br><a href='" . $verificationLink . "' style='display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;'>Verify Email</a>";
                            $message .= "<br><br><strong>Note:</strong> Please open this link in the same browser to ensure proper verification.";
                            $this->phpemail($to, $subject, $message);

                            $this->Flash->success(__('A new verification email has been sent to your email address.'));
                            return $this->redirect(['action' => 'resendVerificationEmail']);
                        } else {
                            $this->Flash->error(__('Unable to save the verification token. Please try again.'));
                            return $this->redirect(['action' => 'resendVerificationEmail']);
                        }
                    }
                }
            } else {
                $this->Flash->error(__('No account found with that email address.'));
                return $this->redirect(['action' => 'resendVerificationEmail']);
            }
        }

        $this->set(compact('breadcum', 'layoutTitle'));
    }
	
	public function stateList()
    {
        // Allow only POST requests
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException(__('Method not allowed'));
        }

        $statesTable = $this->fetchTable('States'); // Fetch the States table
        $counttyID = $this->request->getData('countryId'); // Use getData() to access request data

        // Fetch states based on the country ID
        $parentCountryList = $statesTable->find()
            ->where(['States.country_id' => $counttyID])
            ->order(['States.name' => 'ASC'])
            ->toArray();

        // Prepare the response options
        $response = [
            'options' => '<option value="">Select State</option>',
        ];

        foreach ($parentCountryList as $key => $state) {
            $response['options'] .= '<option value="' . h($state->id) . '">' . h($state->name) . '</option>'; // Use h() for HTML escaping
        }

        // Return JSON response
        return $this->response->withType('application/json')
                              ->withStringBody(json_encode($response));
    }
	
    public function cityList()
    {
        $this->request->allowMethod(['post']); // Allow only POST requests

        $citiesTable = $this->fetchTable('Cities');

        // Get the state ID from the request data
        $stateID = $this->request->getData('stateId');

        // Fetch the cities based on the state ID
        $cityList = $citiesTable->find()
            ->where(['state_id' => $stateID])
            ->order(['name' => 'ASC'])
            ->toArray();

        // Prepare the response options
        $response = [
            'options' => '<option value="">Select City</option>',
        ];

        foreach ($cityList as $city) {
            $response['options'] .= '<option value="' . h($city->id) . '">' . h($city->name) . '</option>';
        }

        // Set the response type to JSON and return the response
        return $this->response->withType('application/json')
                              ->withStringBody(json_encode($response));
    }
	
	public function deletelicence($id)
    {
        // Fetch the Multilicenses table
        $multilicensesTable = $this->fetchTable('Multilicenses');
        
        // Find the license by ID and user ID
        $multi = $multilicensesTable->find()
            ->where([
                'Multilicenses.id' => $id,
                'Multilicenses.userId' => $this->request->getSession()->read('RitevetUsers.id')
            ])
            ->first();

        if ($multi) {
            // Delete the license
            if ($multilicensesTable->delete($multi)) {
                $this->Flash->success(__('Licence has been deleted.'));
            } else {
                $this->Flash->error(__('Licence could not be deleted. Please, try again.'));
            }
        } else {
            $this->Flash->error(__('Licence could not be found or does not belong to you.'));
        }

        // Redirect based on UTYPE
        if ($multi && $multi->UTYPE == 2) {
            return $this->redirect(['controller' => 'Users', 'action' => 'veterinarianRegister']);
        } else {
            return $this->redirect(['controller' => 'Users', 'action' => 'otherPetServiceRegister']);
        }
    }
	
	public function imagedelete($id = null)
    {
        // Fetch the Images table
        $imagesTable = $this->fetchTable('Images');

        // Find the image by ID and user ID
        $image = $imagesTable->find()
            ->where([
                'Images.userId' => $this->request->getSession()->read('RitevetUsers.id'),
                'Images.id' => $id
            ])
            ->first();

        if ($image) {
            // Delete the image file from the server
            $imagePath = WWW_ROOT . 'img/uploads/multiimage/' . $image->image;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            // Delete the image record from the database
            if ($imagesTable->delete($image)) {
                // Redirect based on UTYPE
                if ($image->UTYPE == 2) {
                    return $this->redirect(['controller' => 'Users', 'action' => 'veterinarianRegister']);
                } else {
                    return $this->redirect(['controller' => 'Users', 'action' => 'otherPetServiceRegister']);
                }
            } else {
                $this->Flash->error(__('Unable to delete the image record. Please try again.'));
            }
        } else {
            $this->Flash->error(__('Image not found or does not belong to you.'));
        }

        // Fallback redirect if image not found or deletion failed
        return $this->redirect(['controller' => 'Users', 'action' => 'otherPetServiceRegister']);
    }
	
    public function uploadImages($user, $request)
    {
        // Define the upload path
        $uploadPath = WWW_ROOT . 'img/uploads/multiimage/';

        // Ensure the directory exists and is writable
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        if (!is_writable($uploadPath)) {
            chmod($uploadPath, 0777);
        }

        // Get uploaded files from the request
        $uploadedFiles = $request->getUploadedFiles();
        if (empty($uploadedFiles)) {
            throw new InvalidArgumentException('No files provided for upload.');
        }

        // Load the Images table
        $imagesTable = $this->fetchTable('Images');

        // Track processed file objects by hash
        $processedFileObjects = [];

        // Process each uploaded file
        foreach ($uploadedFiles as $fieldName => $files) {
            // Normalize to array for consistent processing
            $files = is_array($files) ? $files : [$files];

            foreach ($files as $index => $file) {
                // Validate the file
                if (!$file instanceof UploadedFileInterface || $file->getError() !== UPLOAD_ERR_OK) {
                    continue;
                }

                // Get a unique identifier for the file object
                $fileHash = spl_object_hash($file);
                if (in_array($fileHash, $processedFileObjects)) {
                    continue;
                }

                // Get and validate original filename
                $originalName = $file->getClientFilename();
                if (empty($originalName)) {
                    continue;
                }

                // Generate a sanitized unique filename
                $fileName = time() . '_' . basename($originalName);
                $fileName = preg_replace('/[\s\'"()\[\]!@#$%^&*]/', '', $fileName);
                $targetPath = $uploadPath . $fileName;

                // Map field name to image type
                $imageType = match ($fieldName) {
                    'uploadTranscript' => 'Transcript',
                    'uploadLicense' => 'License',
                    'BImage' => 'Business',
                    'uploadDocument' => 'Document',
                    'EX_CERT' => 'EX_CERT',
                    default => null
                };

                if (!$imageType) {
                    continue;
                }

                // Prepare image data for storage
                $imageData = [
                    'UTYPE' => $user->UTYPE,
                    'userId' => $user->userId,
                    'userInfoId' => $user->id,
                    'imageType' => $imageType,
                    'image' => $fileName,
                    'added_from' => 'WEB',
                    'created' => date('Y-m-d H:i:s')
                ];

                // Check for existing image in the database
                $existingImage = $imagesTable->find()
                    ->where([
                        'userId' => $this->request->getSession()->read('RitevetUsers.id'),
                        'image' => $fileName,
                    ])
                    ->first();

                // Check if the file already exists on disk
                $fileExistsOnDisk = file_exists($targetPath);

                try {
                    if ($existingImage && $fileExistsOnDisk) {
                        $processedFileObjects[] = $fileHash; // Mark as processed
                        continue;
                    } elseif ($existingImage) {
                        $file->moveTo($targetPath);
                        chmod($targetPath, 0777); // Optional
                    } else {
                        $imageEntity = $imagesTable->newEntity($imageData, ['validate' => false]);
                        if ($imagesTable->save($imageEntity)) {
                            $file->moveTo($targetPath);
                            chmod($targetPath, 0777); // Optional
                        } else {
                            continue;
                        }
                    }
                    $processedFileObjects[] = $fileHash;
                } catch (Laminas\Diactoros\Exception\UploadedFileAlreadyMovedException $e) {
                    if ($existingImage || (isset($imageEntity) && $imagesTable->exists(['id' => $imageEntity->id]))) {
                        $processedFileObjects[] = $fileHash;
                    } else {
                    }
                    continue;
                } catch (Exception $e) {
                    if (isset($imageEntity) && !$existingImage) {
                        $imagesTable->delete($imageEntity); // Rollback on failure
                    }
                }
            }
        }
    }
    
    function convertTimeSlotToUTC($timeSlot, $timeZone) 
    {
        // Split the time slot into start and end times
        list($startTime, $endTime) = explode(' - ', $timeSlot);
        // Create DateTime objects for the start and end times in the specified time zone
        $start = new \DateTime($startTime, new \DateTimeZone($timeZone));
        $end = new \DateTime($endTime, new \DateTimeZone($timeZone));
        // Convert the times to UTC
        $start->setTimezone(new \DateTimeZone('UTC'));
        $end->setTimezone(new \DateTimeZone('UTC'));
    
        // Return the time slot in UTC format using 12-hour format with AM/PM
        return $start->format('g:i A') . ' - ' . $end->format('g:i A');
    }
    
    function test() 
    {
        $timeSlot = '9:00 PM - 9:30 PM';
        $timeZone = 'Africa/cairo'; // Specify your time zone
        $utcTimeSlot = $this->convertTimeSlotToUTC($timeSlot, $timeZone);
        echo $utcTimeSlot;
        exit;
    }
    
}