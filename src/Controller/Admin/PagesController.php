<?php
namespace App\Controller\Admin;
use App\Controller\Admin\Component\ImgComponent;

use App\Controller\Admin\AppController;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
/******************************************************************************************/
class PagesController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
    }

    public function index()
	{
	    $layoutTitle = 'Admin::ManagePage';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    $limit = 30; 

	    $this->paginate = [
	        'limit' => $limit,
	        'order' => [
	            'Pages.created' => 'DESC'
	        ],
	    ];

	    $keyword = $this->request->getQuery('keyword');
	    $condition = [];
	    
	    if (!empty($keyword)) {
	        $condition = [
	            'OR' => [
	                'Pages.question LIKE' => '%' . $keyword . '%',
	                'Pages.answer LIKE' => '%' . $keyword . '%',
	            ]
	        ];
	    }

	    $query = $this->fetchTable('Pages')->find('all')->where($condition);
	    $pages = $this->paginate($query);

	    $this->set(compact('pages', 'limit'));
	}

    public function add()
	{
	    $layoutTitle = 'Admin::AddNewPage';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    $pages = $this->fetchTable('Pages')->newEmptyEntity();

	    if ($this->request->is('post')) {
	        $pages = $this->fetchTable('Pages')->patchEntity($pages, $this->request->getData(), ['validate' => false]);
	        $pages->created = date('Y-m-d H:i:s');

	        if ($this->fetchTable('Pages')->save($pages)) {
	            $this->Flash->success(__('Page added successfully.'));
	            return $this->redirect(['action' => 'index']);	
	        } else {
	            $this->Flash->error(__('Unable to add new page, please try again later.'));
	        }
	    }

	    $this->set(compact('pages'));
	}
    
    public function edit($id)
	{
	    $layoutTitle = 'Admin::EditPage';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    $pages = $this->fetchTable('Pages')->get($id);

	    if ($this->request->is(['patch', 'post', 'put'])) {
	        $pages = $this->fetchTable('Pages')->patchEntity($pages, $this->request->getData(), ['validate' => false]); 
	        
	        if ($this->fetchTable('Pages')->save($pages)) {
	            $this->Flash->success(__('The page updated successfully.'));
	            return $this->redirect(['action' => 'index']);
	        } else {
	            $this->Flash->error(__('Page information could not be saved. Please try again later.'));
	        }
	    }

	    $this->set(compact('pages'));
	}
   
    public function delete($id)
	{
	    $pages = $this->fetchTable('Pages')->get($id);

	    if ($this->fetchTable('Pages')->delete($pages)) {
	        $this->Flash->success(__('Page has been deleted.'));
	    } else {
	        $this->Flash->error(__('Page could not be deleted. Please, try again.'));
	    }

	    return $this->redirect(['action' => 'index']);
	}
    
    public function status($id)
	{
	    $pages = $this->fetchTable('Pages')->get($id);
	    $status = '1';
	    $msg = 'activated';

	    if ($pages->status == '1') {
	        $status = '0';
	        $msg = 'deactivated';
	    }

	    // Update the status using the fetchTable method
	    $pages->status = $status;

	    if ($this->fetchTable('Pages')->save($pages)) {
	        $this->Flash->success(__('Page has been ' . $msg . ' successfully.'));
	    } else {
	        $this->Flash->error(__('Unable to change the status of the page. Please try again.'));
	    }

	    return $this->redirect(['action' => 'index']);
	}
    
}