<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;

class NetworksRepricingsController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
    }

    public function index()
    {
        $this->viewBuilder()->setLayout('Admin/admin');
        $this->set('layoutTitle', 'Admin::Networks Repricing');

        // Check session for admin role
        $session = $this->request->getSession();
        if ($session->read('AnnuityAdmin.role') !== 'Admin') {
            $session->destroy();
            return $this->redirect(['controller' => 'Admins', 'action' => 'login', 'prefix' => 'Admin']);
        }

        $keyword = $this->request->getQuery('keyword');
	    $condition[] = ['NetworksRepricing.id >' => 0];

	    if (!empty($keyword)) {
	        $condition = [
	            'OR' => [
	                'NetworksRepricing.name LIKE' => "%{$keyword}%",
	            ]
	        ];
	    }
        // Configure pagination
        $this->paginate = [
            'limit' => 10,
            'order' => ['NetworksRepricing.id' => 'ASC']
        ];

        // Fetch networks without containing Programs to avoid Entity objects
        $networksTable = $this->fetchTable('NetworksRepricing');
        $query = $networksTable->find()->where($condition);

        $networks = $this->paginate($query);

        $this->set(compact('networks'));
    }

    public function add()
    {
        $this->viewBuilder()->setLayout('Admin/admin');
        $this->set('layoutTitle', 'Admin::Add Network Repricing');

        // Check session for admin role
        $session = $this->request->getSession();
        if ($session->read('AnnuityAdmin.role') !== 'Admin') {
            $session->destroy();
            return $this->redirect(['controller' => 'Admins', 'action' => 'login', 'prefix' => 'Admin']);
        }

        $networksTable = $this->fetchTable('NetworksRepricing');
        $network = $networksTable->newEmptyEntity();

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

            $network = $networksTable->patchEntity($network, $data);

            if ($networksTable->save($network)) {
                $this->Flash->success(__('Network repricing added successfully.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Unable to add network repricing. Please, try again.'));
            }
        }

        $this->set(compact('network', 'programs'));
    }

    public function edit($id = null)
    {
        $this->viewBuilder()->setLayout('Admin/admin');
        $this->set('layoutTitle', 'Admin::Edit Network Repricing');

        // Check session for admin role
        $session = $this->request->getSession();
        if ($session->read('AnnuityAdmin.role') !== 'Admin') {
            $session->destroy();
            return $this->redirect(['controller' => 'Admins', 'action' => 'login', 'prefix' => 'Admin']);
        }

        $networksTable = $this->fetchTable('NetworksRepricing');
        $network = $networksTable->get($id);

        if (!$network) {
            $this->Flash->error(__('Network repricing not found.'));
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

            $network = $networksTable->patchEntity($network, $data);

            if ($networksTable->save($network)) {
                $this->Flash->success(__('Network repricing updated successfully.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Unable to update network repricing. Please, try again.'));
            }
        }

        // Convert comma-separated program_id back to array for the form
        if (!empty($network->program_id)) {
            if (is_string($network->program_id)) {
                $network->program_id = explode(',', $network->program_id);
            }
        }

        $this->set(compact('network', 'programs'));
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

        $networksTable = $this->fetchTable('NetworksRepricing');
        $network = $networksTable->get($id);

        if (!$network) {
            $this->Flash->error(__('Network repricing not found.'));
            return $this->redirect(['action' => 'index']);
        }

        if ($networksTable->delete($network)) {
            $this->Flash->success(__('Network repricing deleted successfully.'));
        } else {
            $this->Flash->error(__('Unable to delete network repricing. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
