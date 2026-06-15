<?php
namespace App\Controller\Admin;

use App\Controller\Admin\Component\ImgComponent;
use App\Controller\Admin\AppController;
use Cake\View\Helper\TimeHelper;
use Cake\Event\EventInterface;
use Cake\Queue\QueueManager;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Cake\Mailer\Email;
use Cake\I18n\Time;

/********************************************************************************************/
class UsersController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
    }
    /*********************************************************************************/
    public function index()
	{
	    $layoutTitle = 'Admin::Employee';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    $limit = 10;
	    $this->paginate = [
	        'limit' => $limit,
	        'order' => [
	            'Users.created' => 'desc'
	        ],
	    ];

	    $keyword = $this->request->getQuery('keyword');
	    $condition = ['Users.role !=' => 'Admin'];

	    if (!empty($keyword)) {
	        $condition = [
	            'Users.role !=' => 'Admin',
	            'OR' => [
	                'Users.firstName LIKE' => '%' . $keyword . '%',
	                'Users.lastName LIKE' => '%' . $keyword . '%',
	                'Users.email LIKE' => '%' . $keyword . '%',
	                'Users.contactNumber LIKE' => '%' . $keyword . '%'
	            ]
	        ];
	    }

	    $query = $this->Users->find('all')->where($condition);
	    $users = $this->paginate($query);

	    $this->set(compact('users', 'limit'));
	}

    public function userdetails($id)
	{
	    $layoutTitle = 'Admin::Employee Details';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    try {
	        // Fetch the user with related data using named arguments
	        $user = $this->Users->get($id, contain: []);

	        // Set the user data to the view
	        $this->set(compact('user'));
	    } catch (\Cake\Datasource\Exception\RecordNotFoundException $e) {
	        // Handle the case where the user does not exist
	        $this->Flash->error(__('Employee does not exist.'));
	        return $this->redirect(['controller' => 'Users', 'action' => 'index']);
	    }
	}

	public function add()
	{
	    $layoutTitle = 'Admin::Add Employee';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    $users = $this->Users->newEmptyEntity();

	    if ($this->request->is('post')) {
	        $users = $this->Users->patchEntity($users, $this->request->getData(), ['validate' => 'user']);
			$users->username = $this->request->getData('email');
			$users->role = 'Employee';
	        $users->created = date('Y-m-d H:i:s');

	        if ($this->Users->save($users)) {
	            $this->Flash->success(__('Faq added successfully.'));
	            return $this->redirect(['action' => 'index']);
	        } else {
				//pr($users); die;
	            $this->Flash->error(__('Unable to add new user, please fill all fields.'));
	        }
	    }

	    $this->set(compact('users'));
	}

    public function edit($id)
	{
	    $layoutTitle = 'Admin::Edit Employee';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');
	    $users = $this->fetchTable('Users')->get($id);
	    if ($this->request->is(['patch', 'post', 'put'])) {
	        $users = $this->fetchTable('Users')->patchEntity($users, $this->request->getData(), ['validate' => 'user']);
	        if ($this->fetchTable('Users')->save($users)) {
	            $this->Flash->success(__('The faq updated successfully.'));
	            return $this->redirect(['action' => 'index']);
	        } else {
	            $this->Flash->error(__('Faq information could not be saved. Please try again later.'));
	        }
	    }

	    $this->set(compact('users'));
	}


    public function status($id)
	{
	    // Fetch the Users table
	    $usersTable = $this->fetchTable('Users');

	    // Retrieve the user by ID
	    $user = $usersTable->get($id);

	    // Toggle the status
	    $user->status = $user->status == '1' ? '0' : '1';
	    $msg = $user->status == '1' ? 'activated' : 'deactivated';

	    // Update the user's status
	    if ($usersTable->save($user)) {
	        $this->Flash->success(__('Member has been ' . $msg . ' successfully.'));
	    } else {
	        $this->Flash->error(__('Unable to update member status. Please try again.'));
	    }

	    return $this->redirect(['controller' => 'Users', 'action' => 'index']);
	}

	public function delete($id = null)
	{
	    $this->request->allowMethod(['post', 'delete']);

	    // Check session for admin role
	    $session = $this->request->getSession();
	    if ($session->read('AnnuityAdmin.role') !== 'Admin') {
	        $session->destroy();
	        return $this->redirect(['controller' => 'Admins', 'action' => 'login', 'prefix' => 'Admin']);
	    }

	    $usersTable = $this->fetchTable('Users');
	    $user = $usersTable->get($id);

	    if ($usersTable->delete($user)) {
	        $this->Flash->success(__('Member deleted successfully.'));
	    } else {
	        $this->Flash->error(__('Unable to delete member. Please, try again.'));
	    }

	    return $this->redirect(['action' => 'index']);
	}

    public function adminVerify($id)
    {
        // Use fetchTable instead of loadModel
        $usersInfoTable = $this->fetchTable('Usersinformations');
        $usersTable = $this->fetchTable('Users');

        $user = $usersInfoTable->get($id);

        $status = '1';
        $msg = 'activated';
        if ($user->verifyAdmin == '1') {
            $status = '0';
            $msg = 'deactivated';
        }

        // Modern update approach
        $usersInfoTable->updateAll(
            ['verifyAdmin' => $status],
            ['id' => $id]
        );

        if ($status == '1') {
            $userData = $usersTable->find()
                ->where(['Users.id' => $user->userId])
                ->first();

            $to = $userData->email;
            $subject = "Welcome to our Platform: Unlock Your Global Potential!";

            if ($user->UTYPE == 2) {
                $message = "Dear " . ucfirst($userData->firstName) . ", the review process is complete. Welcome to RiteVet! We are thrilled to have you on board as a new member of our vibrant and global community. This email serves as a warm welcome, introducing you to our platform and its incredible opportunities to connect with a diverse client base worldwide.";
                $message .= "<br><br>At RiteVet our mission is to empower individuals like you to unlock their true potential and reach new heights of success. Our platform offers you a unique avenue to showcase your skills and expertise on a global scale. We believe in the power of connection and collaboration, and our services are designed to help you tap into a vast network of clients from the USA and around the world.";
                $message .= "<br><br>We believe that your talents deserve a global stage, and we are excited to be part of your journey to success. To get started, simply log in to your account, and start exploring the myriad of opportunities awaiting you.";
                $message .= "<br><br>If you have any questions or need assistance, please don't hesitate to reach out to our support team at ritevet@ritevet.com. We're here to help!";
                $message .= "<br><br>Once again, welcome to RiteVet. We can't wait to see you thrive in our global community!";
                $message .= "<br><br>Best regards, <br><br>RiteVet Management Team.";
            } else {
                $message = "Dear " . ucfirst($userData->firstName) . ", the review process is complete. Welcome to RiteVet! We are thrilled to have you on board as a new member of our vibrant community. This email serves as a warm welcome, introducing you to our platform and its incredible opportunities to connect with a diverse client base.";
                $message .= "<br><br>We believe that your talents deserve a big stage, and we are excited to be part of your journey to success. To get started, simply log in to your account, and start exploring the myriad of opportunities awaiting you.";
                $message .= "<br><br>If you have any questions or need assistance, please don't hesitate to reach out to our support team at ritevet@ritevet.com. We're here to help!";
                $message .= "<br><br>Once again, welcome to RiteVet. We can't wait to see you thrive in our global community!";
                $message .= "<br><br>Best regards, <br><br>RiteVet Management Team.";
            }

            $this->phpemail($to, $subject, $message);
        }

        $this->Flash->success(__('Data has been ' . $msg . ' successfully.'));

        // Modern redirect approach without query string
        if ($user->UTYPE == '2') {
            return $this->redirect([
                'controller' => 'Users',
                'action' => 'veterinarian'
            ]);
        } else {
            return $this->redirect([
                'controller' => 'Users',
                'action' => 'other_pet_service'
            ]);
        }
    }



}
