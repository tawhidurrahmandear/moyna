<footer>
    <div class="footer-content">
        <p>©<?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
        <?php if (has_nav_menu('footer')) : ?>
            <nav class="footer-menu">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'menu_class'     => 'footer-menu',
                    'container'      => false,
                ));
                ?>
            </nav>
        <?php endif; ?>
    </div>
    <?php wp_footer(); ?>
</footer>
</body>
</html>