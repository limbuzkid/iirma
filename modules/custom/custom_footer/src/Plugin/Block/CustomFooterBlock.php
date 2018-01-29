<?php
namespace Drupal\custom_footer\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Component\Annotation\Plugin;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Provides a 'Custom' Block
 *
 * @Block(
 *   id = "custom_footer",
 *   admin_label = @Translation("Custom Footer"),
 * )
 */

class CustomFooterBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    
    /* You can put any PHP code here if required */
    //print ('Hello World - PHP version');
    $footer_mnu = '';
    $first = true;
    $tree = \Drupal::menuTree()->load('footer', new \Drupal\Core\Menu\MenuTreeParameters());
    $count = 0;
    foreach ($tree as $item) {
      $title = $item->link->getTitle();
      $url_obj = $item->link->getUrlObject();
      $url_string = $url_obj->toString();
      if($item->link->isEnabled()) {
        if($item->hasChildren) {
          if($first) {
            $footer_mnu .= '<article class="footer-left"><h5>'.$title.'</h5><ul>';
            $first = false;
          } else {
            $footer_mnu .= '<article class="center"><h5>'.$title.'</h5><ul>';
          }
          foreach($item->subtree as $child) {
            $child_title = $child->link->getTitle();
            $child_link  = $child->link->getUrlObject()->toString();
            if($child->link->isEnabled()) {
              $footer_mnu .= '<li rel="'.$count.'"><a href="'.$child_link.'">'.$child_title.'</a></li>';
            }
            $count++;
          }
          $footer_mnu .= '</ul></article>';
        } else {
          $footer_mnu .= '<li rel="'.$count.'"><a href="'.$url_string.'">'.$title.'</a></li>';
          $count++;
        }
      }
    }
    $footer_mnu .= '<article class="footer-right">
                      <h5>Get in touch</h5>
                      <ul>
                        <li class="phone"><a href="tel:02692260391">02692-260391</a>, <a href="tel:02692260181">260181</a></li>
                        <li class="fax"><a href="tel:02692260188">02692-260188</a></li>
                        <li class="email"><a href="mailto:contact@ota.com">iaaec@irma.ac.in</a></li>
                        <li class="addres">Institute of Rural Management, <br/> Post Box No. 60, Anand 388001, Gujarat, India</li>
                      </ul>
                    </article>
                    <article class="socialMedia">
                      <ul>
                        <li><a href="https://www.facebook.com/alumni.irma/" target="_blank"><img src="/themes/basic/images/facbook.jpg" alt="facbook"></a></li>
                        <li><a href="https://www.linkedin.com/organization/15098227/" target="_blank"><img src="/themes/basic/images/in.jpg" alt="In"></a></li>
                        <li><a href="https://twitter.com/irma_alumni" target="_blank"><img src="/themes/basic/images/twiter.jpg" alt="twiter"></a></li>
                        <li><a href="#" target="_blank"><img src="/themes/basic/images/youtube.jpg" alt="youtube"></a></li>
                      </ul>
                    </article>
                    <article class="copytext">
                      <ul>
                        <li><p>&copy; '.date("Y").' IRMA</p></li>
                        <li><a href="/privacy-policy">Privacy policy</a></li>
                        <li class="last"><a href="/disclaimer">Disclaimer</a></li>
                      </ul>
                    </article>';
    


    return array(
      '#markup' => $this->t($footer_mnu),
      /* If you want to bypass Drupal 8's default caching for this block then simply add this, otherwise remove the next three line */
      '#cache' => array(
          'max-age' => 0,
      ),
    );
  }
}