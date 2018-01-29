<?php
namespace Drupal\custom_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Component\Annotation\Plugin;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Provides a 'Custom' Block
 *
 * @Block(
 *   id = "similar_jobs",
 *   admin_label = @Translation("Similar Jobs"),
 * )
 */

class CustomSimilarJobs extends BlockBase {
  /**
   * {@inheritdoc}
   */
    public function build() {
    
    /* You can put any PHP code here if required */
    $output = '';
    $query = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->condition('type', 'jobs')
        ->sort('nid', 'DESC')
        ->range(0,4);
    $nids = $query->execute();

    $nodes = entity_load_multiple('node', $nids);
    $node_count = 1;
    $output = '<div class="similarJob">
                <h3>Similar <strong>Jobs</strong></h3>
                <ul>';
    foreach($nodes as $node) {
        $node_url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$node->nid->value);
        $shortdesc = preg_replace('/\s+?(\S+)?$/', '', substr($node->body->value, 0, 150));      
           

        if(strlen($node->title->value) > 45) {
          $title = preg_replace('/\s+?(\S+)?$/', '', substr($node->title->value, 0, 40)). '...';
        } else {
          $title = $node->title->value;
        }
        
        $output .= '<li><a href="'.$node_url.'">'.$title.'</a></li>';
        $node_count++;
    }
    $output .= '</ul>';
    $output .= '</div>';

    return array(
      '#title' => $this->t('Similar Jobs'),
      '#markup' => $this->t($output),
    );

  }
}