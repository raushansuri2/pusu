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

        $keyword = $this->request->getQuery('keyword');
	    $condition[] = ['RequestQuotes.id >' => 0];

	    if (!empty($keyword)) {
	        $condition[] = [
	            'OR' => [
	                //'RequestQuotes.Final_Proposals_Due' => $keyword,
                    //'RequestQuotes.Policy_Effective_Date' => $keyword,
                    //'RequestQuotes.Policy_Termination_Date' => $keyword,
                    'Users.firstName LIKE' => "%{$keyword}%",
                    'Users.email LIKE' => "%{$keyword}%",
                    'Users.contactNumber LIKE' => "%{$keyword}%",
                    'Programs.name LIKE' => "%{$keyword}%",
                    'Quotgroups.group_name LIKE' => "%{$keyword}%"
	            ]
	        ];
	    }

        // Set pagination
        $this->paginate = [
            'limit' => 10,
            'order' => ['RequestQuotes.id' => 'DESC']
        ];

        // Fetch request quotes using pagination
        $requestQuotesTable = $this->fetchTable('RequestQuotes');
        $query = $requestQuotesTable->find()->where($condition)->contain([
            'Users',
            'Programs',
            'Quotgroups',
            'LoosePlans',
            'BenifitPlans',
            'NetworksRepricing'
        ]);

        $requestQuotes = $this->paginate($query);

        $networkIds = [];
        $lossPlanIds = [];
        $benifitPlanIds = [];
        foreach ($requestQuotes as $rq) {
            if (!empty($rq->networking_id)) {
                $parts = array_filter(array_map('trim', explode(',', (string)$rq->networking_id)));
                foreach ($parts as $p) {
                    if ($p !== '') {
                        $networkIds[] = (int)$p;
                    }
                }
            }

            if (!empty($rq->loss_plan)) {
                $parts = array_filter(array_map('trim', explode(',', (string)$rq->loss_plan)));
                foreach ($parts as $p) {
                    if ($p !== '') {
                        $lossPlanIds[] = (int)$p;
                    }
                }
            }

            if (!empty($rq->benifit_plan)) {
                $parts = array_filter(array_map('trim', explode(',', (string)$rq->benifit_plan)));
                foreach ($parts as $p) {
                    if ($p !== '') {
                        $benifitPlanIds[] = (int)$p;
                    }
                }
            }
        }
        $networkIds = array_values(array_unique(array_filter($networkIds)));
        $lossPlanIds = array_values(array_unique(array_filter($lossPlanIds)));
        $benifitPlanIds = array_values(array_unique(array_filter($benifitPlanIds)));

        $networkNamesById = [];
        if (!empty($networkIds)) {
            $networks = $this->fetchTable('NetworksRepricing')
                ->find('list', ['keyField' => 'id', 'valueField' => 'name'])
                ->where(['NetworksRepricing.id IN' => $networkIds])
                ->toArray();
            $networkNamesById = $networks;
        }

        $lossPlanNamesById = [];
        if (!empty($lossPlanIds)) {
            $lossPlans = $this->fetchTable('LoosePlans')
                ->find('list', ['keyField' => 'id', 'valueField' => 'plan_name'])
                ->where(['LoosePlans.id IN' => $lossPlanIds])
                ->toArray();
            $lossPlanNamesById = $lossPlans;
        }

        $benifitPlanNamesById = [];
        if (!empty($benifitPlanIds)) {
            $benifitPlans = $this->fetchTable('BenifitPlans')
                ->find('list', ['keyField' => 'id', 'valueField' => 'plan_name'])
                ->where(['BenifitPlans.id IN' => $benifitPlanIds])
                ->toArray();
            $benifitPlanNamesById = $benifitPlans;
        }

        $this->set(compact('requestQuotes', 'networkNamesById', 'lossPlanNamesById', 'benifitPlanNamesById'));
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

        $networkIds = [];
        $lossPlanIds = [];
        $benifitPlanIds = [];
        if (!empty($requestQuote->networking_id)) {
            $networkIds = array_values(array_unique(array_filter(array_map('intval', array_filter(array_map('trim', explode(',', (string)$requestQuote->networking_id)))))));
        }

        if (!empty($requestQuote->get('loss_plan'))) {
            $lossPlanIds = array_values(array_unique(array_filter(array_map('intval', array_filter(array_map('trim', explode(',', (string)$requestQuote->get('loss_plan'))))))));
        }

        if (!empty($requestQuote->get('benifit_plan'))) {
            $benifitPlanIds = array_values(array_unique(array_filter(array_map('intval', array_filter(array_map('trim', explode(',', (string)$requestQuote->get('benifit_plan'))))))));
        }

        $networkNamesById = [];
        if (!empty($networkIds)) {
            $networks = $this->fetchTable('NetworksRepricing')
                ->find('list', ['keyField' => 'id', 'valueField' => 'name'])
                ->where(['NetworksRepricing.id IN' => $networkIds])
                ->toArray();
            $networkNamesById = $networks;
        }

        $lossPlanNamesById = [];
        if (!empty($lossPlanIds)) {
            $lossPlans = $this->fetchTable('LoosePlans')
                ->find('list', ['keyField' => 'id', 'valueField' => 'plan_name'])
                ->where(['LoosePlans.id IN' => $lossPlanIds])
                ->toArray();
            $lossPlanNamesById = $lossPlans;
        }

        $benifitPlanNamesById = [];
        if (!empty($benifitPlanIds)) {
            $benifitPlans = $this->fetchTable('BenifitPlans')
                ->find('list', ['keyField' => 'id', 'valueField' => 'plan_name'])
                ->where(['BenifitPlans.id IN' => $benifitPlanIds])
                ->toArray();
            $benifitPlanNamesById = $benifitPlans;
        }

        $this->set(compact('networkNamesById', 'lossPlanNamesById', 'benifitPlanNamesById'));
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
