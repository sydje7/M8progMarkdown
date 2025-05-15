<?php get_header(); ?>

<article>
  <h1><?php the_title(); ?></h1>
  <?php the_post_thumbnail('large'); ?>
  <div><?php the_content(); ?></div>

  <p><strong>Ingrediënten:</strong>
    <?php the_terms(get_the_ID(), 'ingredient', '', ', '); ?>
  </p>
</article>

<?php get_footer(); ?>
