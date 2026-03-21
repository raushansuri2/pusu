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

        // Fetch fees
        $feesTable = $this->fetchTable('Fees');
        $fees = $feesTable->find()
            ->order(['Fees.id' => 'ASC'])
            ->all();

        $programIds = [];
        foreach ($fees as $fee) {
            if (!empty($fee->program_id)) {
                $parts = array_filter(array_map('trim', explode(',', (string)$fee->program_id)));
                foreach ($parts as $p) {
                    if ($p !== '') {
                        $programIds[] = (int)$p;
                    }
                }
            }
        }
        $programIds = array_values(array_unique(array_filter($programIds)));

        $programNamesById = [];
        if (!empty($programIds)) {
            $programsTable = $this->fetchTable('Programs');
            $programNamesById = $programsTable->find('list', ['keyField' => 'id', 'valueField' => 'name'])
                ->where(['Programs.id IN' => $programIds])
                ->toArray();
        }

        $this->set(compact('fees', 'programNamesById'));
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

        $statusOptions = [1 => 'Active', 0 => 'Inactive'];

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            $programIds = $data['program_id'] ?? ($data['program_id[]'] ?? null);
            if (is_array($programIds)) {
                $flatProgramIds = [];
                array_walk_recursive($programIds, function ($v) use (&$flatProgramIds) {
                    if (is_scalar($v) || $v === null) {
                        $flatProgramIds[] = (string)$v;
                    }
                });
                $flatProgramIds = array_values(array_filter(array_map('trim', $flatProgramIds)));
                $data['program_id'] = !empty($flatProgramIds) ? implode(',', $flatProgramIds) : '';
            } elseif ($programIds === null) {
                $data['program_id'] = '';
            }
            $fee = $feesTable->patchEntity($fee, $data);

            if ($feesTable->save($fee)) {
                $this->Flash->success(__('Fee added successfully.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Unable to add fee. Please, try again.'));
            }
        }

        $this->set(compact('fee', 'programs', 'statusOptions'));
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

        $statusOptions = [1 => 'Active', 0 => 'Inactive'];

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            if(!isset($data['is_editable'])){
                $data['is_editable'] = 0;
            }
            if(!isset($data['is_applied_to_premium'])){
                $data['is_applied_to_premium'] = 0;
            }
            $programIds = $data['program_id'] ?? ($data['program_id[]'] ?? null);
            if (is_array($programIds)) {
                $flatProgramIds = [];
                array_walk_recursive($programIds, function ($v) use (&$flatProgramIds) {
                    if (is_scalar($v) || $v === null) {
                        $flatProgramIds[] = (string)$v;
                    }
                });
                $flatProgramIds = array_values(array_filter(array_map('trim', $flatProgramIds)));
                $data['program_id'] = !empty($flatProgramIds) ? implode(',', $flatProgramIds) : '';
            } elseif ($programIds === null) {
                $data['program_id'] = '';
            }
            $fee = $feesTable->patchEntity($fee, $data);

            if ($feesTable->save($fee)) {
                $this->Flash->success(__('Fee updated successfully.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Unable to update fee. Please, try again.'));
            }
        }

        $this->set(compact('fee', 'programs', 'statusOptions'));
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
