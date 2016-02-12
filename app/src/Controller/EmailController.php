<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 7/25/15
 * Time: 6:31 AM
 */
namespace KaiApp\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class EmailController extends BaseController
{
    public function send(Request $request,Response $response, array $args)
    {
        $to = $request->getParam('to');
        $body = $request->getParam('body');
        $from = $request->getParam('from');
        $subject = $request->getParam('subject');
        $items = $request->getParam('items');
        $notificationid = $request->getParam('notificationid');

        $missingParams = $this->getMissingParams(array("to" => $to,"body" => $body,"from" => $from,"subject" => $subject));
        if (!empty($missingParams))
            return $this->simpleResponse("Missing required parameters ".implode(",",$missingParams),$response,500);


        $transport = Swift_SmtpTransport::newInstance('mail.kai-mx.net', 25)
            ->setUsername('guildwarsv2@kai-mx.net')
            ->setPassword('OperationN24%');

        $mailer = Swift_Mailer::newInstance($transport);

        $emailTemplate = file_get_contents('http://www.kai-mx.net/HTML%20Template/emailTemplateGuildWars.html');

        $emailTemplate = !empty($notificationid) ? str_replace("{title}","Guild Wars Notification",$emailTemplate)
        : $emailTemplate = str_replace("{title}","Guild Wars Report",$emailTemplate);

        $emailTemplate = (!empty($items)) ? $emailTemplate = str_replace("{Items}",$items,$emailTemplate)
            : $emailTemplate = str_replace("{Items}","",$emailTemplate);


        $emailTemplate = str_replace("{Body}",$body,$emailTemplate);

        // Create a message
        $message = Swift_Message::newInstance($subject)
            ->setFrom("guildwarsv2@kai-mx.net")
            ->setTo($to)
            ->setBody($emailTemplate,"text/html");

        $message->setFrom($from);

        // Send the message
        $result = $mailer->send($message,$failures);

        $response = array();

        $response['status'] = $result;
        if (!empty($notificationid))
            $response['notification_id'] = $notificationid;

        echo json_encode($response);
    }


}