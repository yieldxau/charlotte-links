<?php
/**
 * Plugin Name: SMC Insights — Community Links
 * Description: Centralized Join Community and Telegram destinations across SMC Insights frontend.
 * Version: 1.0.0
 * Author: SMC Insights
 */
defined('ABSPATH') || exit;
function smccl_community_url(){ return 'https://t.me/+0knPw1gxTb0zNTJk'; }
function smccl_telegram_url(){ return 'https://t.me/+xFDvVawMwVpmZTY8'; }
add_action('template_redirect',function(){
 if(is_admin()||wp_doing_ajax()||is_feed()||is_robots())return;
 if(defined('REST_REQUEST')&&REST_REQUEST)return;
 ob_start('smccl_replace_existing_links');
},2);
function smccl_replace_existing_links($html){
 if(!is_string($html)||strpos($html,'</html>')===false)return $html;
 $old='https://t.me/+1mT3BuELHoY2ODQ8';
 // Replace the exact original site channel URL before sending HTML to the client.
 // Buttons explicitly labelled Join Community receive Community; Telegram-labelled buttons receive Telegram.
 return preg_replace_callback('~<a\b[^>]*>.*?</a>~is',function($m)use($old){
  $a=$m[0];
  $plain=preg_replace('/\s+/',' ',trim(wp_strip_all_tags($a)));
  if(strpos($a,$old)===false)return $a;
  $community=(bool)preg_match('/\bcommunity\b/i',$plain);
  $telegram=(bool)preg_match('/\btelegram\b/i',$plain);
  $destination=($community && !$telegram)?smccl_community_url():smccl_telegram_url();
  return str_replace($old,esc_url($destination),$a);
 },$html);
}
add_shortcode('smc_join_community',function(){
 return '<a class="smc-community-link" href="'.esc_url(smccl_community_url()).'" target="_blank" rel="noopener noreferrer">Join Community</a>';
});
add_shortcode('smc_join_telegram',function(){
 return '<a class="smc-telegram-link" href="'.esc_url(smccl_telegram_url()).'" target="_blank" rel="noopener noreferrer">Join Telegram</a>';
});
