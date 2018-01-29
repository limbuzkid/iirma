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
   *   id = "faculty_listing",
   *   admin_label = @Translation("Faculty Listing"),
   * )
   */

  class CustomFacultyListing extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {
      $ddown  = new CustomPagesController;
      $area_group    = $ddown->area_group_options();
      $subject_group = $ddown->subject_group_options();
      $load_more_show = false;
      global $base_url;
      
      $output = '<div class="naws_listing mainListPSrch">
      <div class="container">
        <ul class="adwanceBtn">
          <li>
            <div class="selectSearch customSelect">
              <div class="dropdownWrap">
                <a class="shortDropLink" href="javascript:;"></a>
                <select class="userType">
                  <option value="alumni">Alumni</option>
                  <option value="student">Student</option>
                  <option value="faculty" selected>Faculty</option>
                </select>
              </div>
            </div>
            <input type="text" onBlur="clearText(this)" onFocus="clearText(this)" class="name"value="Enter Name">
          </li>
          <li class="areaGrp">
            <select data-placeholder="Area Group" id="installationDiffusion" multiple="multiple" name="areaGroup" class="chosen">
            '.$area_group.'
            </select>
          </li>
          <li class="subGrp">
<div class="selectSearch customSelect">
            <select data-placeholder="Subject Group" id="installationDiffusion" multiple="multiple" name="subGroup" class="chosen">
            '.$subject_group.'   
            </select>
</div>
            <a href="javascript:;" class="searchBtn factListSrchBtn"></a>
            <a class="advancedSearch" href="javascript:;">Advanced Search</a>
          </li>
        </ul>
      </div>
    </div>';

      $output .= '<div class="managementLest mainListingP alimniListingCls"><div class="container"><ul class="alList">';
    
      $query = \Drupal::database()->select('users_field_data', 'ufd');
      $query->fields('ufd', ['uid', 'mail']);
      $query->leftJoin('users_extra', 'ue', 'ue.entity_id = ufd.uid');
      $query->fields('ue', ['name','mobile_number', 'area_group', 'subject_group', 'years_as_faculty']);
      $query->leftJoin('users_profile', 'up', 'up.entity_id = ufd.uid');
      $query->fields('up', ['nickname', 'hobbies', 'year_of_experience', 'fun_photo_id', 'linkedin_url']);
      $query->leftJoin('user__user_picture', 'upp', 'upp.entity_id = ufd.uid');
      $query->fields('upp', ['user_picture_target_id']);
      $query->leftJoin('file_managed', 'fm', 'fm.fid = upp.user_picture_target_id');
      $query->fields('fm', ['uri']);
      $query->condition('ue.user_type', 'faculty');
      $query->orderBy('ufd.uid', 'DESC');
      $query->range(0,4);
      $users = $query->execute()->fetchAllAssoc('uid');
      
      $sql = 'SELECT * FROM {users_extra} WHERE user_type = :type LIMIT 4';
      $result = db_query($sql, array(':type' => 'alumni'));
      $result->allowRowCount = TRUE; // <-- JUST ADD THIS
      $count = $result->rowCount();
  
      $cnt = 1;
      foreach($users as $user) {
        if($user->fun_photo_id != NULL) {
          $query = \Drupal::database()->select('file_managed', 'fm');
          $query->addField('fm', 'uri');
          $query->condition('fm.fid', $user->fun_photo_id);
          $query->range(0, 4);
          $furi = $query->execute()->fetchField();
          if($furi) {
            $fun_image = file_create_url($furi);
          } else {
            $fun_image = '';
          }
        } else {
          $fun_image = '';
        }
        if($cnt < 4) {
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
          
          $name         = $user->name;
          $area_group   = $user->area_group;
          $subj_group   = $user->subject_group;
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
          $output .= '</div><div class="addrs"><p>Area Group: <strong>'.$area_group.'</strong></p>
                      <p>Subject Group: <strong>'.$subj_group.'</strong></p>
                      <p>Number of years as faculty: <strong>'.$user->years_as_faculty.' years</strong></p>
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
      
      if($count > 3) {
        $output .= '</ul><a class="button loadMoreAL" href="javascript:;">Load more</a></div></div>';
      } else {
        $output .= '</ul></div></div>';
      }
      
      return array(
        '#title' => $this->t('Faculty Listing'),
        '#markup' => $this->t($output),
      );
    }
    
  }

