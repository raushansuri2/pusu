<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\EventInterface;

class LoosePlansController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
    }

    public function index()
    {
        $this->viewBuilder()->setLayout('Admin/admin');
        $this->set('layoutTitle', 'Admin::Loose Plans');

        // Check session for admin role
        $session = $this->request->getSession();
        if ($session->read('AnnuityAdmin.role') !== 'Admin') {
            $session->destroy();
            return $this->redirect(['controller' => 'Admins', 'action' => 'login', 'prefix' => 'Admin']);
        }

        $keyword = $this->request->getQuery('keyword');
	    $condition[] = ['LoosePlans.id >' => 0];

	    if (!empty($keyword)) {
	        $condition = [
	            'OR' => [
	                'LoosePlans.plan_name LIKE' => "%{$keyword}%",
	            ]
	        ];
	    }

        // Configure pagination
        $this->paginate = [
            'limit' => 10,
            'order' => ['LoosePlans.id' => 'ASC']
        ];

        // Fetch loose plans using pagination
        $loosePlansTable = $this->fetchTable('LoosePlans');
        $query = $loosePlansTable->find()->where($condition);

        $loosePlans = $this->paginate($query);

        $this->set(compact('loosePlans'));
    }

    public function add()
    {
        $this->viewBuilder()->setLayout('Admin/admin');
        $this->set('layoutTitle', 'Admin::Add Loose Plan');

        // Check session for admin role
        $session = $this->request->getSession();
        if ($session->read('AnnuityAdmin.role') !== 'Admin') {
            $session->destroy();
            return $this->redirect(['controller' => 'Admins', 'action' => 'login', 'prefix' => 'Admin']);
        }

        // Fetch programs for multi-select
        $programsTable = $this->fetchTable('Programs');
        $programs = $programsTable->find('list', ['keyField' => 'id', 'valueField' => 'name'])->toArray();


        $loosePlansTable = $this->fetchTable('LoosePlans');
        $loosePlan = $loosePlansTable->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            // Handle multiple program IDs - convert array to comma-separated string
            if (isset($data['program_id']) && is_array($data['program_id'])) {
                $data['program_id'] = implode(',', array_filter($data['program_id']));
            } else {
                $data['program_id'] = '';
            }
            $loosePlan = $loosePlansTable->patchEntity($loosePlan, $data);

            if ($loosePlansTable->save($loosePlan)) {
                $this->Flash->success(__('Losse plan added successfully.'));
                return $this->redirect(['action' => 'index']);
            } else {
                //pr($loosePlansTable->getErrors()); die;
                $this->Flash->error(__('Unable to add losse plan. Please, try again.'));
            }
        }

        $this->set(compact('loosePlan','programs'));
    }

    public function edit($id = null)
    {
        $this->viewBuilder()->setLayout('Admin/admin');
        $this->set('layoutTitle', 'Admin::Edit Loose Plan');

        // Check session for admin role
        $session = $this->request->getSession();
        if ($session->read('AnnuityAdmin.role') !== 'Admin') {
            $session->destroy();
            return $this->redirect(['controller' => 'Admins', 'action' => 'login', 'prefix' => 'Admin']);
        }
        // Fetch programs for multi-select
        $programsTable = $this->fetchTable('Programs');
        $programs = $programsTable->find('list', ['keyField' => 'id', 'valueField' => 'name'])->toArray();

        $loosePlansTable = $this->fetchTable('LoosePlans');
        $loosePlan = $loosePlansTable->get($id);

        if (!$loosePlan) {
            $this->Flash->error(__('Loose plan not found.'));
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
             // Handle multiple program IDs - convert array to comma-separated string
            if (isset($data['program_id']) && is_array($data['program_id'])) {
                $data['program_id'] = implode(',', array_filter($data['program_id']));
            } else {
                $data['program_id'] = '';
            }

            $loosePlan = $loosePlansTable->patchEntity($loosePlan, $data);

            if ($loosePlansTable->save($loosePlan)) {
                $this->Flash->success(__('Loose plan updated successfully.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Unable to update loose plan. Please, try again.'));
            }
        }

        $this->set(compact('loosePlan','programs'));
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

        $loosePlansTable = $this->fetchTable('LoosePlans');
        $loosePlan = $loosePlansTable->get($id);

        if (!$loosePlan) {
            $this->Flash->error(__('Loose plan not found.'));
            return $this->redirect(['action' => 'index']);
        }

        if ($loosePlansTable->delete($loosePlan)) {
            $this->Flash->success(__('Loose plan deleted successfully.'));
        } else {
            $this->Flash->error(__('Unable to delete loose plan. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
