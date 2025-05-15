<?php get_header(); ?>

<h1>Surinaamse Recepten</h1>

<form method="GET">
  <label for="ingredient">Filter op ingrediënt:</label>
  <select name="ingredient" id="ingredient" onchange="this.form.submit()">
    <option value="">-- Alles --</option>
    <?php
    $terms = get_terms(['taxonomy' => 'ingredient', 'hide_empty' => false]);
    foreach ($terms as $term) {
        $selected = (isset($_GET['ingredient']) && $_GET['ingredient'] == $term->slug) ? 'selected' : '';
        echo "<option value='{$term->slug}' $selected>{$term->name}</option>";
    }
    ?>
  </select>
</form>

<div class="recepten">
  <?php
  $args = ['post_type' => 'recept', 'posts_per_page' => 10];
  if (!empty($_GET['ingredient'])) {
      $args['tax_query'] = [[
          'taxonomy' => 'ingredient',
          'field' => 'slug',
          'terms' => sanitize_text_field($_GET['ingredient'])
      ]];
  }

  $query = new WP_Query($args);
  if ($query->have_posts()) {
      while ($query->have_posts()) {
          $query->the_post();
          get_template_part('template-parts/content', 'recept');
      }
  } else {
      echo "<p>Geen recepten gevonden.</p>";
  }
  wp_reset_postdata();
  ?>
</div>

<?php get_footer(); ?>
