<?php
namespace App\Controller\Admin;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;
use Cake\Core\Configure;
use Cake\Mailer\Email;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class AppController extends Controller
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Flash');
        //echo Configure::read('App.siteurl'); die;
    }

    public function beforeFilter(EventInterface $event): void
	{
	    $this->set('PATH', Configure::read('App.siteurl')); 
	    $allowedActions = ['login', 'reset', 'forgotpassword', 'showprofile'];
	    
	    if (in_array($this->request->getParam('action'), $allowedActions) && $this->request->getParam('prefix') === 'Admin') {
	        // Allow these actions without session check
	        return;
	    }
	    
	    $session = $this->request->getSession();
	    if (!$session->check('AnnuityAdmin') || $session->read('AnnuityAdmin.role') !== 'Admin') {
	        $redirect = $session->read('REDIRECT');
	        if ($redirect) {
	            $session->delete('REDIRECT');
	            $this->redirect([
	                'controller' => $redirect['controller'],
	                'action' => $redirect['action'],
	                'prefix' => $redirect['prefix'] ?? 'Admin',
	                $redirect['pass']
	            ]);
	            return;
	        }
	        
	        $data = [
	            'controller' => $this->request->getParam('controller'),
	            'action' => $this->request->getParam('action'),
	            'prefix' => $this->request->getParam('prefix'),
	            'pass' => $this->request->getParam('pass.0', '')
	        ];
	        
	        if (!empty($data['pass']) && $this->request->getParam('pass.1')) {
	            $data['pass'] .= '/' . $this->request->getParam('pass.1');
	        }
	        
	        $session->write('REDIRECT', $data);
	        
	        // Change the redirect to the desired login action
	        $this->redirect(['controller' => 'Admins', 'action' => 'login']);
	        return;
	    }
	}

    /********************************************************************************
    Function Name: sendmail
    *Type: Public function used as action of the controller
    *Input: template variables, mail template, receiver email and email subject
    *Author: Raushan Suri
    *Modified By: Raushan Suri
    *Output: Function to send emails using CakePHP Email
    *********************************************************************************/
    public function sendmail($Email_variables, $mail_template = '', $mailto = '', $subject = '', $mailcc = '', $from = '')
    {
        $this->autoRender = false;
        
        $email = new Email('Smtp'); // Ensure 'Smtp' profile is configured in config/app.php
        $email->setTemplate($mail_template, 'email_layout')
            ->setEmailFormat('html')
            ->setViewVars($Email_variables)
            ->setSubject($subject)
            ->setTo($mailto)
            ->setFrom($from ?: ['info@ritevet.com' => 'Ritevet'])
            ->send();
        
        return true;
    }

    /********************************************************************************
    Function Name: phpemail
    *Type: Public function using PHPMailer
    *Input: to, subject, message
    *Author: Raushan Suri
    *Modified By: Raushan Suri
    *Output: Sends email using PHPMailer
    *********************************************************************************/
    public function phpemail($to, $subject, $message)
    {
        $this->autoRender = false;
        
        $mailer = new PHPMailer(true); // Enable exceptions
        try {
            $mailer->isSMTP();
            $mailer->SMTPDebug = 0; // Set to 2 for debugging
            $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS
            $mailer->Host = 'mail.smtp2go.com';
            $mailer->SMTPAuth = true;
            $mailer->Username = 'info@ritevet.com';
            $mailer->Password = 'Testing@1234567890';
            $mailer->Port = 2525; // Common TLS port (8025 might be specific to your setup)

            // Email content
            $header = '<html xmlns="http://www.w3.org/1999/xhtml" style="margin:0;padding:0;">
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                    <title>Ritevet Email</title>
                    <link href="http://fonts.googleapis.com/css?family=Raleway:500,300|Lato:300,400,700" rel="stylesheet" type="text/css">
                    <style>
                        * { margin: 0; padding: 0; }
                        .style1 { color: #555; font-family: "Lato", sans-serif; font-size: 14px; line Asc line-height: 24px; margin-bottom: 16px; }
                    </style>
                </head>
                <body width="100%">
                    <table border="0" cellspacing="0" cellpadding="0" style="width:80%;margin:0 auto;">
                        <tr>
                            <td bgcolor="#000000" align="center" style="padding:10px 0;">
                                <img src="' . Configure::read('App.siteurl') . 'img/logo-black.png">
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#f4f4f4" style="padding:10px;">';
            $content = '<div class="style1">' . $message . '</div>';
            $footer = '</td></tr>
                        <tr>
                            <td bgcolor="#FFCC00" style="padding:15px;">
                                <p style="font-size:13px;text-align:center;color:#FFF;font-family:Lato,sans-serif;">
                                    <strong style="font-size:18px;">Ritevet Support Team</strong><br>
                                    <a style="color:#FFF;" href="mailto:support@ritevet.com">support@ritevet.com</a>
                                </p>
                            </td>
                        </tr>
                    </table>
                </body>
            </html>';

            $body = $header . $content . $footer;

            $mailer->setFrom('info@ritevet.com', 'Ritevet');
            $mailer->addAddress($to);
            $mailer->isHTML(true);
            $mailer->Subject = $subject;
            $mailer->Body = $body;

            $mailer->send();
            $mailer->clearAllRecipients();
            return true;
        } catch (Exception $e) {
            $this->log("Email could not be sent. Mailer Error: {$mailer->ErrorInfo}", 'error');
            return false;
        }
    }

    /********************************************************************************
    Function Name: random_password
    *Type: Public function used as action of the controller
    *Input: Length of password (default 10)
    *Author: Raushan Suri
    *Modified By: Raushan Suri
    *Output: Generates a random password
    *********************************************************************************/
    public function random_password($length = 10)
    {
        $this->autoRender = false;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}