<?php
namespace Drupal\custom_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Component\Annotation\Plugin;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\image\Entity\ImageStyle;
use Drupal\taxonomy\Entity\Term;

/**
 * Provides a 'Custom' Block
 *
 * @Block(
 *   id = "jobs_list",
 *   admin_label = @Translation("Jobs Listing"),
 * )
 */

class CustomJobsList extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    
    /* You can put any PHP code here if required */
    //print ('Hello World - PHP version');
    
    $output = '';
    $load_more_show = false;
    $query = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->condition('type', 'jobs')
        ->sort('created', 'DESC');
        //->range(0,6);
    if(isset($_POST['jobtitle']) && !empty($_POST['jobtitle'])){
      $query->condition('field_job_positions', $_POST['jobtitle'], 'IN');
    }
    if(isset($_POST['location']) && !empty($_POST['location'])){
      $query->condition('field_city', $_POST['location'], 'IN');
    }
    if(isset($_POST['company']) && !empty($_POST['company'])){
      $query->condition('field_company', $_POST['company'], 'IN');
    }
    if(isset($_POST['functions']) && !empty($_POST['functions'])){
      $query->condition('field_job_functions', $_POST['functions'], 'IN');
    }
    $nids = $query->execute();
    //$count = $query->count()->execute();
    if($count > 7) {
      $load_more_show = true;
    }
    $nodes = entity_load_multiple('node', $nids);
    $companyTerms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('organization');
    $jobfunctionsTerms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('job_functions');
    $locationTerms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('city');
    $jobPositionTerms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadTree('job_position');
    //echo "<pre>"; print_r($_POST); exit;
    $filterOutput = '
    <div class="naws_listing postjobSec">
            <div class="container">
                <form name="" id="jobsearchform" action="" method="POST"/>
                <div class="leftSec">
                    <ul>
                        <li>
                            <select data-placeholder="By job title" id="installationDiffusion" multiple="multiple" name="jobtitle[]" class="chosen">';
    foreach ($jobPositionTerms as $key => $value) {
        if(isset($_POST['jobtitle']) && in_array($value->tid,$_POST['jobtitle'])){
            $selected = "selected";
        }
        else
        {
            $selected="";
        }
        $filterOutput .= '<option value="'.$value->tid.'" '.$selected.'>'.$value->name.'</option>';
    }
    $filterOutput .=       '</select>
                        </li>
                        <li>
                            <select data-placeholder="By location" id="installationDiffusion" multiple="multiple" name="location[]" class="chosen">';
    foreach ($locationTerms as $key => $value) {
        if(isset($_POST['location']) && in_array($value->tid,$_POST['location'])){
            $selected = "selected";
        }
        else
        {
            $selected="";
        }
        $filterOutput .= '<option value="'.$value->tid.'" '.$selected.'>'.$value->name.'</option>';
    }
    $filterOutput .=    '</select>
                        </li>
                        <li>
                            <select data-placeholder="By company" id="installationDiffusion" multiple="multiple" name="company[]" class="chosen">';
    foreach ($companyTerms as $key => $value) {
        if(isset($_POST['company']) && in_array($value->tid,$_POST['company'])){
            $selected = "selected";
        }
        else
        {
            $selected="";
        }
        $filterOutput .= '<option value="'.$value->tid.'" '.$selected.'>'.$value->name.'</option>';
    }
    $filterOutput .=   '</select>
                        </li>
                        <li>
                            <select data-placeholder="By Function" id="installationDiffusion" multiple="multiple" name="functions[]" class="chosen">';
    foreach ($jobfunctionsTerms as $key => $value) {
        if(isset($_POST['functions']) && in_array($value->tid,$_POST['functions'])){
            $selected = "selected";
        }
        else
        {
            $selected="";
        }
        $filterOutput .= '<option value="'.$value->tid.'" '.$selected.'>'.$value->name.'</option>';
    }
    $filterOutput .=       '</select>
                            <!--<a class="searchBtn" href="javascript:;"></a>-->
                            <input type="submit" class="searchBtn" value="" />
                        </li>
                    </ul>
                </div>
                </form>
                <div class="rightBtn">
                    <a class="button postjob" href="/alumni-network/careers/post-a-job">Post a Job</a>
                </div>
                <i class="clrBoth"></i>
            </div>
        </div>';

    $listOutput = '';
    $listOutput .= '<div class="managementLest jobsListing loadMoreFunc" data-show="3" data-load="3">';
    
    $node_count = 1;
    $listOutput .= '<div class="container"><ul id="jobsList">';
    foreach($nodes as $node) {
        
      //if($node_count < 7) {
        //echo "<pre>"; print_r($node); exit;
        $node_url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$node->nid->value);
        $shortdesc = preg_replace('/\s+?(\S+)?$/', '', substr($node->body->value, 0, 300));    
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
        //echo "<pre>heloooo"; print_r($name); exit; 
        //$image = file_create_url($node->field_image->entity->getFileUri());      
        if(strlen($node->title->value) > 45) {
          $title = preg_replace('/\s+?(\S+)?$/', '', substr($node->title->value, 0, 40)). '...';
        } else {
          $title = $node->title->value;
        }

        $listOutput .='
                    <li  rel="'.$node->nid->value.'">
                        <div class="contentSec">
                            <div class="titleSec">
                                <h4>'.$title.'</h4>
                                <h6>'.$companyname.' - '.$location.'</h6>
                            </div>
                            <div class="discription">
                                <p>Min Exp: '.$node->field_experience->value.' - '.$node->field_maximum_experience->value.' Yrs.</p>
                            </div>
                        </div>
                        <div class="btnSec" rel="'.date('d-m-Y', $node->created->value).'">
                            <a class="button" href="'.$node_url.'">View Details</a>
                        </div>
                    </li>';
        $node_count++;
      //}
    }
    $listOutput .= '</ul></div><a class="button jobsLoadMore" href="javascript:;">Load more</a>';
    $listOutput .='</div>';

    return array(
      '#title' => $this->t('Jobs Listing'),
      '#markup' => $this->t($filterOutput.$listOutput),
    );

  }

}

