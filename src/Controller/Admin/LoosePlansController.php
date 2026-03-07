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

        // Configure pagination
        $this->paginate = [
            'limit' => 10,
            'order' => ['LoosePlans.id' => 'ASC']
        ];

        // Fetch loose plans using pagination
        $loosePlansTable = $this->fetchTable('LoosePlans');
        $query = $loosePlansTable->find();

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

        $loosePlansTable = $this->fetchTable('LoosePlans');
        $loosePlan = $loosePlansTable->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $loosePlan = $loosePlansTable->patchEntity($loosePlan, $data);

            if ($loosePlansTable->save($loosePlan)) {
                $this->Flash->success(__('Loose plan added successfully.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Unable to add loose plan. Please, try again.'));
            }
        }

        $this->set(compact('loosePlan'));
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

        $loosePlansTable = $this->fetchTable('LoosePlans');
        $loosePlan = $loosePlansTable->get($id);

        if (!$loosePlan) {
            $this->Flash->error(__('Loose plan not found.'));
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $loosePlan = $loosePlansTable->patchEntity($loosePlan, $data);

            if ($loosePlansTable->save($loosePlan)) {
                $this->Flash->success(__('Loose plan updated successfully.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Unable to update loose plan. Please, try again.'));
            }
        }

        $this->set(compact('loosePlan'));
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
