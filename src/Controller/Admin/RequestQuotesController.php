<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class RequestQuotesController extends AppController
{
    public function index()
    {
        $this->viewBuilder()->setLayout('Admin/admin');
        $this->set('layoutTitle', 'Admin::Request Quotes');

        // Check session for admin role
        $session = $this->request->getSession();
        if ($session->read('AnnuityAdmin.role') !== 'Admin') {
            $session->destroy();
            return $this->redirect(['controller' => 'Admins', 'action' => 'login', 'prefix' => 'Admin']);
        }

        // Set pagination
        $this->paginate = [
            'limit' => 10,
            'order' => ['RequestQuotes.id' => 'DESC']
        ];

        // Fetch request quotes using pagination
        $requestQuotesTable = $this->fetchTable('RequestQuotes');
        $query = $requestQuotesTable->find()->contain([
            'Users',
            'Programs',
            'Quotgroups',
            'LoosePlans',
            'BenifitPlans',
            'NetworksRepricing'
        ]);

        $requestQuotes = $this->paginate($query);

        $this->set(compact('requestQuotes'));
    }

    public function view($id = null)
    {
        $this->viewBuilder()->setLayout('Admin/admin');
        $this->set('layoutTitle', 'Admin::View Request Quote');

        // Check session for admin role
        $session = $this->request->getSession();
        if ($session->read('AnnuityAdmin.role') !== 'Admin') {
            $session->destroy();
            return $this->redirect(['controller' => 'Admins', 'action' => 'login', 'prefix' => 'Admin']);
        }

        $requestQuotesTable = $this->fetchTable('RequestQuotes');
        $requestQuote = $requestQuotesTable->get($id, [
            'contain' => [
                'Users',
                'Programs',
                'Quotgroups',
                'LoosePlans',
                'BenifitPlans',
                'NetworksRepricing'
            ]
        ]);

        if (!$requestQuote) {
            $this->Flash->error(__('Request quote not found.'));
            return $this->redirect(['action' => 'index']);
        }

        $this->set(compact('requestQuote'));
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

        $requestQuotesTable = $this->fetchTable('RequestQuotes');
        $requestQuote = $requestQuotesTable->get($id);

        if (!$requestQuote) {
            $this->Flash->error(__('Request quote not found.'));
            return $this->redirect(['action' => 'index']);
        }

        if ($requestQuotesTable->delete($requestQuote)) {
            $this->Flash->success(__('Request quote deleted successfully.'));
        } else {
            $this->Flash->error(__('Unable to delete request quote. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
