<?php
namespace App\Controller\Admin;
use App\Controller\Admin\Component\ImgComponent;

use App\Controller\Admin\AppController;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
/******************************************************************************************/
class NotificationsController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
    }

    public function index()
	{
	    $layoutTitle = 'Admin::Notification';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    $limit = 30; 

	    $this->paginate = [
	        'limit' => $limit,
	        'order' => ['Notifications.created' => 'DESC'],
	    ];

	    $keyword = $this->request->getQuery('keyword');
	    $condition = [];
	    
	    if (!empty($keyword)) {
	        $condition = [
	            'OR' => [
	                'Notifications.message LIKE' => '%' . $keyword . '%',
	                'Users.firstName LIKE' => '%' . $keyword . '%',
					'Users.email LIKE' => '%' . $keyword . '%',
	            ]
	        ];
	    }

	    $query = $this->fetchTable('Notifications')->find('all')->where($condition)->contain(['Users']);
	    $notifications = $this->paginate($query);

	    $this->set(compact('notifications', 'limit'));
	}

    public function add()
	{
	    $layoutTitle = 'Admin::AddNewPost';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    $posts = $this->fetchTable('Posts')->newEmptyEntity();

	    if ($this->request->is('post')) {
	        $posts = $this->fetchTable('Posts')->patchEntity($posts, $this->request->getData(), ['validate' => false]);
	        $posts->created = date('Y-m-d H:i:s');

	        if ($this->fetchTable('Posts')->save($posts)) {
	            $this->Flash->success(__('Post added successfully.'));
	            return $this->redirect(['action' => 'index']);	
	        } else {
	            $this->Flash->error(__('Unable to add new post, please try again later.'));
	        }
	    }

	    $this->set(compact('posts'));
	}
    
    public function edit($id)
	{
	    $layoutTitle = 'Admin::EditPost';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    $posts = $this->fetchTable('Posts')->get($id);

	    if ($this->request->is(['patch', 'post', 'put'])) {
	        $posts = $this->fetchTable('Posts')->patchEntity($posts, $this->request->getData(), ['validate' => false]); 
	        
	        if ($this->fetchTable('Posts')->save($posts)) {
	            $this->Flash->success(__('The post updated successfully.'));
	            return $this->redirect(['action' => 'index']);
	        } else {
	            $this->Flash->error(__('Post information could not be saved. Please try again later.'));
	        }
	    }

	    $this->set(compact('posts'));
	}
   
    public function delete($id)
	{
	    $posts = $this->fetchTable('Notifications')->get($id);

	    if ($this->fetchTable('Notifications')->delete($posts)) {
	        $this->Flash->success(__('Notification has been deleted.'));
	    } else {
	        $this->Flash->error(__('Post could not be deleted. Please, try again.'));
	    }

	    return $this->redirect(['action' => 'index']);
	}
    
    public function status($id)
	{
	    $posts = $this->fetchTable('Posts')->get($id);
	    $status = '1';
	    $msg = 'activated';

	    if ($posts->status == '1') {
	        $status = '0';
	        $msg = 'deactivated';
	    }

	    // Update the status using the fetchTable method
	    $posts->status = $status;

	    if ($this->fetchTable('Posts')->save($posts)) {
	        $this->Flash->success(__('Post has been ' . $msg . ' successfully.'));
	    } else {
	        $this->Flash->error(__('Unable to change the status of the post. Please try again.'));
	    }

	    return $this->redirect(['action' => 'index']);
	}

	public function details($id)
	{
	    $layoutTitle = 'Admin::Post Details';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin'); 

	    try {
	        // Fetch the user with related data using named arguments
	        $post = $this->Posts->get($id, contain: []);
	        
	        // Set the user data to the view
	        $this->set(compact('post'));
	    } catch (\Cake\Datasource\Exception\RecordNotFoundException $e) {
	        // Handle the case where the user does not exist
	        $this->Flash->error(__('Post does not exist.'));
	        return $this->redirect(['controller' => 'Posts', 'action' => 'index']);
	    }
	}
    
}