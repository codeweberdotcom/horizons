<?php $content = get_the_content(); ?>
<?php if (! empty($content)) : ?>
   <div class="mb-3" id="partners_section_1">
      <div class="body-l-r mb-7">
         <?php echo $content; ?>
      </div>
   </div>
<?php endif; ?>

<?php echo do_shortcode('[awards_grid posts_per_page="5" columns="3" columns_md="3" columns_sm="2" partners_awards="true"]'); ?>