<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;

class QuotingPartnerPermissionsController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
    }

    public function index()
    {
        $this->viewBuilder()->setLayout('Admin/admin');
        $this->set('layoutTitle', 'Admin::Quoting Partner Permissions');

        // Check session for admin role
        $session = $this->request->getSession();
        if ($session->read('AnnuityAdmin.role') !== 'Admin') {
            $session->destroy();
            return $this->redirect(['controller' => 'Admins', 'action' => 'login', 'prefix' => 'Admin']);
        }

        // Configure pagination
        $this->paginate = [
            'limit' => 25,
            'order' => ['QuotingPartnerPermissions.id' => 'ASC']
        ];

        // Fetch all quoting partner permissions with user details
        $quotingPartnerPermissionsTable = $this->fetchTable('QuotingPartnerPermissions');
        $permissions = $this->paginate($quotingPartnerPermissionsTable->find()
            ->contain(['Users']));

        $this->set(compact('permissions'));
    }

    public function edit($id = null)
    {
        $this->viewBuilder()->setLayout('Admin/admin');
        $this->set('layoutTitle', 'Admin::Edit Quoting Partner Permission');

        // Check session for admin role
        $session = $this->request->getSession();
        if ($session->read('AnnuityAdmin.role') !== 'Admin') {
            $session->destroy();
            return $this->redirect(['controller' => 'Admins', 'action' => 'login', 'prefix' => 'Admin']);
        }

        $quotingPartnerPermissionsTable = $this->fetchTable('QuotingPartnerPermissions');
        $permission = $quotingPartnerPermissionsTable->get($id);

        if (!$permission) {
            $this->Flash->error(__('Permission not found.'));
            return $this->redirect(['action' => 'index']);
        }

        // Fetch users for dropdown
        $usersTable = $this->fetchTable('Users');
        $users = $usersTable->find('list', ['keyField' => 'id', 'valueField' => 'email'])->toArray();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $permission = $quotingPartnerPermissionsTable->patchEntity($permission, $data);

            if ($quotingPartnerPermissionsTable->save($permission)) {
                $this->Flash->success(__('Permission updated successfully.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Unable to update permission. Please, try again.'));
            }
        }

        $this->set(compact('permission', 'users'));
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

        $quotingPartnerPermissionsTable = $this->fetchTable('QuotingPartnerPermissions');
        $permission = $quotingPartnerPermissionsTable->get($id);

        if (!$permission) {
            $this->Flash->error(__('Permission not found.'));
            return $this->redirect(['action' => 'index']);
        }

        if ($quotingPartnerPermissionsTable->delete($permission)) {
            $this->Flash->success(__('Permission deleted successfully.'));
        } else {
            $this->Flash->error(__('Unable to delete permission. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
