<?php

namespace Drupal\stratis_base\Controller;

use Drupal\Core\Controller\ControllerBase;


/**
 * Class AboutPageController.
 */
class AboutPageController extends ControllerBase
{

  /**
   * About.
   *
   */
    public function content()
    {
        $build = [];
        $build['#theme'] = ['about_page'];
        $build['#content'] = [];

        $storage = \Drupal::entityTypeManager()->getStorage('config_pages');
        $about_page = $storage->load('about_page');

        $build['#content']['field_title'] = $about_page->get('field_title')->value;
        if($about_page){
          $content = $about_page->get('field_content')->referencedEntities();
          if($content){

            $view_builder = \Drupal::service('entity_type.manager')->getViewBuilder('paragraph');
            $build['#content']['contents'] = $view_builder->viewMultiple($content);
          }
        }
        return $build;

    }
}