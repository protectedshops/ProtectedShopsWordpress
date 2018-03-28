<!-- Header Button template -->
<?php
  $redux_ThemeTek = get_option( 'redux_ThemeTek' );
  if (isset($redux_ThemeTek['tek-header-button'])){
    if ($redux_ThemeTek['tek-header-button'] && ($redux_ThemeTek['tek-header-button-action'] == '1')) : ?>
       <a class="modal-menu-item menu-item" data-toggle="modal" data-target="#popup-modal"><?php echo esc_html($redux_ThemeTek['tek-header-button-text']);?></a>
    <?php elseif ($redux_ThemeTek['tek-header-button'] && ($redux_ThemeTek['tek-header-button-action'] == '2')) : ?>
      <?php if (isset($redux_ThemeTek['tek-scroll-id']) && $redux_ThemeTek['tek-scroll-id'] != '' ) : ?>
         <a class="modal-menu-item menu-item scroll-section" href="<?php if( is_page('Home')) { echo esc_html($redux_ThemeTek['tek-scroll-id']); } else { echo esc_url(site_url()) . esc_html($redux_ThemeTek['tek-scroll-id']);} ?>"><?php echo esc_html($redux_ThemeTek['tek-header-button-text']);?></a>
      <?php endif; ?>
    <?php elseif ($redux_ThemeTek['tek-header-button'] && ($redux_ThemeTek['tek-header-button-action'] == '3')) : ?>
      <?php if (isset($redux_ThemeTek['tek-button-new-page']) && $redux_ThemeTek['tek-button-new-page'] != '' ) : ?>
         <a class="modal-menu-item menu-item" target="_blank" href="<?php echo esc_url($redux_ThemeTek['tek-button-new-page']); ?>"><?php echo esc_html($redux_ThemeTek['tek-header-button-text']);?></a>
      <?php endif; ?>
    <?php endif; ?>
<?php } ?>
<!-- END Header Button template -->
