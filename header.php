<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
	
    <header>
	
		<?php
		// Get the site icon URL if available
		$site_icon_url = get_site_icon_url(100);

		// Show the icon only if it exists
		if ($site_icon_url) : ?>
				<div class="site-icon-wrapper">
					<img src="<?php echo esc_url($site_icon_url); ?>" 
				 alt="<?php bloginfo('name'); ?> Icon" 
				 class="site-icon" 
				 width="100" 
				 height="100" 
				 style="width:100px;height:100px;border-radius:50%;object-fit:cover;display:inline-block;">
				</div>
		<?php endif; ?>

        <h1><?php bloginfo('name'); ?></h1>
        <?php if (has_nav_menu('primary')) : ?>
            <nav class="primary-menu">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'menu',
                ));
                ?>
            </nav>
        <?php endif; ?>
    </header>

    <!-- Main content will follow below -->