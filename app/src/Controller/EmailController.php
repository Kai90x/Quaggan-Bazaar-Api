<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 7/25/15
 * Time: 6:31 AM
 */
namespace KaiApp\Controller;

use KaiApp\JsonTransformers\SimpleTransformer;
use League\Fractal\Resource\Item;
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
            return $this->response(new Item("Missing required parameters ".implode(",",$missingParams), new SimpleTransformer()),$response,500);


        $transport = Swift_SmtpTransport::newInstance('mail.kai-mx.net', 25)
            ->setUsername('quagganbazaar@kai-mx.net')
            ->setPassword('OperationN24%');

        $mailer = Swift_Mailer::newInstance($transport);

        $emailTemplate = file_get_contents('assets/templates/email.html',FILE_USE_INCLUDE_PATH);

        $emailTemplate = !empty($notificationid) ? str_replace("{title}","Guild Wars Notification",$emailTemplate)
        : $emailTemplate = str_replace("{title}","Guild Wars Report",$emailTemplate);

        $emailTemplate = (!empty($items)) ? $emailTemplate = str_replace("{Items}",$items,$emailTemplate)
            : $emailTemplate = str_replace("{Items}","",$emailTemplate);

        $emailTemplate = str_replace("{Body}",$body,$emailTemplate);

        $message = Swift_Message::newInstance($subject)
            ->setFrom("quagganbazaar@kai-mx.net")
            ->setTo($to)
            ->setBody($emailTemplate,"text/html");

        $message->setFrom($from);

        $mailer->send($message,$failures);

        return $this->response(new Item('Email sent',new SimpleTransformer($notificationid)),$response);
    }


}