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
 *   id = "news_pager",
 *   admin_label = @Translation("News Pager"),
 * )
 */

class CustomNewsPager extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    
    /* You can put any PHP code here if required */
    //print ('Hello World - PHP version');
    $html = '';
    $next = false;
    $prev = false;
    $node_id = \Drupal::routeMatch()->getRawParameter('node');
    $next_query = \Drupal::entityQuery('node')
                ->condition('status', 1)
                ->condition('type', 'alumni_news')
                ->condition('nid', $node_id, '<')
                ->sort('nid', 'DESC')
                ->range(0,1);
    $next_id = $next_query->execute();
    if($next_id) {
      $n_nid = current($next_id);
      //$next_url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$n_nid);
      $next_url = Url::fromRoute('entity.node.canonical', ['node' => $n_nid])->toString();
      $next = true;
    }
    $prev_query = \Drupal::entityQuery('node')
                ->condition('status', 1)
                ->condition('type', 'alumni_news')
                ->condition('nid', $node_id, '>')
                //->sort('nid', 'DESC')
                ->range(0,1);
    $prev_id = $prev_query->execute();
    if($prev_id) {
      $p_nid = current($prev_id);
      //$prev_url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$p_nid);
      $prev_url = Url::fromRoute('entity.node.canonical', ['node' => $p_nid])->toString();
      $prev = true;
    }
    
    if($next && $prev) {
      $html .= '<a class="button prev '.$p_nid. '" href="'.$prev_url.'">Previous</a><a class="button fr '.$n_nid.'" href="'.$next_url.'">Next</a>'; 
    } elseif(!$next) {
      $html .= '<a class="button prev '.$p_nid.'" href="'.$prev_url.'">Previous</a>';
    } elseif(!$prev) {
      $html .= '<a class="button fr '.$n_nid.'" href="'.$next_url.'">Next</a>';
    }
    

    //echo $node_url;

    //echo $node_id;
    
    return array(
      '#markup' => $this->t($html),
      /* If you want to bypass Drupal 8's default caching for this block then simply add this, otherwise remove the next three line */
      '#cache' => array(
          'max-age' => 0,
      ),
    );
  }
}