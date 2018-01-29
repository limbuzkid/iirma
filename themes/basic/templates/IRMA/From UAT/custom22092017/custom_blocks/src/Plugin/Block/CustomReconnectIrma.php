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
 *   id = "home_reconnec_tirma",
 *   admin_label = @Translation("Home Reconnect IRMA"),
 * )
 */

class CustomReconnectIrma extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    
    /* You can put any PHP code here if required */
    //print ('Hello World - PHP version');
    $query = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->condition('type', 'page_banner')
        ->condition('field_page.value', 'Home Reconnect with Irma');
    $nids = $query->execute();
    foreach($nids as $nid) {
      $controller = \Drupal::entityManager()->getStorage('node');
      $node = $controller->load($nid);
      $image = file_create_url($node->field_image->entity->getFileUri());
    }
       
    $html = '<aside><img src="'.$image.'" alt="Reconnect"></aside><article class="reconnect-right"><h2>Connect with <strong>IRMA</strong></h2>';
    $count = 1;
    $first = true;
    $tree = \Drupal::menuTree()->load('reconnect-with-irma', new \Drupal\Core\Menu\MenuTreeParameters());
    foreach($tree as $item) {
      $title = $item->link->getTitle();
      $url_obj = $item->link->getUrlObject();
      $url_string = $url_obj->toString();
      if($first) {
        $html .= '<article class="left-box">';
        //$first = false;
      }
      if($item->hasChildren) {
        $count++;
        $class = str_replace(' ', '-', strtolower($title));
        $html .= '<h4 class="'.$class.'">'.$title.'</h4><ul>';
        foreach($item->subtree as $child) {
          $child_title = $child->link->getTitle();
          $child_url_obj = $child->link->getUrlObject();
          $child_url = $child_url_obj->toString();
          $html .= '<li><a href="'.$child_url.'">'.$child_title.'</a></li>';
        }
        $html .= '</ul>';
        if($first) {
          $html .= '<a class="button view" href="/register">register</a>';
          $html .= '</article><article class="right-box">';
          $first = false;
        } 
      } else {
        //if($count < 3) {
        //  $html .= '<li><a href="'.$url_string.'">'.$title.'</a></li>';
       // } else {
          $class = str_replace(' ', '-', strtolower($title));
          $html .= '<h4 class="'.$class.'"><a href="'.$url_string.'">'.$title.'</a></h4>';
       // }
        
      }
    }
    $html .= '</article></article>';
    
    return array(
      '#markup' => $this->t($html),
      /* If you want to bypass Drupal 8's default caching for this block then simply add this, otherwise remove the next three line */
      '#cache' => array(
          'max-age' => 0,
      ),
    );
  }
}