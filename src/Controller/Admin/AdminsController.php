<?php
namespace App\Controller\Admin;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use App\Controller\Admin\Component\ImgComponent;
use App\Controller\Admin\AppController;
use Cake\View\Helper\HtmlHelper;
use Cake\View\Helper\FormHelper;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

/*******************************************************************************/
class AdminsController extends AppController
{
	public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
    }
	/*********************************************************************************/
	public function login()
    {
        $this->viewBuilder()->setLayout('Admin/adminlogin');
        $this->set('layoutTitle', 'Admin::Login');

        $session = $this->request->getSession();

        // Check if the user is already logged in
        if ($session->read('AnnuityAdmin.role') === 'Admin') {
            return $this->redirect(['controller' => 'Admins', 'action' => 'dashboard', 'prefix' => 'Admin']);
        }

        // Handle login form submission
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $usersTable = $this->fetchTable('Users');

            $user = $usersTable->find()
                ->where(['username' => $data['username']])
                ->first();
            if ($user && password_verify($data['password'], $user->password) && $user->role === 'Admin') {
                // Store user data in session
                // Additional detail (assuming this is for user ID 1)
                $detail['AnnuityAdmin'] = $usersTable->get(1)->toArray();
                $session->write($detail);

                // Update last login time for the logged-in user
                $user->lastLogin = date('Y-m-d H:i:s');
                $usersTable->save($user);

                return $this->redirect(['controller' => 'Admins', 'action' => 'dashboard']);
            } else {
                $this->Flash->error(__('Invalid username or password, try again'));
                return $this->redirect(['controller' => 'Admins', 'action' => 'login']);
            }
        }
    }

    public function logout()
    {
        $session = $this->request->getSession();

        // Check if user is logged in
        if (!$session->check('AnnuityAdmin') || $session->read('AnnuityAdmin.role') !== 'Admin') {
            // If no session exists or user isn’t an Admin, redirect to login without further action
            return $this->redirect(['controller' => 'Admins', 'action' => 'login']);
        }

        // Update last login time for user with ID 1
        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->get(1); // Fetch user with ID 1
        $user->lastLogin = date('Y-m-d H:i:s');
        $usersTable->save($user);

        // Clear session data
        $session->delete('AnnuityAdmin');
        $session->destroy();

        // Redirect to login page
        return $this->redirect(['controller' => 'Admins', 'action' => 'login']);
    }

	public function dashboard()
    {
        // Set the layout
        $this->viewBuilder()->setLayout('Admin/admin');
        $layoutTitle = 'Admin::Dashboard';
        $this->set(compact('layoutTitle'));

        // Check session for admin role
        $session = $this->request->getSession();
        if ($session->read('AnnuityAdmin.role') !== 'Admin') {
            $session->destroy(); // Logout by destroying session
            return $this->redirect(['controller' => 'Admins', 'action' => 'login', 'prefix' => 'Admin']);
        }

        // Fetch tables
        $usersTable = $this->fetchTable('Users');
        $requestQuotsTable = $this->fetchTable('RequestQuots');

        // Fetch counts
        $totalRequestedQuotes = $requestQuotsTable->find()->count();
        $pendingQuotes = $requestQuotsTable->find()->where(['status' => 2])->count();
        $illustrativeQuotesReady = $requestQuotsTable->find()->where(['status IN' => [1, 6, 7]])->count();
        $cancelledQuotes = $requestQuotsTable->find()->where(['status' => 4])->count();
        $soldQuotes = $requestQuotsTable->find()->where(['status' => 3])->count();
        $totalUsers = $usersTable->find()->where(['role' => 'Member'])->count();

        // Set variables for the view
        $this->set(compact('totalRequestedQuotes', 'pendingQuotes', 'illustrativeQuotesReady', 'cancelledQuotes', 'soldQuotes', 'totalUsers'));
    }

    public function edit()
    {
        // Set layout title and layout
        $layoutTitle = 'Admin::EditInfo';
        $this->viewBuilder()->setLayout('Admin/admin');

        // Hardcode the admin ID
        $id = 1;

        // Fetch the Users table
        $usersTable = $this->fetchTable('Users');

        // Find the user by ID
        $user = $usersTable->get($id);

        if ($this->request->is(['patch', 'post', 'put']))
        {
            $userImage = $this->request->getData('profile_picture');
            $oldFilename = $user->getOriginal('profile_picture'); // Get the old filename

            // Validate the uploaded image
            if ($userImage && $userImage->getError() === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                $fileType = $userImage->getClientMediaType();
                $fileSize = $userImage->getSize();

                // Check if the file type is allowed and size is within limits (e.g., 2MB)
                if (!in_array($fileType, $allowedTypes) || $fileSize > 2 * 1024 * 1024) {
                    $this->Flash->error(__('Invalid image type or size. Please upload a JPEG, PNG, or JPG image under 2MB.'));
                    return;
                }

                // Generate a unique filename
                $filename = time() . '_' . $userImage->getClientFilename();
                $this->request = $this->request->withData('profile_picture', $filename);
            } else {
                $this->request = $this->request->withData('profile_picture', $oldFilename);
            }

            // Patch the entity with the request data
            $user = $usersTable->patchEntity($user, $this->request->getData(), ['validate' => false]);

            if ($usersTable->save($user)) {
                // Handle file uploads if a new image is uploaded
                if (isset($filename)) {
                    $path = WWW_ROOT . 'img/uploads/users/admin/';

                    // Create the admin directory if it doesn't exist
                    if (!is_dir($path)) {
                        mkdir($path, 0755, true);
                    }

                    // Remove the old image if it exists
                    if ($oldFilename) {
                        @unlink($path . $oldFilename);
                    }

                    // Move the uploaded file to the admin directory
                    $userImage->moveTo($path . $filename);

                    // Resize and save the image in the admin directory
                    $MyImageCom = new ImgComponent();
                    $MyImageCom->prepare($path . $filename);
                    $MyImageCom->resize(150, 100);
                    $MyImageCom->save($path . $filename); // Save resized image in the same admin directory

                    // Update session data
                    $this->request->getSession()->write('AnnuityAdmin.profile_picture', $filename);
                } else {
                    // If no new image is uploaded, keep the old filename in the session
                    $this->request->getSession()->write('AnnuityAdmin.profile_picture', $oldFilename);
                }

                // Update session with new user data
                $this->request->getSession()->write('AnnuityAdmin.username', $this->request->getData('username'));
                $this->request->getSession()->write('AnnuityAdmin.email', $this->request->getData('email'));

                $this->Flash->success(__('The admin details have been updated.'));
                return $this->redirect(['action' => 'edit']); // Redirect to the edit page for the same user
            } else {
                $this->Flash->error(__('The admin details could not be updated. Please, try again.'));
                return $this->redirect(['action' => 'edit']);
            }
        }

        // Set the user data to the view
        $this->set(compact('layoutTitle', 'user'));
    }

	public function changepassword()
    {
        // Fetch the Users table
        $usersTable = $this->fetchTable('Users');

        // Get the user ID from the session
        $userId = $this->request->getSession()->read('AnnuityAdmin.id');

        // Fetch the user by ID
        $user = $usersTable->get($userId);

        // Check if the user is authorized
        if ($this->request->getSession()->read('AnnuityAdmin.role') != 'Admin' && empty($this->request->getSession()->read('AnnuityAdmin.role'))) {
            return $this->redirect($this->Auth->logout());
        }

        // Handle the form submission
        if ($this->request->is(['patch', 'post', 'put'])) {
            // Patch the entity with the request data
            $user = $usersTable->patchEntity($user, [
                'old_password' => $this->request->getData('old_password'),
                'password'     => $this->request->getData('password1'),
                'password1'    => $this->request->getData('password1'),
                'password2'    => $this->request->getData('password2')
            ], ['validate' => 'password']);

            // Check if the old password is correct
            if ($this->checkPassword($user->id, $this->request->getData('old_password'))) {
                // Save the user entity
                if ($usersTable->save($user)) {
                    $this->Flash->success(__('Password has been changed successfully.'));
                    return $this->redirect(['action' => 'changepassword']);
                } else {
                    $this->Flash->error(__('The password could not be changed. Please, try again.'));
                }
            } else {
                $this->Flash->error(__('Invalid old password entered.'));
            }
        }

        // Set the layout and title
        $this->viewBuilder()->setLayout('Admin/admin');
        $layoutTitle = 'Admin::ChangePassword';
        $this->set(compact('layoutTitle', 'user'));
    }

	public function forgotpassword()
    {
        $this->viewBuilder()->setLayout('Admin/adminlogin');

        // Fetch the Users table
        $usersTable = $this->fetchTable('Users');

        if ($this->request->is(['post'])) {
            $user = $usersTable->find()
                ->where(['Users.email' => $this->request->getData('email'), 'Users.role' => 'Admin'])
                ->first();

            if (empty($user)) {
                $this->Flash->error(__('Email address does not exist.'));
            } else {
                // Generate a random token
                $token = $this->random_password(50);
                $url = Configure::read('App.siteurl') . 'admin/Admins/reset/' . $token;

                // Update the reset password token for the user
                $user->reset_password_token = $token;
                if ($usersTable->save($user)) {
                    // Prepare email variables
                    $click = '<a href="' . $url . '"><button class="btn btn-primary mr5">Click To Reset Password</button></a>';
                    $Email_variables = [
                        'fullname' => $user->firstName . ' ' . $user->lastName,
                        'click_here' => $click,
                        'link' => $url,
                        'templatemail' => Configure::read('App.adminEmail'),
                        'layoutTitle' => 'Admin forgot password',
                        'imagepath' => Configure::read('App.siteurl')
                    ];

                    // Prepare email content
                    $subject = 'Ritevet-Admin Reset Password';
                    $message = "Dear Admin,<br><br>You have requested to reset your password. Find the reset password link below:";
                    $message .= '<br>Username: admin';
                    $message .= '<br>' . $click;
                    $message .= '<br>';

                    // Send the email
                    $this->phpemail($user->email, $subject, $message);

                    $this->Flash->success(__('Reset password link has been successfully sent to your registered email.'));
                    return $this->redirect(['action' => 'forgotpassword']);
                } else {
                    $this->Flash->error(__('Unable to generate reset password link. Please try again.'));
                }
            }
        }

        $layoutTitle = 'Admin::ForgetPassword';
        $this->set(compact('layoutTitle'));
    }

	public function reset($token = null)
    {
        $this->viewBuilder()->setLayout('Admin/adminlogin');

        // Fetch the Users table
        $usersTable = $this->fetchTable('Users');

        if (!empty($token)) {
            $this->set('token', $token);

            if ($this->request->is(['post'])) {
                // Validate the passwords
                if ($this->request->getData('password1') !== $this->request->getData('password2')) {
                    $this->Flash->error(__('Confirm password does not match.'));
                } elseif (strlen($this->request->getData('password1')) < 6) {
                    $this->Flash->error(__('Password length must be at least 6 characters.'));
                } else {
                    // Find the user with the reset token
                    $user = $usersTable->find()
                        ->where(['Users.reset_password_token' => $token, 'Users.role' => 'Admin'])
                        ->first();

                    if (!empty($user)) {
                        // Hash the new password
                        $passwordHasher = new DefaultPasswordHasher();
                        $hashedPassword = $passwordHasher->hash($this->request->getData('password1'));

                        // Update the user's password and clear the reset token
                        $user->password = $hashedPassword;
                        $user->reset_password_token = '';

                        if ($usersTable->save($user)) {
                            $this->Flash->success(__('Password reset successfully.'));
                            return $this->redirect(['controller' => 'Admins', 'action' => 'login']);
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
            return $this->redirect(['controller' => 'Admins', 'action' => 'logout']);
        }

        $layoutTitle = 'Admin::ResetPassword';
        $this->set(compact('layoutTitle'));
    }

    // Method to check the old password
    private function checkPassword($userId, $oldPassword)
    {
        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->get($userId);
        return password_verify($oldPassword, $user->password);
    }

}
