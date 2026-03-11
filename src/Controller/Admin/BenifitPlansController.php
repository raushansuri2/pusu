<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;

class BenifitPlansController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
    }

    public function index()
    {
        $this->viewBuilder()->setLayout('Admin/admin');
        $this->set('layoutTitle', 'Admin::Benefit Plans');

        // Check session for admin role
        $session = $this->request->getSession();
        if ($session->read('AnnuityAdmin.role') !== 'Admin') {
            $session->destroy();
            return $this->redirect(['controller' => 'Admins', 'action' => 'login', 'prefix' => 'Admin']);
        }

        $keyword = $this->request->getQuery('keyword');
	    $condition[] = ['BenifitPlans.id >' => 0];

	    if (!empty($keyword)) {
	        $condition = [
	            'OR' => [
	                'BenifitPlans.plan_name LIKE' => "%{$keyword}%",
	            ]
	        ];
	    }

        // Configure pagination
        $this->paginate = [
            'limit' => 10,
            'order' => ['BenifitPlans.id' => 'ASC']
        ];

        // Fetch benefit plans using pagination
        $benifitPlansTable = $this->fetchTable('BenifitPlans');
        $query = $benifitPlansTable->find()->where($condition);

        $benifitPlans = $this->paginate($query);

        $this->set(compact('benifitPlans'));
    }

    public function add()
    {
        $this->viewBuilder()->setLayout('Admin/admin');
        $this->set('layoutTitle', 'Admin::Add Benefit Plan');

        // Check session for admin role
        $session = $this->request->getSession();
        if ($session->read('AnnuityAdmin.role') !== 'Admin') {
            $session->destroy();
            return $this->redirect(['controller' => 'Admins', 'action' => 'login', 'prefix' => 'Admin']);
        }

        $benifitPlansTable = $this->fetchTable('BenifitPlans');
        $benifitPlan = $benifitPlansTable->newEmptyEntity();

        // Fetch programs for multi-select
        $programsTable = $this->fetchTable('Programs');
        $programs = $programsTable->find('list', ['keyField' => 'id', 'valueField' => 'name'])->toArray();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            // Handle multiple program IDs - convert array to comma-separated string
            if (isset($data['program_id']) && is_array($data['program_id'])) {
                $data['program_id'] = implode(',', array_filter($data['program_id']));
            } else {
                $data['program_id'] = '';
            }

            $benifitPlan = $benifitPlansTable->patchEntity($benifitPlan, $data);

            if ($benifitPlansTable->save($benifitPlan)) {
                $this->Flash->success(__('Benefit plan added successfully.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Unable to add benefit plan. Please, try again.'));
            }
        }

        $this->set(compact('benifitPlan', 'programs'));
    }

    public function edit($id = null)
    {
        $this->viewBuilder()->setLayout('Admin/admin');
        $this->set('layoutTitle', 'Admin::Edit Benefit Plan');

        // Check session for admin role
        $session = $this->request->getSession();
        if ($session->read('AnnuityAdmin.role') !== 'Admin') {
            $session->destroy();
            return $this->redirect(['controller' => 'Admins', 'action' => 'login', 'prefix' => 'Admin']);
        }

        $benifitPlansTable = $this->fetchTable('BenifitPlans');
        $benifitPlan = $benifitPlansTable->get($id);

        if (!$benifitPlan) {
            $this->Flash->error(__('Benefit plan not found.'));
            return $this->redirect(['action' => 'index']);
        }

        // Fetch programs for multi-select
        $programsTable = $this->fetchTable('Programs');
        $programs = $programsTable->find('list', ['keyField' => 'id', 'valueField' => 'name'])->toArray();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            // Handle multiple program IDs - convert array to comma-separated string
            if (isset($data['program_id']) && is_array($data['program_id'])) {
                $data['program_id'] = implode(',', array_filter($data['program_id']));
            } else {
                $data['program_id'] = '';
            }

            $benifitPlan = $benifitPlansTable->patchEntity($benifitPlan, $data);

            if ($benifitPlansTable->save($benifitPlan)) {
                $this->Flash->success(__('Benefit plan updated successfully.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Unable to update benefit plan. Please, try again.'));
            }
        }

        // Convert comma-separated program_id back to array for the form
        if (!empty($benifitPlan->program_id)) {
            if (is_string($benifitPlan->program_id)) {
                $benifitPlan->program_id = explode(',', $benifitPlan->program_id);
            }
        }

        $this->set(compact('benifitPlan', 'programs'));
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

        $benifitPlansTable = $this->fetchTable('BenifitPlans');
        $benifitPlan = $benifitPlansTable->get($id);

        if (!$benifitPlan) {
            $this->Flash->error(__('Benefit plan not found.'));
            return $this->redirect(['action' => 'index']);
        }

        if ($benifitPlansTable->delete($benifitPlan)) {
            $this->Flash->success(__('Benefit plan deleted successfully.'));
        } else {
            $this->Flash->error(__('Unable to delete benefit plan. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
