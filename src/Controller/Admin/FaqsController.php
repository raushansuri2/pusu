<?php
namespace App\Controller\Admin;
use App\Controller\Admin\Component\ImgComponent;

use App\Controller\Admin\AppController;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
/******************************************************************************************/
class FaqsController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
    }

    public function index()
	{
	    $layoutTitle = 'Admin::ManageFaq';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    $limit = 30; 

	    $this->paginate = [
	        'limit' => $limit,
	        'order' => [
	            'Faqs.created' => 'DESC'
	        ],
	    ];

	    $keyword = $this->request->getQuery('keyword');
	    $condition = [];
	    
	    if (!empty($keyword)) {
	        $condition = [
	            'OR' => [
	                'Faqs.question LIKE' => '%' . $keyword . '%',
	                'Faqs.answer LIKE' => '%' . $keyword . '%',
	            ]
	        ];
	    }

	    $query = $this->fetchTable('Faqs')->find('all')->where($condition);
	    $faqs = $this->paginate($query);

	    $this->set(compact('faqs', 'limit'));
	}

    public function add()
	{
	    $layoutTitle = 'Admin::AddNewFaq';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    $faqs = $this->fetchTable('Faqs')->newEmptyEntity();

	    if ($this->request->is('post')) {
	        $faqs = $this->fetchTable('Faqs')->patchEntity($faqs, $this->request->getData(), ['validate' => false]);
	        $faqs->created = date('Y-m-d H:i:s');

	        if ($this->fetchTable('Faqs')->save($faqs)) {
	            $this->Flash->success(__('Faq added successfully.'));
	            return $this->redirect(['action' => 'index']);	
	        } else {
	            $this->Flash->error(__('Unable to add new faq, please try again later.'));
	        }
	    }

	    $this->set(compact('faqs'));
	}
    
    public function edit($id)
	{
	    $layoutTitle = 'Admin::EditFaq';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    $faqs = $this->fetchTable('Faqs')->get($id);

	    if ($this->request->is(['patch', 'post', 'put'])) {
	        $faqs = $this->fetchTable('Faqs')->patchEntity($faqs, $this->request->getData(), ['validate' => false]); 
	        
	        if ($this->fetchTable('Faqs')->save($faqs)) {
	            $this->Flash->success(__('The faq updated successfully.'));
	            return $this->redirect(['action' => 'index']);
	        } else {
	            $this->Flash->error(__('Faq information could not be saved. Please try again later.'));
	        }
	    }

	    $this->set(compact('faqs'));
	}
   
    public function delete($id)
	{
	    $faqs = $this->fetchTable('Faqs')->get($id);

	    if ($this->fetchTable('Faqs')->delete($faqs)) {
	        $this->Flash->success(__('Faq has been deleted.'));
	    } else {
	        $this->Flash->error(__('Faq could not be deleted. Please, try again.'));
	    }

	    return $this->redirect(['action' => 'index']);
	}
    
    public function status($id)
	{
	    $faqs = $this->fetchTable('Faqs')->get($id);
	    $status = '1';
	    $msg = 'activated';

	    if ($faqs->status == '1') {
	        $status = '0';
	        $msg = 'deactivated';
	    }

	    // Update the status using the fetchTable method
	    $faqs->status = $status;

	    if ($this->fetchTable('Faqs')->save($faqs)) {
	        $this->Flash->success(__('Faq has been ' . $msg . ' successfully.'));
	    } else {
	        $this->Flash->error(__('Unable to change the status of the faq. Please try again.'));
	    }

	    return $this->redirect(['action' => 'index']);
	}
    
}