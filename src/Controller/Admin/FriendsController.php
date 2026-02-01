<?php
namespace App\Controller\Admin;
use App\Controller\Admin\Component\ImgComponent;

use App\Controller\Admin\AppController;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
/******************************************************************************************/
class FriendsController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
    }

    public function index()
	{
	    $layoutTitle = 'Admin::ManageFriend';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    $limit = 30; 

	    $this->paginate = [
	        'limit' => $limit,
	        'order' => [
	            'Friends.created' => 'DESC'
	        ],
	    ];

	    $keyword = $this->request->getQuery('keyword');
	    $condition[] = [];
	    
	    if (!empty($keyword)) {
	        $condition = [
	            'OR' => [
	                'Friends.firstName LIKE' => '%' . $keyword . '%',
	                'Friends.lastName LIKE' => '%' . $keyword . '%',
	            ]
	        ];
	    }

	    $query = $this->fetchTable('Friends')->find('all')->where($condition)->contain(['Senders','Receivers']);
	    $friends = $this->paginate($query);

	    $this->set(compact('friends', 'limit'));
	}

    public function add()
	{
	    $layoutTitle = 'Admin::AddNewFriend';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    $friends = $this->fetchTable('Friends')->newEmptyEntity();

	    if ($this->request->is('post')) {
	        $friends = $this->fetchTable('Friends')->patchEntity($friends, $this->request->getData(), ['validate' => false]);
	        $friends->created = date('Y-m-d H:i:s');

	        if ($this->fetchTable('Friends')->save($friends)) {
	            $this->Flash->success(__('Friend added successfully.'));
	            return $this->redirect(['action' => 'index']);	
	        } else {
	            $this->Flash->error(__('Unable to add new friend, please try again later.'));
	        }
	    }

	    $this->set(compact('friends'));
	}
    
    public function edit($id)
	{
	    $layoutTitle = 'Admin::EditFriend';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    $friends = $this->fetchTable('Friends')->get($id);

	    if ($this->request->is(['patch', 'post', 'put'])) {
	        $friends = $this->fetchTable('Friends')->patchEntity($friends, $this->request->getData(), ['validate' => false]); 
	        
	        if ($this->fetchTable('Friends')->save($friends)) {
	            $this->Flash->success(__('The friend updated successfully.'));
	            return $this->redirect(['action' => 'index']);
	        } else {
	            $this->Flash->error(__('Friend information could not be saved. Please try again later.'));
	        }
	    }

	    $this->set(compact('friends'));
	}
   
    public function delete($id)
	{
	    $friends = $this->fetchTable('Friends')->get($id);

	    if ($this->fetchTable('Friends')->delete($friends)) {
	        $this->Flash->success(__('Friend has been deleted.'));
	    } else {
	        $this->Flash->error(__('Friend could not be deleted. Please, try again.'));
	    }

	    return $this->redirect(['action' => 'index']);
	}
    
    public function status($id)
	{
	    $friends = $this->fetchTable('Friends')->get($id);
	    $status = '1';
	    $msg = 'activated';

	    if ($friends->status == '1') {
	        $status = '0';
	        $msg = 'deactivated';
	    }

	    // Update the status using the fetchTable method
	    $friends->status = $status;

	    if ($this->fetchTable('Friends')->save($friends)) {
	        $this->Flash->success(__('Friend has been ' . $msg . ' successfully.'));
	    } else {
	        $this->Flash->error(__('Unable to change the status of the friend. Please try again.'));
	    }

	    return $this->redirect(['action' => 'index']);
	}

	public function block()
	{
	    $layoutTitle = 'Admin::ManageFriend';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    $limit = 30; 

	    $this->paginate = [
	        'limit' => $limit,
	        'order' => [
	            'Friends.created' => 'DESC'
	        ],
	    ];

	    $keyword = $this->request->getQuery('keyword');
	    $condition[] = ['OR' => [
	                'Friends.block_by_sender' => 1,
	                'Friends.block_by_receiver' => 1,
	            ]];
	    
	    if (!empty($keyword)) {
	        $condition = [
	            'OR' => [
	                'Friends.firstName LIKE' => '%' . $keyword . '%',
	                'Friends.lastName LIKE' => '%' . $keyword . '%',
	            ]
	        ];
	    }

	    $query = $this->fetchTable('Friends')->find('all')->where($condition)->contain(['Senders','Receivers']);
	    $friends = $this->paginate($query);

	    $this->set(compact('friends', 'limit'));
	}
    
}