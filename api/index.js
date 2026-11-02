const config = require('../config.json');
const esc = value => String(value || '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
module.exports = (req, res) => {
  res.setHeader('Cache-Control', 'no-store, max-age=0');
  res.setHeader('CDN-Cache-Control', 'no-store');
  res.setHeader('Vercel-CDN-Cache-Control', 'no-store');
  res.setHeader('Content-Type', 'text/html; charset=utf-8');
  res.setHeader('X-Content-Type-Options', 'nosniff');
  let destination;
  try { destination = new URL(config.destination); if (!['https:', 'http:'].includes(destination.protocol)) throw Error(); }
  catch { res.statusCode = 503; return res.end('Website link is temporarily unavailable.'); }
  const url = destination.href;
  const image = config.thumbnail ? new URL(config.thumbnail, config.publicUrl).href : '';
  const delay = Math.min(10000, Math.max(500, Number(config.redirectDelayMs) || 1000));
  const scriptUrl = JSON.stringify(url).replace(/</g, '\\u003c');
  res.statusCode = 200;
  res.end(`<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>${esc(config.title)}</title><meta name="description" content="${esc(config.description)}">
<meta property="og:type" content="website"><meta property="og:url" content="${esc(config.publicUrl)}">
<meta property="og:title" content="${esc(config.title)}"><meta property="og:description" content="${esc(config.description)}">
<meta name="twitter:card" content="${image ? 'summary_large_image' : 'summary'}"><meta name="twitter:title" content="${esc(config.title)}"><meta name="twitter:description" content="${esc(config.description)}">
${image ? '<meta property="og:image" content="'+esc(image)+'"><meta property="og:image:alt" content="'+esc(config.thumbnailAlt)+'"><meta name="twitter:image" content="'+esc(image)+'">' : ''}
<style>body{margin:0;min-height:100vh;display:grid;place-items:center;background:#0d1321;color:#eef2ff;font:18px system-ui}main{max-width:560px;padding:40px;text-align:center}p{color:#b8c1d9;line-height:1.6}a{display:inline-block;padding:14px 24px;border-radius:12px;background:#a5b4fc;color:#111827;font-weight:700;text-decoration:none}small{display:block;margin-top:24px;color:#b8c1d9}</style></head>
<body><main><h1>${esc(config.destinationName)}</h1><p>${esc(config.description)}</p><p>Opening ${esc(destination.hostname)}…</p><a href="${esc(url)}" rel="noopener">Open website</a><small>If you are not redirected, use the button above.</small></main><script>setTimeout(function(){window.location.replace(${scriptUrl});},${delay});</script></body></html>`);
};
