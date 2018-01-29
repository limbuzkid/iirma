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
 *   id = "Special Interest Groups",
 *   admin_label = @Translation("Special Interest Groups"),
 * )
 */

class CustomSpecialInterestGroups extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $output = '';
    $query = \Drupal::entityQuery('taxonomy_term');
    $query->condition('vid', "special_interest");
    $tids = $query->execute();
    $terms = \Drupal\taxonomy\Entity\Term::loadMultiple($tids);
    $host = \Drupal::request()->getHost();
    $host = "http://".$host;
    foreach($terms as $term) {
      $icon = file_create_url($term->field_icon->entity->getFileUri());
      $term_url = \Drupal::service('path.alias_manager')->getAliasByPath('/taxonomy/term/'.$term->tid->value);
      $desc = preg_replace('/\s+?(\S+)?$/', '', substr($term->description->value, 0, 75)); 
      //echo $term_url . '\n';
      //print_r($term);
      $output .= '<div class="sec"><div class="imgSec"><img src="'.$icon.'" alt=""></div>
                    <div class="content"><h3>'.$term->name->value.'</h3>
                        <p>'.$desc.' ...</p>
                        <div class="bttnSec">
                            <a class="button" href="'.$term_url.'">View</a>
                            <a class="button" href="mailto:?subject=Invitation&body='.$host.$term_url.'">Invite</a>
                        </div></div></div>';
    }
    return array(
      '#title' => $this->t('Special Interest Groups'),
      '#markup' => $this->t($output),
    );

  }
}