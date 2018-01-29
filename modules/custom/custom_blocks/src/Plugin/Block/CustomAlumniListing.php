<?php
  namespace Drupal\custom_blocks\Plugin\Block;
  
  use Drupal\custom_pages\Controller\CustomPagesController;
  use Drupal\Core\Block\BlockBase;
  use Drupal\Component\Annotation\Plugin;
  use Drupal\Core\Annotation\Translation;
  use Drupal\Core\Url;
  use Drupal\Core\Link;
  use Drupal\image\Entity\ImageStyle;
  
  /**
   * Provides a 'Custom' Block
   *
   * @Block(
   *   id = "alumni_listing",
   *   admin_label = @Translation("Alumni Listing"),
   * )
   */

  class CustomAlumniListing extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {
      
      /* You can put any PHP code here if required */
      //print ('Hello World - PHP version');
      $output = '';
      $ddown  = new CustomPagesController;
      $subj_group_options   = '';
      $area_group_options   = '';
      $organisation_options = '';
      $batch_options        = '';

      $batch_options        = $ddown->batch_options();
      $organisation_options = $ddown->oraganisation_options();
      $industry_options     = $ddown->industry_options();
      
      $graduating_year = '<option value="1979">1979</option>';
      $cur_year = date('Y');
      for($i=1980; $i<=$cur_year; $i++) {
        $graduating_year = '<option value="'.$i.'">'. $i .'</option>';
      }
      
      $output .= '<div class="naws_listing mainListPSrch">
      <div class="container">
        <ul class="adwanceBtn">
          <li>
            <div class="selectSearch customSelect">
              <div class="dropdownWrap">
                <a class="shortDropLink" href="javascript:;"></a>
                <select class="userType">
                  <option value="alumni" selected>Alumni</option>
                  <option value="student">Student</option>
                  <option value="faculty">Faculty</option>
                </select>
              </div>
            </div>
            <input type="text" onBlur="clearText(this)" onFocus="clearText(this)" class="name" value="Enter Name">
          </li>
          <li class=city>
            <input id="allCities" type="text" onblur="clearText(this)" onfocus="clearText(this)" value="City">
            <ul class="allCtsList"></ul>
          </li>
          <li class="org">
            <select data-placeholder="Organisation" id="installationDiffusion" multiple="multiple" name="installationDiffusion" class="chosen">
            '.$organisation_options.'
            </select>
          </li>
          <li class="ind">
            <select data-placeholder="Industry" id="installationDiffusion" multiple="multiple" name="installationDiffusion" class="chosen">
            '.$industry_options.'
            </select>
          </li>
          <li class="batchNum">
            <select data-placeholder="Batch Number" id="installationDiffusion" multiple="multiple" name="installationDiffusion" class="chosen">
            '.$batch_options.'
            </select>
          </li>
          <li class="gradYear">
            <div class="selectSearch customSelect">
              <div class="dropdownWrap">
                <a class="shortDropLink" href="javascript:;"></a>
                <select class="alumGradYr"><option>Batch Graduating Year</option>'.$graduating_year.'</select>
              </div>
            </div>
            <a href="javascript:;" class="searchBtn alumListSrchBtn"></a>
            <a class="advancedSearch" href="javascript:;">Advanced Search</a>
          </li>
        </ul>
      </div>
    </div>';
      
      $output .= '<div class="managementLest mainListingP alimniListingCls"><div class="container"><ul class="alList">';
      $load_more_show = false;
      global $base_url;

      $query = \Drupal::database()->select('users_field_data', 'ufd');
      $query->fields('ufd', ['uid', 'mail']);
      $query->leftJoin('users_extra', 'ue', 'ue.entity_id = ufd.uid');
      $query->fields('ue', ['name', 'country', 'state', 'city','mobile_number', 'batch_number', 'course_type', 'course_name', 'joining_year', 'graduating_year']);
      $query->leftJoin('users_profile', 'up', 'up.entity_id = ufd.uid');
      $query->fields('up', ['nickname', 'hobbies', 'professional_background','linkedin_url','fun_photo_id']);
      $query->leftJoin('user__user_picture', 'upp', 'upp.entity_id = ufd.uid');
      $query->fields('upp', ['user_picture_target_id']);
      $query->leftJoin('file_managed', 'fm', 'fm.fid = upp.user_picture_target_id');
      $query->fields('fm', ['uri']);
      $query->condition('ue.user_type', 'alumni');
      $query->orderBy('ufd.uid', 'DESC');
      $query->range(0,10);
      $users = $query->execute()->fetchAllAssoc('uid');
      
      $sql = 'SELECT * FROM {users_extra} WHERE user_type = :type LIMIT 10';
      $result = db_query($sql, array(':type' => 'alumni'));
      $result->allowRowCount = TRUE; // <-- JUST ADD THIS
      $count = $result->rowCount();
      $cnt = 1;
      foreach($users as $user) {
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
        
        if($cnt < 10) {
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
          $output .= '<p>'.$designation.' <br> '.$organisation.' </p>';
          $output .= '</div><div class="addrs"><p>Batch: <strong>'.$batch.' ('.$join_year.' to '.$grad_year.')</strong></p>
                      <p>Course/Dept: <strong>'.$course_type.'</strong></p>
                      <p>City: <strong>'.$city.', '.$state.', '.$country_code.'</strong></p></div>
                      <div class="addrs"><p>Intrests: '.$hobbies.'</p><p>Nickname: '.$nickname.'</p></div>
                      <div class="contact">
                     <a class="mob">'.$mobile.'</a>';
          if($linkedin) {
            $output .= '<a class="socialLink" href="'.$linkedin.'"></a>';
          }
          $output .= '</div></div></li>';
        }
        $cnt++;
      }
      
      if($count > 9) {
        $output .= '</ul><a class="button loadMoreAL" href="javascript:;">Load more</a></div></div>';
      } else {
        $output .= '</ul></div></div>';
      }
      
      return array(
        '#title' => $this->t('Alumni Listing'),
        '#markup' => $this->t($output),
      );
  
    }
    
    public function country_code($country) {
      $query = \Drupal::database()->select('countries', 'c');
      $query->addField('c', 'sortname');
      $query->condition('c.name', $country);
      $query->range(0, 1);
      $code = $query->execute()->fetchField();
      return $code;
    }
  }

