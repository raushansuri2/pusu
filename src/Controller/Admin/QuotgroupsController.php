<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;
/******************************************************************************************/
class QuotgroupsController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
    }

    public function index()
	{
	    $layoutTitle = 'Admin::ManageQuotingGroups';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    $limit = 30;

	    $this->paginate = [
	        'limit' => $limit,
	        'order' => [
	            'Quotgroups.id' => 'DESC'
	        ],
	    ];

	    $keyword = $this->request->getQuery('keyword');
	    $condition = [];

	    if (!empty($keyword)) {
	        $condition = [
	            'OR' => [
	                'Quotgroups.group_name LIKE' => '%' . $keyword . '%',
	                'Quotgroups.SIC_Code LIKE' => '%' . $keyword . '%',
	                'Quotgroups.city LIKE' => '%' . $keyword . '%',
	                'Quotgroups.state_name LIKE' => '%' . $keyword . '%',
	                'Quotgroups.zip LIKE' => '%' . $keyword . '%',
	            ]
	        ];
	    }

	    $query = $this->fetchTable('Quotgroups')->find('all')->where($condition);
	    $quotgroups = $this->paginate($query);

	    $this->set(compact('quotgroups', 'limit'));
	}

    public function add()
	{
	    $layoutTitle = 'Admin::AddNewQuotingGroup';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    $quotgroup = $this->fetchTable('Quotgroups')->newEmptyEntity();

	    if ($this->request->is('post')) {
	        $quotgroup = $this->fetchTable('Quotgroups')->patchEntity($quotgroup, $this->request->getData(), ['validate' => false]);

	        if ($this->fetchTable('Quotgroups')->save($quotgroup)) {
	            $this->Flash->success(__('Quoting group added successfully.'));
	            return $this->redirect(['action' => 'index']);
	        } else {
	            $this->Flash->error(__('Unable to add new quoting group, please try again later.'));
	        }
	    }

	    $this->set(compact('quotgroup'));
	}

    public function edit($id)
	{
	    $layoutTitle = 'Admin::EditQuotingGroup';
	    $this->set(compact('layoutTitle'));
	    $this->viewBuilder()->setLayout('Admin/admin');

	    $quotgroup = $this->fetchTable('Quotgroups')->get($id);

	    if ($this->request->is(['patch', 'post', 'put'])) {
	        $quotgroup = $this->fetchTable('Quotgroups')->patchEntity($quotgroup, $this->request->getData(), ['validate' => false]);

	        if ($this->fetchTable('Quotgroups')->save($quotgroup)) {
	            $this->Flash->success(__('The quoting group updated successfully.'));
	            return $this->redirect(['action' => 'index']);
	        } else {
	            $this->Flash->error(__('Quoting group information could not be saved. Please try again later.'));
	        }
	    }

	    $this->set(compact('quotgroup'));
	}

    public function delete($id)
	{
	    $this->request->allowMethod(['post', 'delete', 'get']);

	    $quotgroup = $this->fetchTable('Quotgroups')->get($id);

	    if ($this->fetchTable('Quotgroups')->delete($quotgroup)) {
	        $this->Flash->success(__('Quoting group has been deleted.'));
	    } else {
	        $this->Flash->error(__('Quoting group could not be deleted. Please, try again.'));
	    }

	    return $this->redirect(['action' => 'index']);
	}

    public function status($id)
	{
	    $quotgroup = $this->fetchTable('Quotgroups')->get($id);
	    $status = '1';
	    $msg = 'activated';

	    if ($quotgroup->status == '1') {
	        $status = '0';
	        $msg = 'deactivated';
	    }

	    $quotgroup->status = $status;

	    if ($this->fetchTable('Quotgroups')->save($quotgroup)) {
	        $this->Flash->success(__('Quoting group has been ' . $msg . ' successfully.'));
	    } else {
	        $this->Flash->error(__('Unable to change the status of the quoting group. Please try again.'));
	    }

	    return $this->redirect(['action' => 'index']);
	}

}
