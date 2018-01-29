<?php
  /**
   * @file
   * Contains \Drupal\user_register\Form\uploadForm.
   */
  
  namespace Drupal\user_register\Form;
  
  use Drupal\Core\Form\FormBase;
  use Drupal\Core\Form\FormStateInterface;
  use Drupal\Component\Utility\UrlHelper;
  use Drupal\file\Entity\File;
  use Drupal\user\Entity\User;
  use Drupal\Core\Password\PhpassHashedPassword;
  
  /**
   * Upload form.
   */
  class uploadForm extends FormBase {
    /**
     * {@inheritdoc}
     */
    public function getFormId() {
      return 'user_register_upload_form';
    }
  
    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
      $form = array(
        '#attributes' => array('enctype' => 'multipart/form-data'),
      );

      $form['file'] = array(
        '#prefix'             => '<div class="formFealds"><ul><li>',
        '#type'               => 'managed_file', 
        '#title'              => $this->t('Upload CSV File'),
        '#suffix'             => '</li>',
        '#upload_validators'  => [
          'file_validate_extensions' => ['csv'],
        ],
        '#upload_location' => 'public://',
        //'#description' => t('Upload a file, allowed extensions: jpg, jpeg, png, gif'),
      );
    
      $form['submit'] = array(
        '#prefix' => '<li>',
        '#type'   => 'submit',
        '#suffix' => '</li></ul></div>',
        '#value'  => t('Submit'),
      );
    
      return $form;
    }
  
    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
      // If the file passed validation:

    }
  
    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
      
      
      $query = \Drupal::database()->select('users', 'u');
      $query->addField('u', 'uid');
      $query->orderBy('uid', 'DESC');
      $query->range(0, 1);
      $uid = $query->execute()->fetchField();
      
      $uid++;
      
      $fid = $form_state->getValue(['file', 0]);
      if (!empty($fid)) {
        $file = File::load($fid);
        $file->setPermanent();
        $file->save();
      }
      $filename = str_replace('public://', '', $file->getFileUri());
      
      if($handle = fopen('sites/default/files/'.$filename, 'r')) {
        $row = fgetcsv($handle);
        //echo '<pre>';
          while ($row = fgetcsv($handle)) {
            //$rev_id = db_query("SELECT vid FROM {node} WHERE nid=:arg", array(':arg' => $cust_no))->fetchField();
            if($row[0] != '') {
              if($row[5] != '') {
                $dob = preg_replace('/-/', '/', $row[5]);
              } else {
                $dob = NULL;
              }
              $email = $row[1];
              $query = \Drupal::database()->select('users_field_data', 'ufd');
              $query->addField('ufd', 'mail');
              $query->condition('mail', $email);
              $query->range(0, 1);
              $found = $query->execute()->fetchField();
              
              if(!$found) {
                $name = $row[2]. ' '. $row[3];
                $mobile = '+'.$row[13].'-'.$row[12];
                $username = strtolower($row[2]).strtolower($row[3]);
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
                
                // users
                $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
                $user = \Drupal\user\Entity\User::create();
                //Mandatory settings
                $user->setPassword('password');
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
                  $request_time = \Drupal::time()->getRequestTime();
                  $query = \Drupal::database()->update('users_field_data');
                  $query->fields([
                    'status'  => 1,
                    'created' => $request_time,
                    'changed' => $request_time,
                    'access'  => $request_time,
                    'login'   => $request_time
                  ]);
                  $query->condition('uid', $uid);
                  $query->execute();
                }
                
                
                if($row[0] == 'alumni') {
                  $query = \Drupal::database()->insert('users_extra');
                  $query->fields(['bundle', 'deleted', 'entity_id', 'user_type', 'name', 'gender', 'date_of_birth', 'address_1', 'address_2', 'address_3', 'country_code', 'country', 'state', 'city', 'mobile_number','course_type', 'course_name', 'joining_year', 'graduating_year', 'batch_number', 'roll_number', 'area_group', 'subject_group', 'years_as_faculty']);
                  $query->values(['user','0',$uid,'alumni',$name,$row[4],$dob,$row[6],$row[7],$row[8],$row[13],$row[9],$row[10],$row[11],$mobile,$row[14],$row[15],$row[16],$row[17],$row[18],$row[19],NULL,NULL,NULL]);
                  $query->execute();
                }
                if($row[0] == 'faculty') {
                  $query = \Drupal::database()->insert('users_extra');
                  $query->fields(['bundle', 'deleted', 'entity_id', 'user_type', 'name', 'gender', 'date_of_birth', 'address_1', 'address_2', 'address_3', 'country_code', 'country', 'state', 'city', 'mobile_number','course_type', 'course_name', 'joining_year', 'graduating_year', 'batch_number', 'roll_number', 'area_group', 'subject_group', 'years_as_faculty']);
                  $query->values(['user','0',$uid,'faculty',$name,$row[4],$dob,'','','',$row[13],'','','',$mobile,'','','','','','',$row[20],$row[21],$row[22]]);
                  $query->execute();
                }
                if($row[0] == 'student') {
                  $query = \Drupal::database()->insert('users_extra');
                  $query->fields(['bundle', 'deleted', 'entity_id', 'user_type', 'name', 'gender', 'date_of_birth', 'address_1', 'address_2', 'address_3', 'country_code', 'country', 'state', 'city', 'mobile_number','course_type', 'course_name', 'joining_year', 'graduating_year', 'batch_number', 'roll_number', 'area_group', 'subject_group', 'years_as_faculty']);
                  $query->values(['user','0',$uid,'student',$name,$row[4],$dob,'','','',$row[13],'','','',$mobile,$row[14],$row[15],$row[16],'',$row[18],$row[19],NULL,NULL,NULL]);
                  $query->execute();
                }
              }
              //print_r($row); 
            }
            //exit;
          }
        
        //echo '</pre>';
        exit;
      } else {
        echo '2';
      }
      //echo '<pre>'; print_r($form_state); echo '</pre>'; exit;
    }
  }
?>