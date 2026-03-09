<?php
/**
 * Moyna Theme Customizer Options
 *
 * Adds visual customization options for colors, fonts, and layout.
 */

// Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}
// 

/**
 * Custom Reset Control Class
 */
if (class_exists('WP_Customize_Control')) {

    class WP_Customize_Moyna_Reset_Control extends WP_Customize_Control {
        public $type = 'moyna_reset_button';
        
        public function render_content() {
            ?>
            <style>
                .moyna-reset-button {
                    background: #d63638;
                    color: #fff;
                    border: none;
                    padding: 12px 20px;
                    border-radius: 4px;
                    cursor: pointer;
                    font-size: 14px;
                    font-weight: 600;
                    width: 100%;
                    text-align: center;
                    transition: background 0.3s ease;
                    margin: 10px 0;
                }
                .moyna-reset-button:hover {
                    background: #b32d2e;
                }
                .moyna-reset-button:disabled {
                    background: #ccc;
                    cursor: not-allowed;
                }
                .moyna-reset-confirm {
                    display: none;
                    margin-top: 10px;
                    padding: 10px;
                    background: #fff3cd;
                    border: 1px solid #ffeaa7;
                    border-radius: 4px;
                }
                .moyna-reset-buttons {
                    margin-top: 10px;
                }
                .moyna-reset-confirm-btn {
                    background: #d63638;
                    color: white;
                    border: none;
                    padding: 8px 16px;
                    border-radius: 3px;
                    cursor: pointer;
                    margin-right: 10px;
                }
                .moyna-reset-cancel-btn {
                    background: #6c757d;
                    color: white;
                    border: none;
                    padding: 8px 16px;
                    border-radius: 3px;
                    cursor: pointer;
                }
            </style>

            <div class="moyna-reset-wrapper">
                <button type="button" class="moyna-reset-button" id="moyna-reset-trigger">
                    <?php echo esc_html__('Reset All Settings to Defaults', 'moyna'); ?>
                </button>
                
                <div class="moyna-reset-confirm" id="moyna-reset-confirm">
                    <p><strong><?php echo esc_html__('Are you sure?', 'moyna'); ?></strong></p>
                    <p><?php echo esc_html__('This will reset all customizer settings to their default values. This action cannot be undone.', 'moyna'); ?></p>
                    <div class="moyna-reset-buttons">
                        <button type="button" class="moyna-reset-confirm-btn" id="moyna-reset-confirm-btn">
                            <?php echo esc_html__('Yes, Reset Everything', 'moyna'); ?>
                        </button>
						<br><br>
                        <button type="button" class="moyna-reset-cancel-btn" id="moyna-reset-cancel-btn">
                            <?php echo esc_html__('Cancel', 'moyna'); ?>
                        </button>
                    </div>
                </div>
            </div>

            <script>
            (function($) {
                $(document).ready(function() {
                    var resetTrigger = $('#moyna-reset-trigger');
                    var resetConfirm = $('#moyna-reset-confirm');
                    var confirmBtn = $('#moyna-reset-confirm-btn');
                    var cancelBtn = $('#moyna-reset-cancel-btn');
                    
                    resetTrigger.on('click', function() {
                        resetConfirm.slideDown();
                        $(this).prop('disabled', true);
                    });
                    
                    cancelBtn.on('click', function() {
                        resetConfirm.slideUp();
                        resetTrigger.prop('disabled', false);
                    });
                    
                    confirmBtn.on('click', function() {
                        $(this).text('<?php echo esc_js(__('Resetting...', 'moyna')); ?>').prop('disabled', true);
                        
                        $.post('<?php echo admin_url('admin-ajax.php'); ?>', {
                            action: 'moyna_reset_customizer',
                            nonce: '<?php echo wp_create_nonce('moyna_reset_nonce'); ?>'
                        }, function(response) {
                            if (response.success) {
                                location.reload();
                            } else {
                                alert('<?php echo esc_js(__('Error resetting settings. Please try again.', 'moyna')); ?>');
                                location.reload();
                            }
                        }).fail(function() {
                            alert('<?php echo esc_js(__('Error resetting settings. Please try again.', 'moyna')); ?>');
                            location.reload();
                        });
                    });
                });
            })(jQuery);
            </script>
            <?php
        }
    }
}


/**
 * Register Customizer Settings and Controls
 */
 
function moyna_customize_register($wp_customize) {

    /*
    |--------------------------------------------------------------------------
    | SECTION: Theme Colors
    |--------------------------------------------------------------------------
    */
    $wp_customize->add_section('moyna_colors_section', array(
        'title'    => __('Theme Colors', 'moyna'),
        'priority' => 30,
    ));

    // Body background
	$wp_customize->add_setting('moyna_body_bg', array(
		'default'   => '#f4f7fa',
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_hex_color',
	));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'moyna_body_bg', array(
        'label'   => __('Body Background', 'moyna'),
        'section' => 'moyna_colors_section',
    )));
	
	// Link color
	$wp_customize->add_setting('moyna_link_color', array(
		'default'   => '#FFFFFF',
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'moyna_link_color', array(
		'label'   => __('Link Color', 'moyna'),
		'section' => 'moyna_colors_section',
	)));

    // Button background
    $wp_customize->add_setting('moyna_button_bg', array(
        'default'   => '#4CAF50',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'moyna_button_bg', array(
        'label'   => __('Button Background', 'moyna'),
        'section' => 'moyna_colors_section',
    )));

    // Button hover
    $wp_customize->add_setting('moyna_button_hover', array(
        'default'   => '#8224e3',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'moyna_button_hover', array(
        'label'   => __('Button Hover Background', 'moyna'),
        'section' => 'moyna_colors_section',
    )));

    /*
    |--------------------------------------------------------------------------
    | SECTION: Post Box
    |--------------------------------------------------------------------------
    */
    $wp_customize->add_section('moyna_post_section', array(
        'title'    => __('Post Box', 'moyna'),
        'priority' => 31,
    ));

    $wp_customize->add_setting('moyna_post_bg', array(
        'default'   => '#f2e6ff',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'moyna_post_bg', array(
        'label'   => __('Post Background', 'moyna'),
        'section' => 'moyna_post_section',
    )));
	
	// Post Text Color
	$wp_customize->add_setting('moyna_post_text_color', array(
		'default'   => '#000000',
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'moyna_post_text_color', array(
		'label'   => __('Post Text Color', 'moyna'),
		'section' => 'moyna_post_section', 
	)));

	// Author Color
	$wp_customize->add_setting('moyna_author_color', array(
		'default'   => '#555555',
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'moyna_author_color', array(
		'label'   => __('Author Color', 'moyna'),
		'section' => 'moyna_post_section', 
	)));

	// Date Color
	$wp_customize->add_setting('moyna_date_color', array(
		'default'   => '#777777',
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'moyna_date_color', array(
		'label'   => __('Date Color', 'moyna'),
		'section' => 'moyna_post_section', 
	)));
		
	$wp_customize->add_setting('moyna_post_radius', array(
        'default'   => 10,
        'transport' => 'refresh',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('moyna_post_radius', array(
        'label'       => __('Post Border Radius (px)', 'moyna'),
        'section'     => 'moyna_post_section',
        'type'        => 'number',
        'input_attrs' => array('min' => 0, 'max' => 50),
    ));
	
    $wp_customize->add_setting('moyna_container_width', array(
        'default'   => 80,
        'transport' => 'refresh',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('moyna_container_width', array(
        'label'       => __('Container Width (%)', 'moyna'),
        'section'     => 'moyna_post_section',
        'type'        => 'number',
        'input_attrs' => array('min' => 60, 'max' => 100),
    ));

    /*
    |--------------------------------------------------------------------------
    | SECTION: Header and Footer
    |--------------------------------------------------------------------------
    */
    $wp_customize->add_section('moyna_header_footer_section', array(
        'title'    => __('Header and Footer', 'moyna'),
        'priority' => 32,
    ));

    $wp_customize->add_setting('moyna_header_bg', array(
        'default'   => '#333333',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'moyna_header_bg', array(
        'label'   => __('Header Background', 'moyna'),
        'section' => 'moyna_header_footer_section',
    )));

    $wp_customize->add_setting('moyna_header_text_color', array(
        'default'   => '#ffffff',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'moyna_header_text_color', array(
        'label'   => __('Header Text Color', 'moyna'),
        'section' => 'moyna_header_footer_section',
    )));

    $wp_customize->add_setting('moyna_footer_bg', array(
        'default'   => '#333333',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'moyna_footer_bg', array(
        'label'   => __('Footer Background', 'moyna'),
        'section' => 'moyna_header_footer_section',
    )));

    $wp_customize->add_setting('moyna_footer_text_color', array(
        'default'   => '#ffffff',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'moyna_footer_text_color', array(
        'label'   => __('Footer Text Color', 'moyna'),
        'section' => 'moyna_header_footer_section',
    )));

	/*
	|--------------------------------------------------------------------------
	| SECTION: Fonts
	|--------------------------------------------------------------------------
	*/
	$wp_customize->add_section('moyna_fonts_section', array(
		'title'    => __('Fonts', 'moyna'),
		'priority' => 33,
	));

	// Base Font

	$wp_customize->add_setting('moyna_base_font_family', array(
		'default'   => 'system-ui',
		'transport' => 'refresh',
		'sanitize_callback' => 'moyna_sanitize_font_family',
	));
	$wp_customize->add_control('moyna_base_font_family', array(
		'label'   => __('Base Font Family', 'moyna'),
		'section' => 'moyna_fonts_section',
		'type'    => 'select',
		'choices' => moyna_get_font_choices(),
	));

	$wp_customize->add_setting('moyna_base_font_size', array(
		'default'   => '16',
		'transport' => 'refresh',
		'sanitize_callback' => 'absint',
	));
	$wp_customize->add_control('moyna_base_font_size', array(
		'label'   => __('Base Font Size (px)', 'moyna'),
		'section' => 'moyna_fonts_section',
		'type'    => 'number',
		'input_attrs' => array(
			'min' => 12,
			'max' => 24,
			'step' => 1,
		),
	));

	// Header Font

	$wp_customize->add_setting('moyna_header_font_family', array(
		'default'   => 'system-ui',
		'transport' => 'refresh',
		'sanitize_callback' => 'moyna_sanitize_font_family',
	));
	$wp_customize->add_control('moyna_header_font_family', array(
		'label'   => __('Header Font Family', 'moyna'),
		'section' => 'moyna_fonts_section',
		'type'    => 'select',
		'choices' => moyna_get_font_choices(),
	));

	$wp_customize->add_setting('moyna_header_font_size', array(
		'default'   => '32',
		'transport' => 'refresh',
		'sanitize_callback' => 'absint',
	));
	$wp_customize->add_control('moyna_header_font_size', array(
		'label'   => __('Header Font Size (px)', 'moyna'),
		'section' => 'moyna_fonts_section',
		'type'    => 'number',
		'input_attrs' => array(
			'min' => 20,
			'max' => 60,
			'step' => 1,
		),
	));

	// Post Text Font

	$wp_customize->add_setting('moyna_post_text_font_family', array(
		'default'   => 'system-ui',
		'transport' => 'refresh',
		'sanitize_callback' => 'moyna_sanitize_font_family',
	));
	$wp_customize->add_control('moyna_post_text_font_family', array(
		'label'   => __('Post Text Font Family', 'moyna'),
		'section' => 'moyna_fonts_section',
		'type'    => 'select',
		'choices' => moyna_get_font_choices(),
	));

	$wp_customize->add_setting('moyna_post_text_font_size', array(
		'default'   => '18',
		'transport' => 'refresh',
		'sanitize_callback' => 'absint',
	));
	$wp_customize->add_control('moyna_post_text_font_size', array(
		'label'   => __('Post Text Font Size (px)', 'moyna'),
		'section' => 'moyna_fonts_section',
		'type'    => 'number',
		'input_attrs' => array(
			'min' => 12,
			'max' => 30,
			'step' => 1,
		),
	));

	// Author Font

	$wp_customize->add_setting('moyna_author_font_family', array(
		'default'   => 'system-ui',
		'transport' => 'refresh',
		'sanitize_callback' => 'moyna_sanitize_font_family',
	));
	$wp_customize->add_control('moyna_author_font_family', array(
		'label'   => __('Author Font Family', 'moyna'),
		'section' => 'moyna_fonts_section',
		'type'    => 'select',
		'choices' => moyna_get_font_choices(),
	));

	$wp_customize->add_setting('moyna_author_font_size', array(
		'default'   => '14',
		'transport' => 'refresh',
		'sanitize_callback' => 'absint',
	));
	$wp_customize->add_control('moyna_author_font_size', array(
		'label'   => __('Author Font Size (px)', 'moyna'),
		'section' => 'moyna_fonts_section',
		'type'    => 'number',
		'input_attrs' => array(
			'min' => 10,
			'max' => 24,
			'step' => 1,
		),
	));

	// Date Font

	$wp_customize->add_setting('moyna_date_font_family', array(
		'default'   => 'system-ui',
		'transport' => 'refresh',
		'sanitize_callback' => 'moyna_sanitize_font_family',
	));
	$wp_customize->add_control('moyna_date_font_family', array(
		'label'   => __('Date Font Family', 'moyna'),
		'section' => 'moyna_fonts_section',
		'type'    => 'select',
		'choices' => moyna_get_font_choices(),
	));

	$wp_customize->add_setting('moyna_date_font_size', array(
		'default'   => '13',
		'transport' => 'refresh',
		'sanitize_callback' => 'absint',
	));
	$wp_customize->add_control('moyna_date_font_size', array(
		'label'   => __('Date Font Size (px)', 'moyna'),
		'section' => 'moyna_fonts_section',
		'type'    => 'number',
		'input_attrs' => array(
			'min' => 10,
			'max' => 22,
			'step' => 1,
		),
	));

	// Button Font

	$wp_customize->add_setting('moyna_button_font_family', array(
		'default'   => 'system-ui',
		'transport' => 'refresh',
		'sanitize_callback' => 'moyna_sanitize_font_family',
	));
	$wp_customize->add_control('moyna_button_font_family', array(
		'label'   => __('Button Font Family', 'moyna'),
		'section' => 'moyna_fonts_section',
		'type'    => 'select',
		'choices' => moyna_get_font_choices(),
	));

	$wp_customize->add_setting('moyna_button_font_size', array(
		'default'   => '16',
		'transport' => 'refresh',
		'sanitize_callback' => 'absint',
	));
	$wp_customize->add_control('moyna_button_font_size', array(
		'label'   => __('Button Font Size (px)', 'moyna'),
		'section' => 'moyna_fonts_section',
		'type'    => 'number',
		'input_attrs' => array(
			'min' => 12,
			'max' => 24,
			'step' => 1,
		),
	));

	// Footer Font

	$wp_customize->add_setting('moyna_footer_font_family', array(
		'default'   => 'system-ui',
		'transport' => 'refresh',
		'sanitize_callback' => 'moyna_sanitize_font_family',
	));
	$wp_customize->add_control('moyna_footer_font_family', array(
		'label'   => __('Footer Font Family', 'moyna'),
		'section' => 'moyna_fonts_section',
		'type'    => 'select',
		'choices' => moyna_get_font_choices(),
	));

	$wp_customize->add_setting('moyna_footer_font_size', array(
		'default'   => '14',
		'transport' => 'refresh',
		'sanitize_callback' => 'absint',
	));
	$wp_customize->add_control('moyna_footer_font_size', array(
		'label'   => __('Footer Font Size (px)', 'moyna'),
		'section' => 'moyna_fonts_section',
		'type'    => 'number',
		'input_attrs' => array(
			'min' => 10,
			'max' => 20,
			'step' => 1,
		),
	));

/*
|--------------------------------------------------------------------------|
| SECTION: Reset Theme Customization
|--------------------------------------------------------------------------|
*/
$wp_customize->add_section('moyna_reset_section', array(
    'title'    => __('Reset Theme Customization', 'moyna'),
    'priority' => 1000, // Place at the bottom
));

// Reset button control
$wp_customize->add_setting('moyna_reset_settings', array(
    'default'   => '',
    'transport' => 'postMessage',
    'sanitize_callback' => 'moyna_sanitize_reset_button',
));

$wp_customize->add_control(new WP_Customize_Moyna_Reset_Control(
    $wp_customize,
    'moyna_reset_settings',
    array(
        'label'       => __('Reset All Settings', 'moyna'),
        'description' => __('Warning: This will reset all customizer settings to their defaults. This action cannot be undone.', 'moyna'),
        'section'     => 'moyna_reset_section',
        'type'        => 'moyna_reset_button',
    )
));

}

add_action('customize_register', 'moyna_customize_register');

/**
 * Get font choices for select controls - Using System Fonts & Web Safe Fonts
 */
 
function moyna_get_font_choices() {
    return array(
        'system-ui' => 'System Default',
        'Arial, sans-serif' => 'Arial',
        'Helvetica, Arial, sans-serif' => 'Helvetica',
        'Georgia, serif' => 'Georgia',
        'Times New Roman, Times, serif' => 'Times New Roman',
        'Tahoma, Geneva, sans-serif' => 'Tahoma',
        'Verdana, Geneva, sans-serif' => 'Verdana',
        'Trebuchet MS, sans-serif' => 'Trebuchet MS',
        'Impact, Haettenschweiler, sans-serif' => 'Impact',
        'Courier New, Courier, monospace' => 'Courier New',
        'Lucida Console, Monaco, monospace' => 'Lucida Console',
        'Palatino Linotype, Book Antiqua, Palatino, serif' => 'Palatino',
        'MS Sans Serif, Geneva, sans-serif' => 'MS Sans Serif',
        'Comic Sans MS, cursive' => 'Comic Sans MS',
        'Segoe UI, system-ui' => 'Segoe UI',
    );
}

/**
 * Sanitize font family
 */
function moyna_sanitize_font_family($input) {
    $valid = array_keys(moyna_get_font_choices());
    return in_array($input, $valid, true) ? $input : 'system-ui';
}

// ----------------------------
// Remove default "Homepage Settings and Menu"
// ----------------------------

add_filter( 'customize_loaded_components', function( $components ) {
    // Remove the homepage section
    add_action( 'customize_register', function( $wp_customize ) {
        $wp_customize->remove_section( 'static_front_page' );
    });

    // Remove the nav menus panel
    if ( isset( $components['nav_menus'] ) ) {
        unset( $components['nav_menus'] );
    }

    return $components;
} );


/**
 * Output Dynamic CSS
 */
function moyna_customizer_css() {
    ?>
    <style type="text/css">
		/* Body */
		body {
			background-color: <?php echo esc_attr(get_theme_mod('moyna_body_bg', '#f4f7fa')); ?> !important;
			font-family: <?php echo esc_attr(get_theme_mod('moyna_base_font_family', 'system-ui')); ?>;
			font-size: <?php echo esc_attr(get_theme_mod('moyna_base_font_size', '16')); ?>px;
		}

        .container {
            width: <?php echo esc_attr(get_theme_mod('moyna_container_width', 80)); ?>%;
        }

		/* Links */
		a,
		.wp-block-button__link,
		.btn,
		button a {
			color: <?php echo esc_attr(get_theme_mod('moyna_link_color', '#FFFFFF')); ?> !important;
		}

        /* Posts */
        .post {
            background-color: <?php echo esc_attr(get_theme_mod('moyna_post_bg', '#f2e6ff')); ?>;
            border-radius: <?php echo esc_attr(get_theme_mod('moyna_post_radius', 10)); ?>px;
        }

        .post,
        .post .post-content,
        .post p,
        .wp-block-post p {
            color: <?php echo esc_attr(get_theme_mod('moyna_post_text_color', '#000000')); ?> !important;
            font-family: <?php echo esc_attr(get_theme_mod('moyna_post_text_font_family', 'system-ui')); ?>;
		   font-size: <?php echo absint( get_theme_mod('moyna_post_text_font_size', 18) ); ?>px;
        }

        .post .author-info strong,
        .wp-block-post-template .wp-block-post-author__name {
            color: <?php echo esc_attr(get_theme_mod('moyna_author_color', '#555555')); ?> !important;
            font-family: <?php echo esc_attr(get_theme_mod('moyna_author_font_family', 'system-ui')); ?>;
		   font-size: <?php echo absint( get_theme_mod('moyna_author_font_size', 14) ); ?>px;
        }

        .post small,
        .wp-block-post-date {
            color: <?php echo esc_attr(get_theme_mod('moyna_date_color', '#777777')); ?> !important;
            font-family: <?php echo esc_attr(get_theme_mod('moyna_date_font_family', 'system-ui')); ?>;
	        font-size: <?php echo absint( get_theme_mod('moyna_date_font_size', 13) ); ?>px;
        }

		/* Header */
        .site-header,
        header {
			background-color: <?php echo esc_attr(get_theme_mod('moyna_header_bg', '#333333')); ?> !important;
		}

		header h1,
		header h2,
		header h3,
		header h4,
		header h5,
		header h6,
		header .site-title,
		header nav a {
			color: <?php echo esc_attr(get_theme_mod('moyna_header_text_color', '#ffffff')); ?> !important;
			font-family: <?php echo esc_attr(get_theme_mod('moyna_header_font_family', 'system-ui')); ?>;
			font-size: <?php echo esc_attr(get_theme_mod('moyna_header_font_size', '32')); ?>px;
		}

		/* Footer */
        .site-footer,
        footer {
			background-color: <?php echo esc_attr(get_theme_mod('moyna_footer_bg', '#333333')); ?> !important;
			font-family: <?php echo esc_html( get_theme_mod('moyna_footer_font_family', 'system-ui') ); ?>;
             font-size: <?php echo absint( get_theme_mod('moyna_footer_font_size', 14) ); ?>px;
		}

		.site-footer,
        .site-footer p,
        .site-footer a,
        footer,
        footer p,
        footer a,
	    footer .widget-title {
			color: <?php echo esc_attr(get_theme_mod('moyna_footer_text_color', '#ffffff')); ?> !important;
			font-family: <?php echo esc_html( get_theme_mod('moyna_footer_font_family', 'system-ui') ); ?>;
             font-size: <?php echo absint( get_theme_mod('moyna_footer_font_size', 14) ); ?>px;
		}

		/* Buttons */
			.pagination a,
			button,
			input[type="submit"],
			input[type="button"],
			button:not(.components-button),
			input[type="submit"],
			input[type="button"],
			.wp-block-button__link,
			.btn {
			background-color: <?php echo esc_attr(get_theme_mod('moyna_button_bg', '#4CAF50')); ?>;
			color: #fff;
			font-family: <?php echo esc_attr(get_theme_mod('moyna_button_font_family', 'system-ui')); ?>;
			font-size: <?php echo esc_attr(get_theme_mod('moyna_button_font_size', '16')); ?>px;
		}
		
		.pagination a:hover,
		button:hover,
		input[type="submit"]:hover,
		input[type="button"]:hover,
        button:not(.components-button):hover,
        input[type="submit"]:hover,
        input[type="button"]:hover,
		.pagination a:hover,
		.wp-block-button__link:hover,
		.btn:hover {
			background-color: <?php echo esc_attr(get_theme_mod('moyna_button_hover', '#45a049')); ?>;
		}
		</style>
    <?php
}
add_action('wp_head', 'moyna_customizer_css');