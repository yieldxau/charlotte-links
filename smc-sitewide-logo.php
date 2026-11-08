<?php
/**
 * Plugin Name: SMC Insights — Sitewide Brand Logo
 * Description: Reuses the original homepage SVG logo in existing inner-page headers and supplies a matching favicon; no theme files or content changed.
 * Version: 1.0.0
 * Author: SMC Insights
 */
defined('ABSPATH') || exit;

function smcbrand_logo_svg() {
 static $cached = null;
 if ($cached !== null) return $cached;
 $template_file = get_theme_file_path('templates/front-page.html');
 $sources = array();
 if (is_readable($template_file)) $sources[] = file_get_contents($template_file);
 if (function_exists('get_block_template')) {
  $template = get_block_template(get_stylesheet() . '//front-page', 'wp_template');
  if ($template && !empty($template->content)) $sources[] = $template->content;
 }
 foreach ($sources as $source) {
  if (!$source) continue;
  if (preg_match('~<svg\b(?=[^>]*\bclass\s*=\s*["\'][^"\']*\bbrand-logo\b)[^>]*>.*?</svg>~is', $source, $found)) {
   $svg = trim($found[0]);
   // Only use the site owner's theme SVG; reject executable markup and external references.
   if (stripos($svg, '<script') === false && !preg_match('~\bon[a-z]+\s*=~i', $svg) && stripos($svg, '<foreignObject') === false) {
    $cached = $svg;
    return $cached;
   }
  }
 }
 // Fallback for themes that store their logo only in rendered dynamic content.
 $cached = '<svg class="brand-logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 110 78" aria-hidden="true"><g fill="#d6a34e"><rect x="11" y="34" width="10" height="28"/><rect x="27" y="24" width="10" height="38"/><rect x="43" y="13" width="10" height="49"/><rect x="59" y="7" width="10" height="55"/></g><path d="M8 69C48 71 82 44 100 7m-17 4 17-4-3 19" stroke="#e6bd70" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>';
 return $cached;
}
function smcbrand_favicon_url() {
 return plugins_url('smc-brand-icon.svg', __FILE__);
}
add_action('wp_head', function() {
 if (!has_site_icon()) {
  echo '<link rel="icon" type="image/svg+xml" href="' . esc_url(smcbrand_favicon_url()) . '">' . "\n";
 }
 echo <<<'CSS'
<style id="smc-sitewide-brand-style">
.smc-sub-nav>a.smc-sitewide-logo,
.smc-sub-nav>a.smc-sitewide-logo:first-child{
 display:inline-flex!important;
 align-items:center!important;
 gap:9px!important;
 text-decoration:none!important;
 max-width:100%!important;
}
.smc-sub-nav>a.smc-sitewide-logo svg.brand-logo{
 display:block!important;
 height:40px!important;
 width:56px!important;
 min-width:56px!important;
 flex:none!important;
 color:#d8ad5e!important;
 filter:drop-shadow(0 0 3px rgba(211,162,74,.27))!important;
 animation:none!important;
 transform:none!important;
}
.smc-sitewide-logo strong{
 font-family:Georgia,'Times New Roman',serif!important;
 font-size:clamp(18px,2vw,24px)!important;
 line-height:1!important;
 font-weight:800!important;
 letter-spacing:-.45px!important;
 white-space:nowrap!important;
 color:#f7f7f7!important;
}
.smc-sitewide-logo strong span{color:#e7ba6b!important}
@media(max-width:620px){
 .smc-sub-nav>a.smc-sitewide-logo svg.brand-logo{width:44px!important;min-width:44px!important;height:36px!important}
 .smc-sitewide-logo strong{font-size:20px!important}
}
</style>
CSS;
}, 60);

add_action('wp_footer', function() {
 // The homepage header already has the correct SVG. Only patch the inner-page text logos.
 if (is_front_page()) return;
 $logo = smcbrand_logo_svg();
 echo '<script id="smc-sitewide-brand-init">(function(){';
 echo 'var svg=' . wp_json_encode($logo, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_HEX_APOS) . ';';
 echo 'function init(){var nodes=document.querySelectorAll(".smc-sub-nav > a");';
 echo 'nodes.forEach(function(a){if(a.classList.contains("smc-sitewide-logo"))return;';
 echo 'if(!/SMC\s*Insights/i.test((a.textContent||"").trim()))return;';
 echo 'var temp=document.createElement("span");temp.innerHTML=svg;var graphic=temp.querySelector("svg");';
 echo 'if(!graphic)return;graphic.setAttribute("aria-hidden","true");a.insertBefore(graphic,a.firstChild);';
 echo 'var strong=a.querySelector("strong");if(strong){strong.innerHTML="SMC <span>Insights</span>";}';
 echo 'a.classList.add("smc-sitewide-logo");a.setAttribute("aria-label","SMC Insights — Home");';
 echo '});}if(document.readyState==="loading"){document.addEventListener("DOMContentLoaded",init,{once:true});}else{init();}})();</script>';
}, 30);
