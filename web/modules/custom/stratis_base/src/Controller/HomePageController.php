<?php

namespace Drupal\stratis_base\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Entity\EntityViewBuilder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;



/**
 * Class HomePageController.
 */
class HomePageController extends ControllerBase
{

  /**
   * Home.
   *
   */
  public function home()
  {
      $build = [];
      $build['#theme'] = ['home_page'];
      $build['#content'] = [];

      $query = \Drupal::entityQuery('node');
      $query->sort('created' , 'DESC');
      $query->condition('type', 'post');
      $nids = $query->execute();

      $node_storage = \Drupal::entityTypeManager()->getStorage('node');
      $nodes_posts = $node_storage->loadMultiple($nids);
      if($nodes_posts) {
        $view_builder = \Drupal::service('entity_type.manager')->getViewBuilder('node');
        $build['#content']['posts'] = $view_builder->viewMultiple($nodes_posts, 'teaser');
        $build['#cache']['max-age'] = 0;
      }
      return $build;

  }
  public function archive($date){

    $values = \Drupal::request()->query->all();
    if ($values) {
      $date_start = $values['date_start'];
      $date_end = strtotime('next month', $date_start);


      $build = [];
      $build['#theme'] = ['archive'];
      $build['#content'] = [];
      $build['#content']['page_title'] = str_replace('-', ' ', $date);


      $query = \Drupal::entityQuery('node');
      $query->sort('created' , 'DESC');
      $query->condition('created', [$date_start, $date_end], 'BETWEEN');
      $query->condition('type', 'post');
      $nids = $query->execute();

      $node_storage = \Drupal::entityTypeManager()->getStorage('node');
      $nodes_posts = $node_storage->loadMultiple($nids);
      if($nodes_posts) {
        $view_builder = \Drupal::service('entity_type.manager')->getViewBuilder('node');
        $build['#content']['posts'] = $view_builder->viewMultiple($nodes_posts, 'teaser');
        $build['#cache']['max-age'] = 0;
      }
      return $build;
    }else {
      $url = Url::fromRoute('stratis_base.home_page')->toString();
      return new RedirectResponse($url);
    }

  }
}