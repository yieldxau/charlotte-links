<?php
/**
 * Plugin Name: SMC Insights — Sitewide Brand Logo
 * Description: Consistent canonical SMC Insights logo for home and interior headers, and matching favicon. Preserves header links and layout.
 * Version: 1.1.0
 * Author: SMC Insights
 */
defined('ABSPATH') || exit;
function smcbrand_icon_url(){return plugins_url('smc-brand-icon.svg',__FILE__);}
function smcbrand_logo_url(){return plugins_url('smc-brand-logo.svg',__FILE__);}
add_action('wp_head',function(){
 if(!has_site_icon()) echo '<link rel="icon" href="'.esc_url(smcbrand_icon_url()).'" type="image/svg+xml">'."\n";
 echo <<<'CSS'
<style id="smcbrand-unified">
.smc-brand-unified{display:inline-flex!important;align-items:center!important;gap:9px!important;text-decoration:none!important;max-width:100%!important;min-width:0!important}
.smc-brand-unified .smc-brand-graphic{display:block!important;width:190px!important;max-width:100%!important;height:51px!important;object-fit:contain!important;object-position:left center!important;flex-shrink:0!important;filter:none!important;transform:none!important;animation:none!important}
.smc-brand-unified > svg:not(.smc-brand-graphic),.smc-brand-unified > .brand-logo,.smc-brand-unified > .logo-mark,.smc-brand-unified > strong,.smc-brand-unified > span:not(.screen-reader-text){display:none!important}
.smc-brand-unified,.smc-brand-unified:hover{opacity:1!important}
@media(max-width:620px){.smc-brand-unified .smc-brand-graphic{width:153px!important;height:43px!important}}
</style>
CSS;
},75);
add_action('wp_footer',function(){
 $url=smcbrand_logo_url();
 echo '<script id="smcbrand-unified-init">(function(){var imgUrl='.wp_json_encode($url).';';
 echo <<<'JS'
function init(){
 var candidates=document.querySelectorAll(
  '.smc26 header a,.smc26 .site-header a,.smc26 .header a,'+
  '.smc-sub-nav > a,.wp-block-site-title a,.site-branding a,.ct-header-logo a,.site-logo a'
 );
 var matched=false;
 Array.prototype.forEach.call(candidates,function(a){
  if(matched||a.closest('footer'))return;
  if(a.classList.contains('smc-brand-unified'))return;
  var label=(a.textContent||'').replace(/\s+/g,' ').trim();
  var href=a.getAttribute('href')||'';
  var linkHome=(href==='/'||href==='#'||/^https?:\/\/[^/]+\/?$/.test(href));
  var explicit=/SMC\s*INSIGHTS/i.test(label)||/\bbrand\b|\blogo\b/i.test(a.className||'')||!!a.closest('.site-branding,.ct-header-logo,.site-logo');
  if(!explicit||(!linkHome && !/SMC\s*INSIGHTS/i.test(label)))return;
  a.classList.add('smc-brand-unified');
  a.setAttribute('aria-label','SMC Insights — Home');
  if(a.getAttribute('href')==='#')a.setAttribute('href','/');
  var graphic=document.createElement('img');
  graphic.className='smc-brand-graphic';
  graphic.src=imgUrl;
  graphic.alt='SMC Insights';
  graphic.width=190;
  graphic.height=51;
  graphic.decoding='async';
  a.insertBefore(graphic,a.firstChild);
  matched=true;
 });
}
if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',init,{once:true});
else init();
})();
JS;
 echo '</script>';
},50);
