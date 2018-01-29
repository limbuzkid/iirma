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
 *   id = "Home Events",
 *   admin_label = @Translation("Home Events"),
 * )
 */

class CustomHomeEvents extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $cur_date   = \Drupal::time()->getRequestTime();
    $cur_date   = date('Y-m-d', $cur_date);
    $output = '<div class="eventscroll owl-carousel owl-theme">';
    $query = \Drupal::entityQuery('node')
              ->condition('status', 1)
              ->condition('type', 'events')
              ->condition('field_event_date.value', $cur_date, '>=')
              ->sort('nid', 'DESC')
              ->range(0,6);
    $nids   = $query->execute();
    $cnt    = $query->count()->execute();
    $nodes  = entity_load_multiple('node', $nids);
    $count  = 1;
    $first  = true;
    if($cnt > 0) {
      foreach($nodes as $node) {
        $shortdesc = preg_replace('/\s+?(\S+)?$/', '', substr($node->body->value, 0, 110));
        if($first) {
          $output .= '<div class="event-contain">';
          $first = false;
        }
        $mod = $count % 2;
        if($mod == 0) {
          $output .= '<article class="event-out last">';
        } else {
          $output .= '<article class="event-out">';
        }
        $output .= '<span>'.$node->field_event_name->value.'</span><p>'.$shortdesc.'</p>';
        $output .= '<div class="eventdetails"><aside><label class="date">'.date('d M', strtotime($node->field_event_date->value)).'</label>';
        $output .= '<label class="location">Delhi</label></aside>';
        $output .= '<aside><a class="button" href="/alumni-network/events/register/'.$node->nid->value.'">REGISTER</a></aside></div></article>';
        
        if($mod == 0) {
          if($count < 6) {
            $output .= '</div><div class="event-contain">';
          } else {
            $output .= '</div>';
          }
        }
        $count++;
      }$output .= '</div>';
    } else {
      $output = '<div class="eventList noEvents"><p>No Upcoming events at the moment.</p></div>';
    }

    return array(
      '#title' => $this->t('Home Events'),
      '#markup' => $this->t($output),
    );

  }
}