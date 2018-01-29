<?php
/**
 * @file
 * Contains \Drupal\demo\Controller\DemoController.
 */

namespace Drupal\demo\Controller;

/**
 * DemoController.
 */
class DemoController {
  /**
   * Generates an example page.
   */
  public function demo() {
    return array(
      '#markup' => t('Hello World!'),
    );
  }      
}