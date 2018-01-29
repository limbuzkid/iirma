<?php

  namespace Drupal\custom_pages\Controller;
  
  use Drupal\Core\Controller\ControllerBase;
  use Drupal\user\UserInterface;
  use Drupal\Core\Annotation\Translation;
  use Drupal\Core\Url;
  use Drupal\Core\Link;
  use Drupal\file\Entity\File;
  use Drupal\image\Entity\ImageStyle;
  use Drupal\node\Entity\Node;
  use Drupal\user\Entity\User;
  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\HttpFoundation\RedirectResponse;
  use Drupal\Core\Password\PhpassHashedPassword;
  
  
  class CustomPagesController extends ControllerBase {

    public function my_account($arg) {
      //return new \Symfony\Component\HttpFoundation\RedirectResponse(\Drupal::url('custom_pages.404'));
      if($arg != NULL) {
        global $base_url;
        $hashed               = new PhpassHashedPassword(2);
        $salt                 = hash_hmac('sha256', 'AAMRII', 'iirmaa');
        $random               = $hashed->hash($this->generate_random_string());
        $token                = md5($salt.$random);
        $_SESSION['myAcct']   = $token;
        
        $login = false;
        $current_user = \Drupal::currentUser()->id();
        //echo $current_user; exit;
        if(strpos($arg, '-') === false) {
          $uid = $arg;
          $user = User::load($uid);
          if($current_user == $arg) {
            $uuid = $user->uuid->value;
          } else {
            return new RedirectResponse(\Drupal::url('<front>'));
          }
        } else {
          $temp_path = explode('-', $arg);
          $uid = end($temp_path);
          $user = User::load($uid);
          $uuid = $user->uuid->value;
          $myUid = str_replace('-'.$uid, '', $arg);
          if($uuid != $myUid) {
            return new RedirectResponse(\Drupal::url('<front>'));
          } else {
            if($current_user == $arg) {
              if($user->login->status != 0) {
                return new RedirectResponse(\Drupal::url('<front>'));
                //user_login_finalize($user);
              } else {
                $query = \Drupal::database()->update('users_field_data');
                $query->fields(['status'  => 1]);
                $query->condition('uid', $uid);
                $query->execute();
                user_login_finalize($user);
                $login = true;
              }
            } else {
              return new RedirectResponse(\Drupal::url('<front>'));
            }
          }
        }
                
        $alumni_progress = 0;
        $student_progress = 0;
        $faculty_progress = 0;
       //echo '<pre>'; print_r($user); echo '</pre>'; exit;
       
        $query = \Drupal::database()->select('user__user_picture', 'uup');
        $query->addField('uup', 'user_picture_target_id');
        $query->condition('uup.entity_id', $uid);
        $query->range(0, 1);
        $fid = $query->execute()->fetchField();
        //$fid = $user->user_picture[0]->target_id;
        //echo 'FID' . $fid;
        if(isset($fid) && $fid > 0) {
          $iquery = \Drupal::database()->select('file_managed', 'fm');
          $iquery->addField('fm', 'uri');
          $iquery->condition('fm.fid', $fid);
          $iquery->range(0, 1);
          $uri = $iquery->execute()->fetchField();
          //echo 'URI'. $uri;
          $profile_image = file_create_url($uri);
          //$file = \Drupal\file\Entity\File::load($fid);
          //$profile_image = file_create_url($file->getFileUri());
          $prof_img = '1';
          $alumni_progress++;
          $student_progress++;
          $faculty_progress++;
        } else {
          $profile_image = $base_url.'/sites/default/files/default.jpg';
          $prof_img = '0';
          $fid = '';
        }
        $ext_qry = \Drupal::database()->select('users_extra', 'ue');
        $ext_qry->fields('ue', ['user_type', 'name','gender', 'date_of_birth', 'address_1', 'address_2', 'address_3', 'country_code', 'country', 'state', 'city', 'mobile_number', 'course_type','course_name', 'joining_year', 'graduating_year', 'batch_number', 'roll_number', 'area_group', 'subject_group', 'years_as_faculty']);
        $ext_qry->condition('ue.entity_id', $uid);
        $fields = $ext_qry->execute()->fetchAssoc();
        
        
        //echo $fields['state'];
        $prof_qry = \Drupal::database()->select('users_profile', 'up');
        $prof_qry->fields('up', ['fun_photo_id', 'nickname','hobbies', 'family_details', 'address_checked', 'permanent_address', 'educational_background','professional_background', 'achievements', 'media_coverage_forums', 'cv_target_id', 'year_of_experience', 'sector_last_worked', 'linkedin_url']);
        $prof_qry->condition('up.entity_id', $uid);
        $profile = $prof_qry->execute()->fetchAssoc();
        
        $fun_photo_id = $profile['fun_photo_id'];
        $fmqry = \Drupal::database()->select('file_managed', 'fm');
        $fmqry->addField('fm', 'uri');
        $fmqry->condition('fm.fid', $fun_photo_id);
        $fmqry->range(0, 1);
        $uri = $fmqry->execute()->fetchField();
        if($uri != NULL) {
          $alumni_progress++;
          $student_progress++;
          $faculty_progress++;
          $fun_photo  = file_create_url($uri);
          $fun_img = '1';
        } else {
          $fun_photo  = $base_url. '/sites/default/files/funimage.png';
          $fun_img = '0';
        }
        
        if($fields['name'] != NULL) {
          $alumni_progress++;
          $student_progress++;
          $faculty_progress++;
        }
        if($fields['gender'] != NULL) {
          $alumni_progress++;
          $student_progress++;
          $faculty_progress++;
        }
        if($fields['date_of_birth'] != NULL) {
          $alumni_progress++;
          $student_progress++;
          $faculty_progress++;
        }
        if($fields['address_1'] != NULL) {
          $alumni_progress++;
        }
        if($fields['country'] != NULL) {
          $alumni_progress++;
        }
        if($fields['state'] != NULL) {
          $alumni_progress++;
        }
        if($fields['city'] != NULL) {
          $alumni_progress++;
        }
  
        if($fields['joining_year'] != NULL) {
          $alumni_progress++;
          $student_progress++;
        }
        if($fields['graduating_year'] != NULL) {
          $alumni_progress++;
        }
        if($fields['batch_number'] != NULL) {
          $alumni_progress++;
          $student_progress++;
        }
        if($fields['roll_number'] != NULL) {
          $alumni_progress++;
          $student_progress++;
        }
        if($fields['area_group'] != NULL) {
          $faculty_progress++;
        }
        if($fields['subject_group'] != NULL) {
          $faculty_progress++;
        }
        if($fields['years_as_faculty'] != NULL) {
          $faculty_progress++;
        }
        if($fields['mobile_number'] != NULL) {
          $alumni_progress++;
          $student_progress++;
          $faculty_progress++;
        }
        if($fields['course_type'] != NULL) {
          $alumni_progress++;
          $student_progress++;
        }
        
        if($profile['nickname'] != NULL) {
          $alumni_progress++;
          $student_progress++;
          $faculty_progress++;
        }
        if($profile['hobbies'] != NULL) {
          $alumni_progress++;
          $student_progress++;
          $faculty_progress++;
        }
  
        if($profile['linkedin_url'] != NULL) {
          $alumni_progress++;
          $student_progress++;
          $faculty_progress++;
        }
        if($profile['year_of_experience'] != NULL) {
          $student_progress++;
          $faculty_progress++;
        }
        if($profile['sector_last_worked'] != NULL)  {
          $student_progress++;
        }
        
        $countries = json_decode($this->countries_options());
        $x_countries_options = $countries->options;
        $country_id = $countries->id;
        $states = json_decode($this->states_options());
        $x_states_options = $states->options;
        $state_id = $states->id;
        $x_cities_options = $this->cities_options();
        
        $countries = json_decode($this->countries_options(strtolower(trim($fields['country']))));
        $cur_countries_options = $countries->options;
        //echo $countries_options;
        $country_id = $countries->id;
        $states = json_decode($this->states_options($country_id,strtolower(trim($fields['state']))));
        $cur_states_options = $states->options;
        $state_id = $states->id;
        $cur_cities_options = $this->cities_options($state_id,strtolower(trim($fields['city'])));
        
        if($profile['cv_target_id']) {
          $alumni_progress++;
          $cv_id = $profile['cv_target_id'];
          $cvqry = \Drupal::database()->select('file_managed', 'fm');
          $cvqry->addField('fm', ['uri','filename']);
          $cvqry->condition('fm.fid', $cv_id);
          $cvqry->range(0, 1);
          $fm = $cvqry->execute()->fetchAssoc();
          $cv_url  = file_create_url($fm['uri']);
          $cv_name = $fm['filename'];
        }
        
        
        $name = explode(' ', $fields['name']);
        $first_name = $name[0];
        $last_name  = $name[1];
        $temp = explode('-', $fields['mobile_number']);
        $mobile = $temp[1];
        $family_details_html = $permanent_address_html = $education_html = $professions_html = $achievements_html = $media_coverage_html = '';
        //ImageStyle::load('image_style_name')->buildUrl($uri);
        //echo $first_name .' '. $last_name;
        //echo '<pre>'; print_r($fields); echo '</pre>'; exit;
        if(isset($profile['family_details'])) {
          $alumni_progress++;
          $fam_details = json_decode($profile['family_details']);
          $family_details_html = '';
          foreach($fam_details as $fam) {
            $family_details_html .= '<ul class="makeCloneIt"><li class="innerLi1"><label>Family Member Name <span class="required">*</span></label>
                                  <input class="famName" type="text" value="'.$fam->name.'">
                              </li><li class="innerLi2"><label>Family Member</label><div class="customSelect"><div class="dropdownWrap">
                                <a class="shortDropLink" href="javascript:;"></a><select class="famRelation"><option value="">Select</option>';
            if($fam->relation == "Father") {
              $family_details_html .= '<option value="Father" selected>Father</option>';
            } else {
              $family_details_html .= '<option value="Father">Father</option>';
            }
            if($fam->relation == "Mother") {
              $family_details_html .= '<option value="Mother" selected>Mother</option>';
            } else {
              $family_details_html .= '<option value="Mother">Mother</option>';
            }
            if($fam->relation == "Sister") {
              $family_details_html .= '<option value="Sister" selected>Sister</option>';
            } else {
              $family_details_html .= '<option value="Sister">Sister</option>';
            }
            if($fam->relation == "Brother") {
              $family_details_html .= '<option value="Brother" selected>Brother</option>';
            } else {
              $family_details_html .= '<option value="Brother">Brother</option>';
            }
            if($fam->relation == "Spouse") {
              $family_details_html .= '<option value="Spouse" selected>Spouse</option>';
            } else {
              $family_details_html .= '<option value="Spouse">Spouse</option>';
            }
            if($fam->relation == "Son") {
              $family_details_html .= '<option value="Son" selected>Son</option>';
            } else {
              $family_details_html .= '<option value="Son">Son</option>';
            }
            if($fam->relation == "Daughter") {
              $family_details_html .= '<option value="Daughter" selected>Daughter</option>';
            } else {
              $family_details_html .= '<option value="Daughter">Daughter</option>';
            }
            if($fam->relation == "Other") {
              $family_details_html .= '<option value="Other" selected>Other</option>';
            } else {
              $family_details_html .= '<option value="Other">Other</option>';
            }
    
            $family_details_html .= '</select></div></div></li><li class="innerLi1"><label>Age</label>
                                  <input class="famAge" type="text" value="'.$fam->age.'" maxlength="3"></li><li class="innerLi2">
                                  <label>Contact Number</label><input class="famContact" type="text" value="'.$fam->mobile.'" maxlength="10"></li></ul>';
          }
        }
        $permanent_address_html = '';
        if($profile['permanent_address']) {
          $alumni_progress++;
          $permanent_address = json_decode($profile['permanent_address']);
          if($profile['address_checked'] == 1) {
            $permanent_address_html .= '<li><label>Permanent Address 1</label><input class="perAddr1" type="text" value="'.$fields['address_1'].'"></li>';
            $permanent_address_html .= '<li><label>Permanent Address 2</label><input class="perAddr2" type="text" value="'.$fields['address_2'].'"></li>';
            $permanent_address_html .= '<li><label>Permanent Address 3</label><input class="perAddr3" type="text" value="'.$fields['address_3'].'"></li>';        
            $permanent_address_html .= '<li class="usrAddrCntry"><label>Country</label>
                          <div class="customSelect permaCntry">
                            <div class="dropdownWrap">
                              <a class="shortDropLink" href="javascript:;"></a>
                              <select class="permCntry countryList">
                                <option value="">Select</option>';
            $permanent_address_html .= $countries_options;
            $permanent_address_html .= '</select></div></div> </li><li class="usrAddrState"><label>State</label><div class="customSelect permaState">
                            <div class="dropdownWrap"><a class="shortDropLink" href="javascript:;"></a><select class="permState addrState"><option value="">Select</option>';
                                
            $permanent_address_html .= $states_options;                 
            $permanent_address_html .= '</select></div></div> </li><li class="usrAddrCty"><label>City</label><div class="customSelect permaCity">
                            <div class="dropdownWrap"><a class="shortDropLink" href="javascript:;"></a>
                              <select class="permCity addrCity"><option value="">Select</option>';
            $permanent_address_html .= $cities_options;
            $permanent_address_html .= '</select></div></div></li>';
            
          } else {
            $permanent_address_html .= '<li><label>Permanent Address 1</label><input class="perAddr1" type="text" value="'.$permanent_address->addr1.'"></li>';
            $permanent_address_html .= '<li><label>Permanent Address 2</label><input class="perAddr2" type="text" value="'.$permanent_address->addr2.'"></li>';
            $permanent_address_html .= '<li><label>Permanent Address 3</label><input class="perAddr3" type="text" value="'.$permanent_address->addr3.'"></li>';
    
            $countries = json_decode($this->countries_options(strtolower(trim($permanent_address->country))));
            $countries_options = $countries->options;
            $country_id = $countries->id;
            
            $states = json_decode($this->states_options($country_id,strtolower(trim($permanent_address->state))));
            $states_options = $states->options;
            $state_id = $states->id;
            
            $cities_options = $this->cities_options($state_id,strtolower(trim($permanent_address->city)));
            
            $permanent_address_html .= '
                                        <li class="usrAddrCntry"><label>Country</label>
                          <div class="customSelect permaCntry">
                            <div class="dropdownWrap">
                              <a class="shortDropLink" href="javascript:;"></a>
                              <select class="permCntry countryList">
                                <option value="">Select</option>';
            $permanent_address_html .= $countries_options;
            $permanent_address_html .= '</select></div></div> </li><li class="usrAddrCty"><label>State</label><div class="customSelect permaState">
                            <div class="dropdownWrap"><a class="shortDropLink" href="javascript:;"></a><select class="permState addrState"><option value="">Select</option>';
                                
            $permanent_address_html .= $states_options;                 
            $permanent_address_html .= '</select></div></div> </li><li class="usrAddrCty"><label>City</label><div class="customSelect permaCity">
                            <div class="dropdownWrap"><a class="shortDropLink" href="javascript:;"></a>
                              <select class="permCity addrCity"><option value="">Select</option>';
            $permanent_address_html .= $cities_options;
            $permanent_address_html .= '</select></div></div></li>';
          }
        }
        
        if($profile['educational_background']) {
          $alumni_progress++;
          $education = json_decode($profile['educational_background']);
          $education_html = '';
          $cur_year = date('Y');
          foreach($education as $edu) {
            $education_html .= '<ul class="makeCloneIt"><li class="innerLi1"><label>Qualification <span class="required">*</span></label>';
            $education_html .= '<input class="qualfn" type="text" value="'.$edu->qualification.'">';
            $education_html .= '</li><li class="innerLi2"><label>College/University <span class="required">*</span></label>';
            $education_html .= '<input class="instution" type="text" value="'.$edu->institution.'"></li>';
            $education_html .= '<li class="innerLi1"><label>Select Year of Passing <span class="required">*</span></label>';
            $education_html .= '<div class="customSelect"><div class="dropdownWrap"><a class="shortDropLink" href="javascript:;"></a>';
            $education_html .= '<select class="yrPassing"><option value="">Select</option>';
            for($i=1979; $i<$cur_year; $i++) {
              if($i == $edu->yearPassing) {
                $education_html .= '<option value="'.$i.'" selected>'.$i.'</option>';
              } else {
                $education_html .= '<option value="'.$i.'">'.$i.'</option>';
              }
            }
            $education_html .= '</select></div></div></li></ul>';
          }
        }
        
        if($profile['media_coverage_forums']) {
          $alumni_progress++;
          $media_coverage = json_decode($profile['media_coverage_forums']);
          $media_coverage_html = '';
          foreach($media_coverage as $media) {
            $media_coverage_html .= '<ul class="makeCloneIt"><li class="innerLi1"><label>Name<span class="required">*</span></label>';
            $media_coverage_html .= '<input class="mediaName" type="text" value="'.$media->name.'"></li>';
            $media_coverage_html .= '<li class="innerLi2"><label>URL</label><input class="mediaUrl" type="text" value="'.$media->url.'"></li>';
            $media_coverage_html .= '<li class="innerLi1"><label>Description</label><textarea class="mediaDesc" value="">'.$media->desc.'</textarea></li></ul>';
          }
        }
        
        if($profile['achievements']) {
          $alumni_progress++;
          $achievements = json_decode($profile['achievements']);
          $achievements_html = '';
          foreach($achievements as $achievement) {
            $achievements_html .= '<ul class="makeCloneIt"><li class="innerLi1"><label>Name <span class="required">*</span></label>';
            $achievements_html .= '<input class="awardName" type="text" value="'.$achievement->name.'">';
            $achievements_html .= '</li><li class="innerLi2"><label>URL</label><input class="awardUrl" type="text" value="'.$achievement->url.'"></li>';
            $achievements_html .= '<li class="innerLi1"><label>Description</label>';
            $achievements_html .= '<textarea class="awarDesc" value="'.$achievement->desc.'">'.$achievement->desc.'</textarea></li></ul>';
          }
        }
        if($profile['professional_background']) {
          $alumni_progress++;
          $professions = json_decode($profile['professional_background']);
          $professions_html = '';
          foreach($professions as $profession) {
            $cntries = json_decode($this->countries_options($profession->country));
            $sttates = json_decode($this->states_options($cntries->id , $profession->state));
            $cties   =  $this->cities_options($sttates->id, $profession->city);
            $professions_html .= '<ul class="makeCloneIt"><li class="innerLi1"><label>Designation <span class="required">*</span></label>
                                  <input class="design" type="text" value="'.$profession->designation.'"></li>
                                  <li class="innerLi2"><label>Organisation <span class="required">*</span></label>
                                  <div class="customSelect"><div class="dropdownWrap"><a class="shortDropLink" href="javascript:;">
                                  </a><select class="organisation"><option value="">Select</option>';
            $professions_html .= $this->oraganisation_options($profession->organisation);                                
            $professions_html .= '</select></div></div></li><li class="innerLi1"><label>Industry <span class="required">*</span></label>
                                  <div class="customSelect"><div class="dropdownWrap"><a class="shortDropLink" href="javascript:;"></a>
                                  <select class="industry"><option value="">Select</option>';
            $professions_html .=  $this->industry_options($profession->industry);               
            $professions_html .= '</select></div></div></li><li class="innerLi2"><label>Country <span class="required">*</span></label>
                                  <div class="customSelect"><div class="dropdownWrap"><a class="shortDropLink" href="javascript:;"></a>
                                  <select class="XprienceCntry countryList"><option value="">Select</option>';
            $professions_html .= $cntries->options;                          
            $professions_html .= '</select></div></div></li><li class="innerLi1"><label>State <span class="required">*</span></label>
                                  <div class="customSelect"><div class="dropdownWrap"><a class="shortDropLink" href="javascript:;"></a>
                                  <select class="XprienceState addrState"><option value="">Select</option>';
            $professions_html .=  $sttates->options;                 
            $professions_html .= '</select></div></div></li><li class="innerLi2"><label>City <span class="required">*</span></label>
                                  <div class="customSelect"><div class="dropdownWrap"><a class="shortDropLink" href="javascript:;"></a>
                                  <select class="XprienceCity addrCity"><option value="">Select</option>';
            $professions_html .=  $cties;                   
            $professions_html .= '</select></div></div></li><li class="innerLi1">
                                  <label>From <span class="required">*</span></label><input class="empFrom" type="text" value="'.$profession->from.'"></li>
                                  <li class="innerLi2"><label>To <span class="required">*</span></label>
                                  <input class="empTo" type="text" value="'.$profession->to.'"></li>
                                  <li class="innerLi1"><div class="customCheckBox"><div class="box">
                                  <input type="checkbox" name="workChk" id="WorkHere"><label for="WorkHere">I Currently Work Here</label></div>
                                  </div></li><li class="innerLi2"><label>Scope Of Responsibility</label>
                                  <textarea class="scope" type="text" value="'.$profession->scope.'">'.$profession->scope.'</textarea></li></ul>';
          }
        }
        
        //$is_addr_checked    = $_POST['addrChked'];
        
        $alumni_progress = ceil(($alumni_progress/16) * 100);
        $student_progress = ceil(($student_progress/26) * 100);
        $faculty_progress = ceil(($faculty_progress/14) * 100);
        
        $data = array(
          'image'           => $profile_image,
          'username'        => $user->name->value,
          'email'           => $user->mail->value,
          'user_type'       => $fields['user_type'],
          'first_name'      => $first_name,
          'last_name'       => $last_name,
          'gender'          => ucwords($fields['gender']),
          'date_of_birth'   => isset($fields['date_of_birth'])?$fields['date_of_birth']:'',
          'address_1'       => $fields['address_1'],
          'address_2'       => $fields['address_2'],
          'address_3'       => $fields['address_3'],
          'country_code'    => $fields['country_code'],
          'country'         => $cur_countries_options,
          'state'           => $cur_states_options,
          'city'            => $cur_cities_options,
          'mobile_number'   => $mobile,
          'course_type'     => $this->course_type_options($fields['course_type']),
          'course_type_val' => $fields['course_type'],
          'course_name'     => $fields['course_name'],
          'joining_year'    => $fields['joining_year'],
          'graduating_year' => $fields['graduating_year'],
          'batch_number'    => $this->batch_options($fields['batch_number']),
          'roll_number'     => $fields['roll_number'],
          'area_group'      => $this->area_group_options($fields['area_group']),
          'subject_group'   => $this->subject_group_options($fields['subject_group']),
          'years_as_faculty'=> $fields['years_as_faculty'],
          'fid'             => $fid,
          'linkedin_url'    => $profile['linkedin_url'],
          'fun_photo'       => $fun_photo,
          'fun_photo_id'    => $fun_photo_id,
          'nickname'        => $profile['nickname'],
          'hobbies'         => $profile['hobbies'],
          'student_progress'=> $student_progress,
          'alumni_progress' => $alumni_progress,
          'faculty_progress'=> $faculty_progress,
          'family_details'  => $family_details_html,
          'perm_address'    => $permanent_address_html,
          'educ_background' => $education_html,
          'prof_background' => $professions_html,
          'achievements'    => $achievements_html,
          'media_coverage'  => $media_coverage_html,
          'cv_target_id'    => $cv_id,
          'cv_path'         => $cv_name,
          'year_experience' => $profile['year_of_experience'],
          'sector_worked'   => $this->sector_options($profile['sector_last_worked']),
          'password_show'   => $password_show,
          'addr_checked'    => $profile['address_checked'],
          'x_countries'     => $x_countries_options,
          'x_states'        => $x_states_options,
          'x_cities'        => $x_cities_options,
          'x_organisation'  => $this->oraganisation_options(),
          'x_industry'      => $this->industry_options(),
          'prof_img'        => $prof_img,
          'fun_img'         => $fun_img,
          'csrf_token'      => $random
        );
        return [
          '#theme'    => 'page__my_account',
          '#title'    => $this->t('My Account'),
          '#test_var' => $this->t('user-'.$uid),
          '#doubles'  => $data,
        ];
      }
    }
    
    
    public function avail_campus_facilities() {
      $query = \Drupal::entityQuery('node')
            ->condition('status', 1)
            ->condition('type', 'avail_campus_facilities');
      $nids = $query->execute();
      $nodes = entity_load_multiple('node', $nids);
      foreach($nodes as $node) {
        if($node->field_image->target_id != NULL) {
          $original_image = $node->field_image->entity->getFileUri();
          //$style = ImageStyle::load('featured');  // Load the image style configuration entity.
          $url = file_create_url($original_image);
        } else {
          $url = '';
        }
        $node_url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$node->nid->value);
        $shortdesc = preg_replace('/\s+?(\S+)?$/', '', substr($node->body->value, 0, 150)). '...';  
        $data[] = array(
          'title' => $node->title->value,
          'desc'  => $shortdesc,
          'image' => $url,
          'alias' => $node_url,
        );
      }
      
      $frm_links = '<ul>';
      $qry = \Drupal::entityQuery('node')
            ->condition('status', 1)
            ->condition('type', 'campus_infrastructure');
      $nids = $qry->execute();
      $nodes = entity_load_multiple('node', $nids);
      foreach($nodes as $node) {
        $node_url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$node->nid->value);
        $frm_links = '<li><a href="'.$node_url.'">'.$node->title->value.'</a></li>';
      }
      $frm_links = '</ul>';
      
      
      return [
        '#theme'    => 'page__irma__avail_campus_facilities',
        '#cur_page' => $this->t('landing_page'),
        '#test_var' => $this->t('Test Value'),
        '#frm_links'=> $frm_links,
        '#data_obj' => $data,
      ];
    }
    
    public function get_involved() {
      $query = \Drupal::entityQuery('node')
            ->condition('status', 1)
            ->condition('type', 'get_involved');
      $nids = $query->execute();
      $nodes = entity_load_multiple('node', $nids);
      foreach($nodes as $node) {
        if($node->field_image->target_id != NULL) {
          $original_image = $node->field_image->entity->getFileUri();
          //$style = ImageStyle::load('featured');  // Load the image style configuration entity.
          $url = file_create_url($original_image);
        } else {
          $url = '';
        }
        $node_url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$node->nid->value);
        $shortdesc = preg_replace('/\s+?(\S+)?$/', '', substr($node->body->value, 0, 150)). '...';  
        $data[] = array(
          'title' => $node->title->value,
          'desc'  => $shortdesc,
          'image' => $url,
          'alias' => $node_url,
        );
      }
      
      return [
        '#theme'    => 'page__irma__get_involved',
        '#cur_page' => $this->t('get-involved'),
        '#test_var' => $this->t('Test Value'),
        '#data_obj' => $data,
      ];
    }
    
    public function give_to_iaa_apply() {
      $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
      $name   = $user->get('name')->value;
      $uid    = $user->get('uid')->value;
      $email  = $user->get('mail')->value;
      $query = \Drupal::database()->select('users_extra', 'ue');
      $query->fields('ue', ['user_type', 'name', 'address_1', 'address_2', 'address_3','batch_number', 'country_code', 'country', 'state','city', 'mobile_number']);
      $query->condition('ue.entity_id', $uid);
      
      $qry = \Drupal::database()->select('users_profile', 'up');
      $qry->fields('up', ['professional_background']);
      $qry->condition('up.entity_id', $uid);
      
      $hashed               = new PhpassHashedPassword();
      $salt                 = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random               = $hashed->hash($this->generate_random_string());
      $token                = md5($salt.$random);
      $_SESSION['iaa']  = $token;
      
      $organisation = '';
      $designation  = '';

      $usr  = $query->execute()->fetchAssoc();
      $prof = $qry->execute()->fetchAssoc();
      $profile = json_decode($prof['professional_background']);
      foreach($profile as $exp) {
        if($exp->workHereChk) {
          $organisation = $exp->organisation;
          $designation  = $exp->designation;
        }
      }
      
      $countries = json_decode($this->countries_options(strtolower(trim($usr['country']))));
      $countries_options = $countries->options;
      $country_id = $countries->id;
      
      $states = json_decode($this->states_options($country_id,strtolower(trim($usr['state']))));
      $states_options = $states->options;
      $state_id = $states->id;
      
      $cities_options = $this->cities_options($state_id,strtolower(trim($usr['city'])));
      
      $temp = explode(' ', $usr['name']);
      $first_name = $temp[0];
      $last_name = $temp[1];
      $temp = explode('-', $usr['mobile_number']);
      $country_code = $temp[0];
      $mobile_number = $temp[1];
      $data   = array(
        'uid'         => $uid,
        'user_type'   => $usr['user_type'].'-'.$uid,
        'first_name'  => $first_name,
        'last_name'   => $last_name,
        'mail'        => $email,
        'address1'    => $usr['address_1'],
        'address2'    => $usr['address_2'],
        'address3'    => $usr['address_3'],
        'organisation'=> $this->oraganisation_options($organisation),
        'designation' => $designation,
        'country'     => $countries_options,
        'state'       => $states_options,
        'country_code'=> trim($country_code),
        'city'        => $cities_options,
        'mobile'      => trim($mobile_number),
        'batch_no'    => $this->batch_options($usr['batch_number']),
        'token'       => $random,
      );
      
      return [
        '#theme'    => 'page__alumni_network__give_to_iaa',
        '#cur_page' => $this->t('apply'),
        '#test_var' => $this->t('Test Value'),
        '#data_obj' => $data,
      ];
    }
    
    public function collaborate_project_apply() {
      $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
      $name   = $user->get('name')->value;
      $uid    = $user->get('uid')->value;
      $email  = $user->get('mail')->value;
      $query = \Drupal::database()->select('users_extra', 'ue');
      $query->fields('ue', ['user_type', 'name', 'joining_year', 'graduating_year', 'batch_number', 'country_code', 'country', 'state', 'city', 'mobile_number']);
      $query->condition('ue.entity_id', $uid);
      
      $hashed               = new PhpassHashedPassword();
      $salt                 = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random               = $hashed->hash($this->generate_random_string());
      $token                = md5($salt.$random);
      $_SESSION['projApp']  = $token;

      $usr = $query->execute()->fetchAssoc();
      $organisation = '';
      $designation  = '';
      
      $qry = \Drupal::database()->select('users_profile', 'up');
      $qry->fields('up', ['professional_background']);
      $qry->condition('up.entity_id', $uid);
      $prof = $qry->execute()->fetchAssoc();
      $profile = json_decode($prof['professional_background']);
      
      foreach($profile as $exp) {
        if($exp->workHereChk) {
          $organisation = $exp->organisation;
          $designation  = $exp->designation;
        }
      }
      
      $countries = json_decode($this->countries_options(strtolower(trim($usr['country']))));
      $countries_options = $countries->options;
      $country_id = $countries->id;
      $states = json_decode($this->states_options($country_id,strtolower(trim($usr['state']))));
      $states_options = $states->options;
      $state_id = $states->id;
      $cities_options = $this->cities_options($state_id,strtolower(trim($usr['city'])));
      
      $temp = explode(' ', $usr['name']);
      $first_name = $temp[0];
      $last_name = $temp[1];
      $temp = explode('-', $usr['mobile_number']);
      $country_code = $temp[0];
      $mobile_number = $temp[1];
      $data   = array(
        'uid'         => $uid,
        'user_type'   => $usr['user_type'].'-'.$uid,
        'first_name'  => $first_name,
        'last_name'   => $last_name,
        'mail'        => $email,
        'country'     => $countries_options,
        'state'       => $states_options,
        'country_code'=> trim($country_code),
        'city'        => $cities_options,
        'mobile'      => trim($mobile_number),
        'batch_no'    => $this->batch_options($usr['batch_number']),
        'organisation'=> $this->oraganisation_options($organisation),
        'designation' => $designation,
        'token'       => $random,
      );
      
      return [
        '#theme'    => 'page__irma__get_involved',
        '#cur_page' => $this->t('collab_project'),
        '#test_var' => $this->t('Test Value'),
        '#data_obj' => $data,
      ];
    }
    
    public function classroom_session_apply() {
      $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
      $name   = $user->get('name')->value;
      $uid    = $user->get('uid')->value;
      $email  = $user->get('mail')->value;
      $query = \Drupal::database()->select('users_extra', 'ue');
      $query->fields('ue', ['user_type', 'name', 'joining_year', 'graduating_year', 'batch_number', 'country_code', 'country', 'state', 'city', 'mobile_number']);
      $query->condition('ue.entity_id', $uid);
      
      $hashed               = new PhpassHashedPassword();
      $salt                 = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random               = $hashed->hash($this->generate_random_string());
      $token                = md5($salt.$random);
      $_SESSION['sessApp']  = $token;

      $usr = $query->execute()->fetchAssoc();
      $organisation = '';
      $designation  = '';
      
      $qry = \Drupal::database()->select('users_profile', 'up');
      $qry->fields('up', ['professional_background']);
      $qry->condition('up.entity_id', $uid);
      $prof = $qry->execute()->fetchAssoc();
      $profile = json_decode($prof['professional_background']);
      
      foreach($profile as $exp) {
        if($exp->workHereChk) {
          $organisation = $exp->organisation;
          $designation  = $exp->designation;
        }
      }
      
      $countries = json_decode($this->countries_options(strtolower(trim($usr['country']))));
      $countries_options = $countries->options;
      $country_id = $countries->id;
      $states = json_decode($this->states_options($country_id,strtolower(trim($usr['state']))));
      $states_options = $states->options;
      $state_id = $states->id;
      $cities_options = $this->cities_options($state_id,strtolower(trim($usr['city'])));
      
      $temp = explode(' ', $usr['name']);
      $first_name = $temp[0];
      $last_name = $temp[1];
      $temp = explode('-', $usr['mobile_number']);
      $country_code = $temp[0];
      $mobile_number = $temp[1];
      $data   = array(
        'uid'         => $uid,
        'user_type'   => $usr['user_type'].'-'.$uid,
        'first_name'  => $first_name,
        'last_name'   => $last_name,
        'mail'        => $email,
        'country'     => $countries_options,
        'state'       => $states_options,
        'country_code'=> trim($country_code),
        'city'        => $cities_options,
        'mobile'      => trim($mobile_number),
        'batch_no'    => $this->batch_options($usr['batch_number']),
        'organisation'=> $this->oraganisation_options($organisation),
        'designation' => $designation,
        'token'       => $random,
      );
      
      return [
        '#theme'    => 'page__irma__get_involved',
        '#cur_page' => $this->t('classroom_sessions'),
        '#test_var' => $this->t('Test Value'),
        '#data_obj' => $data,
      ];
    }
    
    public function codevelop_case_study() {
      $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
      $name   = $user->get('name')->value;
      $uid    = $user->get('uid')->value;
      $email  = $user->get('mail')->value;
      $query = \Drupal::database()->select('users_extra', 'ue');
      $query->fields('ue', ['user_type', 'name', 'joining_year', 'graduating_year', 'batch_number', 'country_code', 'country', 'state', 'city', 'mobile_number']);
      $query->condition('ue.entity_id', $uid);
      
      $hashed               = new PhpassHashedPassword();
      $salt                 = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random               = $hashed->hash($this->generate_random_string());
      $token                = md5($salt.$random);
      $_SESSION['caStudy']  = $token;

      $usr = $query->execute()->fetchAssoc();
      $organisation = '';
      $designation  = '';
      
      $qry = \Drupal::database()->select('users_profile', 'up');
      $qry->fields('up', ['professional_background']);
      $qry->condition('up.entity_id', $uid);
      $prof = $qry->execute()->fetchAssoc();
      $profile = json_decode($prof['professional_background']);
      
      foreach($profile as $exp) {
        if($exp->workHereChk) {
          $organisation = $exp->organisation;
          $designation  = $exp->designation;
        }
      }
      
      $countries = json_decode($this->countries_options(strtolower(trim($usr['country']))));
      $countries_options = $countries->options;
      $country_id = $countries->id;
      $states = json_decode($this->states_options($country_id,strtolower(trim($usr['state']))));
      $states_options = $states->options;
      $state_id = $states->id;
      $cities_options = $this->cities_options($state_id,strtolower(trim($usr['city'])));
      
      $temp = explode(' ', $usr['name']);
      $first_name = $temp[0];
      $last_name = $temp[1];
      $temp = explode('-', $usr['mobile_number']);
      $country_code = $temp[0];
      $mobile_number = $temp[1];
      $data   = array(
        'uid'         => $uid,
        'user_type'   => $usr['user_type'].'-'.$uid,
        'first_name'  => $first_name,
        'last_name'   => $last_name,
        'mail'        => $email,
        'country'     => $countries_options,
        'state'       => $states_options,
        'country_code'=> trim($country_code),
        'city'        => $cities_options,
        'mobile'      => trim($mobile_number),
        'batch_no'    => $this->batch_options($usr['batch_number']),
        'organisation'=> $this->oraganisation_options($organisation),
        'designation' => $designation,
        'token'       => $random,
      );
      
      return [
        '#theme'    => 'page__irma__get_involved',
        '#cur_page' => $this->t('case_study'),
        '#test_var' => $this->t('Test Value'),
        '#data_obj' => $data,
      ];
    }
    
    public function refer_recruiter() {
      $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
      $name   = $user->get('name')->value;
      $uid    = $user->get('uid')->value;
      $email  = $user->get('mail')->value;
      $query = \Drupal::database()->select('users_extra', 'ue');
      $query->fields('ue', ['user_type', 'name', 'joining_year', 'graduating_year', 'batch_number', 'country_code', 'country', 'state', 'city', 'mobile_number']);
      $query->condition('ue.entity_id', $uid);
      
      $hashed               = new PhpassHashedPassword();
      $salt                 = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random               = $hashed->hash($this->generate_random_string());
      $token                = md5($salt.$random);
      $_SESSION['refRecruit']  = $token;

      $usr = $query->execute()->fetchAssoc();
      $organisation = '';
      $designation  = '';
      
      $qry = \Drupal::database()->select('users_profile', 'up');
      $qry->fields('up', ['professional_background']);
      $qry->condition('up.entity_id', $uid);
      $prof = $qry->execute()->fetchAssoc();
      $profile = json_decode($prof['professional_background']);
      
      foreach($profile as $exp) {
        if($exp->workHereChk) {
          $organisation = $exp->organisation;
          $designation  = $exp->designation;
        }
      }
      
      $countries = json_decode($this->countries_options(strtolower(trim($usr['country']))));
      $countries_options = $countries->options;
      $country_id = $countries->id;
      $states = json_decode($this->states_options($country_id,strtolower(trim($usr['state']))));
      $states_options = $states->options;
      $state_id = $states->id;
      $cities_options = $this->cities_options($state_id,strtolower(trim($usr['city'])));
      
      $temp = explode(' ', $usr['name']);
      $first_name = $temp[0];
      $last_name = $temp[1];
      $temp = explode('-', $usr['mobile_number']);
      $country_code = $temp[0];
      $mobile_number = $temp[1];
      $data   = array(
        'uid'         => $uid,
        'user_type'   => $usr['user_type'].'-'.$uid,
        'first_name'  => $first_name,
        'last_name'   => $last_name,
        'mail'        => $email,
        'country'     => $countries_options,
        'state'       => $states_options,
        'country_code'=> trim($country_code),
        'city'        => $cities_options,
        'mobile'      => trim($mobile_number),
        'batch_no'    => $this->batch_options($usr['batch_number']),
        'organisation'=> $this->oraganisation_options($organisation),
        'designation' => $designation,
        'token'       => $random,
      );
      
      return [
        '#theme'    => 'page__irma__get_involved',
        '#cur_page' => $this->t('refer_recruiter'),
        '#test_var' => $this->t('Test Value'),
        '#data_obj' => $data,
      ];
    }
    
    public function invite_faculty_workshop() {
      $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
      $name   = $user->get('name')->value;
      $uid    = $user->get('uid')->value;
      $email  = $user->get('mail')->value;
      $query = \Drupal::database()->select('users_extra', 'ue');
      $query->fields('ue', ['user_type', 'name', 'joining_year', 'graduating_year', 'batch_number', 'country_code', 'country', 'state','city', 'mobile_number']);
      $query->condition('ue.entity_id', $uid);
      
      $hashed               = new PhpassHashedPassword();
      $salt                 = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random               = $hashed->hash($this->generate_random_string());
      $token                = md5($salt.$random);
      $_SESSION['factInv']  = $token;

      $usr = $query->execute()->fetchAssoc();
      $organisation = '';
      $designation  = '';
      
      $qry = \Drupal::database()->select('users_profile', 'up');
      $qry->fields('up', ['professional_background']);
      $qry->condition('up.entity_id', $uid);
      $prof = $qry->execute()->fetchAssoc();
      $profile = json_decode($prof['professional_background']);
      
      foreach($profile as $exp) {
        if($exp->workHereChk) {
          $organisation = $exp->organisation;
          $designation  = $exp->designation;
        }
      }
      
      $countries = json_decode($this->countries_options(strtolower(trim($usr['country']))));
      $countries_options = $countries->options;
      $country_id = $countries->id;
      $states = json_decode($this->states_options($country_id,strtolower(trim($usr['state']))));
      $states_options = $states->options;
      $state_id = $states->id;
      $cities_options = $this->cities_options($state_id,strtolower(trim($usr['city'])));
      
      $temp = explode(' ', $usr['name']);
      $first_name = $temp[0];
      $last_name = $temp[1];
      $temp = explode('-', $usr['mobile_number']);
      $country_code = $temp[0];
      $mobile_number = $temp[1];
      $data   = array(
        'uid'         => $uid,
        'user_type'   => $usr['user_type'].'-'.$uid,
        'first_name'  => $first_name,
        'last_name'   => $last_name,
        'mail'        => $email,
        'country'     => $countries_options,
        'state'       => $states_options,
        'country_code'=> trim($country_code),
        'city'        => $cities_options,
        'mobile'      => trim($mobile_number),
        'batch_no'    => $this->batch_options($usr['batch_number']),
        'organisation'=> $this->oraganisation_options($organisation),
        'designation' => $designation,
        'token'       => $random,
      );
      
      return [
        '#theme'    => 'page__irma__get_involved',
        '#cur_page' => $this->t('faculty_workshop'),
        '#test_var' => $this->t('Test Value'),
        '#data_obj' => $data,
      ];
    }

    public function management_development_program_apply() {
      $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
      $name   = $user->get('name')->value;
      $uid    = $user->get('uid')->value;
      $email  = $user->get('mail')->value;
      $query = \Drupal::database()->select('users_extra', 'ue');
      $query->fields('ue', ['user_type', 'name', 'joining_year', 'graduating_year', 'batch_number', 'country_code', 'country', 'state', 'city', 'mobile_number']);
      $query->condition('ue.entity_id', $uid);
      
      $hashed               = new PhpassHashedPassword();
      $salt                 = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random               = $hashed->hash($this->generate_random_string());
      $token                = md5($salt.$random);
      $_SESSION['mdpApply'] = $token;
      $usr = $query->execute()->fetchAssoc();
      $temp = explode(' ', $usr['name']);
      $first_name = $temp[0];
      $last_name = $temp[1];
      $temp = explode('-', $usr['mobile_number']);
      $country_code = $temp[0];
      $mobile_number = $temp[1];
      
      $organisation = '';
      $designation  = '';
      
      $qry = \Drupal::database()->select('users_profile', 'up');
      $qry->fields('up', ['professional_background']);
      $qry->condition('up.entity_id', $uid);
      $prof = $qry->execute()->fetchAssoc();
      $profile = json_decode($prof['professional_background']);
      
      foreach($profile as $exp) {
        if($exp->workHereChk) {
          $organisation = $exp->organisation;
          $designation  = $exp->designation;
        }
      }
      
      $countries = json_decode($this->countries_options(strtolower(trim($usr['country']))));
      $countries_options = $countries->options;
      $country_id = $countries->id;
      $states = json_decode($this->states_options($country_id,strtolower(trim($usr['state']))));
      $states_options = $states->options;
      $state_id = $states->id;
      $cities_options = $this->cities_options($state_id,strtolower(trim($usr['city'])));
      
      $data   = array(
        'uid'         => $uid,
        'user_type'   => $usr['user_type'].'-'.$uid,
        'first_name'  => $first_name,
        'last_name'   => $last_name,
        'mail'        => $email,
        'country'     => $countries_options,
        'state'       => $states_options,
        'country_code'=> trim($country_code),
        'city'        => $cities_options,
        'mobile'      => trim($mobile_number),
        'batch_no'    => $this->batch_options($usr['batch_number']),
        'designation' => $designation,
        'organisation'=> $this->oraganisation_options($organisation),
        'token'       => $random,
      );
      
      return [
        '#theme'    => 'page__irma__avail_campus_facilities',
        '#cur_page' => $this->t('MDP'),
        '#test_var' => $this->t('Test Value'),
        '#data_obj' => $data,
      ];
    }
    public function campus_infra_apply($arg) {
      //echo $name; exit;
      $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
      $name   = $user->get('name')->value;
      $uid    = $user->get('uid')->value;
      $email  = $user->get('mail')->value;
      $query = \Drupal::database()->select('users_extra', 'ue');
      $query->fields('ue', ['user_type', 'name', 'joining_year', 'graduating_year', 'batch_number', 'country_code', 'country', 'state', 'city', 'mobile_number']);
      $query->condition('ue.entity_id', $uid);
      $usr = $query->execute()->fetchAssoc();
      
      $hashed               = new PhpassHashedPassword();
      $salt                 = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random               = $hashed->hash($this->generate_random_string());
      $token                = md5($salt.$random);
      if($arg == 'etdc') {
        $_SESSION['etdcApp']  = $token;
      }
      if($arg == 'wifi-access') {
        $_SESSION['wifiApp']  = $token;
      }
      if($arg == 'sac') {
        $_SESSION['sacApp']  = $token;
      }
      if($arg == 'students-mess') {
        $_SESSION['messApp']  = $token;
      }
      if($arg == 'library') {
        $_SESSION['libApp']  = $token;
      }
      
      $organisation = '';
      $designation  = '';
      
      $qry = \Drupal::database()->select('users_profile', 'up');
      $qry->fields('up', ['professional_background']);
      $qry->condition('up.entity_id', $uid);
      $prof = $qry->execute()->fetchAssoc();
      $profile = json_decode($prof['professional_background']);
      
      foreach($profile as $exp) {
        if($exp->workHereChk) {
          $organisation = $exp->organisation;
          $designation  = $exp->designation;
        }
      }
      
      $countries = json_decode($this->countries_options(strtolower(trim($usr['country']))));
      $countries_options = $countries->options;
      $country_id = $countries->id;
      $states = json_decode($this->states_options($country_id,strtolower(trim($usr['state']))));
      $states_options = $states->options;
      $state_id = $states->id;
      $cities_options = $this->cities_options($state_id,strtolower(trim($usr['city'])));
      
      
      $temp = explode(' ', $usr['name']);
      $first_name = $temp[0];
      $last_name = $temp[1];
      $temp = explode('-', $usr['mobile_number']);
      $country_code = $temp[0];
      $mobile_number = $temp[1];
      $data   = array(
        'uid'         => $uid,
        'user_type'   => $usr['user_type'].'-'.$uid,
        'first_name'  => $first_name,
        'last_name'   => $last_name,
        'mail'        => $email,
        'country'     => $countries_options,
        'state'       => $states_options,
        'country_code'=> trim($country_code),
        'city'        => $cities_options,
        'mobile'      => trim($mobile_number),
        'batch_no'    => $this->batch_options($usr['batch_number']),
        'designation' => $designation,
        'organisation'=> $this->oraganisation_options($organisation),
        'token'       => $random,
      );
      
      return [
        '#theme'    => 'page__irma__avail_campus_facilities',
        '#cur_page' => $arg,
        '#test_var' => $this->t('Test Value'),
        '#data_obj' => $data,
      ];
    }
    
    public function archived_events() {
      $cur_date = \Drupal::time()->getRequestTime();
      $cur_date = date('Y-m-d', $cur_date);
      $output = '';
      $query = \Drupal::entityQuery('node')
          ->condition('status', 1)
          ->condition('type', 'events')
          ->condition('field_event_date.value', $cur_date, '>')
          ->sort('created', 'DESC');
          //->range(0,6);
      $nids = $query->execute();
      $nodes = entity_load_multiple('node', $nids);
      $count = 1;
      $first = true;
      $element = array(
        '#markup' => 'Upcoming Events',
      );
      return $element;
    }
    
    public function register() {
      $logged_in = \Drupal::currentUser()->isAuthenticated();
      if($logged_in) {
        return new RedirectResponse(\Drupal::url('<front>'));
      }
      
      $hashed             = new PhpassHashedPassword();
      $salt               = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random             = $hashed->hash($this->generate_random_string());
      $token              = md5($salt.$random);
      $_SESSION['usRegn'] = $token;
      $markup = '<input type="hidden" id="csrftoken" value="'.$random.'">';
      $element = array(
        '#markup' => $this->t($markup),
      );
      return $element;
    }
    
    public function search() {
      if($_POST['actFrm'] == 'in') {
        $srch_txt = trim($_POST['srchSecTxt']);
      } else {
        $srch_txt = trim($_POST['srchTxt']);
      }
      $output = '';
      if($srch_txt != '') {
        $query = \Drupal::entityQuery('node');
        $group = $query->orConditionGroup()
              ->condition('title', '%'.$srch_txt.'%', 'LIKE')
              ->condition('body.value', '%'.$srch_txt.'%', 'LIKE');
        $entity_ids = $query
              ->condition('status', 1)
              ->condition($group)
              ->sort('nid', $sort)
              ->execute();
        $count = $query->count()->execute();
        
        $node_count = 0;
        
        
        $srch_html = '';
        if($entity_ids) {
          foreach($entity_ids as $nid) {
            $alias = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$nid);
            if(strlen(strstr($alias,'/node/')) > 0) {
              // discard node
              $count--;
            } else {
              if($node_count < 10) {
                $node = Node::load($nid);
                $desc = preg_replace('/<h3\b[^>]*>(.*?)<\/h3>/i', '', $node->body->value);
                if(strlen($desc) >= 300) {
                  $shortdesc  = preg_replace('/\s+?(\S+)?$/', '', substr($desc, 0, 300)).'...';
                } else {
                  $shortdesc = $desc;
                }
                $srch_html .= '<li rel="'.$node->nid->value.'"><a href="'.$alias.'"><h5>'.$node->title->value.'</h5></a>'.$shortdesc.'</li>';
              }
              $node_count++;
            }
          }
          
        } 
        
        $output .= '<div class="container">';
        if($node_count > 0) {
          $output .= '<span class="tx_search">Showing '.$node_count.' 0f '.$count.' results</span>';
        } else {
          $output .= '<span class="tx_search">No results found</span>';
        }
        $output .= '</div>';
        $output .= '<div class="result_section"><div class="container"><ul class="alSrchList">';
        $output .= $srch_html;
        if($node_count > 10) {
          $output .= '</ul><div class="center_align"><a href="javascript:;" class="button srchLoadMore">Load More</a></div></div></div>';
        } else {
          $output .= '</ul></div></div>';
        }
        
        $output .= '<div class="srchHidden dnone" rel="'.$node_count.'">'.$srch_txt.'</div>';
      } 
      $element = array(
        '#title'  => t('Search Results'),  
        '#markup' => $output,
      );
      return $element;
    }
    
    public function user_logout() {
      user_logout();
      return new RedirectResponse(\Drupal::url('<front>'));
    }
    
    public function generate_random_string($length = 10) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $char_length = strlen($characters);
      $random_str = '';
      for ($i = 0; $i < $length; $i++) {
        $random_str .= $characters[rand(0, $char_length - 1)];
      }
      return $random_str;
    }
    
    public function event_registration($nid) {
      $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
      $name   = $user->get('name')->value;
      $uid    = $user->get('uid')->value;
      $email  = $user->get('mail')->value;
     
      $query = \Drupal::database()->select('users_extra', 'ue');
      $query->fields('ue', ['user_type', 'name', 'address_1', 'address_2', 'address_3','batch_number', 'country_code', 'country', 'state','city', 'mobile_number']);
      $query->condition('ue.entity_id', $uid);
      
      $qry = \Drupal::database()->select('users_profile', 'up');
      $qry->fields('up', ['professional_background']);
      $qry->condition('up.entity_id', $uid);
      
      $usr  = $query->execute()->fetchAssoc();
      
      $countries = json_decode($this->countries_options(strtolower(trim($usr['country']))));
      $countries_options = $countries->options;
      $country_id = $countries->id;
      $states = json_decode($this->states_options($country_id,strtolower(trim($usr['state']))));
      $states_options = $states->options;
      $state_id = $states->id;
      $cities_options = $this->cities_options($state_id,strtolower(trim($usr['city'])));
      
      $node = Node::load($nid);
      //echo '<pre>'; print_r($node); echo '</pre>';
      $event_name = $node->field_event_name->value;
      $event_date = $node->field_event_date->value;
      $hashed               = new PhpassHashedPassword();
      $salt                 = hash_hmac('sha256', 'AAMRII', 'iirmaa');
      $random               = $hashed->hash($this->generate_random_string());
      $token                = md5($salt.$random);
      $_SESSION['evntReg']  = $token;
      
      $organisation = '';
      $designation  = '';
      //echo '<pre>'; print_r($usr); echo '</pre>'; exit;
      $prof = $qry->execute()->fetchAssoc();
      $profile = json_decode($prof['professional_background']);
      foreach($profile as $exp) {
        if($exp->workHereChk) {
          $organisation = $exp->organisation;
          $designation  = $exp->designation;
        }
      }
      
      $countries = json_decode($this->countries_options(strtolower(trim($usr['country']))));
      $countries_options = $countries->options;
      $country_id = $countries->id;
      $states = json_decode($this->states_options($country_id,strtolower(trim($usr['state']))));
      
      $states_options = $states->options;
      $state_id = $states->id;
      
      $cities_options = $this->cities_options($state_id,strtolower(trim($usr['city'])));

      $temp = explode(' ', $usr['name']);
      $first_name = $temp[0];
      $last_name = $temp[1];
      $temp = explode('-', $usr['mobile_number']);
      $country_code = $temp[0];
      $mobile_number = $temp[1];
      
      
      $data   = array(
        'uid'         => $uid,
        'user_type'   => $usr['user_type'].'-'.$uid,
        'event_name'  => $event_name,
        'first_name'  => $first_name,
        'last_name'   => $last_name,
        'mail'        => $email,
        'organisation'=> $this->oraganisation_options($organisation),
        'designation' => $designation,
        'country'     => $countries_options,
        'state'       => $states_options,
        'city'        => $cities_options,
        'country_code'=> trim($country_code),
        'mobile'      => trim($mobile_number),
        'batch_no'    => $this->batch_options($usr['batch_number']),
        'token'       => $random,
      );
      
      // $render = array(
      //  '#theme'    => 'page__alumni_network__events__register',
      //  '#title'    => $this->t('Event Register'),
      //  '#test_var' => $this->t('page-event-register'),
      //  '#data'     => $data,
      //);       
      //return $render;
      
      return [
        '#theme'    => 'page__alumni_network__events__register',
        '#title'    => $this->t('Event Register'),
        '#test_var' => $this->t('page-event-register'),
        '#data'     => $data,
      ];
    }

    public function sector_options($sector=NULL) {
      $query = \Drupal::database()->select('taxonomy_term_field_data', 'ttfd');
      $query->fields('ttfd', ['tid', 'vid', 'name']);
      $query->condition('ttfd.vid', 'work_sectors');
      $query->orderBy('ttfd.name', 'ASC');
      $results = $query->execute()->fetchAllAssoc('tid');
      $options = '';
      foreach($results as $row) {
        if(trim($row->name) == trim($sector)) {
          $options .= '<option value="'.$row->name.'" selected>'.$row->name.'</option>';
        } else {
          $options .= '<option value="'.$row->name.'">'.$row->name.'</option>';
        }
      }
      return $options;
    }
    public function oraganisation_options($organ=NULL) {
      $query = \Drupal::database()->select('taxonomy_term_field_data', 'ttfd');
      $query->fields('ttfd', ['tid', 'vid', 'name']);
      $query->condition('ttfd.vid', 'organization');
      $query->orderBy('ttfd.name', 'ASC');
      $results = $query->execute()->fetchAllAssoc('tid');
      $options = '';
      foreach($results as $row) {
        if(trim($row->name) == trim($organ)) {
          $options .= '<option value="'.$row->name.'" selected>'.$row->name.'</option>';
        } else {
          $options .= '<option value="'.$row->name.'">'.$row->name.'</option>';
        }
      }
      return $options;
    }
    
    public function industry_options($arg=NULL) {
      $query = \Drupal::database()->select('taxonomy_term_field_data', 'ttfd');
      $query->fields('ttfd', ['tid', 'vid', 'name']);
      $query->condition('ttfd.vid', 'industry');
      $query->orderBy('ttfd.tid', 'ASC');
      $results = $query->execute()->fetchAllAssoc('tid');
      $options = '';
      foreach($results as $row) {
        if(trim($row->name) == trim($arg)) {
          $options .= '<option value="'.$row->name.'" selected>'.$row->name.'</option>';
        } else {
          $options .= '<option value="'.$row->name.'">'.$row->name.'</option>';
        }
      }
      return $options;
    }
    
    public function course_type_options($arg=NULL) {
      $query = \Drupal::database()->select('taxonomy_term_field_data', 'ttfd');
      $query->fields('ttfd', ['tid', 'vid', 'name']);
      $query->condition('ttfd.vid', 'course_type');
      $query->orderBy('ttfd.tid', 'ASC');
      $results = $query->execute()->fetchAllAssoc('tid');
      $options = '';
      foreach($results as $row) {
        if(trim($row->name) == trim($arg)) {
          $options .= '<option value="'.$row->name.'" selected>'.$row->name.'</option>';
        } else {
          $options .= '<option value="'.$row->name.'">'.$row->name.'</option>';
        }
      }
      return $options;
    }
    
    public function batch_options($arg=NULL) {
      $query = \Drupal::database()->select('taxonomy_term_field_data', 'ttfd');
      $query->fields('ttfd', ['tid', 'vid', 'name']);
      $query->condition('ttfd.vid', 'batch_number');
      $query->orderBy('ttfd.tid', 'ASC');
      $results = $query->execute()->fetchAllAssoc('tid');
      $options = '';
      foreach($results as $row) {
        if(trim($row->name) == trim($arg)) {
          $options .= '<option value="'.$row->name.'" selected>'.$row->name.'</option>';
        } else {
          $options .= '<option value="'.$row->name.'">'.$row->name.'</option>';
        }
      }
      return $options;
    }
    
    public function subject_group_options($group=NULL) {
      $query = \Drupal::database()->select('taxonomy_term_field_data', 'ttfd');
      $query->fields('ttfd', ['tid', 'vid', 'name']);
      $query->condition('ttfd.vid', 'subject_group');
      $query->orderBy('ttfd.tid', 'ASC');
      $results = $query->execute()->fetchAllAssoc('tid');
      $options = '';
      foreach($results as $row) {
        if(trim($row->name) == trim($group)) {
          $options .= '<option value="'.$row->name.'" selected>'.$row->name.'</option>';
        } else {
          $options .= '<option value="'.$row->name.'">'.$row->name.'</option>';
        }
      }
      return $options;
    }
    
    public function area_group_options($group=NULL) {
      $query = \Drupal::database()->select('taxonomy_term_field_data', 'ttfd');
      $query->fields('ttfd', ['tid', 'vid', 'name']);
      $query->condition('ttfd.vid', 'area_group');
      $query->orderBy('ttfd.tid', 'ASC');
      $results = $query->execute()->fetchAllAssoc('tid');
      $options = '';
      foreach($results as $row) {
        if($row->name == $group) {
          $options .= '<option value="'.$row->name.'" selected>'.$row->name.'</option>';
        } else {
          $options .= '<option value="'.$row->name.'">'.$row->name.'</option>';
        }
      }
      return $options;
    }
    
    public function countries_options($cntry='india') {
      $query = \Drupal::database()->select('countries', 'c');
      $query->fields('c', ['id', 'sortname', 'name', 'phonecode']);
      $query->orderBy('c.id', 'ASC');
      $countries = $query->execute()->fetchAllAssoc('id');
      $options = '';
      $cntry_id = 0;
      foreach($countries as $country) {
        if(strtolower(trim($cntry)) == strtolower(trim($country->name))) {
          $options .= '<option id="'.$country->id.'" value="'.$country->sortname.'" rel="'.$country->phonecode.'" selected>'.$country->name.'</option>'; 
          $cntry_id = $country->id;
        } else {
          $options .= '<option id="'.$country->id.'" value="'.$country->sortname.'" rel="'.$country->phonecode.'">'.$country->name.'</option>'; 
        }
      }
      //echo $options;
      return json_encode(array('options' => $options, 'id' => $cntry_id));
    }
    
    public function states_options($id=101, $stname=NULL) {
      $query = \Drupal::database()->select('states', 'st');
      $query->fields('st', ['id', 'name', 'country_id']);
      $query->condition('st.country_id', $id);
      $query->orderBy('st.id', 'ASC');
      $states = $query->execute()->fetchAllAssoc('id');
      $options = '';
      $state_id = 0;
      foreach($states as $state) {
        if(strtolower(trim($stname)) == strtolower(trim($state->name))) {
          $options .= '<option value="'.$state->id.'" selected>'.$state->name.'</option>'; 
          $state_id = $state->id;
        } else {
          $options .= '<option value="'.$state->id.'">'.$state->name.'</option>'; 
        }
      }
      return json_encode(array('options' => $options, 'id' => $state_id));
    }
    
    public function cities_options($id=1, $ctname=NULL) {
      $query = \Drupal::database()->select('cities', 'ct');
      $query->fields('ct', ['id', 'name', 'state_id']);
      $query->condition('ct.state_id', $id);
      $query->orderBy('ct.id', 'ASC');
      $cities = $query->execute()->fetchAllAssoc('id');
      $options = '';
      foreach($cities as $city) {
        if(strtolower(trim($ctname)) == strtolower(trim($city->name))) {
          $options .= '<option value="'.$city->id.'" selected>'.$city->name.'</option>'; 
        } else {
          $options .= '<option value="'.$city->id.'">'.$city->name.'</option>'; 
        }
      }
      return $options;
    }
    
    
    
    public function change_password() {
      $logged_in = \Drupal::currentUser()->isAuthenticated(); // check if user is logged in
      if($logged_in) {
        $hashed               = new PhpassHashedPassword();
        $salt                 = hash_hmac('sha256', 'AAMRII', 'iirmaa');
        $random               = $hashed->hash($this->generate_random_string());
        $token                = md5($salt.$random);
        $_SESSION['chngPass'] = $token;
      } else {
        // redirect user to same page
        $current_path =  \Drupal::request()->getRequestUri();
      }
      
      $element = array(
        '#markup' => $this->t('<input type="hidden" id="csrftoken" value="'.$random.'">'),
      );
      return $element;
    }
    
    public function forgot_password() {
      $logged_in = \Drupal::currentUser()->isAuthenticated(); // check if user is logged in
      if(!$logged_in) {
        $hashed               = new PhpassHashedPassword();
        $salt                 = hash_hmac('sha256', 'AAMRII', 'iirmaa');
        $random               = $hashed->hash($this->generate_random_string());
        $token                = md5($salt.$random);
        $_SESSION['forgPass'] = $token;
      } else {
        // redirect user
        return new RedirectResponse(\Drupal::url('<front>')); 
      }
      $element = array(
        '#markup' => $this->t('<input type="hidden" id="csrftoken" value="'.$random.'">'),
      );
      return $element;
    }
    
    public function reset_password($arg1, $arg2, $arg3) {
      $logged_in = \Drupal::currentUser()->isAuthenticated(); // check if user is logged in
      if(!$logged_in) {
        $uid  = $arg1;
        $user = User::load($uid);
        $name = $user->name->value;
        $uuid = $user->uuid->value;
        $pass = $user->pass->value;
        if($pass == $arg3) {
          $hashed               = new PhpassHashedPassword();
          $salt                 = hash_hmac('sha256', 'AAMRII', 'iirmaa');
          $random               = $hashed->hash($this->generate_random_string());
          $token                = md5($salt.$random);
          $_SESSION['restPass'] = $token;
        } else {
          //redirect user
          return new RedirectResponse(\Drupal::url('<front>'));
        }
      } else {
        // redirect user
        return new RedirectResponse(\Drupal::url('<front>'));
      }
      
      $element = array(
        '#markup' => $this->t('<input type="hidden" id="token" value="'.$arg1.'-'.$arg3.'"><input type="hidden" id="csrftoken" value="'.$random.'">'),
      );
      return $element;
    }
    
    
    
}