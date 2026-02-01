<?php
namespace App\Controller\Admin;
use App\Controller\Admin\Component\ImgComponent;

use App\Controller\Admin\AppController;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
/******************************************************************************************/
class PostsController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
    }

    public function index()
	{
	    $layoutTitle = 'Admin::ManagePost';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    $limit = 30; 

	    $this->paginate = [
	        'limit' => $limit,
	        'order' => ['Posts.created' => 'DESC'],
	    ];

	    $keyword = $this->request->getQuery('keyword');
	    $condition = [];
	    
	    if (!empty($keyword)) {
	        $condition = [
	            'OR' => [
	                'Posts.postTitle LIKE' => '%' . $keyword . '%',
	                'Users.firstName LIKE' => '%' . $keyword . '%',
					'Users.email LIKE' => '%' . $keyword . '%',
	            ]
	        ];
	    }

	    $query = $this->fetchTable('Posts')->find('all')->where($condition)->contain(['Users']);
	    $posts = $this->paginate($query);

	    $this->set(compact('posts', 'limit'));
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
	    $posts = $this->fetchTable('Posts')->get($id);

	    if ($this->fetchTable('Posts')->delete($posts)) {
	        $this->Flash->success(__('Post has been deleted.'));
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


	public function album()
	{
	    $layoutTitle = 'Admin::Manage Album';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    $limit = 30; 

	    $this->paginate = [
	        'limit' => $limit,
	        'order' => ['Images.created' => 'DESC'],
	    ];

	    $keyword = $this->request->getQuery('keyword');
	    $condition = [];
	    
	    if (!empty($keyword)) {
	        $condition = [
	            'OR' => [
	                'Users.firstName LIKE' => '%' . $keyword . '%',
					'Users.email LIKE' => '%' . $keyword . '%',
	            ]
	        ];
	    }

	    $query = $this->fetchTable('Images')->find('all')->where($condition)->contain(['Users']);
	    $posts = $this->paginate($query);

	    $this->set(compact('posts', 'limit'));
	}
    
}