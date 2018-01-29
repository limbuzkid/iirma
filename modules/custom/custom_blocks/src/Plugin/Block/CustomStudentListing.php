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
   *   id = "student_listing",
   *   admin_label = @Translation("Student Listing"),
   * )
   */

  class CustomStudentListing extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {
      $ddown  = new CustomPagesController;
      $batch_options        = $ddown->batch_options();
      $sector_options       = $ddown->sector_options();
      $work_experience      = '';
      for($i=1; $i<=20; $i++) {
        $work_experience .= '<option value="'.$i.'">'. $i .'</option>';
      }
      
      $output = '<div class="naws_listing mainListPSrch">
      <div class="container">
        <ul class="adwanceBtn">
          <li>
            <div class="selectSearch customSelect">
              <div class="dropdownWrap">
                <a class="shortDropLink" href="javascript:;"></a>
                <select class="userType">
                  <option value="alumni">Alumni</option>
                  <option value="student" selected>Student</option>
                  <option value="faculty">Faculty</option>
                </select>
              </div>
            </div>
            <input type="text" onBlur="clearText(this)" onFocus="clearText(this)" class="name"value="Enter Name">
          </li>
          <li class="batchNum">
              <select data-placeholder="batch Number" id="installationDiffusion" multiple="multiple" name="batchNo" class="chosen batchNo">
              '.$batch_options.'
              </select>
          </li>
          <li class="sector">
            <select data-placeholder="Sector Last worked in" id="installationDiffusion" multiple="multiple" name="sector" class="chosen">'.$sector_options.'</select>
          </li>
          <li>
            <div class="selectSearch customSelect" style="width: 100%">
              <div class="dropdownWrap">
                <a class="shortDropLink" href="javascript:;"></a>
                <select class="workX"><option value="">Select Work experience</option>'.$work_experience.'</select>
              </div>
            </div>
            <a href="javascript:;" class="searchBtn studListSrchBtn"></a>
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
      $query->fields('ue', ['name','mobile_number', 'batch_number', 'course_type', 'course_name', 'joining_year']);
      $query->leftJoin('users_profile', 'up', 'up.entity_id = ufd.uid');
      $query->fields('up', ['nickname', 'hobbies','linkedin_url','fun_photo_id']);
      $query->leftJoin('user__user_picture', 'upp', 'upp.entity_id = ufd.uid');
      $query->fields('upp', ['user_picture_target_id']);
      $query->leftJoin('file_managed', 'fm', 'fm.fid = upp.user_picture_target_id');
      $query->fields('fm', ['uri']);
      $query->condition('ue.user_type', 'student');
      $query->orderBy('ufd.uid', 'DESC');
      $query->range(0,10);
      $users = $query->execute()->fetchAllAssoc('uid');
      
      $sql = 'SELECT * FROM {users_extra} WHERE user_type = :type LIMIT 10';
      $result = db_query($sql, array(':type' => 'student'));
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
            if($prof->workHereChk) {
              $designation = $prof->designation;
              $organisation = $prof->organisation;
            }
          }
          if($user->uri) {
            $image = file_create_url($user->uri);
          } else {
            $image = $base_url.'/sites/default/files/default.jpg';
          }
          
          $temp = explode('-', $user->mobile_number);
          if($temp[1] != NULL) {
            $mobile = $user->mobile_number;
          } else {
            $mobile = '';
          }
          
          $name         = $user->name;
          $batch        = $user->batch_number;
          $join_year    = $user->joining_year;
          $course_type  = $user->course_type;
          $hobbies      = $user->hobbies;
          $nickname     = $user->nickname;
          $mail         = $user->mail;
          $linkedin     = $user->linkedin_url;
          $output .= '<li rel="'.$user->uid.'"><div class="imgSec flip_container2"><div class="flip2"><div class="flip_front2"><img src="'.$image.'" alt=""></div>';
          if($fun_image) {
            $output .= '<div class="flip_back2"><img src="'.$fun_image.'" alt=""></div>';
          }
          $output .= '</div></div><div class="contentSec"><div class="titleSec"><h4>'.$name.'</h4>';
          $output .= '</div><div class="addrs"><p>Batch: <strong>'.$batch.'</strong></p>
                      <p>Course/Dept: <strong>'.$course_type.'</strong></p>
                      <p>Joining Year: <strong>'.$user->joining_year .'</strong></p></div>
                      <div class="addrs"><p>Interests: '.$hobbies.'</p><p>Nickname: '.$nickname.'</p></div>
                      <div class="contact">
                     <a class="mob">'.$mobile.'</a>';
          if($linkedin) {
            $output .= '<a class="socialLink" href="'.$linkedin.'" target="_blank"></a>';
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
        '#title' => $this->t('Student Listing'),
        '#markup' => $this->t($output),
      );
  
    }
  }
    

