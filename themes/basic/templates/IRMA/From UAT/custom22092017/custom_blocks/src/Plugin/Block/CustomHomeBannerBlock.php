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
 *   id = "home_banner",
 *   admin_label = @Translation("Home Banner"),
 * )
 */

class CustomHomeBannerBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    
    /* You can put any PHP code here if required */
    //print ('Hello World - PHP version');
    $output = '<div class="banner-carousal owl-carousel  owl-theme">';
    
    $query = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->condition('type', 'home_banner');
    $nids = $query->execute();

    $nodes = entity_load_multiple('node', $nids);
    foreach($nodes as $node) {
      $body = $node->body->value;
      $image = file_create_url($node->field_image->entity->getFileUri());
      $output .= '<div class="banner-containt"><div class="banner-image"><img src="'.$image.'" alt="banner"></div>';
      $output .= '<div class="banner-details"><div class="bannerleft">'.$body.'<!--<a class="button" href="javascript:;">Login</a>--></div></div></div>';
    }
    $output .= '</div>';


    return array(
      '#markup' => $this->t($output),
      /* If you want to bypass Drupal 8's default caching for this block then simply add this, otherwise remove the next three line */
      '#cache' => array(
          'max-age' => 0,
      ),
    );
  }
}