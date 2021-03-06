<?php

  /**
   * @file
   * Definition of Drupal\views\Tests\Plugin\DisplayExtenderTest.
   */

namespace Drupal\views\Tests\Plugin;

use Drupal\views\Tests\Plugin\PluginTestBase;
use Drupal\views\Views;

/**
 * Tests the display extender plugins.
 *
 * @group views
 * @see \Drupal\views_test_data\Plugin\views\display_extender\DisplayExtenderTest
 */
class DisplayExtenderTest extends PluginTestBase {

  /**
   * Views used by this test.
   *
   * @var array
   */
  public static $testViews = array('test_view');

  protected function setUp() {
    parent::setUp();

    $this->enableViewsTestModule();
  }

  /**
   * Test display extenders.
   */
  public function testDisplayExtenders() {
    \Drupal::config('views.settings')->set('display_extenders', array('display_extender_test'))->save();
    $this->assertEqual(count(Views::getEnabledDisplayExtenders()), 1, 'Make sure that there is only one enabled display extender.');

    $view = Views::getView('test_view');
    $view->initDisplay();

    $this->assertEqual(count($view->display_handler->getExtenders()), 1, 'Make sure that only one extender is initialized.');

    $display_extender = $view->display_handler->getExtenders()['display_extender_test'];
    $this->assertTrue($display_extender instanceof \Drupal\views_test_data\Plugin\views\display_extender\DisplayExtenderTest, 'Make sure the right class got initialized.');

    $view->preExecute();
    $this->assertTrue($display_extender->testState['preExecute'], 'Make sure the display extender was able to react on preExecute.');
    $view->execute();
    $this->assertTrue($display_extender->testState['query'], 'Make sure the display extender was able to react on query.');
  }

}
