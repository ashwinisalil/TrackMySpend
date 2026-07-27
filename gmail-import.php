<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gmail import — TrackMySpend</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --cover: #101c16;
    --cover-deep: #0a1410;
    --paper: #f4efe1;
    --paper-dim: #ece5d2;
    --ink: #1c2620;
    --ink-soft: #4b5850;
    --money: #3f7a5c;
    --money-deep: #2c5842;
    --stamp: #b1462f;
    --gold: #c9a15a;
    --line: rgba(28,38,32,0.14);
    --line-on-cover: rgba(244,239,225,0.16);
  }
  *{ margin:0; padding:0; box-sizing:border-box; }
  html,body{ min-height:100%; }
  body{ background:var(--paper); color:var(--ink); font-family:'IBM Plex Sans', sans-serif; -webkit-font-smoothing:antialiased; }
  @media (prefers-reduced-motion: reduce){ *{ animation-duration:0.001ms !important; animation-iteration-count:1 !important; transition-duration:0.001ms !important; } }
  a{ color:inherit; text-decoration:none; }
  .display{ font-family:'Fraunces', serif; }
  .mono{ font-family:'IBM Plex Mono', monospace; }
  ::selection{ background:var(--gold); color:var(--cover-deep); }
  .wrap{ max-width:1080px; margin:0 auto; padding:0 32px; }

  nav{ background:var(--cover); border-bottom:1px solid var(--line-on-cover); position:sticky; top:0; z-index:60; }
  .nav-inner{ display:flex; align-items:center; justify-content:space-between; padding:16px 32px; max-width:1080px; margin:0 auto; }
  .brand{ display:flex; align-items:center; gap:11px; font-family:'Fraunces', serif; font-weight:600; font-size:1.1rem; color:var(--paper); }
  .brand .mark{ width:32px; height:32px; border-radius:7px; overflow:hidden; flex:none; background:var(--paper); box-shadow:0 2px 0 var(--money-deep); }
  .brand .mark img{ width:100%; height:100%; object-fit:cover; display:block; }
  .nav-links{ display:flex; gap:20px; font-size:0.86rem; }
  .nav-links a{ position:relative; color:rgba(244,239,225,0.68); transition:color 0.2s; padding-bottom:4px; }
  .nav-links a::after{ content:''; position:absolute; left:0; right:0; bottom:0; height:2px; background:var(--gold); transform:scaleX(0); transform-origin:left; transition:transform 0.25s ease; }
  .nav-links a:hover{ color:var(--paper); }
  .nav-links a:hover::after{ transform:scaleX(1); }
  .nav-links a.active{ color:var(--paper); }
  .nav-links a.active::after{ transform:scaleX(1); }
  .nav-user{ display:flex; align-items:center; gap:10px; }
  .nav-avatar{ width:32px; height:32px; border-radius:50%; background:var(--money); color:var(--paper); display:flex; align-items:center; justify-content:center; font-family:'IBM Plex Mono', monospace; font-weight:600; font-size:0.78rem; border:1.5px solid var(--gold); }
  .nav-signout{ font-size:0.82rem; color:rgba(244,239,225,0.55); border:1px solid var(--line-on-cover); padding:7px 13px; border-radius:5px; }
  @media (max-width: 760px){ .nav-links{ display:none; } }

  .page-head{ padding:40px 0 0; }
  .kicker{ font-family:'IBM Plex Mono', monospace; font-size:0.72rem; letter-spacing:0.1em; text-transform:uppercase; color:var(--money-deep); margin-bottom:8px; display:flex; align-items:center; gap:8px; }
  .kicker .g-dot{ width:6px; height:6px; border-radius:50%; background:#EA4335; }
  .page-head h1{ font-family:'Fraunces', serif; font-weight:600; font-size:2rem; }
  .page-head p{ margin-top:8px; color:var(--ink-soft); font-size:0.96rem; max-width:580px; }

  /* STEPPER */
  .stepper{ display:flex; align-items:center; margin:34px 0; }
  .step{ display:flex; align-items:center; gap:10px; color:var(--ink-soft); }
  .step .n{ width:28px; height:28px; border-radius:50%; border:1.5px solid var(--line); display:flex; align-items:center; justify-content:center; font-family:'IBM Plex Mono', monospace; font-size:0.78rem; font-weight:600; flex:none; transition:all 0.25s; }
  .step .label{ font-size:0.84rem; font-weight:600; white-space:nowrap; }
  .step.active .n{ background:var(--money-deep); border-color:var(--money-deep); color:#fff; }
  .step.active .label{ color:var(--ink); }
  .step.done .n{ background:var(--money); border-color:var(--money); color:#fff; }
  .step.done .n::before{ content:'✓'; }
  .step-line{ flex:1; height:1.5px; background:var(--line); margin:0 12px; position:relative; min-width:24px; }
  .step-line .fill{ position:absolute; inset:0; background:var(--money); width:0%; transition:width 0.5s ease; }

  .stage{ display:none; margin-bottom:90px; }
  .stage.visible{ display:block; }

  /* CONNECT CARD */
  .connect-hero{
    background:#fff; border:1px solid var(--line); border-radius:14px; padding:46px 40px; text-align:center;
    position:relative; overflow:hidden;
  }
  .connect-hero::before{
    content:''; position:absolute; inset:0;
    background: radial-gradient(ellipse 70% 50% at 50% -10%, rgba(63,122,92,0.08), transparent 60%);
    pointer-events:none;
  }
  .g-icon-big{ width:64px; height:64px; margin:0 auto 20px; }
  .connect-hero h2{ font-family:'Fraunces', serif; font-weight:600; font-size:1.5rem; position:relative; }
  .connect-hero p{ color:var(--ink-soft); font-size:0.92rem; margin:10px auto 26px; max-width:440px; position:relative; }
  .btn{ padding:13px 24px; border-radius:7px; font-weight:600; font-size:0.9rem; border:none; cursor:pointer; transition:transform 0.15s ease, box-shadow 0.15s ease; font-family:inherit; }
  .btn-google{ background:#fff; color:var(--ink); border:1px solid var(--line); display:inline-flex; align-items:center; gap:10px; box-shadow:0 2px 6px rgba(0,0,0,0.06); }
  .btn-google:hover{ transform:translateY(-1px); box-shadow:0 4px 10px rgba(0,0,0,0.09); }
  .btn-google svg{ width:19px; height:19px; }
  .btn-primary{ background:var(--money-deep); color:var(--paper); box-shadow:0 3px 0 #1e3f2f; }
  .btn-primary:hover{ transform:translateY(-1px); box-shadow:0 4px 0 #1e3f2f; }
  .btn-primary:active{ transform:translateY(1px); box-shadow:0 1px 0 #1e3f2f; }
  .btn-ghost{ background:none; border:1px solid var(--line); color:var(--ink-soft); }
  .btn-ghost:hover{ background:var(--paper-dim); }

  .perm-list{ display:flex; flex-direction:column; gap:12px; max-width:440px; margin:0 auto 30px; text-align:left; position:relative; }
  .perm-item{ display:flex; gap:12px; align-items:flex-start; font-size:0.84rem; color:var(--ink-soft); }
  .perm-item .chk{ width:18px; height:18px; border-radius:5px; background:rgba(63,122,92,0.14); color:var(--money-deep); flex:none; display:flex; align-items:center; justify-content:center; font-size:0.62rem; margin-top:1px; }
  .privacy-note{ font-family:'IBM Plex Mono', monospace; font-size:0.72rem; color:var(--ink-soft); background:var(--paper-dim); border-radius:8px; padding:12px 16px; max-width:440px; margin:0 auto; position:relative; }

  /* SCANNING STAGE */
  .scan-card{ background:#fff; border:1px solid var(--line); border-radius:14px; padding:50px 40px; text-align:center; }
  .scan-ring{ width:88px; height:88px; margin:0 auto 26px; position:relative; }
  .scan-ring svg{ width:100%; height:100%; transform:rotate(-90deg); }
  .scan-ring circle{ fill:none; stroke-width:6; }
  .scan-ring .bg{ stroke:var(--line); }
  .scan-ring .fg{ stroke:var(--money); stroke-linecap:round; stroke-dasharray:251; stroke-dashoffset:251; transition:stroke-dashoffset 0.4s linear; }
  .scan-ring .pct{ position:absolute; inset:0; display:flex; align-items:center; justify-content:center; font-family:'IBM Plex Mono', monospace; font-weight:600; font-size:1rem; color:var(--money-deep); }
  .scan-card h2{ font-family:'Fraunces', serif; font-weight:600; font-size:1.3rem; }
  .scan-status{ font-family:'IBM Plex Mono', monospace; font-size:0.82rem; color:var(--ink-soft); margin-top:10px; min-height:20px; }
  .scan-steps{ display:flex; flex-direction:column; gap:10px; max-width:340px; margin:28px auto 0; text-align:left; }
  .scan-step{ display:flex; align-items:center; gap:10px; font-size:0.82rem; color:var(--ink-soft); opacity:0.4; transition:opacity 0.3s; }
  .scan-step.active{ opacity:1; color:var(--ink); font-weight:600; }
  .scan-step.complete{ opacity:0.75; }
  .scan-step .dot{ width:16px; height:16px; border-radius:50%; border:1.5px solid var(--line); flex:none; display:flex; align-items:center; justify-content:center; font-size:0.58rem; }
  .scan-step.active .dot{ border-color:var(--money); }
  .scan-step.complete .dot{ background:var(--money); border-color:var(--money); color:#fff; }
  .scan-step.complete .dot::before{ content:'✓'; }

  /* RESULTS STAGE */
  .results-toolbar{ display:flex; align-items:center; justify-content:space-between; margin-bottom:18px; flex-wrap:wrap; gap:12px; }
  .results-toolbar .found{ font-size:0.92rem; }
  .results-toolbar .found strong{ color:var(--money-deep); }
  .toolbar-actions{ display:flex; gap:10px; align-items:center; font-size:0.84rem; }
  .toolbar-actions label{ display:flex; align-items:center; gap:6px; color:var(--ink-soft); cursor:pointer; }

  .tx-table{ background:#fff; border:1px solid var(--line); border-radius:12px; overflow:hidden; }
  .tx-row{ display:grid; grid-template-columns:32px 90px 1fr 140px 110px 90px; gap:12px; align-items:center; padding:14px 18px; border-bottom:1px solid var(--line); font-size:0.86rem; }
  .tx-row.head{ background:var(--paper-dim); font-family:'IBM Plex Mono', monospace; font-size:0.66rem; letter-spacing:0.06em; text-transform:uppercase; color:var(--ink-soft); padding:11px 18px; }
  .tx-row:last-child{ border-bottom:none; }
  .tx-row input[type="checkbox"]{ width:16px; height:16px; accent-color:var(--money); cursor:pointer; }
  .tx-date{ font-family:'IBM Plex Mono', monospace; font-size:0.78rem; color:var(--ink-soft); }
  .tx-desc{ min-width:0; }
  .tx-desc .merch{ font-weight:600; }
  .tx-desc .snippet{ font-size:0.74rem; color:var(--ink-soft); margin-top:2px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
  .tx-cat select{ width:100%; padding:7px 8px; border-radius:6px; border:1px solid var(--line); font-size:0.78rem; font-family:inherit; background:#fff; }
  .tx-amt{ font-family:'IBM Plex Mono', monospace; font-weight:600; text-align:right; }
  .tx-amt.debit{ color:var(--stamp); }
  .tx-amt.credit{ color:var(--money-deep); }
  .conf-badge{ font-family:'IBM Plex Mono', monospace; font-size:0.62rem; letter-spacing:0.03em; padding:3px 8px; border-radius:20px; text-align:center; font-weight:600; }
  .conf-high{ background:rgba(63,122,92,0.14); color:var(--money-deep); }
  .conf-med{ background:rgba(201,161,90,0.18); color:#8a6a2e; }

  .results-footer{ display:flex; align-items:center; justify-content:space-between; margin-top:22px; flex-wrap:wrap; gap:14px; }
  .results-footer .sum{ font-size:0.86rem; color:var(--ink-soft); }
  .results-footer .sum strong{ color:var(--ink); font-family:'IBM Plex Mono', monospace; }

  /* SUCCESS STAGE */
  .success-card{ background:var(--cover); color:var(--paper); border-radius:14px; padding:48px 40px; text-align:center; position:relative; overflow:hidden; }
  .success-card::before{ content:''; position:absolute; inset:0; background:radial-gradient(ellipse 70% 60% at 90% 0%, rgba(201,161,90,0.14), transparent 60%), radial-gradient(ellipse 60% 60% at 0% 100%, rgba(63,122,92,0.18), transparent 60%); pointer-events:none; }
  .success-mark{ width:64px; height:64px; border-radius:50%; background:var(--money); color:#fff; font-size:1.6rem; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; border:3px solid var(--gold); position:relative; }
  .success-card h2{ font-family:'Fraunces', serif; font-weight:600; font-size:1.6rem; position:relative; }
  .success-card p{ color:rgba(244,239,225,0.7); margin-top:10px; position:relative; }
  .success-stats{ display:flex; justify-content:center; gap:1px; background:var(--line-on-cover); border:1px solid var(--line-on-cover); border-radius:10px; overflow:hidden; margin:28px auto; max-width:460px; position:relative; }
  .success-stat{ background:rgba(244,239,225,0.03); padding:18px 20px; flex:1; }
  .success-stat .n{ font-family:'Fraunces', serif; font-weight:600; font-size:1.5rem; }
  .success-stat .l{ font-family:'IBM Plex Mono', monospace; font-size:0.62rem; letter-spacing:0.05em; text-transform:uppercase; color:rgba(244,239,225,0.55); margin-top:4px; }
  .success-actions{ display:flex; gap:12px; justify-content:center; position:relative; flex-wrap:wrap; }
  .btn-on-cover{ background:rgba(244,239,225,0.1); color:var(--paper); border:1px solid var(--line-on-cover); }
  .btn-on-cover:hover{ background:rgba(244,239,225,0.16); }

  @media (max-width: 760px){
    .tx-row{ grid-template-columns:24px 1fr; grid-template-areas:"chk desc" "chk date" "chk cat" "chk amt"; row-gap:6px; }
    .tx-row.head{ display:none; }
    .stepper .label{ display:none; }
  }
</style>
  <link rel="icon" type="image/png" href="data:image/png;base64,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">
</head>
<body>

<nav>
  <div class="nav-inner">
    <a href="dashboard.html" class="brand">
      <span class="mark"><img src="data:image/png;base64,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" alt="TrackMySpend logo"></span>
      TrackMySpend
    </a>
    <div class="nav-links">
      <a href="dashboard.html">Dashboard</a>
      <a href="transactions.html">Transactions</a>
      <a href="budgets.html">Budgets</a>
      <a href="goals.html">Goals</a>
      <a href="reports.html">Reports</a>
      <a href="settings.html" class="active">Settings</a>
      <a href="profile.html">Profile</a>
    </div>
    <div class="nav-user">
      <span class="nav-avatar">RS</span>
      <a href="index.html" class="nav-signout">Sign out</a>
    </div>
  </div>
</nav>

<div class="wrap">
  <div class="page-head">
    <div class="kicker"><span class="g-dot"></span> Settings · Connected accounts</div>
    <h1>Import transactions from Gmail</h1>
    <p>TrackMySpend scans your inbox for bank and UPI transaction alerts, then turns them into categorized entries — no manual typing.</p>
  </div>

  <div class="stepper" id="stepper">
    <div class="step active" data-step="1"><span class="n">1</span><span class="label">Connect</span></div>
    <div class="step-line"><div class="fill"></div></div>
    <div class="step" data-step="2"><span class="n">2</span><span class="label">Scan inbox</span></div>
    <div class="step-line"><div class="fill"></div></div>
    <div class="step" data-step="3"><span class="n">3</span><span class="label">Review</span></div>
    <div class="step-line"><div class="fill"></div></div>
    <div class="step" data-step="4"><span class="n">4</span><span class="label">Done</span></div>
  </div>

  <!-- STAGE 1: CONNECT -->
  <div class="stage visible" id="stageConnect">
    <div class="connect-hero">
      <svg class="g-icon-big" viewBox="0 0 24 24"><path fill="#EA4335" d="M12 12.7 2 5.5V19c0 .6.4 1 1 1h3V11l6 4.4 6-4.4v9h3c.6 0 1-.4 1-1V5.5z"/><path fill="#34A853" d="M6 20v-9l6 4.4V20z"/><path fill="#4285F4" d="M18 20v-9l-6 4.4V20z"/><path fill="#FBBC05" d="M2 5.5 12 12.7 22 5.5c0-.6-.5-1.5-1-1.5H3c-.5 0-1 .9-1 1.5z"/></svg>
      <h2>Connect your Gmail account</h2>
      <p>TrackMySpend requests read-only access to find messages from your bank and UPI apps. We never send email on your behalf.</p>

      <div class="perm-list">
        <div class="perm-item"><span class="chk">✓</span> Read-only access to messages matching bank &amp; UPI sender patterns</div>
        <div class="perm-item"><span class="chk">✓</span> No access to personal, work, or unrelated emails</div>
        <div class="perm-item"><span class="chk">✓</span> Revoke access anytime from Settings → Connected accounts</div>
      </div>

      <button class="btn btn-google" id="connectBtn">
        <svg viewBox="0 0 24 24"><path fill="#4285F4" d="M23.5 12.27c0-.85-.08-1.67-.22-2.45H12v4.64h6.46c-.28 1.5-1.13 2.77-2.4 3.63v3h3.88c2.27-2.09 3.56-5.17 3.56-8.82z"/><path fill="#34A853" d="M12 24c3.24 0 5.96-1.07 7.94-2.91l-3.88-3c-1.08.72-2.45 1.15-4.06 1.15-3.13 0-5.78-2.11-6.73-4.96H1.27v3.1C3.24 21.3 7.3 24 12 24z"/><path fill="#FBBC05" d="M5.27 14.28A7.2 7.2 0 0 1 4.9 12c0-.79.14-1.56.37-2.28v-3.1H1.27A11.97 11.97 0 0 0 0 12c0 1.93.46 3.76 1.27 5.38l4-3.1z"/><path fill="#EA4335" d="M12 4.77c1.77 0 3.35.61 4.6 1.8l3.44-3.44C17.95 1.19 15.24 0 12 0 7.3 0 3.24 2.7 1.27 6.62l4 3.1C6.22 6.88 8.87 4.77 12 4.77z"/></svg>
        Continue with Google
      </button>

      <div class="privacy-note" style="margin-top:24px;">🔒 This is a project demo — no real Gmail connection is made. Clicking "Continue with Google" simulates the OAuth flow.</div>
    </div>
  </div>

  <!-- STAGE 2: SCANNING -->
  <div class="stage" id="stageScan">
    <div class="scan-card">
      <div class="scan-ring">
        <svg viewBox="0 0 90 90">
          <circle class="bg" cx="45" cy="45" r="40"></circle>
          <circle class="fg" id="scanCircle" cx="45" cy="45" r="40"></circle>
        </svg>
        <div class="pct" id="scanPct">0%</div>
      </div>
      <h2>Scanning your inbox…</h2>
      <div class="scan-status" id="scanStatus">Connecting to Gmail</div>
      <div class="scan-steps" id="scanSteps">
        <div class="scan-step active" data-s="0"><span class="dot"></span> Connecting to riya.sharma@gmail.com</div>
        <div class="scan-step" data-s="1"><span class="dot"></span> Searching bank &amp; UPI sender patterns</div>
        <div class="scan-step" data-s="2"><span class="dot"></span> Parsing amounts, dates &amp; merchants</div>
        <div class="scan-step" data-s="3"><span class="dot"></span> Auto-categorizing transactions</div>
      </div>
    </div>
  </div>

  <!-- STAGE 3: RESULTS -->
  <div class="stage" id="stageResults">
    <div class="results-toolbar">
      <div class="found">Found <strong id="foundCount">7</strong> transactions from the last 14 days</div>
      <div class="toolbar-actions">
        <label><input type="checkbox" id="selectAll" checked> Select all</label>
      </div>
    </div>

    <div class="tx-table">
      <div class="tx-row head">
        <span></span><span>Date</span><span>Merchant / snippet</span><span>Category</span><span style="text-align:right;">Amount</span><span style="text-align:center;">Match</span>
      </div>
      <div id="txBody"></div>
    </div>

    <div class="results-footer">
      <div class="sum"><span id="selCount">7</span> selected · total <strong id="selTotal">₹0</strong></div>
      <div style="display:flex; gap:12px;">
        <button class="btn btn-ghost" id="cancelReview">Cancel</button>
        <button class="btn btn-primary" id="importBtn">Import selected</button>
      </div>
    </div>
  </div>

  <!-- STAGE 4: SUCCESS -->
  <div class="stage" id="stageSuccess">
    <div class="success-card">
      <div class="success-mark">✓</div>
      <h2>Import complete</h2>
      <p>Your transactions are now in the ledger, categorized and ready to review.</p>
      <div class="success-stats">
        <div class="success-stat"><div class="n" id="finalCount">7</div><div class="l">Imported</div></div>
        <div class="success-stat"><div class="n">94%</div><div class="l">Auto-categorized</div></div>
        <div class="success-stat"><div class="n">Every 6h</div><div class="l">Next auto-scan</div></div>
      </div>
      <div class="success-actions">
        <a href="settings.html" class="btn btn-on-cover">Back to settings</a>
        <button class="btn btn-primary" id="scanAgainBtn">Scan again</button>
      </div>
    </div>
  </div>

</div>

<script>
  const transactions = [
    { date:'18 Jul', merch:'Zomato', snippet:'Your order #ZM4821 has been placed for ₹340.00…', cat:'Food & dining', amt:-340, conf:'high' },
    { date:'17 Jul', merch:'Salary Credit — Infosys Ltd', snippet:'INR 42,000.00 credited to A/C XX4521 on 17-Jul…', cat:'Income', amt:42000, conf:'high' },
    { date:'16 Jul', merch:'Airtel Postpaid', snippet:'Payment of ₹599.00 received for mobile no. XXXXX2231…', cat:'Bills & utilities', amt:-599, conf:'high' },
    { date:'15 Jul', merch:'IRCTC', snippet:'Booking confirmed. ₹1,240.00 debited for PNR 8213…', cat:'Travel', amt:-1240, conf:'high' },
    { date:'14 Jul', merch:'Swiggy Instamart', snippet:'₹612.00 debited via UPI for order #SW9012…', cat:'Food & dining', amt:-612, conf:'med' },
    { date:'12 Jul', merch:'RD Auto-debit — HDFC Bank', snippet:'₹2,000.00 debited towards Recurring Deposit A/C…', cat:'Savings & goals', amt:-2000, conf:'high' },
    { date:'10 Jul', merch:'Amazon Pay', snippet:'₹899.00 debited for order #AMZ-771029…', cat:'Bills & utilities', amt:-899, conf:'med' }
  ];
  const categories = ['Food & dining','Bills & utilities','Travel','Savings & goals','Income','Shopping','Other'];

  function fmtAmt(n){
    const sign = n < 0 ? '-' : '+';
    return sign + '₹' + Math.abs(n).toLocaleString('en-IN');
  }

  function renderRows(){
    const body = document.getElementById('txBody');
    body.innerHTML = transactions.map((t, i) => `
      <div class="tx-row" data-i="${i}">
        <input type="checkbox" class="tx-check" checked>
        <span class="tx-date">${t.date}</span>
        <div class="tx-desc"><div class="merch">${t.merch}</div><div class="snippet">${t.snippet}</div></div>
        <div class="tx-cat">
          <select>${categories.map(c => `<option ${c === t.cat ? 'selected' : ''}>${c}</option>`).join('')}</select>
        </div>
        <div class="tx-amt ${t.amt < 0 ? 'debit' : 'credit'}">${fmtAmt(t.amt)}</div>
        <div class="conf-badge conf-${t.conf}">${t.conf === 'high' ? 'High' : 'Med'}</div>
      </div>
    `).join('');
    updateSelection();
    document.querySelectorAll('.tx-check').forEach(cb => cb.addEventListener('change', updateSelection));
  }

  function updateSelection(){
    const checks = document.querySelectorAll('.tx-check');
    let count = 0, total = 0;
    checks.forEach((cb, i) => { if(cb.checked){ count++; total += transactions[i].amt; } });
    document.getElementById('selCount').textContent = count;
    document.getElementById('selTotal').textContent = (total < 0 ? '-' : '') + '₹' + Math.abs(total).toLocaleString('en-IN');
    document.getElementById('selectAll').checked = count === checks.length;
  }

  document.getElementById('selectAll').addEventListener('change', function(){
    document.querySelectorAll('.tx-check').forEach(cb => cb.checked = this.checked);
    updateSelection();
  });

  // Stepper control
  function setStep(n){
    document.querySelectorAll('.step').forEach(s => {
      const sn = parseInt(s.dataset.step);
      s.classList.toggle('active', sn === n);
      s.classList.toggle('done', sn < n);
    });
    document.querySelectorAll('.step-line .fill').forEach((f, i) => {
      f.style.width = (i < n - 1) ? '100%' : '0%';
    });
    ['stageConnect','stageScan','stageResults','stageSuccess'].forEach((id, i) => {
      document.getElementById(id).classList.toggle('visible', i === n - 1);
    });
  }

  // Stage 1 -> 2
  document.getElementById('connectBtn').addEventListener('click', () => {
    setStep(2);
    runScan();
  });

  function runScan(){
    const circle = document.getElementById('scanCircle');
    const pct = document.getElementById('scanPct');
    const status = document.getElementById('scanStatus');
    const steps = document.querySelectorAll('.scan-step');
    const statusText = [
      'Connecting to riya.sharma@gmail.com',
      'Searching bank & UPI sender patterns',
      'Parsing amounts, dates & merchants',
      'Auto-categorizing transactions'
    ];
    let progress = 0;
    const circumference = 251;
    const timer = setInterval(() => {
      progress += 4;
      if(progress > 100) progress = 100;
      circle.style.strokeDashoffset = circumference - (circumference * progress / 100);
      pct.textContent = progress + '%';
      const stepIdx = Math.min(3, Math.floor(progress / 25));
      status.textContent = statusText[stepIdx];
      steps.forEach((s, i) => {
        s.classList.toggle('active', i === stepIdx);
        s.classList.toggle('complete', i < stepIdx);
      });
      if(progress >= 100){
        clearInterval(timer);
        steps.forEach(s => { s.classList.remove('active'); s.classList.add('complete'); });
        setTimeout(() => { setStep(3); renderRows(); }, 500);
      }
    }, 90);
  }

  document.getElementById('cancelReview').addEventListener('click', () => setStep(1));

  document.getElementById('importBtn').addEventListener('click', () => {
    const count = document.getElementById('selCount').textContent;
    document.getElementById('finalCount').textContent = count;
    setStep(4);
  });

  document.getElementById('scanAgainBtn').addEventListener('click', () => {
    setStep(2);
    document.getElementById('scanCircle').style.strokeDashoffset = 251;
    document.getElementById('scanPct').textContent = '0%';
    runScan();
  });
</script>

</body>
</html>
