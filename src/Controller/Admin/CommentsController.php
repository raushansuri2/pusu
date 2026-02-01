<?php
namespace App\Controller\Admin;
use App\Controller\Admin\Component\ImgComponent;

use App\Controller\Admin\AppController;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
/******************************************************************************************/
class CommentsController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
    }

    public function index($id=NULL)
	{
	    $layoutTitle = 'Admin::ManageComment';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    $limit = 30; 

	    $this->paginate = [
	        'limit' => $limit,
	        'order' => [
	            'Comments.created' => 'DESC'
	        ],
	    ];
		if($id !=""){
			$condition = ['Comments.postId'=>$id];
		}
	    $keyword = $this->request->getQuery('keyword');
	    $condition = [];
	    
	    if (!empty($keyword)) {
	        $condition = [
	            'OR' => [
	                'Comments.comment LIKE' => '%' . $keyword . '%',
	                'Users.email LIKE' => '%' . $keyword . '%',
					'Users.firstName LIKE' => '%' . $keyword . '%',
					'Posts.postTitle LIKE' => '%' . $keyword . '%',
	            ]
	        ];
	    }

	    $query = $this->fetchTable('Comments')->find('all')->where($condition)->contain(['Posts'=>['Users'], 'Users']);
	    $comments = $this->paginate($query);

	    $this->set(compact('comments', 'limit'));
	}

    public function add()
	{
	    $layoutTitle = 'Admin::AddNewComment';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    $comments = $this->fetchTable('Comments')->newEmptyEntity();

	    if ($this->request->is('post')) {
	        $comments = $this->fetchTable('Comments')->patchEntity($comments, $this->request->getData(), ['validate' => false]);
	        $comments->created = date('Y-m-d H:i:s');

	        if ($this->fetchTable('Comments')->save($comments)) {
	            $this->Flash->success(__('Comment added successfully.'));
	            return $this->redirect(['action' => 'index']);	
	        } else {
	            $this->Flash->error(__('Unable to add new comment, please try again later.'));
	        }
	    }

	    $this->set(compact('comments'));
	}
    
    public function edit($id)
	{
	    $layoutTitle = 'Admin::EditComment';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    $comments = $this->fetchTable('Comments')->get($id);

	    if ($this->request->is(['patch', 'post', 'put'])) {
	        $comments = $this->fetchTable('Comments')->patchEntity($comments, $this->request->getData(), ['validate' => false]); 
	        
	        if ($this->fetchTable('Comments')->save($comments)) {
	            $this->Flash->success(__('The comment updated successfully.'));
	            return $this->redirect(['action' => 'index']);
	        } else {
	            $this->Flash->error(__('Comment information could not be saved. Please try again later.'));
	        }
	    }

	    $this->set(compact('comments'));
	}
   
    public function delete($id)
	{
	    $comments = $this->fetchTable('Comments')->get($id);

	    if ($this->fetchTable('Comments')->delete($comments)) {
	        $this->Flash->success(__('Comment has been deleted.'));
	    } else {
	        $this->Flash->error(__('Comment could not be deleted. Please, try again.'));
	    }

	    return $this->redirect(['action' => 'index']);
	}
    
    public function status($id)
	{
	    $comments = $this->fetchTable('Comments')->get($id);
	    $status = '1';
	    $msg = 'activated';

	    if ($comments->status == '1') {
	        $status = '0';
	        $msg = 'deactivated';
	    }

	    // Update the status using the fetchTable method
	    $comments->status = $status;

	    if ($this->fetchTable('Comments')->save($comments)) {
	        $this->Flash->success(__('Comment has been ' . $msg . ' successfully.'));
	    } else {
	        $this->Flash->error(__('Unable to change the status of the comment. Please try again.'));
	    }

	    return $this->redirect(['action' => 'index']);
	}
    
}