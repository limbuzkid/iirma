<?php

  namespace Drupal\custom_admin\Controller;
  
  use Drupal\Component\Utility\Html;
  use Drupal\image\Entity\ImageStyle;
  use Drupal\Core\Controller\ControllerBase;
  use Drupal\Core\Extension\ThemeHandlerInterface;
  use Drupal\Core\Form\FormBuilderInterface;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\DependencyInjection\ContainerInterface;
  use Drupal\Core\Password\PhpassHashedPassword;
  use Drupal\Core\Url;
  use Drupal\taxonomy\Entity\Term;
  use Symfony\Component\HttpFoundation\JsonResponse;
  use \Drupal\custom_mail\Controller\MailController;
  use Drupal\user\Entity\User;
  use Drupal\Core\Form\FormBase;
  use Drupal\Core\Form\FormStateInterface;

/**
 * Returns responses for comment module administrative routes.
 */
class AdminController extends ControllerBase {

  /**
   * The form builder.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * {@inheritdoc}
   */
  /*public static function create(ContainerInterface $container) {
    return new static(
      $container->get('form_builder')
    );
  }*/

  /**
   * Constructs an AdminController object.
   *
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   The form builder.
   */
  /*public function __construct(FormBuilderInterface $form_builder) {
    //$this->formBuilder = $form_builder;
  }*/

  /**
   * Presents an administrative comment listing.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request of the page.
   * @param string $type
   *   The type of the overview form ('approval' or 'new') default to 'new'.
   *
   * @return array
   *   Then comment multiple delete confirmation form or the comments overview
   *   administration form.
   */
  public function adminPage() {
    //$newHashedPassword = new PhpassHashedPassword();
    //echo "<pre>"; print_r($newHashedPassword->hash('rajas@007')); exit;
    $htmlContent = "";
    $build = array(
        '#type' => 'markup',
        '#markup' => $htmlContent,
      );
      return $build;
  }
  
  public function download() {
    $htmlContent = "";
    $build = array(
        '#type' => 'markup',
        '#markup' => $htmlContent,
      );
      return $build;
  }
  
  /*public function getFormId() {
    return 'import_form';
  }*/
  
  /*public function buildForm(array $form, FormStateInterface $form_state) {
    $form['candidate_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Candidate Name:'),
      '#required' => TRUE,
    );
    $form['candidate_mail'] = array(
      '#type' => 'email',
      '#title' => t('Email ID:'),
      '#required' => TRUE,
    );
    $form['candidate_number'] = array (
      '#type' => 'tel',
      '#title' => t('Mobile no'),
    );
    $form['candidate_dob'] = array (
      '#type' => 'date',
      '#title' => t('DOB'),
      '#required' => TRUE,
    );
    $form['candidate_gender'] = array (
      '#type' => 'select',
      '#title' => ('Gender'),
      '#options' => array(
        'Female' => t('Female'),
        'male' => t('Male'),
      ),
    );
    $form['candidate_confirmation'] = array (
      '#type' => 'radios',
      '#title' => ('Are you above 18 years old?'),
      '#options' => array(
        'Yes' =>t('Yes'),
        'No' =>t('No')
      ),
    );
    $form['candidate_copy'] = array(
      '#type' => 'checkbox',
      '#title' => t('Send me a copy of the application.'),
    );
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    );
    return $form;
  }
  */
  public function import() {

  }
  
  public function irma_csv_download() {
    $csv_output = "";
    $arg = $_GET['q'];
    if($arg == 'users') {
      $csv_output .= "Type,Name,Gender,Date of Birth,Email,Address1,Address2,Address3,Country,State,City,Mobile,Course type,Course name,Joining year,Graduating year,Batch number,Roll number,Area group,Subject group,Years as faculty,IRMA Nickname,Hobbies,Years of Experience,Sector last worked in,Family Details,Educational Background,Professional Background,Achievements,Media Coverage\n";
      $sql = 'SELECT u.mail, ue.name,ue.user_type,ue.gender,ue.date_of_birth,ue.address_1,ue.address_2,ue.address_3,ue.country,ue.state,ue.city,ue.mobile_number,
      ue.course_type,ue.course_name,ue.joining_year,ue.graduating_year,ue.batch_number,ue.roll_number,ue.area_group,ue.subject_group,ue.years_as_faculty,up.nickname,up.hobbies,up.permanent_address,up.year_of_experience,up.sector_last_worked,up.family_details,up.educational_background,up.professional_background,up.achievements,up.media_coverage_forums
              FROM users_field_data u
              LEFT JOIN users_extra ue ON ue.entity_id = u.uid
              LEFT JOIN users_profile up ON up.entity_id = u.uid';
              
      $db = \Drupal::database();
      $rows = $db->query($sql);
      $rows->allowRowCount = TRUE;
      foreach($rows as $row) {
        if($row->user_type) {
          if(isset($row->family_details)) {
            $fam_details = json_decode($row->family_details);
            $family_details_data = '';
            $first = true;
            foreach($fam_details as $fam) {
              if($first) {
                $family_details_data .= 'Name:'.$fam->name.' Relation:'.$fam->relation.' Age:'.$fam->age.' Mobile:'.$fam->mobile;
                $first = false;
              } else {
                $family_details_data .= '   Name:'.$fam->name.' Relation:'.$fam->relation.' Age:'.$fam->age.' Mobile:'.$fam->mobile;
              }
            }
          }
          if(isset($row->educational_background)) {
            $education = json_decode($row->educational_background);
            $educational_background_data = '';
            $first = true;
            foreach($education as $edu) {
              if($first) {
                $educational_background_data .= 'Qualification:'.$edu->qualification.' Institution:'.$edu->institution.' Year of passing:'.$edu->yearPassing;
                $first = false;
              } else {
                $educational_background_data .= '   Qualification:'.$edu->qualification.' Institution:'.$edu->institution.' Year of passing:'.$edu->yearPassing;
              }
            }
          }
          if(isset($row->professional_background)) {
            $profession = json_decode($row->professional_background);
            $professional_background_data = '';
            $first = true;
            foreach($profession as $pro) {
              if($first) {
                $professional_background_data .= 'Designation:'.$pro->designation.' Organisation:'.$pro->organisation.' Industry:'.$pro->industry.' Country:'.$pro->country.' State:'.$pro->state.' City:'.$pro->city.' From:'.$pro->from.' To:'.$pro->to.' Scope:'.$pro->scope;
                $first = false;
              } else {
                $professional_background_data .= '    Designation:'.$pro->designation.' Organisation:'.$pro->organisation.' Industry:'.$pro->industry.' Country:'.$pro->country.' State:'.$pro->state.' City:'.$pro->city.' From:'.$pro->from.' To:'.$pro->to.' Scope:'.$pro->scope;
              }
            }
          }
          if(isset($row->achievements)) {
            $achievements = json_decode($row->achievements);
            $achievements_data = '';
            $first = true;
            foreach($achievements as $ach) {
              if($first) {
                $achievements_data .= 'Name:'.$ach->name.' URL:'.$ach->url.' Description:'.$ach->desc;
                $first = false;
              } else {
                $achievements_data .= '   Name:'.$ach->name.' URL:'.$ach->url.' Description:'.$ach->desc;
              }
            }
          }
          if(isset($row->media_coverage_forums)) {
            $media = json_decode($row->media_coverage_forums);
            $media_data = '';
            $first = true;
            foreach($media as $med) {
              if($first) {
                $media_data .= 'Name:'.$med->name.' URL:'.$med->url.' Description:'.$med->desc;
                $first = false;
              } else {
                $media_data .= '   Name:'.$med->name.' URL:'.$med->url.' Description:'.$med->desc;
              }
            }
          }
          $csv_output .=  $row->user_type .",\"".$row->name ."\",\"".$row->gender ."\",\"".$row->date_of_birth ."\",\"".$row->mail."\",\"".$row->address_1 ."\",\"".$row->address_2 ."\",\"".$row->address_3 ."\",\"".$row->country."\",\"".$row->state."\",\"".$row->city."\",\"".$row->mobile_number."\",\"".$row->course_type ."\",\"".$row->course_name ."\",\"".$row->joining_year ."\",\"".$row->graduating_year ."\",\"".$row->batch_number."\",\"".$row->roll_number ."\",\"".$row->area_group."\",\"".$row->subject_group."\",\"".$row->years_as_faculty."\",\"".$row->nickname."\",\"".$row->hobbies."\",\"".$row->year_of_experience."\",\"".$row->sector_last_worked."\",\"".$family_details_data."\",\"".$educational_background_data."\",\"".$professional_background_data."\",\"".$achievements_data."\",\"".$media_data."\"\n";
        }
      }
    }
    if($arg == 'contact') {
      $csv_output .= "First Name,Last Name,Mobile No,Email ID,Feedback Option,Message,Date\n";
      $sql = 'SELECT * FROM contact_form_data';
      $db = \Drupal::database();
      $rows = $db->query($sql);
      $rows->allowRowCount = TRUE;
      foreach($rows as $row) {
        $csv_output .=  $row->firstname .",\"".$row->lastname ."\",\"".$row->mobileno ."\",\"".$row->emailid ."\",\"".$row->feedbackoption."\",\"".$row->message ."\",\"".$row->date_created ."\"\n";
      }
    }
    if($arg == 'etdc') {
      $csv_output .= "Name,Batch Number,Email ID,Organisation,Designation,Country, City,State,Mobile Number,Arrival Date,Time,Departure Date,Time,Purpose Of Visit,State Purpose,No of Persons,No of rooms required,Food To Be Included,Food Preference\n";
      $sql = 'SELECT * FROM campus_infra_etdc';
      $db = \Drupal::database();
      $rows = $db->query($sql);
      $rows->allowRowCount = TRUE;
      foreach($rows as $row) {
        $csv_output .=  $row->name .",\"".$row->batch_number ."\",\"".$row->email ."\",\"".$row->organisation ."\",\"".$row->designation."\",\"".$row->country."\",\"".$row->city ."\",\"".$row->state ."\",\"".$row->mobile_number ."\",\"".$row->arrival_date."\",\"".$row->arrival_time ."\",\"".$row->departure_date ."\",\"".$row->departure_time ."\",\"".$row->purpose_of_visit ."\",\"".$row->purpose ."\",\"".$row->no_persons."\",\"".$row->no_rooms ."\",\"".$row->include_food ."\",\"".$row->food_preference ."\"\n";
      }
    }
    if($arg == 'wifi') {
      $csv_output .= "Name,Batch Number,Email ID,Organisation,Designation,Country, City,State,Mobile Number,Arrival Date,Time,Departure Date,Time,Purpose Of Visit,State Purpose,Wifi Access\n";
      $sql = 'SELECT * FROM campus_infra_wifi';
      $db = \Drupal::database();
      $rows = $db->query($sql);
      $rows->allowRowCount = TRUE;
      foreach($rows as $row) {
        $csv_output .=  $row->name .",\"".$row->batch_number ."\",\"".$row->email ."\",\"".$row->organisation ."\",\"".$row->designation."\",\"".$row->country."\",\"".$row->city ."\",\"".$row->state ."\",\"".$row->mobile_number ."\",\"".$row->arrival_date."\",\"".$row->arrival_time ."\",\"".$row->departure_date ."\",\"".$row->departure_time ."\",\"".$row->purpose_of_visit ."\",\"".$row->purpose ."\",\"".$row->wifi_access ."\"\n";
      }
    }
    if($arg == 'sac') {
      $csv_output .= "Name,Batch Number,Email ID,Organisation,Designation,Country, City,State,Mobile Number,Arrival Date,Time,Departure Date,Time,Purpose Of Visit,State Purpose,Sac Access\n";
      $sql = 'SELECT * FROM campus_infra_sac';
      $db = \Drupal::database();
      $rows = $db->query($sql);
      $rows->allowRowCount = TRUE;
      foreach($rows as $row) {
        $csv_output .=  $row->name .",\"".$row->batch_number ."\",\"".$row->email ."\",\"".$row->organisation ."\",\"".$row->designation."\",\"".$row->country."\",\"".$row->city ."\",\"".$row->state ."\",\"".$row->mobile_number ."\",\"".$row->arrival_date."\",\"".$row->arrival_time ."\",\"".$row->departure_date ."\",\"".$row->departure_time ."\",\"".$row->purpose_of_visit ."\",\"".$row->purpose ."\",\"".$row->sac_access ."\"\n";
      }
    }
    if($arg == 'mess') {
      $csv_output .= "Name,Batch Number,Email ID,Organisation,Designation,Country, City,State,Mobile Number,Arrival Date,Time,Departure Date,Time,Purpose Of Visit,State Purpose,No of Persons,Arrange Food\n";
      $sql = 'SELECT * FROM campus_infra_mess';
      $db = \Drupal::database();
      $rows = $db->query($sql);
      $rows->allowRowCount = TRUE;
      foreach($rows as $row) {
        $csv_output .=  $row->name .",\"".$row->batch_number ."\",\"".$row->email ."\",\"".$row->organisation ."\",\"".$row->designation."\",\"".$row->country."\",\"".$row->city ."\",\"".$row->state ."\",\"".$row->mobile_number ."\",\"".$row->arrival_date."\",\"".$row->arrival_time ."\",\"".$row->departure_date ."\",\"".$row->departure_time ."\",\"".$row->purpose_of_visit ."\",\"".$row->purpose ."\",\"".$row->num_person."\",\"".$row->arrange_food ."\"\n";
      }
    }
    if($arg == 'lib') {
      $csv_output .= "Name,Batch Number,Email ID,Organisation,Designation,Country, City,State,Mobile Number,Arrival Date,Time,Departure Date,Time,Purpose Of Visit,State Purpose,Library access\n";
      $sql = 'SELECT * FROM campus_infra_library';
      $db = \Drupal::database();
      $rows = $db->query($sql);
      $rows->allowRowCount = TRUE;
      foreach($rows as $row) {
        $csv_output .=  $row->name .",\"".$row->batch_number ."\",\"".$row->email ."\",\"".$row->organisation ."\",\"".$row->designation."\",\"".$row->country."\",\"".$row->city ."\",\"".$row->state ."\",\"".$row->mobile_number ."\",\"".$row->arrival_date."\",\"".$row->arrival_time ."\",\"".$row->departure_date ."\",\"".$row->departure_time ."\",\"".$row->purpose_of_visit ."\",\"".$row->purpose ."\",\"".$row->access_library ."\"\n";
      }
    }
    if($arg == 'classroom') {
      $csv_output .= "Name,Batch Number,Email ID,Organisation,Designation,Country, City,State,Mobile Number,Brief About Proposed Classroom Session,Concerned IRMA Subject Group,Number Of Hours Required For One Session\n";
      $sql = 'SELECT * FROM classroom_sessions';
      $db = \Drupal::database();
      $rows = $db->query($sql);
      $rows->allowRowCount = TRUE;
      foreach($rows as $row) {
        $csv_output .=  $row->name .",\"".$row->batch_number ."\",\"".$row->email ."\",\"".$row->organisation ."\",\"".$row->designation."\",\"".$row->country."\",\"".$row->city ."\",\"".$row->state ."\",\"".$row->mobile_number ."\",\"".$row->session_brief."\",\"".$row->concerned_subject_grp ."\",\"".$row->hours_required."\"\n";
      }
    }
    if($arg == 'faculty') {
      $csv_output .= "Name,Batch Number,Email ID,Organisation,Designation,Country, City,State,Mobile Number,Name Of Decision Maker,Email ID Of Decision Maker,Contact Number Of Decision Maker,Brief About Proposed Workshop,IRMA Subject Group,Concerned IRMA Faculty To Be Contacted,Expected Start Date Of Workshop,Expected End Date Of Workshop\n";
      $sql = 'SELECT * FROM invite_faculty';
      $db = \Drupal::database();
      $rows = $db->query($sql);
      $rows->allowRowCount = TRUE;
      foreach($rows as $row) {
        $csv_output .=  $row->name .",\"".$row->batch_number ."\",\"".$row->email ."\",\"".$row->organisation ."\",\"".$row->designation."\",\"".$row->country."\",\"".$row->city ."\",\"".$row->state ."\",\"".$row->mobile_number ."\",\"".$row->decision_maker_name."\",\"".$row->decision_maker_email."\",\"".$row->decision_maker_mobile."\",\"".$row->workshop_brief."\",\"".$row->concerned_subject_grp."\",\"".$row->concerned_faculty_contact."\",\"".$row->workshop_start_date."\",\"".$row->workshop_end_date."\"\n";
      }
    }
    if($arg == 'project') {
      $csv_output .= "Name,Batch Number,Email ID,Organisation,Designation,Country, City,State,Mobile Number,Name Of Decision Maker,Email ID Of Decision Maker,Contact Number Of Decision Maker,Brief About Proposed Project,IRMA Subject Group,Concerned IRMA Faculty To Be Contacted,Expected Start Date Of Project,Expected End Date Of Project\n";
      $sql = 'SELECT * FROM project_apply';
      $db = \Drupal::database();
      $rows = $db->query($sql);
      $rows->allowRowCount = TRUE;
      foreach($rows as $row) {
        $csv_output .=  $row->name .",\"".$row->batch_number ."\",\"".$row->email ."\",\"".$row->organisation ."\",\"".$row->designation."\",\"".$row->country."\",\"".$row->city ."\",\"".$row->state ."\",\"".$row->mobile_number ."\",\"".$row->decision_maker_name."\",\"".$row->decision_maker_email."\",\"".$row->decision_maker_mobile."\",\"".$row->project_brief."\",\"".$row->concerned_subject_grp."\",\"".$row->concerned_faculty_contact."\",\"".$row->project_start_date."\",\"".$row->project_end_date."\"\n";
      }
    }
    if($arg == 'casestudy') {
      $csv_output .= "Name,Batch Number,Email ID,Organisation,Designation,Country, City,State,Mobile Number,Name Of Decision Maker,Email ID Of Decision Maker,Contact Number Of Decision Maker,Brief About Proposed Case Study,IRMA Subject Group,Concerned IRMA Faculty To Be Contacted,Start Date,End Date\n";
      $sql = 'SELECT * FROM case_study';
      $db = \Drupal::database();
      $rows = $db->query($sql);
      $rows->allowRowCount = TRUE;
      foreach($rows as $row) {
        $csv_output .=  $row->name .",\"".$row->batch_number ."\",\"".$row->email ."\",\"".$row->organisation ."\",\"".$row->designation."\",\"".$row->country."\",\"".$row->city ."\",\"".$row->state ."\",\"".$row->mobile_number ."\",\"".$row->decision_maker_name."\",\"".$row->decision_maker_email."\",\"".$row->decision_maker_mobile."\",\"".$row->cs_brief."\",\"".$row->concerned_subject_grp."\",\"".$row->concerned_faculty_contact."\",\"".$row->cs_start_date."\",\"".$row->cs_end_date."\"\n";
      }
    }
    if($arg == 'refer') {
      $csv_output .= "Name,Batch Number,Email ID,Organisation,Designation,Country, City,State,Mobile Number,Name Of HR,Name Of Decision Maker,Email ID Of Decision Maker,Contact Number Of Decision Maker,Recruitment Month,Other Details\n";
      $sql = 'SELECT * FROM refer_recruiter';
      $db = \Drupal::database();
      $rows = $db->query($sql);
      $rows->allowRowCount = TRUE;
      foreach($rows as $row) {
        $csv_output .=  $row->name .",\"".$row->batch_number ."\",\"".$row->email ."\",\"".$row->organisation ."\",\"".$row->designation."\",\"".$row->country."\",\"".$row->city ."\",\"".$row->state ."\",\"".$row->mobile_number ."\",\"".$row->hr_name."\",\"".$row->decision_maker_name."\",\"".$row->decision_maker_email."\",\"".$row->decision_maker_mobile."\",\"".$row->recruitment_month."\",\"".$row->other_details."\"\n";
      }
    }
    if($arg == 'mdp') {
      $csv_output .= "Name,Batch Number,Email ID,Organisation,Designation,Country, City,State,Mobile Number,MDP Name,MDP Start Date,MDP End Date,MDP Query\n";
      $sql = 'SELECT * FROM mdp_data';
      $db = \Drupal::database();
      $rows = $db->query($sql);
      $rows->allowRowCount = TRUE;
      foreach($rows as $row) {
        $csv_output .=  $row->name .",\"".$row->batch_number ."\",\"".$row->email ."\",\"".$row->organisation ."\",\"".$row->designation."\",\"".$row->country."\",\"".$row->city ."\",\"".$row->state ."\",\"".$row->mobile_number ."\",\"".$row->mdp_name."\",\"".$row->start_date."\",\"".$row->end_date."\",\"".$row->mdp_query."\"\n";
      }
    }
    
    if($arg == 'giveto') {
      $csv_output .= "Name,Batch Number,Email ID,Organisation,Designation,Country, City,State,Contact Number,Postal Address 1,Postal Address 2,Postal Address 3,City,State,Country,Postal Code,Give to,Payment Purpose,PAN,Donation Amount,Anonymous Gift\n";
      $sql = 'SELECT * FROM giveto_iaa';
      $db = \Drupal::database();
      $rows = $db->query($sql);
      $rows->allowRowCount = TRUE;
      foreach($rows as $row) {
        $csv_output .=  $row->name .",\"".$row->batch_number ."\",\"".$row->email ."\",\"".$row->organisation ."\",\"".$row->designation."\",\"".$row->country."\",\"".$row->city ."\",\"".$row->state ."\",\"".$row->contact_no ."\",\"".$row->postal_addr1."\",\"".$row->postal_addr2."\",\"".$row->postal_addr3."\",\"".$row->postal_city."\",\"".$row->postal_state."\",\"".$row->postal_country."\",\"".$row->postal_code."\",\"".$row->give_to."\",\"".$row->payment_purpose."\",\"".$row->pan_no."\",\"".$row->donation_amt."\",\"".$row->anonymous_gift."\"\n";
      }
    }
    if($arg == 'evntReg') {
      $csv_output .= "Company,Position,Applied with IRMA Profile,Date Number,Email ID,Organisation,Designation,Country, City,State,Contact Number,Arrival Date & Time,Pickup Required,Pickup Location,Departure Date & Time,Drop Required,Drop Location,No. Of Guests Accompanying,Food Preference,Stay Preference,Hostel Preference\n";
      $sql = 'SELECT * FROM events_registration';
      $db = \Drupal::database();
      $rows = $db->query($sql);
      $rows->allowRowCount = TRUE;
      foreach($rows as $row) {
        $csv_output .=  $row->name .",\"".$row->batch_number ."\",\"".$row->email ."\",\"".$row->organisation ."\",\"".$row->designation."\",\"".$row->country."\",\"".$row->city ."\",\"".$row->state ."\",\"".$row->contact_no ."\",\"".$row->arrival_date."\",\"".$row->pickup_reqd."\",\"".$row->pickup_loc."\",\"".$row->depart_date."\",\"".$row->drop_reqd."\",\"".$row->drop_loc."\",\"".$row->num_guests."\",\"".$row->food_pref."\",\"".$row->accom_pref."\",\"".$row->accom_pref."\"\n";
      }
    }
    
    if($arg == 'jobApp') {
      $csv_output .= "Company,Position,Applied with IRMA Profile,Date\n";
      $htmlContent = '';
      $query = \Drupal::database()->select('apply_irma_profile', 'aip');
      $query->fields('aip', ['job_id', 'user_id', 'fid', 'irma_profile', 'date_created', 'status']);
      $query->orderBy('aip.id', 'ASC');
      $jobs = $query->execute();
    
      foreach($jobs as $job) {
        $user_id      = $job->user_id;
        $fid          = $job->fid;
        $created      = $job->date_created;
        $node         = \Drupal\node\Entity\Node::load($job->job_id);
        $title = $node->title->value;
        $tid = $node->get('field_company')->getString();
        $query = \Drupal::database()->select('taxonomy_term_field_data', 'tfd');
        $query->addField('tfd', 'name');
        $query->condition('tfd.tid', $tid);
        $query->range(0, 1);
        $company = $query->execute()->fetchField();
        if($job->irma_profile == 0) {
          $app = 'No';
        } else {
          $app = 'Yes';
        }
        $csv_output .=  $company .",\"".$title ."\",\"".$app ."\",\"".$created."\"\n";
      }    
    }
    
    if($arg == 'appr') {
      $query = \Drupal::entityQuery('node')
        ->condition('status', 0)
        ->condition('type', 'jobs')
        ->sort('nid', 'DESC');
      $nids = $query->execute();
      $nodes = entity_load_multiple('node', $nids);
      $csv_output .= "Job Title,Job Description,Job Function,Company Name,Location\n";
      foreach($nodes as $node) {
        if(!isset($node->field_approval_status->value) || $node->field_approval_status->value!=1){
          if(strlen($node->title->value) > 45) {
            $title = preg_replace('/\s+?(\S+)?$/', '', substr($node->title->value, 0, 40)). '...';
          } else {
            $title = $node->title->value;
          }
          $shortdesc = preg_replace('/\s+?(\S+)?$/', '', substr($node->body->value, 0, 100));
          $companyname = "";
          $term = Term::load($node->get('field_company')->target_id);
          if(!empty($term)){
            $companyname = $term->getName(); 
          } 
          $location = "";
          $term = Term::load($node->get('field_city')->target_id);
          if(empty($term)){
            $term = Term::load($node->get('field_location')->target_id);
            if(!empty($term)){
              $location = $term->getName(); 
            } 
          } else {
            $location = $term->getName();
          }
          $jobfunctions = "";
          $term = Term::load($node->get('field_job_functions')->target_id);
          $jobfunctions = $term->getName();
          $csv_output .= $title.",\"".$shortdesc."\",\"".$jobfunctions."\",\"".$companyname."\",\"".$location."\"\n";
        }
      }
    }    
    $filename = $arg."_".date("Y-m-d_H-i",time());
    header("Content-type: application/vnd.ms-excel");
    header("Content-disposition: csv" . date("Y-m-d") . ".csv");
    header("Content-disposition: filename=".$filename.".csv");
    print $csv_output;
    exit;
  }
  
  public function irma_users() {
    if(!isset($_GET['uid'])) {
      $htmlContent = "<table><tr><th>Sl.No</th><th>User Type</th><th>Name</th><th>Status</th><th>Action</th></tr>";
      $index = 1;
      $query = \Drupal::database()->select('users_field_data', 'ufd');
      $query->fields('ufd', ['uid', 'access']);
      $query->leftJoin('users_extra', 'ue', 'ue.entity_id = ufd.uid');
      $query->fields('ue', ['name', 'user_type']);
      $query->orderBy('ufd.uid', 'ASC');
      $pager = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')
            ->limit(10);
      $users = $pager->execute();
      //$users = $query->execute()->fetchAllAssoc('uid');
      foreach($users as $user) {
        if($user->uid > 0) {
          if($user->access == 0) {
            $status = 'Inactive';
          } else {
            $status = 'Active';
          }
          $htmlContent .= '<tr>
                            <td>'.$index.'</td>
                            <td>'.ucwords($user->user_type).'</td>
                            <td>'.$user->name.'</td>
                            <td>'.$status.'</td>
                            <td><a href="/admin/iirmalisting/irma-users?uid='.$user->uid.'">View Profile</a></td>
                          </tr>';
          $index++;
        }
      }
      $htmlContent .= '</table><br><a href="/admin/iirmalisting/irma-users/irma-csv-download?q=users">Download</a>';
      $output = array(
          '#markup' => $htmlContent,
      );
      $build['result'] = $output;
      $build['pager'] = [
        '#type' => 'pager',
      ];
      return $build;
    } else {
      $htmlContent = '<table id="userProf">';
      $uid = $_GET['uid'];
      $query = \Drupal::database()->select('users_field_data', 'ufd');
      $query->fields('ufd', ['uid', 'mail', 'access']);
      $query->leftJoin('users_extra', 'ue', 'ue.entity_id = ufd.uid');
      $query->fields('ue', ['name', 'user_type', 'date_of_birth', 'gender','roll_number', 'area_group', 'subject_group', 'years_as_faculty','address_1', 'address_2', 'address_3', 'country', 'state', 'city','mobile_number', 'batch_number', 'course_type', 'course_name', 'joining_year', 'graduating_year']);
      $query->leftJoin('users_profile', 'up', 'up.entity_id = ufd.uid');
      $query->fields('up', ['nickname', 'hobbies', 'family_details', 'fun_photo_id','cv_target_id', 'media_coverage_forums', 'achievements','permanent_address', 'educational_background','professional_background','linkedin_url', 'year_of_experience', 'sector_last_worked']);
      $query->leftJoin('user__user_picture', 'upp', 'upp.entity_id = ufd.uid');
      $query->fields('upp', ['user_picture_target_id']);
      $query->leftJoin('file_managed', 'fm', 'fm.fid = upp.user_picture_target_id');
      $query->fields('fm', ['uri']);
      $query->condition('ufd.uid', $uid);
      $query->orderBy('ufd.uid', 'ASC');
      $users = $query->execute()->fetchAllAssoc('uid');
      $htmlContent = '<table>';
      foreach($users as $user) {
        if($user->uri != NULL) {
          //$profile_image = file_create_url($user->uri);
          $style = ImageStyle::load('featured');  // Load the image style configuration entity.
          $profile_image = $style->buildUrl($user->uri);
        } else {
          $profile_image = $base_url.'/sites/default/files/default.jpg'; 
        }
        if($user->fun_photo_id != NULL) {
          $query = \Drupal::database()->select('file_managed', 'fm');
          $query->addField('fm', 'uri');
          $query->condition('fm.fid', $user->fun_photo_id);
          $query->range(0, 1);
          $uri = $query->execute()->fetchField();
          //$fun_image = file_create_url($uri);
          $style = ImageStyle::load('featured');  // Load the image style configuration entity.
          $fun_image = $style->buildUrl($uri);
        } else {
          $fun_image = $base_url.'/sites/default/files/default.jpg';
        }
      
        if($user->permanent_address != NULL) {
          $addr = '';
          $perm_address = json_decode($user->permanent_address);
          $addr .= '<p>'.$perm_address->addr1.'</p><p>'.$perm_address->addr2.'</p><p>'.$perm_address->addr3.'</p><p>';
          if($perm_address->city != 'Select') {
            $addr .= $perm_address->city;
          }
          if($perm_address->state != 'Select') {
            $addr .= ', '.$perm_address->state;
          }
          if($perm_address->country != 'Select') {
            $addr .= ', '.$perm_address->country;
          }
          $addr .= '</p>';
        }
        
        if($user->family_details != NULL) {
          $families = json_decode($user->family_details);
          $fam_html = '';
          foreach($families as $fam) {
            $fam_html .= '<p><span>Name</span>:<span>'.$fam->name.'</span></p>';
            $fam_html .= '<p><span>Relation</span>:<span>'.$fam->relation.'</span></p>';
            $fam_html .= '<p><span>Age</span>:<span>'.$fam->age.'</span></p>';
            $fam_html .= '<p><span>Mobile Number</span>:<span>'.$fam->mobile.'</span></p><hr>';
          }
        }
        
        if($user->educational_background != NULL) {
          $educations = json_decode($user->educational_background);
          $edu_html = '';
          foreach($educations as $edu) {
            $edu_html .= '<p><span>Qualification</span>:<span>'.$edu->qualification.'</span></p>';
            $edu_html .= '<p><span>Institution</span>:<span>'.$edu->institution.'</span></p>';
            $edu_html .= '<p><span>Year of Passing</span>:<span>'.$edu->yearPassing.'</span></p><hr>';
          }
        }
        
        if($user->professional_background != NULL) {
          $professions = json_decode($user->professional_background);
          $pro_html = '';
          foreach($professions as $pro) {
            $pro_html .= '<p><span>Designation</span>:<span>'.$pro->designation.'</span></p>';
            if($pro->workHereChk == 'true') {
              $pro_html .= '<p><span>Organisation</span>:<span>'.$pro->organisation.' (Currently works here)</span></p>';
            } else {
              $pro_html .= '<p><span>Organisation</span>:<span>'.$pro->organisation.'</span></p>';
            }
            $pro_html .= '<p><span>Industry</span>:<span>'.$pro->industry.'</span></p>
                          <p>From '.$pro->from.' to '.$pro->to.'</p>
                          <p>'.$pro->city.' ,'.$pro->state.' ,'.$pro->country.'</p>
                          <p><span>Scope of work</span>:<span>'.$pro->scope.'</span></p><hr>';
          }
        }
        if($user->achievements != NULL) {
          $achievements = json_decode($user->achievements);
          $ach_html = '';
          foreach($achievements as $ach) {
            $ach_html .= '<p><span>Name</span>:<span>'.$ach->name.'</span></p>';
            $ach_html .= '<p><span>URL</span>:<span>'.$ach->url.'</span></p>';
            $ach_html .= '<p><span>Description</span>:<span>'.$ach->desc.'</span></p><hr>';
          }
        }
        if($user->media_coverage_forums != NULL) {
          $media = json_decode($user->media_coverage_forums);
          $med_html = '';
          foreach($media as $med) {
            $med_html .= '<p><span>Name</span>:<span>'.$med->name.'</span></p>';
            $med_html .= '<p><span>URL</span>:<span>'.$med->url.'</span></p>';
            $med_html .= '<p><span>Description</span>:<span>'.$med->desc.'</span></p><hr>';
          }
        }
        
        if($user->cv_target_id != NULL) {
          $query = \Drupal::database()->select('file_managed', 'fm');
          $query->addField('fm', 'uri');
          $query->condition('fm.fid', $user->cv_target_id);
          $query->range(0, 1);
          $uri = $query->execute()->fetchField();
          $cv_path = '<a href="'.file_create_url($uri).'" target="_blank">Download Resume</a>';
        }
        
        $htmlContent .= '<tr><td width="25"><img src="'.$profile_image.'" style="width:30%;"></td><td width="2">&nbsp;</td><td><img src="'.$fun_image.'" style="width:30%;"></td></tr>
                          <tr><td width="25">User Type</td><td width="2">:</td><td>'.ucwords($user->user_type).'</td></tr>
                          <tr><td width="25">Name</td><td width="2">:</td><td>'.$user->name.'</td></tr>
                          <tr><td width="25">Date of Birth</td><td width="2">:</td><td>'.$user->date_of_birth.'</td></tr>
                          <tr><td width="25">Gender</td><td width="2">:</td><td>'.$user->gender.'</td></tr>
                          <tr><td width="25">Email</td><td width="2">:</td><td>'.$user->mail.'</td></tr>
                          <tr><td width="25">Mobile Number</td><td width="2">:</td><td>'.$user->mobile_number.'</td></tr>
                          <tr><td width="25">IRMA Nickname</td><td width="2">:</td><td>'.$user->nickname.'</td></tr>
                          <tr><td width="25">Hobbies & Interests</td width="2"><td>:</td><td>'.$user->hobbies.'</td></tr>
                          <tr><td width="25">Linkedin URL</td><td width="2">:</td><td>'.$user->linkedin_url.'</td></tr>';
        if($user->user_type == 'alumni') {              
          $htmlContent .= '<tr><td width="25">Address</td><td width="2">:</td><td><p>'.$user->address_1.'</p><p>'.$user->address_2.'</p><p>'.$user->address_3.'</p></td></tr>
                            <tr><td width="25">Country</td><td width="2">:</td><td>'.$user->country.'</td></tr>
                            <tr><td width="25">State</td><td width="2">:</td><td>'.$user->state.'</td></tr>
                            <tr><td width="25">City</td><td width="2">:</td><td>'.$user->city.'</td></tr>
                            <tr><td width="25">Course Type</td><td width="2">:</td><td>'.$user->course_type.'</td></tr>
                            <tr><td width="25">Course Name</td><td width="2">:</td><td>'.$user->course_name.'</td></tr>
                            <tr><td width="25">Batch Number</td><td width="2">:</td><td>'.$user->batch_number.'</td></tr>
                            <tr><td width="25">Roll Number</td><td width="2">:</td><td>'.$user->roll_number.'</td></tr>
                            <tr><td width="25">Joined</td><td width="2">:</td><td>'.$user->joining_year.'</td></tr>
                            <tr><td width="25">Graduated</td><td width="2">:</td><td>'.$user->graduating_year.'</td></tr>
                            <tr><td width="25">Family Details</td><td width="2">:</td><td>'.$fam_html.'</td></tr>
                            <tr><td width="25">Permanent Address</td><td width="2">:</td><td>'.$addr.'</td></tr>
                            <tr><td width="25">Educational Background</td><td width="2">:</td><td>'.$edu_html.'</td></tr>
                            <tr><td width="25">Professional Background</td><td width="2">:</td><td>'.$pro_html.'</td></tr>
                            <tr><td width="25">Achievements & Awards</td><td width="2">:</td><td>'.$ach_html.'</td></tr>
                            <tr><td width="25">Media Coverage/Forums</td><td width="2">:</td><td>'.$med_html.'</td></tr>
                            <tr><td width="25">CV Path</td><td width="2">:</td><td>'.$cv_path.'</td></tr>';
        }
        if($user->user_type == 'student') {              
          $htmlContent .= '<tr><td width="25">Course Type</td><td width="2">:</td><td>'.$user->course_type.'</td></tr>
                            <tr><td width="25">Course Name</td><td width="2">:</td><td>'.$user->course_name.'</td></tr>
                            <tr><td width="25">Batch Number</td><td width="2">:</td><td>'.$user->batch_number.'</td></tr>
                            <tr><td width="25">Roll Number</td><td width="2">:</td><td>'.$user->roll_number.'</td></tr>
                            <tr><td width="25">Joined</td><td width="2">:</td><td>'.$user->joining_year.'</td></tr>
                            <tr><td width="25">Years Of Work Experience</td><td width="2">:</td><td>'.$user->year_of_experience.'</td></tr>
                            <tr><td width="25">Sector Last Worked in</td><td width="2">:</td><td>'.$user->sector_last_worked.'</td></tr>';
        }
        
        
        if($user->user_type == 'faculty') {              
          $htmlContent .= '<tr><td width="25">Area Group</td><td width="2">:</td><td>'.$user->area_group.'</td></tr>
                            <tr><td width="25">Subject Group</td><td width="2">:</td><td>'.$user->subject_group.'</td></tr>
                            <tr><td width="25">Years Of Work Experience</td><td width="2">:</td><td>'.$user->year_of_experience.'</td></tr>
                            <tr><td width="25">No. Of Years As Faculty at IRMA</td><td width="2">:</td><td>'.$user->years_as_faculty.'</td></tr>';
        }
      }
      $htmlContent .= '</table>';
      $build = array(
          '#markup' => $htmlContent,
      );
      return $build;
    }
  }
  
  public function contactFormPage() {
    //$newHashedPassword = new PhpassHashedPassword();
    //echo "<pre>"; print_r($newHashedPassword->hash('rajas@007')); exit;
    $query = \Drupal::database()->select('contact_form_data', 'cfd');
    $query->fields('cfd', ['firstname', 'lastname', 'mobileno', 'emailid', 'feedbackoption', 'message','date_created']);
    $query->orderBy('date_created','DESC');
    $result = $query->execute();
    $htmlContent = "<table id='contactformdata'>
                     <tr>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Mobile No</th>
                      <th>Email ID</th>
                      <th>Feedback Option</th>
                      <th>Message</th>
                      <th>Date</th>
                     </tr>";
    while ($row = $result->fetchAssoc()) {
      //echo "<pre>"; print_r($row); exit;
      $htmlContent .= '<tr>
                      <td>'.$row['firstname'].'</td>
                      <td>'.$row['lastname'].'</td>
                      <td>'.$row['mobileno'].'</td>
                      <td>'.$row['emailid'].'</td>
                      <td>'.$row['feedbackoption'].'</td>
                      <td>'.$row['message'].'</td>
                      <td>'.$row['date_created'].'</td>
                     </tr>';
    }
    
    $arg = json_encode(array('header'=>$header, 'data'=>$data));
    $htmlContent .='</table><br><a href="/admin/iirmalisting/irma-users/irma-csv-download?q=contact">Download</a>';

    $build = array(
        '#type' => 'markup',
        '#markup' => $htmlContent,
      );
      return $build;
  }

  public function jobApproveDisapprovePage() {
    //$newHashedPassword = new PhpassHashedPassword();
    //echo "<pre>"; print_r($newHashedPassword->hash('rajas@007')); exit;
    $query = \Drupal::entityQuery('node')
        ->condition('status', 0)
        ->condition('type', 'jobs')
        ->sort('nid', 'DESC');
    $nids = $query->execute();
    $nodes = entity_load_multiple('node', $nids);

    $htmlContent = "<table id='contactformdata'>
                     <tr>
                      <th>Job Title</th>
                      <th>Job Description</th>
                      <th>Job Function</th>
                      <th>Company Name</th>
                      <th>Location</th>
                      <th>Action</th>
                     </tr>";
    foreach($nodes as $node) {
      if(!isset($node->field_approval_status->value) || $node->field_approval_status->value!=1){
          if(strlen($node->title->value) > 45) {
              $title = preg_replace('/\s+?(\S+)?$/', '', substr($node->title->value, 0, 40)). '...';
            } else {
              $title = $node->title->value;
            }
            $shortdesc = preg_replace('/\s+?(\S+)?$/', '', substr($node->body->value, 0, 100));
            $companyname = "";
            $term = Term::load($node->get('field_company')->target_id);
            if(!empty($term)){
                $companyname = $term->getName(); 
            } 
            $location = "";
            $term = Term::load($node->get('field_city')->target_id);
            if(empty($term)){
                $term = Term::load($node->get('field_location')->target_id);
                if(!empty($term)){
                    $location = $term->getName(); 
                } 
            } else {
                $location = $term->getName();
            }
            $jobfunctions = "";
            $term = Term::load($node->get('field_job_functions')->target_id);
            $jobfunctions = $term->getName();
            $htmlContent .= '<tr id="node-'.$node->nid->value.'">
                              <td>'.$title.'</td>
                              <td>'.$shortdesc.'</td>
                              <td>'.$jobfunctions.'</td>
                              <td>'.$companyname.'</td>
                              <td>'.$location.'</td>
                              <td><a href="#" class="approvejobs" data-nodeid="'.$node->nid->value.'" data-status="1">Approve </a> / <a href="#" class="approvejobs" data-nodeid="'.$node->nid->value.'" data-status="0">Decline </a></td>
                             </tr>';
        }
      }
    $htmlContent .='</table><br><a href="/admin/iirmalisting/irma-users/irma-csv-download?q=appr">Download</a>';

    $build = array(
        '#type' => 'markup',
        '#markup' => $htmlContent,
      );
      return $build;
  }

  public function ajaxJobApproveDecline(){
    $node = \Drupal\node\Entity\Node::load($_POST['nodeid']);
    $uid = $node->getOwnerId();
    $user = \Drupal\user\Entity\User::load($uid);
    $name   = $user->get('name')->value;
    $email  = $user->get('mail')->value;
    $params = array();
    if(isset($_POST['nodeid']) && !empty($_POST['nodeid']) && isset($_POST['nodestatus']) && $_POST['nodestatus']==0){
      $node->set("status",$_POST['nodestatus']);
      if($_POST['nodestatus']==0){
        $node->set("field_approval_status",1);
      }
      $node->save();
      $body = MailController::getEmailTemplates('job-decline',$params);
      MailController::sendCustomMail("",$email,"IIRMA Job Declined",$body);
      return new JsonResponse(['nodeid' => $_POST['nodeid'], 'status' => $_POST['nodestatus'],'msg'=>'success']);
    } else {
       $node->set("status",$_POST['nodestatus']);
       $node->save();
       $body = MailController::getEmailTemplates('job-approve',$params);
       MailController::sendCustomMail("",$email,"IIRMA Job Approved",$body);
       return new JsonResponse(['nodeid' => $_POST['nodeid'], 'status' => $_POST['nodestatus'],'msg'=>'success']);
    }
  }

  public function campusInfrastructure() {
    $htmlContent ="";
    $build = array(
        '#type' => 'markup',
        '#markup' => $htmlContent,
      );
      return $build;
  }
  public function bookETDCAccomodation() {
	  
	   //$newHashedPassword = new PhpassHashedPassword();
    //echo "<pre>"; print_r($newHashedPassword->hash('rajas@007')); exit;
    $query = \Drupal::database()->select('campus_infra_etdc', 'etdc');
    $query->fields('etdc', ['name', 'batch_number', 'organisation', 'designation', 'email', 'country', 'state', 'city', 'country_code', 'mobile_number', 'arrival_date','arrival_time', 'departure_date', 'departure_time', 'purpose_of_visit','purpose', 'no_persons', 'no_rooms', 'include_food','food_preference']);
    $query->orderBy('date_created','DESC');
    $result = $query->execute();
    $htmlContent = "<table id='contactformdata'>
                     <tr>
                      <th>Name</th>
                      <th>Batch Number</th>
                      <th>Email ID</th>
                      <th>Organisation</th>
                      <th>Designation</th>
                      <th>Country </th>
                      <th>City </th>
                      <th>State</th>
                      <th>Mobile Number</th>
                      <th>Arrival Date</th>
                      <th>Time</th>
                      <th>Departure Date</th>
                      <th>Time</th>
                      <th>Purpose Of Visit</th>
                      <th>State Purpose </th>
                      <th>No of Persons </th>
                      <th>No of rooms required</th>
                      <th>Food To Be Included</th>
                      <th>Food Preference</th>
                     </tr>";
    while ($row = $result->fetchAssoc()) {
      //echo "<pre>"; print_r($row); exit;
      $htmlContent .= '<tr>
                      <td>'.$row['name'].'</td>
                      <td>'.$row['batch_number'].'</td>
                      <td>'.$row['email'].'</td>
                      <td>'.$row['organisation'].'</td>
                      <td>'.$row['designation'].'</td>
                      <td>'.$row['country'].'</td>
                      <td>'.$row['city'].'</td>
                      <td>'.$row['state'].'</td>
                      <td>'.$row['mobile_number'].'</td>
                      <td>'.$row['arrival_date'].'</td>
                      <td>'.$row['arrival_time'].'</td>
                      <td>'.$row['departure_date'].'</td>
                      <td>'.$row['departure_time'].'</td>
                      <td>'.$row['purpose_of_visit'].'</td>
                      <td>'.$row['purpose'].'</td>
                      <td>'.$row['no_persons'].'</td>
                      <td>'.$row['no_rooms'].'</td>
                      <td>'.$row['include_food'].'</td>
                      <td>'.$row['food_preference'].'</td>
                     </tr>';
    }
    $htmlContent .='</table><br><a href="/admin/iirmalisting/irma-users/irma-csv-download?q=etdc">Download</a>';

    $build = array(
        '#type' => 'markup',
        '#markup' => $htmlContent,
      );
      return $build;
	  
  /*  
    $htmlContent ="";

    $build = array(
        '#type' => 'markup',
        '#markup' => $htmlContent,
      );
      return $build; */
  }

  public function wifiAccess() {
    
    $query = \Drupal::database()->select('campus_infra_wifi', 'wifi');
    $query->fields('wifi', ['name', 'batch_number', 'organisation', 'designation', 'email', 'country', 'state', 'city', 'country_code', 'mobile_number', 'arrival_date','arrival_time', 'departure_date', 'departure_time', 'purpose_of_visit','purpose', 'wifi_access']);
    $query->orderBy('date_created','DESC');
    $result = $query->execute();
    $htmlContent = "<table id='wififormdata'>
                     <tr>
                      <th>Name</th>
                      <th>Batch Number</th>
                      <th>Email ID</th>
                      <th>Organisation</th>
                      <th>Designation</th>
                      <th>Country </th>
                      <th>City </th>
                      <th>State</th>
                      <th>Mobile Number</th>
                      <th>Arrival Date</th>
                      <th>Time</th>
                      <th>Departure Date</th>
                      <th>Time</th>
                      <th>Purpose Of Visit</th>
                      <th>State Purpose </th>
                      <th>wifi_access</th>
                     </tr>";
    while ($row = $result->fetchAssoc()) {
      //echo "<pre>"; print_r($row); exit;
      $htmlContent .= '<tr>
                      <td>'.$row['name'].'</td>
                      <td>'.$row['batch_number'].'</td>
                      <td>'.$row['email'].'</td>
                      <td>'.$row['organisation'].'</td>
                      <td>'.$row['designation'].'</td>
                      <td>'.$row['country'].'</td>
                      <td>'.$row['city'].'</td>
                      <td>'.$row['state'].'</td>
                      <td>'.$row['mobile_number'].'</td>
                      <td>'.$row['arrival_date'].'</td>
                      <td>'.$row['arrival_time'].'</td>
                      <td>'.$row['departure_date'].'</td>
                      <td>'.$row['departure_time'].'</td>
                      <td>'.$row['purpose_of_visit'].'</td>
                      <td>'.$row['purpose'].'</td>
                      <td>'.$row['wifi_access'].'</td>
                      
                     </tr>';
    }
    $htmlContent .='</table><br><a href="/admin/iirmalisting/irma-users/irma-csv-download?q=wifi">Download</a>';

    $build = array(
        '#type' => 'markup',
        '#markup' => $htmlContent,
      );
      return $build;
  }

  public function sportsActivityCentre() {
    
    $query = \Drupal::database()->select('campus_infra_sac', 'sac');
    $query->fields('sac', ['name', 'batch_number', 'organisation', 'designation', 'email', 'country', 'state', 'city', 'country_code', 'mobile_number', 'arrival_date','arrival_time', 'departure_date', 'departure_time', 'purpose_of_visit','purpose', 'sac_access']);
    $query->orderBy('date_created','DESC');
    $result = $query->execute();
    $htmlContent = "<table id='contactformdata'>
                     <tr>
                      <th>Name</th>
                      <th>Batch Number</th>
                      <th>Email ID</th>
                      <th>Organisation</th>
                      <th>Designation</th>
                      <th>Country </th>
                      <th>City </th>
                      <th>State</th>
                      <th>Mobile Number</th>
                      <th>Arrival Date</th>
                      <th>Time</th>
                      <th>Departure Date</th>
                      <th>Time</th>
                      <th>Purpose Of Visit</th>
                      <th>State Purpose </th>
                      <th>Sac Access </th>
                     </tr>";
    while ($row = $result->fetchAssoc()) {
      //echo "<pre>"; print_r($row); exit;
      $htmlContent .= '<tr>
                      <td>'.$row['name'].'</td>
                      <td>'.$row['batch_number'].'</td>
                      <td>'.$row['email'].'</td>
                      <td>'.$row['organisation'].'</td>
                      <td>'.$row['designation'].'</td>
                      <td>'.$row['country'].'</td>
                      <td>'.$row['city'].'</td>
                      <td>'.$row['state'].'</td>
                      <td>'.$row['mobile_number'].'</td>
                      <td>'.$row['arrival_date'].'</td>
                      <td>'.$row['arrival_time'].'</td>
                      <td>'.$row['departure_date'].'</td>
                      <td>'.$row['departure_time'].'</td>
                      <td>'.$row['purpose_of_visit'].'</td>
                      <td>'.$row['purpose'].'</td>
                      <td>'.$row['sac_access'].'</td>
                     </tr>';
    }
    $htmlContent .='</table><br><a href="/admin/iirmalisting/irma-users/irma-csv-download?q=sac">Download</a>';

    $build = array(
        '#type' => 'markup',
        '#markup' => $htmlContent,
      );
      return $build;
  }

  public function iirmaStudentsMess() {
    
    $query = \Drupal::database()->select('campus_infra_mess', 'mess');
    $query->fields('mess', ['name','batch_number','email','organisation','designation','country','city','state','mobile_number','arrival_date','arrival_time', 'departure_date', 'departure_time', 'purpose_of_visit','purpose', 'arrange_food', 'num_person']);
    $query->orderBy('date_created','DESC');
    $result = $query->execute();
    $htmlContent = "<table id='contactformdata'>
                     <tr>
                      <th>Name</th>
                      <th>Batch Number</th>
                      <th>Email ID</th>
                      <th>Organisation</th>
                      <th>Designation</th>
                      <th>Country </th>
                      <th>City </th>
                      <th>State</th>
                      <th>Mobile Number</th>
                      <th>Arrival Date</th>
                      <th>Time</th>
                      <th>Departure Date</th>
                      <th>Time</th>
                      <th>Purpose Of Visit</th>
                      <th>State Purpose </th>
                      <th>No of Persons </th>
                      <th>arrange_food</th>
                     
                     </tr>";
    while ($row = $result->fetchAssoc()) {
      //echo "<pre>"; print_r($row); exit;
      $htmlContent .= '<tr>
      <td>'.$row['name'].'</td>
                      <td>'.$row['batch_number'].'</td>
                      <td>'.$row['email'].'</td>
                      <td>'.$row['organisation'].'</td>
                      <td>'.$row['designation'].'</td>
                      <td>'.$row['country'].'</td>
                      <td>'.$row['city'].'</td>
                      <td>'.$row['state'].'</td>
                      <td>'.$row['mobile_number'].'</td>
                      <td>'.$row['arrival_date'].'</td>
                      <td>'.$row['arrival_time'].'</td>
                      <td>'.$row['departure_date'].'</td>
                      <td>'.$row['departure_time'].'</td>
                      <td>'.$row['purpose_of_visit'].'</td>
                      <td>'.$row['purpose'].'</td>
                      <td>'.$row['num_person'].'</td>
                      <td>'.$row['arrange_food'].'</td>
                     </tr>';
    }
    $htmlContent .='</table><br><a href="/admin/iirmalisting/irma-users/irma-csv-download?q=mess">Download</a>';

    $build = array(
        '#type' => 'markup',
        '#markup' => $htmlContent,
      );
      return $build;
  }

  public function library() {
    
    $query = \Drupal::database()->select('campus_infra_library', 'library');
    $query->fields('library', ['name','batch_number','email','organisation','designation','country','city','state','mobile_number','arrival_date','arrival_time', 'departure_date', 'departure_time', 'purpose_of_visit','purpose', 'access_library']);
    $query->orderBy('date_created','DESC');
    $result = $query->execute();
    $htmlContent = "<table id='librarydata'>
                     <tr>
                      <th>Name</th>
                      <th>Batch Number</th>
                      <th>Email ID</th>
                      <th>Organisation</th>
                      <th>Designation</th>
                      <th>Country </th>
                      <th>City </th>
                      <th>State</th>
                      <th>Mobile Number</th>
                      <th>Arrival Date</th>
                      <th>Time</th>
                      <th>Departure Date</th>
                      <th>Time</th>
                      <th>Purpose Of Visit</th>
                      <th>State Purpose </th>
                      <th>Library access</th>
                     </tr>";
    while ($row = $result->fetchAssoc()) {
      //echo "<pre>"; print_r($row); exit;
      $htmlContent .= '<tr>
                      <td>'.$row['name'].'</td>
                      <td>'.$row['batch_number'].'</td>
                      <td>'.$row['email'].'</td>
                      <td>'.$row['organisation'].'</td>
                      <td>'.$row['designation'].'</td>
                      <td>'.$row['country'].'</td>
                      <td>'.$row['city'].'</td>
                      <td>'.$row['state'].'</td>
                      <td>'.$row['mobile_number'].'</td>
                      <td>'.$row['arrival_date'].'</td>
                      <td>'.$row['arrival_time'].'</td>
                      <td>'.$row['departure_date'].'</td>
                      <td>'.$row['departure_time'].'</td>
                      <td>'.$row['purpose_of_visit'].'</td>
                      <td>'.$row['purpose'].'</td>
                      <td>'.$row['access_library'].'</td>
                     </tr>';
    }
    $htmlContent .='</table><br><a href="/admin/iirmalisting/irma-users/irma-csv-download?q=lib">Download</a>';

    $build = array(
        '#type' => 'markup',
        '#markup' => $htmlContent,
      );
      return $build;
  }

  public function getInvolved() {
    
    $htmlContent ="";

    $build = array(
        '#type' => 'markup',
        '#markup' => $htmlContent,
      );
      return $build;
  }

  public function classroomSessions() {
    $htmlContent ="";
    $query = \Drupal::database()->select('classroom_sessions', 'classroomsessions');
    $query->fields('classroomsessions', ['name', 'batch_number', 'organisation', 'designation', 'email', 'country', 'state', 'city', 'country_code', 'mobile_number', 'session_brief', 'concerned_subject_grp','hours_required']);
    $query->orderBy('date_created','DESC');
    $result = $query->execute();
    $htmlContent = "<table id='contactformdata'>
                     <tr>
                      <th>Name</th>
                      <th>Batch Number</th>
                      <th>Email ID</th>
                      <th>Organisation</th>
                      <th>Designation</th>
                      <th>Country </th>
                      <th>City </th>
                      <th>State</th>
                      <th>Mobile Number</th>
                      <th>Brief About Proposed Classroom Session *</th>
                      <th>Concerned IRMA Subject Group</th>
                      <th>Number Of Hours Required For One Session *</th>
                     </tr>";
    while ($row = $result->fetchAssoc()) {
      //echo "<pre>"; print_r($row); exit;
      $htmlContent .= '<tr>
                      <td>'.$row['name'].'</td>
                      <td>'.$row['batch_number'].'</td>
                      <td>'.$row['email'].'</td>
                      <td>'.$row['organisation'].'</td>
                      <td>'.$row['designation'].'</td>
                      <td>'.$row['country'].'</td>
                      <td>'.$row['city'].'</td>
                      <td>'.$row['state'].'</td>
                      <td>'.$row['mobile_number'].'</td>
                      <td>'.$row['session_brief'].'</td>
                      <td>'.$row['concerned_subject_grp'].'</td>
                      <td>'.$row['hours_required'].'</td>
                     </tr>';
    }
    $htmlContent .='</table><br><a href="/admin/iirmalisting/irma-users/irma-csv-download?q=classroom">Download</a>';

    $build = array(
        '#type' => 'markup',
        '#markup' => $htmlContent,
      );
      return $build;
  }

  public function inviteFaculty() {
    $query = \Drupal::database()->select('invite_faculty', 'invitefaculty');
    $query->fields('invitefaculty', ['name', 'batch_number', 'organisation', 'designation', 'email', 'country', 'state', 'city', 'country_code', 'mobile_number', 'decision_maker_name','decision_maker_email', 'decision_maker_mobile', 'workshop_brief', 'concerned_subject_grp','concerned_faculty_contact', 'workshop_start_date', 'workshop_end_date']);
    $query->orderBy('date_created','DESC');
    $result = $query->execute();
    $htmlContent = "<table id='contactformdata'>
                     <tr>
                      <th>Name</th>
                      <th>Batch Number</th>
                      <th>Email ID</th>
                      <th>Organisation</th>
                      <th>Designation</th>
                      <th>Country </th>
                      <th>City </th>
                      <th>State</th>
                      <th>Mobile Number</th>
                      <th>Name Of Decision Maker</th>
                      <th>Email ID Of Decision Maker</th>
                      <th>Contact Number Of Decision Maker</th>
                      <th>Brief About Proposed Workshop</th>
                      <th>IRMA Subject Group</th>
                      <th>Concerned IRMA Faculty To Be Contacted</th>
                      <th>Expected Start Date Of Workshop</th>
                      <th>Expected End Date Of Workshop</th>
                     </tr>";
    while ($row = $result->fetchAssoc()) {
      //echo "<pre>"; print_r($row); exit;
      $htmlContent .= '<tr>
                      <td>'.$row['name'].'</td>
                      <td>'.$row['batch_number'].'</td>
                      <td>'.$row['email'].'</td>
                      <td>'.$row['organisation'].'</td>
                      <td>'.$row['designation'].'</td>
                      <td>'.$row['country'].'</td>
                      <td>'.$row['city'].'</td>
                      <td>'.$row['state'].'</td>
                      <td>'.$row['mobile_number'].'</td>
                      <td>'.$row['decision_maker_name'].'</td>
                      <td>'.$row['decision_maker_email'].'</td>
                      <td>'.$row['decision_maker_mobile'].'</td>
                      <td>'.$row['workshop_brief'].'</td>
                      <td>'.$row['concerned_subject_grp'].'</td>
                      <td>'.$row['concerned_faculty_contact'].'</td>
                      <td>'.$row['workshop_start_date'].'</td>
                      <td>'.$row['workshop_end_date'].'</td>
                     </tr>';
    }
    $htmlContent .='</table><br><a href="/admin/iirmalisting/irma-users/irma-csv-download?q=faculty">Download</a>';

    $build = array(
        '#type' => 'markup',
        '#markup' => $htmlContent,
      );
      return $build;
  }

  public function projectApply() {
    $query = \Drupal::database()->select('project_apply', 'projapp');
    $query->fields('projapp', ['name', 'batch_number', 'organisation', 'designation', 'email', 'country', 'state', 'city', 'country_code', 'mobile_number', 'decision_maker_name','decision_maker_email', 'decision_maker_mobile', 'project_brief', 'concerned_subject_grp','concerned_faculty_contact', 'project_start_date', 'project_end_date']);
    $query->orderBy('date_created','DESC');
    $result = $query->execute();
    $htmlContent = "<table id='contactformdata'>
                     <tr>
                      <th>Name</th>
                      <th>Batch Number</th>
                      <th>Email ID</th>
                      <th>Organisation</th>
                      <th>Designation</th>
                      <th>Country </th>
                      <th>City </th>
                      <th>State</th>
                      <th>Mobile Number</th>
                      <th>Name Of Decision Maker</th>
                      <th>Email ID Of Decision Maker</th>
                      <th>Contact Number Of Decision Maker</th>
                      <th>Brief About Proposed Project</th>
                      <th>IRMA Subject Group</th>
                      <th>Concerned IRMA Faculty To Be Contacted</th>
                      <th>Expected Start Date Of Project</th>
                      <th>Expected End Date Of Project</th>
                     </tr>";
    while ($row = $result->fetchAssoc()) {
      //echo "<pre>"; print_r($row); exit;
      $htmlContent .= '<tr>
                      <td>'.$row['name'].'</td>
                      <td>'.$row['batch_number'].'</td>
                      <td>'.$row['email'].'</td>
                      <td>'.$row['organisation'].'</td>
                      <td>'.$row['designation'].'</td>
                      <td>'.$row['country'].'</td>
                      <td>'.$row['city'].'</td>
                      <td>'.$row['state'].'</td>
                      <td>'.$row['mobile_number'].'</td>
                      <td>'.$row['decision_maker_name'].'</td>
                      <td>'.$row['decision_maker_email'].'</td>
                      <td>'.$row['decision_maker_mobile'].'</td>
                      <td>'.$row['project_brief'].'</td>
                      <td>'.$row['concerned_subject_grp'].'</td>
                      <td>'.$row['concerned_faculty_contact'].'</td>
                      <td>'.$row['project_start_date'].'</td>
                      <td>'.$row['project_end_date'].'</td>
                     </tr>';
    }
    $htmlContent .='</table><br><a href="/admin/iirmalisting/irma-users/irma-csv-download?q=project">Download</a>';

    $build = array(
        '#type' => 'markup',
        '#markup' => $htmlContent,
      );
      return $build;
  }

  public function caseStudy() {
    $query = \Drupal::database()->select('case_study', 'casestudy');
    $query->fields('casestudy', ['name', 'batch_number', 'organisation', 'designation', 'email', 'country', 'state', 'city', 'country_code', 'mobile_number', 'decision_maker_name','decision_maker_email', 'decision_maker_mobile', 'cs_brief', 'concerned_subject_grp','concerned_faculty_contact', 'cs_start_date', 'cs_end_date']);
    $query->orderBy('date_created','DESC');
    $result = $query->execute();
    $htmlContent = "<table id='contactformdata'>
                     <tr>
                      <th>Name</th>
                      <th>Batch Number</th>
                      <th>Email ID</th>
                      <th>Organisation</th>
                      <th>Designation</th>
                      <th>Country </th>
                      <th>City </th>
                      <th>State</th>
                      <th>Mobile Number</th>
                      <th>Name Of Decision Maker</th>
                      <th>Email ID Of Decision Maker</th>
                      <th>Contact Number Of Decision Maker</th>
                      <th>Brief About Proposed Case Study</th>
                      <th>IRMA Subject Group</th>
                      <th>Concerned IRMA Faculty To Be Contacted</th>
                      <th>Start Date</th>
                      <th>End Date</th>
                     </tr>";
    while ($row = $result->fetchAssoc()) {
      //echo "<pre>"; print_r($row); exit;
      $htmlContent .= '<tr>
                      <td>'.$row['name'].'</td>
                      <td>'.$row['batch_number'].'</td>
                      <td>'.$row['email'].'</td>
                      <td>'.$row['organisation'].'</td>
                      <td>'.$row['designation'].'</td>
                      <td>'.$row['country'].'</td>
                      <td>'.$row['city'].'</td>
                      <td>'.$row['state'].'</td>
                      <td>'.$row['mobile_number'].'</td>
                      <td>'.$row['decision_maker_name'].'</td>
                      <td>'.$row['decision_maker_email'].'</td>
                      <td>'.$row['decision_maker_mobile'].'</td>
                      <td>'.$row['cs_brief'].'</td>
                      <td>'.$row['concerned_subject_grp'].'</td>
                      <td>'.$row['concerned_faculty_contact'].'</td>
                      <td>'.$row['cs_start_date'].'</td>
                      <td>'.$row['cs_end_date'].'</td>
                     </tr>';
    }
    $htmlContent .='</table><br><a href="/admin/iirmalisting/irma-users/irma-csv-download?q=casestudy">Download</a>';

    $build = array(
        '#type' => 'markup',
        '#markup' => $htmlContent,
      );
      return $build;
  }

  public function referRecruiter() {
    $htmlContent ="";
    $query = \Drupal::database()->select('refer_recruiter', 'referrecruiter');
    $query->fields('referrecruiter', ['name', 'batch_number', 'organisation', 'designation', 'email', 'country', 'state', 'city', 'country_code', 'mobile_number', 'hr_name', 'decision_maker_name','decision_maker_email', 'decision_maker_mobile', 'recruitment_month', 'other_details']);
    $query->orderBy('date_created','DESC');
    $result = $query->execute();
    $htmlContent = "<table id='contactformdata'>
                     <tr>
                      <th>Name</th>
                      <th>Batch Number</th>
                      <th>Email ID</th>
                      <th>Organisation</th>
                      <th>Designation</th>
                      <th>Country </th>
                      <th>City </th>
                      <th>State</th>
                      <th>Mobile Number</th>
                      <th>Name Of HR</th>
                      <th>Name Of Decision Maker</th>
                      <th>Email ID Of Decision Maker</th>
                      <th>Contact Number Of Decision Maker</th>
                      <th>Recruitment Month</th>
                      <th>Other Details</th>
                     </tr>";
    while ($row = $result->fetchAssoc()) {
      //echo "<pre>"; print_r($row); exit;
      $htmlContent .= '<tr>
                      <td>'.$row['name'].'</td>
                      <td>'.$row['batch_number'].'</td>
                      <td>'.$row['email'].'</td>
                      <td>'.$row['organisation'].'</td>
                      <td>'.$row['designation'].'</td>
                      <td>'.$row['country'].'</td>
                      <td>'.$row['city'].'</td>
                      <td>'.$row['state'].'</td>
                      <td>'.$row['mobile_number'].'</td>
                      <td>'.$row['hr_name'].'</td>
                      <td>'.$row['decision_maker_name'].'</td>
                      <td>'.$row['decision_maker_email'].'</td>
                      <td>'.$row['decision_maker_mobile'].'</td>
                      <td>'.$row['recruitment_month'].'</td>
                      <td>'.$row['other_details'].'</td>
                     </tr>';
    }
    $htmlContent .='</table><br><a href="/admin/iirmalisting/irma-users/irma-csv-download?q=refer">Download</a>';

    $build = array(
        '#type' => 'markup',
        '#markup' => $htmlContent,
      );
      return $build;
  }
  
  public function managementdevelopmentprogPage() {
    
    $query = \Drupal::database()->select('mdp_data', 'mdpdata');
    $query->fields('mdpdata', ['name', 'batch_number', 'organisation', 'designation', 'email', 'country', 'state', 'city', 'country_code', 'mobile_number', 'mdp_name','start_date', 'end_date', 'mdp_query']);
    $query->orderBy('date_created','DESC');
    $result = $query->execute();
    $htmlContent = "<table id='contactformdata'>
                     <tr>
                      <th>Name</th>
                      <th>Batch Number</th>
                      <th>Email ID</th>
                      <th>Organisation</th>
                      <th>Designation</th>
                      <th>Country </th>
                      <th>City </th>
                      <th>State</th>
                      <th>Mobile Number</th>
                      <th>MDP Name</th>
                      <th>MDP Start Date *</th>
                      <th>MDP End Date</th>
                      <th>MDP Query</th>
                     </tr>";
    while ($row = $result->fetchAssoc()) {
      //echo "<pre>"; print_r($row); exit;
      $htmlContent .= '<tr>
                      <td>'.$row['name'].'</td>
                      <td>'.$row['batch_number'].'</td>
                      <td>'.$row['email'].'</td>
                      <td>'.$row['organisation'].'</td>
                      <td>'.$row['designation'].'</td>
                      <td>'.$row['country'].'</td>
                      <td>'.$row['city'].'</td>
                      <td>'.$row['state'].'</td>
                      <td>'.$row['mobile_number'].'</td>
                      <td>'.$row['mdp_name'].'</td>
                      <td>'.$row['start_date'].'</td>
                      <td>'.$row['end_date'].'</td>
                      <td>'.$row['mdp_query'].'</td>
                     </tr>';
    }
    $htmlContent .='</table><br><a href="/admin/iirmalisting/irma-users/irma-csv-download?q=mdp">Download</a>';

    $build = array(
        '#type' => 'markup',
        '#markup' => $htmlContent,
      );
      return $build;
  }
  
  public function givetoiaaPage() {
    
    $query = \Drupal::database()->select('giveto_iaa', 'giveiaa');
    $query->fields('giveiaa', ['name', 'batch_number', 'organisation', 'designation', 'email', 'country', 'state', 'city', 'contact_no', 'postal_addr1', 'postal_addr2','postal_addr3', 'postal_city', 'postal_state', 'postal_country','postal_code', 'give_to', 'payment_purpose','pan_no', 'donation_amt', 'anonymous_gift']);
    $query->orderBy('date_created','DESC');
    $result = $query->execute();
    $htmlContent = "<table id='contactformdata'>
                     <tr>
                      <th>Name</th>
                      <th>Batch Number</th>
                      <th>Email ID</th>
                      <th>Organisation</th>
                      <th>Designation</th>
                      <th>Country </th>
                      <th>City </th>
                      <th>State</th>
                      <th>Contact Number*</th>
                      <th>Postal Address 1</th>
                      <th>Postal Address 2</th>
                      <th>Postal Address 3</th>
                      <th>City </th>
                      <th>state </th>
                      <th>Country </th>
                      <th>Code </th>
                      <th>Give to  </th>
                      <th>Payment purpose </th>
                      <th>Pan no</th>
                      <th>Donation amount</th>
                      <th>Anonymous gift.  </th>
                     </tr>";
    while ($row = $result->fetchAssoc()) {
      //echo "<pre>"; print_r($row); exit;
      $htmlContent .= '<tr>
                      <td>'.$row['name'].'</td>
                      <td>'.$row['batch_number'].'</td>
                      <td>'.$row['email'].'</td>
                      <td>'.$row['organisation'].'</td>
                      <td>'.$row['designation'].'</td>
                      <td>'.$row['country'].'</td>
                      <td>'.$row['city'].'</td>
                      <td>'.$row['state'].'</td>
                      <td>'.$row['contact_no'].'</td>
                      <td>'.$row['postal_addr1'].'</td>
                      <td>'.$row['postal_addr2'].'</td>
                      <td>'.$row['postal_addr3'].'</td>
                      <td>'.$row['postal_city'].'</td>
                      <td>'.$row['postal_state'].'</td>
                      <td>'.$row['postal_country'].'</td>
                      <td>'.$row['postal_code'].'</td>
                      <td>'.$row['give_to'].'</td>
                      <td>'.$row['payment_purpose'].'</td>
                      <td>'.$row['pan_no'].'</td>
                      <td>'.$row['donation_amt'].'</td>
                      <td>'.$row['anonymous_gift'].'</td>
                     </tr>';
    }
    $htmlContent .='</table><br><a href="/admin/iirmalisting/irma-users/irma-csv-download?q=giveto">Download</a>';

    $build = array(
        '#type' => 'markup',
        '#markup' => $htmlContent,
      );
      return $build;
  }
  
    
   public function eventregPage() {
    
    $query = \Drupal::database()->select('events_registration', 'eventreg');
    $query->fields('eventreg', ['name', 'batch_number', 'organisation', 'designation', 'email', 'country', 'state', 'city', 'contact_no', 'arrival_date', 'pickup_reqd','pickup_loc', 'depart_date', 'drop_reqd', 'drop_loc','num_guests', 'food_pref', 'accom_pref', 'hostel_pref']);
    $query->orderBy('date_created','DESC');
    $result = $query->execute();
    $htmlContent = "<table id='contactformdata'>
                     <tr>
                      <th>Name</th>
                      <th>Batch Number</th>
                      <th>Email ID</th>
                      <th>Organisation</th>
                      <th>Designation</th>
                      <th>Country </th>
                      <th>City </th>
                      <th>State</th>
                      <th>Contact Number</th>
                      <th>Arrival Date & Time</th>
                      <th>Pickup Required</th>
                      <th>Pickup Location</th>
                      <th>Departure Date & Time</th>
                      <th>Drop Required</th>
                      <th>Drop Location</th>
                      <th>No. Of Guests Accompanying</th>
                      <th>Food Preference</th>
                      <th>Stay Preference</th>
                      <th>Hostel Preference</th>
                   
                     </tr>";
    while ($row = $result->fetchAssoc()) {
      //echo "<pre>"; print_r($row); exit;
      $htmlContent .= '<tr>
                      <td>'.$row['name'].'</td>
                      <td>'.$row['batch_number'].'</td>
                      <td>'.$row['email'].'</td>
                      <td>'.$row['organisation'].'</td>
                      <td>'.$row['designation'].'</td>
                      <td>'.$row['country'].'</td>
                      <td>'.$row['city'].'</td>
                      <td>'.$row['state'].'</td>
                      <td>'.$row['contact_no'].'</td>
                      <td>'.$row['arrival_date'].'</td>
                      <td>'.$row['pickup_reqd'].'</td>
                      <td>'.$row['pickup_loc'].'</td>
                      <td>'.$row['depart_date'].'</td>
                      <td>'.$row['drop_reqd'].'</td>
                      <td>'.$row['drop_loc'].'</td>
                      <td>'.$row['num_guests'].'</td>
                      <td>'.$row['food_pref'].'</td>
                      <td>'.$row['accom_pref'].'</td>
                      <td>'.$row['hostel_pref'].'</td>
                     </tr>';
    }
    $htmlContent .='</table><br><a href="/admin/iirmalisting/irma-users/irma-csv-download?q=evntReg">Download</a>';

    $build = array(
        '#type' => 'markup',
        '#markup' => $htmlContent,
      );
      return $build;
  }

  public function job_applications() {
    $htmlContent = '';
    $query = \Drupal::database()->select('apply_irma_profile', 'aip');
    $query->fields('aip', ['job_id', 'user_id', 'fid', 'irma_profile', 'date_created', 'status']);
    $query->orderBy('aip.id', 'DESC');
    $pager = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')
          ->limit(10);
    $jobs = $pager->execute();
    $htmlContent = "<table id='wififormdata'>
                     <tr>
                      <th>Company</th>
                      <th>Position</th>
                      <th>Applied with IRMA profile</th>
                      <th>Date</th>
                      <th>Action</th>
                     </tr>";
    
    foreach($jobs as $job) {
      $job_id       = $job->job_id;
      $user_id      = $job->user_id;
      $fid          = $job->fid;
      $created      = $job->date_created;
      $node         = \Drupal\node\Entity\Node::load($job->job_id);
      //echo '<pre>'; print_r($node); echo '</pre>';
      $title = $node->title->value;
      //$tid = $node->field_company->getValue();
      $tid = $node->get('field_company')->getString();
      $query = \Drupal::database()->select('taxonomy_term_field_data', 'tfd');
      $query->addField('tfd', 'name');
      $query->condition('tfd.tid', $tid);
      $query->range(0, 1);
      $company = $query->execute()->fetchField();
      if($job->irma_profile == 0) {
        $app = 'No';
        $query = \Drupal::database()->select('file_managed', 'fm');
        $query->addField('fm', 'uri');
        $query->condition('fm.fid', $job->fid);
        $query->range(0, 1);
        $uri = $query->execute()->fetchField();
        $fileurl = file_create_url($uri);
        $irma_profile = '<a href="'.$fileurl.'">Download Resume</a>';
      } else {
        $app = 'Yes';
        $irma_profile = '<a href="/admin/iirmalisting/irma-users?uid='.$user_id.'" target="_blank">View User Profile</a>';
      }
      $htmlContent .= '<tr><th>'.$company.'</th><th>'.$title.'</th><th>'.$app.'</th><th>'.$created.'</th><th>'.$irma_profile.'</th></tr>';
    }
    $htmlContent .= '</table><br><a href="/admin/iirmalisting/irma-users/irma-csv-download?q=jobApp">Download</a>';
    $output = array(
        '#markup' => $htmlContent,
    );
    $build['result'] = $output;
    $build['pager'] = [
      '#type' => 'pager',
    ];
    return $build;
  }

}
