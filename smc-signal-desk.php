<?php
/**
 * Plugin Name: SMC Insights — Signal Desk
 * Description: Isolated, manually moderated XAUUSD signal research desk with history and performance reporting. No automatic trade execution or cross-site dependencies.
 * Version: 1.0.0
 * Author: SMC Insights
 */
defined('ABSPATH') || exit;
function smcsd_register() {
 register_post_type('smcsd_signal', array(
   'labels'=>array('name'=>'Signal Desk','singular_name'=>'Signal','add_new_item'=>'Add Research Signal','edit_item'=>'Edit Research Signal'),
   'public'=>false,'show_ui'=>true,'show_in_menu'=>true,'menu_icon'=>'dashicons-chart-line',
   'supports'=>array('title','editor'),'capability_type'=>'post',
   'show_in_rest'=>false,'exclude_from_search'=>true,'rewrite'=>false
 ));
}
add_action('init','smcsd_register');
function smcsd_schema() {
 return array(
 'symbol'=>array('Symbol','text','XAUUSD'),
 'direction'=>array('Direction','select',array('BUY'=>'BUY','SELL'=>'SELL','NEUTRAL'=>'NEUTRAL')),
 'timeframe'=>array('Timeframe','select',array('M5'=>'M5','M15'=>'M15','H1'=>'H1','H4'=>'H4','D1'=>'D1')),
 'category'=>array('Category','select',array('SCALP'=>'Scalp','INTRADAY'=>'Intraday','SWING'=>'Swing','RESEARCH'=>'Research')),
 'entry'=>array('Entry','number',''),
 'stop'=>array('Stop Loss','number',''),
 'tp1'=>array('Target 1','number',''),
 'tp2'=>array('Target 2','number',''),
 'tp3'=>array('Target 3','number',''),
 'outcome'=>array('Outcome','select',array('PENDING'=>'Pending','ACTIVE'=>'Active','WIN'=>'Win','LOSS'=>'Loss','BREAKEVEN'=>'Breakeven','CANCELLED'=>'Cancelled')),
 'reason'=>array('Setup rationale','textarea',''),
 'source_time'=>array('Market data timestamp (UTC)','text',''),
 );
}
add_action('add_meta_boxes',function(){
 add_meta_box('smcsd_details','Signal Data & Verification','smcsd_metabox','smcsd_signal','normal','high');
});
function smcsd_metabox($post){
 wp_nonce_field('smcsd_save','smcsd_nonce');
 echo '<p style="color:#444">Publish only verified setups. The website never fabricates signals, prices, or outcomes. All fields are manual and require editorial review.</p><table class="form-table"><tbody>';
 foreach(smcsd_schema() as $name=>$s){
  $current=get_post_meta($post->ID,'_smcsd_'.$name,true);
  $id='smcsd_'.$name;
  echo '<tr><th><label for="'.esc_attr($id).'">'.esc_html($s[0]).'</label></th><td>';
  if($s[1]==='select'){
   echo '<select name="'.esc_attr($id).'" id="'.esc_attr($id).'">';
   foreach($s[2] as $v=>$label) echo '<option value="'.esc_attr($v).'"'.selected($current ?: array_key_first($s[2]),$v,false).'>'.esc_html($label).'</option>';
   echo '</select>';
  }elseif($s[1]==='textarea'){
   echo '<textarea id="'.esc_attr($id).'" name="'.esc_attr($id).'" rows="4" style="width:100%">'.esc_textarea($current).'</textarea>';
  }else{
   echo '<input id="'.esc_attr($id).'" name="'.esc_attr($id).'" type="'.esc_attr($s[1]).'"'.($s[1]==='number'?' step="0.001" min="0"':'').' value="'.esc_attr($current?:($name==='symbol'?'XAUUSD':'')).'" style="width:100%;max-width:470px">';
  }
  echo '</td></tr>';
 }
 echo '</tbody></table>';
}
add_action('save_post_smcsd_signal',function($id,$post){
 if(!isset($_POST['smcsd_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['smcsd_nonce'])),'smcsd_save'))return;
 if((defined('DOING_AUTOSAVE')&&DOING_AUTOSAVE)||wp_is_post_revision($id)||!current_user_can('edit_post',$id))return;
 foreach(smcsd_schema() as $name=>$s){
  $key='smcsd_'.$name;
  if(!isset($_POST[$key]))continue;
  $value=wp_unslash($_POST[$key]);
  if(is_array($value))continue;
  if($s[1]==='number'){
    $value=trim((string)$value);
    $value=($value!=='' && is_numeric($value) && (float)$value>=0) ? (string)round((float)$value,3) : '';
  }elseif($s[1]==='select'){
    $value=sanitize_key($value);
    $choices=array_change_key_case($s[2],CASE_LOWER);
    if(!array_key_exists(strtolower($value),$choices))continue;
    $value=strtoupper($value);
  }elseif($s[1]==='textarea') $value=sanitize_textarea_field($value);
  else $value=sanitize_text_field($value);
  update_post_meta($id,'_smcsd_'.$name,$value);
 }
},10,2);
register_activation_hook(__FILE__,function(){
 smcsd_register();
 if(!get_page_by_path('signal-desk',OBJECT,'page')) {
    $id=wp_insert_post(array(
      'post_type'=>'page','post_status'=>'publish',
      'post_title'=>'Signal Desk','post_name'=>'signal-desk',
      'post_content'=>'[smc_signal_desk]','comment_status'=>'closed',
    ),true);
    if(!is_wp_error($id) && $id)update_post_meta($id,'_smcsd_created','1');
 }
 flush_rewrite_rules(false);
});
add_action('wp_head',function(){
 if(!is_page('signal-desk'))return;
 echo <<<'CSS'
<style id="smcsd-theme">
.smcsd{--g:#e5ba72;--muted:#a0afbd;max-width:1190px;margin:0 auto 80px;padding:12px 20px 20px;color:#f4f5f6;font:15px/1.65 Inter,"Segoe UI",Arial,sans-serif}
.smcsd *{box-sizing:border-box}.smcsd h1,.smcsd h2,.smcsd h3{color:#f5f5f6;margin:0}.smcsd h1{font-size:clamp(39px,5vw,68px);line-height:1.06;letter-spacing:-1.8px;margin:14px 0 20px}.smcsd h1 span{color:var(--g)}.smcsd p{color:#b8c0c9}.smcsd a{color:var(--g);text-decoration:none}.smcsd .eyebrow{color:var(--g);font-size:11px;letter-spacing:2.1px;font-weight:900;text-transform:uppercase}.smcsd .hero{padding:47px clamp(20px,5vw,60px);background:radial-gradient(ellipse at 100% 10%,#bc82272e,transparent 50%),linear-gradient(125deg,#121d28,#080e14);border:1px solid #69563a;border-radius:17px;margin-bottom:20px;overflow:hidden}.smcsd .hero p{max-width:750px;font-size:16px;line-height:1.85}.smcsd .actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:18px}.smcsd .btn{color:#f6dfb5!important;border-radius:9px;border:1px solid #b99a64;background:#18232d;padding:10px 18px;font-weight:800;font-size:13px;display:inline-flex;align-items:center;justify-content:center;cursor:pointer}.smcsd .btn.primary{background:linear-gradient(130deg,#ffe0a3,#cf9744);color:#20160d!important}.smcsd .btn:hover{filter:brightness(1.12)}.smcsd .stats{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin:24px 0}.smcsd .stat{border:1px solid #394751;border-radius:11px;background:#121d27;padding:19px}.smcsd .stat small{font-size:11px;color:#a9b5c0;text-transform:uppercase;letter-spacing:1px}.smcsd .stat strong{display:block;font-size:33px;margin-top:3px;font-weight:800;color:#f2d095}.smcsd h2{font-size:27px;margin:27px 0 10px}.smcsd .filter{display:flex;flex-wrap:wrap;gap:8px;margin:18px 0}.smcsd .filter button{cursor:pointer;border:1px solid #53606c;border-radius:9px;background:#17212a;color:#d4dce3;padding:9px 14px;font-weight:750}.smcsd .filter button[aria-pressed=true]{color:#20180c;background:linear-gradient(120deg,#efd39a,#d6a550);border-color:#f4d7a2}.smcsd .signals{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}.smcsd .signal{padding:23px;border-radius:13px;border:1px solid #394852;background:linear-gradient(125deg,#151f29,#0a1118)}.smcsd .signal h3{font-size:18px}.smcsd .signal-top{display:flex;justify-content:space-between;align-items:start;gap:8px}.smcsd .badge{border:1px solid #8c7651;border-radius:6px;color:#ead1a5;background:#332b1f;padding:5px 9px;white-space:nowrap;font-weight:800;font-size:11px}.smcsd .badge.buy{color:#a5e7c9;border-color:#3c9a78;background:#17352c}.smcsd .badge.sell{color:#f0b3b3;border-color:#ad6565;background:#3a2428}.smcsd .levels{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin:18px 0}.smcsd .levels div{border:1px solid #3d4650;background:#0f171f;border-radius:8px;padding:12px}.smcsd .levels small{display:block;color:#98a9b6;font-size:10px;font-weight:750}.smcsd .levels strong{display:block;font-size:17px;color:#f1f2f2}.smcsd .signal .rationale{font-size:13px;line-height:1.7;color:#afbac4;white-space:pre-line}.smcsd .meta{font-size:11px;color:#9da9b5;margin:10px 0}.smcsd .empty{border:1px dashed #7a694e;background:#141b20;text-align:center;border-radius:13px;padding:45px 18px;color:#c6d0d9}.smcsd .notice{margin:25px 0 12px;border-left:3px solid var(--g);background:#1a2128;border-radius:0 10px 10px 0;padding:16px 20px;color:#c3cbd1;font-size:13px}.smcsd .bottomlinks{display:flex;gap:15px;flex-wrap:wrap;border-top:1px solid #37434c;padding:18px 0;margin-top:34px;font-size:13px}.smcsd .chartlink{margin:25px 0;border:1px solid #4a463f;border-radius:12px;padding:23px;background:linear-gradient(130deg,#1c2024,#0c141d)}.smcsd button:focus-visible,.smcsd a:focus-visible{outline:2px solid #f5d38a;outline-offset:3px}
@media(max-width:750px){.smcsd{padding:8px 14px}.smcsd .hero{padding:25px 17px}.smcsd .stats{grid-template-columns:repeat(2,1fr)}.smcsd .signals{grid-template-columns:1fr}.smcsd .levels{gap:5px}.smcsd .levels div{padding:9px}.smcsd .levels strong{font-size:14px}.smcsd .stat strong{font-size:27px}}
</style>
CSS;
});
function smcsd_sig_meta($id,$field) {return get_post_meta($id,'_smcsd_'.$field,true);}
function smcsd_format($v){return $v!==''?number_format((float)$v,2,'.',','):'—';}
add_shortcode('smc_signal_desk',function(){
 $signals=get_posts(array('post_type'=>'smcsd_signal','post_status'=>'publish','numberposts'=>120,'orderby'=>'date','order'=>'DESC','suppress_filters'=>false));
 $count=count($signals);$wins=0;$losses=0;$pending=0;$closed=0;
 foreach($signals as $post){
 $out=smcsd_sig_meta($post->ID,'outcome');
 if($out==='WIN'){$wins++;$closed++;}
 elseif($out==='LOSS'){$losses++;$closed++;}
 elseif($out==='PENDING'||$out==='ACTIVE'||$out===''){$pending++;}
 }
 $rate=($wins+$losses)>0?number_format($wins/($wins+$losses)*100,1).'%':'—';
 ob_start();?>
 <div class="smcsd"><section class="hero"><div class="eyebrow">SMC Insights · Gold Market Intelligence</div><h1>XAUUSD <span>Signal Desk</span></h1>
 <p>Research signal journal for Gold Spot: transparent entries, stops, targets, supporting rationale, and recorded outcomes. Editorially reviewed signals only. This module does not connect to accounts or execute trades.</p>
 <div class="actions"><a class="btn primary" href="<?php echo esc_url('https://www.tradingview.com/chart/?symbol=OANDA%3AXAUUSD');?>" target="_blank" rel="noopener noreferrer">Open XAUUSD Chart ↗</a><a class="btn" href="<?php echo esc_url('https://t.me/+1mT3BuELHoY2ODQ8');?>" target="_blank" rel="noopener noreferrer">Join Telegram ↗</a></div></section>
 <div class="stats" aria-label="Published signal outcome statistics"><div class="stat"><small>Published signals</small><strong><?php echo esc_html($count);?></strong></div><div class="stat"><small>Pending / Active</small><strong><?php echo esc_html($pending);?></strong></div><div class="stat"><small>Closed Wins</small><strong><?php echo esc_html($wins);?></strong></div><div class="stat"><small>Recorded Win Rate</small><strong><?php echo esc_html($rate);?></strong></div></div>
 <section aria-label="Research signals"><div class="eyebrow">Signal Archive</div><h2>Published Research Setups</h2>
 <div class="filter" role="group" aria-label="Filter signals"><button type="button" data-filter="ALL" aria-pressed="true">All</button><button type="button" data-filter="SCALP" aria-pressed="false">Scalp</button><button type="button" data-filter="INTRADAY" aria-pressed="false">Intraday</button><button type="button" data-filter="SWING" aria-pressed="false">Swing</button><button type="button" data-filter="RESEARCH" aria-pressed="false">Research</button></div>
 <?php if(!$signals):?><div class="empty"><h3>No verified signals published yet</h3><p>The signal desk is installed. A verified market-data feed and source strategy are required before automated signals can appear here.</p><p>There are no fabricated historical signals or performance claims.</p></div><?php else:?><div class="signals" id="smcsd-list">
 <?php foreach($signals as $post):
 $id=$post->ID;$direction=smcsd_sig_meta($id,'direction')?:'NEUTRAL';
 $category=smcsd_sig_meta($id,'category')?:'RESEARCH';
 $result=smcsd_sig_meta($id,'outcome')?:'PENDING';
 $timeframe=smcsd_sig_meta($id,'timeframe')?:'H1';
 ?>
 <article class="signal" data-category="<?php echo esc_attr($category);?>">
 <div class="signal-top"><div><div class="eyebrow"><?php echo esc_html($category.' · '.$timeframe);?></div><h3><?php echo esc_html(get_the_title($id));?></h3></div><span class="badge <?php echo esc_attr(strtolower($direction));?>"><?php echo esc_html($direction);?></span></div>
 <div class="levels"><div><small>ENTRY</small><strong><?php echo esc_html(smcsd_format(smcsd_sig_meta($id,'entry')));?></strong></div><div><small>STOP</small><strong><?php echo esc_html(smcsd_format(smcsd_sig_meta($id,'stop')));?></strong></div><div><small>TP1</small><strong><?php echo esc_html(smcsd_format(smcsd_sig_meta($id,'tp1')));?></strong></div></div>
 <p class="meta">Status: <?php echo esc_html($result);?> · Published: <?php echo esc_html(get_the_date('d M Y, H:i',$id));?> · Market timestamp: <?php echo esc_html(smcsd_sig_meta($id,'source_time')?:'Not provided');?></p>
 <?php $reason=smcsd_sig_meta($id,'reason');if($reason):?><p class="rationale"><?php echo esc_html($reason);?></p><?php endif;?>
 <?php if(smcsd_sig_meta($id,'tp2')!==''):?><p class="meta">TP2: <?php echo esc_html(smcsd_format(smcsd_sig_meta($id,'tp2')));?><?php if(smcsd_sig_meta($id,'tp3')!==''):?> · TP3: <?php echo esc_html(smcsd_format(smcsd_sig_meta($id,'tp3')));?><?php endif;?></p><?php endif;?>
 </article>
 <?php endforeach;?></div><p id="smcsd-no-filter" class="empty" hidden>No signals in the selected category.</p><?php endif;?></section>
 <section class="chartlink"><div class="eyebrow">Technical Research</div><h2>Review the Market Structure</h2><p>Use independent chart analysis before interpreting any signal. The TradingView chart is offered as an external source of market data and technical tools.</p><a class="btn primary" href="<?php echo esc_url('https://www.tradingview.com/chart/?symbol=OANDA%3AXAUUSD');?>" target="_blank" rel="noopener noreferrer">View Gold Chart ↗</a></section>
 <div class="notice"><strong>Data disclosure:</strong> All figures reflect the currently published, manually reviewed journal entries, not a verified automated performance backtest. Win rate excludes pending, cancelled and breakeven records. The signal engine and Telegram broadcasting are not connected to this module. Educational information only; leveraged gold trading involves substantial risk.</div>
 <nav class="bottomlinks"><a href="<?php echo esc_url(home_url('/xauusd-analysis/'));?>">XAUUSD Analysis ↗</a><a href="<?php echo esc_url(home_url('/smc-academy/'));?>">SMC Academy ↗</a><a href="<?php echo esc_url(home_url('/economic-calendar/'));?>">Economic Calendar ↗</a><a href="<?php echo esc_url(home_url('/'));?>">Home ↗</a></nav>
 </div>
 <script>(function(){var l=document.getElementById("smcsd-list");if(!l)return;var b=document.querySelectorAll(".smcsd .filter button");var none=document.getElementById("smcsd-no-filter");b.forEach(function(x){x.addEventListener("click",function(){var f=x.getAttribute("data-filter"),total=0;b.forEach(function(y){y.setAttribute("aria-pressed",String(y===x))});l.querySelectorAll(".signal").forEach(function(z){var show=f==="ALL"||z.getAttribute("data-category")===f;z.hidden=!show;if(show)total++});if(none)none.hidden=total!==0})})})();</script>
 <?php return ob_get_clean();
});
