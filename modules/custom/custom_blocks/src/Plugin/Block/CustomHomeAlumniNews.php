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
 *   id = "Home Alumni News",
 *   admin_label = @Translation("Home Alumni News"),
 * )
 */

class CustomHomeAlumniNews extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    
    /* You can put any PHP code here if required */
    //print ('Hello World - PHP version');
    
    $output = '';
    $query = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->condition('type', 'alumni_news')
        ->sort('field_news_date', 'DESC')
        ->range(0,3);
    $nids = $query->execute();
    $nodes = entity_load_multiple('node', $nids);
    
    foreach($nodes as $node) {
      $node_url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$node->nid->value);
      $title = preg_replace('/\s+?(\S+)?$/', '', substr($node->title->value, 0, 30));
      $shortdesc = preg_replace('/\s+?(\S+)?$/', '', substr($node->body->value, 0, 110));
      if($node->field_image->target_id != NULL) {
        $original_image = $node->field_image->entity->getFileUri();
        $style = ImageStyle::load('thumbnail');  // Load the image style configuration entity.
        $uri = $style->buildUri($original_image);
        $url = $style->buildUrl($original_image);
      } else {
        $url = '';
      }
      
      //$image = file_create_url($node->field_image->entity->getFileUri());      
      $output .= '<article><div class="image"><img src="'.$url.'" alt="news"></div>';
      $output .= '<div class="right"><label>'.$title.'</label>';
      if($shortdesc) {
        $output .= $shortdesc .'<br/>';
      }
      $output .= '<a href="'.$node_url.'">Read more.</a>';
      $output .= '</div></article>';
    }

    return array(
      '#title' => $this->t('Featured Alumni'),
      '#markup' => $this->t($output),
    );

  }
}