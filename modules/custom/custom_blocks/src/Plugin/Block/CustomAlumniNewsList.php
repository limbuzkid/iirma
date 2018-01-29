<?php
namespace Drupal\custom_blocks\Plugin\Block;

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
 *   id = "alumni_news_list",
 *   admin_label = @Translation("Alumni News List"),
 * )
 */

class CustomAlumniNewsList extends BlockBase {
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
        ->condition('type', 'alumni_news')
        ->sort('field_news_date', 'DESC')
        ->range(0,10);
    $nids = $query->execute();
    $count = $query->count()->execute();
    if($count > 9) {
      $load_more_show = true;
    }
    $nodes = entity_load_multiple('node', $nids);
    
    $node_count = 1;
    foreach($nodes as $node) {
      if($node_count < 10) {
        if($node->field_image[0]->target_id != NULL) {
          $node_url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$node->nid->value);
          $shortdesc = preg_replace('/\s+?(\S+)?$/', '', substr($node->body->value, 0, 300));      
          $image = file_create_url($node->field_image->entity->getFileUri());
        } else {
          $image = '';
        }  
        if(strlen($node->title->value) > 45) {
          $title = preg_replace('/\s+?(\S+)?$/', '', substr($node->title->value, 0, 40)). '...';
        } else {
          $title = $node->title->value;
        }
        $news_date = date('M d Y', strtotime($node->field_news_date->value));
        $output .= '<li rel="'.$node->nid->value.'"><div class="imgSec"><img src="'.$image.'" alt=""></div>
        <div class="contentSec"><div class="titleSec"><h4>'.$title.'</h4><p>Date: '.$news_date.'</p>
          </div><div class="discription"><p>'.$shortdesc.'</p></div></div> <div class="btnSec">
          <a class="button" href="'.$node_url.'">Read More</a><a class="shareBtn" href="javascript:;">Share</a><div class="social"></div></div></li>';
        $node_count++;
      }
    }
    
    $output .= '</div>';

    return array(
      '#title' => $this->t('Alumni News'),
      '#markup' => $this->t($output),
    );

  }
}

