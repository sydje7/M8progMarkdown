<?php get_header(); ?>
<div class="filters">
    <?php
    $ingredients = get_terms('ingredient');
    foreach ($ingredients as $ingredient) {
        echo '<button class="filter-btn" data-ingredient="' . $ingredient->slug . '">' . $ingredient->name . '</button>';
    }
    ?>
</div>
<div id="recepten-grid">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="recept-card" data-ingredients="<?php echo implode(' ', wp_get_post_terms(get_the_ID(), 'ingredient', array('fields' => 'slugs'))); ?>">
            <h2><?php the_title(); ?></h2>
            <?php the_post_thumbnail('medium'); ?>
            <?php the_excerpt(); ?>
        </div>
    <?php endwhile; endif; ?>
</div>
<?php get_footer(); ?>
