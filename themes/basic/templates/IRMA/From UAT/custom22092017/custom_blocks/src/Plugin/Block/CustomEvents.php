<?php
  namespace Drupal\custom_blocks\Plugin\Block;
  
  use Drupal\Core\Block\BlockBase;
  use Drupal\Component\Annotation\Plugin;
  use Drupal\Core\Annotation\Translation;
  use Drupal\Core\Url;
  use Drupal\Core\Link;
  use Drupal\image\Entity\ImageStyle;
  use Drupal\KernelTests\Core\Datetime;

/**
 * Provides a 'Custom Events' Block
 *
 * @Block(
 *   id = "Events",
 *   admin_label = @Translation("Events"),
 * )
 */

class CustomEvents extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    
    $cur_date   = \Drupal::time()->getRequestTime();
    $cur_date   = date('Y-m-d', $cur_date);
    /*-------------- Update URL alias -----------------------------*/
    $logged_in = \Drupal::currentUser()->isAuthenticated();
      $qry = \Drupal::entityQuery('node')
            ->condition('status', 1)
            ->condition('type', 'events')
            ->condition('field_event_date.value', $cur_date, '<');
      $node_ids = $qry->execute();
      foreach($node_ids as $node_id) {
        $source = '/node/'.$node_id;
        $quer = \Drupal::database()->select('url_alias', 'url');
        $quer->addField('url', 'alias');
        $quer->condition('url.source', $source);
        $quer->range(0, 1);
        $url_alias = $quer->execute()->fetchField();
        $new_alias = str_replace('upcoming-events', 'archived-events', $url_alias);
        $up_query = \Drupal::database()->update('url_alias');
        $up_query->fields([
          'alias' => $new_alias
        ]);
        $up_query->condition('source', $source);
        $up_query->execute();
      }
      /*-------------- Update URL alias -----------------------------*/
  
      $load_more  = false;    
      $current_uri = \Drupal::request()->getRequestUri();
      $temp = explode('/', $current_uri);
      if($temp[3] == 'archived-events') {
        $current_page = 'archived';
        $op = '<';
      } else {
        $current_page = 'upcoming';
        $op = '>=';
      }  
          
      $output = '<div class="managementLest archived_eventsP"><div class="container"><ul class="alList archivList">';
      $dd_query = \Drupal::entityQuery('node')
              ->condition('status', 1)
              ->condition('type', 'events')
              ->condition('field_event_date.value', $cur_date, $op)
              ->sort('nid', 'DESC');
      $ids = $dd_query->execute();
      $dd_nodes = entity_load_multiple('node', $ids);
      $year = '';
      $evt_venue = '';
      $year_arr = array();
      $venue_arr = array();
      foreach($dd_nodes as $row) {
        $evt_year = date('Y', strtotime($row->field_event_date->value));
        if(!in_array($evt_year, $year_arr)) {
          array_push($year_arr, $evt_year);
          $year .= '<option value="'.$evt_year.'">'.$evt_year.'</option>';
        }
        if(!in_array($row->field_event_venue->value, $venue_arr)) {
          array_push($venue_arr, $row->field_event_venue->value);
          $evt_venue .= '<option value="'.$row->field_event_venue->value.'">'.$row->field_event_venue->value.'</option>';
        }
      }
      
      $query = \Drupal::entityQuery('node')
            ->condition('status', 1)
            ->condition('type', 'events')
            ->condition('field_event_date.value', $cur_date, $op)
            ->sort('nid', 'DESC')
            ->range(0,10);
      $nids = $query->execute();
      $cnt = $query->count()->execute();
      if($cnt > 9) {
        $load_more = true;
      }
      if($cnt > 0) {
        $nodes = entity_load_multiple('node', $nids);
        $count = 0;
        foreach($nodes as $node) {
          if($count <9) {
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
            $output .= '<div class="contentSec"><div class="titleSec"><h4>'.$node->title->value.'</h4></div>';
            $output .= '<div class="discription">'.$shortdesc.'...</p></div>';
            $output .= '<div class="addrSec"><span class="date">'.$event_date.'</span><span class="location">'.$node->field_event_venue->value.'</span></div>';
            $output .= '<div class="btnSec"><a class="button" href="'.$alias.'">View Details</a><a class="shareBtn" href="javascript:;">Share</a><div class="social"></div></div>';
            $output .= '</div></li>';
            $count++;
          }
        }
      } else {
        $output .= '<h2>No Upcoming events at the moment.</h2>'; 
      }  
      $output .= '</ul></div></div>';
        
      if($load_more) {
        $output .= '<a class="button eventsLoadMore" href="javascript:;">Load more</a>';
      }
      
      $top_html = '<div class="naws_listing archived_entsS">
              <div class="container">
                  <div class="shortBy customSelect">
                      <p>Sort by: </p>
                      <div class="dropdownWrap">
                        <a class="shortDropLink" href="javascript:;"></a>
                        <select class="sortYear"><option val="">Select</option>'.$year.'</select>
                      </div>
                      <div class="dropdownWrap">
                        <a class="shortDropLink" href="javascript:;"></a>
                        <select class="sortEvnt"><option val="">Select</option>'.$evt_venue.'</select>
                      </div>
                  </div>
                  <i class="clrBoth"></i>
              </div>
          </div>';
          
      $output = $top_html . $output;

    //echo $output;
    //exit;
    return array(
      '#title'  => $this->t('Events'),
      '#markup' => $this->t($output),
      '#cache'  => array('max-age' => 0 ),
    );

  }
}