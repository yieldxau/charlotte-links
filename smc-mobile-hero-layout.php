<?php
/**
 * Plugin Name: SMC Insights — Mobile Hero Layout Fix
 * Description: Isolated mobile-only sizing fix for the existing SMC Insights homepage hero, SVG and responsive header. No theme template replacements.
 * Version: 1.0.0
 * Author: SMC Insights
 */
defined('ABSPATH') || exit;
add_action('wp_head', static function () {
 if (!is_front_page()) return;
 echo <<<'CSS'
<style id="smc-mobile-hero-layout-fix">
/* Keep all dimensions contained inside the device viewport. */
@media(max-width:767px) {
 html,body {max-width:100%;overflow-x:clip}
 .smc26,.smc26 * {box-sizing:border-box}
 .smc26 {width:100%!important;max-width:100vw!important;overflow-x:clip!important}
 .smc26 .topbar {
   display:flex!important;flex-direction:column!important;gap:7px!important;
   padding:8px 10px 9px!important;width:100%!important;max-width:100%!important;
   min-width:0!important;
 }
 .smc26 .brand {width:auto!important;max-width:100%!important;margin:0 auto!important;min-width:0!important}
 .smc26 .brandname {font-size:clamp(22px,6.2vw,31px)!important;line-height:1.05!important}
 .smc26 .brand small {font-size:9px!important;letter-spacing:0!important}
 .smc26 .nav {
   display:grid!important;grid-template-columns:repeat(3,minmax(0,1fr))!important;
   gap:5px!important;width:100%!important;min-width:0!important;max-width:100%!important;
   margin:0!important;
 }
 .smc26 .nav a {
   min-width:0!important;width:100%!important;min-height:49px!important;height:auto!important;
   padding:5px 3px!important;gap:3px!important;
   border-radius:8px!important;box-shadow:none!important;
   display:flex!important;flex-direction:column!important;align-items:center!important;justify-content:center!important;
 }
 .smc26 .nav a svg {width:16px!important;height:16px!important;flex:none!important}
 .smc26 .nav span {display:block!important;min-width:0!important;font-size:clamp(9px,2.6vw,11px)!important;line-height:1.12!important;text-align:center!important;white-space:normal!important}
 .smc26 .hero {
   position:relative!important;display:flex!important;flex-direction:column!important;
   height:auto!important;min-height:0!important;max-width:100%!important;width:100%!important;
   padding:0 0 7px!important;overflow:hidden!important;
   isolation:isolate!important;
 }
 .smc26 .hero-copy {
   display:block!important;position:relative!important;z-index:3!important;
   width:100%!important;max-width:100%!important;min-width:0!important;
   margin:0!important;padding:22px 15px 10px!important;
   transform:none!important;
 }
 .smc26 .hero h1 {
   max-width:100%!important;overflow-wrap:normal!important;
   font-size:clamp(37px,9.3vw,57px)!important;line-height:1.01!important;
   letter-spacing:-1.4px!important;margin-bottom:10px!important;
 }
 .smc26 .hero h1 strong {font-size:1.01em!important;letter-spacing:-1.3px!important}
 .smc26 .subtitle {max-width:100%!important;font-size:clamp(14px,4vw,18px)!important;line-height:1.24!important;margin:0 0 13px!important}
 .smc26 .chips {display:flex!important;flex-wrap:wrap!important;gap:6px!important;margin:0 0 12px!important;max-width:100%!important}
 .smc26 .chip {font-size:11px!important;padding:5px 8px!important;min-width:0!important}
 .smc26 .btns,.smc26 .hero-actions {display:flex!important;flex-wrap:wrap!important;gap:9px!important;max-width:100%!important}
 .smc26 .btn {font-size:13px!important;padding:10px 12px!important;min-width:0!important;max-width:100%!important}
 .smc26 .hero-art {
   position:relative!important;display:block!important;z-index:1!important;
   top:auto!important;bottom:auto!important;left:auto!important;right:auto!important;
   width:100%!important;max-width:100%!important;height:clamp(200px,59vw,310px)!important;
   min-width:0!important;min-height:0!important;flex:0 0 auto!important;
   margin:4px auto 0!important;padding:0!important;overflow:hidden!important;
   opacity:.82!important;transform:none!important;
   -webkit-mask-image:none!important;mask-image:none!important;
   contain:layout paint!important;
 }
 .smc26 .hero-art svg {
   display:block!important;position:relative!important;
   width:100%!important;max-width:100%!important;
   height:100%!important;max-height:100%!important;
   min-width:0!important;min-height:0!important;
   overflow:visible!important;
   transform:none!important;filter:none!important;
 }
 .smc26 .hero-art .gold-bull,
 .smc26 .hero-art svg .gold-bull {display:none!important}
 .smc26 .hero-art::before,.smc26 .hero-art::after {animation:none!important}
 .smc26 .ticker {width:100%!important;max-width:100%!important;overflow:hidden!important}
 .smc26 .panel,.smc26 .feature {max-width:100%!important;min-width:0!important}
}
@media(max-width:390px) {
 .smc26 .hero-copy {padding:18px 12px 8px!important}
 .smc26 .hero h1 {font-size:clamp(34px,9vw,39px)!important}
 .smc26 .nav span {font-size:9px!important}
 .smc26 .hero-art {height:215px!important}
 .smc26 .btn {font-size:12px!important;padding:9px 10px!important}
}
@media(prefers-reduced-motion:reduce) {
 .smc26 .hero-art * {animation:none!important}
}
</style>
CSS;
}, 200);
