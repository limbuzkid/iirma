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
 *   id = "chapters_list",
 *   admin_label = @Translation("Chapters List"),
 * )
 */

class CustomChaptersList extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    
    /* You can put any PHP code here if required */
    $output = '';
    $load_more_show = false;
    $query = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->condition('type', 'chapters')
        ->sort('title', 'ASC  ')
        ->range(0,4);
    $nids = $query->execute();
    
    $count = $query->count()->execute();
    
    $nodes = entity_load_multiple('node', $nids);
    $node_count = 1;
    $output = '<ul class="networkGroup chaptersLoadMoreUL">';
    foreach($nodes as $node) {
      if($node_count <= 4) {
        $node_url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$node->nid->value);
        $shortdesc = preg_replace('/\s+?(\S+)?$/', '', substr($node->body->value, 0, 150));      
        if($node->field_image->target_id != NULL) {
          $image = file_create_url($node->field_image->entity->getFileUri());  
        } else {
          $image = '';  
        }

        if(strlen($node->title->value) > 45) {
          $title = preg_replace('/\s+?(\S+)?$/', '', substr($node->title->value, 0, 40)). '...';
        } else {
          $title = $node->title->value;
        }
        
        $output .= '<li rel="'.$node->nid->value.'" class="netowrkSec">
                      <div class="imgSec">
                          <img src="'.$image.'" alt="">
                      </div>
                      <div class="content">
                          <h3>'.$title.'</h3>
                          <p>'.$shortdesc.'</p>
                          <div class="bttnSec">
                              <a class="button" href="'.$node_url.'">View</a>
                          </div>
                      </div>    
                    </li>';
        $node_count++;
      }
    }
    if($count > 3) {
      $output .= '</ul><a class="button chapterLoadMore" href="javascript:;" rel="4">Load more</a></div>';
    } else {
      $output .= '</ul></div>';
    }
    

    return array(
      '#title' => $this->t('Chapters'),
      '#markup' => $this->t($output),
    );

  }
}

