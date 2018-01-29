<?php
namespace Drupal\custom_blocks\Plugin\Block;

use Drupal\custom_pages\Controller\CustomPagesController;
use Drupal\Core\Block\BlockBase;
use Drupal\Component\Annotation\Plugin;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Password\PhpassHashedPassword;
use Drupal\node\Entity\Node;
use \Drupal\custom_mail\Controller\MailController;

/**
 * Provides a 'Custom' Block
 *
 * @Block(
 *   id = "post_job",
 *   admin_label = @Translation("Post a job"),
 * )
 */

class CustomPostAJob extends BlockBase {
  /**
   * {@inheritdoc}
   */
    public function build() {
    //echo "<pre>"; print_r($_POST); print_r($_FILES); 
    $ddown  = new CustomPagesController;
    /* You can put any PHP code here if required */
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $name   = $user->get('name')->value;
    $email  = $user->get('mail')->value;
    $uid    = $user->get('uid')->value;
    $status = "unsuccess";
    $companyTerms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('organization');
    $jobfunctionsTerms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('job_functions');
    $locationTerms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('location');
    $jobPositionTerms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('job_position');

    $countriesTerms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('country');
    $statesTerms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('states');
    $citiesTerms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('city');

    $query = \Drupal::database()->select('countries', 'ctr');
    $query->fields('ctr', ['id','sortname', 'name']);
    $result = $query->execute()->fetchAll();
    foreach ($result as $key => $value) {
      $countryArr[$value->id] = $value->name;
      $newcountryArr[$value->sortname] = $value->name;
      $newcountryArr2[$value->id] = $value->sortname;
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
    foreach ($jobPositionTerms as $key => $value) {
      $jobposArr[] = $value->tid;
      $jobPositionArr[$value->tid] = $value->name;
    }

    $randm = new CustomPagesController;
    $hashed = new PhpassHashedPassword();

    if(isset($_POST['country']) && !empty($_POST['country'])){
      $countries = json_decode($ddown->countries_options($newcountryArr[$_POST['country']]));
    } else {
      $countries = json_decode($ddown->countries_options());
    }
    $countries_options = $countries->options;
    $country_id = $countries->id;
    if(isset($_POST['state']) && !empty($_POST['state'])){
      $states = json_decode($ddown->states_options($country_id,$statesArr[$_POST['state']]));
    } else {
      $states = json_decode($ddown->states_options($country_id,NULL));
    }
    $states_options = $states->options;
    $state_id = $states->id;

    if(isset($_POST['city']) && !empty($_POST['city'])){
      $cities_options = $ddown->cities_options($state_id,$citiesArr[$_POST['city']]);
    } else {
      $cities_options = $ddown->cities_options($state_id,NULL);
    }
    

    $salt         = hash_hmac('sha256', 'AAMRII', 'iirmaa');
    $randomnumber = rand(10,100);
    $token        = md5($salt.$randomnumber);
    $_SESSION['postajobtoken'] = $token;
    //echo "<pre>"; print_r($token); exit;
    $error = false;
    $jobtitleerrMsg = '';
    $functionerrMsg = '';
    $organisationerrMsg ='';
    $countryerrMsg = '';
    $stateerrMsg = '';
    $cityerrMsg = '';
    $minexperrMsg = '';
    $maxexperrMsg = '';
    $jobdescerrMsg = '';
    //echo "<pre>"; print_r($jobposArr); print_r($jobfunctionsArr);
    if(isset($_POST) && !empty($_POST)){
      //echo "<pre>"; print_r($_POST); 
      if(isset($_POST['jobdesc']) && !empty($_POST['jobdesc'])){
        $jobdescval = $_POST['jobdesc'];
      } else {
        $jobdescval = "";
      }

      if($_POST['jobtitle'] == '') {
        $jobtitleerrMsg = '<span class="errorMsg">Please select a job title.</span>';
        $error = true;
      } else {
        if(!in_array($_POST['jobtitle'], $jobposArr)) {
          $jobtitleerrMsg = '<span class="errorMsg">Invalid Job Title.</span>';
          $error = true; 
        }
      }

      if($_POST['function'] == '') {
        $functionerrMsg = '<span class="errorMsg">Please select a function.</span>';
        $error = true;
      } else {
        if(!in_array($_POST['function'], $jobfunctionsArr)) {
          $functionerrMsg = '<span class="errorMsg">Invalid Job Function.</span>';
          $error = true; 
        }
      }

      if($_POST['organisationname'] == '') {
        $organisationerrMsg = '<span class="errorMsg">Please select organisation name.</span>';
        $error = true;
      } else {
        /*if(!in_array($_POST['orgname'], $compArr)) {
          $errMsg[] = 'organisationname-Please select a valid organisation name.';
          $error = true; 
        }*/
      }

      if(empty($_POST['country'])) {
        $countryerrMsg = '<span class="errorMsg">Please select a country.</span>';
        $error = true;
      } else {
        if(!in_array($_POST['country'],$newcountryArr2)) {
          $countryerrMsg = '<span class="errorMsg">Please select a valid country.</span>';
          $error = true; 
        }
      }
      
      if($_POST['state'] == '') {
        $stateerrMsg = '<span class="errorMsg">Please select a state.</span>';
        $error = true;
      } else {
        if(is_numeric($statesArr[$_POST['state']])) {
          $stateerrMsg = '<span class="errorMsg">Please select a valid state.</span>';
          $error = true; 
        }
      }

      if($_POST['city'] == '') {
        $cityerrMsg = '<span class="errorMsg">Please select a city.</span>';
        $error = true;
      } else {
        if(is_numeric($citiesArr[$_POST['city']])) {
          $cityerrMsg = '<span class="errorMsg">Please select a valid city.</span>';
          $error = true; 
        }
      }

      if((isset($_POST['minexp']) && empty($_POST['minexp'])) && !is_numeric($_POST['minexp'])) {
        $minexperrMsg = '<span class="errorMsg">Please select min experience.</span>';
        $error = true; 
      } 

      if((isset($_POST['maxexp']) && empty($_POST['maxexp'])) && !is_numeric($_POST['maxexp'])){
        $maxexperrMsg = '<span class="errorMsg">Please select max experience.</span>';
        $error = true; 
      } 
      
      if($_POST['jobdesc'] == '') {
        $jobdescerrMsg = '<span class="errorMsg">Job Description is required.</span>';
        $error = true;
      } else {
        if(!$this->valid_name($_POST['jobdesc'])){
          $jobdescerrMsg = '<span class="errorMsg">Please enter valid details in description.</span>';
          $error = true;
        }
      }
      $uploaderror = false;
      $fid = (isset($_POST['uploadid']) && !empty($_POST['uploadid']))? $_POST['uploadid'] : "";
      $filename = (isset($_POST['uploadfilename']) && !empty($_POST['uploadfilename']))? $_POST['uploadfilename'] : "";
      if(empty($fid)){
        if(isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])){
          $allowed_ext = array('docx', 'doc', 'pdf');
          $uuid_service = \Drupal::service('uuid');
          $uuid         = $uuid_service->generate();
          $filename     = $_FILES['file']['name'];
          $filemime     = $_FILES['file']['type'];
          $temp         = explode('.', $filename);
          $extension    = strtolower(end($temp));
          $uri = 'public://jobs/'.$filename;

          if(!in_array($extension, $allowed_ext)) {
            $uploadFileMsg = "Only files with extension docx, doc and pdf are allowed.";
            $uploaderror = true;
            $error = true;
          }
          $size = $_FILES['file']['size'];
          $filesize = $size/2097152;
          if($filesize > 2) {
            $uploadFileMsg = 'Filesize exceeds the max allowed - 2 MB.';
            $uploaderror = true;
            $error = true;
          }
          if($uploaderror==false){
            $handle = fopen($_FILES['file']['tmp_name'], 'r');
            $filesaved = file_save_data($handle, $uri, FILE_EXISTS_RENAME);
            fclose($handle);
            if($filesaved) {
              $fpath = file_create_url($filesaved->getFileUri()); 
              $fid = $filesaved->id();
              $image = file_create_url($filename);
              $uploadFileMsg = 'File Uploaded Successfully.';
            } else {
              $uploadFileMsg = 'File Upload failed';
            }
          }
        }
      }
      if($error==false){
        $mailparams = $valArr = array(
            'type' => 'jobs',
            'title'=> $jobPositionArr[$_POST['jobtitle']],
            'body' => $_POST['jobdesc'],
            'uid'  =>$uid,
            'field_company' =>[$_POST['organisationname']],
            'field_job_functions' =>[$_POST['function']],
            'field_country' =>$countriesArr2[$newcountryArr[$_POST['country']]],
            'field_state' =>$statesArr2[$statesArr[$_POST['state']]],
            'field_city' =>$citiesArr2[$citiesArr[$_POST['city']]],
            'field_job_positions' =>[$_POST['jobtitle']],
            'status' => FALSE,
          );
        $mailparams['field_attach_file'] = $valArr['field_attach_file'] = ['target_id'=>$_POST['uploadid'],'alt' => $jobPositionArr[$_POST['jobtitle']],
            'title' => $jobPositionArr[$_POST['jobtitle']]];
        $mailparams['field_experience'] = $valArr['field_experience'] = $_POST['minexp'];
        $mailparams['field_maximum_experience'] = $valArr['field_maximum_experience'] = $_POST['maxexp'];
        
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

        $successmessage = 'Thank you for posting a job. Once the moderator approves the job, you will receive a notification email & the job will be displayed on the website.';
        $status = 'success';
      }
    }
    $output = '';
    if($status=="unsuccess"){
      $output .= '<div class="formFealds">   
                  <form id="postajobForm" autocomplete="off" class="postajobForm" action=""   enctype="multipart/form-data" method="POST">
                    <ul>';
      /*$output .=  '<li>
                    <input type="text" onblur="clearText(this)" onfocus="clearText(this)" class="jobtitle" id="jobtitle" value="Job Title" maxlength="255" name="jobtitle">
                  </li>';*/
      $output .=  '<li>
                      <label>Job Title <span class="required">*</span></label>
                      <div class="customSelect">
                      <div class="dropdownWrap">
                        <a class="shortDropLink" href="javascript:;"></a>
                    <select id="jobtitle" class="jobtitle" name="jobtitle">
                    <option value="">Select</option>';
      foreach ($jobPositionTerms as $key => $value) {
          if(isset($_POST['jobtitle']) && $value->tid==$_POST['jobtitle']){
            $output .= '<option value="'.$value->tid.'" selected>'.$value->name.'</option>';
          } else {
            $output .= '<option value="'.$value->tid.'">'.$value->name.'</option>';
          }
      }
      $output .=  '</select>'.$jobtitleerrMsg.'
                  </div>
                  </div>
                  </li>';
      $output .=  '<li>
                  <label>Function <span class="required">*</span></label>
                      <div class="customSelect">
                      <div class="dropdownWrap">
                      <a class="shortDropLink" href="javascript:;"></a>
                    <select id="function" class="function" name="function">
                    <option value="">Select</option>';
      foreach ($jobfunctionsTerms as $key => $value) {
          if(isset($_POST['function']) && $value->tid==$_POST['function']){
            $output .= '<option value="'.$value->tid.'" selected>'.$value->name.'</option>';
          } else {
            $output .= '<option value="'.$value->tid.'">'.$value->name.'</option>';
          }
      }
      $output .=  '</select>'.$functionerrMsg.'
                  </div>
                  </div>
                  </li>';
      $output .=  '<li>
                      <label>Organisation Name <span class="required">*</span></label>
                      <div class="customSelect">
                      <div class="dropdownWrap">
                      <a class="shortDropLink" href="javascript:;"></a>
                    <select id="organisationname" class="organisationname" name="organisationname">
                      <option value="">Select</option>';
      foreach ($companyTerms as $key => $value) {
          if(isset($_POST['organisationname']) && $value->tid==$_POST['organisationname']){
            $output .= '<option value="'.$value->tid.'" selected>'.$value->name.'</option>';
          } else {
            $output .= '<option value="'.$value->tid.'">'.$value->name.'</option>';
          }
      }
      $output .=  '</select>'.$organisationerrMsg.'
                  </div>
                  </div>
                  </li>';
      $output .= '<li class="regCntry">
                      <label>Country <span class="required">*</span></label>
                    <div class="customSelect cntryList">
                      <div class="dropdownWrap">
                        <a class="shortDropLink" href="javascript:;"></a>
                        <select name="country" class="form-control countries countryList" id="countryId">
                          <option value="">Select</option>
                          '.$countries_options.'
                        </select>'.$countryerrMsg.'
                      </div>
                    </div>
                  </li>
                  <li class="regState">
                    <label>State <span class="required">*</span></label>
                    <div class="customSelect stateList">
                      <div class="dropdownWrap">
                        <a class="shortDropLink" href="javascript:;"></a>
                        <select name="state" class="form-control states" id="stateId">
                          <option value="">Select</option>
                          '.$states_options.'
                        </select>'.$stateerrMsg.'
                      </div>
                    </div>
                  </li>
                  <li class="regCity">
                      <label>City <span class="required">*</span></label>
                    <div class="customSelect ctList">
                      <div class="dropdownWrap">
                        <a class="shortDropLink" href="javascript:;"></a>
                        <select name="city" class="form-control cities cityList" id="cityId">
                          <option value="" >Select City</option>
                          '.$cities_options.'
                        </select>'.$cityerrMsg.'
                      </div>
                    </div>
                  </li>';           
      /*$output .=  '<li>
                      <label>Location <span class="required">*</span></label>
                      <div class="customSelect">
                      <div class="dropdownWrap">
                      <a class="shortDropLink" href="javascript:;"></a>
                    <select id="location" class="location" name="location">
                      <option value="">Select</option>';
      foreach ($locationTerms as $key => $value) {
          $output .= '<option value="'.$value->tid.'">'.$value->name.'</option>';
      }
      $output .=  '</select>
                      </div>
                  </div>
                  </li>';*/

      $output .=  '<li>
                      <label>Minimum Experience</label>
                      <div class="customSelect">
                      <div class="dropdownWrap">
                      <a class="shortDropLink" href="javascript:;"></a>
                    <select id="minexp" class="minexp" name="minexp">
                      <option value="">Select</option>';
      $i=30;
      $j=1;
      while($i>=1){
        if(isset($_POST['minexp']) && $j==$_POST['minexp']){
          $output .= '<option value="'.$j.'" selected>'.$j.'</option>';
        } else {
          $output .= '<option value="'.$j.'">'.$j.'</option>';
        }
        $j++;
        $i--;
      }
      $output .=  '</select>'.$minexperrMsg.'
                  </div>
                  </div>
                  </li>';
      $output .=  '<li>
                      <label>Maximum Experience</label>
                      <div class="customSelect">
                      <div class="dropdownWrap">
                      <a class="shortDropLink" href="javascript:;"></a>
                    <select id="maxexp" class="maxexp" name="maxexp">
                      <option value="">Select</option>';
      $i=30;
      $j=1;
      while($i>=1){
        if(isset($_POST['maxexp']) && $j==$_POST['maxexp']){
          $output .= '<option value="'.$j.'" selected>'.$j.'</option>';
        } else {
          $output .= '<option value="'.$j.'">'.$j.'</option>';
        }
        $j++;
        $i--;
      }
      //echo '<pre>'; print_r($_SESSION); echo '</pre>'; exit;
      $output .=  '</select>'.$maxexperrMsg.'
                  </div>
                  </div>
                  </li>';
      $output .=  '<li>
                  <label>Job Description <span class="required">*</span></label>
                    <textarea onblur="clearText(this)" onfocus="clearText(this)" class="jobdesc" id="jobdesc" maxlength="255" name="jobdesc" placeholder="Job Description" value="'.$jobdescval.'">';
      if(isset($_POST['jobdesc']) && !empty($_POST['jobdesc'])){
        $output .= $_POST['jobdesc'];
      }                
      $output   .= '</textarea>'.$jobdescerrMsg.'
                  </li>';
      $output .=  '<li class="uploadli ie9upload" style="display:none;"><label>Attach File : <span class="">'.$filename.'</span></label><input name="file" type="file" class="inputFile" id="inputFile">'.$uploadFileMsg.'</li>';         
      $output .= '<input name="csrftoken" id="csrftoken" type="hidden" value="'.$randomnumber.'"></ul>';
      $output .= '<input name="uploadfilename" id="uploadfilename" type="hidden" value="'.$filename.'">';
      $output .= '<input name="uploadid" id="uploadid" type="hidden" value="'.$fid.'">';
      $output .= '<input name="testtoken" id="testtoken" type="hidden" value="'.$randomnumber.'"></form>';
      $output .=  '<ul><li class="uploadli"><div class="uploadBtn">
                      <form id="fileForm" action="" method="post"  enctype="multipart/form-data">
                        <a class="fileUploadLink" href="javascript:;">Attach File.</a>
                        <input name="file" type="file" class="inputFile" id="inputFile">
                        <span class="uploadedFileName"></span>
                        <input type="hidden" name="postby" value="job" id="postby"/>
                        <input type="hidden" name="jobtitle" value="" id="jobtitle"/>
                        <input type="hidden" name="function" value="" id="function"/>
                        <input type="hidden" name="organisationname" value="" id="organisationname"/>
                        <input type="hidden" name="country" value="" id="country"/>
                        <input type="hidden" name="state" value="" id="state"/>
                        <input type="hidden" name="city" value="" id="city"/>
                        <input type="hidden" name="minexp" value="" id="minexp"/>
                        <input type="hidden" name="maxexp" value="" id="maxexp"/>
                        <input type="hidden" name="jobdesc" value="" id="jobdesc"/>
                        <input type="submit" value="Attach selected file" class="btnSubmit fileUpload">
                      </form>
                  </div><div class="messages messages--status" style="display:none;"></div></li></ul>';
      $output .= '<input class="button postajob '.$randomnumber.' '.$_SESSION['postajobtoken'].'" type="button" value="Post a job"></ul>';
      $output .= '';
    } else {
      $output .= '<div class="content"></div><div class="messages messages--status">'.$successmessage.'</div>';
    }

    return array(
      '#title' => $this->t('Post a job'),
      '#markup' => $this->t($output),
    );

  }

  public function valid_name($name) {
    if(preg_match("/^([a-zA-Z.\']+\s?)*$/", trim($name))) {
      return true;
    }  else {
      return false;
    }
  }

}