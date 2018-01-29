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
 *   id = "Home Featured Alumni",
 *   admin_label = @Translation("Home Featured Alumni"),
 * )
 */

class CustomHomeFeaturedAlumni extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    
    /* You can put any PHP code here if required */
    //print ('Hello World - PHP version');
    
    $output = '<aside class="feature"><h3>Featured <strong>Alumni</strong></h3><div class="listinfo owl-carousel owl-theme">';
        
    $temp = array();
    $sql = 'SELECT fb.entity_id, SUBSTR(`field_batch_value`, 5, 3)
            FROM `node__field_batch` fb
            LEFT JOIN node__field_show_on_home_page hp ON hp.entity_id = fb.entity_id
            WHERE hp.field_show_on_home_page_value = 1
            ORDER BY CAST(SUBSTR(`field_batch_value`, 5,3) AS SIGNED)
            LIMIT 6';
    $db = \Drupal::database();
    $rows = $db->query($sql);
    $rows->allowRowCount = TRUE;
    foreach($rows as $row) {
      //echo $row->entity_id . ' ' . $row->field_batch_value . '<br>';
      array_push($temp, $row->entity_id);
    }
    /*$query = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->condition('type', 'featured_alumni')
        ->condition('field_show_on_home_page.value', 1)
        ->sort('changed', 'ASC')
        ->range(0,6);
    $nids = $query->execute();*/
    $nodes = entity_load_multiple('node', $temp);
    //echo '<pre>';
    //foreach($nodes as $node) {
    //  print_r($node);
    //}
    //echo '</pre>';exit;
    

    
      
    
    foreach($nodes as $node) {
      //$image = file_create_url($node->field_image->entity->getFileUri());
      $shortdesc = preg_replace('/\s+?(\S+)?$/', '', substr($node->body->value, 0, 200)). '...'; 
      if($node->field_image->target_id != NULL) {
        $original_image = $node->field_image->entity->getFileUri();
        $style = ImageStyle::load('featured');  // Load the image style configuration entity.
        $url = $style->buildUrl($original_image);
      } else {
        $url = '';
      }
      $output .= '<div class="list"><span><img src="'.$url.'" width="283" height="183" alt=""></span>';
      $output .= '<div class="information"><h5 class="name">'.$node->title->value.'</h5><h6>'.$node->field_designation->value.'</h6>';
      $output .= '<p>'.$node->field_company_name->value.'</p><summary>'.$shortdesc.'</summary></div></div>';
    }
    
    $output .= '</div><a class="button" href="/alumni-network/featured-alumni">View all</a></aside>';
    
    //echo $output;
    //exit;

    return array(
      '#title' => $this->t('Featured Alumni'),
      '#markup' => $this->t($output),
    );
  
    /*return array(
      '#theme' => 'block--homefeaturedalumni',
      '#title' => $this->t('Featured Alumni'),
      '#doubles' => $data,
    );*/
  }
}