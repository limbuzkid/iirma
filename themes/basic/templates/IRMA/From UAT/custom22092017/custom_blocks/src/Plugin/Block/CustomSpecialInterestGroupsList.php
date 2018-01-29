<?php
  namespace Drupal\custom_blocks\Plugin\Block;
  
  use Drupal\Core\Block\BlockBase;
  use Drupal\Component\Annotation\Plugin;
  use Drupal\Core\Annotation\Translation;
  use Drupal\Core\Url;
  use Drupal\Core\Link;
  use Drupal\image\Entity\ImageStyle;

/**
 * Provides a 'Custom Events' Block
 *
 * @Block(
 *   id = "Special Interest Group List",
 *   admin_label = @Translation("Special Interest Group List"),
 * )
 */

class CustomSpecialInterestGroupsList extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    
    $current_uri = \Drupal::request()->getRequestUri();
    $temp = explode('/', $current_uri);
    $name = $temp[4];
    $logged_in = \Drupal::currentUser()->isAuthenticated();
    if($logged_in) {
      $output = '';
      $query = \Drupal::database()->select('taxonomy_term_field_data', 'td');
      $query->fields('td', ['tid', 'description__value']);
      $query->condition('td.name', $name);
      $query->range(0, 1);
      $result = $query->execute()->fetchAssoc();
      $tid = $result['tid'];
      $desc = $result['description__value'];
      
      $query = \Drupal::entityQuery('node')
              ->condition('status', 1)
              ->condition('type', 'featured_alumni')
              ->condition('field_interests.target_id', $tid)
              ->sort('nid', 'DESC');
              //->range(0,3);
      $nids = $query->execute();
      $nodes = entity_load_multiple('node', $nids);
      
      $output .= '<div class="managementLest groupDetailP"><div class="container"><ul>';
    
      foreach($nodes as $node) {
        $original_image = $node->field_image->entity->getFileUri();
        $style = ImageStyle::load('featured');  // Load the image style configuration entity.
        $image_url = $style->buildUrl($original_image);
        $output .= '<li><span><img src="'.$image_url.'" height="192" alt=""></span>';
        $output .= '<div class="information"><h5 class="name">'.$node->title->value.'</h5>';
        $output .= '<h6>'.$node->field_designation->value.' </h6><i class="clrBoth"></i>';
        $output .= '<div class="contactSec"><a class="email" href="mailto:abhay_kumat@gmail.com">abhay_kumat@gmail.com</a>';
        $output .= '<p>Batch: <strong>PRM-10 ('.$node->field_batch->value.')</strong></p>';
        $output .= '<a class="socialLink" href="javascript:;"></a></div></div></li>';
      }
      
      $output .= '</ul></div></div>';
    } else {
      $output .= '<div class="detailContent trigger">
          <div class="managementLest">
            <h2>You need to log in to view this page</h2>
          </div>
        </div>';
    }
    

    return array(
      '#title' => $this->t(ucwords($name)),
      '#markup' => $this->t($output),
    );

  }
}