<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 7/25/15
 * Time: 6:31 AM
 */
namespace KaiApp\Controller;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class email extends BaseController
{

    public function sendEmailAction()
    {
        $emailTo = $this->app->request()->post('emailTo');
        $emailBody = $this->app->request()->post('emailBody');
        $emailFrom = $this->app->request()->post('emailFrom');
        $emailSubject = $this->app->request()->post('emailSubject');
        $items = $this->app->request()->post('emailItems');
        $notificationid = $this->app->request()->post('notificationid');

        if (\Utils\Common::CheckEmptyParams(array($emailTo,$emailBody,$emailFrom,$emailSubject))) {
            echo json_encode(\Utils\Common::GenerateResponse(\Utils\Common::STATUS_ERROR,"Missing paramters"));
            return;
        }

        // Create the Transport
        $transport = Swift_SmtpTransport::newInstance('mail.kai-mx.net', 25)
            ->setUsername('guildwarsv2@kai-mx.net')
            ->setPassword('OperationN24%')
        ;

        // Create the Mailer using your created Transport
        $mailer = Swift_Mailer::newInstance($transport);

        //Get Email Template
        $emailTemplate = file_get_contents('http://www.kai-mx.net/HTML%20Template/emailTemplateGuildWars.html');

        if (!empty($notificationid))
            $emailTemplate = str_replace("{title}","Guild Wars Notification",$emailTemplate);
        else
            $emailTemplate = str_replace("{title}","Guild Wars Report",$emailTemplate);

        if (!empty($items)) {
            $emailTemplate = str_replace("{Items}",$items,$emailTemplate);
        } else {
            $emailTemplate = str_replace("{Items}","",$emailTemplate);
        }

        $emailTemplate = str_replace("{Body}",$emailBody,$emailTemplate);

        // Create a message
        $message = Swift_Message::newInstance($emailSubject)
            ->setFrom("guildwarsv2@kai-mx.net")
            ->setTo($emailTo)
            ->setBody($emailTemplate,"text/html")
        ;

        $message->setFrom($emailFrom);

        // Send the message
        $result = $mailer->send($message,$failures);

        $response = array();

        $response['status'] = $result;
        if (!empty($notificationid))
            $response['notification_id'] = $notificationid;

        echo json_encode($response);
    }


}