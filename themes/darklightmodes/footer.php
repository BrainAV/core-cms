<?php
/**
 * Theme: Dark/Light Mode
 * Template: Footer
 */
// Get Footer Text: Check Label Editor first, fallback to Site Settings
$settings_text = get_option('site_footer_text', '&copy; ' . date('Y') . ' Core CMS.');
$footer_text = get_label('footer_text', $settings_text);
?>
    </div> <!-- End .container -->
</main>

<footer class="site-footer">
    <div class="container">
        <?php echo render_blocks($footer_text); ?>
    </div>
</footer>

<?php
// Scroll to Top Logic
if (get_option('scroll_top_enabled', '0') === '1') {
    $st_pos = get_option('scroll_top_position', 'bottom-right');
    $st_bg = get_option('scroll_top_bg_color', '#007bff');
    $st_icon = get_option('scroll_top_icon_color', '#ffffff');
    $st_shape = get_option('scroll_top_shape', 'rounded');

    $pos_css = match($st_pos) {
        'bottom-left' => 'bottom: 20px; left: 20px;',
        'bottom-center' => 'bottom: 20px; left: 50%; transform: translateX(-50%);',
        default => 'bottom: 20px; right: 20px;',
    };

    $radius = match($st_shape) {
        'circle' => '50%',
        'rounded' => '5px',
        default => '0',
    };
?>
<button id="scrollToTopBtn" title="Go to top" style="display: none; position: fixed; <?php echo $pos_css; ?> z-index: 99; border: none; outline: none; background-color: <?php echo $st_bg; ?>; color: <?php echo $st_icon; ?>; cursor: pointer; width: 50px; height: 50px; padding: 0; text-align: center; line-height: 50px; border-radius: <?php echo $radius; ?>; font-size: 24px;">
    &uarr;
</button>

<script>
    var mybutton = document.getElementById("scrollToTopBtn");
    window.onscroll = function() {scrollFunction()};
    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            mybutton.style.display = "block";
        } else {
            mybutton.style.display = "none";
        }
    }
    mybutton.onclick = function() {
        window.scrollTo({top: 0, behavior: 'smooth'});
    };
</script>
<?php } ?>

<script>
    // Theme Toggle Logic
    const toggleBtn = document.getElementById('theme-toggle');
    toggleBtn.addEventListener('click', () => {
        let currentTheme = document.documentElement.getAttribute('data-theme');
        
        // If no attribute set yet, determine what the browser is currently showing
        if (!currentTheme) {
             currentTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }
        
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
    });
</script>

</body>
</html>