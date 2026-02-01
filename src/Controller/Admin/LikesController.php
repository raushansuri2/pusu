<?php
namespace App\Controller\Admin;
use App\Controller\Admin\Component\ImgComponent;

use App\Controller\Admin\AppController;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
/******************************************************************************************/
class LikesController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
    }

    public function index($id=NULL)
	{
	    $layoutTitle = 'Admin::ManageLike';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    $limit = 30; 

	    $this->paginate = [
	        'limit' => $limit,
	        'order' => [
	            'Likes.created' => 'DESC'
	        ],
	    ];
		if($id !=""){
			$condition = ['Likes.postId'=>$id];
		}
	    $keyword = $this->request->getQuery('keyword');
	    $condition = [];
	    
	    if (!empty($keyword)) {
	        $condition = [
	            'OR' => [
	                
	                'Users.firstName LIKE' => '%' . $keyword . '%',
					'Users.email LIKE' => '%' . $keyword . '%',
					'Posts.postTitle LIKE' => '%' . $keyword . '%',
	            ]
	        ];
	    }

	    $query = $this->fetchTable('Likes')->find('all')->where($condition)->contain(['Posts'=>['Users'], 'Users']);
	    $likes = $this->paginate($query);

	    $this->set(compact('likes', 'limit'));
	}

    public function add()
	{
	    $layoutTitle = 'Admin::AddNewLike';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    $likes = $this->fetchTable('Likes')->newEmptyEntity();

	    if ($this->request->is('post')) {
	        $likes = $this->fetchTable('Likes')->patchEntity($likes, $this->request->getData(), ['validate' => false]);
	        $likes->created = date('Y-m-d H:i:s');

	        if ($this->fetchTable('Likes')->save($likes)) {
	            $this->Flash->success(__('Like added successfully.'));
	            return $this->redirect(['action' => 'index']);	
	        } else {
	            $this->Flash->error(__('Unable to add new like, please try again later.'));
	        }
	    }

	    $this->set(compact('likes'));
	}
    
    public function edit($id)
	{
	    $layoutTitle = 'Admin::EditLike';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    $likes = $this->fetchTable('Likes')->get($id);

	    if ($this->request->is(['patch', 'post', 'put'])) {
	        $likes = $this->fetchTable('Likes')->patchEntity($likes, $this->request->getData(), ['validate' => false]); 
	        
	        if ($this->fetchTable('Likes')->save($likes)) {
	            $this->Flash->success(__('The like updated successfully.'));
	            return $this->redirect(['action' => 'index']);
	        } else {
	            $this->Flash->error(__('Like information could not be saved. Please try again later.'));
	        }
	    }

	    $this->set(compact('likes'));
	}
   
    public function delete($id)
	{
	    $likes = $this->fetchTable('Likes')->get($id);

	    if ($this->fetchTable('Likes')->delete($likes)) {
	        $this->Flash->success(__('Like has been deleted.'));
	    } else {
	        $this->Flash->error(__('Like could not be deleted. Please, try again.'));
	    }

	    return $this->redirect(['action' => 'index']);
	}
    
    public function status($id)
	{
	    $likes = $this->fetchTable('Likes')->get($id);
	    $status = '1';
	    $msg = 'activated';

	    if ($likes->status == '1') {
	        $status = '0';
	        $msg = 'deactivated';
	    }

	    // Update the status using the fetchTable method
	    $likes->status = $status;

	    if ($this->fetchTable('Likes')->save($likes)) {
	        $this->Flash->success(__('Like has been ' . $msg . ' successfully.'));
	    } else {
	        $this->Flash->error(__('Unable to change the status of the like. Please try again.'));
	    }

	    return $this->redirect(['action' => 'index']);
	}
    
}