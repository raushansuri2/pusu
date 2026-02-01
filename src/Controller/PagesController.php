<?php
namespace App\Controller;

use Cake\Routing\Router;

class PagesController extends AppController
{
    public function home()
    {
        $this->viewBuilder()->setLayout('home');
        $layoutTitle = 'Ritevet';
        
        $pagesTable = $this->fetchTable('Pages');
        $home = $pagesTable->find()
            ->where(['Pages.id' => 1])
            ->first();
        
        $usersInfoTable = $this->fetchTable('Usersinformations');
        $users = $usersInfoTable->find()
            ->where([
                'Usersinformations.UTYPE IN' => [2, 3],
                'Users.status' => 1,
                'Usersinformations.verifyAdmin' => 1
            ])
            ->contain(['Users', 'Reviews'])
            ->order(['Users.AVGRating' => 'DESC'])
            ->limit(3)
            ->toArray();
        
        $this->set(compact('layoutTitle', 'home', 'users'));
    }
    
    public function aboutus()
    {
        $this->viewBuilder()->setLayout('pages');
        $layoutTitle = 'Ritevet - About Us';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'About Us',
            'URL' => [
                'Home' => $url,
                'About Us' => $url . '/pages/aboutus'
            ]
        ];
        
        $pagesTable = $this->fetchTable('Pages');
        $pages = $pagesTable->find()
            ->where(['Pages.status' => 1, 'Pages.slug' => 'about-us'])
            ->first();
        
        $this->set(compact('layoutTitle', 'pages', 'breadcum'));
    }
    
    public function contactus()
    {
        $this->viewBuilder()->setLayout('pages');
        $layoutTitle = 'Ritevet - Contact Us';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'Contact Us',
            'URL' => [
                'Home' => $url,
                'Contact Us' => $url . 'contact'
            ]
        ];

        if ($this->request->is('post')) {
            $contactsTable = $this->fetchTable('Contacts');
            $contact = $contactsTable->newEmptyEntity();
            $contact = $contactsTable->patchEntity($contact, $this->request->getData(), ['validate' => false]);
            $contact->created = date('Y-m-d H:i:s');
            $contact->status = 1;

            if ($contactsTable->save($contact)) {
                // Prepare email content
                $to = 'ritevet@ritevet.com';
                $subject = 'Ritevet Contact Us';
                $message = "Dear Admin,<br>";
                $message .= "One visitor filled the contact us form.<br>";
                $message .= "Information given below:<br><br>";
                $message .= "Name: " . ucfirst($this->request->getData('name')) . "<br>";
                $message .= "Email: " . $this->request->getData('email') . "<br>";
                $message .= "Message: " . $this->request->getData('message') . "<br>";

                // Call the phpemail function from AppController
                if ($this->phpemail($to, $subject, $message)) {
                    $this->Flash->success(__('Thank you for contacting us. We will contact you soon.'));
                } else {
                    $this->Flash->error(__('Failed to send email.'));
                }
            } else {
                $this->Flash->error(__('Please fill all required information.'));
            }

            return $this->redirect(['action' => 'contactus']);
        }

        $this->set(compact('layoutTitle', 'breadcum'));
    }
    
    public function elearn()
    {
        $this->viewBuilder()->setLayout('pages');
        $layoutTitle = 'Ritevet - E-Learning';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'E-Learning',
            'URL' => [
                'Home' => $url,
                'E-Learning' => $url . 'elearn'
            ]
        ];
        
        $categoriesTable = $this->fetchTable('ElearningCategories');
        $categories = $categoriesTable->find('all')->toArray();
        
        $this->set(compact('layoutTitle', 'breadcum', 'categories'));
    }
    
    public function faqs()
    {
        $this->viewBuilder()->setLayout('pages');
        $layoutTitle = 'Ritevet - Faqs';
        $url = Router::url('/', true);
        $breadcum = [
            'Title' => 'FAQ',
            'URL' => [
                'Home' => $url,
                'FAQ' => $url . 'faqs'
            ]
        ];
        
        $faqsTable = $this->fetchTable('Faqs');
        $faqs = $faqsTable->find()
            ->where(['Faqs.status' => 1])
            ->toArray();
        
        $this->set(compact('layoutTitle', 'breadcum', 'faqs'));
    }
    
    public function howitwork()
    {
        $this->viewBuilder()->setLayout('pages');
        $layoutTitle = 'Ritevet - HowitWork';
        $url = Router::url('/', true);;
        $breadcum = [
            'Title' => 'How it Work',
            'URL'=>[
            'Home' => $url,
            'How it Work' => ''             ]
        ];
        
        $this->set(compact('layoutTitle', 'breadcum'));
    }
}