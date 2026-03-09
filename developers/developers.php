<?php
/**
 * Theme Developer Admin Menu
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Moyna_Developer' ) ) {

	class Moyna_Developer {

		private $theme_name;
		private $theme_version;
		private $page_title;
		private $menu_title;

		public function __construct() {
			$theme = wp_get_theme();
			$this->theme_name    = $theme->get( 'Name' );
			$this->theme_version = $theme->get( 'Version' );

			// Initialize translatable titles
			$this->page_title = esc_html__( '%s Developer', 'moyna' );
			$this->menu_title = esc_html__( '%s Theme', 'moyna' );

			add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
		}

		public function add_admin_menu() {
			add_theme_page(
				sprintf( $this->page_title, $this->theme_name ),
				sprintf( $this->menu_title, $this->theme_name ),
				'edit_theme_options',
				'moyna-developer',
				[ $this, 'render_screen' ]
			);
		}
		
        public function render_screen() {
            echo '<div class="information-wrap">';

            // Developer Title
            $developer_title = sprintf(
                esc_html__( 'Welcome to %s', 'moyna' ),
                esc_html( $this->theme_name )
            );
            echo '<h1 class="theme-name-moyna">' . $developer_title . ' ' . esc_html( $this->theme_version ) . '</h1>';

            // Developer Content
            $developer_content = sprintf(
                esc_html__( '%s is ideal for self-hosted Micro Blog, Community Social Network, News Headlines Publishing etc.', 'moyna' ),
                esc_html( $this->theme_name )
            );
            echo '<p class="theme-description-moyna">' . $developer_content . '</p>';

            // Quick Links
            $quick_links = [
                [ 'text' => esc_html__( 'Live Preview', 'moyna' ), 'url' => 'https://wp-themes.com/moyna/' ],
                [ 'text' => esc_html__( 'Introduction to Theme', 'moyna' ), 'url' => 'https://store.devilhunter.net/wordpress-theme/moyna' ],
                [ 'text' => esc_html__( 'Theme on WordPress.org', 'moyna' ), 'url' => 'https://wordpress.org/themes/moyna/' ],
                [ 'text' => esc_html__( 'Web Documentation', 'moyna' ), 'url' => 'https://store.devilhunter.net/documentation/moyna/' ],
                [ 'text' => esc_html__( 'Theme Developer', 'moyna' ), 'url' => 'https://www.tawhidurrahmandear.com/' ],
                [ 'text' => esc_html__( 'Rate and Review', 'moyna' ), 'url' => 'https://wordpress.org/support/theme/moyna/reviews/#new-post' ],
                [ 'text' => esc_html__( 'Released under GPL 2.0 or later', 'moyna' ), 'url' => 'https://www.gnu.org/licenses/gpl-3.0.en.html' ],
            ];

            echo '<div class="quick-links">';
            foreach ( $quick_links as $link ) {
                echo '<a href="' . esc_url( $link['url'] ) . '" target="_blank" class="button-link">'
                    . $link['text'] 
                    . '</a>';
            }
            echo '</div>';

            // Feedback Form (opens default email client)
            if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['moyna_feedback_nonce'] ) ) {

                if ( ! wp_verify_nonce( $_POST['moyna_feedback_nonce'], 'moyna_feedback_action' ) ) {
                    echo '<p>' . esc_html__( 'Security check failed.', 'moyna' ) . '</p>';
                    return;
                }

                $email    = sanitize_email( $_POST['email'] );
                $feedback = sanitize_textarea_field( $_POST['feedback'] );

                $subject = 'Feedback from ' . $email;
                $body    = "Feedback:\n" . $feedback . "\n\nSent from: " . $email;

                $mailto = 'mailto:tawhidurrahmandear@gmail.com'
                        . '?subject=' . rawurlencode( $subject )
                        . '&body=' . rawurlencode( $body );

                echo '<script type="text/javascript">
                        window.location.href = "' . esc_url( $mailto ) . '";
                      </script>';

						echo '<p style="text-align:center; font-weight:500; margin:50px 0;">' 
			. esc_html__( 'Your email client has been opened with the feedback.', 'moyna' ) 
			. '</p>';

            } else {
                ?>
                <div class="feedback-form">
                    <h2><?php echo esc_html__( 'Feedback', 'moyna' ); ?></h2>

                    <form method="post" action="">
                        <?php wp_nonce_field( 'moyna_feedback_action', 'moyna_feedback_nonce' ); ?>

                        <p>
                            <label><?php echo esc_html__( 'Your Email:', 'moyna' ); ?></label><br>
                            <input type="email" name="email" required>
                        </p>

                        <p>
                            <label><?php echo esc_html__( 'Your Feedback:', 'moyna' ); ?></label><br>
                            <textarea name="feedback" required></textarea>
                        </p>

                        <p>
                            <button type="submit"><?php echo esc_html__( 'Submit Feedback', 'moyna' ); ?></button>
                        </p>
                    </form>
                </div>
                <?php
            }

            echo '</div>';
        }

        public function enqueue_assets( $hook_suffix ) {
            wp_enqueue_style(
                'moyna-theme-developer-css',
                get_template_directory_uri() . '/developers/assets/developers.css'
            );
        }

    }
}

// Initialize
function moyna_init_developer_page() {
    new Moyna_Developer();
}
add_action( 'after_setup_theme', 'moyna_init_developer_page' );
