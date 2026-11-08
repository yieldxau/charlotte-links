<?php
/**
 * Plugin Name: SMC Insights — Hero Bull Motion Fix
 * Description: Corrects the animated SVG bull motion on the SMC Insights homepage without modifying theme files.
 * Version: 1.0.0
 * Author: SMC Insights
 */
defined('ABSPATH') || exit;
add_action('wp_head', static function () {
    if (!is_front_page()) {
        return;
    }
    echo '<style id="smc-bull-motion-fix">
@keyframes smcBullGlide2026 {
  0%,100% { translate: 0 0; filter: drop-shadow(0 0 8px rgba(246,178,38,.55)); }
  50% { translate: 0 -6px; filter: drop-shadow(0 0 14px rgba(255,212,92,.78)); }
}
.smc26 .hero-art svg .gold-bull {
  animation: smcBullGlide2026 6s ease-in-out infinite !important;
  will-change: translate, filter;
}
@media (prefers-reduced-motion: reduce) {
  .smc26 .hero-art svg .gold-bull { animation: none !important; }
}
</style>' . "\n";
}, 99);
