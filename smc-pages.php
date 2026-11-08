<?php
/**
 * Plugin Name: SMC Insights — Branded Pages
 * Description: Self-contained pages for XAUUSD Analysis, SMC Academy, Economic Calendar, About and Contact. Does not modify the theme or existing WordPress content.
 * Version: 1.0.0
 * Author: SMC Insights
 */
defined('ABSPATH') || exit;
function smcpg_definitions() {
 return array(
  'xauusd-analysis' => 'XAUUSD Analysis',
  'smc-academy' => 'SMC Academy',
  'economic-calendar' => 'Economic Calendar',
  'about' => 'About',
  'contact' => 'Contact',
 );
}
register_activation_hook(__FILE__, function () {
 foreach (smcpg_definitions() as $slug => $title) {
   if (get_page_by_path($slug, OBJECT, 'page')) continue;
   $post_id = wp_insert_post(array(
      'post_title' => $title,
      'post_name' => $slug,
      'post_type' => 'page',
      'post_status' => 'publish',
      'post_content' => '<!-- SMC Insights branded page. Managed visually by SMC Insights Branded Pages plugin. -->',
      'comment_status' => 'closed',
   ), true);
   if (!is_wp_error($post_id) && $post_id) update_post_meta($post_id, '_smcpg_created', '1');
 }
 flush_rewrite_rules(false);
});
add_filter('the_content', function ($content) {
 if (is_admin() || !is_singular('page') || !in_the_loop() || !is_main_query()) return $content;
 $p = get_post();
 if (!$p) return $content;
 $slug = $p->post_name;
 if (!array_key_exists($slug, smcpg_definitions())) return $content;
 return smcpg_render($slug);
}, 98);
add_action('wp_head', function () {
 if (!is_page(array_keys(smcpg_definitions()))) return;
 echo <<<'CSS'
<style id="smcpg-styles">
.smcpg{--g:#dfb66f;--fg:#f2f4f6;--muted:#a2adb7;--line:#35414d;max-width:1210px;margin:0 auto 70px;padding:0 20px;color:var(--fg);font-family:Inter,"Segoe UI",Arial,sans-serif;line-height:1.65}
.smcpg *{box-sizing:border-box}.smcpg a{text-decoration:none}.smcpg h1,.smcpg h2,.smcpg h3,.smcpg p{margin-top:0}.smcpg h1{font-size:clamp(40px,5.5vw,70px);line-height:1.05;letter-spacing:-2.2px;margin:16px 0 20px;font-weight:850;color:#f6f7f9}.smcpg h1 em{font-style:normal;color:var(--g)}.smcpg h2{font-size:clamp(25px,2.8vw,38px);letter-spacing:-.9px;color:#fff;line-height:1.19}.smcpg h3{font-size:20px;line-height:1.25;color:#f4f5f8}.smcpg p{color:#b1bbc6}.smcpg small{color:#93a1ae}.smcpg .label{font-size:11px;font-weight:800;letter-spacing:2.2px;color:var(--g);text-transform:uppercase}.smcpg .lead{font-size:clamp(16px,1.8vw,20px);max-width:770px;line-height:1.8}.smcpg .smcpg-nav{display:flex;gap:7px;flex-wrap:wrap;padding:14px 0 24px;margin-top:8px}.smcpg-nav a{border:1px solid #384450;background:#101923;border-radius:7px;color:#c9d1da!important;font-size:12px;font-weight:700;padding:7px 12px}.smcpg-nav a:hover,.smcpg-nav a.current{background:#42321e;border-color:#d5a451;color:#ffdc98!important}.smcpg-hero{border:1px solid #534a3c;border-radius:18px;padding:clamp(25px,5vw,66px);background:radial-gradient(ellipse at 100% 0,rgba(224,164,55,.17),transparent 50%),linear-gradient(110deg,#111922,#0a0f15);margin-bottom:34px;position:relative;overflow:hidden}.smcpg .actions{display:flex;flex-wrap:wrap;gap:12px;margin:25px 0 0}.smcpg .action{display:inline-flex;align-items:center;justify-content:center;text-align:center;border:1px solid #ccaa71;border-radius:9px;padding:12px 19px;font-size:13px;font-weight:800;background:#17212b;color:#f6e9d3!important;transition:transform .15s,border-color .15s}.smcpg .action:hover{transform:translateY(-2px);border-color:#ffe1a7}.smcpg .action.primary{background:linear-gradient(130deg,#ffdea0,#c89745);color:#201509!important}.smcpg .section{margin:48px 0}.smcpg .section-intro{max-width:790px}.smcpg .cards{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}.smcpg .cards.two{grid-template-columns:repeat(2,minmax(0,1fr))}.smcpg .card{border:1px solid #36414c;border-radius:13px;background:linear-gradient(145deg,#151f29,#0b1219);padding:24px;min-width:0}.smcpg .card .number{color:var(--g);font-size:12px;letter-spacing:2px;font-weight:850;margin-bottom:13px}.smcpg .card p{margin:0;line-height:1.75;font-size:14px}.smcpg .card h3{margin:0 0 11px}.smcpg .card a{color:#f4c97d}.smcpg .dark-note{padding:20px;border:1px solid #5c5242;border-radius:12px;background:#181b1d;color:#c5c9cc;font-size:13px;line-height:1.8}.smcpg .dark-note strong{color:#f0cc90}.smcpg .steps{display:grid;gap:10px}.smcpg details{border:1px solid #36414c;background:#101922;border-radius:11px;padding:0 19px}.smcpg details summary{cursor:pointer;font-weight:800;font-size:16px;padding:18px 0;color:#f2f4f7}.smcpg details summary::marker{color:#e4b96e}.smcpg details p{padding-bottom:15px;font-size:14px;margin:0}.smcpg .tablebox{overflow-x:auto;border:1px solid #34404b;border-radius:12px}.smcpg table{width:100%;border-collapse:collapse;min-width:590px;text-align:left}.smcpg th{background:#26313c;color:#f2cc8d}.smcpg th,.smcpg td{border-bottom:1px solid #34404b;padding:14px;font-size:13px}.smcpg td{color:#c0cad4}.smcpg .external{font-size:12px;color:#d6aa66!important;font-weight:750}.smcpg .footer-nav{border-top:1px solid #34404b;padding-top:20px;margin-top:54px;display:flex;align-items:center;gap:15px;flex-wrap:wrap;font-size:12px;color:#a7b2bf}.smcpg .footer-nav a{color:#d6aa66}.smcpg .callout{border-left:3px solid var(--g);padding:12px 18px;background:#1a2027;border-radius:0 10px 10px 0;margin:24px 0;color:#d9e0e5}.smcpg .twchart{min-height:340px;display:grid;place-items:center;border:1px solid #3b4651;border-radius:12px;background:repeating-linear-gradient(0deg,#111c24 0,#111c24 54px,#22313b 55px),#121b24;padding:20px;text-align:center;overflow:hidden}.smcpg .twchart p{max-width:520px}.smcpg .twchart iframe{display:block;border:0;width:100%;min-height:490px}.smcpg .mini-links{display:flex;gap:13px;flex-wrap:wrap;margin-top:16px}.smcpg .mini-links a{font-weight:750;color:#f4c97d!important;border-bottom:1px solid #98723f}.smcpg button.action{cursor:pointer}.smcpg button:focus-visible,.smcpg a:focus-visible{outline:2px solid #f0d399;outline-offset:3px}
@media(max-width:740px){.smcpg{padding:0 14px;margin-bottom:40px}.smcpg .cards,.smcpg .cards.two{grid-template-columns:1fr}.smcpg .card{padding:19px}.smcpg .smcpg-nav{gap:5px}.smcpg-nav a{padding:7px 9px;font-size:11px}.smcpg .section{margin:34px 0}.smcpg-hero{padding:28px 20px}.smcpg .twchart iframe{min-height:420px}.smcpg h1{letter-spacing:-1.3px}}
@media(prefers-reduced-motion:reduce){.smcpg *, .smcpg *:before, .smcpg *:after{transition:none!important;animation:none!important}}
</style>
CSS;
});
function smcpg_nav($slug) {
 $links = array('home'=>array('Home',home_url('/')));
 foreach (smcpg_definitions() as $k=>$title) $links[$k]=array($title,home_url('/'.$k.'/'));
 $out='<nav class="smcpg-nav" aria-label="SMC Insights pages">';
 foreach($links as $key=>$item) $out.='<a'.($key===$slug?' class="current" aria-current="page"':'').' href="'.esc_url($item[1]).'">'.esc_html($item[0]).'</a>';
 return $out.'</nav>';
}
function smcpg_card($n,$title,$desc) {
 return '<article class="card"><div class="number">'.esc_html($n).'</div><h3>'.esc_html($title).'</h3><p>'.esc_html($desc).'</p></article>';
}
function smcpg_render($slug) {
 $telegram='https://t.me/+1mT3BuELHoY2ODQ8';
 $tradingview='https://www.tradingview.com/chart/?symbol=OANDA%3AXAUUSD';
 $nav=smcpg_nav($slug);
 $footer='<div class="footer-nav"><span>SMC INSIGHTS · GOLD MARKET INTELLIGENCE</span><a href="'.esc_url(home_url('/contact/')).'">Contact</a><span>Educational information only. Not investment advice.</span></div>';
 $body='';
 switch ($slug) {
 case 'xauusd-analysis':
 $body='<section class="smcpg-hero"><span class="label">Gold Market Intelligence · Technical Research</span><h1>XAUUSD <em>Analysis</em></h1><p class="lead">A structured way to interpret gold-market price action using ICT and Smart Money Concepts. Start with higher-timeframe context, map liquidity, then define conditional scenarios rather than guessing direction.</p><div class="actions"><a class="action primary" href="#xauusd-chart">Explore XAUUSD Chart</a><a class="action" href="'.esc_url($telegram).'" target="_blank" rel="noopener noreferrer">Join Telegram Community ↗</a></div></section>';
 $body.='<section class="section" id="xauusd-chart"><span class="label">XAUUSD · Chart on demand</span><h2>Gold Spot / U.S. Dollar</h2><p class="section-intro">Interactive TradingView chart is loaded only when you request it. No invented price quotes, entry signals or automated trade advice.</p><div class="twchart" id="smcpg-chart"><div><h3>Ready to examine the structure?</h3><p>Load the TradingView chart for XAUUSD. Chart data, when available, is supplied by the chart provider.</p><button class="action primary" type="button" id="smcpg-load-chart">Load Interactive Chart</button><p style="margin:17px 0 0"><a class="external" target="_blank" rel="noopener noreferrer" href="'.esc_url($tradingview).'">Open full TradingView chart ↗</a></p></div></div></section>';
 $body.='<section class="section"><span class="label">Research Methodology</span><h2>Top-down market framework</h2><div class="cards">';
 $body.=smcpg_card('01 / MARKET CONTEXT','Higher-timeframe bias','Start with weekly and daily swing structure, key premium/discount zones and macroeconomic context before considering intraday opportunities.');
 $body.=smcpg_card('02 / LIQUIDITY','Liquidity & displacement','Mark external and internal liquidity, evaluate sweeps, and distinguish sustained displacement from temporary volatility.');
 $body.=smcpg_card('03 / EXECUTION','Confirmation & risk','Use lower-timeframe structure shifts and fair value gaps as research tools. Define invalidation and risk before any trade.');
 $body.='</div></section><section class="section"><h2>Scenario planning checklist</h2><div class="tablebox"><table><thead><tr><th>Context</th><th>What to assess</th><th>What changes the thesis</th></tr></thead><tbody><tr><td>Premium zone</td><td>Rejection after buy-side liquidity sweep</td><td>Strong acceptance above resistance</td></tr><tr><td>Discount zone</td><td>Sell-side sweep followed by displacement</td><td>Fresh breakdown below invalidation</td></tr><tr><td>High-impact news</td><td>Spread, volatility and market response</td><td>Unexpected repricing or new structure</td></tr></tbody></table></div></section>';
 $body.='<div class="dark-note"><strong>Research disclaimer:</strong> These are analytical examples, not active trading signals. Gold carries substantial risk; past patterns do not guarantee future outcomes.</div>';
 $body.='<script>(function(){var b=document.getElementById("smcpg-load-chart");if(!b)return;b.addEventListener("click",function(){var area=document.getElementById("smcpg-chart");area.innerHTML="";area.style.padding="0";area.style.display="block";area.style.minHeight="490px";var iframe=document.createElement("iframe");iframe.title="TradingView XAUUSD advanced chart";iframe.loading="lazy";iframe.referrerPolicy="strict-origin-when-cross-origin";iframe.src="https://www.tradingview.com/widgetembed/?frameElementId=smcpg-chart&symbol=OANDA%3AXAUUSD&interval=240&theme=dark&style=1";iframe.style.width="100%";iframe.style.height="490px";iframe.style.border="0";area.appendChild(iframe);},{once:true});})();</script>';
 break;
 case 'smc-academy':
 $body='<section class="smcpg-hero"><span class="label">ICT &amp; Smart Money Concepts · Education</span><h1>The SMC <em>Academy</em></h1><p class="lead">Learn how market structure, liquidity and displacement fit together. Work through the concepts in sequence instead of relying on isolated chart patterns.</p><div class="actions"><a class="action primary" href="#lessons">Explore Lessons</a><a class="action" href="'.esc_url(home_url('/xauusd-analysis/')).'">Apply on Gold Charts ↗</a></div></section>';
 $lessons=array(
 array('01 · Foundations','What is Smart Money Concepts?','SMC is a framework for interpreting swings, liquidity and price delivery. Start by marking clear swing highs and lows before adding indicators.'),
 array('02 · Market Structure','BOS vs CHoCH','A break of structure (BOS) is often used to track continuation; a change of character (CHoCH) suggests potential transition. Neither is reliable without surrounding context.'),
 array('03 · Liquidity','Buy-side and sell-side liquidity','Clustered stops often sit above prominent highs and below prominent lows. A liquidity sweep alone does not guarantee a reversal.'),
 array('04 · Displacement','Momentum and imbalance','An impulsive candle sequence can expose inefficiency. Evaluate what triggered the move, what structure it broke, and whether price accepts the new range.'),
 array('05 · Fair Value Gaps','Finding and validating FVGs','A three-candle imbalance may be used as a potential reaction zone. Seek directional context and invalidation, not automatic entries.'),
 array('06 · Order Blocks','Supply and demand logic','Consider the order block in relation to displacement, structure and liquidity. Avoid labeling every opposing candle an institutional order block.'),
 array('07 · Multi-timeframe','Top-down analysis for XAUUSD','Build context on higher timeframes; then study lower timeframes for timing, confirmation and manageable invalidation.'),
 array('08 · Risk Management','Plan, measure and review','Define maximum risk, stop conditions, trade journaling and post-session review. Educational setups are not guaranteed outcomes.')
 );
 $body.='<section class="section" id="lessons"><span class="label">Structured Curriculum</span><h2>Eight core lessons</h2><p class="section-intro">Select a topic to open its explanation.</p><div class="steps">';
 foreach($lessons as $lesson) $body.='<details><summary>'.esc_html($lesson[0]).' — '.esc_html($lesson[1]).'</summary><p>'.esc_html($lesson[2]).'</p></details>';
 $body.='</div></section><section class="section"><h2>Practice the concepts</h2><div class="cards two">'.smcpg_card('PRACTICE A','Structure mapping','Open a historical 4H gold chart; label significant swing highs/lows and then compare it with 1H structure.').smcpg_card('PRACTICE B','Liquidity review','Identify a major high or low and study how price reacts before and after it. Record invalidation conditions, not predictions.').'</div><div class="actions"><a href="'.esc_url($tradingview).'" class="action primary" target="_blank" rel="noopener noreferrer">Open XAUUSD TradingView ↗</a><a href="'.esc_url(home_url('/economic-calendar/')).'" class="action">Economic Calendar</a></div></section>';
 break;
 case 'economic-calendar':
 $body='<section class="smcpg-hero"><span class="label">Macroeconomic Events · Gold Volatility</span><h1>Economic <em>Calendar</em></h1><p class="lead">Major economic releases can influence XAUUSD volatility, the U.S. dollar and interest-rate expectations. Use verified calendars for exact release times and consensus estimates.</p><div class="actions"><a class="action primary" href="https://www.tradingview.com/economic-calendar/" target="_blank" rel="noopener noreferrer">Open Live Economic Calendar ↗</a><a class="action" href="#events">Understand Key Events</a></div></section>';
 $body.='<section class="section" id="events"><span class="label">Events That Matter</span><h2>Important macro releases</h2><div class="cards">';
 $body.=smcpg_card('USA · LABOR','Nonfarm Payrolls (NFP)','Employment growth, wages and unemployment can shift monetary-policy expectations and near-term gold volatility.');
 $body.=smcpg_card('USA · INFLATION','CPI / PCE Inflation','Consumer inflation releases influence real-yield expectations, the U.S. dollar and interest-rate outlook.');
 $body.=smcpg_card('USA · MONETARY POLICY','FOMC / Fed Decisions','Rate decisions, projections and press conferences can cause sharp price moves and temporary liquidity disruptions.');
 $body.='</div></section><section class="section"><h2>Verified calendar sources</h2><div class="cards two"><article class="card"><div class="number">ECONOMIC CALENDAR</div><h3>TradingView Events</h3><p>View scheduled releases, forecasts and previously reported data using the publisher\'s calendar.</p><div class="mini-links"><a href="https://www.tradingview.com/economic-calendar/" target="_blank" rel="noopener noreferrer">Open Calendar ↗</a></div></article><article class="card"><div class="number">CENTRAL BANK</div><h3>Federal Reserve Calendar</h3><p>Official U.S. Federal Reserve meeting information and monetary-policy releases.</p><div class="mini-links"><a href="https://www.federalreserve.gov/monetarypolicy/fomccalendars.htm" target="_blank" rel="noopener noreferrer">View Fed Schedule ↗</a></div></article></div></section><div class="dark-note"><strong>Before high-impact releases:</strong> Verify timezone, watch spreads, avoid assuming a direction from a headline alone, and consider the possibility of sharp two-way volatility.</div>';
 break;
 case 'about':
 $body='<section class="smcpg-hero"><span class="label">About · Gold Market Intelligence</span><h1>Built for <em>clarity.</em></h1><p class="lead">SMC Insights focuses on research-driven XAUUSD education, price structure and macroeconomic context. Our goal is to help traders ask better questions about the market—not promote certainty where none exists.</p><div class="actions"><a href="'.esc_url(home_url('/smc-academy/')).'" class="action primary">Explore the Academy</a><a href="'.esc_url(home_url('/contact/')).'" class="action">Contact / Community ↗</a></div></section>';
 $body.='<section class="section"><span class="label">Our approach</span><h2>Structure. Evidence. Discipline.</h2><div class="cards">';
 $body.=smcpg_card('01 / RESEARCH','Gold market focus','XAUUSD is assessed through market structure, price delivery and the influence of macroeconomic events.');
 $body.=smcpg_card('02 / EDUCATION','Transparent methods','We explain concepts such as liquidity, fair value gaps, BOS and CHoCH without portraying illustrations as live signals.');
 $body.=smcpg_card('03 / PROCESS','Risk-aware thinking','Clear invalidation, scenario planning and independent decision-making matter more than promises or predictions.');
 $body.='</div></section><section class="section"><h2>What you can explore</h2><div class="cards two"><article class="card"><h3>Market research</h3><p>Technical structure, liquidity questions and scenario frameworks for XAUUSD.</p><div class="mini-links"><a href="'.esc_url(home_url('/xauusd-analysis/')).'">Gold Analysis ↗</a></div></article><article class="card"><h3>Market education</h3><p>Practical lessons covering ICT, SMC and macroeconomic event awareness.</p><div class="mini-links"><a href="'.esc_url(home_url('/smc-academy/')).'">SMC Academy ↗</a></div></article></div></section><div class="dark-note">SMC Insights provides market commentary and educational information, not personalized investment advice or guarantees of financial performance.</div>';
 break;
 case 'contact':
 $body='<section class="smcpg-hero"><span class="label">Community · Get in touch</span><h1>Connect with <em>SMC Insights</em></h1><p class="lead">Join our gold market community to follow educational analysis and discussion around institutional price structure.</p><div class="actions"><a class="action primary" href="'.esc_url($telegram).'" target="_blank" rel="noopener noreferrer">Join Telegram Channel ↗</a><a class="action" href="'.esc_url(home_url('/about/')).'">About SMC Insights</a></div></section>';
 $body.='<section class="section"><div class="cards two"><article class="card"><div class="number">TELEGRAM</div><h3>Gold Market Community</h3><p>Stay connected with XAUUSD discussion, educational market ideas and research updates.</p><div class="mini-links"><a href="'.esc_url($telegram).'" target="_blank" rel="noopener noreferrer">Open Telegram ↗</a></div></article><article class="card"><div class="number">WEBSITE</div><h3>Explore Our Research</h3><p>Browse educational analysis and trading concepts on the SMC Insights website.</p><div class="mini-links"><a href="'.esc_url(home_url('/xauusd-analysis/')).'">Visit Analysis ↗</a></div></article></div></section><div class="dark-note">Do not share account passwords, brokerage credentials, identification documents or sensitive financial details in public channel discussions.</div>';
 break;
 }
 return '<div class="smcpg">'.$nav.$body.$footer.'</div>';
}
