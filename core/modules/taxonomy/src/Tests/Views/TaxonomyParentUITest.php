<?php

namespace Drupal\taxonomy\Tests\Views;

use Drupal\views\Tests\ViewTestData;
use Drupal\views_ui\Tests\UITestBase;

/**
 * Tests views taxonomy parent plugin UI.
 *
 * @group taxonomy
 * @see Drupal\taxonomy\Plugin\views\access\Role
 */
class TaxonomyParentUITest extends UITestBase {

  /**
   * Views used by this test.
   *
   * @var array
   */
  public static $testViews = ['test_taxonomy_parent'];

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['taxonomy', 'taxonomy_test_views'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    ViewTestData::createTestViews(get_class($this), ['taxonomy_test_views']);
  }

  /**
   * Tests the taxonomy parent plugin UI.
   */
  public function testTaxonomyParentUI() {
    $this->drupalGet('admin/structure/views/nojs/handler/test_taxonomy_parent/default/relationship/parent');
    $this->assertNoText('The handler for this item is broken or missing.');
  }

}