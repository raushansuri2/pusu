<?php
declare(strict_types=1);

namespace App\Job;

use Cake\Queue\Job\JobInterface;
use Cake\Queue\Job\Message;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Cake\Core\Configure;
use Interop\Queue\Processor;

class SendEmailJob implements JobInterface
{
    public function execute(Message $message): ?string
    {
        $data = $message->getData();
        $to = $data['to'];
        $subject = $data['subject'];

        $mailer = new PHPMailer(true);
        try {
            $mailer->isSMTP();
            $mailer->Host = 'mail.smtp2go.com';
            $mailer->SMTPAuth = true;
            $mailer->Username = 'info@ritevet.com';
            $mailer->Password = 'Testing@1234567890';
            $mailer->SMTPSecure = 'tls'; // Use TLS (not set twice)
            $mailer->Port = 8025;

            // SSL Options (only if needed)
            $mailer->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];

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
                        <img src="' . Configure::read('App.siteurl') . 'img/logo-black.png" alt="Ritevet Logo">
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#f4f4f4" style="padding: 10px; color: #555; font-family: Lato, sans-serif; font-size: 14px; line-height: 24px;">';

            $content = '<div class="style1">' . $data['body'] . '</div>';

            $footer = '</td>
                </tr>
                <tr>
                    <td bgcolor="#FFCC00" style="padding: 15px;">
                        <p style="font-size: 13px; text-align: center; color: #FFF; font-family: Lato, sans-serif; line-height: 24px;">
                            <strong style="font-size: 18px;">Ritevet Support Team</strong><br>
                            <a style="color: #FFF;" href="mailto:support@ritevet.com" target="_blank">support@ritevet.com</a>
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
            $mailer->AltBody = strip_tags($message); // Plain text fallback

            $mailer->send();
            return Processor::ACK; // Success
        } catch (Exception $e) {
            \Cake\Log\Log::error("Email sending failed: {$mailer->ErrorInfo}");
            return "Failed to send email: {$mailer->ErrorInfo}"; // Failure
        }
    }
}
// namespace App\Job;

// use Queue\Job\BaseJob;

// class EmailJob extends BaseJob
// {
//     public function perform(array $data)
//     {
//         // Extract data
//         $to = $data['to'];
//         $subject = $data['subject'];
//         $message = $data['message'];

//         // Implement your email sending logic here
//         $email = new \Cake\Mailer\Email();
//         $email->setTo($to)
//               ->setSubject($subject)
//               ->send($message);
//     }
// }