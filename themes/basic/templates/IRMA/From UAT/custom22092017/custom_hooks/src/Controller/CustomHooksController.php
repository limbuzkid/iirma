<?php

  namespace Drupal\custom_hooks\Controller;
  
  use Drupal\Core\Controller\ControllerBase;
  
  use Drupal\Core\Annotation\Translation;
  use Drupal\Core\Url;
  use Drupal\Core\Link;
  use Drupal\image\Entity\ImageStyle;
  
  
  class CustomHooksController extends ControllerBase {

    public function custom_hooks_form_alter(&$form, \Drupal\Core\Form\FormStateInterface $form_state, $form_id) {
      switch($form_id) {
        case 'user_register_form': user_register_frm();
                                   exit();
      }

    }
    
    public function user_register_frm() {
      
    }
}