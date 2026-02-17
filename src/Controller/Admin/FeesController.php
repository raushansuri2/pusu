<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;

class FeesController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
    }

    public function index()
    {
        $this->viewBuilder()->setLayout('Admin/admin');
        $this->set('layoutTitle', 'Admin::Fees');

        // Check session for admin role
        $session = $this->request->getSession();
        if ($session->read('AnnuityAdmin.role') !== 'Admin') {
            $session->destroy();
            return $this->redirect(['controller' => 'Admins', 'action' => 'login', 'prefix' => 'Admin']);
        }

        // Fetch fees with program details using contain
        $feesTable = $this->fetchTable('Fees');
        $fees = $feesTable->find()
            ->contain(['Programs'])
            ->order(['Fees.id' => 'ASC'])
            ->all();

        $this->set(compact('fees'));
    }

    public function add()
    {
        $this->viewBuilder()->setLayout('Admin/admin');
        $this->set('layoutTitle', 'Admin::Add Fee');

        // Check session for admin role
        $session = $this->request->getSession();
        if ($session->read('AnnuityAdmin.role') !== 'Admin') {
            $session->destroy();
            return $this->redirect(['controller' => 'Admins', 'action' => 'login', 'prefix' => 'Admin']);
        }

        $feesTable = $this->fetchTable('Fees');
        $fee = $feesTable->newEmptyEntity();

        // Fetch programs for dropdown
        $programsTable = $this->fetchTable('Programs');
        $programs = $programsTable->find('list', ['keyField' => 'id', 'valueField' => 'name'])->toArray();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $fee = $feesTable->patchEntity($fee, $data);

            if ($feesTable->save($fee)) {
                $this->Flash->success(__('Fee added successfully.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Unable to add fee. Please, try again.'));
            }
        }

        $this->set(compact('fee', 'programs'));
    }

    public function edit($id = null)
    {
        $this->viewBuilder()->setLayout('Admin/admin');
        $this->set('layoutTitle', 'Admin::Edit Fee');

        // Check session for admin role
        $session = $this->request->getSession();
        if ($session->read('AnnuityAdmin.role') !== 'Admin') {
            $session->destroy();
            return $this->redirect(['controller' => 'Admins', 'action' => 'login', 'prefix' => 'Admin']);
        }

        $feesTable = $this->fetchTable('Fees');
        $fee = $feesTable->get($id);

        if (!$fee) {
            $this->Flash->error(__('Fee not found.'));
            return $this->redirect(['action' => 'index']);
        }

        // Fetch programs and networks for dropdown
        $programsTable = $this->fetchTable('Programs');
        $programs = $programsTable->find('list', ['keyField' => 'id', 'valueField' => 'name'])->toArray();

        $networksTable = $this->fetchTable('NetworksRepricing');
        $networks = $networksTable->find('list', ['keyField' => 'id', 'valueField' => 'name'])->toArray();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $fee = $feesTable->patchEntity($fee, $data);

            if ($feesTable->save($fee)) {
                $this->Flash->success(__('Fee updated successfully.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Unable to update fee. Please, try again.'));
            }
        }

        $this->set(compact('fee', 'programs'));
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

        $feesTable = $this->fetchTable('Fees');
        $fee = $feesTable->get($id);

        if (!$fee) {
            $this->Flash->error(__('Fee not found.'));
            return $this->redirect(['action' => 'index']);
        }

        if ($feesTable->delete($fee)) {
            $this->Flash->success(__('Fee deleted successfully.'));
        } else {
            $this->Flash->error(__('Unable to delete fee. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
