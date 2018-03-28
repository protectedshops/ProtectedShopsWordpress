<?php
  $redux_ThemeTek = get_option( 'redux_ThemeTek' );
?>

<div class="modal fade popup-modal" id="popup-modal" role="dialog">
  <div class="modal-content">
      <div class="row">
        <?php if (isset($redux_ThemeTek['tek-modal-title']) && $redux_ThemeTek['tek-modal-title'] != '' ) : ?>
            <h2><?php echo esc_attr($redux_ThemeTek['tek-modal-title']); ?></h2><div class="heading-separator"></div>
        <?php endif; ?>
            <div class="modal-content-inner">
              <?php if (isset($redux_ThemeTek['tek-modal-form-select']) && $redux_ThemeTek['tek-modal-form-select'] != '' ) : ?>
                   <?php if ($redux_ThemeTek['tek-modal-form-select'] == '1') : ?>
                        <?php if (isset($redux_ThemeTek['tek-modal-contactf7-formid']) && $redux_ThemeTek['tek-modal-contactf7-formid'] != '' ) : ?>
                              <?php echo do_shortcode('[contact-form-7 id="'. esc_attr($redux_ThemeTek['tek-modal-contactf7-formid']).'"]'); ?>
                        <?php endif; ?>
                   <?php elseif ($redux_ThemeTek['tek-modal-form-select'] == '2') : ?>
                        <?php if (isset($redux_ThemeTek['tek-modal-ninja-formid']) && $redux_ThemeTek['tek-modal-ninja-formid'] != '' ) : ?>
                              <?php echo do_shortcode('[ninja_form id="'. esc_attr($redux_ThemeTek['tek-modal-ninja-formid']).'"]'); ?>
                        <?php endif; ?>
                   <?php endif; ?>
              <?php endif; ?>
            </div>
      </div>
  </div>
</div>
