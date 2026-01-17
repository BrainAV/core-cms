  </div> <!-- End .container -->

  <!-- Footer -->
  <footer>
    <div class="footer-content">
      <div class="footer-grid">
        <div class="footer-section">
          <h4>BrainAV</h4>
          <p>A creative technology lab building intelligent tools for the future of music.</p>
        </div>
        
        <div class="footer-section">
          <h4>Resources</h4>
          <ul>
            <li><a href="https://github.com/BrainAV">GitHub Organization</a></li>
            <li><a href="https://jasonbrain.com/blog">Tech & Music Blog</a></li>
            <li><a href="https://jasonbrain.com">JasonBrain.com (Services)</a></li>
          </ul>
        </div>
        
        <div class="footer-section">
          <h4>Connect</h4>
          <div class="social-links">
            <a href="https://github.com/BrainAV/" target="_blank" rel="noopener" title="GitHub"><i data-feather="github"></i></a>
            <a href="https://x.com/BrainAVDJ" target="_blank" rel="noopener" title="X"><i data-feather="twitter"></i></a>
            <a href="https://www.youtube.com/@brainav" target="_blank" rel="noopener" title="YouTube"><i data-feather="youtube"></i></a>
          </div>
        </div>
      </div>
      
      <div class="footer-bottom">
        <p><?php echo get_option('site_footer_text', '&copy; ' . date('Y') . ' BrainAV | MIT License'); ?></p>
      </div>
    </div>
  </footer>

  <!-- Scroll to Top Button -->
  <button class="scroll-top" id="scrollTop">↑</button>

  <!-- Theme Scripts -->
  <script src="<?php echo BASE_URL; ?>/themes/brainav/main.js"></script>
  <script>
    feather.replace();
  </script>
</body>
</html>