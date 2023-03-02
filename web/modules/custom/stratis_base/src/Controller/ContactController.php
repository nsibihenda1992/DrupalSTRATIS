<?php

namespace Drupal\stratis_base\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class ControllerController.
 */
class ContactController extends ControllerBase
{

  /**
   * formulaire.
   *
   */
    public function formulaire()
    {
      return [
        '#theme' => 'contact_us',
        '#content' => ''
      ];
    }
}