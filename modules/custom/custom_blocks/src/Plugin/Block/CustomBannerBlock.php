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
    
    $current_url = Url::fromRoute('<current>');
    $path = $current_url->toString();
    $arg = explode('/',$path);
    if(isset($arg[4])) {
      $page = str_replace('-', ' ', $arg[3]);
      $title = ucwords(str_replace('-', ' ', $arg[4]));
    } elseif(isset($arg[3])) {
      if($arg[2] == 'alumni-news') {
        $page = str_replace('-', ' ', $arg[2]);
        $title = ucwords(str_replace('-', ' ', $arg[3]));
      } else if($arg[2] == 'give-to-iaa' && $arg[3] == 'apply') {
        $page = str_replace('-', ' ', $arg[2]);
        $title = 'Giving Form';
      } else {
        $page = str_replace('-', ' ', $arg[3]);
        $title = '';
      }
    } elseif(isset($arg[2])) {
      $page = str_replace('-', ' ', $arg[2]);
      $title = '';
    } else {
      $page = str_replace('-', ' ', $arg[1]);
      $title = '';
    }
    
    //echo 'Page '. $page;

    $banner_html = '';
    $query = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->condition('type', 'page_banner')
        ->condition('field_page.value', $page);
    $nids = $query->execute();

    $nodes = entity_load_multiple('node', $nids);
    foreach($nodes as $node) {
      if($title == '') {
        $title = $node->title->value;
      }
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