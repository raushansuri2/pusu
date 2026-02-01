<?php

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\View\Helper\HtmlHelper;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Routing\Router;

class MaintenanceController extends AppController
{
    public function maintenance()
    {
        // Set the layout for the maintenance page
        $this->viewBuilder()->layout('maintenance');
    }
    
    public function contact()
    {
        // Set the layout for the contact page
        $this->viewBuilder()->layout('contact-us');
        
        if ($this->request->is('post')) 
        {
            if($this->request->data['first_name'] != '' && $this->request->data['last_name'] != '' && $this->request->data['email'] != '' && $this->request->data['country'] != '' && $this->request->data['role'] != '')
            {
                $to = 'landing@ritevet.com';
                $subject = "Ritevet Early Access";
                $message = "Dear Admin";
                $message .= '<br>One visitor filled the landing form.';
                $message .= '<br>Information given below:';
                $message .= '<br>';
                $message .= '<br>Name: ' . ucfirst($this->request->data['first_name']) . ' ' . ucfirst($this->request->data['last_name']);
                $message .= '<br>Email: ' . $this->request->data['email'];
                $message .= '<br>Country: ' . $this->request->data['country'];
                $message .= '<br>Role: ' . $this->request->data['role'];
                
                if (!empty($this->request->data['sub_role'])) {
                    $message .= '<br>Sub Role: ' . $this->request->data['sub_role'];
                }
                
                // Send email
                $this->phpemail($to, $subject, $message);
                
                // Load Contacts model and save the data
                $this->loadModel('Contacts');
                $contactData = [
                    'first_name' => ucfirst($this->request->data['first_name']),
                    'last_name' => ucfirst($this->request->data['last_name']),
                    'email' => $this->request->data['email'],
                    'country' => $this->request->data['country'],
                    'role' => $this->request->data['role'],
                    'sub_role' => $this->request->data['sub_role'],
                    'status' => 1,
                    'landing_page' => 1
                ];
                
                $this->loadModel('Contacts');
                $newsletters = $this->Contacts->newEntity();
                $newsletters = $this->Contacts->patchEntity($newsletters, $contactData, ['validate' => false]);
                $newsletters->created = date('Y-m-d H:i:s');
                
                if ($this->Contacts->save($newsletters)) {
                    $this->Flash->success(__('Thank you for contacting us. We will get back to you soon.'));
                } else {
                    $this->Flash->error(__('Please fill in all required information.'));
                }
                
                return $this->redirect(['action' => 'contact']); 
                
            } else {
                $this->Flash->error(__('Please fill in all required information.'));
                return $this->redirect(['action' => 'contact']); 
            }
        }
    }
    
    public function aboutus()
    {
        // Set the layout for the about us page
        $this->viewBuilder()->layout('aboutus');
    }
    
    public function contactus()
    {
        // Set the layout for the contact us page
        $this->viewBuilder()->layout('contactus');
        
        if ($this->request->is('post')) 
        {
            if($this->request->data['email'] != '' && $this->request->data['name'] != '')
            {
                $to = 'ritevet@ritevet.com';
                $subject = "Ritevet Contact Us";
                $message = "Dear Admin";
                $message .= '<br>One visitor filled the contact us form.';
                $message .= '<br>Information given below:';
                $message .= '<br>';
                $message .= '<br>Name: ' . ucfirst($this->request->data['name']);
                $message .= '<br>Email: ' . $this->request->data['email'] . '<br>Message: ' . $this->request->data['message'];
                
                // Send email
                $this->phpemail($to, $subject, $message);
                
                // Load Contacts model and save the data
                $this->loadModel('Contacts');
                $contactData = [
                    'name' => ucfirst($this->request->data['name']),
                    'email' => $this->request->data['email'],
                    'message' => $this->request->data['message'],
                    'status' => 1
                ];
                
                $this->loadModel('Contacts');
                $newsletters = $this->Contacts->newEntity();
                $newsletters = $this->Contacts->patchEntity($newsletters, $contactData, ['validate' => false]);

                $newsletters->created = date('Y-m-d H:i:s');
                
                if ($this->Contacts->save($newsletters)) {
                    $this->Flash->success(__('Thank you for contacting us. We will get back to you soon.'));
                } else {
                    $this->Flash->error(__('Please fill in all required information.'));
                }
                
                return $this->redirect(['action' => 'contactus']); 
            } else {
                $this->Flash->error(__('Please fill in all required information.'));
                return $this->redirect(['action' => 'contactus']); 
            }
        } 
    }
}