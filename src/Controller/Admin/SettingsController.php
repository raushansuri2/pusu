<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;

class SettingsController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
    }

    public function index()
    {
        $this->viewBuilder()->setLayout('Admin/admin');
        $this->set('layoutTitle', 'Admin::Settings');

        // Check session for admin role
        $session = $this->request->getSession();
        if ($session->read('AnnuityAdmin.role') !== 'Admin') {
            $session->destroy();
            return $this->redirect(['controller' => 'Admins', 'action' => 'login', 'prefix' => 'Admin']);
        }

        // Fetch general settings
        $generalSettingsTable = $this->fetchTable('GeneralSettings');
        $settings = $generalSettingsTable->find('all')->first();

        $this->set(compact('settings'));
    }

    public function edit()
    {
        $this->viewBuilder()->setLayout('Admin/admin');
        $this->set('layoutTitle', 'Admin::Edit Settings');

        // Check session for admin role
        $session = $this->request->getSession();
        if ($session->read('AnnuityAdmin.role') !== 'Admin') {
            $session->destroy();
            return $this->redirect(['controller' => 'Admins', 'action' => 'login', 'prefix' => 'Admin']);
        }

        $generalSettingsTable = $this->fetchTable('GeneralSettings');
        $setting = $generalSettingsTable->find('all')->first();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            
            if ($setting) {
                $setting = $generalSettingsTable->patchEntity($setting, $data);
            } else {
                $setting = $generalSettingsTable->newEntity($data);
            }

            if ($generalSettingsTable->save($setting)) {
                $this->Flash->success(__('Settings updated successfully.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Unable to update settings. Please, try again.'));
            }
        }

        $this->set(compact('setting'));
    }
}
