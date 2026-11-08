<?php
/**
 * Plugin Name: SMC Insights — Hero Bull Motion Fix
 * Description: Keeps the SVG bull in frame, replaces bouncing with a subtle gold aura, and reduces costly SVG filtering.
 * Version: 1.1.0
 * Author: SMC Insights
 */
defined('ABSPATH') || exit;
add_action('wp_head', static function () {
    if (!is_front_page()) { return; }
    echo <<<'STYLE'
<style id="smc-bull-motion-fix">
/* Keep the original vector artwork while avoiding repeated full-scene GPU filters. */
.smc26 .hero-art svg { filter: none !important; }
.smc26 .hero-art svg .gold-bull {
  animation: none !important;
  translate: -72px -37px;
  scale: .81;
  transform-box: fill-box;
  transform-origin: center center;
  filter: drop-shadow(0 0 4px rgba(255,187,39,.6));
  will-change: auto !important;
}
/* Gentle aura behind the bull. Only opacity animates: no bouncing or shape distortion. */
@keyframes smcGoldAura2026 { 0%,100% {opacity:.22;} 50% {opacity:.65;} }
.smc26 .hero-art::after {
  content: "";
  position: absolute;
  left: 40%;
  top: 21%;
  width: 49%;
  height: 69%;
  pointer-events: none;
  border-radius: 50%;
  background: radial-gradient(ellipse,rgba(255,174,26,.21) 0%,rgba(255,176,18,.08) 38%,transparent 72%);
  animation: smcGoldAura2026 7s ease-in-out infinite;
}
@media(max-width:680px) {
 .smc26 .hero-art svg .gold-bull { translate: -36px -20px; scale: .78; }
 .smc26 .hero-art::after { left:33%;top:14%; }
}
@media(prefers-reduced-motion:reduce) {
 .smc26 .hero-art::after { animation: none !important;opacity:.38; }
}
</style>
STYLE;
}, 99);
