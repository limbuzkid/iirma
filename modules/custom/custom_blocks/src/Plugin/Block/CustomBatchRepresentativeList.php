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
   *   id = "batch_representative_list",
   *   admin_label = @Translation("Batch Representative List"),
   * )
   */

  class CustomBatchRepresentativeList extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {
      /* You can put any PHP code here if required */
      
      $temp = array();
      $sql = "SELECT nid, title FROM node_field_data WHERE type='batch_representative' ORDER BY CAST(SUBSTR(`title`, 5) AS SIGNED) LIMIT 0,10";
      $db = \Drupal::database();
      $rows = $db->query($sql);
      $rows->allowRowCount = TRUE;
      foreach($rows as $row) {
        //echo $row->entity_id . ' ' . $row->field_batch_value . '<br>';
        array_push($temp, $row->nid);
      }
      $output = '';
     
      $count = $rows->rowCount();
      
      $nodes = entity_load_multiple('node', $temp);
      $node_count = 1;
      $output = '<ul class="networkGroup batchLoadMoreUL">';
      foreach($nodes as $node) {
        if($node_count <= 4) {
          $node_url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$node->nid->value);
          $shortdesc = preg_replace('/\s+?(\S+)?$/', '', substr($node->body->value, 0, 150));      
          if($node->field_image->target_id != NULL) {
            $image = file_create_url($node->field_image->entity->getFileUri());
          } else {
            $image = '';
          }
  
          if(strlen($node->title->value) > 45) {
            $title = preg_replace('/\s+?(\S+)?$/', '', substr($node->title->value, 0, 40)). '...';
          } else {
            $title = $node->title->value;
          }
          $output .= '<li rel="'.$node->nid->value.'" class="netowrkSec"><div class="imgSec"><img src="'.$image.'" alt=""></div>
                      <div class="content"><h3>'.$title.'</h3><p>'.$shortdesc.'</p>
                      <div class="bttnSec"><a class="button" href="'.$node_url.'">View</a></div></div></li>';
          $node_count++;
        }
      }
      $output .= '</ul>';
      if($count > 4) {
        $output .= '<a class="button loadMoreBr" href="javascript:;" rel="4">Load more</a>';
      }
      $output .= '</div>';
  
      return array(
        '#title' => $this->t('Batch Representatives'),
        '#markup' => $this->t($output),
      );
  
    }
  }

