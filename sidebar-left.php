<?php
global $opt_name;

$post_type = universal_get_post_type();
$post_type_lc = strtolower($post_type);

$sidebar_position = get_sidebar_position($opt_name);


// Левый сайдбар
if ($sidebar_position === 'left' && is_active_sidebar($post_type)) { ?>
   <aside class="col-xl-4 sidebar sticky-sidebar mt-md-0 py-14 d-none d-xl-block">
      <?php
      do_action('codeweber_before_sidebar', $post_type);
      dynamic_sidebar($post_type);
      do_action('codeweber_after_sidebar', $post_type);
      ?>
   </aside>
   <?php
} else {
   if ($sidebar_position === 'left' && !is_active_sidebar($post_type)) { ?>
      <aside class="col-xl-4 sidebar sticky-sidebar mt-md-0 py-14 d-none d-xl-block">
         <?php
         do_action('codeweber_after_widget', $post_type); ?>
      </aside>
<?php
   }
}