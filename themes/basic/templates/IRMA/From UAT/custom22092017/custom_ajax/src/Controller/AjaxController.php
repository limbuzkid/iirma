<?php
  /**
  @file
  Contains \Drupal\custom_ajax\Controller\AjaxController.
   */
  namespace Drupal\custom_ajax\Controller;
  
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
  use Drupal\Core\Password\PhpassHashedPassword;
  use \DateTime;
  use \Drupal\custom_mail\Controller\MailController;
  use Drupal\Core\Session\AccountInterface;
  use Drupal\Core\DependencyInjection\ContainerInjectionInterface; 
  use Symfony\Component\DependencyInjection\ContainerInterface;
  use Drupal\Core\Password\PasswordInterface;
  use Drupal\Component\Serialization\Json;

  class AjaxController extends ControllerBase implements ContainerInjectionInterface {
    
    public function __construct(PasswordInterface $password_hasher, AccountInterface $account) {
      $this->passwordHasher = $password_hasher;
      $this->account = $account;
    }
    
    public static function create(ContainerInterface $container) {
      return new static(
        $container->get('password'),
        $container->get('current_user')
      );
    }
    
    public function dropdown_options() {
      $page = $_POST['page'];
      $ddown  = new CustomPagesController;
      $subj_group_options   = '';
      $area_group_options   = '';
      $course_type_options  = '';
      $batch_options        = '';
      $sector_last_worked   = '';
      if($page == 'register') {
        $subj_group_options   = $ddown->subject_group_options();
        $area_group_options   = $ddown->area_group_options();
        $course_type_options  = $ddown->course_type_options();
        $batch_options        = $ddown->batch_options();
      }
      if($page == 'alumni') {
        $subj_group_options   = $ddown->subject_group_options();
        $area_group_options   = $ddown->area_group_options();
      }
      
      if($page == 'faculty') {
        $subj_group_options   = $ddown->subject_group_options();
        $area_group_options   = $ddown->area_group_options();
      }
      if($page == 'student') {
        $batch_options        = $ddown->batch_options();
        $sector_last_worked   = $ddown->sector_options();
      }
            
      return new JsonResponse(['batchNo' => $batch_options, 'areaGrp' => $area_group_options, 'subjGrp' => $subj_group_options, 'courseType' => $course_type_options, 'sector' => $sector_last_worked]);
    }
    
    public function featured_alumni() {
      global $base_url;
      $nid = $_POST['nid'];
      $upper = (int)$nid + (int)10;
      $cat = $_POST['cat'];
      $output = '';
      if($cat == 'exeCom') {
        $field = 'field_executive_member.value';
      } else {
        $field = 'field_featured_alumni.value';
      }
      $load_more_show = '0';
      
      $temp = array();
      if($cat = 'exeCom') {
        $sql = 'SELECT b.entity_id, b.field_batch_value
                FROM node__field_batch b
                LEFT JOIN node__field_executive_member e ON e.entity_id = b.entity_id
                WHERE e.field_executive_member_value = 1
                ORDER BY CAST(SUBSTR(b.field_batch_value, 5) AS SIGNED)
                LIMIT '.$nid.','. $upper;
      }
      if($cat = 'featAl') {
       $sql = 'SELECT entity_id, field_batch_value FROM node__field_batch ORDER BY CAST(SUBSTR(field_batch_value, 5) AS SIGNED) LIMIT '.$nid.','. $upper;
      }
      //exit;
      $db = \Drupal::database();
      $rows = $db->query($sql);
      $rows->allowRowCount = TRUE;
      
      foreach($rows as $row) {
        array_push($temp, $row->entity_id);
      }
      
      /*$query = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->condition('type', 'featured_alumni')
        ->condition($field, 1)
        ->condition('nid', $nid, '<')
        ->sort('nid', 'DESC')
        ->range(0,4);
      $nids = $query->execute();
      $count = $query->count()->execute();*/
      if($rows->rowCount() > 9) {
        $load_more_show = '1';
      }
      
      $nodes = entity_load_multiple('node', $temp);
      $row_count = 1;
      foreach($nodes as $node) {
        if($row_count < 10) {
          if($node->field_image[0] != NULL) {
            $original_image = $node->field_image->entity->getFileUri();
            $style = ImageStyle::load('featured');  // Load the image style configuration entity.
            $url = $style->buildUrl($original_image);
          } else {
            $url = $base_url.'/sites/default/files/default.jpg';
          }
          $shortdesc = preg_replace('/\s+?(\S+)?$/', '', substr($node->body->value, 0, 170)). '...'; 
          $output .= '<li rel="'.$node->nid->value.'"><span><img src="'.$url.'" height="183" alt=""></span>';
          $output .= '<div class="information clickLightbox"><h5 class="name"><a class="clickLightbox" href="javascript:;">'.$node->title->value.'</a></h5>';
          $output .= '<h6>'.$node->field_designation->value.'</h6><p>'.$node->field_company_name->value.'</p>';
          $output .= '<span><span class="batchNo">Batch: '.$node->field_batch->value.' </span><a href="'.$node->field_linkedin_url->value.'" target="_blank">';
          $output .= '<img src="/themes/basic/images/linked-icon.png" alt=""></a></span>';
          $output .= '<div class="detailSec"><summary>'.$shortdesc.'</summary></div></div></li>';
          $row_count++;
        }
      } 
      return new JsonResponse(['loadMore' => $load_more_show, 'data' => $output, 'lim' => $upper]);
      //return new JsonResponse('Hello World');      
    }
    
    public function featured_alumnus() {
      $nid = $_POST['nid'];
      $node = Node::load($nid);
      $output = '';
      if($node->field_image->target_id != NULL) {
        $image = file_create_url($node->field_image->entity->getFileUri());
      } else {
        $image = '';
      }
      $output .= '<div class="imgSec"><img src="'.$image.'" alt=""><a class="social" href="'.$node->field_linkedin_url->value.'" target="_blank"><img src="/themes/basic/images/linked-icon.png" alt=""></a></div>';
      $output .= '<div class="rightContent"><h3>'.$node->title->value.'</h3><h4>'.$node->field_designation->value.'</h4>';
      $output .= '<p>'.$node->field_company_name->value.'</p><div class="batchY"><h5>Batch: <strong>'.$node->field_batch->value.'</strong></h5>';
      $output .= '</div><div class="discription">';
      $output .= '<div class="scroll-pane"><p>'.$node->body->value.'</p></div></div></div>';
      return new JsonResponse(['data' => $output]);
    }
    
    public function alumni_news() {
      $nid            = $_POST['nid'];
      $sort_val       = $_POST['sortVal'];
      $load_more_show = '0';
      $output         = '';
      if($sort_val == 0) {
        $sort = 'DESC';
        $op = '<';
      } else {
        $sort = 'ASC';
        $op = '>';
      }
      
      $query = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->condition('type', 'alumni_news')
        ->condition('nid', $nid, $op)
        ->sort('nid', $sort)
        ->range(0,10);
      $nids = $query->execute();
      $count = $query->count()->execute();
      if($count > 9) {
        $load_more_show = '1';
      }
      
      $nodes = entity_load_multiple('node', $nids);
      $node_count = 1;
      foreach($nodes as $node) {
        if($node_count < 10) {
          if($node->field_image[0]->target_id != NULL) {
            $node_url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$node->nid->value);
            $shortdesc = preg_replace('/\s+?(\S+)?$/', '', substr($node->body->value, 0, 300));      
            $image = file_create_url($node->field_image->entity->getFileUri());
          } else {
            $image = '';  
          }
          if(strlen($node->title->value) > 45) {
            $title = preg_replace('/\s+?(\S+)?$/', '', substr($node->title->value, 0, 40)). '...';
          } else {
            $title = $node->title->value;
          }
          $news_date = date('M d Y', strtotime($node->field_news_date->value));
          $output .= '<li rel="'.$node->nid->value.'"><div class="imgSec"><img src="'.$image.'" alt=""></div>
          <div class="contentSec"><div class="titleSec"><h4>'.$title.'</h4><p>Date: '.$news_date.'</p>
            </div><div class="discription"><p>'.$shortdesc.'</p></div></div> <div class="btnSec">
            <a class="button" href="'.$node_url.'">Read More</a><a class="shareBtn" href="javascript:;">Share</a><div class="social"></div></div></li>';
          $node_count++;
        }
      }
      return new JsonResponse(['loadMore' => $load_more_show, 'data' => $output]);
    }
    
    public function alumni_news_sort() {
      $sort_val = $_POST['sortVal'];
      if($sort_val == 0) {
        $sort = 'DESC';
      } else {
        $sort = 'ASC';
      }
      $output = '';
      $load_more_show = false;
      $query = \Drupal::entityQuery('node')
          ->condition('status', 1)
          ->condition('type', 'alumni_news')
          ->sort('nid', $sort)
          ->range(0,10);
      $nids = $query->execute();
      $count = $query->count()->execute();
      if($count > 9) {
        $load_more_show = true;
      }
      $nodes = entity_load_multiple('node', $nids);
      
      $node_count = 1;
      foreach($nodes as $node) {
        if($node_count < 10) {
          $node_url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$node->nid->value);
          $shortdesc = preg_replace('/\s+?(\S+)?$/', '', substr($node->body->value, 0, 300));      
          if($node->field_image->target_id != NULL) {
            $image = file_create_url($node->field_image->entity->getFileUri());
          } else {
            $image = '';
          }      
          if(strlen($node->title->value) > 45) {
            $title = preg_replace('/\s+?(\S+)?$/', '', substr($node->title->value, 0, 40)). '...';
          } else {
            $title = $node->title->value;
          }
          $news_date = date('M d Y', strtotime($node->field_news_date->value));
          $output .= '<li rel="'.$node->nid->value.'"><div class="imgSec"><img src="'.$image.'" alt=""></div>
          <div class="contentSec"><div class="titleSec"><h4>'.$title.'</h4><p>Date: '.$news_date.'</p>
            </div><div class="discription"><p>'.$shortdesc.'</p></div></div> <div class="btnSec">
            <a class="button" href="'.$node_url.'">Read More</a><a class="shareBtn" href="javascript:;">Share</a></div></li>';
          $node_count++;
        }
      }
      
      $output .= '</div>';
      return new JsonResponse(['loadMore' => $load_more_show, 'data' => $output]);
    }
    
    public function alumni_news_search() {
      $output = '';
      $search_text = $_POST['srchTxt'];
      if($search_text == '' || $search_text == 'Search within news') {
        $msg = 'Please enter a search text';
        $is_search = false;
      } else {
        $is_search = true;
      }
      if($is_search) {
        $query = \Drupal::entityQuery('node');
        $group = $query->orConditionGroup()
              ->condition('title', '%'.$search_text.'%', 'LIKE')
              ->condition('body.value', '%'.$search_text.'%', 'LIKE');
        $entity_ids = $query
              ->condition('status', 1)
              ->condition('type', 'alumni_news')
              ->condition($group)
              ->sort('nid', 'DESC')
              ->range(0,10)
              ->execute();
        if($query->count()->execute() > 9) {
          $loadmore = '1';
        } else {
          $loadmore = '0';
        }
        
        if($entity_ids) {
          $nodes = entity_load_multiple('node', $entity_ids);
          $count = 1;
          foreach($nodes as $node) {
            if($count < 10) {
              $node_url   = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$node->nid->value);
              $shortdesc  = preg_replace('/\s+?(\S+)?$/', '', substr($node->body->value, 0, 300));      
              if($node->field_image->target_id != NULL) {
                $image = file_create_url($node->field_image->entity->getFileUri());
              } else {
                $image = '';
              }     
              if(strlen($node->title->value) > 45) {
                $title = preg_replace('/\s+?(\S+)?$/', '', substr($node->title->value, 0, 40)). '...';
              } else {
                $title = $node->title->value;
              }
              $news_date = date('M d Y', strtotime($node->field_news_date->value));
              $output .= '<li rel="'.$node->nid->value.'"><div class="imgSec"><img src="'.$image.'" alt=""></div>';
              $output .= '<div class="contentSec"><div class="titleSec"><h4>'.$title.'</h4><p>Date: '.$news_date.'</p></div>';
              $output .= '<div class="discription"><p>'.$shortdesc.'</p></div></div> <div class="btnSec">';
              $output .= '<a class="button" href="'.$node_url.'">Read More</a><a class="shareBtn" href="javascript:;">Share</a></div></li>';
              $count++;
            }
          }
          $msg = 'success';
        } else {
          $msg = 'Search yielded 0 results';
        }
      }
      //return new JsonResponse(['loadMore' => $load_more_show, 'data' => $output]);
      return new JsonResponse(['data' => $output, 'loadmore' => $loadmore, 'msg' => $msg]);
    }
    
    public function search() {
      $last_nid = $_POST['nid'];
      $srch_txt = $_POST['srchStr'];
      $srch_num = $_POST['noTxt'];
      
      $load_more_show = '0';    
      $output = '';
      $query = \Drupal::entityQuery('node');
      
      $group = $query->orConditionGroup()
            ->condition('title', '%'.$srch_txt.'%', 'LIKE')
            ->condition('body.value', '%'.$srch_txt.'%', 'LIKE');
      $entity_ids = $query
            ->condition('status', 1)
            ->condition($group)
            ->condition('nid', $last_nid, '>')
            ->sort('nid', $sort)
            ->execute();
            
      $temp = preg_match_all('/\d+/', $srch_num, $match);
      $start = $match[0][0];
      $end = $match[0][1];
      $node_count = 0;
      if($entity_ids) {
        foreach($entity_ids as $nid) {
          $alias = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$nid);
          if(strlen(strstr($alias,'/node/')) > 0) {
            // discard node
          } else {
            if($node_count < 10) {
              $start++;
              $node = Node::load($nid);
              $desc = preg_replace('/<h3\b[^>]*>(.*?)<\/h3>/i', '', $node->body->value);
              if(strlen($desc) >= 300) {
                $shortdesc  = preg_replace('/\s+?(\S+)?$/', '', substr($desc, 0, 300)).'...';
              } else {
                $shortdesc = $desc;
              }
              $output .= '<li rel="'.$node->nid->value.'"><a href="'.$alias.'"><h5>'.$node->title->value.'</h5></a>'.$shortdesc.'</li>';
            }
            $node_count++;
          }
        }
        $pager_txt = "Showing ". $start . " of ". $end;
      } else {
        $pager_txt = "No results found";
      }
      if(($end % $start) > 0) {
        $load_more_show = '1';
      } 
      
      return new JsonResponse(['loadMore' => $load_more_show, 'data' => $output, 'pager_txt' => $pager_txt]);
    }
    
    public function events() {
      $nid = $_POST['nid'];
      $page = $_POST['page'];
      if($page == 'archived') {
        $op = '<';
      } else {
        $op = '>';
      }
      $cur_date = \Drupal::time()->getRequestTime();
      $cur_date = date('Y-m-d', $cur_date);
          
      $output = '';
      $query = \Drupal::entityQuery('node')
            ->condition('status', 1)
            ->condition('type', 'events')
            ->condition('field_event_date.value', $cur_date, $op)
            ->condition('nid', $nid, '<')
            ->sort('nid', 'DESC')
            ->range(0,10);
      $nids = $query->execute();
      $count = $query->count()->execute();
      if($count > 9) {
        $load_more_show = '1';
      } else {
        $load_more_show = '0';
      }
      $nodes = entity_load_multiple('node', $nids);
      $count = 1;
      foreach($nodes as $node) {
        if($count < 10) {
          $alias = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$node->nid->value);
          $event_date = date('d M Y', strtotime($node->field_event_date->value));
          if($node->field_image->target_id != NULL) {
            $original_image = $node->field_image->entity->getFileUri();
            $style = ImageStyle::load('featured');  // Load the image style configuration entity.
            $image_url = $style->buildUrl($original_image);
          } else {
            $image_url = '';
          }
          $shortdesc = preg_replace('/\s+?(\S+)?$/', '', substr($node->body->value, 0, 110));
          $output .= '<li rel="'.$node->nid->value.'"><div class="imgSec"><img src="'.$image_url.'" alt=""></div>';
          $output .= '<div class="contentSec"><div class="titleSec"><h4>'.$node->field_event_name->value.'</h4></div>';
          $output .= '<div class="discription">'.$shortdesc.'...</p></div>';
          $output .= '<div class="addrSec"><span class="date">'.$event_date.'</span><span class="location">'.$node->field_event_venue->value.'</span></div>';
          $output .= '<div class="btnSec"><a class="button" href="'.$alias.'">View Details</a><a class="shareBtn" href="javascript:;">Share</a></div>';
          $output .= '</div></li>';
          $count++;
        }
      }
      return new JsonResponse(['loadMore' => $load_more_show, 'data' => $output]);
    }
    
    public function contact_form_submission(){
      //echo "<pre>"; print_r($_POST); 
      $error = false;
      if(isset($_POST['firstname']) && !empty($_POST['firstname']) && $_POST['firstname']!="First Name" && $this->valid_name($_POST['firstname'])==true){

      } else {
        $errorsArr[] = "firstnameErr-Please enter valid Firstname.";
        $error = true;
      }
      if(isset($_POST['lastname']) && !empty($_POST['lastname']) && $_POST['lastname']!="Last Name" && $this->valid_name($_POST['lastname'])==true) {

      } else {
        $errorsArr[] = "lastnameErr-Please enter valid Lastname.";
        $error = true;
      }
      if($this->valid_mobile($_POST['mobileno'])==false) {
        $errorsArr[] = "mobilenoErr-Please enter valid Mobile number.";
        $error = true;
      } else {

      }
      if (!filter_var($_POST['emailid'], FILTER_VALIDATE_EMAIL) === false) 
      {
        // is valid email id
      } else {
        $errorsArr[] = "emailidErr-Please enter valid Email.";
        $error = true;
      }
      if ($this->valid_address($_POST['message'])==false || empty($_POST['message'])) {
        $errorsArr[] = "messageErr-Please enter valid Message Details.";
        $error = true;
      } else {

      }
      if ($this->valid_address($_POST['feedbackmsg'])==false || empty($_POST['feedbackmsg'])) {
        $errorsArr[] = "feedbackmsgErr-Please select valid feedback.";
        $error = true;
      } else {

      }
      
      if($error==true) {
        return new JsonResponse(['message'=>'Failure','response' => $errorsArr]);
      } else {
        $query = \Drupal::database()->insert('contact_form_data');
        $query->fields(['firstname','lastname','mobileno','emailid','feedbackoption','message','status','date_created','date_modified']);
        $query->values([$_POST['firstname'],$_POST['lastname'],$_POST['mobileno'], $_POST['emailid'],$_POST['feedbackmsg'], $_POST['message'], 1,date('Y-m-d H:i:s'),date('Y-m-d H:i:s')]);
        $query->execute();
        
        $site_mail = \Drupal::config('system.site')->get('mail');
        $body = '<p>Thank you for contacting us. We will get back to you shortly.</p>';
        $mail_data = '<table>
                        <tr><td>Name</td><td>:</td><td>'.$_POST['firstname'].' '.$_POST['lastname'].'</td></tr>
                        <tr><td>Mobile Number</td><td>:</td><td>'.$_POST['mobileno'].'</td></tr>
                        <tr><td>Email</td><td>:</td><td>'.$_POST['emailid'].'</td></tr>
                        <tr><td>Feedback option</td><td>:</td><td>'.$_POST['feedbackmsg'].'</td></tr>
                        <tr><td>Feedback</td><td>:</td><td>'.$_POST['message'].'</td></tr>
                      </table>';
        
        $body = 'Thank you for your application. Your request has been sent to IRMA. They will revert shortly.<br>' . $mail_data ;
        
        $body = $this->build_html($_POST['firstname'], $body);
        /*MailController::sendCustomMail("",$email,"Alumni Website - MDP Request",$body); //Mail to user

        $body = '<p>Thank you for contacting us. We will get back to you shortly.</p>';
        $body .= '<table>
                    <tr><td>Name</td><td>:</td><td>'.$_POST['firstname'].' '.$_POST['lastname'].'</td></tr>
                    <tr><td>Mobile Number</td><td>:</td><td>'.$_POST['mobileno'].'</td></tr>
                    <tr><td>Email</td><td>:</td><td>'.$_POST['emailid'].'</td></tr>
                    <tr><td>Feedback option</td><td>:</td><td>'.$_POST['feedbackmsg'].'</td></tr>
                    <tr><td>Feedback</td><td>:</td><td>'.$_POST['message'].'</td></tr>
                  </table>';*/
        
        MailController::sendCustomMail("",$_POST['emailid'],"Thank you for contacting us.",$body);  // Mail to user
        MailController::sendCustomMail("",$site_mail,"Thank you for contacting us.",$body);  // Mail to admin
        $mailMessage = "success";
        return new JsonResponse(['message'=>'Success','mailmessage'=>$mailmessage]);
      }
    }

    public function check_contact_captcha(){
      //$secret="6LcjliMUAAAAAMY5jiB4ReIh2M_6hrGny4q1t_49";
      $secret = "6LcA3ioUAAAAAL-laHa-E5USaqn58VkiCH-Lcf4V";
      $response=$_POST["captcha"];

      $verify=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
      $captcha_success=json_decode($verify);
      if ($captcha_success->success==false) {
        //This user was not verified by recaptcha.
        return new JsonResponse(['response' => $captcha_success->success]);
      }
      else if ($captcha_success->success==true) {
        //This user is verified by recaptcha
        return new JsonResponse(['response' => $captcha_success->success]);
      }
    }
    
    public function image_upload() {
      $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
      if($user) {
        $uuid = $user->get('uuid')->value;
        $uid  = $user->get('uid')->value;
        if($_FILES['file']['error'] == 0) {
          $image_info = getimagesize($_FILES['file']['tmp_name']);
          $width = $image_info[0];
          $height = $image_info[1];
          $error = '0';
          $filename     = $_FILES['file']['name'];
          $filemime     = $_FILES['file']['type'];
          $temp         = explode('.', $filename);
          $extension    = strtolower(end($temp));
          if((isset($_POST['postby']) && $_POST['postby'] =="job") || isset($_POST['cvUpldBtn'])) {
            $allowed_ext = array('docx', 'doc', 'pdf');
            if(isset($_POST['postby'])) {
              $uri = 'public://jobs/'.$filename;
            } else {
              $uri = 'public://uploaded_files/'.$filename;
            }
            $textReplace = "file";
            $extmsg = 'Only files with extension docx, doc and pdf are allowed.';
          } else {
            $allowed_ext = array('jpeg', 'jpg', 'png', 'gif', 'JPEG', 'JPG', 'PNG', 'GIF');
            if(isset($_POST['fun'])) {
              $uri = 'public://images/fun/'.$filename;
            } else {
              $uri = 'public://images/users/'.$filename;
            }
            $textReplace = "image";
            $extmsg = 'Only files with extension jpeg, jpg, png and gif are allowed.';
          }
  
          if(!in_array($extension, $allowed_ext)) {
            $msg = $extmsg;
            $error = '1';
          }
          $size = $_FILES['file']['size'];
          $filesize = $size/2097152;
          if($filesize > 2) {
            $error = '1';
            $msg = 'Filesize exceeds the max allowed - 2 MB.';
          }
          if($error == '0') {
            $handle = fopen($_FILES['file']['tmp_name'], 'r');
            $filesaved = file_save_data($handle, $uri, FILE_EXISTS_RENAME);
            fclose($handle);
            if($filesaved) {
              $fpath = file_create_url($filesaved->getFileUri()); 
              $fid = $filesaved->id();
              if(isset($_POST['prof'])) {
                $fid = $fid .'-'. $width .'-'. $height;
              }
              $image = file_create_url($filename);
              $msg = 'success';
            } else {
              $msg = 'File Upload failed';
            }
          } 
          
        } else {
          $err_code = $_FILES['file']['error'];
          $error = '1';
          switch($err_code) {
            case 1: $msg = 'File exceeds the upload_max_filesize';
                    break;
            case 2: $msg = 'File exceeds the max size (2 MB)';
                    break;
            case 3: $msg = 'File was partially uploaded';
                    break;
            case 4: $msg = 'Please select an file to upload ';
                    break;
            case 6: $msg = 'Missing a temporary folder';
                    break;
            case 7: $msg = 'Failed to write file to disk';
                    break;
            case 8: $msg = 'A PHP extension stopped the file upload';
                    break;      
          }
        }
      }
      if(isset($_POST['return']) && !empty($_POST['return'])){
        return $fid;
      } else {
        return new JsonResponse(['message'=>$msg,'fid'=>$fid, 'error'=> $error, 'nnid' => $uuid, 'file' => $fpath, 'filename' => $filename]);
      }
    }

    public function chapters() {
      $nid = $_POST['nid'];
      $upper = (int)$nid + 5;
      $output = '';
      $load_more_show = false;
      $query = \Drupal::entityQuery('node')
            ->condition('status', 1)
            ->condition('type', 'chapters')
            ->sort('title', 'ASC')
            ->range($nid, $upper);
      $nids = $query->execute();
      $count = $query->count()->execute();
      if($count > 4) {
        $load_more_show = true;
      }
      $nodes = entity_load_multiple('node', $nids);
      $node_count = 1;
      foreach($nodes as $node) {
        if($node_count <= 4) {
          $node_url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$node->nid->value);
          $shortdesc = preg_replace('/\s+?(\S+)?$/', '', substr($node->body->value, 0, 150));      
          if($node->field_image->target_id != NULL) {
            $image = file_create_url($node->field_image->entity->getFileUri());
          } else {
            $image = '';
          }      
          if(strlen($node->title->value) > 45) {
            $title = preg_replace('/\s+?(\S+)?$/', '', substr($node->title->value, 0, 40)). '...';
          } else {
            $title = $node->title->value;
          }
          
          $output .= '<li rel="'.$node->nid->value.'" class="netowrkSec">
                        <div class="imgSec">
                            <img src="'.$image.'" alt="">
                        </div>
                        <div class="content">
                            <h3>'.$title.'</h3>
                            <p>'.$shortdesc.'</p>
                            <div class="bttnSec">
                                <a class="button" href="'.$node_url.'">View</a>
                            </div>
                        </div>    
                      </li>';
          $node_count++;
        }
      }
      return new JsonResponse(['loadMore' => $load_more_show, 'data' => $output, 'lim'=>$upper]);
    }
    
    public function batchrepresentative() {
      $nid = $_POST['nid'];
      $upper = (int)$nid + 5;
      $output = '';
      $load_more_show = '0';
      
      $temp = array();
      $sql = "SELECT nid, title
              FROM node_field_data
              WHERE type='batch_representative'
              ORDER BY CAST(SUBSTR(`title`, 5) AS SIGNED)
              LIMIT ".$nid.",".$upper;
      $db = \Drupal::database();
      $rows = $db->query($sql);
      $rows->allowRowCount = TRUE;
      foreach($rows as $row) {
        //echo $row->entity_id . ' ' . $row->field_batch_value . '<br>';
        array_push($temp, $row->nid);
      }
      
      
      $count = $rows->rowCount();
      if($count > 4) {
        $load_more_show = '1';
      }
      $nodes = entity_load_multiple('node', $temp);
      $node_count = 1;
      foreach($nodes as $node) {
        if($node_count <= 4) {
          $node_url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$node->nid->value);
          $shortdesc = preg_replace('/\s+?(\S+)?$/', '', substr($node->body->value, 0, 150));      
          if($node->field_image->target_id != NULL) {
            $image = file_create_url($node->field_image->entity->getFileUri());
          } else {
            $image = '';
          }     

          if(strlen($node->title->value) > 45) {
            $title = preg_replace('/\s+?(\S+)?$/', '', substr($node->title->value, 0, 40)). '...';
          } else {
            $title = $node->title->value;
          }
          
          $output .= '<li rel="'.$node->nid->value.'" class="netowrkSec"><div class="imgSec"><img src="'.$image.'" alt=""></div>
                      <div class="content"><h3>'.$title.'</h3><p>'.$shortdesc.'</p>
                      <div class="bttnSec"><a class="button" href="'.$node_url.'">View</a></div></div></li>';
          $node_count++;
        }
      }
      return new JsonResponse(['loadMore' => $load_more_show, 'data' => $output, 'lim'=>$upper-1]);
    }

    public function user_register() {
      global $base_url;
      $mesg         = array();
      $error        = false;
      $status       = 'failed';
      $experience   = $area_group = $sub_group = $sub_group = $addr1 = $addr2 = $addr3 = $country = $city = '';
      $course_type  = $course_name = $join_year = $grad_year = $batch_no = $roll_no = '';
      $user_type    = $_POST['userType'];
      $fid          = $_POST['fid'];
      $actual_token = $_SESSION['usRegn'];
      $hashed       = new PhpassHashedPassword();
      $salt         = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random       = $_POST['token'];
      $token        = md5($salt.$random);
      $randm        = new CustomPagesController;
      $rnd_num      = $hashed->hash($randm->generate_random_string());
      $fresh_token  = md5($salt.$rnd_num);

      $_SESSION['usRegn'] = $fresh_token;
      
      if($actual_token == $token) {
        $temp_mob = explode('-', $_POST['mobile']); 
        $country_code = $temp_mob[0];
        
        if(!empty($temp_mob[1])) {
          if(!$this->valid_mobile($temp_mob[1])) {
            $msg[] = 'phoneNo-Please enter valid mobile number.';
            $error = true;
          } else {
            $mobile = $_POST['mobile'];
          }
        } else {
          $msg[] = 'phoneNo-Please enter mobile number.';
          $error = true;
        }
        if($_POST['eMail'] == '' || $_POST['eMail'] == 'Email Id') {
          $msg[] = 'emailId-Please enter email id.';
          $error = true;
        } else {
          if(!$this->valid_mail($_POST['eMail'])) {
            $msg[] = 'emailId-Please enter valid email id.';
            $error = true;
          } else {
            $email = $_POST['eMail'];
          }
        }
        if($_POST['fName'] == '' || $_POST['fName'] == 'First Name') {
          $msg[] = 'firstName-Please enter first name.';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['fName'])) {
            $msg[] = 'firstName-Please enter valid first name.';
            $error = true;
          } else {
            $fname = $_POST['fName'];
          }
        }
        if($_POST['lName'] == '' || $_POST['lName'] == 'Last Name') {
          $msg[] = 'lastName-Please enter Last Name.';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['lName'])) {
            $msg[] = 'lastName-Please enter valid last name.';
            $error = true;
          } else {
            $lname = $_POST['lName'];
          }
        }
        if(isset($_POST['gender'])) {
          if(strtolower($_POST['gender']) != 'male' && strtolower($_POST['gender']) != 'female') {
            $msg[] = 'gender-Please enter valid gender.';
            $error = true;
          } else {
            $gender = $_POST['gender'];
          }
        } else {
          $msg[] = 'gender-Please enter valid gender.';
          $error = true;
        }
        
      
        if($_POST['dob'] != '' && $_POST['dob'] != 'Date of Birth') {
          if(!$this->valid_date($_POST['dob'])) {
            $msg[] = 'dobReg-Please enter birth date.';
            $error = true;
          } else {
            $dob = $_POST['dob'];
          }
        }
      
        if(trim($_POST['password']) == '') {
          $msg[] = 'ppassword-Please enter password';
          $error = true;
        } else {
          /*if(strlen($_POST['password']) < 8) {
            $mesg[] = 'ppassword-Password must be at least 8 characters long';
            $error = true;
          } else {
            $password = trim($_POST['password']);
          }*/
          $password = trim($_POST['password']);
        }
        if(trim($_POST['c_password']) == '') {
          $msg[] = 'cPassword-Please enter confirm  password';
          $error = true;
        } else {
          if(trim($_POST['password']) != trim($_POST['c_password'])) {
            $msg[] = 'cPassword-Passwords do not match';
            $error = true;
          } else {
            $password = trim($_POST['password']);
            //$hashed_password = new PhpassHashedPassword();
            //$new_password = $hashed_password->hash($password);
          }
        }
        if($user_type == 'alumni') {
          $mobile = $_POST['mobile'];
          if($_POST['addr1'] != '' && $_POST['addr1'] != 'Address 1') {
            if(!$this->valid_address($_POST['addr1'])) {
              $msg[] = 'addr1-Please enter valid address.';
              $error = true;
            } else {
              $addr1 = $_POST['addr1'];
            }
          }
          if($_POST['addr2'] != '' && $_POST['addr2'] != 'Address 2') {
            if(!$this->valid_address($_POST['addr2'])) {
              $msg[] = 'addr2-Please enter valid address.';
              $error = true;
            } else {
              $addr2 = $_POST['addr2'];
            }
          }
          if($_POST['addr3'] != '' && $_POST['addr3'] != 'Address 3') {
            if(!$this->valid_address($_POST['addr3'])) {
              $msg[] = 'addr3-Please enter valid address.';
              $error = true;
            } else {
              $addr3 = $_POST['addr3'];
            }
          }
          if($_POST['country'] != '' && $_POST['country'] != 'Select') {
            if(!$this->valid_input($_POST['country'])) {
              $msg[] = 'cntryErr-Please select a valid country.';
              $error = true;
            } else {
              $country = $_POST['country'];
            }
          }
          if($_POST['state'] != '' && $_POST['state'] != 'Select') {
            if(!$this->valid_input($_POST['state'])) {
              $msg[] = 'stErr-Please select a valid state.';
              $error = true;
            } else {
              $state = $_POST['state'];
            }
          }
          if($_POST['city'] != '' && $_POST['city'] != 'Select') {
            if(!$this->valid_input($_POST['city'])) {
              $msg[] = 'cityErr-Please select a valid city.';
              $error = true;
            } else {
              $city = $_POST['city'];
            }
          }
          
          if($_POST['courseType'] == '' || $_POST['courseType'] == 'Select') {
            $msg[] = 'crseErr-Please select course type.';
            $error = true;
          } else {
            if(!$this->valid_input($_POST['courseType'])) {
              $msg[] = 'crseErr-Please select a valid course type.';
              $error = true;
            } else {
              $course_type  = $_POST['courseType'];
            }
          }
          
          if($_POST['courseName'] != '') {
            if(!$this->valid_input($_POST['courseName'])) {
              $msg[] = 'course-Please enter a valid course name.';
              $error = true;
            } else {
              $course_name  = $_POST['courseName'];
            }
          } else {
            $course_name  = '';
          }
          
          if(trim($_POST['joinYr']) == '' || trim($_POST['joinYr']) == 'Select') {
            $msg[] = 'joinErr-Please select joining year.';
            $error = true;
          } else {
            if(!$this->valid_year(trim($_POST['joinYr']))) {
              $msg[] = 'joinErr-Please select a valid year.';
              $error = true;
            } else {
              $join_year  = trim($_POST['joinYr']);
            }
          }
          
          if($_POST['gradYr'] == '' || $_POST['gradYr'] == 'Select') {
            $msg[] = 'gradErr-Please select graduating year.';
            $error = true;
          } else {
            if(!$this->valid_year($_POST['gradYr'])) {
              $msg[] = 'gradErr-Please select a valid year.';
              $error = true;
            } else {
              $grad_year = $_POST['gradYr'];
            }
          }
          
          if($_POST['batchNo'] == '' || $_POST['batchNo'] == 'Select') {
            $msg[] = 'batchErr-Please select batch number.';
            $error = true;
          } else {
            if(!$this->valid_input($_POST['batchNo'])) {
              $msg[] = 'batchErr-Please select a valid batch number.';
              $error = true;
            } else {
              $batch_no = $_POST['batchNo'];
            }
          }
          if($_POST['rollNo'] != '') {
            if(!$this->valid_input($_POST['rollNo'])) {
              $msg[] = 'rollNo-Please enter a valid roll number.';
              $error = true;
            } else {
              $roll_no  = $_POST['rollNo'];
            }
          } else {
            $roll_no  = '';
          }
        } else {
          
        }
        
        if($user_type == 'student') {
          if($_POST['courseType'] == '' || $_POST['courseType'] == 'Select') {
            $msg[] = 'crseErr-Please select course type.';
            $error = true;
          } else {
            if(!$this->valid_input($_POST['courseType'])) {
              $msg[] = 'crseErr-Please select a valid course type.';
              $error = true;
            } else {
              $course_type  = $_POST['courseType'];
            }
          }
          
          if($_POST['courseName'] != '') {
            if(!$this->valid_input($_POST['courseName'])) {
              $msg[] = 'course-Please enter a valid course name.';
              $error = true;
            } else {
              $course_name  = $_POST['courseName'];
            }
          } else {
            $course_name  = '';
          }
          
          if(trim($_POST['joinYr']) == '' || trim($_POST['joinYr']) == 'Select') {
            $msg[] = 'joinErr-Please select joining year.';
            $error = true;
          } else {
            if(!$this->valid_year(trim($_POST['joinYr']))) {
              $msg[] = 'joinErr-Please select a valid year.';
              $error = true;
            } else {
              $join_year  = trim($_POST['joinYr']);
            }
          }
          if($_POST['batchNo'] == '' || $_POST['batchNo'] == 'Select') {
            $msg[] = 'batchErr-Please select batch number.';
            $error = true;
          } else {
            if(!$this->valid_input($_POST['batchNo'])) {
              $msg[] = 'batchErr-Please select a valid batch number.';
              $error = true;
            } else {
              $batch_no = $_POST['batchNo'];
            }
          }
          
          if($_POST['rollNo'] != '') {
            if(!$this->valid_input($_POST['rollNo'])) {
              $msg[] = 'rollNo-Please enter a valid roll number.';
              $error = true;
            } else {
              $roll_no  = $_POST['rollNo'];
            }
          } else {
            $roll_no  = '';
          }
        }
      
        if($user_type == 'faculty') {
          if($_POST['areaGroup'] == '') {
            $msg[] = 'areaGrp-Please select an Area Group.';
            $error = true;
          } else {
            $area_group = $_POST['areaGroup'];
          }
          
          if($_POST['subGroup'] == '') {
            $msg[] = 'subGrp-Please select a Subject Group.';
            $error = true;
          } else {
            $sub_group  = $_POST['subGroup'];
          }
          
          if($_POST['experience'] == '') {
            $msg[] = 'expFactGrp-Please enter experience at Irma.';
            $error = true;
          } else {
            if(!is_numeric($_POST['experience']) || $_POST['experience'] > 99) {
              $msg[] = 'expFactGrp-Please enter a valid experience number.';
              $error = true;
            }  else {
              $experience = $_POST['experience'];
            }
          }
        }
      
        $name = $fname .' '.$lname;
        $username = strtolower($fname.$lname);
        
        $query = \Drupal::database()->select('users_field_data', 'ufd');
        $query->fields('ufd', ['uid', 'name']);
        $query->condition('ufd.name', $username.'%', 'LIKE');
        $res  = $query->execute()->fetchAllKeyed();
        if($res) {
          $user_array  = array();
          foreach($res as $row) {
            array_push($user_array, $row);
          }
          //print_r($user_array);
          $index = 1;
          $temp_username = $username;
          foreach($user_array as $uname) {
            if($uname == $temp_username) {
              $temp_username = $username.$index;
              $index++;
            }
          }
          $username = $temp_username;
        }
        
        $created = $changed = \Drupal::time()->getRequestTime();
        //$temp = explode('-', $mobile);
        //$country_code = $temp[0];
        
        $query = \Drupal::database()->select('users_field_data', 'ufd');
        $query->fields('ufd', ['uid', 'name', 'mail']);
        $query->condition('ufd.mail', $email);
        //$query->condition('ufd.name', $name);
        $query->range(0, 1);
        $result = $query->execute()->fetchAssoc();
        if($result) {
          if(!$error) {
            $msg[] = 'formFealds-User already exists.';
            $error = true;
          }
        }
        if(!$error) {
          $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
          $user = \Drupal\user\Entity\User::create();
          //Mandatory settings
          $user->setPassword($password);
          $user->enforceIsNew();
          $user->setEmail($email);
          $user->setUsername($username); //This username must be unique and accept only a-Z,0-9, - _ @ .
          //Optional settings'
          $user->set("init", $email);
          $user->set("langcode", $language);
          $user->set("preferred_langcode", $language);
          $user->set("preferred_admin_langcode", $language);
          //$user->set("setting_name", 'setting_value');
          //$user->activate();
          //Save user
          $user->save();
          $uuid = $user->uuid();
          $uid = $user->id();
          if($uid) {
            if($fid != '') {
              $qry = \Drupal::database()->insert('user__user_picture');
              $qry->fields(['bundle', 'deleted', 'entity_id', 'revision_id', 'langcode', 'delta', 'user_picture_target_id', 'user_picture_alt', 'user_picture_title', 'user_picture_width', 'user_picture_height']);
              $qry->values(['user', '0', $uid, $uid, 'en', '0', $fid, '', '', '85', '77']);
              $qry->execute();
            }
            $query = \Drupal::database()->insert('users_extra');
            $query->fields(['bundle', 'deleted', 'entity_id', 'user_type', 'name', 'gender', 'date_of_birth', 'address_1', 'address_2', 'address_3', 'country_code', 'country', 'state','city', 'mobile_number', 'course_type', 'course_name', 'joining_year', 'graduating_year', 'batch_number', 'roll_number','area_group', 'subject_group', 'years_as_faculty']);
            $query->values(['user', '0', $uid, $user_type, $name, $gender, $dob, $addr1, $addr2, $addr3, $country_code, $country, $state, $city, $mobile, $course_type, $course_name, $join_year, $grad_year, $batch_no, $roll_no, $area_group, $sub_group, $experience]);
            if($query->execute() != NULL) {
              $query = \Drupal::database()->update('file_managed');
              $query->fields([
                'uuid' => $uuid,
                'uid'  => $uid
              ]);
              $query->condition('fid', $fid);
              $query->execute();
              //drupal_session_destroy_uid($uid);
              $status = 'success';
              $msg = 'Thank you for signing up. We have sent a verification link to your registered email ID. To confirm your registration, kindly click on the link provided in the email.';
              $mail_url = $base_url.'/my-account/'.$uuid.'-'.$uid;
              $site_mail = \Drupal::config('system.site')->get('mail');
              $body = 'Thank you for signing up. Kindly please <a href="'.$mail_url.'">click here</a> to confirm the registration.';
              $body = $this->build_html($name, $body);
              MailController::sendCustomMail("",$email,"Confirmation Required",$body); //Mail to user
              
              } else {
                $msg[] = 'formFealds-users_extra insert failed';
              }

          } else {
            $msg = 'formFealds-no user id generated';
            $status = 'failed';
          }
        }
      } else {
        $msg = 'formFealds-Token mismatch...Please try again';
        $status = 'failed';
        //return new JsonResponse(['msg' => $msg, 'data' => 'tErr', 'uid'=> '', 'fid' => '', 'tok' => $rnd_num]);
      }
      return new JsonResponse(['msg' => $msg, 'data' => $status, 'uid'=> $uuid, 'fid' => $fid, 'tok' => $rnd_num]);
    }
    
    public function user_account() {
      global $base_url;
      $actual_token = $_SESSION['myAcct'];
      $hashed       = new PhpassHashedPassword();
      $salt         = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random       = $_POST['token'];
      $token        = md5($salt.$random);
      $randm        = new CustomPagesController;
      $rnd_num      = $hashed->hash($randm->generate_random_string());
      $fresh_token  = md5($salt.$rnd_num);
      $red_url = '';

      $_SESSION['myAcct'] = $fresh_token;
      $mesg                             = array();
      $error                            = false;
      $status                           = 'failed';
      $dob                              = NULL;
      $addr1 = $addr2 = $addr3          = NULL;
      $country = $state = $city         = NULL;
      $mobile   = $code                 = NULL;
      $course_type  = $course_name      = NULL;
      $joining_year = $graduating_year  = NULL;
      $batch_number = $roll_no          = NULL;
      $p_addr1 = $p_addr2 = $p_addr3    = NULL;
      $area_group = $subject_group      = NULL;
      $years_as_faculty                 = NULL;
      $cv_target_id                     = NULL;
      $year_of_experience               = 0;
      $sector_last_worked               = NULL;
      $family_details = $media_coverage_forums = $achievements = $professional_background = $educational_background = NULL;
      
      if(trim($_POST['userType']) == 'alumni' || trim($_POST['userType']) == 'student' || trim($_POST['userType']) == 'faculty') {
        $user_type = trim($_POST['userType']);
      } else {
        $mesg[] = 'Invalid user type';
        $error = true;
      }
      if(isset($_POST['userId'])) {
        $temp = explode('-', $_POST['userId']);
        $user_id = $temp[1];
      } else {
        $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
        if($user) {
          $user_id  = $user->get('uid')->value;
        } else {
          return new JsonResponse([]);
        }
      }
           
      if($_POST['acctType'] == 'basic') {
        if(trim($_POST['fname']) == '') {
          $mesg[] = 'fName-Please enter first name';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['fname'])) {
            $mesg[] = 'fName-Please enter valid first name';
            $error = true;
            $next   = false;
          } else {
            $first_name = $_POST['fname'];
          }
        }
        if(trim($_POST['lname']) == '') {
          $mesg[] = 'lName-Please enter Last Name';
          $error = true;
          $next   = false;
        } else {
          if(!$this->valid_name($_POST['lname'])) {
            $mesg[] = 'lName-Please enter valid last name';
            $error = true;
            $next   = false;
          } else {
            $last_name  = $_POST['lname'];
          }
        }
        if(trim($_POST['gender']) == 'Male' || trim($_POST['gender']) == 'Female') {
          $gender = trim($_POST['gender']);
        } else {
          $error = true;
          $next   = false;
        }
        if(trim($_POST['email']) == '') {
          $mesg[] = 'mail-Please enter email id.';
          $error = true;
          $next   = false;
        } else {
          if(!$this->valid_mail($_POST['email'])) {
            $mesg[] = 'mail-Please enter a valid email id.';
            $error = true;
            $next   = false;
          } else {
            $email  = $_POST['email'];
          }
        }
        
        
      
        if(trim($_POST['dob']) != '') {
          if(!$this->valid_date($_POST['dob'])) {
            $mesg[] = 'dob-Please enter a valid date.';
            $error = true;
            $next   = false;
          } else {
            $dob = trim($_POST['dob']);
          }
        }
        
        if(trim($_POST['code']) == '') {
          $mesg[] = 'phoneNo-Please enter a valid country code.';
          $error = true;
          $next   = false;
        } else {
          if(!$this->country_code(trim($_POST['code']))) {
            $mesg[] = 'phoneNo-Please select a valid country code';
            $error = true;
            $next   = false;
          } else {
            $code = trim($_POST['code']);
          }
        }

        if(trim($_POST['mobile']) == '') {
          $mesg[] = 'phoneNo-Please enter mobile number';
          $error = true;
        } else {
          if(!is_numeric(trim($_POST['mobile']))) {
            $mesg[] = 'phoneNo-Please enter valid mobile number.';
            $error = true;
            $next   = false;
          } elseif(strlen(trim($_POST['mobile'])) > 15) {
            $mesg[] = 'phoneNo-Please enter valid mobile number.';
            $error = true;
            $next   = false;
          } else {
            $mobile = trim($_POST['mobile']);
          }
        }
        if(trim($_POST['nickName']) != '' && trim($_POST['nickName']) != 'Nickname In IRMA') {
          if(!$this->valid_name(trim($_POST['nickName']))) {
            $mesg[] = 'nickName-Please enter a valid nickname.';
            $error = true;
            $next   = false;
          } else {
            $nickname = trim($_POST['nickName']);
          }
        }
      
        if(trim($_POST['hobbies']) != '' && trim($_POST['nickName']) != 'Hobbies & Interests') {
          $hobbies = trim($_POST['hobbies']);
        }

        if(trim($user_type) == 'alumni' || trim($user_type) == 'student') {
          if(trim($_POST['courseType']) == '' || trim($_POST['courseType']) == 'Select') {
            $mesg[] = 'courseType-Please select a Course type';
            $error = true;
            $next   = false;
          } else {
            $course_type = trim($_POST['courseType']);
          }
          if(strtolower($_POST['courseType']) == 'other') {
            if(trim($_POST['courseName']) == '') {
              $mesg[] = 'courseName-Course Please enter name.';
              $error = true;
              $next   = false;
            } else {
              $course_name = trim($_POST['courseName']);
            }
          }
          if(trim($_POST['joiningYear']) == '' || trim($_POST['joiningYear']) == 'Select') {
            $mesg[] = 'joiningYear-Please select joining year';
            $error = true;
            $next   = false;
          } else {
            if(!$this->valid_year(trim($_POST['joiningYear']))) {
              $mesg[] = 'joiningYear-Please select a valid year';
              $error = true;
              $next   = false;
            } else {
              $joining_year = trim($_POST['joiningYear']);
            }
          }
          if(trim($_POST['batchNo']) == '' || trim($_POST['batchNo']) == 'Select') {
            $mesg[] = 'batchNumber-Please select batch number.';
            $error = true;
            $next   = false;
          } else {
            $batch_number = trim($_POST['batchNo']);
          }
          if(trim($_POST['rollNo']) != '') {
            if(!is_numeric(trim($_POST['rollNo']))) {
              $mesg[] = 'rollNo-Please enter roll number.';
              $error = true;
              $next   = false;
            } else {
              $roll_no = trim($_POST['rollNo']);
            }
          }
        }
        
        if($user_type == 'alumni') {
          if(trim($_POST['addr1']) != '') {
            if(!$this->valid_address($_POST['addr1'])) {
              $mesg[] = 'addr1-Please enter valid address';
              $error = true;
              $next   = false;
            } else {
              $addr1 = trim($_POST['addr1']);
            }
          }
          if(trim($_POST['addr2']) != '') {
            if(!$this->valid_address($_POST['addr2'])) {
              $mesg[] = 'addr2-Please enter valid address';
              $error = true;
              $next   = false;
            } else {
              $addr2 = trim($_POST['addr2']);
            }
          }
          if(trim($_POST['addr3']) != '') {
            if(!$this->valid_address($_POST['addr3'])) {
              $mesg[] = 'addr3-Please enter valid address';
              $error = true;
              $next   = false;
            } else {
              $addr3 = trim($_POST['addr3']);
              
            }
          }
          
          if(trim($_POST['cntry']) == '' || trim($_POST['cntry']) == 'Select') {
            $mesg[] = 'cntryList-Please select a country.';
            $error = true;
            $next   = false;
          } else {
            if(!$this->valid_country(trim($_POST['cntry']))) {
              $mesg[] = 'cntryList-Please select a valid country.';
              $error = true;
              $next   = false;
            } else {
              $cur_country = trim($_POST['cntry']);
            }
          }
          if(trim($_POST['state']) == '' || trim($_POST['state']) == 'Select') {
            $mesg[] = 'stateList-Please select a state.';
            $error = true;
            $next   = false;
          } else {
            if(!$this->valid_country(trim($_POST['state']))) {
              $mesg[] = 'stateList-Please select a valid state.';
              $error = true;
              $next   = false;
            } else {
              $cur_state = trim($_POST['state']);
            }
          }
          if(trim($_POST['city']) == '' || trim($_POST['city']) == 'Select') {
              $mesg[] = 'cityList-Please select a city.';
              $error = true;
          } else {
            if(!$this->valid_country(trim($_POST['city']))) {
              $mesg[] = 'cityList-Please select a valid city.';
              $error = true;
              $next   = false;
            } else {
              $cur_city = trim($_POST['city']);
            }
          }
          if($_POST['addrChked'] == 1) {
            $p_addr1    = $addr1;
            $p_addr2    = $addr2;
            $p_addr3    = $addr3;
            $p_country  = $cur_country;
            $p_state    = $cur_state;
            $p_city     = $cur_city;
          } else {
            if(trim($_POST['pAddr1']) != '') {
              if(!$this->valid_address($_POST['pAddr1'])) {
                $mesg[] = 'perAddr1-Please enter valid address';
                $error = true;
                $next   = false;
              } else {
                $p_addr1 = trim($_POST['pAddr1']);
              }
            } else {
              $p_addr1 = '';
            }
            if(trim($_POST['pAddr2']) != '') {
              if(!$this->valid_address($_POST['pAddr2'])) {
                $mesg[] = 'perAddr2-Please enter valid address';
                $error = true;
                $next   = false;
              } else {
                $p_addr2 = trim($_POST['pAddr2']);
              }
            }
            if(trim($_POST['pAddr3']) != '') {
              if(!$this->valid_address($_POST['pAddr3'])) {
                $mesg[] = 'perAddr3-Please enter valid address';
                $error = true;
                $next   = false;
              } else {
                $p_addr3 = trim($_POST['pAddr3']);
              }
            } 
            if(trim($_POST['pCntry']) == '' || trim($_POST['pCntry']) == 'Select') {
                $mesg[] = 'permaCntry-Please select a country.';
                $error = true;
            } else {
              if(!$this->valid_country(trim($_POST['pCntry']))) {
                $mesg[] = 'permaCntry-Please select a valid country.';
                $error = true;
                $next   = false;
              } else {
                $p_country = trim($_POST['pCntry']);
              }
            }
            if(trim($_POST['pState']) == '' || trim($_POST['pState']) == 'Select') {
              $mesg[] = 'permaState-Please select a state.';
              $error = true;
            } else {
              if(!$this->valid_country(trim($_POST['pCity']))) {
                $mesg[] = 'permaState-Please select a valid state.';
                $error = true;
                $next   = false;
              } else {
                $p_state = trim($_POST['pState']);
              }
            }
            if(trim($_POST['pCity']) == '' || trim($_POST['pCity']) == 'Select') {
              $mesg[] = 'permaCity-Please select a city.';
              $error = true;
            } else {
              
              if(!$this->valid_country(trim($_POST['pCity']))) {
                $mesg[] = 'permaCity-Please select a valid city.';
                $error = true;
                $next   = false;
              } else {
                $p_city = trim($_POST['pCity']);
              }
            }
          }
          
          if(trim($_POST['graduatingYear']) == '' || trim($_POST['graduatingYear']) == 'Select') {
            $mesg[] = 'graduatingYear-Please select graduating year';
            $error = true;
            $next   = false;
          } else {
            if(!$this->valid_year(trim($_POST['graduatingYear']))) {
              $mesg[] = 'graduatingYear-Please select a valid year';
              $error = true;
              $next   = false;
            } else {
              $graduating_year = trim($_POST['graduatingYear']);
            }
          }
          
          $fam_details  = json_decode($_POST['famDetails']);
          $count = 0;
          foreach($fam_details as $family) {
            if($family->name == '') {
              $mesg[] = 'famName*'.$count.'-Please enter name.';
              $error = true;
              $next   = false;
            } else {
              if(!$this->valid_name($family->name)) {
                $mesg[] = 'famName*'.$count.'-Please enter a valid name.';
                $error = true;
                $next   = false;
              }
            }
            if($family->relation == '' || $family->relation == 'Select') {
              $mesg[] = 'famRelation*'.$count.'-Please select relationship.';
              $error = true;
            } else {
              if(!$this->valid_relation($family->relation)) {
                $mesg[] = 'famRelation*'.$count.'-Please select a valid batch number';
                $error = true;
                $next   = false;
              }
            }
            if($family->age == '') {
              $mesg[] = 'famAge*'.$count.'-Please enter age.';
              $error = true;
            } else {
              if(!is_numeric($family->age)) {
                $mesg[] = 'famAge*'.$count.'-Please enter a valid age.';
                $error = true;
                $next   = false;
              }
            }
            if($family->mobile != '') {
              if(!is_numeric($family->mobile)) {
                $mesg[] = 'famContact*'.$count.'-Please enter contact number.';
                $error = true;
                $next   = false;
              }
            }
            $count++;
          }
          
          if($_POST['addrChk'] == 0 || $_POST['addrChk'] == 1) {
            $addr_chked = $_POST['addrChk'];
          } else {
            $mesg[] = 'addrChk-Invalid selection';
            $error = true;
          }
          if($addr_chked == 1) {
            $permanent_addr = json_encode(array('addr1' => $p_addr1, 'addr2' => $p_addr2, 'addr3' => $p_addr3, 'addrChk' => $addr_chked , 'country'=> $p_country, 'state'=> $p_state, 'city' => $p_city));
          } else {
            if($p_addr1 == '' && $p_addr2 == '' && $p_addr1 == '') {
              $permanent_addr = NULL;
            } else {
              $permanent_addr = json_encode(array('addr1' => $p_addr1, 'addr2' => $p_addr2, 'addr3' => $p_addr3, 'addrChk' => $addr_chked , 'country'=> $p_country, 'state'=> $p_state, 'city' => $p_city));
            }
          }
          
        }
        if($user_type == 'faculty') {
          if(trim($_POST['areaGrp']) == '' || $_POST['areaGrp'] == 'Select') {
            $mesg[] = 'areaGrp-Please select a area group.';
            $error = true;
            $next   = false;
          } else {
            $area_group = $_POST['areaGrp'];
          }
          if(trim($_POST['subjGrp']) == '' || $_POST['subjGrp'] == 'Select') {
            $mesg[] = 'subjGrp-Please enter Concerned IRMA Subject Group.';
            $error = true;
            $next   = false;
          } else {
            $subject_group = $_POST['subjGrp'];
          }
          if(trim($_POST['experience']) == '') {
            $mesg[] = 'expfact-Please enter experience.';
            $error = true;
            $next   = false;
          } else {
            if(!is_numeric($_POST['experience']) && $_POST['experience'] > 40) {
              $mesg[] = 'expfact-Invalid data';
              $error = true;
              $next   = false;
            } else {
              $years_as_faculty = $_POST['experience'];
            }
          }
        }
        if($_POST['fntoken'] != '') {
          if(!is_numeric($_POST['fntoken'])) {
            $mesg[] = 'funImgFrm-Invalid photo id';
            $error = true;
          } else {
            $fun_photo_id = $_POST['fntoken'];
          }
        } else {
          $fun_photo_id = 0;
        }
        
        if($_POST['fidToken'] != '') {
          $pix_temp = explode('-', $_POST['fidToken']);
          $fid    = $pix_temp[0];
          $width  = $pix_temp[1];
          $height = $pix_temp[2];
          if(!is_numeric($fid)) {
            $mesg[] = 'uploadFrm-Invalid photo id';
            $error = true;
          } 
        } else {
          $fid = 0;
          $width = 0;
          $height = 0;
        }
        $name = $first_name .' '. $last_name;
      }
      
      if($_POST['acctType'] == 'proff') {
        if($user_type == 'alumni') {
          $awards       = json_decode($_POST['awardsAchv']);
          $medium       = json_decode($_POST['mediaCoverage']);
          $education    = json_decode($_POST['eduQualification']);
          $xperiences   = json_decode($_POST['xPriance']);
  
          $count = 0;
          foreach($awards as $award) {
            if($award->name == '') {
              $mesg[] = 'awardName*'.$count.'-Award Please enter name.';
              $error = true;
            } else {
              if(!$this->valid_name($award->name)) {
                $mesg[] = 'awardName*'.$count.'-Please enter a valid name.';
                $error = true;
              }
            }
            if($award->url != '') {
              if(!$this->valid_url($award->url)) {
                $mesg[] = 'awardUrl*'.$count.'-Please enter a valid URL.';
                $error = true;
              }
            }
            if($award->desc != '') {
              if(!$this->valid_text_area_content($award->desc)) {
                $mesg[] = 'awarDesc*'.$count.'-Please select a valid batch number';
                $error = true;
              }
            }
            $count++;
          }
          
          $count = 0;
          foreach($medium as $media) {
            if(trim($media->name) == '') {
              $mesg[] = 'mediaName*'.$count.'-Media Please enter name.';
              $error = true;
            } else {
              if(!$this->valid_name($media->name)) {
                $mesg[] = 'mediaName*'.$count.'-Please enter a valid name.';
                $error = true;
              }
            }
            if($media->url != '') {
              if(!$this->valid_url($media->url)) {
                $mesg[] = 'mediaUrl*'.$count.'-Please enter a valid URL.';
                $error = true;
              }
            }
            if($media->desc != '') {
              if(!$this->valid_text_area_content($media->desc)) {
                $mesg[] = 'mediaDesc*'.$count.'-Please select a valid batch number';
                $error = true;
              }
            }
            $count++;
          }
          $count = 0;
          foreach($education as $edu) {
            if($edu->qualification == '') {
              $mesg[] = 'qualfn*'.$count.'-Please enter qualification.';
              $error = true;
            } else {
              if(!$this->valid_qualification($edu->qualification)) {
                $mesg[] = 'qualfn*'.$count.'-Please select a valid batch number';
                $error = true;
              }
            }
            if($edu->institution == '') {
              $mesg[] = 'instution*'.$count.'-Institution Please enter name.';
              $error = true;
            } else {
              if(!$this->valid_organisation($edu->institution)) {
                $mesg[] = 'instution*'.$count.'-Please select a valid batch number';
                $error = true;
              }
            }
            if($edu->yearPassing == '' || $edu->yearPassing == 'Select') {
              $mesg[] = 'yrPassing*'.$count.'-Please select a year of passing.';
              $error = true;
            } else {
              if(!$this->valid_year(trim($edu->yearPassing))) {
                $mesg[] = 'yrPassing*'.$count.'-Please select a valid year';
                $error = true;
              }
            }
            $count++;
          }
          $count = 0;
          foreach($xperiences as $expr) {
            if($expr->designation == '') {
              $mesg[] = 'design*'.$count.'-Please enter designation.';
              $error = true;
            } else {
              if(!$this->valid_design($expr->designation)) {
                $mesg[] = 'design*'.$count.'-Please select a valid batch number';
                $error = true;
              }
            }
            if($expr->organisation == '' || $expr->organisation == 'Select') {
              $mesg[] = 'organisation*'.$count.'-Please select organisation.';
              $error = true;
            } else {
              if(!$this->valid_organisation($expr->organisation)) {
                $mesg[] = 'organisation*'.$count.'-Please select a valid batch number';
                $error = true;
              }
            }
            if($expr->industry == '' || $expr->industry == 'Select') {
              $mesg[] = 'industry*'.$count.'-Please select industry.';
              $error = true;
            } else {
              if(!$this->valid_organisation($expr->industry)) {
                $mesg[] = 'industry*'.$count.'-Please select a valid batch number';
                $error = true;
              }
            }
            if($expr->country == '' || $expr->country == 'Select') {
              $mesg[] = 'XprienceCntry*'.$count.'-Please select a country.';
              $error = true;
            } else {
              if(!$this->valid_country($expr->country)) {
                $mesg[] = 'XprienceCntry*'.$count.'-Please select a valid batch number';
                $error = true;
              }
            }
            
            if($expr->state == '' || $expr->state == 'Select') {
              $mesg[] = 'XprienceState*'.$count.'-Please select a state.';
              $error = true;
            } else {
              if(!$this->valid_country($expr->state)) {
                $mesg[] = 'XprienceState*'.$count.'-Please select a valid batch number';
                $error = true;
              }
            }
            if($expr->city == '' || $expr->city == 'Select') {
              $mesg[] = 'XprienceCity*'.$count.'-Please select a city.';
              $error = true;
            } else {
              if(!$this->valid_country($expr->city)) {
                $mesg[] = 'XprienceCity*'.$count.'-Please select a valid batch number';
                $error = true;
              }
            }
            if($expr->from == '' || $expr->from == 'Select') {
              $mesg[] = 'empFrom*'.$count.'-Please select from date.';
              $error = true;
            } else {
              if(!$this->valid_date($expr->from)) {
                $mesg[] = 'empFrom*'.$count.'-Please select a valid year';
                $error = true;
              }
            }
            if($expr->to == '' || $expr->to == 'Select') {
              $mesg[] = 'empTo*'.$count.'-Please select to date.';
              $error = true;
            } else {
              if(!$this->valid_date(trim($expr->to))) {
                $mesg[] = 'empTo*'.$count.'-Please select a valid year';
                $error = true;
              }
            }
            if($expr->scope != '') {
              if(!$this->valid_text_area_content($expr->scope)) {
                $mesg[] = 'scope*'.$count.'-Invalid input';
                $error = true;
              }
            }
            $count++;
          }
        }
          
        if($user_type == 'faculty') {        
          if(trim($_POST['totalExp']) == '' || $_POST['totalExp'] == 'Select') {
            $mesg[] = 'totalExp-Total Please enter experience.';
            $error = true;
          } else {
            if(!is_numeric($_POST['totalExp']) && $_POST['totalExp'] > 60) {
              $mesg[] = 'totalExp-Invalid data';
              $error = true;
            } else {
              $year_of_experience = $_POST['totalExp'];
            }
          }
        }     
        if($user_type == 'student') {
          if(trim($_POST['numWrkExp']) == '') {
            $mesg[] = 'yrsExpr-Please enter experience.';
            $error = true;
          } else {
            if(!is_numeric($_POST['numWrkExp']) && $_POST['numWrkExp'] > 40) {
              $mesg[] = 'yrsExpr-Invalid data';
              $error = true;
            } else {
              $year_of_experience = $_POST['numWrkExp'];
            }
          }
          if(trim($_POST['lastSector']) == '') {
            $mesg[] = 'sectorWrkd-Please enter experience.';
            $error = true;
          } else {
            if(!is_numeric($_POST['lastSector']) && $_POST['lastSector'] > 40) {
              $mesg[] = 'sectorWrkd-Invalid data';
              $error = true;
            } else {
              $sector_last_worked = $_POST['lastSector'];
            }
          }
        }
        if($_POST['linkdinUrl'] != '' ) {
          if(!$this->valid_url($_POST['linkdinUrl'])) {
            $mesg[] = 'linkedUrl-Please enter a valid URL.';
            $error = true;
          } else {
            $linkedin_url = $_POST['linkdinUrl'];
          }
        } else {
          $linkedin_url = '';
        }
        if($_POST['cvtoken'] != '') {
          if(!is_numeric($_POST['cvtoken'])) {
            $mesg[] = 'uploadFrm-Invalid id';
            $error = true;
          } else {
            $cv_fid = $_POST['cvtoken'];
          }
        } else {
          $cv_fid = 0;
        }
      }

      
      
      
      
      
      if(!$error) {
        
        $uid_qry = \Drupal::database()->select('users', 'u');
        $uid_qry->addField('u', 'uuid');
        $uid_qry->condition('u.uid', $user_id);
        $uid_qry->range(0, 1);
        $uuid = $uid_qry->execute()->fetchField();
        
        if($_POST['acctType'] == 'basic') {
          $query = \Drupal::database()->update('users_extra');
          $query->fields([
            'name'              => trim($name),
            'gender'            => trim($gender),
            'date_of_birth'     => trim($dob),
            'address_1'         => trim($addr1),
            'address_2'         => trim($addr2),
            'address_3'         => trim($addr3),
            'country_code'      => trim($code),
            'country'           => trim($cur_country),
            'state'             => trim($cur_state),
            'city'              => trim($cur_city),
            'mobile_number'     => trim($code .'-'. $mobile),
            'course_type'       => trim($course_type),
            'course_name'       => trim($course_name),
            'joining_year'      => trim($joining_year),
            'graduating_year'   => trim($graduating_year),
            'batch_number'      => trim($batch_number),
            'roll_number'       => trim($roll_no),
            'area_group'        => trim($area_group),
            'subject_group'     => trim($subject_group),
            'years_as_faculty'  => trim($years_as_faculty),
            
          ]);
          $query->condition('entity_id', $user_id);
          $query->execute();
          
          $query = \Drupal::database()->select('user__user_picture', 'uup');
          $query->addField('uup', 'entity_id');
          $query->condition('uup.entity_id', $user_id);
          $query->range(0, 1);
          $userid = $query->execute()->fetchField();
          if($userid) {
            $query = \Drupal::database()->update('user__user_picture');
            $query->fields(['user_picture_target_id'  => $fid, 'user_picture_width'=> $width, 'user_picture_height' => $height ]);
            $query->condition('entity_id', $userid);
            $query->execute();
          } else {
            $query = \Drupal::database()->insert('user__user_picture');
            $query->fields([
              'bundle',
              'deleted',
              'entity_id',
              'revision_id',
              'langcode',
              'delta',
              'user_picture_target_id',
              'user_picture_alt',
              'user_picture_title',
              'user_picture_width',
              'user_picture_height'
            ]);
            $query->values(['user','0',trim($user_id),trim($user_id),'en','0',$fid,'','',$width,$height]);
            $query->execute();
          }
          
          $findqry = \Drupal::database()->select('file_managed', 'fm');
          $findqry->fields('fm', ['fid','uri','uid']);
          $findqry->condition('fm.uid', $user_id);
          $findqry->condition('fm.uri', 'public://images/users/%', 'LIKE');
          $rows = $findqry->execute()->fetchAllAssoc('fid');
          foreach($rows as $row) {
            if($row->fid != $fid) {
              $delqry = \Drupal::database()->delete('file_managed');
              $delqry->condition('fid', $row->fid);
              $delqry->execute();
            }
          }
          
          if($fun_photo_id) {
            $findqry = \Drupal::database()->select('file_managed', 'fm');
            $findqry->fields('fm', ['fid','uri','uid']);
            $findqry->condition('fm.uid', $user_id);
            $findqry->condition('fm.uri', 'public://images/fun/%', 'LIKE');
            $rows = $findqry->execute()->fetchAllAssoc('fid');
            foreach($rows as $row) {
              if($row->fid != $fun_photo_id) {
                $delqry = \Drupal::database()->delete('file_managed');
                $delqry->condition('fid', $row->fid);
                $delqry->execute();
              }
            }
          }
        }
      
        $query = \Drupal::database()->select('users_profile', 'up');
        $query->addField('up', 'entity_id');
        $query->condition('up.entity_id', $user_id);
        $query->range(0, 1);
        $nid = $query->execute()->fetchField();
        
        if($_POST['acctType'] == 'proff') {
          if($nid) {
            // update
            $query = \Drupal::database()->update('users_profile');
            $query->fields([
              'educational_background'  => $_POST['eduQualification'],
              'professional_background' => $_POST['xPriance'],
              'achievements'            => $_POST['awardsAchv'],
              'media_coverage_forums'   => $_POST['mediaCoverage'],
              'cv_target_id'            => $cv_fid,
              'year_of_experience'      => trim($year_of_experience),
              'sector_last_worked'      => trim($sector_last_worked),
              'linkedin_url'            => trim($linkedin_url)
            ]);
            $query->condition('entity_id', $user_id);
            $query->execute();
          } else {
            // insert$fam_details  = json_decode($_POST['famDetails']);
            $query = \Drupal::database()->insert('users_profile');
            $query->fields([
              'bundle',
              'deleted',
              'entity_id',
              'educational_background',
              'professional_background',
              'achievements',
              'media_coverage_forums',
              'cv_target_id',
              'year_of_experience',
              'sector_last_worked',
              'linkedin_url'          
            ]);
            $query->values([
              'user',
              '0',
              trim($user_id),
              $_POST['eduQualification'],
              $_POST['xPriance'],
              $_POST['awardsAchv'],
              $_POST['mediaCoverage'],
              trim($cv_fid),
              trim($year_of_experience),
              trim($sector_last_worked),
              trim($linkedin_url)
            ]);
            $query->execute();
          }
        } else {
          if($nid) {
            // update
            $query = \Drupal::database()->update('users_profile');
            $query->fields([
              'fun_photo_id'            => $fun_photo_id,
              'nickname'                => trim($nickname),
              'hobbies'                 => trim($hobbies),
              'address_checked'         => $_POST['addrChked'],
              'permanent_address'       => $permanent_addr,
              'educational_background'  => $_POST['eduQualification'],
              'professional_background' => $_POST['xPriance'],
              'achievements'            => $_POST['awardsAchv'],
              'media_coverage_forums'   => $_POST['mediaCoverage'],
              'cv_target_id'            => $cv_fid,
              'year_of_experience'      => trim($year_of_experience),
              'sector_last_worked'      => trim($sector_last_worked),
              'linkedin_url'            => trim($linkedin_url)
            ]);
            $query->condition('entity_id', $user_id);
            $query->execute();
          } else {
            // insert$fam_details  = json_decode($_POST['famDetails']);
            $query = \Drupal::database()->insert('users_profile');
            $query->fields([
              'bundle',
              'deleted',
              'entity_id',
              'fun_photo_id',
              'nickname',
              'hobbies',
              'family_details',
              'permanent_address'
            ]);
            $query->values([
              'user',
              '0',
              trim($user_id),
              trim($fun_photo_id),
              trim($nickname),
              trim($hobbies),
              $_POST['famDetails'],
              $permanent_addr
            ]);
            $query->execute();
          }
        }
        
        $status = 'success';
        $query = \Drupal::database()->select('users_field_data', 'ufd');
        $query->addField('ufd', 'login');
        $query->condition('ufd.uid', $user_id);
        $query->range(0, 1);
        $is_login = $query->execute()->fetchField();
        
        if($is_login == 0) {
          $request_time   = \Drupal::time()->getRequestTime();
          $query = \Drupal::database()->update('users_field_data');
          $query->fields([
            'login'   => $request_time,
            'access'  => $request_time
          ]);
          $query->condition('uid', $user_id);
          $query->execute();
          $user = User::load($user_id);
          user_login_finalize($user);
          $logged_in = \Drupal::currentUser()->isAuthenticated();
          if($logged_in) {
            $status = 'success';
            $red_url = $base_url;
          }
        } else {
          $current_path = \Drupal::request()->getRequestUri();
          $red_url = $base_url.'/my-account/'.$user_id;
        }
      } else {
        //echo 'here';
      }
      
      return new JsonResponse(['mesg' => $mesg, 'status' => $status, 'data' => '', 'tok' => $rnd_num, 'red' => $red_url]);
    }

    public function management_development_program() {
      $error      = false;
      $mesg       = array();
      $status     = 'failed';
      $user_data  = $_POST['userData'];
      $temp_data  = explode('-', $user_data);
      $user_id    = $temp_data[1];
      $user_type  = $temp_data [0];
      
      $actual_token = $_SESSION['mdpApply'];
      $hashed       = new PhpassHashedPassword();
      $salt         = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random       = $_POST['token'];
      $token        = md5($salt.$random);
      $randm        = new CustomPagesController;
      $rnd_num      = $hashed->hash($randm->generate_random_string());
      $fresh_token  = md5($salt.$rnd_num);

      $_SESSION['mdpApply'] = $fresh_token;
      if($actual_token == $token) {
        if($_POST['fname'] == '') {
          $mesg[] = 'fName-Please enter first name.';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['fname'])) {
            $mesg[] = 'fName-Please enter valid first name field.';
            $error = true; 
          } else {
            $first_name = $_POST['fname'];
          }
        }
        if($_POST['lname'] == '') {
          $mesg[] = 'lName-Please enter Last Name.';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['lname'])) {
            $mesg[] = 'lName-Please enter valid last name field.';
            $error = true; 
          } else {
            $last_name = $_POST['lname'];
          }
        }
        if($_POST['batch'] == '' || $_POST['batch'] == 'Select') {
          $mesg[] = 'batchNo-Please select batch number.';
          $error = true;
        } else {
          if(!$this->valid_batch($_POST['batch'])) {
            $mesg[] = 'batchNo-Please select a valid batch number.';
            $error = true; 
          } else {
            $batch_no = $_POST['batch'];
          }
        }
        if($_POST['org'] == '' || $_POST['org'] == 'Select') {
          $mesg[] = 'organisation-Please select organisation.';
          $error = true;
        } else {
          if(!$this->valid_organisation($_POST['org'])) {
            $mesg[] = 'organisation-Please select a valid organisation.';
            $error = true; 
          } else {
            $organisation = $_POST['org'];
          }
        }
        if($_POST['design'] == '') {
          $mesg[] = 'design-Please enter designation.';
          $error = true;
        } else {
          if(!$this->valid_design($_POST['design'])) {
            $mesg[] = 'design-Please select a valid designation.';
            $error = true; 
          } else {
            $designation = $_POST['design'];
          }
        }
        if($_POST['email'] == '') {
          $mesg[] = 'email-Please enter email id.';
          $error = true;
        } else {
          if(!$this->valid_mail($_POST['email'])) {
            $mesg[] = 'email-Please enter valid email id.';
            $error = true; 
          } else {
            $email = $_POST['email'];
          }
        }
        if($_POST['cntry'] == '' || $_POST['cntry'] == 'Select') {
          $mesg[] = 'country-Please select a country.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['cntry'])) {
            $mesg[] = 'country-Please select a valid country.';
            $error = true; 
          } else {
            $country = $_POST['cntry'];
          }
        }
        if($_POST['state'] == '' || $_POST['state'] == 'Select') {
          $mesg[] = 'states-Please select a state.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['state'])) {
            $mesg[] = 'states-Please select a valid state.';
            $error = true; 
          } else {
            $state = $_POST['state'];
          }
        }
        if($_POST['city'] == '' || $_POST['city'] == 'Select' || $_POST['city'] == 'Select') {
          $mesg[] = 'cityList-Please select a city.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['city'])) {
            $mesg[] = 'cityList-Please select a valid city.';
            $error = true; 
          } else {
            $city = $_POST['city'];
          }
        }
        if($_POST['code'] == '') {
          $mesg[] = 'telCode-Please enter country code.';
          $error = true;
        } else {
          if(!$this->country_code($_POST['code'])) {
            $mesg[] = 'telCode-Please select a valid country code.';
            $error = true; 
          } else {
            $code = $_POST['code'];
          }
        }
        if($_POST['mobile'] == '') {
          $mesg[] = 'mobileNo-Please enter mobile number.';
          $error = true;
        } else {
          if(!is_numeric($_POST['mobile'])) {
            $mesg[] = 'mobileNo-Please enter valid mobile number.';
            $error = true; 
          } else {
            $mobile = $_POST['mobile'];
          }
        }
        
        if($_POST['mdpName'] == '') {
          $mesg[] = 'mdpName-MDP Please enter name..';
          $error = true; 
        } else {
          if(!$this->valid_name($_POST['mdpName'])) {
            $mesg[] = 'mdpName-Please enter a valid name.';
            $error = true; 
          } else {
            $mdp_name = $_POST['mdpName'];
          }
        }
        if($_POST['startDate'] == '' || $_POST['startDate'] == 'MDP Start Date'){
          $mesg[] = 'startDate-Please enter a valid start date.';
          $error = true;
        } else {
          if(!$this->valid_date($_POST['startDate'])) {
            $mesg[] = 'startDate-Please enter a valid date.';
            $error = true;
          } else {
            $temp_arr   = str_replace('/', '-', $_POST['startDate']);
            $start_date_timestamp = strtotime($temp_arr);
            $start_date = $_POST['startDate'];
          }
        }
        
        if($_POST['enDate'] == '') {
          $mesg[] = 'enDate-Please enter a valid end date.';
          $error = true;
        } else {
          if(!$this->valid_date($_POST['enDate'])) {
            $mesg[] = 'enDate-Please enter a valid date.';
            $error = true;
          } else {
            $temp_dep   = str_ireplace('/', '-', $_POST['enDate']);
            $end_date_timestamp = strtotime($temp_dep);
            if($start_date_timestamp > $end_date_timestamp) {
              $mesg[] = 'enDate-End date cannot be behind Start Date.';
              $error = true;
            } else {
              $end_date = $_POST['enDate'];
            }
          }
        }
        if($_POST['mdpQry'] == '') {
          $mesg[] = 'mdpQry-Please enter the query.';
          $error = true; 
        } else {
          if(!$this->valid_text_area_content($_POST['mdpQry'])) {
            $mesg[] = 'mdpQry-Please enter a valid text.';
            $error = true; 
          } else {
            $media_query = $_POST['mdpQry'];
          }
        }

        $name = $first_name .' '. $lastname;
        
        if(!$error) {
          $arrival_time = $arrival_hour.':'.$arrival_min;
          $depart_time = $depart_hour .':'. $depart_min;
          $query = \Drupal::database()->insert('mdp_data');
          $query->fields(['uid','user_type','name','batch_number','organisation','designation','email','country', 'state','city','country_code','mobile_number','mdp_name','start_date', 'end_date','mdp_query']);
          $query->values([$user_id, $user_type, $name, $batch_no, $organisation, $designation, $email, $country, $state, $city, $code, $mobile, $mdp_name,$start_date,$end_date, $media_query]);
          $query->execute();
          $mesg = 'Thank you for your application. Your request has been sent to IRMA. They will revert shortly.';
          $site_mail = \Drupal::config('system.site')->get('mail');
          $mail_data = '<table>
                          <tr><td>Name</td><td>:</td><td>'.$name.'</td></tr>
                          <tr><td>Batch Number</td><td>:</td><td>'.$batch_no.'</td></tr>
                          <tr><td>Organisation</td><td>:</td><td>'.$organisation.'</td></tr>
                          <tr><td>Designation</td><td>:</td><td>'.$designation.'</td></tr>
                          <tr><td>email</td><td>:</td><td>'.$email.'</td></tr>
                          <tr><td>Country</td><td>:</td><td>'.$country.'</td></tr>
                          <tr><td>State</td><td>:</td><td>'.$state.'</td></tr>
                          <tr><td>City</td><td>:</td><td>'.$city.'</td></tr>
                          <tr><td>Mobile Number</td><td>:</td><td>'.$mobile.'</td></tr>
                          <tr><td>MDP Name</td><td>:</td><td>'.$mdp_name.'</td></tr>
                          <tr><td>Start Date</td><td>:</td><td>'.$start_date.'</td></tr>
                          <tr><td>End Date</td><td>:</td><td>'.$end_date.'</td></tr>
                          <tr><td>MDP Query</td><td>:</td><td>'.$media_query.'</td></tr>
                        </table>';
          
          $body = 'Thank you for your application. Your request has been sent to IRMA. They will revert shortly.<br>' . $mail_data ;
          
          $body = $this->build_html($first_name, $body);
          MailController::sendCustomMail("",$email,"Alumni Website - MDP Request",$body); //Mail to user
          $status = 'success';
        }
      } else {
        $mesg   = 'Token mismatch... Please try again.';
        $status = 'tErr';
      }
      return new JsonResponse(['msg' => $mesg, 'status' => $status, 'tok' => $rnd_num]);
    } 
    public function campus_infrastructure_etdc() {
      $error      = false;
      $mesg       = array();
      $status     = 'failed';
      $user_data  = $_POST['userData'];
      $temp_data  = explode('-', $user_data);
      $user_id    = $temp_data[1];
      $user_type  = $temp_data [0];
      
      $actual_token = $_SESSION['etdcApp'];
      $hashed       = new PhpassHashedPassword();
      $salt         = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random       = $_POST['token'];
      $token        = md5($salt.$random);
      $randm        = new CustomPagesController;
      $rnd_num      = $hashed->hash($randm->generate_random_string());
      $fresh_token  = md5($salt.$rnd_num);

      $_SESSION['etdcApp'] = $fresh_token;
      if($actual_token == $token) {
        if($_POST['fname'] == '') {
          $mesg[] = 'fName-Please enter first name.';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['fname'])) {
            $mesg[] = 'fName-Please enter valid first name field.';
            $error = true; 
          } else {
            $first_name = $_POST['fname'];
          }
        }
        if($_POST['lname'] == '') {
          $mesg[] = 'lName-Please enter Last Name.';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['lname'])) {
            $mesg[] = 'lName-Please enter valid last name field.';
            $error = true; 
          } else {
            $last_name = $_POST['lname'];
          }
        }
        if($_POST['batch'] == '' || $_POST['batch'] == 'Select') {
          $mesg[] = 'batchNo-Please select batch number.';
          $error = true;
        } else {
          if(!$this->valid_batch($_POST['batch'])) {
            $mesg[] = 'batchNo-Please select a valid batch number.';
            $error = true; 
          } else {
            $batch_no = $_POST['batch'];
          }
        }
        if($_POST['org'] == '' || $_POST['org'] == 'Select') {
          $mesg[] = 'organisation-Please select organisation..';
          $error = true;
        } else {
          if(!$this->valid_organisation($_POST['org'])) {
            $mesg[] = 'organisation-Please select a valid organisation.';
            $error = true; 
          } else {
            $organisation = $_POST['org'];
          }
        }
        if($_POST['design'] == '') {
          $mesg[] = 'design-Please enter designation..';
          $error = true;
        } else {
          if(!$this->valid_design($_POST['design'])) {
            $mesg[] = 'design-Please select a valid designation.';
            $error = true; 
          } else {
            $designation = $_POST['design'];
          }
        }
        if($_POST['email'] == '') {
          $mesg[] = 'email-Please enter email id.';
          $error = true;
        } else {
          if(!$this->valid_mail($_POST['email'])) {
            $mesg[] = 'email-Please enter valid email id.';
            $error = true; 
          } else {
            $email = $_POST['email'];
          }
        }
        if($_POST['cntry'] == '' || $_POST['cntry']=='Select') {
          $mesg[] = 'country-Please select a country.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['cntry'])) {
            $mesg[] = 'country-Please select a valid country.';
            $error = true; 
          } else {
            $country = $_POST['cntry'];
          }
        }
        if($_POST['state'] == '' || $_POST['state'] == 'Select') {
          $mesg[] = 'states-Please select a state.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['state'])) {
            $mesg[] = 'states-Please select a valid state.';
            $error = true; 
          } else {
            $state = $_POST['state'];
          }
        }
        if($_POST['city'] == '' || $_POST['city']=='Select') {
          $mesg[] = 'cityList-Please select a city.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['city'])) {
            $mesg[] = 'cityList-Please select a valid city.';
            $error = true; 
          } else {
            $city = $_POST['city'];
          }
        }
        if($_POST['code'] == '') {
          $mesg[] = 'telCode-Please enter country code.';
          $error = true;
        } else {
          if(!$this->country_code($_POST['code'])) {
            $mesg[] = 'telCode-Please select a valid country code.';
            $error = true; 
          } else {
            $code = $_POST['code'];
          }
        }
        if($_POST['mobile'] == '') {
          $mesg[] = 'mobileNo-Please enter mobile number.';
          $error = true;
        } else {
          if(!is_numeric($_POST['mobile'])) {
            $mesg[] = 'mobileNo-Please enter valid mobile number.';
            $error = true; 
          } else {
            $mobile = $_POST['mobile'];
          }
        }
        
        if($_POST['arrDate'] == '') {
          $mesg[] = 'arrDate-Please enter arrival date.';
          $error = true;
        } else {
          if(!$this->valid_date($_POST['arrDate'])) {
            $mesg[] = 'arrDate-Please enter a valid date.';
            $error = true;
          } else {
            $temp_arr   = str_replace('/', '-', $_POST['arrDate']);
            $arr_timestamp = strtotime($temp_arr);
            $arrival_date = $_POST['arrDate'];
          }
        }
        if($_POST['depDate'] == '') {
          $mesg[] = 'depDate-Please enter departure date.';
          $error = true;
        } else {
          if(!$this->valid_date($_POST['depDate'])) {
            $mesg[] = 'depDate-Please enter a valid date.';
            $error = true;
          } else {
            $temp_dep   = str_ireplace('/', '-', $_POST['depDate']);
            $dep_timestamp = strtotime($temp_dep);
            if($arr_timestamp > $dep_timestamp) {
              $mesg[] = 'depDate-Departure date cannot be behind Arrival Date.';
              $error = true;
            } else {
              $depart_date = $_POST['depDate'];
            }
          }
        }
        if($_POST['arrHr'] == '' || $_POST['arrHr'] == 'Select' || $_POST['arrHr'] == 'Select') {
          $mesg[] = 'arrHr-Please enter arrival time.';
          $error = true;
        } else {
          if(is_numeric($_POST['arrHr']) && ($_POST['arrHr'] >= 0 && $_POST['arrHr'] <= 23)) {
            $arrival_hour = $_POST['arrHr'];
          } else {
            $mesg[] = 'arrHr-Please enter a valid time.';
            $error = true;
          }
        }
        if($_POST['arrMin'] == '' || $_POST['arrMin'] == 'Select' || $_POST['arrMin'] == 'Select') {
          $mesg[] = 'arrMin-Please enter arrival time.';
          $error = true;
        } else {
          if(is_numeric($_POST['arrMin']) && ($_POST['arrMin'] >= 0 && $_POST['arrMin'] <= 59)) {
            $arrival_min = $_POST['arrMin'];
          } else {
            $mesg[] = 'arrMin-Please enter a valid time.';
            $error = true;
          }
        }
        if($_POST['depHr'] == '' || $_POST['depHr'] == 'Select' || $_POST['depHr'] == 'Select') {
          $mesg[] = 'depHr-Please enter a departure time.';
          $error = true;
        } else {
          if(is_numeric($_POST['depHr']) && ($_POST['depHr'] >= 0 && $_POST['depHr'] <= 23)) {
            $depart_hour = $_POST['depHr'];
          } else {
            $mesg[] = 'depHr-Please enter a valid time.';
            $error = true;
          }
        }
        if($_POST['depMin'] == '' || $_POST['depMin'] == 'Select' || $_POST['depMin'] == 'Select') {
          $mesg[] = 'depMin-Please enter a departure time.';
          $error = true;
        } else {
          if(is_numeric($_POST['depMin']) && ($_POST['depMin'] >= 0 && $_POST['depMin'] <= 59)) {
            $depart_min = $_POST['depMin'];
          } else {
            $mesg[] = 'depMin-Please enter a valid time.';
            $error = true;
          }
        }
        if(!isset($_POST['purpVisit'])) {
          $mesg[] = 'purpVisit-Please enter purpose of visit.';
          $error = true;
        } else {
          if($_POST['purpVisit'] == 'Personal' || $_POST['purpVisit'] == 'Official') {
            $purpose_visit = $_POST['purpVisit'];
            if($purpose_visit == 'Official') {
              if($_POST['purpose'] == '') {
                $mesg[] = 'purpose-Please enter purpose.';
                $error = true;
              } else {
                if(!preg_match("/^([a-zA-Z]+\s?)*$/", $_POST['purpose'])) {
                  $mesg[] = 'purpose-Please select a valid batch number.';
                  $error = true;
                } else {
                  $purpose = $_POST['purpose'];
                }
              }
            } else {
              $purpose = '';
            }
          } 
        }
        if($_POST['noPers'] == '' || $_POST['noPers'] == 'Select') {
          $mesg[] = 'numPers-No of Persons is required.';
          $error = true;
        } else {
          if(is_numeric($_POST['noPers']) && ($_POST['noPers'] >= 0 && $_POST['noPers'] <= 10)) {
            $num_persons = $_POST['noPers'];
          } else {
            $mesg[] = 'numPers-Please select a valid batch number.';
            $error = true;
          }
        }
        if($_POST['numRooms'] == '' || $_POST['numRooms'] == 'Select') {
          $mesg[] = 'numRooms-No of rooms is required.';
          $error = true;
        } else {
          if(is_numeric($_POST['numRooms']) && ($_POST['numRooms'] >= 0 && $_POST['numRooms'] <= 10)) {
            $num_rooms = $_POST['numRooms'];
          } else {
            $mesg[] = 'numRooms-Please select a valid batch number.';
            $error = true;
          }
        }
        if(isset($_POST['incFood'])) {
          if($_POST['incFood'] == 'yes' || $_POST['incFood'] == 'no') {
            $include_food = $_POST['incFood'];
          } else {
            $mesg[] = 'incFood-Please select a valid batch number.';
            $error = true;
          }
        } else {
          $mesg[] = 'incFood-Food To Be Included is required.';
          $error = true;
        }
        
        if(!isset($_POST['prefFood'])) {
          $mesg[] = 'prefFood-Please enter food preference.';
          $error = true;
        } else {
          if($_POST['prefFood'] == 'veg' || $_POST['prefFood'] == 'non-veg') {
            $food_preference = $_POST['prefFood'];
          } else {
            $mesg[] = 'prefFood-Please select a valid batch number.';
            $error = true;
          }
        }
    
        $name = $first_name .' '. $lastname;
        
        if(!$error) {
          $arrival_time = $arrival_hour.':'.$arrival_min;
          $depart_time = $depart_hour .':'. $depart_min;
          $query = \Drupal::database()->insert('campus_infra_etdc');
          $query->fields(['uid','user_type','name','batch_number','organisation','designation','email','country','state','city','country_code','mobile_number','arrival_date','arrival_time','departure_date','departure_time','purpose_of_visit','purpose','no_persons','no_rooms','include_food','food_preference']);
          $query->values([$user_id, $user_type, $name, $batch_no, $organisation, $designation, $email, $country, $state, $city, $code, $mobile, $arrival_date,$arrival_time,$depart_date, $depart_time,$purpose_visit, $purpose, $num_persons, $num_rooms, $include_food, $food_preference]);
          $query->execute();
          $mesg = 'Your ETDC room booking request has been sent to IRMA. They will revert shortly. Kindly contact iaaec@irma.ac.in / iao@irma.ac.in, in case of any issues';
          $site_mail = \Drupal::config('system.site')->get('mail');
          $mail_data = '<table>
                          <tr><td>Name</td><td>:</td><td>'.$name.'</td></tr>
                          <tr><td>Batch Number</td><td>:</td><td>'.$batch_no.'</td></tr>
                          <tr><td>Organisation</td><td>:</td><td>'.$organisation.'</td></tr>
                          <tr><td>Designation</td><td>:</td><td>'.$designation.'</td></tr>
                          <tr><td>email</td><td>:</td><td>'.$email.'</td></tr>
                          <tr><td>Country</td><td>:</td><td>'.$country.'</td></tr>
                          <tr><td>State</td><td>:</td><td>'.$state.'</td></tr>
                          <tr><td>City</td><td>:</td><td>'.$city.'</td></tr>
                          <tr><td>Mobile Number</td><td>:</td><td>'.$mobile.'</td></tr>
                          <tr><td>Arrival Date</td><td>:</td><td>'.$arrival_date.' - '.$arrival_time.'</td></tr>
                          <tr><td>Departure Date</td><td>:</td><td>'.$depart_date.' - '.$depart_time.'</td></tr>
                          <tr><td>Purpose of visit</td><td>:</td><td>'.$purpose_visit.'</td></tr>
                          <tr><td>Purpose</td><td>:</td><td>'.$purpose.'</td></tr>
                          <tr><td>No. of persons</td><td>:</td><td>'.$num_persons.'</td></tr>
                          <tr><td>No.of rooms</td><td>:</td><td>'.$num_rooms.'</td></tr>
                          <tr><td>Food included</td><td>:</td><td>'.$include_food.'</td></tr>
                          <tr><td>Food preference</td><td>:</td><td>'.$food_preference.'</td></tr>
                        </table>';
          
          $body = 'Your ETDC room booking request has been sent to IRMA. They will revert shortly. Kindly contact iaaec@irma.ac.in / iao@irma.ac.in , in case of any issues.<br>' . $mail_data ;
          $body = $this->build_html($first_name, $body);
          MailController::sendCustomMail("",$email,"Alumni Website - ETDC Accomodation Request",$body); //Mail to user
          $status = 'success';
        }
      } else {
        $mesg   = 'Token mismatch... Please try again.';
        $status = 'tErr';
      }
      return new JsonResponse(['msg' => $mesg, 'status' => $status, 'tok' => $rnd_num]);
    } 
    public function collaborate_project_apply() {
      $error      = false;
      $mesg       = array();
      $status     = 'failed';
      $user_data  = $_POST['usr_data'];
      $temp_data  = explode('-', $user_data);
      $user_id    = $temp_data[1];
      $user_type  = $temp_data [0];
      
      $actual_token = $_SESSION['projApp'];
      $hashed       = new PhpassHashedPassword();
      $salt         = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random       = $_POST['token'];
      $token        = md5($salt.$random);
      $randm        = new CustomPagesController;
      $rnd_num      = $hashed->hash($randm->generate_random_string());
      $fresh_token  = md5($salt.$rnd_num);

      $_SESSION['projApp'] = $fresh_token;
      if($actual_token == $token) {
        if($_POST['fname'] == '') {
          $mesg[] = 'fName-Please enter first name.';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['fname'])) {
            $mesg[] = 'fName-Please enter valid first name field.';
            $error = true; 
          } else {
            $first_name = $_POST['fname'];
          }
        }
        if($_POST['lname'] == '') {
          $mesg[] = 'lName-Please enter Last Name.';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['lname'])) {
            $mesg[] = 'lName-Please enter valid last name field.';
            $error = true; 
          } else {
            $last_name = $_POST['lname'];
          }
        }
        if($_POST['batch'] == '' || $_POST['batch'] == 'Select') {
          $mesg[] = 'batchNo-Please select batch number.';
          $error = true;
        } else {
          if(!$this->valid_batch($_POST['batch'])) {
            $mesg[] = 'batchNo-Please select a valid batch number.';
            $error = true; 
          } else {
            $batch_no = $_POST['batch'];
          }
        }
        if($_POST['org'] == '' || $_POST['org'] == 'Select') {
          $mesg[] = 'organisation-Please select organisation..';
          $error = true;
        } else {
          if(!$this->valid_organisation($_POST['org'])) {
            $mesg[] = 'organisation-Please select a valid organisation.';
            $error = true; 
          } else {
            $organisation = $_POST['org'];
          }
        }
        if($_POST['design'] == '') {
          $mesg[] = 'design-Please enter designation..';
          $error = true;
        } else {
          if(!$this->valid_design($_POST['design'])) {
            $mesg[] = 'design-Please select a valid designation.';
            $error = true; 
          } else {
            $designation = $_POST['design'];
          }
        }
        if($_POST['email'] == '') {
          $mesg[] = 'email-Please enter email id.';
          $error = true;
        } else {
          if(!$this->valid_mail($_POST['email'])) {
            $mesg[] = 'email-Please enter valid email id.';
            $error = true; 
          } else {
            $email = $_POST['email'];
          }
        }
        if($_POST['cntry'] == '' || $_POST['cntry'] == 'Select') {
          $mesg[] = 'country-Please select a country.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['cntry'])) {
            $mesg[] = 'country-Please select a valid country.';
            $error = true; 
          } else {
            $country = $_POST['cntry'];
          }
        }
        if($_POST['state'] == '' || $_POST['state'] == 'Select') {
          $mesg[] = 'states-Please select a state.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['state'])) {
            $mesg[] = 'states-Please select a valid state.';
            $error = true; 
          } else {
            $state = $_POST['state'];
          }
        }
        if($_POST['city'] == '' || $_POST['city'] == 'Select') {
          $mesg[] = 'cityList-Please select a city.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['city'])) {
            $mesg[] = 'cityList-Please select a valid city.';
            $error = true; 
          } else {
            $city = $_POST['city'];
          }
        }
        if($_POST['code'] == '') {
          $mesg[] = 'telCode-Please enter country code.';
          $error = true;
        } else {
          if(!$this->country_code($_POST['code'])) {
            $mesg[] = 'telCode-Please select a valid country code.';
            $error = true; 
          } else {
            $code = $_POST['code'];
          }
        }
        if($_POST['mobile'] == '') {
          $mesg[] = 'mobileNo-Please enter mobile number.';
          $error = true;
        } else {
          if(!is_numeric($_POST['mobile'])) {
            $mesg[] = 'mobileNo-Please enter valid mobile number.';
            $error = true; 
          } else {
            $mobile = $_POST['mobile'];
          }
        }
        if($_POST['dmName'] == '') {
          $mesg[] = 'dmName-Please enter name..';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['dmName'])) {
            $mesg[] = 'dmName-Please enter a valid name.';
            $error = true; 
          } else {
            $decision_maker_name = $_POST['dmName'];
          }
        }
        if($_POST['dmEmail'] == '') {
          $mesg[] = 'dmEmail-Please enter email id.';
          $error = true;
        } else {
          if(!$this->valid_mail($_POST['dmEmail'])) {
            $mesg[] = 'dmEmail-Please enter valid email id.';
            $error = true; 
          } else {
            $decision_maker_mail = $_POST['dmEmail'];
          }
        }
        if($_POST['dmContactNo'] == '') {
          $mesg[] = 'dmContactNo-Please enter mobile number.';
          $error = true;
        } else {
          if(!is_numeric($_POST['dmContactNo'])) {
            $mesg[] = 'dmContactNo-Please enter valid mobile number.';
            $error = true; 
          } else if(!$this->valid_mobile($_POST['dmContactNo'])) {
            $mesg[] = 'dmContactNo-Please enter valid mobile number.';
            $error = true; 
          } else {
            $decision_member_mobile = $_POST['dmContactNo'];
          }
        }
        if($_POST['projBrief'] == '') {
          $mesg[] = 'projBrief-Project Brief is required.';
          $error = true;
        } else {
          if(!$this->valid_text_area_content($_POST['projBrief'])) {
            $mesg[] = 'projBrief-Invalid characters in text.';
            $error = true; 
          } else {
            $project_brief = $_POST['projBrief'];
          }
        }
        if($_POST['subjGrp'] == '' || $_POST['subjGrp'] == 'Select') {
          $mesg[] = 'subjGrp-Please enter Concerned IRMA Subject Group..';
          $error = true;
        } else {
          if(!$this->valid_text_area_content($_POST['subjGrp'])) {
            $mesg[] = 'subjGrp-Invalid Concerned IRMA Subject Group.';
            $error = true; 
          } else {
            $subject_group = $_POST['subjGrp'];
          }
        }
        if($_POST['facultyContact'] != '') {
          if(!$this->valid_name($_POST['facultyContact'])) {
            $mesg[] = 'facultyContact-Invalid Faculty Contact.';
            $error = true; 
          } else {
            $faculty_contact = $_POST['facultyContact'];
          }
        } else {
          $faculty_contact = '';
        }
        if($_POST['projStart'] == '') {
          $mesg[] = 'projStart-Please enter a date.';
          $error = true;
        } else {
          if(!$this->valid_date($_POST['projStart'])) {
            $mesg[] = 'projStart-Please enter a valid date.';
            $error = true; 
          } else {
            $project_start_date = $_POST['projStart'];
          }
        }
        if($_POST['projEnd'] == '') {
          $mesg[] = 'projEnd-Please enter a date.';
          $error = true;
        } else {
          if(!$this->valid_date($_POST['projEnd'])) {
            $mesg[] = 'projEnd-Please enter a valid date.';
            $error = true; 
          } else {
            $project_end_date = $_POST['projEnd'];
          }
        }
        $name = $first_name .' '. $lastname;
        
        if(!$error) {
          $query = \Drupal::database()->insert('project_apply');
          $query->fields(['uid','user_type','name','batch_number','organisation','designation','email','country','state','city','country_code','mobile_number','decision_maker_name','decision_maker_email','decision_maker_mobile','project_brief','concerned_subject_grp','concerned_faculty_contact','project_start_date','project_end_date']);
          $query->values([$user_id, $user_type, $name, $batch_no, $organisation, $designation, $email, $country, $state, $city, $code, $mobile, $decision_maker_name,$decision_maker_mail, $decision_member_mobile, $project_brief, $subject_group, $faculty_contact, $project_start_date, $project_end_date]);
          $query->execute();
          $mesg = 'Thank you. Your request has been sent to IRMA. They will revert shortly.';
          $site_mail = \Drupal::config('system.site')->get('mail');
          $mail_data = '<table>
                          <tr><td>Name</td><td>:</td><td>'.$name.'</td></tr>
                          <tr><td>Batch Number</td><td>:</td><td>'.$batch_no.'</td></tr>
                          <tr><td>Organisation</td><td>:</td><td>'.$organisation.'</td></tr>
                          <tr><td>Designation</td><td>:</td><td>'.$designation.'</td></tr>
                          <tr><td>email</td><td>:</td><td>'.$email.'</td></tr>
                          <tr><td>Country</td><td>:</td><td>'.$country.'</td></tr>
                          <tr><td>State</td><td>:</td><td>'.$state.'</td></tr>
                          <tr><td>City</td><td>:</td><td>'.$city.'</td></tr>
                          <tr><td>Mobile Number</td><td>:</td><td>'.$mobile.'</td></tr>
                          <tr><td>Decision maker\'s name</td><td>:</td><td>'.$decision_maker_name.'</td></tr>
                          <tr><td>Decision maker\'s mail</td><td>:</td><td>'.$decision_maker_mail.'</td></tr>
                          <tr><td>Decision maker\'s mobile</td><td>:</td><td>'.$decision_member_mobile.'</td></tr>
                          <tr><td>Project brief</td><td>:</td><td>'.$project_brief.'</td></tr>
                          <tr><td>Subject group</td><td>:</td><td>'.$subject_group.'</td></tr>
                          <tr><td>Faculty Contact</td><td>:</td><td>'.$faculty_contact.'</td></tr>
                          <tr><td>Project start date</td><td>:</td><td>'.$project_start_date.'</td></tr>
                          <tr><td>Project start date</td><td>:</td><td>'.$project_end_date.'</td></tr>
                        </table>';
          $body = 'Thank you. Your request has been sent to IRMA. They will revert shortly.<br>' . $mail_data ;
          $body = $this->build_html($first_name, $body);
          MailController::sendCustomMail("",$email,"Alumni Website - Collaborate On A Project Request",$body); //Mail to user
          $status = 'success';
        }
      } else {
        $mesg   = 'Token mismatch... Please try again.';
        $status = 'tErr';
      }
      return new JsonResponse(['msg' => $mesg, 'status' => $status, 'tok' => $rnd_num]);
    }
    
    public function classroom_session_apply() {
      $error      = false;
      $mesg       = array();
      $status     = 'failed';
      $user_data  = $_POST['usr_data'];
      $temp_data  = explode('-', $user_data);
      $user_id    = $temp_data[1];
      $user_type  = $temp_data [0];
      
      $actual_token = $_SESSION['sessApp'];
      $hashed       = new PhpassHashedPassword();
      $salt         = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random       = $_POST['token'];
      $token        = md5($salt.$random);
      $randm        = new CustomPagesController;
      $rnd_num      = $hashed->hash($randm->generate_random_string());
      $fresh_token  = md5($salt.$rnd_num);

      $_SESSION['sessApp'] = $fresh_token;
      if($actual_token == $token) {
        if($_POST['fname'] == '') {
          $mesg[] = 'fName-Please enter first name.';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['fname'])) {
            $mesg[] = 'fName-Please enter valid first name field.';
            $error = true; 
          } else {
            $first_name = $_POST['fname'];
          }
        }
        if($_POST['lname'] == '') {
          $mesg[] = 'lName-Please enter Last Name.';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['lname'])) {
            $mesg[] = 'lName-Please enter valid last name field.';
            $error = true; 
          } else {
            $last_name = $_POST['lname'];
          }
        }
        if($_POST['batch'] == '' || $_POST['batch'] == 'Select') {
          $mesg[] = 'batchNo-Please select batch number.';
          $error = true;
        } else {
          if(!$this->valid_batch($_POST['batch'])) {
            $mesg[] = 'batchNo-Please select a valid batch number.';
            $error = true; 
          } else {
            $batch_no = $_POST['batch'];
          }
        }
        if($_POST['org'] == '' || $_POST['org'] == 'Select') {
          $mesg[] = 'organisation-Please select organisation..';
          $error = true;
        } else {
          if(!$this->valid_organisation($_POST['org'])) {
            $mesg[] = 'organisation-Please select a valid organisation.';
            $error = true; 
          } else {
            $organisation = $_POST['org'];
          }
        }
        if($_POST['design'] == '') {
          $mesg[] = 'design-Please enter designation..';
          $error = true;
        } else {
          if(!$this->valid_design($_POST['design'])) {
            $mesg[] = 'design-Please select a valid designation.';
            $error = true; 
          } else {
            $designation = $_POST['design'];
          }
        }
        if($_POST['email'] == '') {
          $mesg[] = 'email-Please enter email id.';
          $error = true;
        } else {
          if(!$this->valid_mail($_POST['email'])) {
            $mesg[] = 'email-Please enter valid email id.';
            $error = true; 
          } else {
            $email = $_POST['email'];
          }
        }
        if($_POST['cntry'] == '' || $_POST['cntry'] == 'Select') {
          $mesg[] = 'country-Please select a country.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['cntry'])) {
            $mesg[] = 'country-Please select a valid country.';
            $error = true; 
          } else {
            $country = $_POST['cntry'];
          }
        }
        if($_POST['state'] == '' || $_POST['state'] == 'Select') {
          $mesg[] = 'states-Please select a state.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['state'])) {
            $mesg[] = 'states-Please select a valid state.';
            $error = true; 
          } else {
            $state = $_POST['state'];
          }
        }
        if($_POST['city'] == '' || $_POST['city'] == 'Select') {
          $mesg[] = 'cityList-Please select a city.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['city'])) {
            $mesg[] = 'cityList-Please select a valid city.';
            $error = true; 
          } else {
            $city = $_POST['city'];
          }
        }
        if($_POST['code'] == '') {
          $mesg[] = 'telCode-Please enter country code.';
          $error = true;
        } else {
          if(!$this->country_code($_POST['code'])) {
            $mesg[] = 'telCode-Please select a valid country code.';
            $error = true; 
          } else {
            $code = $_POST['code'];
          }
        }
        if($_POST['mobile'] == '') {
          $mesg[] = 'mobileNo-Please enter mobile number.';
          $error = true;
        } else {
          if(!is_numeric($_POST['mobile'])) {
            $mesg[] = 'mobileNo-Please enter valid mobile number.';
            $error = true; 
          } else {
            $mobile = $_POST['mobile'];
          }
        }
        
        if($_POST['sessBrief'] == '') {
          $mesg[] = 'sessBrief-Session Brief is required.';
          $error = true;
        } else {
          if(!$this->valid_text_area_content($_POST['sessBrief'])) {
            $mesg[] = 'sessBrief-Invalid characters in text.';
            $error = true; 
          } else {
            $session_brief = $_POST['sessBrief'];
          }
        }
        if($_POST['subjGrp'] == '' || $_POST['subjGrp'] == 'Select') {
          $mesg[] = 'subjGrp-Please enter Concerned IRMA Subject Group..';
          $error = true;
        } else {
          if(!$this->valid_text_area_content($_POST['subjGrp'])) {
            $mesg[] = 'subjGrp-Invalid Concerned IRMA Subject Group.';
            $error = true; 
          } else {
            $subject_group = $_POST['subjGrp'];
          }
        }
        if($_POST['sessHrs'] == '') {
          $mesg[] = 'sessHrs-Session Hours is required.';
          $error = true;
        } else {
          if(!is_numeric($_POST['sessHrs']) || $_POST['sessHrs'] > 100) {
            $mesg[] = 'sessHrs-Invalid Session Hours.';
            $error = true; 
          } else {
            $session_hours = $_POST['sessHrs'];
          }
        }

        $name = $first_name .' '. $lastname;
        
        if(!$error) {
          $query = \Drupal::database()->insert('classroom_sessions');
          $query->fields(['uid','user_type','name','batch_number','organisation','designation','email','country','state', 'city','country_code','mobile_number','session_brief','concerned_subject_grp', 'hours_required']);
          $query->values([$user_id, $user_type, $name, $batch_no, $organisation, $designation, $email, $country, $state, $city, $code, $mobile, $session_brief, $subject_group, $session_hours]);
          $query->execute();
          $mesg = 'Thank you. Your request has been sent to IRMA. They will revert shortly.';
          $site_mail = \Drupal::config('system.site')->get('mail');
          $mail_data = '<table>
                          <tr><td>Name</td><td>:</td><td>'.$name.'</td></tr>
                          <tr><td>Batch Number</td><td>:</td><td>'.$batch_no.'</td></tr>
                          <tr><td>Organisation</td><td>:</td><td>'.$organisation.'</td></tr>
                          <tr><td>Designation</td><td>:</td><td>'.$designation.'</td></tr>
                          <tr><td>email</td><td>:</td><td>'.$email.'</td></tr>
                          <tr><td>Country</td><td>:</td><td>'.$country.'</td></tr>
                          <tr><td>State</td><td>:</td><td>'.$state.'</td></tr>
                          <tr><td>City</td><td>:</td><td>'.$city.'</td></tr>
                          <tr><td>Mobile Number</td><td>:</td><td>'.$mobile.'</td></tr>
                          <tr><td>Session brief</td><td>:</td><td>'.$session_brief.'</td></tr>
                          <tr><td>Subject Group</td><td>:</td><td>'.$subject_group.'</td></tr>
                          <tr><td>Session hours</td><td>:</td><td>'.$session_hours.'</td></tr>
                        </table>';

          $body = 'Thank you. Your request has been sent to IRMA. They will revert shortly.<br>' . $mail_data ;
          $body = $this->build_html($first_name, $body);
          MailController::sendCustomMail("",$email,"Alumni Website - Take A Classroom Session Request",$body); //Mail to user
          $status = 'success';
        }
      } else {
        $mesg   = 'Token mismatch... Please try again.';
        $status = 'tErr';
      }
      return new JsonResponse(['msg' => $mesg, 'status' => $status, 'tok' => $rnd_num]);
    }
    
    public function codevelop_case_study_apply() {
      $error      = false;
      $mesg       = array();
      $status     = 'failed';
      $user_data  = $_POST['usr_data'];
      $temp_data  = explode('-', $user_data);
      $user_id    = $temp_data[1];
      $user_type  = $temp_data [0];
      
      $actual_token = $_SESSION['caStudy'];
      $hashed       = new PhpassHashedPassword();
      $salt         = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random       = $_POST['token'];
      $token        = md5($salt.$random);
      $randm        = new CustomPagesController;
      $rnd_num      = $hashed->hash($randm->generate_random_string());
      $fresh_token  = md5($salt.$rnd_num);

      $_SESSION['caStudy'] = $fresh_token;
      if($actual_token == $token) {
        if($_POST['fname'] == '') {
          $mesg[] = 'fName-Please enter first name.';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['fname'])) {
            $mesg[] = 'fName-Please enter valid first name field.';
            $error = true; 
          } else {
            $first_name = $_POST['fname'];
          }
        }
        if($_POST['lname'] == '') {
          $mesg[] = 'lName-Please enter Last Name.';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['lname'])) {
            $mesg[] = 'lName-Please enter valid last name field.';
            $error = true; 
          } else {
            $last_name = $_POST['lname'];
          }
        }
        if($_POST['batch'] == '' || $_POST['batch'] == 'Select') {
          $mesg[] = 'batchNo-Please select batch number.';
          $error = true;
        } else {
          if(!$this->valid_batch($_POST['batch'])) {
            $mesg[] = 'batchNo-Please select a valid batch number.';
            $error = true; 
          } else {
            $batch_no = $_POST['batch'];
          }
        }
        if($_POST['org'] == '' || $_POST['org'] == 'Select') {
          $mesg[] = 'organisation-Please select organisation..';
          $error = true;
        } else {
          if(!$this->valid_organisation($_POST['org'])) {
            $mesg[] = 'organisation-Please select a valid organisation.';
            $error = true; 
          } else {
            $organisation = $_POST['org'];
          }
        }
        if($_POST['design'] == '') {
          $mesg[] = 'design-Please enter designation..';
          $error = true;
        } else {
          if(!$this->valid_design($_POST['design'])) {
            $mesg[] = 'design-Please select a valid designation.';
            $error = true; 
          } else {
            $designation = $_POST['design'];
          }
        }
        if($_POST['email'] == '') {
          $mesg[] = 'email-Please enter email id.';
          $error = true;
        } else {
          if(!$this->valid_mail($_POST['email'])) {
            $mesg[] = 'email-Please enter valid email id.';
            $error = true; 
          } else {
            $email = $_POST['email'];
          }
        }
        if($_POST['cntry'] == '' || $_POST['cntry'] == 'Select') {
          $mesg[] = 'country-Please select a country.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['cntry'])) {
            $mesg[] = 'country-Please select a valid country.';
            $error = true; 
          } else {
            $country = $_POST['cntry'];
          }
        }
        if($_POST['state'] == '' || $_POST['state'] == 'Select') {
          $mesg[] = 'states-Please select a state.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['state'])) {
            $mesg[] = 'states-Please select a valid state.';
            $error = true; 
          } else {
            $state = $_POST['state'];
          }
        }
        if($_POST['city'] == '' || $_POST['city'] == 'Select') {
          $mesg[] = 'cityList-Please select a city.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['city'])) {
            $mesg[] = 'cityList-Please select a valid city.';
            $error = true; 
          } else {
            $city = $_POST['city'];
          }
        }
        if($_POST['code'] == '') {
          $mesg[] = 'telCode-Please enter country code.';
          $error = true;
        } else {
          if(!$this->country_code($_POST['code'])) {
            $mesg[] = 'telCode-Please select a valid country code.';
            $error = true; 
          } else {
            $code = $_POST['code'];
          }
        }
        if($_POST['mobile'] == '') {
          $mesg[] = 'mobileNo-Please enter mobile number.';
          $error = true;
        } else {
          if(!is_numeric($_POST['mobile'])) {
            $mesg[] = 'mobileNo-Please enter valid mobile number.';
            $error = true; 
          } else {
            $mobile = $_POST['mobile'];
          }
        }
        if($_POST['dmName'] == '' || $_POST['dmName'] == 'Name Of Decision Maker *') {
          $mesg[] = 'dmName-Please enter name..';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['dmName'])) {
            $mesg[] = 'dmName-Please enter a valid name.';
            $error = true; 
          } else {
            $decision_maker_name = $_POST['dmName'];
          }
        }
        if($_POST['dmEmail'] == '' || $_POST['dmEmail'] == 'Email ID Of Decision Maker *') {
          $mesg[] = 'dmEmail-Please enter email id.';
          $error = true;
        } else {
          if(!$this->valid_mail($_POST['dmEmail'])) {
            $mesg[] = 'dmEmail-Please enter valid email id.';
            $error = true; 
          } else {
            $decision_maker_mail = $_POST['dmEmail'];
          }
        }
        if($_POST['dmContactNo'] == '' || $_POST['dmContactNo'] == 'Contact Number Of Decision Maker *') {
          $mesg[] = 'dmContactNo-Please enter mobile number.';
          $error = true;
        } else {
          if(!is_numeric($_POST['dmContactNo'])) {
            $mesg[] = 'dmContactNo-Please enter valid mobile number.';
            $error = true; 
          } else if(!$this->valid_mobile($_POST['dmContactNo'])) {
            $mesg[] = 'dmContactNo-Please enter valid mobile number.';
            $error = true; 
          } else {
            $decision_member_mobile = $_POST['dmContactNo'];
          }
        }
        if($_POST['csBrief'] == '' || $_POST['csBrief'] == 'Brief About Proposed Case Study *') {
          $mesg[] = 'csBrief-Case Study Brief is required.';
          $error = true;
        } else {
          if(!$this->valid_text_area_content($_POST['csBrief'])) {
            $mesg[] = 'csBrief-Invalid characters in text.';
            $error = true; 
          } else {
            $cs_brief = $_POST['csBrief'];
          }
        }
        if($_POST['subjGrp'] == '' || $_POST['subjGrp'] == 'Select') {
          $mesg[] = 'subjGrp-Please enter Concerned IRMA Subject Group..';
          $error = true;
        } else {
          if(!$this->valid_text_area_content($_POST['subjGrp'])) {
            $mesg[] = 'subjGrp-Invalid Concerned IRMA Subject Group.';
            $error = true; 
          } else {
            $subject_group = $_POST['subjGrp'];
          }
        }
        if($_POST['facultyContact'] != '' && $_POST['facultyContact'] != 'Concerned IRMA Faculty To Be Contacted') {
          if(!$this->valid_name($_POST['facultyContact'])) {
            $mesg[] = 'facultyContact-Invalid Faculty Contact.';
            $error = true; 
          } else {
            $faculty_contact = $_POST['facultyContact'];
          }
        } else {
          $faculty_contact = '';
        }
        if($_POST['csStart'] == '' || $_POST['csStart'] == 'Expected Start Date of Case Study Development *') {
          $mesg[] = 'csStart-Please enter a date.';
          $error = true;
        } else {
          if(!$this->valid_date($_POST['csStart'])) {
            $mesg[] = 'csStart-Please enter a valid date.';
            $error = true; 
          } else {
            $cs_start_date = $_POST['csStart'];
          }
        }
        if($_POST['csEnd'] == '' || $_POST['csEnd'] == 'Expected End Date of Case Study Development *') {
          $mesg[] = 'csEnd-Please enter a date.';
          $error = true;
        } else {
          if(!$this->valid_date($_POST['csEnd'])) {
            $mesg[] = 'csEnd-Please enter a valid date.';
            $error = true; 
          } else {
            $cs_end_date = $_POST['csEnd'];
          }
        }

        $name = $first_name .' '. $lastname;
        
        if(!$error) {
          $query = \Drupal::database()->insert('case_study');
          $query->fields(['uid','user_type','name','batch_number','organisation','designation','email','country','state', 'city','country_code','mobile_number','decision_maker_name','decision_maker_email','decision_maker_mobile','cs_brief','concerned_subject_grp','concerned_faculty_contact','cs_start_date','cs_end_date']);
          $query->values([$user_id, $user_type, $name, $batch_no, $organisation, $designation, $email, $country, $state, $city, $code, $mobile, $decision_maker_name,$decision_maker_mail, $decision_member_mobile, $cs_brief, $subject_group, $faculty_contact, $cs_start_date, $cs_end_date]);
          $query->execute();
          $mesg = 'Thank you. Your request has been sent to IRMA. They will revert shortly.';
          $site_mail = \Drupal::config('system.site')->get('mail');
          $mail_data = '<table>
                          <tr><td>Name</td><td>:</td><td>'.$name.'</td></tr>
                          <tr><td>Batch Number</td><td>:</td><td>'.$batch_no.'</td></tr>
                          <tr><td>Organisation</td><td>:</td><td>'.$organisation.'</td></tr>
                          <tr><td>Designation</td><td>:</td><td>'.$designation.'</td></tr>
                          <tr><td>email</td><td>:</td><td>'.$email.'</td></tr>
                          <tr><td>Country</td><td>:</td><td>'.$country.'</td></tr>
                          <tr><td>State</td><td>:</td><td>'.$state.'</td></tr>
                          <tr><td>City</td><td>:</td><td>'.$city.'</td></tr>
                          <tr><td>Mobile Number</td><td>:</td><td>'.$mobile.'</td></tr>
                          <tr><td>Name Of Decision Maker</td><td>:</td><td>'.$decision_maker_name.'</td></tr>
                          <tr><td>Email ID Of Decision Maker</td><td>:</td><td>'.$decision_maker_mail.'</td></tr>
                          <tr><td>Contact Number Of Decision Maker</td><td>:</td><td>'.$decision_member_mobile.'</td></tr>
                          <tr><td>Brief About Proposed Case Study</td><td>:</td><td>'.$cs_brief.'</td></tr>
                          <tr><td>Concerned IRMA Subject Group</td><td>:</td><td>'.$subject_group.'</td></tr>
                          <tr><td>Concerned IRMA Faculty To Be Contacted</td><td>:</td><td>'.$faculty_contact.'</td></tr>
                          <tr><td>Expected Start Date of Case Study Development</td><td>:</td><td>'.$cs_start_date.'</td></tr>
                          <tr><td>Expected End Date of Case Study Development</td><td>:</td><td>'.$cs_end_date.'</td></tr>
                        </table>';

          $body = 'Thank you. Your request has been sent to IRMA. They will revert shortly.<br>' . $mail_data ;
          $body = $this->build_html($first_name, $body);
          MailController::sendCustomMail("",$email,"Alumni Website - Co-develop Case Study Request",$body); //Mail to user
          $status = 'success';
        }
      } else {
        $mesg   = 'Token mismatch... Please try again.';
        $status = 'tErr';
      }
      return new JsonResponse(['msg' => $mesg, 'status' => $status, 'tok' => $rnd_num]);
    }
    
    public function invite_faculty_apply() {
      $error      = false;
      $mesg       = array();
      $status     = 'failed';
      $user_data  = $_POST['usr_data'];
      $temp_data  = explode('-', $user_data);
      $user_id    = $temp_data[1];
      $user_type  = $temp_data [0];
      
      $actual_token = $_SESSION['factInv'];
      $hashed       = new PhpassHashedPassword();
      $salt         = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random       = $_POST['token'];
      $token        = md5($salt.$random);
      $randm        = new CustomPagesController;
      $rnd_num      = $hashed->hash($randm->generate_random_string());
      $fresh_token  = md5($salt.$rnd_num);

      $_SESSION['factInv'] = $fresh_token;
      if($actual_token == $token) {
        if($_POST['fname'] == '') {
          $mesg[] = 'fName-Please enter first name.';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['fname'])) {
            $mesg[] = 'fName-Please enter valid first name field.';
            $error = true; 
          } else {
            $first_name = $_POST['fname'];
          }
        }
        if($_POST['lname'] == '') {
          $mesg[] = 'lName-Please enter Last Name.';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['lname'])) {
            $mesg[] = 'lName-Please enter valid last name field.';
            $error = true; 
          } else {
            $last_name = $_POST['lname'];
          }
        }
        if($_POST['batch'] == '' || $_POST['batch'] == 'Select') {
          $mesg[] = 'batchNo-Please select batch number.';
          $error = true;
        } else {
          if(!$this->valid_batch($_POST['batch'])) {
            $mesg[] = 'batchNo-Please select a valid batch number.';
            $error = true; 
          } else {
            $batch_no = $_POST['batch'];
          }
        }
        if($_POST['org'] == '' || $_POST['org'] == 'Select') {
          $mesg[] = 'organisation-Please select organisation..';
          $error = true;
        } else {
          if(!$this->valid_organisation($_POST['org'])) {
            $mesg[] = 'organisation-Please select a valid organisation.';
            $error = true; 
          } else {
            $organisation = $_POST['org'];
          }
        }
        if($_POST['design'] == '') {
          $mesg[] = 'design-Please enter designation..';
          $error = true;
        } else {
          if(!$this->valid_design($_POST['design'])) {
            $mesg[] = 'design-Please select a valid designation.';
            $error = true; 
          } else {
            $designation = $_POST['design'];
          }
        }
        if($_POST['email'] == '') {
          $mesg[] = 'email-Please enter email id.';
          $error = true;
        } else {
          if(!$this->valid_mail($_POST['email'])) {
            $mesg[] = 'email-Please enter valid email id.';
            $error = true; 
          } else {
            $email = $_POST['email'];
          }
        }
        if($_POST['cntry'] == '' || $_POST['cntry'] == 'Select') {
          $mesg[] = 'country-Please select a country.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['cntry'])) {
            $mesg[] = 'country-Please select a valid country.';
            $error = true; 
          } else {
            $country = $_POST['cntry'];
          }
        }
        if($_POST['state'] == '' || $_POST['state'] == 'Select') {
          $mesg[] = 'states-Please select a state.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['state'])) {
            $mesg[] = 'states-Please select a valid state.';
            $error = true; 
          } else {
            $state = $_POST['state'];
          }
        }
        if($_POST['city'] == '' || $_POST['city'] == 'Select') {
          $mesg[] = 'cityList-Please select a city.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['city'])) {
            $mesg[] = 'cityList-Please select a valid city.';
            $error = true; 
          } else {
            $city = $_POST['city'];
          }
        }
        if($_POST['code'] == '') {
          $mesg[] = 'telCode-Please enter country code.';
          $error = true;
        } else {
          if(!$this->country_code($_POST['code'])) {
            $mesg[] = 'telCode-Please select a valid country code.';
            $error = true; 
          } else {
            $code = $_POST['code'];
          }
        }
        if($_POST['mobile'] == '') {
          $mesg[] = 'mobileNo-Please enter mobile number.';
          $error = true;
        } else {
          if(!is_numeric($_POST['mobile'])) {
            $mesg[] = 'mobileNo-Please enter valid mobile number.';
            $error = true; 
          } else {
            $mobile = $_POST['mobile'];
          }
        }
        if($_POST['dmName'] == '') {
          $mesg[] = 'dmName-Please enter name..';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['dmName'])) {
            $mesg[] = 'dmName-Please enter a valid name.';
            $error = true; 
          } else {
            $decision_maker_name = $_POST['dmName'];
          }
        }
        if($_POST['dmEmail'] == '') {
          $mesg[] = 'dmEmail-Please enter email id.';
          $error = true;
        } else {
          if(!$this->valid_mail($_POST['dmEmail'])) {
            $mesg[] = 'dmEmail-Please enter valid email id.';
            $error = true; 
          } else {
            $decision_maker_mail = $_POST['dmEmail'];
          }
        }
        if($_POST['dmContactNo'] == '') {
          $mesg[] = 'dmContactNo-Please enter mobile number.';
          $error = true;
        } else {
          if(!is_numeric($_POST['dmContactNo'])) {
            $mesg[] = 'dmContactNo-Please enter valid mobile number.';
            $error = true; 
          } else if(!$this->valid_mobile($_POST['dmContactNo'])) {
            $mesg[] = 'dmContactNo-Please enter valid mobile number.';
            $error = true; 
          } else {
            $decision_member_mobile = $_POST['dmContactNo'];
          }
        }
        if($_POST['workBrief'] == '') {
          $mesg[] = 'workBrief-Workshop Brief is required.';
          $error = true;
        } else {
          if(!$this->valid_text_area_content($_POST['workBrief'])) {
            $mesg[] = 'workBrief-Invalid characters in text.';
            $error = true; 
          } else {
            $workshop_brief = $_POST['workBrief'];
          }
        }
        if($_POST['subjGrp'] == '' || $_POST['subjGrp'] == 'Select') {
          $mesg[] = 'subjGrp-Please enter Concerned IRMA Subject Group..';
          $error = true;
        } else {
          if(!$this->valid_text_area_content($_POST['subjGrp'])) {
            $mesg[] = 'subjGrp-Invalid Concerned IRMA Subject Group.';
            $error = true; 
          } else {
            $subject_group = $_POST['subjGrp'];
          }
        }
        if($_POST['facultyContact'] != '') {
          if(!$this->valid_name($_POST['facultyContact'])) {
            $mesg[] = 'facultyContact-Invalid Faculty Contact.';
            $error = true; 
          } else {
            $faculty_contact = $_POST['facultyContact'];
          }
        } else {
          $faculty_contact = '';
        }
        if($_POST['workStart'] == '') {
          $mesg[] = 'workStart-Please enter a date.';
          $error = true;
        } else {
          if(!$this->valid_date($_POST['workStart'])) {
            $mesg[] = 'workStart-Please enter a valid date.';
            $error = true; 
          } else {
            $workshop_start_date = $_POST['workStart'];
          }
        }
        if($_POST['workEnd'] == '') {
          $mesg[] = 'workEnd-Please enter a date.';
          $error = true;
        } else {
          $workstartdate = str_replace('/', '-', $_POST['workStart']);
          $workstartdate = strtotime($workstartdate);
          $workenddate = str_replace('/', '-', $_POST['workEnd']);
          $workenddate = strtotime($workenddate);
          if(!$this->valid_date($_POST['workEnd'])) {
            $mesg[] = 'workEnd-Please enter a valid date.';
            $error = true; 
          } elseif(isset($_POST['workStart']) && ($workstartdate>$workenddate)) {
            $mesg[] = 'workEnd-End date should be greater than start date.';
            $error = true;
          } else {
            $workshop_end_date = $_POST['workEnd'];
          }
        }
        $name = $first_name .' '. $lastname;
        
        if(!$error) {
          $query = \Drupal::database()->insert('invite_faculty');
          $query->fields(['uid','user_type','name','batch_number','organisation','designation','email','country', 'state','city','country_code','mobile_number','decision_maker_name','decision_maker_email','decision_maker_mobile','workshop_brief','concerned_subject_grp','concerned_faculty_contact','workshop_start_date','workshop_end_date']);
          $query->values([$user_id, $user_type, $name, $batch_no, $organisation, $designation, $email, $country, $state, $city, $code, $mobile, $decision_maker_name,$decision_maker_mail, $decision_member_mobile, $workshop_brief, $subject_group, $faculty_contact, $workshop_start_date, $workshop_end_date]);
          $query->execute();
          $mesg = 'Thank you. Your request has been sent to IRMA. They will revert shortly.';
          $site_mail = \Drupal::config('system.site')->get('mail');
          $mail_data = '<table>
                          <tr><td>Name</td><td>:</td><td>'.$name.'</td></tr>
                          <tr><td>Batch Number</td><td>:</td><td>'.$batch_no.'</td></tr>
                          <tr><td>Organisation</td><td>:</td><td>'.$organisation.'</td></tr>
                          <tr><td>Designation</td><td>:</td><td>'.$designation.'</td></tr>
                          <tr><td>email</td><td>:</td><td>'.$email.'</td></tr>
                          <tr><td>Country</td><td>:</td><td>'.$country.'</td></tr>
                          <tr><td>State</td><td>:</td><td>'.$state.'</td></tr>
                          <tr><td>City</td><td>:</td><td>'.$city.'</td></tr>
                          <tr><td>Mobile Number</td><td>:</td><td>'.$mobile.'</td></tr>
                          <tr><td>Decision maker\'s name</td><td>:</td><td>'.$decision_maker_name.'</td></tr>
                          <tr><td>Decision maker\'s mail</td><td>:</td><td>'.$decision_maker_mail.'</td></tr>
                          <tr><td>Decision maker\'s mobile</td><td>:</td><td>'.$decision_member_mobile.'</td></tr>
                          <tr><td>Workshop Brief</td><td>:</td><td>'.$workshop_brief.'</td></tr>
                          <tr><td>Subject Group</td><td>:</td><td>'.$subject_group.'</td></tr>
                          <tr><td>Faculty Contact</td><td>:</td><td>'.$faculty_contact.'</td></tr>
                          <tr><td>Workshop start date</td><td>:</td><td>'.$workshop_start_date.'</td></tr>
                          <tr><td>Workshop end date</td><td>:</td><td>'.$workshop_end_date.'</td></tr>
                        </table>';
          $body = 'Thank you. Your request has been sent to IRMA. They will revert shortly.<br>' . $mail_data ;
          
          $body = $this->build_html($first_name, $body);
          MailController::sendCustomMail("",$email,"Alumni Website -  Invite Faculty To Conduct Workshop Request",$body); //Mail to user
          $status = 'success';
        }
      } else {
        $mesg   = 'Token mismatch... Please try again.';
        $status = 'tErr';
      }
      return new JsonResponse(['msg' => $mesg, 'status' => $status, 'tok' => $rnd_num]);
    }
    
    public function refer_recruiter_apply() {
      $error      = false;
      $mesg       = array();
      $status     = 'failed';
      $user_data  = $_POST['usr_data'];
      $temp_data  = explode('-', $user_data);
      $user_id    = $temp_data[1];
      $user_type  = $temp_data [0];
      
      $actual_token = $_SESSION['refRecruit'];
      $hashed       = new PhpassHashedPassword();
      $salt         = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random       = $_POST['token'];
      $token        = md5($salt.$random);
      $randm        = new CustomPagesController;
      $rnd_num      = $hashed->hash($randm->generate_random_string());
      $fresh_token  = md5($salt.$rnd_num);

      $_SESSION['refRecruit'] = $fresh_token;
      if($actual_token == $token) {
        if($_POST['fname'] == '') {
          $mesg[] = 'fName-Please enter first name.';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['fname'])) {
            $mesg[] = 'fName-Please enter valid first name field.';
            $error = true; 
          } else {
            $first_name = $_POST['fname'];
          }
        }
        if($_POST['lname'] == '') {
          $mesg[] = 'lName-Please enter Last Name.';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['lname'])) {
            $mesg[] = 'lName-Please enter valid last name field.';
            $error = true; 
          } else {
            $last_name = $_POST['lname'];
          }
        }
        if($_POST['batch'] == '' || $_POST['batch'] == 'Select') {
          $mesg[] = 'batchNo-Please select batch number.';
          $error = true;
        } else {
          if(!$this->valid_batch($_POST['batch'])) {
            $mesg[] = 'batchNo-Please select a valid batch number.';
            $error = true; 
          } else {
            $batch_no = $_POST['batch'];
          }
        }
        if($_POST['org'] == '' || $_POST['org'] == 'Select') {
          $mesg[] = 'organisation-Please select organisation..';
          $error = true;
        } else {
          if(!$this->valid_organisation($_POST['org'])) {
            $mesg[] = 'organisation-Please select a valid organisation.';
            $error = true; 
          } else {
            $organisation = $_POST['org'];
          }
        }
        if($_POST['design'] == '') {
          $mesg[] = 'design-Please enter designation..';
          $error = true;
        } else {
          if(!$this->valid_design($_POST['design'])) {
            $mesg[] = 'design-Please select a valid designation.';
            $error = true; 
          } else {
            $designation = $_POST['design'];
          }
        }
        if($_POST['email'] == '') {
          $mesg[] = 'email-Please enter email id.';
          $error = true;
        } else {
          if(!$this->valid_mail($_POST['email'])) {
            $mesg[] = 'email-Please enter valid email id.';
            $error = true; 
          } else {
            $email = $_POST['email'];
          }
        }
        if($_POST['cntry'] == '' || $_POST['cntry'] == 'Select') {
          $mesg[] = 'country-Please select a country.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['cntry'])) {
            $mesg[] = 'country-Please select a valid country.';
            $error = true; 
          } else {
            $country = $_POST['cntry'];
          }
        }
        if($_POST['state'] == '' || $_POST['state'] == 'Select') {
          $mesg[] = 'states-Please select a state.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['state'])) {
            $mesg[] = 'states-Please select a valid state.';
            $error = true; 
          } else {
            $state = $_POST['state'];
          }
        }
        if($_POST['city'] == '' || $_POST['city'] == 'Select') {
          $mesg[] = 'cityList-Please select a city.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['city'])) {
            $mesg[] = 'cityList-Please select a valid city.';
            $error = true; 
          } else {
            $city = $_POST['city'];
          }
        }
        if($_POST['code'] == '') {
          $mesg[] = 'telCode-Please enter country code.';
          $error = true;
        } else {
          if(!$this->country_code($_POST['code'])) {
            $mesg[] = 'telCode-Please select a valid country code.';
            $error = true; 
          } else {
            $code = $_POST['code'];
          }
        }
        if($_POST['mobile'] == '') {
          $mesg[] = 'mobileNo-Please enter mobile number.';
          $error = true;
        } else {
          if(!is_numeric($_POST['mobile'])) {
            $mesg[] = 'mobileNo-Please enter valid mobile number.';
            $error = true; 
          } elseif(!$this->valid_mobile($_POST['mobile'])) {
            $mesg[] = 'mobileNo-Please enter valid mobile number.';
            $error = true; 
          } else {
            $mobile = $_POST['mobile'];
          }
        }
        if($_POST['hrName'] == '' || $_POST['hrName'] == 'Name Of HR *') {
          $mesg[] = 'hrName-Please enter name..';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['hrName'])) {
            $mesg[] = 'hrName-Please enter a valid name.';
            $error = true; 
          } else {
            $hr_name = $_POST['hrName'];
          }
        }
        if($_POST['dmName'] == '' || $_POST['dmName'] == 'Name Of Decision Maker *') {
          $mesg[] = 'dmName-Please enter name..';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['dmName'])) {
            $mesg[] = 'dmName-Please enter a valid name.';
            $error = true; 
          } else {
            $decision_maker_name = $_POST['dmName'];
          }
        }
        if($_POST['dmEmail'] == '' || $_POST['dmEmail'] == 'Email ID Of Decision Maker *') {
          $mesg[] = 'dmEmail-Please enter email id.';
          $error = true;
        } else {
          if(!$this->valid_mail($_POST['dmEmail'])) {
            $mesg[] = 'dmEmail-Please enter valid email id.';
            $error = true; 
          } else {
            $decision_maker_mail = $_POST['dmEmail'];
          }
        }
        if($_POST['dmContactNo'] == '' || $_POST['dmContactNo'] == 'Contact Number Of Decision Maker *') {
          $mesg[] = 'dmContactNo-Please enter mobile number.';
          $error = true;
        } else {
          if(!is_numeric($_POST['dmContactNo'])) {
            $mesg[] = 'dmContactNo-Please enter valid mobile number.';
            $error = true; 
          } else if(!$this->valid_mobile($_POST['dmContactNo'])) {
            $mesg[] = 'dmContactNo-Please enter valid mobile number.';
            $error = true; 
          } else {
            $decision_maker_mobile = $_POST['dmContactNo'];
          }
        }
        if($_POST['recMnth'] == '' || $_POST['recMnth'] == 'Recruitment Month *') {
          $mesg[] = 'recMnth-Recruitment month is required.';
          $error = true;
        } else {
          if(!$this->valid_month($_POST['recMnth'])) {
            $mesg[] = 'recMnth-Please select a valid batch number.';
            $error = true; 
          } else {
            $recruitment_month = $_POST['recMnth'];
          }
        }
        if($_POST['otherDtls'] != '' && $_POST['otherDtls'] != 'Other Details') {
          if(!$this->valid_text_area_content($_POST['otherDtls'])) {
            $mesg[] = 'otherDtls-Invalid characters in text.';
            $error = true; 
          } else {
            $other_details = $_POST['otherDtls'];
          }
        }

        $name = $first_name .' '. $lastname;
        
        if(!$error) {
          $query = \Drupal::database()->insert('refer_recruiter');
          $query->fields(['uid','user_type','name','batch_number','organisation','designation','email','country', 'state','city','country_code','mobile_number', 'hr_name','decision_maker_name','decision_maker_email','decision_maker_mobile','recruitment_month','other_details']);
          $query->values([$user_id, $user_type, $name, $batch_no, $organisation, $designation, $email, $country, $state, $city, $code, $mobile, $hr_name, $decision_maker_name,$decision_maker_mail, $decision_maker_mobile, $recruitment_month, $other_details]);
          $query->execute();
          $mesg = 'Thank you. Your request has been sent to IRMA. They will revert shortly.';
          $site_mail = \Drupal::config('system.site')->get('mail');
          $mail_data = '<table>
                          <tr><td>Name</td><td>:</td><td>'.$name.'</td></tr>
                          <tr><td>Batch Number</td><td>:</td><td>'.$batch_no.'</td></tr>
                          <tr><td>Organisation</td><td>:</td><td>'.$organisation.'</td></tr>
                          <tr><td>Designation</td><td>:</td><td>'.$designation.'</td></tr>
                          <tr><td>email</td><td>:</td><td>'.$email.'</td></tr>
                          <tr><td>Country</td><td>:</td><td>'.$country.'</td></tr>
                          <tr><td>State</td><td>:</td><td>'.$state.'</td></tr>
                          <tr><td>City</td><td>:</td><td>'.$city.'</td></tr>
                          <tr><td>Mobile Number</td><td>:</td><td>'.$mobile.'</td></tr>
                          <tr><td>HR\'s name </td><td>:</td><td>'.$hr_name.'</td></tr>
                          <tr><td>Decision maker\'s name</td><td>:</td><td>'.$decision_maker_name.'</td></tr>
                          <tr><td>Decision maker\'s mail</td><td>:</td><td>'.$decision_maker_mail.'</td></tr>
                          <tr><td>Decision maker\'s mobile</td><td>:</td><td>'.$decision_maker_mobile.'</td></tr>
                          <tr><td>ecruitment month</td><td>:</td><td>'.$recruitment_month.'</td></tr>
                          <tr><td>Other details</td><td>:</td><td>'.$other_details.'</td></tr>
                        </table>';
          $body = 'Thank you. Your request has been sent to IRMA. They will revert shortly.<br>' . $mail_data ;
          $body = $this->build_html($first_name, $body);
          MailController::sendCustomMail("",$email,"Alumni Website - Refer A Recruiter Request",$body); //Mail to user
          $status = 'success';
        }
      } else {
        $mesg   = 'Token mismatch... Please try again.';
        $status = 'tErr';
      }
      return new JsonResponse(['msg' => $mesg, 'status' => $status, 'tok' => $rnd_num]);
    }
    
    
    
    public function campus_infrastructure_wifi() {
      $error      = false;
      $mesg       = array();
      $status     = 'failed';
      $user_data  = $_POST['userData'];
      $temp_data  = explode('-', $user_data);
      $user_id    = $temp_data[1];
      $user_type  = $temp_data [0];
      
      $actual_token = $_SESSION['wifiApp'];
      $hashed       = new PhpassHashedPassword();
      $salt         = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random       = $_POST['token'];
      $token        = md5($salt.$random);
      $randm        = new CustomPagesController;
      $rnd_num      = $hashed->hash($randm->generate_random_string());
      $fresh_token  = md5($salt.$rnd_num);

      $_SESSION['wifiApp'] = $fresh_token;
      if($actual_token == $token) {
        if($_POST['fname'] == '') {
          $mesg[] = 'fName-Please enter first name.';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['fname'])) {
            $mesg[] = 'fName-Please enter valid first name field.';
            $error = true; 
          } else {
            $first_name = $_POST['fname'];
          }
        }
        if($_POST['lname'] == '') {
          $mesg[] = 'lName-Please enter Last Name.';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['lname'])) {
            $mesg[] = 'lName-Please enter valid last name field.';
            $error = true; 
          } else {
            $last_name = $_POST['lname'];
          }
        }
        if($_POST['batch'] == '' || $_POST['batch'] == 'Select') {
          $mesg[] = 'batchNo-Please select batch number.';
          $error = true;
        } else {
          if(!$this->valid_batch($_POST['batch'])) {
            $mesg[] = 'batchNo-Please select a valid batch number.';
            $error = true; 
          } else {
            $batch_no = $_POST['batch'];
          }
        }
        if($_POST['org'] == '' || $_POST['org'] == 'Select') {
          $mesg[] = 'organisation-Please select organisation..';
          $error = true;
        } else {
          if(!$this->valid_organisation($_POST['org'])) {
            $mesg[] = 'organisation-Please select a valid organisation.';
            $error = true; 
          } else {
            $organisation = $_POST['org'];
          }
        }
        if($_POST['design'] == '') {
          $mesg[] = 'design-Please enter designation..';
          $error = true;
        } else {
          if(!$this->valid_design($_POST['design'])) {
            $mesg[] = 'design-Please select a valid designation.';
            $error = true; 
          } else {
            $designation = $_POST['design'];
          }
        }
        if($_POST['email'] == '') {
          $mesg[] = 'email-Please enter email id.';
          $error = true;
        } else {
          if(!$this->valid_mail($_POST['email'])) {
            $mesg[] = 'email-Please enter valid email id.';
            $error = true; 
          } else {
            $email = $_POST['email'];
          }
        }
        if($_POST['cntry'] == '' || $_POST['cntry'] == 'Select') {
          $mesg[] = 'country-Please select a country.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['cntry'])) {
            $mesg[] = 'country-Please select a valid country.';
            $error = true; 
          } else {
            $country = $_POST['cntry'];
          }
        }
        if($_POST['state'] == '' || $_POST['state'] == 'Select') {
          $mesg[] = 'states-Please select a state.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['state'])) {
            $mesg[] = 'states-Please select a valid state.';
            $error = true; 
          } else {
            $state = $_POST['state'];
          }
        }
        if($_POST['city'] == '' || $_POST['city'] == 'Select') {
          $mesg[] = 'cityList-Please select a city.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['city'])) {
            $mesg[] = 'cityList-Please select a valid city.';
            $error = true; 
          } else {
            $city = $_POST['city'];
          }
        }
        if($_POST['code'] == '') {
          $mesg[] = 'telCode-Please enter country code.';
          $error = true;
        } else {
          if(!$this->country_code($_POST['code'])) {
            $mesg[] = 'telCode-Please select a valid country code.';
            $error = true; 
          } else {
            $code = $_POST['code'];
          }
        }
        if($_POST['mobile'] == '') {
          $mesg[] = 'mobileNo-Please enter mobile number.';
          $error = true;
        } else {
          if(!is_numeric($_POST['mobile'])) {
            $mesg[] = 'mobileNo-Please enter valid mobile number.';
            $error = true; 
          } else {
            $mobile = $_POST['mobile'];
          }
        }
        
        if($_POST['arrDate'] == '') {
          $mesg[] = 'arrDate-Please enter arrival date.';
          $error = true;
        } else {
          if(!$this->valid_date($_POST['arrDate'])) {
            $mesg[] = 'arrDate-Please enter a valid date.';
            $error = true;
          } else {
            $temp_arr   = str_replace('/', '-', $_POST['arrDate']);
            $arr_timestamp = strtotime($temp_arr);
            $arrival_date = $_POST['arrDate'];
          }
        }
        if($_POST['depDate'] == '') {
          $mesg[] = 'depDate-Please enter departure date.';
          $error = true;
        } else {
          if(!$this->valid_date($_POST['depDate'])) {
            $mesg[] = 'depDate-Please enter a valid date.';
            $error = true;
          } else {
            $temp_dep   = str_ireplace('/', '-', $_POST['depDate']);
            $dep_timestamp = strtotime($temp_dep);
            if($arr_timestamp > $dep_timestamp) {
              $mesg[] = 'depDate-Departure date cannot be behind Arrival Date.';
              $error = true;
            } else {
              $depart_date = $_POST['depDate'];
            }
          }
        }
        if($_POST['arrHr'] == '' || $_POST['arrHr'] == 'Select') {
          $mesg[] = 'arrHr-Arrival hour is required.';
          $error = true;
        } else {
          if(is_numeric($_POST['arrHr']) && ($_POST['arrHr'] >= 0 && $_POST['arrHr'] <= 23)) {
            $arrival_hour = $_POST['arrHr'];
          } else {
            $mesg[] = 'arrHr-Please enter a valid time.';
            $error = true;
          }
        }
        if($_POST['arrMin'] == '' || $_POST['arrMin'] == 'Select') {
          $mesg[] = 'arrMin-Arrival minute is required.';
          $error = true;
        } else {
          if(is_numeric($_POST['arrMin']) && ($_POST['arrMin'] >= 0 && $_POST['arrMin'] <= 59)) {
            $arrival_min = $_POST['arrMin'];
          } else {
            $mesg[] = 'arrMin-Please enter a valid time.';
            $error = true;
          }
        }
        if($_POST['depHr'] == '' || $_POST['depHr'] == 'Select') {
          $mesg[] = 'depHr-Please enter a departure time.';
          $error = true;
        } else {
          if(is_numeric($_POST['depHr']) && ($_POST['depHr'] >= 0 && $_POST['depHr'] <= 23)) {
            $depart_hour = $_POST['depHr'];
          } else {
            $mesg[] = 'depHr-Please enter a valid time.';
            $error = true;
          }
        }
        if($_POST['depMin'] == '' || $_POST['depMin'] == 'Select') {
          $mesg[] = 'depMin-Please enter a departure time.';
          $error = true;
        } else {
          if(is_numeric($_POST['depMin']) && ($_POST['depMin'] >= 0 && $_POST['depMin'] <= 59)) {
            $depart_min = $_POST['depMin'];
          } else {
            $mesg[] = 'depMin-Please enter a valid time.';
            $error = true;
          }
        }
        if(!isset($_POST['purpVisit'])) {
          $mesg[] = 'purpVisit-Please enter purpose of visit.';
          $error = true;
        } else {
          if($_POST['purpVisit'] == 'Personal' || $_POST['purpVisit'] == 'Official') {
            $purpose_visit = $_POST['purpVisit'];
            if($purpose_visit == 'Official') {
              if($_POST['purpose'] == '' || $_POST['purpose'] == 'State Purpose *') {
                $mesg[] = 'purpose-Please enter purpose.';
                $error = true;
              } else {
                if(!preg_match("/^([a-zA-Z]+\s?)*$/", $_POST['purpose'])) {
                  $mesg[] = 'purpose-Please select a valid batch number.';
                  $error = true;
                } else {
                  $purpose = $_POST['purpose'];
                }
              }
            } else {
              $purpose = '';
            }
          } else {
            $mesg[] = 'purpVisit-Invalid data.';
            $error = true;
          }
        }
        if(!isset($_POST['accessWifi'])) {
          $mesg[] = 'accWifi-Access Wifi is required.';
          $error = true;
        } else {
          if($_POST['accessWifi'] == 'yes' || $_POST['accessWifi'] == 'no') {
            $access_wifi = $_POST['accessWifi'];
          } else {
            $mesg[] = 'accWifi-Please select a valid batch number.';
            $error = true;
          }
        }   
        $name = $first_name .' '. $lastname;
        
        if(!$error) {
          $arrival_time = $arrival_hour.':'.$arrival_min;
          $depart_time = $depart_hour .':'. $depart_min;
          $query = \Drupal::database()->insert('campus_infra_wifi');
          $query->fields(['uid','user_type','name','batch_number','organisation','designation','email','country','state', 'city','country_code','mobile_number','arrival_date','arrival_time','departure_date','departure_time','purpose_of_visit','purpose','wifi_access']);
          $query->values([$user_id, $user_type, $name, $batch_no, $organisation, $designation, $email, $country, $state, $city, $code, $mobile, $arrival_date,$arrival_time,$depart_date, $depart_time,$purpose_visit, $purpose, $access_wifi]);
          $query->execute();
          $mesg = 'The wifi username and password will be emailed to you by IRMA. Kindly contact iaaec@irma.ac.in / iao@irma.ac.in  in case of any issues';
          $site_mail = \Drupal::config('system.site')->get('mail');
          $mail_data = '<table>
                          <tr><td>Name</td><td>:</td><td>'.$name.'</td></tr>
                          <tr><td>Batch Number</td><td>:</td><td>'.$batch_no.'</td></tr>
                          <tr><td>Organisation</td><td>:</td><td>'.$organisation.'</td></tr>
                          <tr><td>Designation</td><td>:</td><td>'.$designation.'</td></tr>
                          <tr><td>email</td><td>:</td><td>'.$email.'</td></tr>
                          <tr><td>Country</td><td>:</td><td>'.$country.'</td></tr>
                          <tr><td>State</td><td>:</td><td>'.$state.'</td></tr>
                          <tr><td>City</td><td>:</td><td>'.$city.'</td></tr>
                          <tr><td>Mobile Number</td><td>:</td><td>'.$mobile.'</td></tr>
                          <tr><td>Arrival date </td><td>:</td><td>'.$arrival_date.' - '.$arrival_time.'</td></tr>
                          <tr><td>Departure date</td><td>:</td><td>'.$depart_date.' - '.$depart_time.'</td></tr>
                          <tr><td>Purpose of visit</td><td>:</td><td>'.$purpose_visit.'</td></tr>
                          <tr><td>Purpose</td><td>:</td><td>'.$purpose.'</td></tr>
                          <tr><td>Wi-fi Access</td><td>:</td><td>'.$access_wifi.'</td></tr>
                        </table>';
          $body = 'The wifi username and password will be emailed to you by IRMA. Kindly contact iaaec@irma.ac.in / iao@irma.ac.in  in case of any issues<br>' . $mail_data ;
          $body = $this->build_html($first_name, $body);
          MailController::sendCustomMail("",$email,"Alumni Website - Wifi Access Request",$body); //Mail to user
          $status = 'success';
        }
      } else {
        $mesg   = 'Token mismatch... Please try again.';
        $status = 'tErr';
      }
      return new JsonResponse(['msg' => $mesg, 'status' => $status, 'tok' => $rnd_num]);
    }
    
    public function campus_infrastructure_sac() {
      $error      = false;
      $mesg       = array();
      $status     = 'failed';
      $user_data  = $_POST['userData'];
      $temp_data  = explode('-', $user_data);
      $user_id    = $temp_data[1];
      $user_type  = $temp_data [0];
      
      $actual_token = $_SESSION['sacApp'];
      $hashed       = new PhpassHashedPassword();
      $salt         = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random       = $_POST['token'];
      $token        = md5($salt.$random);
      $randm        = new CustomPagesController;
      $rnd_num      = $hashed->hash($randm->generate_random_string());
      $fresh_token  = md5($salt.$rnd_num);

      $_SESSION['sacApp'] = $fresh_token;
      if($actual_token == $token) {
        if($_POST['fname'] == '') {
          $mesg[] = 'fName-Please enter first name.';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['fname'])) {
            $mesg[] = 'fName-Please enter valid first name field.';
            $error = true; 
          } else {
            $first_name = $_POST['fname'];
          }
        }
        if($_POST['lname'] == '') {
          $mesg[] = 'lName-Please enter Last Name.';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['lname'])) {
            $mesg[] = 'lName-Please enter valid last name field.';
            $error = true; 
          } else {
            $last_name = $_POST['lname'];
          }
        }
        if($_POST['batch'] == '' || $_POST['batch'] == 'Select') {
          $mesg[] = 'batchNo-Please select batch number.';
          $error = true;
        } else {
          if(!$this->valid_batch($_POST['batch'])) {
            $mesg[] = 'batchNo-Please select a valid batch number.';
            $error = true; 
          } else {
            $batch_no = $_POST['batch'];
          }
        }
        if($_POST['org'] == '' || $_POST['org'] == 'Select') {
          $mesg[] = 'organisation-Please select organisation..';
          $error = true;
        } else {
          if(!$this->valid_organisation($_POST['org'])) {
            $mesg[] = 'organisation-Please select a valid organisation.';
            $error = true; 
          } else {
            $organisation = $_POST['org'];
          }
        }
        if($_POST['design'] == '') {
          $mesg[] = 'design-Please enter designation..';
          $error = true;
        } else {
          if(!$this->valid_design($_POST['design'])) {
            $mesg[] = 'design-Please select a valid designation.';
            $error = true; 
          } else {
            $designation = $_POST['design'];
          }
        }
        if($_POST['email'] == '') {
          $mesg[] = 'email-Please enter email id.';
          $error = true;
        } else {
          if(!$this->valid_mail($_POST['email'])) {
            $mesg[] = 'email-Please enter valid email id.';
            $error = true; 
          } else {
            $email = $_POST['email'];
          }
        }
        if($_POST['cntry'] == '' || $_POST['cntry'] == 'Select') {
          $mesg[] = 'country-Please select a country.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['cntry'])) {
            $mesg[] = 'country-Please select a valid country.';
            $error = true; 
          } else {
            $country = $_POST['cntry'];
          }
        }
        if($_POST['state'] == '' || $_POST['state'] == 'Select') {
          $mesg[] = 'states-Please select a state.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['state'])) {
            $mesg[] = 'states-Please select a valid state.';
            $error = true; 
          } else {
            $state = $_POST['state'];
          }
        }
        if($_POST['city'] == '' || $_POST['city'] == 'Select') {
          $mesg[] = 'cityList-Please select a city.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['city'])) {
            $mesg[] = 'cityList-Please select a valid city.';
            $error = true; 
          } else {
            $city = $_POST['city'];
          }
        }
        if($_POST['code'] == '') {
          $mesg[] = 'telCode-Please enter country code.';
          $error = true;
        } else {
          if(!$this->country_code($_POST['code'])) {
            $mesg[] = 'telCode-Please select a valid country code.';
            $error = true; 
          } else {
            $code = $_POST['code'];
          }
        }
        if($_POST['mobile'] == '') {
          $mesg[] = 'mobileNo-Please enter mobile number.';
          $error = true;
        } else {
          if(!is_numeric($_POST['mobile'])) {
            $mesg[] = 'mobileNo-Please enter valid mobile number.';
            $error = true; 
          } else {
            $mobile = $_POST['mobile'];
          }
        }
        
        if($_POST['arrDate'] == '') {
          $mesg[] = 'arrDate-Please enter arrival date.';
          $error = true;
        } else {
          if(!$this->valid_date($_POST['arrDate'])) {
            $mesg[] = 'arrDate-Please enter a valid date.';
            $error = true;
          } else {
            $temp_arr   = str_replace('/', '-', $_POST['arrDate']);
            $arr_timestamp = strtotime($temp_arr);
            $arrival_date = $_POST['arrDate'];
          }
        }
        if($_POST['depDate'] == '') {
          $mesg[] = 'depDate-Please enter departure date.';
          $error = true;
        } else {
          if(!$this->valid_date($_POST['depDate'])) {
            $mesg[] = 'depDate-Please enter a valid date.';
            $error = true;
          } else {
            $temp_dep   = str_ireplace('/', '-', $_POST['depDate']);
            $dep_timestamp = strtotime($temp_dep);
            if($arr_timestamp > $dep_timestamp) {
              $mesg[] = 'depDate-Departure date cannot be behind Arrival Date.';
              $error = true;
            } else {
              $depart_date = $_POST['depDate'];
            }
          }
        }
        if($_POST['arrHr'] == '' || $_POST['arrHr'] == 'Select') {
          $mesg[] = 'arrHr-Arrival hour is required.';
          $error = true;
        } else {
          if(is_numeric($_POST['arrHr']) && ($_POST['arrHr'] >= 0 && $_POST['arrHr'] <= 23)) {
            $arrival_hour = $_POST['arrHr'];
          } else {
            $mesg[] = 'arrHr-Please enter a valid time.';
            $error = true;
          }
        }
        if($_POST['arrMin'] == '' || $_POST['arrMin'] == 'Select') {
          $mesg[] = 'arrMin-Arrival minute is required.';
          $error = true;
        } else {
          if(is_numeric($_POST['arrMin']) && ($_POST['arrMin'] >= 0 && $_POST['arrMin'] <= 59)) {
            $arrival_min = $_POST['arrMin'];
          } else {
            $mesg[] = 'arrMin-Please enter a valid time.';
            $error = true;
          }
        }
        if($_POST['depHr'] == '' || $_POST['depHr'] == 'Select') {
          $mesg[] = 'depHr-Please enter a departure time.';
          $error = true;
        } else {
          if(is_numeric($_POST['depHr']) && ($_POST['depHr'] >= 0 && $_POST['depHr'] <= 23)) {
            $depart_hour = $_POST['depHr'];
          } else {
            $mesg[] = 'depHr-Please enter a valid time.';
            $error = true;
          }
        }
        if($_POST['depMin'] == '' || $_POST['depMin'] == 'Select') {
          $mesg[] = 'depMin-Please enter a departure time.';
          $error = true;
        } else {
          if(is_numeric($_POST['depMin']) && ($_POST['depMin'] >= 0 && $_POST['depMin'] <= 59)) {
            $depart_min = $_POST['depMin'];
          } else {
            $mesg[] = 'depMin-Please enter a valid time.';
            $error = true;
          }
        }
        if(!isset($_POST['purpVisit'])) {
          $mesg[] = 'purpVisit-Please enter purpose of visit.';
          $error = true;
        } else {
          if($_POST['purpVisit'] == 'Personal' || $_POST['purpVisit'] == 'Official') {
            $purpose_visit = $_POST['purpVisit'];
            if($purpose_visit == 'Official') {
              if($_POST['purpose'] == '' || $_POST['purpose'] == 'State Purpose *') {
                $mesg[] = 'purpose-Please enter purpose.';
                $error = true;
              } else {
                if(!preg_match("/^([a-zA-Z]+\s?)*$/", $_POST['purpose'])) {
                  $mesg[] = 'purpose-Please select a valid batch number.';
                  $error = true;
                } else {
                  $purpose = $_POST['purpose'];
                }
              }
            } else {
              $purpose = '';
            }
          } else {
            $mesg[] = 'purpVisit-Invalid data.';
            $error = true;
          }
        }
        if(!isset($_POST['accessSac'])) {
          $mesg[] = 'accSac-SAC Access is required.';
          $error = true;
        } else {
          if($_POST['accessSac'] == 'yes' || $_POST['accessSac'] == 'no') {
            $access_sac = $_POST['accessSac'];
          } else {
            $mesg[] = 'accSac-Please select a valid batch number.';
            $error = true;
          }
        }   
        $name = $first_name .' '. $lastname;
        
        if(!$error) {
          $arrival_time = $arrival_hour.':'.$arrival_min;
          $depart_time = $depart_hour .':'. $depart_min;
          $query = \Drupal::database()->insert('campus_infra_sac');
          $query->fields(['uid','user_type','name','batch_number','organisation','designation','email','country', 'state','city','country_code','mobile_number','arrival_date','arrival_time','departure_date','departure_time','purpose_of_visit','purpose','sac_access']);
          $query->values([$user_id, $user_type, $name, $batch_no, $organisation, $designation, $email, $country, $state, $city, $code, $mobile, $arrival_date,$arrival_time,$depart_date, $depart_time,$purpose_visit, $purpose, $access_sac]);
          $query->execute();
          $mesg = 'Thank you for informing us. You will receive a confirmation mail. Your request has been sent to IRMA.  They will revert shortly. Kindly contact iaaec@irma.ac.in / iao@irma.ac.in in case of any issues.';
          $site_mail = \Drupal::config('system.site')->get('mail');
          $mail_data = '<table>
                          <tr><td>Name</td><td>:</td><td>'.$name.'</td></tr>
                          <tr><td>Batch Number</td><td>:</td><td>'.$batch_no.'</td></tr>
                          <tr><td>Organisation</td><td>:</td><td>'.$organisation.'</td></tr>
                          <tr><td>Designation</td><td>:</td><td>'.$designation.'</td></tr>
                          <tr><td>email</td><td>:</td><td>'.$email.'</td></tr>
                          <tr><td>Country</td><td>:</td><td>'.$country.'</td></tr>
                          <tr><td>State</td><td>:</td><td>'.$state.'</td></tr>
                          <tr><td>City</td><td>:</td><td>'.$city.'</td></tr>
                          <tr><td>Mobile Number</td><td>:</td><td>'.$mobile.'</td></tr>
                          <tr><td>Arrival date </td><td>:</td><td>'.$arrival_date.' - '.$arrival_time.'</td></tr>
                          <tr><td>Departure date</td><td>:</td><td>'.$depart_date.' - '.$depart_time.'</td></tr>
                          <tr><td>Purpose of visit</td><td>:</td><td>'.$purpose_visit.'</td></tr>
                          <tr><td>Purpose</td><td>:</td><td>'.$purpose.'</td></tr>
                          <tr><td>SAC Access</td><td>:</td><td>'.$access_sac.'</td></tr>
                        </table>';
          $body = 'Thank you for informing us. You will receive a confirmation mail. Your request has been sent to IRMA.  They will revert shortly. Kindly contact iaaec@irma.ac.in / iao@irma.ac.in in case of any issues.<br>' . $mail_data ;
          $body = $this->build_html($first_name, $body);
          MailController::sendCustomMail("",$email,"Alumni Website - Wifi Access Request",$body); //Mail to user
          $status = 'success';
        }
      } else {
        $mesg   = 'Token mismatch... Please try again.';
        $status = 'tErr';
      }
      return new JsonResponse(['msg' => $mesg, 'status' => $status, 'tok' => $rnd_num]);
    }
    
    public function campus_infrastructure_stdtmess() {
      $error      = false;
      $mesg       = array();
      $status     = 'failed';
      $user_data  = $_POST['userData'];
      $temp_data  = explode('-', $user_data);
      $user_id    = $temp_data[1];
      $user_type  = $temp_data [0];
      
      $actual_token = $_SESSION['messApp'];
      $hashed       = new PhpassHashedPassword();
      $salt         = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random       = $_POST['token'];
      $token        = md5($salt.$random);
      $randm        = new CustomPagesController;
      $rnd_num      = $hashed->hash($randm->generate_random_string());
      $fresh_token  = md5($salt.$rnd_num);

      $_SESSION['messApp'] = $fresh_token;
      if($actual_token == $token) {
        if($_POST['fname'] == '') {
          $mesg[] = 'fName-Please enter first name.';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['fname'])) {
            $mesg[] = 'fName-Please enter valid first name field.';
            $error = true; 
          } else {
            $first_name = $_POST['fname'];
          }
        }
        if($_POST['lname'] == '') {
          $mesg[] = 'lName-Please enter Last Name.';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['lname'])) {
            $mesg[] = 'lName-Please enter valid last name field.';
            $error = true; 
          } else {
            $last_name = $_POST['lname'];
          }
        }
        if($_POST['batch'] == '' || $_POST['batch'] == 'Select') {
          $mesg[] = 'batchNo-Please select batch number.';
          $error = true;
        } else {
          if(!$this->valid_batch($_POST['batch'])) {
            $mesg[] = 'batchNo-Please select a valid batch number.';
            $error = true; 
          } else {
            $batch_no = $_POST['batch'];
          }
        }
        if($_POST['org'] == '' || $_POST['org'] == 'Select') {
          $mesg[] = 'organisation-Please select organisation..';
          $error = true;
        } else {
          if(!$this->valid_organisation($_POST['org'])) {
            $mesg[] = 'organisation-Please select a valid organisation.';
            $error = true; 
          } else {
            $organisation = $_POST['org'];
          }
        }
        if($_POST['design'] == '') {
          $mesg[] = 'design-Please enter designation..';
          $error = true;
        } else {
          if(!$this->valid_design($_POST['design'])) {
            $mesg[] = 'design-Please select a valid designation.';
            $error = true; 
          } else {
            $designation = $_POST['design'];
          }
        }
        if($_POST['email'] == '') {
          $mesg[] = 'email-Please enter email id.';
          $error = true;
        } else {
          if(!$this->valid_mail($_POST['email'])) {
            $mesg[] = 'email-Please enter valid email id.';
            $error = true; 
          } else {
            $email = $_POST['email'];
          }
        }
        if($_POST['cntry'] == '' || $_POST['cntry'] == 'Select') {
          $mesg[] = 'country-Please select a country.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['cntry'])) {
            $mesg[] = 'country-Please select a valid country.';
            $error = true; 
          } else {
            $country = $_POST['cntry'];
          }
        }
        if($_POST['state'] == '' || $_POST['state'] == 'Select') {
          $mesg[] = 'states-Please select a state.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['state'])) {
            $mesg[] = 'states-Please select a valid state.';
            $error = true; 
          } else {
            $state = $_POST['state'];
          }
        }
        if($_POST['city'] == '' || $_POST['city'] == 'Select') {
          $mesg[] = 'cityList-Please select a city.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['city'])) {
            $mesg[] = 'cityList-Please select a valid city.';
            $error = true; 
          } else {
            $city = $_POST['city'];
          }
        }
        if($_POST['code'] == '') {
          $mesg[] = 'telCode-Please enter country code.';
          $error = true;
        } else {
          if(!$this->country_code($_POST['code'])) {
            $mesg[] = 'telCode-Please select a valid country code.';
            $error = true; 
          } else {
            $code = $_POST['code'];
          }
        }
        if($_POST['mobile'] == '') {
          $mesg[] = 'mobileNo-Please enter mobile number.';
          $error = true;
        } else {
          if(!is_numeric($_POST['mobile'])) {
            $mesg[] = 'mobileNo-Please enter valid mobile number.';
            $error = true; 
          } else {
            $mobile = $_POST['mobile'];
          }
        }
        if($_POST['arrDate'] == '') {
          $mesg[] = 'arrDate-Please enter arrival date.';
          $error = true;
        } else {
          if(!$this->valid_date($_POST['arrDate'])) {
            $mesg[] = 'arrDate-Please enter a valid date.';
            $error = true;
          } else {
            $temp_arr   = str_replace('/', '-', $_POST['arrDate']);
            $arr_timestamp = strtotime($temp_arr);
            $arrival_date = $_POST['arrDate'];
          }
        }
        if($_POST['depDate'] == '') {
          $mesg[] = 'depDate-Please enter departure date.';
          $error = true;
        } else {
          if(!$this->valid_date($_POST['depDate'])) {
            $mesg[] = 'depDate-Please enter a valid date.';
            $error = true;
          } else {
            $temp_dep   = str_ireplace('/', '-', $_POST['depDate']);
            $dep_timestamp = strtotime($temp_dep);
            if($arr_timestamp > $dep_timestamp) {
              $mesg[] = 'depDate-Departure date cannot be behind Arrival Date.';
              $error = true;
            } else {
              $depart_date = $_POST['depDate'];
            }
          }
        }
        if($_POST['arrHr'] == '' || $_POST['arrHr'] == 'Select' || $_POST['arrHr'] == 'Select') {
          $mesg[] = 'arrHr-Arrival hour is required.';
          $error = true;
        } else {
          if(is_numeric($_POST['arrHr']) && ($_POST['arrHr'] >= 0 && $_POST['arrHr'] <= 23)) {
            $arrival_hour = $_POST['arrHr'];
          } else {
            $mesg[] = 'arrHr-Please enter a valid time.';
            $error = true;
          }
        }
        if($_POST['arrMin'] == '' || $_POST['arrMin'] == 'Select') {
          $mesg[] = 'arrMin-Arrival minute is required.';
          $error = true;
        } else {
          if(is_numeric($_POST['arrMin']) && ($_POST['arrMin'] >= 0 && $_POST['arrMin'] <= 59)) {
            $arrival_min = $_POST['arrMin'];
          } else {
            $mesg[] = 'arrMin-Please enter a valid time.';
            $error = true;
          }
        }
        if($_POST['depHr'] == '' || $_POST['depHr'] == 'Select') {
          $mesg[] = 'depHr-Please enter a departure time.';
          $error = true;
        } else {
          if(is_numeric($_POST['depHr']) && ($_POST['depHr'] >= 0 && $_POST['depHr'] <= 23)) {
            $depart_hour = $_POST['depHr'];
          } else {
            $mesg[] = 'depHr-Please enter a valid time.';
            $error = true;
          }
        }
        if($_POST['depMin'] == '' || $_POST['depMin'] == 'Select') {
          $mesg[] = 'depMin-Please enter a departure time.';
          $error = true;
        } else {
          if(is_numeric($_POST['depMin']) && ($_POST['depMin'] >= 0 && $_POST['depMin'] <= 59)) {
            $depart_min = $_POST['depMin'];
          } else {
            $mesg[] = 'depMin-Please enter a valid time.';
            $error = true;
          }
        }
        if(!isset($_POST['purpVisit'])) {
          $mesg[] = 'purpVisit-Please enter purpose of visit.';
          $error = true;
        } else {
          if($_POST['purpVisit'] == 'Personal' || $_POST['purpVisit'] == 'Official') {
            $purpose_visit = $_POST['purpVisit'];
            if($purpose_visit == 'Official') {
              if($_POST['purpose'] == '') {
                $mesg[] = 'purpose-Please enter purpose.';
                $error = true;
              } else {
                if(!preg_match("/^([a-zA-Z]+\s?)*$/", $_POST['purpose'])) {
                  $mesg[] = 'purpose-Please select a valid batch number.';
                  $error = true;
                } else {
                  $purpose = $_POST['purpose'];
                }
              }
            } else {
              $purpose = '';
            }
          } else {
            $mesg[] = 'purpVisit-Invalid data.';
            $error = true;
          }
        }
        if(!isset($_POST['arrFood'])) {
          $mesg[] = 'arrFood-Arrange Food is required.';
          $error = true;
        } else {
          if($_POST['arrFood'] == 'yes' || $_POST['arrFood'] == 'no') {
            $arrange_food = $_POST['arrFood'];
          } else {
            $mesg[] = 'arrFood-Please select a valid batch number.';
            $error = true;
          }
        }
        if($_POST['numPerson'] == '' || $_POST['numPerson'] == 'Select') {
          $mesg[] = 'noPers-No of Persons is required.';
          $error = true;
        } else {
          if(is_numeric($_POST['numPerson']) && ($_POST['numPerson'] >= 0 && $_POST['numPerson'] <= 10)) {
            $num_persons = $_POST['numPerson'];
          } else {
            $mesg[] = 'noPers-Please select a valid batch number.';
            $error = true;
          }
        }

        $name = $first_name .' '. $lastname;
        
        if(!$error) {
          $arrival_time = $arrival_hour.':'.$arrival_min;
          $depart_time = $depart_hour .':'. $depart_min;
          $query = \Drupal::database()->insert('campus_infra_mess');
          $query->fields(['uid','user_type','name','batch_number','organisation','designation','email','country', 'state','city','country_code','mobile_number','arrival_date','arrival_time','departure_date','departure_time','purpose_of_visit','purpose','arrange_food', 'num_person']);
          $query->values([$user_id, $user_type, $name, $batch_no, $organisation, $designation, $email, $country, $state, $city, $code, $mobile, $arrival_date,$arrival_time,$depart_date, $depart_time,$purpose_visit, $purpose, $arrange_food, $num_persons]);
          $query->execute();
          $mesg = 'Thank you for informing us. You will receive a confirmation mail. Your request has been sent to IRMA.  They will revert shortly. Kindly contact iaaec@irma.ac.in / iao@irma.ac.in in case of any issues.';
          $site_mail = \Drupal::config('system.site')->get('mail');
          $mail_data = '<table>
                          <tr><td>Name</td><td>:</td><td>'.$name.'</td></tr>
                          <tr><td>Batch Number</td><td>:</td><td>'.$batch_no.'</td></tr>
                          <tr><td>Organisation</td><td>:</td><td>'.$organisation.'</td></tr>
                          <tr><td>Designation</td><td>:</td><td>'.$designation.'</td></tr>
                          <tr><td>email</td><td>:</td><td>'.$email.'</td></tr>
                          <tr><td>Country</td><td>:</td><td>'.$country.'</td></tr>
                          <tr><td>State</td><td>:</td><td>'.$state.'</td></tr>
                          <tr><td>City</td><td>:</td><td>'.$city.'</td></tr>
                          <tr><td>Mobile Number</td><td>:</td><td>'.$mobile.'</td></tr>
                          <tr><td>Arrival date </td><td>:</td><td>'.$arrival_date.' - '.$arrival_time.'</td></tr>
                          <tr><td>Departure date</td><td>:</td><td>'.$depart_date.' - '.$depart_time.'</td></tr>
                          <tr><td>Purpose of visit</td><td>:</td><td>'.$purpose_visit.'</td></tr>
                          <tr><td>Purpose</td><td>:</td><td>'.$purpose.'</td></tr>
                          <tr><td>Arrange Food</td><td>:</td><td>'.$arrange_food.'</td></tr>
                          <tr><td>Number of persons</td><td>:</td><td>'.$num_persons.'</td></tr>
                        </table>';
          $body = 'Thank you for informing us. You will receive a confirmation mail. Your request has been sent to IRMA.  They will revert shortly. Kindly contact iaaec@irma.ac.in / iao@irma.ac.in in case of any issues.<br>' . $mail_data ;
          $body = $this->build_html($first_name, $body);
          MailController::sendCustomMail("",$email,"Alumni Website - IRMA Students Mess Request",$body); //Mail to user
          $status = 'success';
        }
      } else {
        $mesg   = 'Token mismatch... Please try again.';
        $status = 'tErr';
      }
      return new JsonResponse(['msg' => $mesg, 'status' => $status, 'tok' => $rnd_num]);
    }
    
    public function campus_infrastructure_library() {
      $error      = false;
      $mesg       = array();
      $status     = 'failed';
      $user_data  = $_POST['userData'];
      $temp_data  = explode('-', $user_data);
      $user_id    = $temp_data[1];
      $user_type  = $temp_data [0];
      
      $actual_token = $_SESSION['libApp'];
      $hashed       = new PhpassHashedPassword();
      $salt         = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random       = $_POST['token'];
      $token        = md5($salt.$random);
      $randm        = new CustomPagesController;
      $rnd_num      = $hashed->hash($randm->generate_random_string());
      $fresh_token  = md5($salt.$rnd_num);

      $_SESSION['libApp'] = $fresh_token;
      if($actual_token == $token) {
        if($_POST['fname'] == '') {
          $mesg[] = 'fName-Please enter first name.';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['fname'])) {
            $mesg[] = 'fName-Please enter valid first name field.';
            $error = true; 
          } else {
            $first_name = $_POST['fname'];
          }
        }
        if($_POST['lname'] == '') {
          $mesg[] = 'lName-Please enter Last Name.';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['lname'])) {
            $mesg[] = 'lName-Please enter valid last name field.';
            $error = true; 
          } else {
            $last_name = $_POST['lname'];
          }
        }
        if($_POST['batch'] == '' || $_POST['batch'] == 'Select') {
          $mesg[] = 'batchNo-Please select batch number.';
          $error = true;
        } else {
          if(!$this->valid_batch($_POST['batch'])) {
            $mesg[] = 'batchNo-Please select a valid batch number.';
            $error = true; 
          } else {
            $batch_no = $_POST['batch'];
          }
        }
        if($_POST['org'] == '' || $_POST['org'] == 'Select') {
          $mesg[] = 'organisation-Please select organisation..';
          $error = true;
        } else {
          if(!$this->valid_organisation($_POST['org'])) {
            $mesg[] = 'organisation-Please select a valid organisation.';
            $error = true; 
          } else {
            $organisation = $_POST['org'];
          }
        }
        if($_POST['design'] == '') {
          $mesg[] = 'design-Please enter designation..';
          $error = true;
        } else {
          if(!$this->valid_design($_POST['design'])) {
            $mesg[] = 'design-Please select a valid designation.';
            $error = true; 
          } else {
            $designation = $_POST['design'];
          }
        }
        if($_POST['email'] == '') {
          $mesg[] = 'email-Please enter email id.';
          $error = true;
        } else {
          if(!$this->valid_mail($_POST['email'])) {
            $mesg[] = 'email-Please enter valid email id.';
            $error = true; 
          } else {
            $email = $_POST['email'];
          }
        }
        if($_POST['cntry'] == '' || $_POST['cntry'] == 'Select') {
          $mesg[] = 'country-Please select a country.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['cntry'])) {
            $mesg[] = 'country-Please select a valid country.';
            $error = true; 
          } else {
            $country = $_POST['cntry'];
          }
        }
        if($_POST['state'] == '' || $_POST['state'] == 'Select') {
          $mesg[] = 'states-Please select a state.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['state'])) {
            $mesg[] = 'states-Please select a valid state.';
            $error = true; 
          } else {
            $state = $_POST['state'];
          }
        }
        if($_POST['city'] == '' || $_POST['city'] == 'Select') {
          $mesg[] = 'cityList-Please select a city.';
          $error = true;
        } else {
          if(!$this->valid_country($_POST['city'])) {
            $mesg[] = 'cityList-Please select a valid city.';
            $error = true; 
          } else {
            $city = $_POST['city'];
          }
        }
        if($_POST['code'] == '') {
          $mesg[] = 'telCode-Please enter country code.';
          $error = true;
        } else {
          if(!$this->country_code($_POST['code'])) {
            $mesg[] = 'telCode-Please select a valid country code.';
            $error = true; 
          } else {
            $code = $_POST['code'];
          }
        }
        if($_POST['mobile'] == '') {
          $mesg[] = 'mobileNo-Please enter mobile number.';
          $error = true;
        } else {
          if(!is_numeric($_POST['mobile'])) {
            $mesg[] = 'mobileNo-Please enter valid mobile number.';
            $error = true; 
          } else {
            $mobile = $_POST['mobile'];
          }
        }
        if($_POST['arrDate'] == '') {
          $mesg[] = 'arrDate-Please enter arrival date.';
          $error = true;
        } else {
          if(!$this->valid_date($_POST['arrDate'])) {
            $mesg[] = 'arrDate-Please enter a valid date.';
            $error = true;
          } else {
            $temp_arr   = str_replace('/', '-', $_POST['arrDate']);
            $arr_timestamp = strtotime($temp_arr);
            $arrival_date = $_POST['arrDate'];
          }
        }
        if($_POST['depDate'] == '') {
          $mesg[] = 'depDate-Please enter departure date.';
          $error = true;
        } else {
          if(!$this->valid_date($_POST['depDate'])) {
            $mesg[] = 'depDate-Please enter a valid date.';
            $error = true;
          } else {
            $temp_dep   = str_ireplace('/', '-', $_POST['depDate']);
            $dep_timestamp = strtotime($temp_dep);
            if($arr_timestamp > $dep_timestamp) {
              $mesg[] = 'depDate-Departure date cannot be behind Arrival Date.';
              $error = true;
            } else {
              $depart_date = $_POST['depDate'];
            }
          }
        }
        if($_POST['arrHr'] == '' || $_POST['arrHr'] == 'Select') {
          $mesg[] = 'arrHr-Arrival hour is required.';
          $error = true;
        } else {
          if(is_numeric($_POST['arrHr']) && ($_POST['arrHr'] >= 0 && $_POST['arrHr'] <= 23)) {
            $arrival_hour = $_POST['arrHr'];
          } else {
            $mesg[] = 'arrHr-Please enter a valid time.';
            $error = true;
          }
        }
        if($_POST['arrMin'] == '' || $_POST['arrMin'] == 'Select') {
          $mesg[] = 'arrMin-Arrival minute is required.';
          $error = true;
        } else {
          if(is_numeric($_POST['arrMin']) && ($_POST['arrMin'] >= 0 && $_POST['arrMin'] <= 59)) {
            $arrival_min = $_POST['arrMin'];
          } else {
            $mesg[] = 'arrMin-Please enter a valid time.';
            $error = true;
          }
        }
        if($_POST['depHr'] == '' || $_POST['depHr'] == 'Select') {
          $mesg[] = 'depHr-Please enter a departure time.';
          $error = true;
        } else {
          if(is_numeric($_POST['depHr']) && ($_POST['depHr'] >= 0 && $_POST['depHr'] <= 23)) {
            $depart_hour = $_POST['depHr'];
          } else {
            $mesg[] = 'depHr-Please enter a valid time.';
            $error = true;
          }
        }
        if($_POST['depMin'] == '' || $_POST['depMin'] == 'Select') {
          $mesg[] = 'depMin-Please enter a departure time.';
          $error = true;
        } else {
          if(is_numeric($_POST['depMin']) && ($_POST['depMin'] >= 0 && $_POST['depMin'] <= 59)) {
            $depart_min = $_POST['depMin'];
          } else {
            $mesg[] = 'depMin-Please enter a valid time.';
            $error = true;
          }
        }
        if(!isset($_POST['purpVisit'])) {
          $mesg[] = 'purpVisit-Please enter purpose of visit.';
          $error = true;
        } else {
          if($_POST['purpVisit'] == 'Personal' || $_POST['purpVisit'] == 'Official') {
            $purpose_visit = $_POST['purpVisit'];
            if($purpose_visit == 'Official') {
              if($_POST['purpose'] == '' || $_POST['purpose'] == 'State Purpose *') {
                $mesg[] = 'purpose-Please enter purpose.';
                $error = true;
              } else {
                if(!preg_match("/^([a-zA-Z]+\s?)*$/", $_POST['purpose'])) {
                  $mesg[] = 'purpose-Please select a valid purpose.';
                  $error = true;
                } else {
                  $purpose = $_POST['purpose'];
                }
              }
            } else {
              $purpose = '';
            }
          } else {
            $mesg[] = 'purpVisit-Invalid data.';
            $error = true;
          }
        }
        if(!isset($_POST['accLibr'])) {
          $mesg[] = 'accLib-Please enter access library.';
          $error = true;
        } else {
          if($_POST['accLibr'] == 'yes' || $_POST['accLibr'] == 'no') {
            $access_lib = $_POST['accLibr'];
          } else {
            $mesg[] = 'accLib-Please select a valid batch number.';
            $error = true;
          }
        }
        
        $name = $first_name .' '. $last_name;
        
        if(!$error) {
          $arrival_time = $arrival_hour.':'.$arrival_min;
          $depart_time = $depart_hour .':'. $depart_min;
          $query = \Drupal::database()->insert('campus_infra_library');
          $query->fields(['uid','user_type','name','batch_number','organisation','designation','email','country', 'state','city','country_code','mobile_number','arrival_date','arrival_time','departure_date','departure_time','purpose_of_visit','purpose','access_library']);
          $query->values([$user_id, $user_type, $name, $batch_no, $organisation, $designation, $email, $country, $state, $city, $code, $mobile, $arrival_date,$arrival_time,$depart_date, $depart_time,$purpose_visit, $purpose, $access_lib]);
          $query->execute();
          $mesg = 'Thank you for informing us. Your request has been sent to IRMA.  They will revert shortly. Kindly contact iaaec@irma.ac.in / iao@irma.ac.in in case of any issues.';
          $site_mail = \Drupal::config('system.site')->get('mail');
          $mail_data = '<table>
                          <tr><td>Name</td><td>:</td><td>'.$name.'</td></tr>
                          <tr><td>Batch Number</td><td>:</td><td>'.$batch_no.'</td></tr>
                          <tr><td>Organisation</td><td>:</td><td>'.$organisation.'</td></tr>
                          <tr><td>Designation</td><td>:</td><td>'.$designation.'</td></tr>
                          <tr><td>email</td><td>:</td><td>'.$email.'</td></tr>
                          <tr><td>Country</td><td>:</td><td>'.$country.'</td></tr>
                          <tr><td>State</td><td>:</td><td>'.$state.'</td></tr>
                          <tr><td>City</td><td>:</td><td>'.$city.'</td></tr>
                          <tr><td>Mobile Number</td><td>:</td><td>'.$mobile.'</td></tr>
                          <tr><td>Arrival date </td><td>:</td><td>'.$arrival_date.' - '.$arrival_time.'</td></tr>
                          <tr><td>Departure date</td><td>:</td><td>'.$depart_date.' - '.$depart_time.'</td></tr>
                          <tr><td>Purpose of visit</td><td>:</td><td>'.$purpose_visit.'</td></tr>
                          <tr><td>Purpose</td><td>:</td><td>'.$purpose.'</td></tr>
                          <tr><td>Access Library</td><td>:</td><td>'.$access_lib.'</td></tr>
                        </table>';
          $body = 'Thank you for informing us. Your request has been sent to IRMA.  They will revert shortly. Kindly contact iaaec@irma.ac.in / iao@irma.ac.in in case of any issues.<br>' . $mail_data ;
          $body = $this->build_html($first_name, $body);
          MailController::sendCustomMail("",$email,"Alumni Website - Library Access Request",$body); //Mail to user
          $status = 'success';
        }
      } else {
        $mesg   = 'Token mismatch... Please try again.';
        $status = 'tErr';
      }
      return new JsonResponse(['msg' => $mesg, 'status' => $status, 'tok' => $rnd_num]);
    }
    
    public function post_a_job(){
      //echo '<pre>'; print_r($_SESSION); echo '</pre>'; exit;
      $actual_token = $_SESSION['postajobtoken'];
      $hashed       = new PhpassHashedPassword();
      $salt         = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random       = $_POST['token'];
      $token        = md5($salt.$random);
      $randm        = new CustomPagesController;
      $_SESSION['libApp'] = $fresh_token;
      $error = false;
      
      $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
      $name   = $user->get('name')->value;
      $email  = $user->get('mail')->value;
      $uid    = $user->get('uid')->value;
      //$uid  = 99;

      $companyTerms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('organization');
      $jobfunctionsTerms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('job_functions');
      $locationTerms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('location');
      $jobPositionsTerms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('job_position');

      $countriesTerms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('country');
      $statesTerms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('states');
      $citiesTerms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('city');

      $query = \Drupal::database()->select('countries', 'ctr');
      $query->fields('ctr', ['id', 'name']);
      $result = $query->execute()->fetchAll();
      foreach ($result as $key => $value) {
        $countryArr[$value->id] = $value->name;
      }
      $query = \Drupal::database()->select('states', 'st');
      $query->fields('st', ['id', 'name']);
      $result = $query->execute()->fetchAll();
      foreach ($result as $key => $value) {
        $statesArr[$value->id] = $value->name;
      }

      $query = \Drupal::database()->select('cities', 'ct');
      $query->fields('ct', ['id', 'name']);
      $result = $query->execute()->fetchAll();
      foreach ($result as $key => $value) {
        $citiesArr[$value->id] = $value->name;
      }

      $compArr = array();
      $compArr2 = array();
      $jobfunctionsArr = array();
      $jobfunctionsArr2 = array();
      $locArr = array();
      $locArr2 = array();
      $jobposArr = array();
      $errMsg = array();
      $jobPositionArr = array();

      foreach ($countriesTerms as $key => $value) {
        $countriesArr2[$value->name] = $value->tid;
      }
      foreach ($statesTerms as $key => $value) {
        $statesArr2[$value->name] = $value->tid;
      }
      foreach ($citiesTerms as $key => $value) {
        $citiesArr2[$value->name] = $value->tid;
      }

      foreach ($companyTerms as $key => $value) {
        $compArr[] = $value->tid;
        $compArr2[$value->tid] = $value->name;
      }
      foreach ($jobfunctionsTerms as $key => $value) {
        $jobfunctionsArr[] = $value->tid;
        $jobfunctionsArr2[$value->tid] = $value->name;
      }
      foreach ($locationTerms as $key => $value) {
        $locArr[] = $value->tid;
        $locArr2[$value->tid] = $value->name;
      }
      foreach ($jobPositionsTerms as $key => $value) {
        $jobposArr[] = $value->tid;
        $jobPositionArr[$value->tid] = $value->name;
      }

      if($_POST['jobtitle'] == '') {
        $errMsg[] = 'jobtitle-Job Title is required.';
        $error = true;
      } else {
        if(!in_array($_POST['jobtitle'], $jobposArr)) {
          $errMsg[] = 'jobtitle-Invalid Job Title.';
          $error = true; 
        }
      }

      if($_POST['jobfunction'] == '') {
        $errMsg[] = 'function-Function is required.';
        $error = true;
      } else {
        if(!in_array($_POST['jobfunction'], $jobfunctionsArr)) {
          $errMsg[] = 'function-Invalid Job Function.';
          $error = true; 
        }
      }

      if($_POST['orgname'] == '') {
        $errMsg[] = 'organisationname-Organisation Please enter name..';
        $error = true;
      } else {
        /*if(!in_array($_POST['orgname'], $compArr)) {
          $errMsg[] = 'organisationname-Please select a valid organisation name.';
          $error = true; 
        }*/
      }

      if($_POST['country'] == '') {
        $errMsg[] = 'countryId-Please select a country.';
        $error = true;
      } else {
        if(is_numeric($countryArr[$_POST['country']])) {
          $errMsg[] = 'countryId-Please select a valid country.';
          $error = true; 
        }
      }
      
      if($_POST['state'] == '') {
        $errMsg[] = 'stateId-Please select a state.';
        $error = true;
      } else {
        if(is_numeric($statesArr[$_POST['state']])) {
          $errMsg[] = 'stateId-Please select a valid state.';
          $error = true; 
        }
      }

      if($_POST['city'] == '') {
        $errMsg[] = 'cityId-Please select a city.';
        $error = true;
      } else {
        if(is_numeric($citiesArr[$_POST['city']])) {
          $errMsg[] = 'cityId-Please select a valid city.';
          $error = true; 
        }
      }

      if((isset($_POST['minexp']) && !empty($_POST['minexp'])) && !is_numeric($_POST['minexp'])) {
        $errMsg[] = 'minexp-Min Please enter experience..';
        $error = true; 
      } 

      if((isset($_POST['maxexp']) && !empty($_POST['maxexp'])) && !is_numeric($_POST['maxexp'])) {
        $errMsg[] = 'maxexp-Max Please enter experience..';
        $error = true; 
      } 
      
      if($_POST['jobdesc'] == '') {
        $errMsg[] = 'jobdesc-Job Description is required.';
        $error = true;
      } else {
        if(!$this->valid_name($_POST['jobdesc'])){
          $errMsg[] = 'jobdesc-Please enter valid details in description.';
          $error = true;
        }
      }

      if($error == true){
        $mesg = "Validation Errors";
        $status = "error";
      } else {
        if($actual_token==$token){
          //unset($_SESSION['postajobtoken']);

          $mailparams = $valArr = array(
            'type' => 'jobs',
            'title'=> $jobPositionArr[$_POST['jobtitle']],
            'body' => $_POST['jobdesc'],
            'uid'  =>$uid,
            'field_company' =>[$_POST['orgname']],
            'field_job_functions' =>[$_POST['jobfunction']],
            'field_country' =>$countriesArr2[$countryArr[$_POST['country']]],
            'field_state' =>$statesArr2[$statesArr[$_POST['state']]],
            'field_city' =>$citiesArr2[$citiesArr[$_POST['city']]],
            'field_job_positions' =>[$_POST['jobtitle']],
            'status' => FALSE,
          );
          if(isset($_POST['uploadid']) && !empty($_POST['uploadid'])){
            $valArr['field_attach_file'] = ['target_id'=>$_POST['uploadid'],'alt' => $jobPositionArr[$_POST['jobtitle']],
            'title' => $jobPositionArr[$_POST['jobtitle']]];
          }
          if(isset($_POST['minexp']) && !empty($_POST['minexp'])){
            $mailparams['field_experience'] = $valArr['field_experience'] = $_POST['minexp'];
          }
          if(isset($_POST['maxexp']) && !empty($_POST['maxexp'])){
            $mailparams['field_maximum_experience'] = $valArr['field_maximum_experience'] = $_POST['maxexp'];
          }
          $node = Node::create($valArr);
          $node->save();
          $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
          $name   = $user->get('name')->value;
          $user_email  = $user->get('mail')->value;
          
          $mailparams['name'] = $name;
          $mailparams['field_company'] = $compArr2[$_POST['orgname']];
          $mailparams['field_job_functions'] = $jobfunctionsArr2[$_POST['jobfunction']];
          $mailparams['field_city'] = $citiesArr[$_POST['city']];
          $mailparams['field_job_positions'] = $jobPositionArr[$_POST['jobtitle']];
          $body = MailController::getEmailTemplates("job-post",$mailparams);
          $site_mail = \Drupal::config('system.site')->get('mail');
          MailController::sendCustomMail("",$user_email,"Alumni Website - Job Post",$body); //Mail to user
          MailController::sendCustomMail("",$site_mail,"Alumni Website - Job Post",$body); //Mail to admin

          $mesg = 'Thank you for posting a job. Once the moderator approves the job, you will receive a notification email & the job will be displayed on the website.';
          $status = 'success';
        } else {
          $mesg = "Token mismatch";
          $errMsg[] = "csrftoken-Oops something went wrong. Please refresh page and try again.";
          $errMsg[] = "$actual_token==$token";
          $status = "error";
        }
      }

      return new JsonResponse(['msg' => $mesg, 'status' => $status, 'tok' => $rnd_num,'error'=>$errMsg]);

    }

    public function user_login() {
      global $base_url;
      $red_url      = $_POST['ref']; 
      $error        = false;
      $status       = 'failed';
      $mesg         = array();
      $actual_token = $_POST['random'];
      $hashed       = new PhpassHashedPassword();
      $salt         = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random       = trim($_POST['csrf']);
      $token        = md5($salt.$random);
      $randm        = new CustomPagesController;
      $rnd_num      = $hashed->hash($randm->generate_random_string());
      $fresh_token  = md5($salt.$rnd_num);
      $_SESSION['custLog'] = $fresh_token;
      
      if($actual_token == $token) {
        if($_POST['email'] == '') {
          $mesg[] = 'emailLgn-Please enter an email id';
          $error  = true;
        } else {
          if(!$this->valid_mail($_POST['email'])) {
            $mesg[] = 'emailLgn-Please enter a valid email id';
            $error  = true;
          } else {
            $email = trim($_POST['email']);
          }
        }
        if($_POST['password'] == '') {
          $mesg[] = 'passwordLgn-Please enter a password';
          $error  = true;
        } else {
          $password = trim($_POST['password']);
        }
        $query = \Drupal::database()->select('users_field_data', 'ufd');
        $query->fields('ufd', ['uid','access','login','pass']);
        $query->condition('ufd.mail', $email);
        $query->range(0, 1);
        $row = $query->execute()->fetchAssoc();
        if(!$row) {
          if($this->valid_mail($_POST['email']) && ($_POST['email'] != '' && $_POST['password'] != '')) {
            $mesg[] = 'loginBtn-User does not exist';
            $error  = true;
          }
        } else {
          $have_logged_in = $row['login'];
          $last_accessed  = $row['access'];
          $response = new \stdClass();
          $pass = $this->passwordHasher->check($password, $row['pass']);
          $response->password = $pass;
          if($response->password) {
            $user = User::load($row['uid']);
            user_login_finalize($user);
            $logged_in = \Drupal::currentUser()->isAuthenticated();
            if($logged_in) {
              $status = 'success';
            }
          } else {
            if($_POST['email'] != '' && $_POST['password'] != '') {
              $mesg[] = 'passwordLgn-Incorrect Password';
              $error  = true;
            }
          }

        }
      } else {
        $mesg   = 'loginBtn-Token Mismatch - Please Try Again.';
      }
      return new JsonResponse(['msg' => $mesg, 'status' => $status, 'tok' => $rnd_num, 'rndTok' => $fresh_token, 'redUrl' => $red_url]);
    }
    
    public function give_to_iaa_apply() {
      $error        = false;
      $actual_token = $_SESSION['iaa'];
      $status       = 'failed';
      $hashed       = new PhpassHashedPassword();
      $salt         = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random       = $_POST['token'];
      $token        = md5($salt.$random);
      $randm        = new CustomPagesController;
      $rnd_num      = $hashed->hash($randm->generate_random_string());
      $fresh_token  = md5($salt.$rnd_num);

      $_SESSION['iaa'] = $fresh_token;
      $user_data  = explode('-', $_POST['userData']);
      $user_id    = $user_data[1];
      $user_type  = $user_data[0];
      
      if($token == $actual_token) {
        if(trim($_POST['fName']) == '') {
          $mesg[] = 'fName-Please enter first name';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['fName'])) {
            $mesg[] = 'fName-Please enter valid first name';
            $error = true;
          } else {
            $first_name = $_POST['fName'];
          }
      }
        if(trim($_POST['lName']) == '') {
          $mesg[] = 'lName-Please enter Last Name';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['lName'])) {
            $mesg[] = 'lName-Please enter valid last name';
            $error = true;
          } else {
            $last_name  = $_POST['lName'];
          }
        }
        if(trim($_POST['mail']) == '') {
          $mesg[] = 'mail-Please enter email id';
          $error = true;
        } else {
          if(!$this->valid_mail($_POST['mail'])) {
            $mesg[] = 'mail-Please enter a valid email id';
            $error = true;
          } else {
            $email  = $_POST['mail'];
          }
        }
           
        if(trim($_POST['telCode']) == '') {
          $mesg[] = 'telCode-Please enter a valid country code.';
          $error = true;
        } else {
          if(!$this->country_code(trim($_POST['telCode']))) {
            $mesg[] = 'telCode-Please select a valid country code';
            $error = true;
          } else {
            $code = trim($_POST['telCode']);
          }
        }
        if(!is_numeric(trim($_POST['mobileNo']))) {
          $mesg[] = 'mobileNo-Please enter valid mobile number.';
          $error = true;
        } elseif(strlen(trim($_POST['mobileNo'])) > 15) {
          $mesg[] = 'mobileNo-Please enter valid mobile number.';
          $error = true;
        } else {
          $mobile = trim($_POST['mobileNo']);
        }

        if(trim($_POST['batch']) == '' || trim($_POST['batch']) == 'Select') {
          $mesg[] = 'batch-Plese select batch number';
          $error = true;
        } else {
          if(!$this->valid_input(trim($_POST['batch']))) {
            $mesg[] = 'batch-Please select a valid batch number';
            $error = true;
          } else {
            $batch_number = trim($_POST['batch']);
          }
        }
        if(trim($_POST['addr1']) != '') {
          if(!$this->valid_address($_POST['addr1'])) {
            $mesg[] = 'addr1-Please enter valid address';
            $error = true;
          } else {
            $addr1 = trim($_POST['addr1']);
          }
        }
        if(trim($_POST['addr2']) != '') {
          if(!$this->valid_address($_POST['addr2'])) {
            $mesg[] = 'addr2-Please enter valid address';
            $error = true;
          } else {
            $addr2 = trim($_POST['addr2']);
          }
        }
        if(trim($_POST['addr3']) != '') {
          if(!$this->valid_address($_POST['addr3'])) {
            $mesg[] = 'addr3-Please enter valid address';
            $error = true;
          } else {
            $addr3 = trim($_POST['addr3']);
          }
        }
        if(trim($_POST['organisation']) == '' || trim($_POST['organisation']) == 'Select') {
          $mesg[] = 'organisation-Please select organisation.';
          $error = true;
        } else {
          if(!$this->valid_input(trim($_POST['organisation']))) {
            $mesg[] = 'organisation-Please select a valid organisation';
            $error = true;
          } else {
            $organisation = trim($_POST['organisation']);
          }
        }
        if(trim($_POST['designation']) == '') {
          $mesg[] = 'designation-Please enter designation.';
          $error = true;
        } else {
          if(!$this->valid_input(trim($_POST['designation']))) {
            $mesg[] = 'designation-Please select a valid designation';
            $error = true;
          } else {
            $designation = trim($_POST['designation']);
          }
        }
        
        if(trim($_POST['country']) == '' || trim($_POST['country']) == 'Select' || trim($_POST['country']) == 'Select Country') {
          $mesg[] = 'countries-Please select a country.';
          $error = true;
        } else {
          if(!$this->valid_country(trim($_POST['country']))) {
            $mesg[] = 'countries-Please select a valid country.';
            $error = true;
          } else {
            $country = trim($_POST['country']);
          }
        }
        if(trim($_POST['citie']) == '' || trim($_POST['citie']) == 'Select' || trim($_POST['citie'])=='Select City') {
          $mesg[] = 'cities-Please select a city.';
          $error = true;
        } else {
          if(!$this->valid_country(trim($_POST['citie']))) {
            $mesg[] = 'cities-Please select a valid city.';
            $error = true;
          } else {
            $city = trim($_POST['citie']);
          }
        }
        if(trim($_POST['state']) == '' || trim($_POST['state']) == 'Select' || trim($_POST['state']) == 'Select State') {
          $mesg[] = 'states-Please select a state.';
          $error = true;
        } else {
          if(!$this->valid_country(trim($_POST['state']))) {
            $mesg[] = 'states-Please select a valid state.';
            $error = true;
          } else {
            $state = trim($_POST['state']);
          }
        }
        if(trim($_POST['cCntry']) == '' || trim($_POST['cCntry']) == 'Select' || trim($_POST['cCntry']) == 'Select Country') {
          $mesg[] = 'cCntry-Please select a country.';
          $error = true;
        } else {
          if(!$this->valid_country(trim($_POST['cCntry']))) {
            $mesg[] = 'cCntry-Please select a valid country.';
            $error = true;
          } else {
            $c_country = trim($_POST['cCntry']);
          }
        }
        if(trim($_POST['ccity']) == '') {
          $mesg[] = 'ccity-Please select a city.';
          $error = true;
        } else {
          if(!$this->valid_country(trim($_POST['ccity']))) {
            $mesg[] = 'ccity-Please select a valid city.';
            $error = true;
          } else {
            $c_city = trim($_POST['ccity']);
          }
        }
        if(trim($_POST['province']) == '') {
          $mesg[] = 'province-Province is required';
          $error = true;
        } else {
          if(!$this->valid_country(trim($_POST['province']))) {
            $mesg[] = 'province-Please select a valid province';
            $error = true;
          } else {
            $province = trim($_POST['province']);
          }
        }
        if(trim($_POST['pCode']) == '') {
          $mesg[] = 'pCode-Postal code is required';
          $error = true;
        } else {  
          if(!is_numeric(trim($_POST['pCode']))) {
            $mesg[] = 'pCode-Please select a valid Postal code';
            $error = true;
          } else {
            $postal_code = trim($_POST['pCode']);
          }
        }
        if(trim($_POST['giveTo']) == '' || trim($_POST['giveTo']) == 'Select') {
          $mesg[] = 'giveTo-Give to is required';
          $error = true;
        } else {
          if(!$this->valid_input(trim($_POST['giveTo']))) {
            $mesg[] = 'giveTo-Please select a valid input';
            $error = true;
          } else {
            $give_to = trim($_POST['giveTo']);
          }
        }
        if(trim($_POST['payPurpose']) != '') {
          if(!$this->valid_input(trim($_POST['payPurpose']))) {
            $mesg[] = 'payPurpose-Please select a valid Payment Purpose';
            $error = true;
          } else {
            $pay_purpose = trim($_POST['payPurpose']);
          }
        }
        if(trim($_POST['pan']) == '') {
          $mesg[] = 'pan-Plese enter PAN number';
          $error = true;
        } else {
          if(!$this->valid_PAN(trim($_POST['pan']))) {
            $mesg[] = 'pan-Please enter a valid PAN';
            $error = true;
          } else {
            $pan_no = trim($_POST['pan']);
          }
        }
        if(trim($_POST['donAmt']) == '') {
          $mesg[] = 'donAmt-Please enter a donation amount';
          $error = true;
        } else {
          if(!is_numeric(trim($_POST['donAmt']))) {
            $mesg[] = 'donAmt-Please select a valid batch number';
            $error = true;
          } else {
            $donation_amount = trim($_POST['donAmt']);
          }
        }
        if($_POST['anonDonate']) {
          $anon_donate = 'Yes';
        } else {
          $anon_donate = 'Yes';
        }
        
        if(!$error) {
          $name = $first_name .' '. $last_name;
          $contact_no = $code.'-'.$mobile;
          $query = \Drupal::database()->insert('giveto_iaa');
          $query->fields(['uid','user_type','name','batch_number','organisation','designation','email','country','state','city','contact_no','postal_addr1','postal_addr2','postal_addr3','postal_city','postal_state','postal_country','postal_code','give_to','payment_purpose','pan_no','donation_amt','anonymous_gift']);
          $query->values([$user_id,$user_type,$name,$batch_number,$organisation,$designation,$email,$country,$state,$city,$contact_no,$addr1,$addr2,$addr3,$c_city,$province,$c_country,$postal_code,$give_to,$pay_purpose,$pan_no,$donation_amount,$anon_donate]);
          $query->execute();
          $mesg = 'Thank you for giving. You will now proceed to the payment gateway. You will receive a confirmation email, after the payment is received. Kindly contact iaaec@irma.ac.in / iao@irma.ac.in in case of any issues.';
          $site_mail = \Drupal::config('system.site')->get('mail');
          $mail_data = '<table>
                          <tr><td>Name</td><td>:</td><td>'.$name.'</td></tr>
                          <tr><td>Batch Number</td><td>:</td><td>'.$batch_number.'</td></tr>
                          <tr><td>Organisation</td><td>:</td><td>'.$organisation.'</td></tr>
                          <tr><td>Designation</td><td>:</td><td>'.$designation.'</td></tr>
                          <tr><td>email</td><td>:</td><td>'.$email.'</td></tr>
                          <tr><td>Country</td><td>:</td><td>'.$country.'</td></tr>
                          <tr><td>State</td><td>:</td><td>'.$state.'</td></tr>
                          <tr><td>City</td><td>:</td><td>'.$city.'</td></tr>
                          <tr><td>Mobile Number</td><td>:</td><td>'.$mobile.'</td></tr>
                          <tr><td>Postal Address</td><td>:</td><td>'.$addr1.'<br>'.$addr2.'<br>'.$addr3.'</td></tr>
                          <tr><td>City</td><td>:</td><td>'.$c_city.'</td></tr>
                          <tr><td>State/Province</td><td>:</td><td>'.$province.'</td></tr>
                          <tr><td>Country</td><td>:</td><td>'.$c_country.'</td></tr>
                          <tr><td>Postal Code</td><td>:</td><td>'.$postal_code.'</td></tr>
                          <tr><td>Give to</td><td>:</td><td>'.$give_to.'</td></tr>
                          <tr><td>Payment Purpose</td><td>:</td><td>'.$pay_purpose.'</td></tr>
                          <tr><td>Permanent Account Number</td><td>:</td><td>'.$pan_no.'</td></tr>
                          <tr><td>Donation Amount</td><td>:</td><td>'.$donation_amount.'</td></tr>
                          <tr><td>Donate Anonymously</td><td>:</td><td>'.$anon_donate.'</td></tr>
                        </table>';
          $body = 'Thank you for giving. You will now proceed to the payment gateway. You will receive a confirmation email, after the payment is received. Kindly contact iaaec@irma.ac.in / iao@irma.ac.in in case of any issues.<br>' . $mail_data ;
          
          $body = $this->build_html($first_name, $body);
          MailController::sendCustomMail("",$email,"Alumni Website - Give To IAA Submission",$body); //Mail to user
          $status = 'success';
        } 
      
      } else {
        $mesg[] = 'messages-Token mismatch';
        $error = true;
      }

      return new JsonResponse(['mesg' => $mesg, 'status' => $status, 'tok' => $rnd_num]);
    }
    
    public function event_registration() {
      $error        = false;
      $actual_token = $_SESSION['evntReg'];
      $status       = 'failed';
      $hashed       = new PhpassHashedPassword();
      $salt         = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random       = $_POST['token'];
      $token        = md5($salt.$random);
      $randm        = new CustomPagesController;
      $rnd_num      = $hashed->hash($randm->generate_random_string());
      $fresh_token  = md5($salt.$rnd_num);

      $_SESSION['evntReg'] = $fresh_token;
      $user_data  = explode('-', $_POST['userData']);
      $user_id    = $user_data[1];
      $user_type  = $user_data[0];
      
      if($token == $actual_token) {
        if(trim($_POST['evntName']) == '') {
          $mesg[] = 'evntName-Event Please enter name.';
          $error = true;
        } else {
          if(!$this->valid_input($_POST['evntName'])) {
            $mesg[] = 'evntName-Please enter valid event name';
            $error = true;
          } else {
            $event_name = $_POST['evntName'];
          }
        }
        if(trim($_POST['fName']) == '') {
          $mesg[] = 'fName-Please enter first name';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['fName'])) {
            $mesg[] = 'fName-Please enter valid first name';
            $error = true;
          } else {
            $first_name = $_POST['fName'];
          }
        }
        if(trim($_POST['lName']) == '') {
          $mesg[] = 'lName-Please enter Last Name';
          $error = true;
        } else {
          if(!$this->valid_name($_POST['lName'])) {
            $mesg[] = 'lName-Please enter valid last name';
            $error = true;
          } else {
            $last_name  = $_POST['lName'];
          }
        }
        if(trim($_POST['mail']) == '') {
          $mesg[] = 'mail-Please enter email id';
          $error = true;
        } else {
          if(!$this->valid_mail($_POST['mail'])) {
            $mesg[] = 'mail-Please enter a valid email id';
            $error = true;
          } else {
            $email  = $_POST['mail'];
          }
        }
           
        if(trim($_POST['telCode']) == '') {
          $mesg[] = 'telCode-Please enter a valid country code.';
          $error = true;
        } else {
          if(!$this->country_code(trim($_POST['telCode']))) {
            $mesg[] = 'telCode-Please select a valid country code';
            $error = true;
          } else {
            $code = trim($_POST['telCode']);
          }
        }
        if(!is_numeric(trim($_POST['mobileNo']))) {
          $mesg[] = 'mobileNo-Please enter valid mobile number.';
          $error = true;
        } elseif(strlen(trim($_POST['mobileNo'])) > 15) {
          $mesg[] = 'mobileNo-Please enter valid mobile number.';
          $error = true;
        } elseif($_POST['mobileNo']==0) {
          $mesg[] = 'mobileNo-Please enter valid mobile number.';
          $error = true;
        } else {
          $mobile = trim($_POST['mobileNo']);
        }

        if(trim($_POST['batch']) == '' || trim($_POST['batch']) == 'Select') {
          $mesg[] = 'batch-Plese select batch number';
          $error = true;
        } else {
          if(!$this->valid_input(trim($_POST['batch']))) {
            $mesg[] = 'batch-Please select a valid batch number';
            $error = true;
          } else {
            $batch_number = trim($_POST['batch']);
          }
        }
        if(trim($_POST['organisation']) == '' || trim($_POST['organisation']) == 'Select') {
          $mesg[] = 'organisation-Please select organisation.';
          $error = true;
        } else {
          if(!$this->valid_input(trim($_POST['organisation']))) {
            $mesg[] = 'organisation-Please select a valid batch number';
            $error = true;
          } else {
            $organisation = trim($_POST['organisation']);
          }
        }
        if(trim($_POST['designation']) == '') {
          $mesg[] = 'designation-Please enter designation.';
          $error = true;
        } else {
          if(!$this->valid_input(trim($_POST['designation']))) {
            $mesg[] = 'designation-Please select a valid batch number';
            $error = true;
          } else {
            $designation = trim($_POST['designation']);
          }
        }
        
        if(trim($_POST['country']) == '' || trim($_POST['country']) == 'Select') {
          $mesg[] = 'countries-Please select a country.';
          $error = true;
        } else {
          if(!$this->valid_country(trim($_POST['country']))) {
            $mesg[] = 'countries-Please select a valid country.';
            $error = true;
          } else {
            $country = trim($_POST['country']);
          }
        }
        if(trim($_POST['cities']) == '' || trim($_POST['cities']) == 'Select') {
          $mesg[] = 'cities-Please select a city.';
          $error = true;
        } else {
          if(!$this->valid_country(trim($_POST['cities']))) {
            $mesg[] = 'cities-Please select a valid city.';
            $error = true;
          } else {
            $city = trim($_POST['cities']);
          }
        }
        if(trim($_POST['state']) == '' || trim($_POST['state']) == 'Select') {
          $mesg[] = 'states-Please select a state.';
          $error = true;
        } else {
          if(!$this->valid_country(trim($_POST['state']))) {
            $mesg[] = 'states-Please select a valid state.';
            $error = true;
          } else {
            $state = trim($_POST['state']);
          }
        }
        if(trim($_POST['arrDate']) == '') {
          $mesg[] = 'arrDate-Please enter a date';
          $error = true;
        } else {
          if(!$this->valid_date(trim($_POST['arrDate']))) {
            $mesg[] = 'arrDate-Please enter a valid date.';
            $error = true;
          } else {
            $arr_date = trim($_POST['arrDate']);
          }
        }
        if(trim($_POST['pickUpReq']) == '') {
          $mesg[] = 'pickUpRad-Please enter pick up';
          $error = true;
        } else {
          if(strtolower($_POST['pickUpReq']) == 'yes' || strtolower($_POST['pickUpReq']) == 'no') {
            $pickup_reqd = trim($_POST['pickUpReq']);
          } else {
            $mesg[] = 'pickUpRad-Please enter a valid number.';
            $error = true;
          }
        }
        if(strtolower($_POST['pickUpReq']) == 'yes') {
          if($_POST['pickUpLox'] == '') {
            $mesg[] = 'pickUpLox-Please enter a pick up locaton.';
            $error = true;
          } else {
            if(!$this->valid_input($_POST['pickUpLox'])) {
              $mesg[] = 'pickUpLox-Please enter a valid pick up location.';
              $error = true;
            } else {
              $pickup_lox = $_POST['pickUpLox'];
            }
          }
        } else {
          $pickup_lox = '';
        }
        
        if(trim($_POST['departDate']) == '') {
          $mesg[] = 'departDate-Please enter a date';
          $error = true;
        } else {
          if(!$this->valid_date(trim($_POST['departDate']))) {
            $mesg[] = 'departDate-Please enter a valid date.';
            $error = true;
          } else {
            $dep_date = trim($_POST['departDate']);
          }
        }
        if(trim($_POST['dropReq']) == '') {
          $mesg[] = 'dropReq-Please enter drop field';
          $error = true;
        } else {
          if(strtolower($_POST['dropReq']) == 'yes' || strtolower($_POST['dropReq']) == 'no') {
            $drop_reqd = trim($_POST['dropReq']);
          } else {
            $mesg[] = 'dropReq-Please enter a valid number';
            $error = true;
          }
        }
        if(strtolower($_POST['dropReq']) == 'yes') {
          if($_POST['dropLox'] == '') {
            $mesg[] = 'dropRad-Please enter a drop location.';
            $error = true;
          } else {
            if(!$this->valid_input($_POST['dropLox'])) {
              $mesg[] = 'dropRad-Please enter a valid number';
              $error = true;
            } else {
              $drop_lox = $_POST['dropLox'];
            }
          }
        } else {
          $drop_lox = '';
        }
        if($_POST['noFGuests'] == '') {
          $mesg[] = 'noFGuests-Pleaes enter no.of guests';
          $error = true;
        } else {
          if($_POST['noFGuests'] > 0 && $_POST['noFGuests'] <= 10) {
            $num_guests = $_POST['noFGuests'];
          } else {
            $mesg[] = 'noFGuests-Please select a valid number';
            $error = true;
          }
        }
        if(trim($_POST['foodPref']) == '') {
          $mesg[] = 'foodiePref-Please enter food preference';
          $error = true;
        } else {
          if(strtolower($_POST['foodPref']) == 'veg' || strtolower($_POST['foodPref']) == 'non-veg') {
            $food_pref = trim($_POST['foodPref']);
          } else {
            $mesg[] = 'foodiePref-Please select a valid batch number';
            $error = true;
          }
        }
        if(trim($_POST['stayPref']) == '') {
          $mesg[] = 'accomoPref-Please enter stay preference';
          $error = true;
        } else {
          if(strtolower($_POST['stayPref']) == 'etdc' || strtolower($_POST['stayPref']) == 'irma hostel' || strtolower($_POST['stayPref']) == 'own arrangements') {
            $stay_pref = trim($_POST['stayPref']);
          } else {
            $mesg[] = 'accomoPref-Please select a valid number';
            $error = true;
          }
        }
        
        if(strtolower($_POST['stayPref']) == 'irma hostel') {
          if($_POST['prefHostel'] != '') {
            if(!$this->valid_input($_POST['prefHostel'])) {
              $mesg[] = 'prefHostel-Please select a valid number';
              $error = true;
            } else {
              $pref_hostel = $_POST['prefHostel'];
            }
          }
        } else {
          $pref_hostel = '';
        }
            
        if(!$error) {
          $name = $first_name .' '. $last_name;
          $contact_no = $code.'-'.$mobile;
          $query = \Drupal::database()->insert('events_registration');
          $query->fields(['uid','user_type','event_name','name','batch_number','organisation','designation','email','country','state','city','contact_no','arrival_date','pickup_reqd','pickup_loc','depart_date','drop_reqd','drop_loc','num_guests','food_pref','accom_pref','hostel_pref']);
          $query->values([$user_id,$user_type,$event_name,$name,$batch_number,$organisation,$designation,$email,$country,$state,$city,$contact_no,$arr_date,$pickup_reqd,$pickup_lox,$dep_date,$drop_reqd,$drop_lox,$num_guests,$food_pref,$stay_pref,$pref_hostel]);
          $query->execute();
          $status = 'success';
          $mesg = 'Thank you for registering for '.$event_name.'. You will receive a confirmation mail. Kindly contact iaaec@irma.ac.in / iao@irma.ac.in in case of any issue. <br>We look forward to seeing you there! ';
          $site_mail = \Drupal::config('system.site')->get('mail');
          $mail_data = '<table>
                          <tr><td>Name</td><td>:</td><td>'.$name.'</td></tr>
                          <tr><td>Event Name</td><td>:</td><td>'.$event_name.'</td></tr>
                          <tr><td>Batch Number</td><td>:</td><td>'.$batch_number.'</td></tr>
                          <tr><td>Organisation</td><td>:</td><td>'.$organisation.'</td></tr>
                          <tr><td>Designation</td><td>:</td><td>'.$designation.'</td></tr>
                          <tr><td>email</td><td>:</td><td>'.$email.'</td></tr>
                          <tr><td>Country</td><td>:</td><td>'.$country.'</td></tr>
                          <tr><td>State</td><td>:</td><td>'.$state.'</td></tr>
                          <tr><td>City</td><td>:</td><td>'.$city.'</td></tr>
                          <tr><td>Mobile Number</td><td>:</td><td>'.$mobile.'</td></tr>
                          <tr><td>Arrival date</td><td>:</td><td>'.$arr_date.'</td></tr>
                          <tr><td>Pick Up Required</td><td>:</td><td>'.$pickup_reqd.'</td></tr>
                          <tr><td>Pick Up Location</td><td>:</td><td>'.$pickup_lox.'</td></tr>
                          <tr><td>Departure date</td><td>:</td><td>'.$dep_date.'</td></tr>
                          <tr><td>Drop Required</td><td>:</td><td>'.$drop_reqd.'</td></tr>
                          <tr><td>Drop Location</td><td>:</td><td>'.$drop_lox.'</td></tr>
                          <tr><td>Number of guests</td><td>:</td><td>'.$num_guests.'</td></tr>
                          <tr><td>Food preference</td><td>:</td><td>'.$food_pref.'</td></tr>
                          <tr><td>Stay Preference</td><td>:</td><td>'.$stay_pref.'</td></tr>
                          <tr><td>Preffered Hostel Room</td><td>:</td><td>'.$pref_hostel.'</td></tr>
                        </table>';
                        
          $body = 'Thank you for registering for '.$event_name.'. You will receive a confirmation mail. Kindly contact iaaec@irma.ac.in / iao@irma.ac.in in case of any issue. <br>We look forward to seeing you there! <br>' . $mail_data ;
          
          $body = $this->build_html($first_name, $body);
          MailController::sendCustomMail("",$email,"Alumni Website - Event Registration",$body); //Mail to user
          $mesg = 'Thank you for registering for '.$event_name.'. You will receive a confirmation mail. Kindly contact iaaec@irma.ac.in / iao@irma.ac.in in case of any issue.<br>We look forward to seeing you there!';
        } 
      } else {
        $mesg[] = 'messages-Token mismatch';
        $error = true;
      }

      return new JsonResponse(['mesg' => $mesg, 'status' => $status, 'tok' => $rnd_num]);
    }
    
    public function change_password() {
      $actual_token = $_SESSION['chngPass'];
      $status       = 'failed';
      $error        = false;
      $hashed       = new PhpassHashedPassword();
      $salt         = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random       = $_POST['token'];
      $token        = md5($salt.$random);
      $randm        = new CustomPagesController;
      $rnd_num      = $hashed->hash($randm->generate_random_string());
      $fresh_token  = md5($salt.$rnd_num);

      $_SESSION['chngPass'] = $fresh_token;
      if($actual_token == $token) {
        if($_POST['old_pass'] == '') {
          $mesg[] = 'oldPass-Current Please enter password';
          $error = true;
        } else {
          $old_password = trim($_POST['old_pass']);
        }
        if($_POST['new_pass'] == '') {
          $mesg[] = 'newPass-New Please enter password';
          $error = true;
        } else {
          $new_password = trim($_POST['new_pass']);
        }
        if($_POST['con_pass'] == '') {
          $mesg[] = 'confirmPass-Please enter confirm  password';
          $error = true;
        } else {
          if($_POST['new_pass'] != '' && ($_POST['con_pass'] != $_POST['new_pass'])) {
            $mesg[] = 'newPass-New password and confirm password does not match';
            $error = true;
          }
        }
        $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
        $name     = $user->get('name')->value;
        $email    = $user->get('mail')->value;
        $uid      = $user->get('uid')->value;
        $db_pass  = $user->get('pass')->value;
        if($_POST['old_pass'] != '') {
          $response = new \stdClass();
          $pass = $this->passwordHasher->check($old_password, $db_pass);
          $response->password = $pass;
          if($response->password) {
            // password matches
            $mesg[] = 'oldPass-correct Password';
          } else {
            // password does not match
            $mesg[] = 'oldPass-Incorrect Password';
            $error = true;
          }
        }
        
        if(!$error) {
          $hashed_password = new PhpassHashedPassword();
          $new_password = $hashed_password->hash($new_password);
          $query = \Drupal::database()->update('users_field_data');
          $query->fields(['pass' => $new_password]);
          $query->condition('uid', $uid);
          $query->execute();
          $status = 'success';
          $mesg = 'Your password has been changed successfully.'; 
        }
      } else {
        $mesg[] = 'changePwdP-Token Mismatch... Please Try Again';
        $error = true;
      }
      return new JsonResponse(['mesg' => $mesg, 'status' => $status, 'tok' => $rnd_num]);
    }
    
    public function forgot_password() {
      global $base_url;
      $actual_token = $_SESSION['forgPass'];
      $status       = 'failed';
      $error        = false;
      $hashed       = new PhpassHashedPassword();
      $salt         = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random       = $_POST['token'];
      $token        = md5($salt.$random);
      $randm        = new CustomPagesController;
      $rnd_num      = $hashed->hash($randm->generate_random_string());
      $fresh_token  = md5($salt.$rnd_num);

      $_SESSION['forgPass'] = $fresh_token;
      
      if($actual_token == $token) {        
        if(trim($_POST['email']) == '') {
          $mesg[] = 'fpEmail-Please enter email id.';
          $error = true;
        } else {
          if(!$this->valid_mail(trim($_POST['email']))) {
            $mesg[] = 'fpEmail-Please enter valid email id.';
            $error = true;
          } else {
            $email = trim($_POST['email']);
          }
        }
      } else {
        //Token mismatch
        $mesg[] = 'changePwdP-Token Mismatch... Please Try Again';
        $error = true;
      }
      if(!$error) {
        $query = \Drupal::database()->select('users_field_data', 'ufd');
        $query->addField('ufd', 'uid');
        $query->condition('ufd.mail', $email);
        $query->range(0, 1);
        $uid = $query->execute()->fetchField();
        if($uid) {
          $request_time = \Drupal::time()->getRequestTime();
          $user = User::load($uid);
          $name = $user->name->value;
          $uuid = $user->uuid->value;
          $pass = $user->pass->value;
          $mail_url = $base_url.'/reset-password/'.$uid.'/'.$request_time.'/'.$pass;
          $site_mail = \Drupal::config('system.site')->get('mail');
          $body = 'You have requested to reset your password. Kindly please click <a href="'.$mail_url.'">here</a> to proceed ahead with reseting your password.';
          $body = $this->build_html('user', $body);
          MailController::sendCustomMail("",$email,"Reset Password Request",$body); //Mail to user
          $mesg = 'We have sent an email on your registered email ID. To reset your password, kindly click on the link provided in the email.';
          $status = 'success';
          unset($_SESSION['forgPass']);
        } else {
          $mesg[] = 'fpEmail-Email id mismatch.';
          $error = true;
        } 
      }
      return new JsonResponse(['mesg' => $mesg, 'status' => $status, 'tok' => $rnd_num]); 
    }
    
    public function reset_password() {
      $actual_token = $_SESSION['restPass'];
      $status       = 'failed';
      $error        = false;
      $hashed       = new PhpassHashedPassword();
      $salt         = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random       = $_POST['cToken'];
      $token        = md5($salt.$random);
      $randm        = new CustomPagesController;
      $rnd_num      = $hashed->hash($randm->generate_random_string());
      $fresh_token  = md5($salt.$rnd_num);

      $_SESSION['restPass'] = $fresh_token;
      if($actual_token == $token) {
        if($_POST['newPass'] == '') {
          $mesg[] = 'newPass-New Please enter password';
          $error = true;
        } else {
          $new_password = $_POST['newPass'];
        }
        if($_POST['conPass'] == '') {
          $mesg[] = 'confirmPass-Please enter confirm  password';
          $error = true;
        } else {
          if($_POST['newPass'] != '') {
            if($_POST['newPass'] != $_POST['conPass']) {
              $mesg[] = 'confirmPass-Paswords do not match';
              $error = true;
            } 
          }
        }
        
      } else {
        //Token mismatch
        $mesg[] = 'changePwdP-Token Mismatch... Please Try Again';
        $error = true;
      }
      if(!$error) {
        $temp = explode('-', $_POST['token']);
        $uid  = $temp[0];
        $hashed_password = new PhpassHashedPassword();
        $new_password = $hashed_password->hash($new_password);
        $query = \Drupal::database()->update('users_field_data');
        $query->fields(['pass' => $new_password]);
        $query->condition('uid', $uid);
        $query->execute();
        $status = 'success';
        $mesg = 'Your password has been reset.'; 
      }
      
      return new JsonResponse(['mesg' => $mesg, 'status' => $status, 'tok' => $rnd_num]);
      
    }
    
    public function valid_input($str) {
      if(preg_match("/^[a-zA-Z0-9,.\- \'()]*$/", trim($str))) {
        return true;
      } else {
        return false;
      }
    }
    public function valid_PAN($pan_number) {
      if(!preg_match("/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/", trim($pan_number))) {
        return false;
      } else {
        return true;
      }
    }
    public function numeric($string) {
      if(preg_match("/^[0-9]*$/", trim($string))) {
        return true;
      } else {
        return false;
      }
    }  
    public function valid_text_area_content($string) {
      if(preg_match("/^[a-zA-Z0-9,.!-:& * ()\[\]]*$/", trim($string))) {
        return true;
      } else {
        return false;
      }
    }
    public function valid_country($country) {
      if(preg_match("/^([a-zA-Z.]+\s?)*$/", trim($country))) {
        return true;
      } else {
        return false;
      }
    }
    public function valid_mail($email) {
      if(!filter_var(trim($email), FILTER_VALIDATE_EMAIL) === false) {
        return true;
      } else {
        return false;
      }
    }
    public function valid_url($url) {
      if(!filter_var(trim($url), FILTER_VALIDATE_URL === false)) {
        return true;
      } else {
        return false;
      }
    }
    public function valid_mobile($mobile) {
      if(!preg_match("/^[789][0-9]{9}$/", trim($mobile))) {
        return false;
      } else {
        return true;
      }
    }

    public function valid_qualification($deg) {
      if(preg_match("/^([a-zA-Z.\-]+\s?)*$/", trim($deg))) {
        return true;
      }  else {
        return false;
      }
    }
    public function valid_name($name) {
      if(preg_match("/^([a-zA-Z.\']+\s?)*$/", trim($name))) {
        return true;
      }  else {
        return false;
      }
    }
    public function valid_relation($name) {
      if(preg_match("/^([a-zA-Z\-]+\s?)*$/", trim($name))) {
        return true;
      }  else {
        return false;
      }
    }
    public function valid_job_title($name) {
      if(preg_match("/^([a-zA-Z.\ ]+\s?)*$/", trim($name))) {
        return true;
      }  else {
        return false;
      }
    }

    public function valid_address($address) {
      if(preg_match("/^[a-zA-Z0-9,.!?#\-:& *'\"\/\\\ ()\[\]]*$/", trim($address))) {
        return true;
      } else {
        return false;
      }
    }
    public function valid_design($designation) {
      if(preg_match("/^[a-z][a-z ]*$/i", trim($designation))) {
        return true;
      } else {
        return false;
      }
    }
    public function valid_batch($batch_no) {
      if(preg_match("/^[a-zA-Z0-9 \-]*$/", trim($batch_no))) {
        return true;
      } else {
        return false;
      }
    }
    
    public function valid_organisation($org) {
      if(preg_match("/^([a-zA-Z0-9,.\'&\-()]+\s?)*$/", trim($org))) {
        return true;
      } else {
        return false;
      }
    }
    public function valid_date($date) {
      if(preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/([0-9]{4})$/",trim($date))) {
        return true;
      } else {
        return false;
      }
    }
    public function country_code($code) {
      if(preg_match('/^(\+\d{1,4})/', trim($code))) {
        return true;
      } else {
        return false;
      }
    }
    public function valid_year($year) {
      if(preg_match('/^([0-9]{4})$/', trim($year))) {
        return true;
      } else {
        return false;
      }
    }
    public function valid_month($str) {
      $month = array('January', 'Febraury', 'March' , 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
      if(in_array(trim($str), $month)) {
        return true;
      } else {
        return false;
      }
    }

    public function countries() {
      if(isset($_GET['type']) || !empty($_GET['type'])) {
        $type = $_GET['type'];
        $res = array();
        if($type=='getCountries') {
          $query = \Drupal::database()->select('countries', 'c');
          $query->fields('c', ['id','sortname', 'name', 'phonecode']);
          $countries = $query->execute();
          foreach($countries as $country) {
            //print_r($country);
            $res[] = $country;
          }
          return new JsonResponse(['status'=>'success', 'tp'=>1, 'msg'=>"Countries fetched successfully.", 'result'=> $res]);
        }
      }
    }
    public function cities() {
      $state_id = $_GET['stateId'];
      $res = array();
      $query = \Drupal::database()->select('cities', 'ct');
      $query->fields('ct', ['id', 'name', 'state_id']);
      $query->condition('ct.state_id', $state_id);
      $cities = $query->execute();
      foreach($cities as $city) {
        $res[$city->id] = $city->name;
      }
      return new JsonResponse(['status'=>'success', 'tp'=>1, 'msg'=>"Cities fetched successfully.", 'result'=> $res]);
    }
    
    public function states() {
      if(isset($_GET['countryId']) || !empty($_GET['countryId'])) {
        $countryId = $_GET['countryId'];
        $res = array();
        $query = \Drupal::database()->select('states', 's');
        $query->fields('s', ['id', 'name']);
        $query->condition('s.country_id', $countryId);
        $states = $query->execute();
        foreach($states as $state) {
          $res[$state->id] = $state->name;
        }
        return new JsonResponse(['status'=>'success', 'tp'=>1, 'msg'=>"States fetched successfully.", 'result'=> $res]);
      }
    }
    
    public function get_all_cities() {
      $cities = array();
      $str = $_POST['strTxt'];
      $options = '';
      $query = \Drupal::database()->select('cities', 'ct');
      $query->fields('ct', ['id','name']);
      $query->condition('ct.name', $str. '%', 'LIKE');
      $cts = $query->execute()->fetchAllAssoc('id');
      $options = '';
      foreach($cts as $city) {
        $options .= '<li><a href="javascript:;">'. $city->name . '</a></li>';
      }
      return new JsonResponse($options);
    }

    public function sort_events() {
      $cur_date   = \Drupal::time()->getRequestTime();
      $cur_date   = date('Y-m-d', $cur_date);
      $type   = $_POST['type'];
      $venue  = $_POST['venue'];
      $year   = $_POST['evtYear'];
      $load_more_show = '0';
      if($type == 'archived') {
        $op = '<';
      } else {
        $op = '>=';
      }  
      if($year == '' && $venue != '') {
        //echo '1';
        $opt = 1;
        $query = \Drupal::entityQuery('node')
          ->condition('status', 1)
          ->condition('type', 'events')
          ->condition('field_event_venue.value', $venue)
          ->sort('nid', 'DESC')
          ->range(0,10);
      
      } else if($venue == '' && $year != ''){
        //echo '2';
        $opt = 2;
        $query = \Drupal::entityQuery('node')
          ->condition('status', 1)
          ->condition('type', 'events')
          ->condition('field_event_date.value', $cur_date, $op)
          ->sort('nid', 'DESC')
          ->range(0,10);
      } else {
        //echo '3';
        $opt = 3;
        $query = \Drupal::entityQuery('node')
          ->condition('status', 1)
          ->condition('type', 'events')
          ->condition('field_event_date.value', $cur_date, $op)
          ->condition('field_event_venue.value', $venue)
          ->sort('nid', 'DESC')
          ->range(0,10);
      }
      
      $nids = $query->execute();
      
      if($opt == 1) {
        $cnt  = $query->count()->execute();
        if($cnt > 9) {
          $load_more_show = '1';
        }
      }
      $output = '';
      if($opt == 2 || $opt == 3) {
        $count = 0;
        $cnt_array = array();
        $nodes = entity_load_multiple('node', $nids);
        foreach($nodes as $node) {
          $event_year = date('Y', strtotime($node->field_event_date->value));
          if($event_year == $year) {
            array_push($cnt_array, $node->nid->value);
            if($count < 9) {
              $alias = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$node->nid->value);
              $event_date = date('d M Y', strtotime($node->field_event_date->value));
              $original_image = $node->field_image->entity->getFileUri();
              $style = ImageStyle::load('featured');  // Load the image style configuration entity.
              $image_url = $style->buildUrl($original_image);
              $shortdesc = preg_replace('/\s+?(\S+)?$/', '', substr($node->body->value, 0, 110));
              $output .= '<li rel="'.$node->nid->value.'"><div class="imgSec"><img src="'.$image_url.'" alt=""></div>';
              $output .= '<div class="contentSec"><div class="titleSec"><h4>'.$node->field_event_name->value.'</h4></div>';
              $output .= '<div class="discription">'.$shortdesc.'...</p></div>';
              $output .= '<div class="addrSec"><span class="date">'.$event_date.'</span><span class="location">'.$node->field_event_venue->value.'</span></div>';
              $output .= '<div class="btnSec"><a class="button" href="'.$alias.'">View Details</a><a class="shareBtn" href="javascript:;">Share</a></div>';
              $output .= '</div></li>';
              $count++;
            }
          }
        }
        if(count($cnt_array) > 9) {
          $load_more_show = '1';
        }
      } else {
        $count = 0;
        $nodes = entity_load_multiple('node', $nids);
        foreach($nodes as $node) {
          if($count < 9) {
            $alias = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$node->nid->value);
            $event_date = date('d M Y', strtotime($node->field_event_date->value));
            $original_image = $node->field_image->entity->getFileUri();
            $style = ImageStyle::load('featured');  // Load the image style configuration entity.
            $image_url = $style->buildUrl($original_image);
            $shortdesc = preg_replace('/\s+?(\S+)?$/', '', substr($node->body->value, 0, 110));
            $output .= '<li rel="'.$node->nid->value.'"><div class="imgSec"><img src="'.$image_url.'" alt=""></div>';
            $output .= '<div class="contentSec"><div class="titleSec"><h4>'.$node->field_event_name->value.'</h4></div>';
            $output .= '<div class="discription">'.$shortdesc.'...</p></div>';
            $output .= '<div class="addrSec"><span class="date">'.$event_date.'</span><span class="location">'.$node->field_event_venue->value.'</span></div>';
            $output .= '<div class="btnSec"><a class="button" href="'.$alias.'">View Details</a><a class="shareBtn" href="javascript:;">Share</a></div>';
            $output .= '</div></li>';
            $count++;
          }
        }
      }
      return new JsonResponse(['loadMore' => $load_more_show, 'data' => $output]);
    }
    
    public function alumni_search() {
      global $base_url;
      $name     = trim($_POST['name']);
      $city     = trim($_POST['city']);
      $org      = trim($_POST['org']);
      $industry = trim($_POST['ind']);
      $batchNo  = trim($_POST['batchNum']);
      $gradYr   = trim($_POST['gradYear']);
      $flag     = false;
    
      $sql = "SELECT ufd.uid,ufd.mail,upp.user_picture_target_id,fm.uri,ue.user_type,ue.name,ue.country,ue.state,ue.city,ue.mobile_number,ue.batch_number,ue.course_type,ue.course_name,ue.joining_year,ue.graduating_year,up.nickname,up.hobbies,up.professional_background,up.linkedin_url,up.fun_photo_id
              FROM {users_field_data} ufd
              LEFT JOIN {users_extra} ue ON ue.entity_id=ufd.uid
              LEFT JOIN {users_profile} up ON up.entity_id=ufd.uid
              LEFT JOIN {user__user_picture} upp ON upp.entity_id=ufd.uid
              LEFT JOIN {file_managed} fm ON fm.fid = upp.user_picture_target_id ";
      if($name != '') {
        $sql .= "WHERE ue.name LIKE '%$name%' ";
        $flag = true;
      }
      if($city != '') {
        if($flag) {
          $sql .= " AND ue.city IN ('".$city."') ";
        } else {
          $sql .= " WHERE ue.city IN ('".$city."') ";
        }
        $flag = true;
      }
      if($org != '') {
        $temp = explode(',', $org);
        $first = true;
        foreach($temp as $row) {
          if($first) {
            if($flag) {
              $sql .= " AND (up.professional_background LIKE '%$row%' ";
            } else {
              $sql .= " WHERE (up.professional_background LIKE '%$row%' ";
            }
            $first = false;
          } else {
            $sql .= " OR up.professional_background LIKE '%$row%'";
          }
        }
        $sql .= ")";
      }
      if($industry != '') {
        $temp = explode(',', $industry);
        $first = true;
        foreach($temp as $row) {
          if($first) {
            if($flag) {
              $sql .= " AND (up.professional_background LIKE '%$row%' ";
            } else {
              $sql .= " WHERE (up.professional_background LIKE '%$row%' ";
            }
            $first = false;
          } else {
            $sql .= " OR up.professional_background LIKE '%$row%'";
          }
        }
        $sql .= ")";
      }
      if($batchNo != '') {
        $temp = explode(',', $batchNo);
        $first = true;
        foreach($temp as $row) {
          if($first) {
            if($flag) {
              $sql .= " AND (ue.batch_number = '$row' ";
            } else {
              $sql .= " WHERE (ue.batch_number = '$row' ";
            }
            $first = false;
          } else {
            $sql .= " OR ue.batch_number = '$row'";
          }
        }
        $sql .= ")";
      }
      if($gradYr != '') {
        if($flag) {
          $sql .= " AND ue.graduating_year = '$gradYr' ";
        } else {
          $sql .= " WHERE ue.graduating_year = '$gradYr'";
        }
      }
      //echo $sql; exit;
      $db = \Drupal::database();
      $rows = $db->query($sql);
      $rows->allowRowCount = TRUE;
      
      if ($rows->rowCount() > 0) {
        foreach($rows as $row) {
          $designation  = '';
          $organisation = '';
          $profession = json_decode($row->professional_background);
          foreach($profession as $prof) {
            if($prof->workHereChk == 'true') {
              $designation = $prof->designation;
              $organisation = $prof->organisation;
            }
          }
          $temp = explode('-', $row->mobile_number);
          $country_code = $temp[0];
          if(isset($temp[1])) {
            $mobile = $temp[1];
          } else {
            $mobile = '';
          }
          if($row->uri) {
            $image = file_create_url($row->uri);
          } else {
            $image = $base_url.'/sites/default/files/default.jpg';
          }
          
          if($row->fun_photo_id != NULL) {
            $query = \Drupal::database()->select('file_managed', 'fm');
            $query->addField('fm', 'uri');
            $query->condition('fm.fid', $row->fun_photo_id);
            $query->range(0, 1);
            $furi = $query->execute()->fetchField();
            if($furi) {
              $fun_image = file_create_url($furi);
            } else {
              $fun_image = '';
            }
          } else {
            $fun_image = '';
          }
          $output .= '<li rel="'.$row->uid.'"><div class="imgSec flip_container2"><div class="flip2"><div class="flip_front2"><img src="'.$image.'" alt=""></div>';
          if($fun_image) {
            $output .= '<div class="flip_back2"><img src="'.$fun_image.'" alt=""></div>';
          }
          $output .= '</div></div><div class="contentSec"><div class="titleSec"><h4>'.$row->name.'</h4>';
          $output .= '<p>'.$designation.' <br> '.$organisation.' </p>';
          $output .= '</div><div class="addrs"><p>Batch: <strong>'.$row->batch.' ('.$row->joining_year.' to '.$row->graduating_year.')</strong></p>
                      <p>Course/Dept: <strong>'.$row->course_type.'</strong></p>
                      <p>City: <strong>'.$row->city.', '.$row->state.', '.$country_code.'</strong></p></div>
                      <div class="addrs"><p>Intrests: '.$row->hobbies.'</p><p>Nickname: '.$row->nickname.'</p></div>
                      <div class="contact">
                     <a class="mob">'.$mobile.'</a><a class="socialLink" href="'.$row->linkedin_url.'"></a>
                     </div></div></li>';
        }
      } else {
        $output .= '';
      }
      return new JsonResponse(['loadMore' => $load_more_show, 'data' => $output]);
    }
    
    public function student_search() {
      $loadmore_show = '0';
      $name   = trim($_POST['name']);
      $batch  = trim($_POST['batchNum']);
      $sector = trim($_POST['sector']);
      $workX  = trim($_POST['workExp']);
      $output = '';
      $sql = "SELECT ufd.uid,ufd.mail,upp.user_picture_target_id,fm.uri,ue.user_type,ue.name,ue.country,ue.state,ue.city,ue.mobile_number,ue.batch_number,ue.course_type,ue.course_name,ue.joining_year,up.nickname,up.hobbies,up.linkedin_url,up.fun_photo_id
              FROM {users_field_data} ufd
              LEFT JOIN {users_extra} ue ON ue.entity_id=ufd.uid
              LEFT JOIN {users_profile} up ON up.entity_id=ufd.uid
              LEFT JOIN {user__user_picture} upp ON upp.entity_id=ufd.uid
              LEFT JOIN {file_managed} fm ON fm.fid = upp.user_picture_target_id
              WHERE ue.user_type = 'student'";
      if($name != '') {
        $sql .= "AND ue.name LIKE '%$name%' ";
      }
      if($batch != '') {
        $temp = explode(',', $batch);
        $first = true;
        foreach($temp as $row) {
          if($first) {
            $sql .= " AND (ue.batch_number = '$row' ";
            $first = false;
          } else {
            $sql .= " OR ue.batch_number = '$row'";
          }
        }
        $sql .= ")";
      }
      if($sector != '') {
        $temp = explode(',', $sector);
        $first = true;
        foreach($temp as $row) {
          if($first) {
            $sql .= " AND (up.sector_last_worked LIKE '%$row%' ";
            $first = false;
          } else {
            $sql .= " OR up.sector_last_worked LIKE '%$row%'";
          }
        }
        $sql .= ")";
      }

      if($workX != '') {
        $sql .= " AND up.year_of_experience = '$workX'";
      }
      
      $temp = explode('-', $row->mobile_number);
      if($temp[1] == NULL) {
        $mobile = '';
      } else {
        $mobile = $row->mobile_number;
      }
      
      //echo $sql;
      //echo $mobile;
      $db = \Drupal::database();
      $rows = $db->query($sql);
      $rows->allowRowCount = TRUE;
      
      if ($rows->rowCount() > 0) {
        foreach($rows as $row) {
          if($row->fun_photo_id != NULL) {
            $query = \Drupal::database()->select('file_managed', 'fm');
            $query->addField('fm', 'uri');
            $query->condition('fm.fid', $row->fun_photo_id);
            $query->range(0, 1);
            $furi = $query->execute()->fetchField();
            if($furi) {
              $fun_image = file_create_url($furi);
            } else {
              $fun_image = '';
            }
          } else {
            $fun_image = '';
          }
          if($row->uri) {
            $image = file_create_url($row->uri);
          } else {
            $image = $base_url.'/sites/default/files/default.jpg';
          }
          $output .= '<li rel="'.$row->uid.'"><div class="imgSec flip_container2"><div class="flip2"><div class="flip_front2"><img src="'.$image.'" alt=""></div>';
          if($fun_image) {
            $output .= '<div class="flip_back2"><img src="'.$fun_image.'" alt=""></div>';
          }
          $output .= '</div></div><div class="contentSec"><div class="titleSec"><h4>'.$row->name.'</h4>';
          $output .= '</div><div class="addrs"><p>Batch: <strong>'.$row->batch_number.'</strong></p>
                      <p>Course/Dept: <strong>'.$row->course_type.'</strong></p>
                      <p>Joining Year: <strong>'.$row->joining_year .'</strong></p></div>
                      <div class="addrs"><p>Intrests: '.$row->hobbies.'</p><p>Nickname: '.$row->nickname.'</p></div>
                      <div class="contact">
                     <a class="mob">'.$mobile.'</a><a class="socialLink" href="'.$row->linkedin_url.'" target="_blank"></a>
                     </div></div></li>';
        }
      }
      return new JsonResponse(['loadMore' => $load_more_show, 'data' => $output]);
    }
    
    public function faculty_search() {
      $loadmore_show = '0';
      $name      = trim($_POST['name']);
      $area_grp  = trim($_POST['areaGrp']);
      $subj_grp  = trim($_POST['subjectGrp']);

      $output = '';
      $sql = "SELECT ufd.uid,ufd.mail,upp.user_picture_target_id,fm.uri,ue.user_type,ue.name,ue.mobile_number,up.nickname,up.hobbies,up.linkedin_url,up.fun_photo_id,ue.area_group,ue.subject_group,ue.years_as_faculty
              FROM {users_field_data} ufd
              LEFT JOIN {users_extra} ue ON ue.entity_id=ufd.uid
              LEFT JOIN {users_profile} up ON up.entity_id=ufd.uid
              LEFT JOIN {user__user_picture} upp ON upp.entity_id=ufd.uid
              LEFT JOIN {file_managed} fm ON fm.fid = upp.user_picture_target_id
              WHERE ue.user_type = 'faculty'";
      if($name != '') {
        $sql .= "AND ue.name LIKE '%$name%' ";
      }
      if($subj_grp != '') {
        $temp = explode(',', $subj_grp);
        $first = true;
        foreach($temp as $row) {
          if($first) {
            $sql .= " AND (ue.subject_group ='".$row."' ";
            $first = false;
          } else {
            $sql .= " OR ue.subject_group ='".$row."' ";
          }
        }
        $sql .= ")";
      }
      if($area_grp != '') {
        $temp = explode(',', $area_grp);
        $first = true;
        foreach($temp as $row) {
          if($first) {
            $sql .= " AND (ue.area_group ='".$row."' ";
            $first = false;
          } else {
            $sql .= " OR ue.area_group ='".$row."' ";
          }
        }
        $sql .= ")";
      }
      
      $temp = explode('-', $row->mobile_number);
      if($temp[1] == NULL) {
        $mobile = '';
      } else {
        $mobile = $row->mobile_number;
      }
      
      //echo $sql;
      //echo $mobile;
      $db = \Drupal::database();
      $rows = $db->query($sql);
      $rows->allowRowCount = TRUE;
      
      if ($rows->rowCount() > 0) {
        foreach($rows as $row) {
          if($row->fun_photo_id != NULL) {
            $query = \Drupal::database()->select('file_managed', 'fm');
            $query->addField('fm', 'uri');
            $query->condition('fm.fid', $row->fun_photo_id);
            $query->range(0, 1);
            $furi = $query->execute()->fetchField();
            if($furi) {
              $fun_image = file_create_url($furi);
            } else {
              $fun_image = '';
            }
          } else {
            $fun_image = '';
          }
          if($row->uri) {
            $image = file_create_url($row->uri);
          } else {
            $image = $base_url.'/sites/default/files/default.jpg';
          }
          $output .= '<li rel="'.$row->uid.'"><div class="imgSec flip_container2"><div class="flip2"><div class="flip_front2"><img src="'.$image.'" alt=""></div>';
          if($fun_image) {
            $output .= '<div class="flip_back2"><img src="'.$fun_image.'" alt=""></div>';
          }
          $output .= '</div></div><div class="contentSec"><div class="titleSec"><h4>'.$row->name.'</h4>';
          $output .= '</div><div class="addrs"><p>Area Group: <strong>'.$row->area_group.'</strong></p>
                      <p>Subject Group: <strong>'.$row->subject_group.'</strong></p>
                      <p>Number of years as faculty: <strong>'.$row->years_as_faculty.' years</strong></p>
                      <div class="addrs"><p>Intrests: '.$row->hobbies.'</p><p>Nickname: '.$row->nickname.'</p></div>
                      <div class="contact">
                     <a class="mob">'.$mobile.'</a><a class="socialLink" href="'.$row->linkedin_url.'" target="_blank"></a>
                     </div></div></li>';
        }
      }
      return new JsonResponse(['loadMore' => $load_more_show, 'data' => $output]);
    }
    
    public function listing_more() {
      $uid  = $_POST['nid'];
      $type = trim($_POST['type']);
      $load_more_show = '0';
      $query = \Drupal::database()->select('users_field_data', 'ufd');
      $query->fields('ufd', ['uid', 'mail']);
      $query->leftJoin('users_extra', 'ue', 'ue.entity_id = ufd.uid');
      $query->fields('ue', ['name', 'country', 'state', 'city','mobile_number', 'batch_number', 'course_type', 'course_name', 'joining_year', 'graduating_year','years_as_faculty']);
      $query->leftJoin('users_profile', 'up', 'up.entity_id = ufd.uid');
      $query->fields('up', ['nickname', 'fun_photo_id', 'hobbies', 'professional_background','linkedin_url', 'year_of_experience']);
      $query->leftJoin('user__user_picture', 'upp', 'upp.entity_id = ufd.uid');
      $query->fields('upp', ['user_picture_target_id']);
      $query->leftJoin('file_managed', 'fm', 'fm.fid = upp.user_picture_target_id');
      $query->fields('fm', ['uri']);
      $query->condition('ue.user_type', $type);
      $query->condition('ufd.uid', $uid, '<');
      $query->orderBy('ufd.uid', 'DESC');
      $query->range(0,10);
      $users = $query->execute()->fetchAllAssoc('uid');
      $count = 1;
      foreach($users as $user) {
        if($count < 10) {
          $designation  = '';
          $organisation = '';
          $profession   = json_decode($user->professional_background);
          foreach($profession as $prof) {
            if($prof->workHereChk == 'true') {
              $designation = $prof->designation;
              $organisation = $prof->organisation;
            }
          }
          if($user->uri) {
            $image = file_create_url($user->uri);
          } else {
            $image = $base_url.'/sites/default/files/default.jpg';
          }
          
          if($user->fun_photo_id != NULL) {
            $query = \Drupal::database()->select('file_managed', 'fm');
            $query->addField('fm', 'uri');
            $query->condition('fm.fid', $user->fun_photo_id);
            $query->range(0, 1);
            $furi = $query->execute()->fetchField();
            if($furi) {
              $fun_image = file_create_url($furi);
            } else {
              $fun_image = '';
            }
          } else {
            $fun_image = '';
          }
          
          $name         = $user->name;
          $batch        = $user->batch_number;
          $join_year    = $user->joining_year;
          $grad_year    = $user->graduating_year;
          $course_type  = $user->course_type;
          $country_code = $this->country_code($user->country);
          $state        = $user->state;
          $city         = $user->city;
          $hobbies      = $user->hobbies;
          $nickname     = $user->nickname;
          $mobile       = $user->mobile_number;
          $mail         = $user->mail;
          $linkedin     = $user->linkedin_url;
          $output .= '<li rel="'.$user->uid.'"><div class="imgSec flip_container2"><div class="flip2"><div class="flip_front2"><img src="'.$image.'" alt=""></div>';
          if($fun_image) {
            $output .= '<div class="flip_back2"><img src="'.$fun_image.'" alt=""></div>';
          }
          $output .= '</div></div><div class="contentSec"><div class="titleSec"><h4>'.$name.'</h4>';
          if($type == 'alumni') {
            $output .= '<p>'.$designation.' <br> '.$organisation.' </p>';
            $output .= '</div><div class="addrs"><p>Batch: <strong>'.$batch.' ('.$join_year.' to '.$grad_year.')</strong></p>';
            $output .= '<p>Course/Dept: <strong>'.$course_type.'</strong></p>';
            $output .= '<p>City: <strong>'.$city.', '.$state.', '.$country_code.'</strong></p></div>';
          }
          if($type == 'faculty') {
            $output .= '</div><div class="addrs"><p>Area Group: <strong>'.$user->area_group.'</strong></p>';
            $output .= '<p>Subject Group: <strong>'.$user->subject_group.'</strong></p>';
            $output .= '<p>Number of years as faculty: <strong>'.$user->years_as_faculty.' years</strong></p></div>';
          }
          if($type == 'student') {
            $output .= '</div><div class="addrs"><p>Batch: <strong>'.$batch.'</strong></p>';
            $output .= '<p>Course/Dept: <strong>'.$course_type.'</strong></p>';
            $output .= '<p>Joining Year: <strong>'.$user->joining_year.'</strong></p></div>';
          }
          $output .= '<div class="addrs"><p>Intrests: '.$hobbies.'</p><p>Nickname: '.$nickname.'</p></div>';
          $output .= '<div class="contact">';
          $output .= '<a class="mob">'.$mobile.'</a>';
          if($linkedin) {
            $output .= '<a class="socialLink" href="'.$linkedin.'"></a>';
          }
          $output .= '</div></div></li>';
        }
        $count++;
      }
      
      if($count > 9) {
        $load_more_show = '1';
      } 
      return new JsonResponse(['loadMore' => $load_more_show, 'data' => $output]);  
    }
    
    public function newsletter() {
      $hashed   = new PhpassHashedPassword(2);
			$salt     = hash_hmac('sha256', 'AAMRII', 'iirmaa');
			$randm    = new CustomPagesController;
			$random   = $hashed->hash($randm->generate_random_string());
			$token    = md5($salt.$random);
      
			$_SESSION['newsletter'] = $token;
      
      $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
      $name   = $user->get('name')->value;
      $uid    = $user->get('uid')->value;
      $email  = $user->get('mail')->value;
      
      $query = \Drupal::entityQuery('node')
            ->condition('status', 1)
            ->condition('type', 'alumni_newsletter')
            ->sort('field_year.value', 'DESC');
      $nids = $query->execute();
      $nodes = entity_load_multiple('node', $nids);
      $temp_arr = array();
      $options = '';
      $first = true;
      foreach($nodes as $node) {
        $year = $node->field_year->value;
        if(!in_array($year, $temp_arr)) {
          array_push($temp_arr, $year);
          if($first) {
             $options .= '<option value="'.$year.'" selected>'.$year.'</option>';
             $selected = $year;
             $first = false;
          } else {
            $options .= '<option value="'.$year.'">'.$year.'</option>';
          }
         
        }
      }
      return new JsonResponse(['options' => $options, 'token' => $random, 'email' => $email, 'selected' => $selected]);
    }
    
    public function newsletter_more() {
      $nid = $_POST['nid'];
      $year = trim($_POST['yearNL']);
      if(trim($year) == 'Select') {
        $year = '';
      } 
      $output = '';
      $load_more_show = '0';
      $query = \Drupal::entityQuery('node')
            ->condition('status', 1)
            ->condition('type', 'alumni_newsletter');
      if($year != '') {
        $query->condition('field_year', $year);
      }
      $query->condition('nid', $nid, '<')
            ->sort('nid', 'DESC')
            ->range(0,5);
      $nids = $query->execute();
      $count = $query->count()->execute();
      if($count > 4) {
        $load_more_show = '1';
      }
      $nodes = entity_load_multiple('node', $nids);
      $row_count = 1;
      foreach($nodes as $node) {
        if($row_count <= 4) {
          $image = file_create_url($node->field_image->entity->getFileUri());
          $pdf   = file_create_url($node->field_upload_bylaws->entity->getFileUri());
          $output .= '<li rel="'.$node->nid->value.'"><div class="imgSec"><img src="'.$image.'" alt=""></div>';
          $output .= '<div class="contentSec"><div class="titleSec"><h4>'.$node->title->value.'</h4><p>'.$node->body->value.'</p></div></div>';
          $output .= '<div class="btnSec"><a class="button" href="'.$pdf.'" target="_blank">Download</a><a class="shareBtn" href="javascript:;">Share</a><div class="social"></div></div></li>';
          $row_count++;
        }
      } 
      return new JsonResponse(['loadMore' => $load_more_show, 'data' => $output]);  
    }
    
    public function newsletter_filter() {
      $year = trim($_POST['yearNL']);
      $load_more_show = '0';
      $output = '';
      $query = \Drupal::entityQuery('node')
            ->condition('status', 1)
            ->condition('type', 'alumni_newsletter');
      if($year != '') {
        $query->condition('field_year', $year);
      }
      $query->sort('nid', 'ASC')
            ->range(0,5);
      $nids  = $query->execute();
      $cnt = $query->count()->execute();
      $nodes = entity_load_multiple('node', $nids);
      $count = 1;
      foreach($nodes as $node) {
        if($count <= 4) {
          if($node->field_image->target_id != NULL) {
            $image = file_create_url($node->field_image->entity->getFileUri());
          } else {
            $image = '';
          }
          if($node->field_upload_bylaws->target_id != NULL) {
            $pdf = file_create_url($node->field_upload_bylaws->entity->getFileUri());
          } else {
            $pdf = '';
          }
          $output .= '<li rel="'.$node->nid->value.'"><div class="imgSec"><img src="'.$image.'" alt=""></div>';
          $output .= '<div class="contentSec"><div class="titleSec"><h4>'.$node->title->value.'</h4><p>'.$node->body->value.'</p></div></div>';
          $output .= '<div class="btnSec"><a class="button" href="'.$pdf.'" target="_blank">Download</a><a class="shareBtn" href="javascript:;">Share</a><div class="social"></div></div></li>';
        }
        $count++;
      }
      if($cnt > 4) {
        $output = '<a class="button loadMoreNewsL" href="javascript:;">Load more</a>';
      }
      return new JsonResponse(['loadMore' => $load_more_show, 'data' => $output]); 
    }
  
    public function subscribe() {
      $error        = false;
      $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
      $name   = $user->get('name')->value;
      $uid    = $user->get('uid')->value;
      $actual_token = $_SESSION['newsletter'];
      $status       = 'failed';
      $hashed       = new PhpassHashedPassword();
      $salt         = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random       = $_POST['token'];
      $token        = md5($salt.$random);
      $randm        = new CustomPagesController;
      $rnd_num      = $hashed->hash($randm->generate_random_string());
      $fresh_token  = md5($salt.$rnd_num);
      
      $query = \Drupal::database()->select('users_extra', 'ue');
      $query->addField('ue', 'user_type');
      $query->condition('ue.entity_id', $uid);
      $query->range(0, 1);
      $user_type = $query->execute()->fetchField();
      
      if($actual_token == $token) {
        if($_POST['email'] == '' || $_POST['email'] == 'yourid@mail.com') {
          $mesg = 'Please enter an email id';
          $error = true;
        } else {
          if(!$this->valid_mail($_POST['email'])) {
            $mesg = 'Please enter valid email id';
            $error = true;
          } else {
            $email = $_POST['email'];
            $query = \Drupal::database()->select('newsletter_subscribe', 'ns');
            $query->addField('ns', 'email');
            $query->condition('ns.email', $email);
            $query->range(0, 1);
            $found = $query->execute()->fetchField();
            if($found) {
              $mesg = 'You have already subscribed to our newsletter';
              $error = true;
            }
          }
        }
        if(!$error) {
          $query = \Drupal::database()->insert('newsletter_subscribe');
          $query->fields(['uid','user_type','email']);
          $query->values([$uid, $user_type, $email]);
          $query->execute();
          $status = 'success';
        }
      } else {
        $mesg = 'Token mismatch - Please try again';
        $status = 'failed';
      }
      return new JsonResponse(['status' => $status, 'tok' => $rnd_num, 'mesg' => $mesg]);
    }
    
    public function apply_job(){
      $error        = false;
      $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
      $name   = $user->get('name')->value;
      $uid    = $user->get('uid')->value;
      $_POST['postby'] = "job";
      $_POST['return'] = "plain";
      $jobid = $_POST['nodeid'];

      $query = \Drupal::database()->select('apply_irma_profile', 'aip');
      $query->fields('aip', ['job_id', 'user_id']);
      $query->condition('aip.job_id',$jobid);
      $query->condition('aip.user_id', $uid);
      $query->range(0, 1);
      $result = $query->execute()->fetchAssoc();
     

      if($_POST['irma_profile']==0){
        $fid = $this->image_upload();
        if(!empty($result)){
          $message = "Resume has been updated successfully";
        } else {
           $message = "Resume has been uploaded successfully";
        }
      } else {
        $fid = NULL;
        if(!empty($result)){
          return new JsonResponse(['status' => "success","message"=>"You have already applied for this job profile."]);
        } else {
          $message = "Applied with your IRMA profile successfully";
        }
      }
      $query = \Drupal::database()->insert('apply_irma_profile');
      $query->fields(['job_id','user_id','fid','irma_profile','date_created','date_modified','status']);
      $query->values([$jobid, $uid, $fid,$_POST['irma_profile'],date('Y-m-d H:i:s'),date('Y-m-d H:i:s'),1]);
      $query->execute();
      $mesg = 'Thank you for applying for the job. We will get back to you shortly.';
      $site_mail = \Drupal::config('system.site')->get('mail');
      $mail_data = '<table>
                      <tr><td>Name</td><td>:</td><td>'.$name.'</td></tr>
                      <tr><td>Batch Number</td><td>:</td><td>'.$batch_no.'</td></tr>
                      <tr><td>Organisation</td><td>:</td><td>'.$organisation.'</td></tr>
                      <tr><td>Designation</td><td>:</td><td>'.$designation.'</td></tr>
                      <tr><td>email</td><td>:</td><td>'.$email.'</td></tr>
                      <tr><td>Country</td><td>:</td><td>'.$country.'</td></tr>
                      <tr><td>State</td><td>:</td><td>'.$state.'</td></tr>
                      <tr><td>City</td><td>:</td><td>'.$city.'</td></tr>
                      <tr><td>Mobile Number</td><td>:</td><td>'.$mobile.'</td></tr>
                      <tr><td>MDP Name</td><td>:</td><td>'.$mdp_name.'</td></tr>
                      <tr><td>Start Date</td><td>:</td><td>'.$start_date.'</td></tr>
                      <tr><td>End Date</td><td>:</td><td>'.$end_date.'</td></tr>
                      <tr><td>MDP Query</td><td>:</td><td>'.$media_query.'</td></tr>
                    </table>';
      
      $body = 'Thank you for applying for the job. We will get back to you shortly..<br>' . $mail_data ;
      
      $body = $this->build_html($first_name, $body);
      MailController::sendCustomMail("",$email,"Alumni Website - Job Applied",$body); //Mail to user
      
      
      return new JsonResponse(['status' => "success","message"=>$message]);
    }
    
    public function upload_country_cities(){
      $query = \Drupal::database()->select('cities', 'ct');
      $query->fields('ct', ['id', 'name']);
      $result = $query->execute()->fetchAll();
      /*foreach ($result as $key => $value) {
        $term = \Drupal\taxonomy\Entity\Term::create([
                  'vid' => 'city',
                  'name' => $value->name,
            ]);
        $term->save();
      }*/
      echo "<pre>"; print_r($result); exit;
      return new JsonResponse(['status' => "success","message"=>""]);
    }

    public function build_html($name, $body) {
      global $base_url;
      return '<table width="563" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr><td style="line-height:10px;"><img src="'.$base_url.'/sites/default/files/maiLogo.png" alt="" border="0"></td></tr>
      <tr><td valign="top"><table width="563" border="0" cellspacing="0" cellpadding="0"><tr><td width="15"></td>
			<td width="533" valign="top"><table width="533" border="0" cellspacing="0" cellpadding="0"><tr>
      <td style="font-family:Arial; font-size:14px; color:#000;"><p>Hello '.$name.'</p></td></tr></table></td></tr>
			<tr><td width="15"></td><td width="533" valign="top"><table width="533" border="0" cellspacing="0" cellpadding="0">
			<tr><td height="15"></td></tr><tr><td style="font-family:Arial; font-size:14px; color:#000;"><p>'.$body.'</p></td></tr>
			<tr><td height="15"></td></tr></table></td><td width="15"></td></tr><tr><td width="15"></td><td width="533" valign="top">
			<table width="533" border="0" cellspacing="0" cellpadding="0">
        <tr><td style="font-family:Arial; font-size:14px; color:#000;">Thanks,</td></tr>
        <tr><td style="font-family:Arial; font-size:14px; color:#000;"><p>IRMA Alumni Team</p></td></tr>
        <tr><td height="15"></td></tr>
      </table></td><td width="15"></td></tr></table></td></tr><tr>
      <td valign="top" bgcolor="#b30608">
			<table width="563" border="0" cellspacing="0" cellpadding="0"><tr><td width="15"></td><td width="533" valign="top">
			<table width="533" border="0" cellspacing="0" cellpadding="0"><tr><td height="15"></td></tr><tr><td height="2" bgcolor="#a8a8a8"></td></tr>
			<tr><td height="15"></td></tr><tr>
			<td align="center" style="font-family:Arial; font-size:12px; color:#000; text-decoration:underline;">
			This email is an automated notification and does not require a reply.</td></tr><tr><td height="15"></td></tr>
			</table></td><td width="15"></td></tr></table></td></tr></table>';
    }
  }