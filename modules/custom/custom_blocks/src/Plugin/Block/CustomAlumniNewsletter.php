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
   *   id = "alumni_newsletter",
   *   admin_label = @Translation("Alumni Newsletter"),
   * )
   */

  class CustomAlumniNewsletter extends BlockBase {
    /**
     * {@inheritdoc}
     */
    public function build() {
      $load_more = false;
      $output    = '';
      $query = \Drupal::entityQuery('node')
              ->condition('status', 1)
              ->condition('type', 'alumni_newsletter')
              ->condition('field_year', '2017')
              ->sort('nid', 'ASC');
      $nids   = $query->execute();
      $cnt = $query->count()->execute();
      $nodes  = entity_load_multiple('node', $nids);
      foreach($nodes as $node) {
        if($node->field_image->target_id != NULL) {
          $image = file_create_url($node->field_image->entity->getFileUri());
        } else {
          $image = '';
        }
        if($node->field_upload_bylaws->target_id != NULL) {
          $pdf   = file_create_url($node->field_upload_bylaws->entity->getFileUri());
        } else {
          $pdf = '';
        }
        $output .= '<li rel="'.$node->nid->value.'"><div class="imgSec"><img src="'.$image.'" alt=""></div>';
        $output .= '<div class="contentSec"><div class="titleSec"><h4>'.$node->title->value.'</h4><p>'.$node->body->value.'</p></div></div>';
        $output .= '<div class="btnSec"><a class="button" href="'.$pdf.'" target="_blank">Download</a><a class="shareBtn" href="javascript:;">Share</a><div class="social"></div></div></li>';

      }
      return array(
        '#title'  => $this->t('Newsletter'),
        '#markup' => $this->t($output),
        '#cache'  => array('max-age' => 0 ),
      );
    }
  }

