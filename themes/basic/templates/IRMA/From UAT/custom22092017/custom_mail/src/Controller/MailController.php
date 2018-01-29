<?php
  /**
  @file
  Contains \Drupal\custom_mail\Controller\MailController.
   */
  namespace Drupal\custom_mail\Controller;
  
  use Drupal\custom_pages\Controller\CustomPagesController;
  use Drupal\Core\Controller\ControllerBase;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\HttpFoundation\JsonResponse;
  use Drupal\image\Entity\ImageStyle;
  use Drupal\node\Entity\Node;
  use Drupal\user\Entity\User;
  use Drupal\Core\Url;
  use Drupal\Core\Link;
  use Drupal\file\Entity\File;
  use Drupal\Core\Mail\MailManagerInterface;
  use Drupal\Component\Utility\SafeMarkup;
  use Drupal\Component\Utility\Html;
  use \DateTime;

  
class MailController extends ControllerBase {
    
  public function sendCustomMail($from="",$to="",$subject="",$body=""){
    $send_mail = new \Drupal\Core\Mail\Plugin\Mail\PhpMail(); // this is used to send HTML emails
    
    if(empty($from)){
      $from = 'rajsarwade86@gmail.com';
    }
    if(empty($to)){
      $to = 'rajdattatestemail@gmail.com';
    }
    $message['headers'] = array(
    'content-type' => 'text/html',
    'MIME-Version' => '1.0',
    //'reply-to' => $from,
    'from' => 'IIRMA <'.$from.'>'
    );
    $message['to'] = $to;
    if(empty($subject)){
      $message['subject'] = "IIRMA";
    } else {
      $message['subject'] = $subject;
    }
     
    $message['body'] = $body;
     
    $send_mail->mail($message);
  }
  
  public function getEmailTemplates($key,$params=array()){
    $html = "";
    //echo "<pre>"; print_r($params); exit;
    switch ($key) {
      case 'job-post':
        $html.='<!DOCTYPE html>
          <html>
          <head>
          <meta charset="UTF-8">
          <title>Mail 2</title>
          </head>
          <body>
          <p>Hello'. $params['name'].',</p>
          <p>Thank you for posting a job. Once the moderator approves the job, you will receive a notification email & the job will be shown on the website.</p>
          <b>Job details</b><br><br>
          <b>Job Title:</b> ' .$params['title']. '<br><br>
          <b>Job Function:</b> ' .$params['field_job_functions'].  '<br><br>
          <b>Company Name:</b> ' .$params['field_company']. '<br><br>
          <b>Job Position:</b> ' .$params['field_job_positions']. '<br><br>
          <b>Location:</b> ' .$params['field_city']. '<br><br>
          <b>Min Experience:</b> ' .$params['field_experience']. '<br><br>
          <b>Max Experience:</b> ' .$params['field_maximum_experience'].'<br><br>
          <b>Job Description:</b> ' .$params['body'].'<br><br>
          <p>Thanks,<br> IAA Website Team</p>
          </body>
          </html>';
      break;
      case 'job-approve':
        $html.='<!DOCTYPE html>
          <html>
          <head>
          <meta charset="UTF-8">
          <title>Job Approved</title>
          </head>
          <body>
          <p>The job which you have posted is approved.</p>
          </body>
          </html>';
      break;
      case 'job-decline':
        $html.='<!DOCTYPE html>
          <html>
          <head>
          <meta charset="UTF-8">
          <title>Job Decline</title>
          </head>
          <body>
          <p>The job which you have posted is declined.</p>
          </body>
          </html>';
      break;
    }
    return $html;
  }
}