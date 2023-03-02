<?php

namespace Drupal\stratis_base\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Side Block' Block.
 *
 * @Block(
 *   id = "side_block",
 *   admin_label = @Translation("Side Block"),
 * )
 */
class SideBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];

    // RECENT POSTS
    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'post');
    $query->condition('status', 1);
    $query->range(0, 4);
    $query->sort('created' , 'DESC');
    $nids = $query->execute();
    $node_storage = \Drupal::entityTypeManager()->getStorage('node');
    $nodes_posts = $node_storage->loadMultiple($nids);

    $posts = [];
    foreach ($nodes_posts as $key => $post) {
        $posts[$post->getTitle()] = $post->toUrl()->toString();
    }

    //RECENT COMMENTS
    $query2 = \Drupal::entityQuery('comment');
    $query2->range(0, 3);
    $query2->sort('created' , 'DESC');
    $query->condition('status', 1);
    $cids = $query2->execute();
    $comment_storage = \Drupal::entityTypeManager()->getStorage('comment');
    $comments_posts = $comment_storage->loadMultiple($cids);

    $comments = [];
    foreach ($comments_posts as $key => $comment) {
        $element = [];
        $element['commented_post_title'] = $comment->getCommentedEntity()->getTitle();
        $element['commented_post_link']= $comment->getCommentedEntity()->toUrl()->toString();
        $element['auther_name'] = $comment->getAuthorName();
        $element['auther_link'] = $comment->getOwner()->toUrl()->toString();

        $comments[] = $element;
    }

    //CATEGORIES
    $categories_query = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'categories']);
    $categories = [];
    foreach ($categories_query as $key => $category) {
      $categories[$category->getName()] = $category->toUrl()->toString();
    }

    //ARCHIVES
    $datetime = strtotime(date('Y-m-d'));
    $months = [];
    for ($i=1; $i < 5 ; $i++) {
      $date_start = strtotime('first day of -'. $i .'month', $datetime);
      // $a = date("Y-m-d H:i:s" ,$date_start); for test
      $string_date = date("F Y",$date_start);
      $link_date = date("F-Y",$date_start);
      $months[] = [
        'link_date' => $link_date,
        'string_date' => $string_date,
        'date_start' => $date_start,
      ];
    }

    $build['#content']['posts'] = $posts;
    $build['#content']['comments'] = $comments;
    $build['#content']['categories'] = $categories;
    $build['#content']['months'] = $months;

    $build['#theme'] = 'side_block';

    return $build;
  }

}