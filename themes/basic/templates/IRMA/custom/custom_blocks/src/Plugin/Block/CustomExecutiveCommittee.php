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
 *   id = "Executive Committee",
 *   admin_label = @Translation("Executive Committee"),
 * )
 */

class CustomExecutiveCommittee extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    
    /* You can put any PHP code here if required */
    //print ('Hello World - PHP version');
    
    $output = '<ul class="alList">';
    
    $temp = array();
    $sql = 'SELECT b.entity_id, b.field_batch_value
            FROM node__field_batch b
            LEFT JOIN node__field_executive_member e ON e.entity_id = b.entity_id
            WHERE e.field_executive_member_value = 1
            ORDER BY CAST(SUBSTR(b.field_batch_value, 5) AS SIGNED)
            LIMIT 0,10';
    $db = \Drupal::database();
    $rows = $db->query($sql);
    $rows->allowRowCount = TRUE;
    foreach($rows as $row) {
      //echo $row->entity_id . ' ' . $row->field_batch_value . '<br>';
      array_push($temp, $row->entity_id);
    }
    
    $nodes = entity_load_multiple('node', $temp);
    $count = 1;
    foreach($nodes as $node) {
      if($count < 10) {
        if($node->field_image->target_id != NULL) {
          $original_image = $node->field_image->entity->getFileUri();
          $style = ImageStyle::load('featured');  // Load the image style configuration entity.
          $url = $style->buildUrl($original_image);
        } else {
          $url = '';
        }
        $shortdesc = preg_replace('/\s+?(\S+)?$/', '', substr($node->body->value, 0, 170)); 
        $output .= '<li rel="'.$node->nid->value.'">
                    <span><img src="'.$url.'" height="183" alt=""></span>
                    <div class="information clickLightbox">
                    <h5 class="name"><a class="" href="javascript:;">'.$node->title->value.'</a></h5>
                    <h6>'.$node->field_designation->value.'</h6><p>'.$node->field_company_name->value.'</p>
                    <span>Batch: '.$node->field_batch->value.' <a href="javascript:;"><img src="/themes/basic/images/linked-icon.png" alt=""></a></span>
                    <div class="detailSec"><summary> '.$shortdesc.'...</summary></div></div></li>';
      }
      $count++;
    }
    //echo $output;
    //exit;
    
    if($rows->rowCount() > 9) {
      $output .= '</ul><a class="button loadMoreBtn" href="javascript:;" id="featAl" rel="'.$count.'">Load more</a>';
    } else {
      $output .= '</ul>';
    }

    return array(
      '#markup' => $this->t($output),
      /* If you want to bypass Drupal 8's default caching for this block then simply add this, otherwise remove the next three line */
      '#cache' => array(
          'max-age' => 0,
      ),
    );
  }
}