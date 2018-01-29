<?php
namespace Drupal\custom_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Component\Annotation\Plugin;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\taxonomy\Entity\Term;

/**
 * Provides a 'Custom' Block
 *
 * @Block(
 *   id = "page_banner",
 *   admin_label = @Translation("Page Banner"),
 * )
 */

class CustomBannerBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    
    $request = \Drupal::request();
    $page_title = '';
    // Assuming the Request is $request.
    if ($request->attributes->has('_title')) {
      if(strtolower($request->attributes->get('_title')) != 'apply') {
        $page_title = $request->attributes->get('_title');
      }
    }    

      $current_url = Url::fromRoute('<current>');
      $path = $current_url->toString();
      $arg = explode('/',$path);
      
      //echo 'PATH' . $path; exit;
      //if(isset($arg[4]) && $arg[4] == 'apply') {
      //  $title = str_replace('-', ' ', $arg[2]); 
      //}
      
      //if($arg[1] == 'my-account' || $arg[1] == 'register' || $arg[1] == 'reset-password' || $arg[1] == 'change-password') {
      if(!isset($arg[2]) || ($arg[1] == 'my-account' || $arg[1] == 'register' || $arg[1] == 'reset-password' || $arg[1] == 'change-password')) {
        $page = str_replace('-', ' ', $arg[1]);      
      } else {
        if(isset($arg[2])) {
          $page = str_replace('-', ' ', $arg[2]);
        }
      }
      //echo 'PAGE '. $page;
      //echo '<pre>'; print_r($arg); echo '</pre>';
      //$url = Url::fromRoute('<current>',array(),array('absolute'=>'true'))->toString();
      
      if(isset($arg[3])) {
        $title = ucwords(str_replace('-', ' ', $arg[3]));
        if(trim($title)=="Chapters" || trim($title) == 'Upcoming Events' || trim($title) == 'Archived Events'){
          if(isset($arg[4]) && !empty($arg[4])){
            $title = ucwords(str_replace('-', ' ', $arg[4]));
          }
        }
      } elseif(isset($arg[2])) {
        if($arg[2] != 'alumni-news' || $arg[2] != 'careers') {
          if(is_numeric($arg[2])) {
            $title = ucwords(str_replace('-', ' ', $arg[1]));
          } else {
            if(isset($arg[3])) {
              $title = ucwords(str_replace('-', ' ', $arg[3]));
            }
          }
        }
      } else {
        $title = ucwords($page);
      }
  
      if (\Drupal::routeMatch()->getRouteName() == 'entity.taxonomy_term.canonical' && $tid = \Drupal::routeMatch()->getRawParameter('taxonomy_term')) {
        $termdata = Term::load($tid);
        $title = $termdata->getName();
      }
    //echo $page;
    //echo $title;
    $banner_html = '';
    $query = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->condition('type', 'page_banner')
        ->condition('field_page.value', $page);
    $nids = $query->execute();

    $nodes = entity_load_multiple('node', $nids);
    foreach($nodes as $node) {
      //echo "<pre>"; print_r($node->nid->value);
      if($title != '') {
        $title = $title;
      } else {
        $title = $node->title->value;
      }
      //echo 'NT '.  $title;
      if($title == '') {
        $title = $page_title;
      }
      
      //echo 'TITLE' . $title;
      $image = file_create_url($node->field_image->entity->getFileUri());
      $banner_html .= '<img src="'.$image.'" alt=""><div class="container"><h2 class="banner-title '.$node->field_select_title_color->value.'">'.$title.'</h2></div>';
    }
    
    
    
    return array(
      '#markup' => $this->t($banner_html),
      /* If you want to bypass Drupal 8's default caching for this block then simply add this, otherwise remove the next three line */
      '#cache' => array(
          'max-age' => 0,
      ),
    );
  }
}