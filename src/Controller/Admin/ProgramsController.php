<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;

class ProgramsController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
    }

    public function index()
    {
        $this->viewBuilder()->setLayout('Admin/admin');
        $this->set('layoutTitle', 'Admin::Programs');

        // Check session for admin role
        $session = $this->request->getSession();
        if ($session->read('AnnuityAdmin.role') !== 'Admin') {
            $session->destroy();
            return $this->redirect(['controller' => 'Admins', 'action' => 'login', 'prefix' => 'Admin']);
        }

        // Fetch programs with network details
        $programsTable = $this->fetchTable('Programs');
        $programs = $programsTable->find()
            ->contain(['NetworksRepricing'])
            ->order(['Programs.id' => 'ASC'])
            ->all();

        $this->set(compact('programs'));
    }

    public function add()
    {
        $this->viewBuilder()->setLayout('Admin/admin');
        $this->set('layoutTitle', 'Admin::Add Program');

        // Check session for admin role
        $session = $this->request->getSession();
        if ($session->read('AnnuityAdmin.role') !== 'Admin') {
            $session->destroy();
            return $this->redirect(['controller' => 'Admins', 'action' => 'login', 'prefix' => 'Admin']);
        }

        $programsTable = $this->fetchTable('Programs');
        $program = $programsTable->newEmptyEntity();

        // Fetch networks for dropdown
        $networksTable = $this->fetchTable('NetworksRepricing');
        $networks = $networksTable->find('list', ['keyField' => 'id', 'valueField' => 'name'])->toArray();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $program = $programsTable->patchEntity($program, $data);

            if ($programsTable->save($program)) {
                $this->Flash->success(__('Program added successfully.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Unable to add program. Please, try again.'));
            }
        }

        $this->set(compact('program', 'networks'));
    }

    public function edit($id = null)
    {
        $this->viewBuilder()->setLayout('Admin/admin');
        $this->set('layoutTitle', 'Admin::Edit Program');

        // Check session for admin role
        $session = $this->request->getSession();
        if ($session->read('AnnuityAdmin.role') !== 'Admin') {
            $session->destroy();
            return $this->redirect(['controller' => 'Admins', 'action' => 'login', 'prefix' => 'Admin']);
        }

        $programsTable = $this->fetchTable('Programs');
        $program = $programsTable->get($id);

        if (!$program) {
            $this->Flash->error(__('Program not found.'));
            return $this->redirect(['action' => 'index']);
        }

        // Fetch networks for dropdown
        $networksTable = $this->fetchTable('NetworksRepricing');
        $networks = $networksTable->find('list', ['keyField' => 'id', 'valueField' => 'name'])->toArray();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $program = $programsTable->patchEntity($program, $data);

            if ($programsTable->save($program)) {
                $this->Flash->success(__('Program updated successfully.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Unable to update program. Please, try again.'));
            }
        }

        $this->set(compact('program', 'networks'));
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

        $programsTable = $this->fetchTable('Programs');
        $program = $programsTable->get($id);

        if (!$program) {
            $this->Flash->error(__('Program not found.'));
            return $this->redirect(['action' => 'index']);
        }

        if ($programsTable->delete($program)) {
            $this->Flash->success(__('Program deleted successfully.'));
        } else {
            $this->Flash->error(__('Unable to delete program. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
