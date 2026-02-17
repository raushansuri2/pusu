<?php
declare(strict_types=1);

namespace App\Controller;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Cake\Controller\Controller;
use Cake\Event\EventInterface;
use Cake\Core\Configure;
use Cake\Mailer\Email;
use Cake\Log\Log;


//echo "<pre>"; print_r($_SERVER); die;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/5/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/5/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }

    public function random_password($length = '10')
    {
        //$this->autoRender = false;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    /**
     * beforeRender callback.
     *
     * @param \Cake\Event\EventInterface $event Event.
     * @return void
     */
    public function beforeRender(EventInterface $event)
    {
        $this->set('PATH', Configure::read('App.siteurl'));

        // Check if the response type is JSON or XML
        if (in_array($this->response->getType(), ['application/json', 'application/xml'])) {
            $this->set('_serialize', true);
        }


        // Retrieve user ID from session once
        $userId = $this->request->getSession()->read('RitevetUsers.id');

        // Accessing models using fetchTable (preferred in CakePHP 5)
        $usersTable = $this->fetchTable('Users');
        $globalParametersTable = $this->fetchTable('GlobalParameters');
        $testimonialsTable = $this->fetchTable('Testimonials');
        $productsTable = $this->fetchTable('Products');
        $cartsTable = $this->fetchTable('Carts');

        // Fetching data with error handling
        $user = $userId ? $usersTable->find()
            ->where(['Users.id' => $userId])
            ->first() : null;

        try {
            $globalparameters = $globalParametersTable->get(1);
        } catch (RecordNotFoundException $e) {
            $globalparameters = null; // Fallback if record ID 1 doesn't exist
        }

        $TESTIMONIALS = $testimonialsTable->find()
            ->where(['Testimonials.status' => 1])
            ->limit(5)
            ->order('RAND()') // Use string for SQL function; avoids deprecation warning
            ->all()
            ->toArray();

        $FREESTAFFS = $productsTable->find()
            ->where(['Products.status' => 1, 'Products.unitPrice' => 0])
            ->contain(['Users'])
            ->limit(3)
            ->order('RAND()')
            ->all()
            ->toArray();

        $CART = 0; // Default value
        if ($userId !== null) {
            $CART = $cartsTable->find()
                ->where([
                    'Carts.userId' => $userId,
                    'Carts.orderId IS' => null // Modern IS NULL syntax
                ])
                ->count();
        }

        // Setting variables
        $this->set(compact('globalparameters', 'TESTIMONIALS', 'CART', 'user', 'FREESTAFFS'));
    }

    public function sendmail($Email_variables, $mail_template = '', $mailto = '', $subject = '', $mailcc = '', $from = '')
    {
        $this->autoRender = false;

        $email = new Email('default');
        $email->helpers([]);

        try {
            $email->template($mail_template, 'email_layout')
                ->emailFormat('html')
                ->viewVars($Email_variables)
                ->subject($subject)
                ->to($mailto)
                ->from(['support@ritevet.com' => 'Ritevet']);

            if ($email->send()) {
                Log::write('info', 'Email sent successfully to: ' . $mailto);
                return true; // Email sent successfully
            } else {
                Log::write('error', 'Email not sent: ' . json_encode($Email_variables));
                return false; // Email not sent
            }
        } catch (Exception $e) {
            Log::write('error', 'Email sending failed: ' . $e->getMessage());
            return false; // Email sending failed
        }
    }

    public function phpemail(string $to, string $subject, string $message): bool
    {
        $mailer = new PHPMailer(); // Enable exceptions for better error handling

        try {
            // SMTP Configuration
            $mailer->isSMTP();
            $mailer->SMTPDebug = 0; // Set to 2 for debugging output
            $mailer->Mailer = "smtp";
             $mailer->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];
            $mailer->Host = 'mail.smtp2go.com';
            $mailer->SMTPAuth = true;
            $mailer->Username = 'demo5';
            $mailer->Password = 'Testing@1234567890';
            $mailer->SMTPSecure = 'tls'; // Use TLS (not set twice)
            $mailer->Port = 587; // SMTP2GO typically uses 8025, not 2525 8025

            // SSL Options (only if needed)


            // Email Headers and Content
            $header = '<!DOCTYPE html>
            <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                <title>Ritevet Contact</title>
                <link href="http://fonts.googleapis.com/css?family=Raleway:500,300|Lato:300,400,700" rel="stylesheet" type="text/css">
                <style>
                    * { margin: 0; padding: 0; }
                    body { font-family: "Lato", sans-serif; }
                    .style1 { color: #555; font-size: 14px; line-height: 24px; margin-bottom: 16px; }
                </style>
            </head>
            <body width="100%">
            <table border="0" cellspacing="0" cellpadding="0" style="width: 80%; margin: 0 auto;">
                <tr>
                    <td bgcolor="#000000" align="center" style="padding: 10px 0;">
                        <img src="' . Configure::read('App.siteurl') . 'datingapp/img/admin/logo-black.png" alt="LGBT Logo" style="width:200px;height:100px;">
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#f4f4f4" style="padding: 10px; color: #555; font-family: Lato, sans-serif; font-size: 14px; line-height: 24px;">';

            $content = '<div class="style1">' . $message . '</div>';

            $footer = '</td>
                </tr>
                <tr>
                    <td bgcolor="#FFCC00" style="padding: 15px;">
                        <p style="font-size: 13px; text-align: center; color: #FFF; font-family: Lato, sans-serif; line-height: 24px;">
                            <strong style="font-size: 18px;">LGBT-TOGO Support Team</strong><br>
                            <a style="color: #FFF;" href="mailto:support@LGBT-TOGO.com" target="_blank">support@LGBT-TOGO.com</a>
                        </p>
                    </td>
                </tr>
            </table>
            </body>
            </html>';

            $body = $header . $content . $footer;

            // Sender and Recipient
            $mailer->From = "serveradmin@evirtualservices.com";
            $mailer->FromName = "LGBT-TOGO";
            $mailer->addAddress($to);

            // Email Content
            $mailer->isHTML(true);
            $mailer->Subject = $subject;
            $mailer->Body = $body;
            $mailer->WordWrap = true;
            //$mailer->AltBody = strip_tags($message); // Plain text fallback

            // Send the email
            $mailer->send();
            $mailer->clearAllRecipients();

            return true;
        } catch (Exception $e) {
            // Log the error for debugging (optional)
            $this->log('Email sending failed: ' . $mailer->ErrorInfo, 'error');
            return false;
        }
    }

    public function sendNotificationOnAndroid($deviceToken=NULL,$body=NULL)
    {
        $url = Configure::read('App.siteurl').'datingapp/push.php';
        $fields =[
            'deviceToken' => $deviceToken,
            'body' => json_encode($body)
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        $result = curl_exec($ch);
        //echo "<pre>"; print_r($result); die('G1GG');
        curl_close($ch);
        return $result;
    }

    public function sednIosPushNotification($deviceToken=NULL,$body=NULL)
    {

        $url = Configure::read('App.siteurl').'datingapp/push.php';
        $fields =[
            'deviceToken' => $deviceToken,
            'body' => json_encode($body)
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        $result = curl_exec($ch);
        //echo "<pre>"; print_r($result); die('G1GG');
        curl_close($ch);
        return $result;
    }

}
