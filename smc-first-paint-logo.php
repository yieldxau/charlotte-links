<?php
/**
 * Plugin Name: SMC Insights — First Paint Logo
 * Description: Render one canonical brand SVG directly in server HTML before first paint. No JavaScript logo replacement.
 * Version: 2.0.0
 * Author: SMC Insights
 */
defined('ABSPATH') || exit;
function smcfirst_logo_src(){return plugins_url('brand-logo.svg',__FILE__);}
function smcfirst_icon_src(){return plugins_url('brand-icon.svg',__FILE__);}
/**
 * Adapt logo-bearing header anchors while preserving classes, href, attributes,
 * link behavior, responsive menus and the rest of the original markup.
 */
function smcfirst_transform($html){
 if(!is_string($html)||stripos($html,'</html>')===false)return $html;
 if(stripos($html,'SMC')===false)return $html;
 $p=stripos($html,'<body');
 if($p===false)return $html;
 $prefix=substr($html,0,$p);
 $body=substr($html,$p);
 // Apply only to header/navigation region, never to article links, CTA or footer.
 $limit=min(strlen($body),140000);
 $section=substr($body,0,$limit);
 $replaced=0;
 $section=preg_replace_callback('~<a\b(?P<attrs>[^>]*?)>(?P<inner>(?:(?!</a>).)*?)</a>~is',function($m)use(&$replaced){
  if($replaced>=8)return $m[0];
  $attrs=$m['attrs'];
  $inner=$m['inner'];
  $plain=trim(preg_replace('/\s+/u',' ',html_entity_decode(wp_strip_all_tags($inner),ENT_QUOTES|ENT_HTML5,'UTF-8')));
  $matches_name=(bool)preg_match('/\bSMC\s*Insights\b/i',$plain);
  $has_brand=(bool)preg_match('/(?:brand|logo|site-title|site-name)/i',$attrs);
  $has_svg=(stripos($inner,'<svg')!==false);
  $is_header=$has_brand||$matches_name;
  if(!$is_header)return $m[0];
  // Branding only: never replace a body button that happens to mention the name.
  if(!$has_brand && !$has_svg && !preg_match('~^\s*<strong[^>]*>\s*SMC~i',$inner))return $m[0];
  // Guard against accidental replacement of links to unrelated external sites.
  if(preg_match('~\bhref\s*=\s*(["\'])(.*?)\1~is',$attrs,$href)){
    $link=html_entity_decode($href[2],ENT_QUOTES|ENT_HTML5,'UTF-8');
    if(preg_match('~^https?://~i',$link) && stripos($link,parse_url(home_url('/'),PHP_URL_HOST))===false)return $m[0];
  }
  if(stripos($inner,'smcfirst-canonical-logo')!==false)return $m[0];
  $replaced++;
  $graphic='<img class="smcfirst-canonical-logo" src="'.esc_url(smcfirst_logo_src()).'" width="190" height="51" alt="SMC Insights" decoding="sync" fetchpriority="high">';
  $safeAttrs=rtrim($attrs);
  $safeAttrs.=' data-smc-first-paint="true"';
  return '<a'.$safeAttrs.'>'.$graphic.'</a>';
 },$section);
 if($section===null)return $html;
 return $prefix.$section.substr($body,$limit);
}
add_action('template_redirect',function(){
 if(is_admin()||wp_doing_ajax()||is_feed()||is_robots()||is_trackback()||is_embed())return;
 if(defined('REST_REQUEST')&&REST_REQUEST)return;
 ob_start('smcfirst_transform');
},0);
add_action('wp_head',function(){
 echo '<link rel="preload" as="image" href="'.esc_url(smcfirst_logo_src()).'" type="image/svg+xml">'."\n";
 if(!has_site_icon())echo '<link rel="icon" href="'.esc_url(smcfirst_icon_src()).'" type="image/svg+xml">'."\n";
 echo <<<'CSS'
<style id="smcfirst-canonical">
a[data-smc-first-paint="true"]{display:inline-flex!important;align-items:center!important;max-width:100%!important;text-decoration:none!important;line-height:1!important}
a[data-smc-first-paint="true"] img.smcfirst-canonical-logo{display:block!important;width:190px!important;max-width:100%!important;height:51px!important;object-fit:contain!important;object-position:left center!important;flex:0 0 auto!important;transform:none!important;animation:none!important;filter:none!important}
@media(max-width:620px){a[data-smc-first-paint="true"] img.smcfirst-canonical-logo{width:153px!important;height:43px!important}}
</style>
CSS;
},1);
