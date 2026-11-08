<?php
/**
 * Plugin Name: SMC Insights — Live Design Refinement
 * Description: Reversible visual refinement for the existing SMC Insights homepage. Does not replace WordPress theme templates.
 * Version: 1.2.0
 * Author: SMC Insights
 */
defined('ABSPATH') || exit;
add_action('wp_head', static function () {
  if (!is_front_page()) { return; }
  echo <<<'STYLE'
<style id="smc-existing-design-refinement">

/* SMC Insights: live visual refinement of the EXISTING homepage.
   Completely reversible: disable this plugin. No theme template replacements. */

/* Restrained dark/gold palette, without expensive infinite light animations. */
.smc26 {
  background: radial-gradient(ellipse at 71% 8%,rgba(127,82,23,.13),transparent 44%),#080d12!important;
  color:#f3f5f7!important;
}
.smc26 .topbar {
  grid-template-columns:minmax(260px,340px) minmax(0,1fr)!important;
  gap:18px!important;
  padding:9px clamp(16px,2.2vw,38px)!important;
  background:linear-gradient(110deg,#090d12,#141a20 52%,#090d12)!important;
  border-bottom:1px solid #b68a43!important;
  box-shadow:0 2px 12px rgba(170,119,42,.22)!important;
}
.smc26 .brand-logo {
  width:62px!important;
  filter:drop-shadow(0 0 4px rgba(228,181,83,.35))!important;
}
.smc26 .brandname {font-family:Georgia,"Times New Roman",serif!important;font-size:clamp(25px,2.3vw,36px)!important;letter-spacing:-.9px!important}
.smc26 .brand small {font-size:10px!important;letter-spacing:.01em!important;color:#c0c6ce!important}
.smc26 .nav {gap:7px!important}
.smc26 .nav a {
  min-height:54px!important;height:54px!important;
  padding:5px 8px!important;
  background:linear-gradient(160deg,#1a232b,#0b1117)!important;
  border:1px solid #59636b!important;
  border-radius:9px!important;
  box-shadow:0 1px 0 rgba(213,169,91,.3)!important;
  transition:transform .2s ease,border-color .2s ease,background .2s ease!important;
}
.smc26 .nav a.active {
  background:linear-gradient(120deg,#f4d89c,#d8a247)!important;
  color:#16110a!important;
  border-color:#e9cb8c!important;
  box-shadow:0 2px 12px rgba(220,162,54,.26)!important;
}
.smc26 .nav a:hover,.smc26 .nav a:focus-visible {
  transform:translateY(-2px)!important;
  filter:none!important;
  border-color:#e3bc78!important;
  box-shadow:0 5px 14px rgba(198,141,55,.24)!important;
}
.smc26 .nav svg {height:20px!important;width:20px!important;color:#daba67!important}
.smc26 .nav a.active svg {color:#31210c!important}
.smc26 .nav span {font-size:clamp(10px,.91vw,14px)!important;letter-spacing:-.15px!important}
.smc26 .hero {
  min-height:410px!important;
  background:linear-gradient(107deg,#080c11 0%,#0b1118 44%,#20190f 100%)!important;
}
.smc26 .hero-grid {opacity:.075!important;mask-image:linear-gradient(90deg,transparent,#000 55%)!important}
.smc26 .hero:after {opacity:.2!important;filter:blur(28px)!important}
.smc26 .hero-copy {
  width:53%!important;
  max-width:770px!important;
  margin-left:clamp(24px,4.1vw,78px)!important;
  padding:39px 0 32px!important;
}
.smc26 .hero h1 {
  font-family:Georgia,"Times New Roman",serif!important;
  font-size:clamp(49px,4.55vw,77px)!important;
  font-style:normal!important;
  font-weight:850!important;
  line-height:1.03!important;
  letter-spacing:-1.6px!important;
  text-shadow:0 3px 14px rgba(0,0,0,.65)!important;
  margin:0 0 13px!important;
}
.smc26 .hero h1 strong {
  font-size:1.13em!important;
  line-height:1.01!important;
  font-style:italic!important;
  font-family:Georgia,"Times New Roman",serif!important;
  letter-spacing:-2px!important;
  background:linear-gradient(125deg,#ffe7a8,#e9b85c 52%,#b77e29)!important;
  -webkit-background-clip:text!important;background-clip:text!important;
  filter:none!important;
}
.smc26 .subtitle {
  color:#e7e9ed!important;
  font-size:clamp(16px,1.65vw,24px)!important;
  line-height:1.27!important;
  font-weight:650!important;
  margin:0 0 17px!important;
  text-shadow:none!important;
  max-width:620px!important;
}
.smc26 .hero-art {
  right:-2%!important;
  width:57%!important;
  opacity:.95!important;
  overflow:hidden!important;
  -webkit-mask-image:linear-gradient(90deg,transparent 1%,#000 22%,#000 100%)!important;
  mask-image:linear-gradient(90deg,transparent 1%,#000 22%,#000 100%)!important;
}
.smc26 .hero-art svg {filter:none!important}
.smc26 .hero-art svg .gold-bull {display:none!important;animation:none!important;filter:none!important}
.smc26 .hero-art::after {content:none!important;animation:none!important}
.smc26 .chip {
  background:linear-gradient(130deg,rgba(28,35,42,.97),rgba(8,13,19,.97))!important;
  border:1px solid #8a724d!important;
  border-radius:9px!important;
  box-shadow:none!important;
  padding:8px 12px!important;
  font-size:clamp(12px,1vw,16px)!important;
}
.smc26 .chip svg {width:19px!important;height:19px!important;stroke:#e5c477!important}
.smc26 .chips {gap:10px!important;margin-bottom:17px!important}
.smc26 .btn {
  animation:none!important;
  border-radius:10px!important;
  border:1px solid #d4a758!important;
  font-size:clamp(14px,1.18vw,18px)!important;
  padding:12px 22px!important;
  box-shadow:0 3px 13px rgba(195,143,54,.24)!important;
  text-shadow:none!important;
  transition:transform .2s ease,box-shadow .2s ease,filter .2s ease!important;
}
.smc26 .btn:not(.dark) {
  background:linear-gradient(120deg,#f4d28a,#d49a3c)!important;
  color:#1c1409!important;
}
.smc26 .btn.dark {
  background:linear-gradient(145deg,#18222b,#0b1219)!important;
  color:#f2f3f4!important;
}
.smc26 .btn:hover {
  filter:brightness(1.09)!important;
  transform:translateY(-2px)!important;
  box-shadow:0 5px 18px rgba(212,166,79,.32)!important;
}
.smc26 .ticker {
  min-height:54px!important;
  background:linear-gradient(180deg,#182029,#0d141b)!important;
  border-block:1px solid #6d665a!important;
  box-shadow:0 2px 12px rgba(0,0,0,.33)!important;
}
.smc26 .ticker-seg {font-size:clamp(12px,1.16vw,19px)!important;color:#e3e7eb!important}
.smc26 .ticker-seg small {color:#bcac8c!important}
.smc26 .panel {
  background:linear-gradient(140deg,#111a23,#091118)!important;
  border:1px solid #756d5f!important;
  box-shadow:0 4px 17px rgba(0,0,0,.38)!important;
  border-radius:14px!important;
}
.smc26 .panel:before {
  background:linear-gradient(90deg,transparent,rgba(235,193,118,.8),transparent)!important;
  box-shadow:none!important;height:1px!important;
}
.smc26 .analysis h2 {font-weight:750!important;border-bottom-color:#4e514c!important}
.smc26 .insight {border-bottom-color:#3b434a!important}
.smc26 .insight:hover {background:rgba(227,176,95,.08)!important}
.smc26 .feature {
  border:1px solid #807359!important;
  border-radius:13px!important;
  background:linear-gradient(140deg,#21242a,#0e151c 72%)!important;
  box-shadow:0 5px 14px rgba(0,0,0,.34)!important;
  padding:16px!important;
}
.smc26 .feature:hover {
  transform:translateY(-3px)!important;
  border-color:#daba78!important;
  box-shadow:0 8px 18px rgba(184,129,56,.22)!important;
}
.smc26 .feature svg {filter:none!important;color:#e5bf76!important}
.smc26 .feature strong {font-size:clamp(14px,1.3vw,21px)!important}
.smc26 .feature p {color:#c5cbd1!important;font-size:clamp(11px,.94vw,15px)!important}
.smc26 .foot {border-top-color:#343c43!important}
@media(max-width:1240px){
  .smc26 .topbar {grid-template-columns:minmax(230px,290px) minmax(0,1fr)!important;gap:9px!important;padding:8px 14px!important}
  .smc26 .brand-logo {width:51px!important}
  .smc26 .brandname {font-size:27px!important}
  .smc26 .nav a {min-height:50px!important;height:50px!important;padding:3px 5px!important}
  .smc26 .nav span {font-size:11px!important}
  .smc26 .hero-copy {width:56%!important}
  .smc26 .hero h1 {font-size:clamp(47px,4.5vw,66px)!important}
}
@media(max-width:1000px){
  .smc26 .topbar {display:block!important}
  .smc26 .brand {width:max-content!important;max-width:100%!important;margin:0 auto 8px!important}
  .smc26 .hero {min-height:510px!important}
  .smc26 .hero-copy {width:69%!important;padding:30px 0 22px!important}
  .smc26 .hero-art {width:65%!important;right:-13%!important;opacity:.74!important}
  .smc26 .hero h1 {font-size:clamp(45px,6vw,60px)!important}
  .smc26 .nav a {min-height:48px!important;height:48px!important}
}
@media(max-width:680px){
  .smc26 .topbar {padding:8px 10px!important}
  .smc26 .nav {grid-template-columns:repeat(3,minmax(0,1fr))!important;gap:6px!important}
  .smc26 .nav a {min-height:44px!important;height:44px!important}
  .smc26 .nav span {font-size:10px!important}
  .smc26 .nav svg {height:17px!important;width:17px!important}
  .smc26 .hero {min-height:620px!important}
  .smc26 .hero-copy {width:100%!important;margin:0!important;padding:27px 17px 0!important}
  .smc26 .hero h1 {font-size:clamp(40px,10vw,60px)!important}
  .smc26 .hero h1 strong {font-size:1.06em!important;letter-spacing:-1.5px!important}
  .smc26 .subtitle {font-size:clamp(15px,4.1vw,19px)!important;line-height:1.24!important}
  .smc26 .hero-art {
    top:auto!important;bottom:0!important;right:-22%!important;
    height:285px!important;width:115%!important;opacity:.82!important;
  }
  .smc26 .chip {font-size:11px!important;padding:6px 8px!important}
  .smc26 .chip svg {width:15px!important;height:15px!important}
  .smc26 .btn {padding:10px 15px!important;font-size:13px!important}
  .smc26 .ticker {grid-template-columns:1fr 1fr!important;gap:7px!important}
  .smc26 .ticker-seg {font-size:12px!important}
  .smc26 .feature {min-height:104px!important;padding:10px!important}
}
@media(prefers-reduced-motion:reduce) {
  .smc26 .btn,.smc26 .nav a,.smc26 .feature {animation:none!important;transition:none!important}
}

</style>
STYLE;
}, 99);
