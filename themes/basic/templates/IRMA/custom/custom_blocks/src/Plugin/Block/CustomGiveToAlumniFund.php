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
 *   id = "give_to_alumni_fund",
 *   admin_label = @Translation("Give to Alumni Fund"),
 * )
 */

class CustomGiveToAlumniFund extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    
    /* You can put any PHP code here if required */
    //print ('Hello World - PHP version');

    $query = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->condition('type', 'give_to_iaa')
        ->sort('nid', 'DESC');
    $nids = $query->execute();
    $nodes = entity_load_multiple('node', $nids);
    
    $node_count = 1;
    $output = '<div class="resp-tabs-list">';
    $output .= '<ul id="sel-option">';
    foreach ($nodes as $node) {
      $output .= '<li>'.$node->title->value.'</li>';
    }
    $output .= '</ul>';
    foreach($nodes as $node) {

        $node_url = \Drupal::service('path.alias_manager')->getAliasByPath('/node/'.$node->nid->value);
        $shortdesc = preg_replace('/\s+?(\S+)?$/', '', substr($node->body->value, 0, 300));
        if(isset($node->field_image->target_id) && !empty($node->field_image->target_id)){
          $image_file = \Drupal\file\Entity\File::load($node->field_image->target_id);
          $uri = $image_file->uri->value;    
          $image = file_create_url($uri);      
        } else {
          $image = "";
        }

        $output .= '<div class="text_box">
                      <div class="detailContent">
                        <div class="container"> ';
        if($node_count==1){
          $output .= '<div class="left-col">
                        <h3>'.$node->title->value.'</h3>
                        <p>'.$node->body->value.'</p>
                        <a class="button" href="'.$node->field_linkedin_url->value.'">'.$node->field_link_text->value.'</a>
                      </div>
                      <div class="right-col">
                        <img src="'.$image.'" alt="'.$node->title->value.'">
                      </div>';
        } elseif ($node_count==2) {
          $output .= '<div class="right-col">
                        <img src="'.$image.'" alt="'.$node->title->value.'">
                      </div>
                      <div class="left-col margn_lft">
                        <h3>'.$node->title->value.'</h3>
                        <p>'.$node->body->value.'</p>
                      </div>';
        } elseif ($node_count==3) {
          $output .= '<h3>'.$node->title->value.'</h3>
                      <p>'.$node->body->value.'</p>
                      <a class="button" href="'.$node->field_linkedin_url->value.'">'.$node->field_link_text->value.'</a>';
        }
        $output .= '  </div>
                    </div>
                  </div>';
        $node_count++;
    }
    
    $output .= '</div>';

    return array(
      '#title' => $this->t('Give To Alumni Fund'),
      '#markup' => $this->t($output),
    );

  }
}

