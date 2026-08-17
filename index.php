<!DOCTYPE html>
<html lang="en">
<head>
   <link rel="icon" type="image/png" href="files/images/favicon.png">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dear SEA Swimwear — Made For Your Sea</title>
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
    --shadow: 0 18px 40px rgba(53,42,34,.08);
    --ease: cubic-bezier(.16,.8,.24,1);
  }

  *{box-sizing:border-box;}
  html{scroll-behavior:smooth;}
  body{
    margin:0;
    background:var(--ivory);
    color:var(--espresso);
    font-family:'Manrope', sans-serif;
    font-weight:400;
    -webkit-font-smoothing:antialiased;
    overflow-x:hidden;
  }
  @media (prefers-reduced-motion: reduce){
    html{scroll-behavior:auto;}
    *{animation-duration:.01ms !important; animation-iteration-count:1 !important; transition-duration:.01ms !important; scroll-behavior:auto !important;}
  }

  h1,h2,h3,.serif{font-family:'Fraunces', serif; font-weight:400; letter-spacing:.01em;}
  a{color:inherit; text-decoration:none;}
  img{max-width:100%; display:block;}
  button{font-family:inherit; cursor:pointer;}
  ::selection{background:var(--ocean); color:var(--white);}
  :focus-visible{outline:2px solid var(--ocean); outline-offset:3px;}

  .eyebrow{
    font-size:.7rem; letter-spacing:.22em; text-transform:uppercase;
    color:var(--espresso-70); font-weight:600;
  }

  .wrap{max-width:1360px; margin:0 auto; padding:0 clamp(20px,4vw,64px);}

  /* ---------- signature wave rule ---------- */
  .wave-rule{width:100%; height:22px; display:block; margin:0 auto;}
  .wave-rule path{fill:none; stroke:var(--espresso-45); stroke-width:1; stroke-linecap:round;}
  .wave-divider{padding:38px 0;}

  /* ---------- placeholder "photography" ---------- */
  .photo{
    position:relative; overflow:hidden; background:var(--sand);
    isolation:isolate;
  }
  .photo::before{
    content:"";
    position:absolute; inset:0;
    background:
      radial-gradient(140% 100% at 15% 0%, rgba(255,253,249,.55), transparent 55%),
      linear-gradient(155deg, var(--ph1,#DCCDB2) 0%, var(--ph2,#54707A) 100%);
    transition:transform 1.1s var(--ease);
  }
  .photo::after{
    content:"";
    position:absolute; inset:0;
    background-image:
      repeating-linear-gradient(115deg, rgba(255,253,249,.07) 0 2px, transparent 2px 34px),
      repeating-linear-gradient(25deg, rgba(53,42,34,.05) 0 1px, transparent 1px 26px);
    mix-blend-mode:soft-light;
  }
  .photo:hover::before{transform:scale(1.06);}
  .photo-img{
    position:absolute; inset:0; width:100%; height:100%; object-fit:cover; z-index:1;
    transition:transform 1.1s var(--ease);
  }
  .photo:hover .photo-img{transform:scale(1.06);}
  .photo .cap{
    position:absolute; left:16px; bottom:14px; z-index:2;
    font-size:.64rem; letter-spacing:.16em; text-transform:uppercase;
    color:var(--white); opacity:.85; font-weight:600;
  }
  .photo.dark::before{filter:brightness(.82);}

  /* ---------- mini slideshow (used in Story / Collaboration / ASAP) ---------- */
  .ms{position:relative; overflow:hidden; background:var(--sand);}
  .ms-slides{position:relative; width:100%; height:100%;}
  .ms-slide{position:absolute; inset:0; opacity:0; transition:opacity 1s var(--ease);}
  .ms-slide.active{opacity:1; z-index:1;}
  .ms-arrow{
    position:absolute; top:50%; transform:translateY(-50%); z-index:5;
    width:34px; height:34px; border-radius:50%; border:1px solid rgba(255,253,249,.55);
    background:rgba(53,42,34,.32); color:var(--white); backdrop-filter:blur(6px);
    display:flex; align-items:center; justify-content:center;
    opacity:0; transition:opacity .3s, background .3s;
  }
  .ms:hover .ms-arrow{opacity:1;}
  .ms-arrow:hover{background:rgba(53,42,34,.6);}
  .ms-arrow svg{width:14px; height:14px;}
  .ms-arrow.prev{left:12px;}
  .ms-arrow.next{right:12px;}
  .ms-dots{position:absolute; left:0; right:0; bottom:14px; z-index:5; display:flex; justify-content:center; gap:6px;}
  .ms-dots button{
    width:5px; height:5px; border-radius:50%; border:1px solid var(--white); background:transparent; padding:0;
    transition:background .3s;
  }
  .ms-dots button.active{background:var(--white);}
  @media (max-width:700px){ .ms-arrow{opacity:1;} }

  /* ---------- reveal on scroll ---------- */
  .reveal{opacity:0; transform:translateY(26px); transition:opacity .9s var(--ease), transform .9s var(--ease);}
  .reveal.in{opacity:1; transform:translateY(0);}

  /* =========================================================
     ANNOUNCEMENT BAR
  ========================================================= */
  .announce{
    background:var(--espresso); color:var(--white); overflow:hidden;
    height:38px; display:flex; align-items:center;
  }
  .announce-track{
    display:flex; gap:56px; white-space:nowrap; padding-left:56px;
    animation:marquee 26s linear infinite;
  }
  .announce-track span{
    font-size:.66rem; letter-spacing:.16em; text-transform:uppercase; font-weight:600;
    color:rgba(255,253,249,.82);
  }
  @keyframes marquee{ from{transform:translateX(0);} to{transform:translateX(-50%);} }
  @media (prefers-reduced-motion: reduce){ .announce-track{animation:none;} }

  /* =========================================================
     HEADER
  ========================================================= */
  header{
    position:sticky; top:0; z-index:100;
    background:rgba(247,242,232,.92);
    backdrop-filter:blur(10px);
    border-bottom:1px solid var(--line);
  }
  .header-row{
    display:flex; align-items:center; justify-content:space-between;
    padding:20px clamp(20px,4vw,64px);
    max-width:1360px; margin:0 auto;
  }
  .logo{display:flex; align-items:center;}
  .logo img{height:46px; width:auto; display:block;}
  .logo-fallback{font-family:'Fraunces', serif; font-size:1.34rem; letter-spacing:.14em; font-weight:500;}
  .logo-fallback small{display:block; font-family:'Manrope',sans-serif; font-size:.56rem; letter-spacing:.32em; font-weight:600; color:var(--espresso-70); margin-top:2px;}

  nav.primary-nav{display:flex; gap:34px;}
  nav.primary-nav a{
    font-size:.72rem; letter-spacing:.12em; text-transform:uppercase; font-weight:600;
    position:relative; padding:4px 0;
  }
  nav.primary-nav a::after{
    content:""; position:absolute; left:0; bottom:0; width:0; height:1px; background:var(--espresso);
    transition:width .35s var(--ease);
  }
  nav.primary-nav a:hover::after{width:100%;}

  .header-icons{display:flex; align-items:center; gap:20px;}
  .icon-btn{background:none; border:none; padding:4px; display:flex; color:var(--espresso);}
  .icon-btn svg{width:19px; height:19px; stroke-width:1.4;}
  .hamburger{display:none;}
  .mobile-logo{display:none;}
  .mobile-logo img{height:34px; width:auto; display:block;}

  @media (max-width: 900px){
    nav.primary-nav{display:none;}
    .hamburger{display:flex;}
    .header-row{position:relative;}
    .logo{display:none;}
    .mobile-logo{display:block; position:absolute; left:50%; top:50%; transform:translate(-50%,-50%);}
  }

  .mobile-menu{
    position:fixed; inset:0 0 0 auto; width:min(84vw,360px); height:100vh;
    background:var(--ivory); z-index:200; transform:translateX(100%);
    transition:transform .5s var(--ease);
    padding:26px 26px; display:flex; flex-direction:column; gap:28px;
    box-shadow:-20px 0 50px rgba(0,0,0,.12);
  }
  .mobile-menu.open{transform:translateX(0);}
  .mobile-menu-top{display:flex; justify-content:flex-end;}
  .mobile-menu a{font-family:'Fraunces',serif; font-size:1.5rem;}
  .mobile-menu .close-btn{background:none; border:none; font-size:1.6rem; color:var(--espresso);}
  .scrim{position:fixed; inset:0; background:rgba(53,42,34,.35); z-index:190; opacity:0; pointer-events:none; transition:opacity .4s;}
  .scrim.show{opacity:1; pointer-events:auto;}

  /* =========================================================
     HERO
  ========================================================= */
  .hero{
    position:relative; min-height:92vh; display:grid; grid-template-columns:1fr 1fr;
    border-bottom:1px solid var(--line); overflow:hidden;
  }
  .hero .photo{--ph1:#DCCDB2; --ph2:#4F6D77; height:100%;}
  .hero .photo:nth-child(2){--ph1:#EADFC9; --ph2:#7C3E3F;}
  .hero-overlay{
    position:absolute; inset:0; z-index:5; display:flex; align-items:center; justify-content:center;
    text-align:center; pointer-events:none;
  }
  .hero-overlay::before{
    content:""; position:absolute; inset:0;
    background:radial-gradient(58% 62% at 50% 48%, rgba(53,42,34,.42), transparent 72%);
  }
  .hero-card{
    position:relative; z-index:2; max-width:620px; padding:0 24px; pointer-events:auto;
  }
  .hero-card .eyebrow{color:rgba(255,253,249,.85);}
  .hero-card h1{
    font-size:clamp(2.4rem,5.2vw,4.4rem); line-height:1.04; margin:16px 0 20px; color:var(--white);
  }
  .hero-card h1 em{font-style:italic; color:#F3E4C9;}
  .hero-card p{
    font-size:1rem; line-height:1.75; color:rgba(255,253,249,.9);
    max-width:44ch; margin:0 auto 30px;
  }
  .btn-row{display:flex; flex-wrap:wrap; gap:14px; justify-content:center;}
  .btn{
    display:inline-flex; align-items:center; gap:10px;
    padding:15px 26px; font-size:.72rem; letter-spacing:.14em; text-transform:uppercase; font-weight:700;
    border:1px solid var(--espresso); transition:background .35s var(--ease), color .35s var(--ease), border-color .35s;
    white-space:nowrap;
  }
  .btn-solid{background:var(--espresso); color:var(--white);}
  .btn-solid:hover{background:var(--ocean-deep); border-color:var(--ocean-deep);}
  .btn-outline{background:transparent; color:var(--espresso);}
  .btn-outline:hover{background:var(--espresso); color:var(--white);}
  .hero .btn-outline{border-color:rgba(255,253,249,.75); color:var(--white);}
  .hero .btn-outline:hover{background:var(--white); color:var(--espresso);}
  .hero .btn-solid{background:var(--white); color:var(--espresso); border-color:var(--white);}
  .hero .btn-solid:hover{background:transparent; color:var(--white); border-color:rgba(255,253,249,.75);}

  .scroll-cue{
    position:absolute; left:50%; bottom:26px; transform:translateX(-50%); z-index:6;
    display:flex; flex-direction:column; align-items:center; gap:8px; color:var(--white);
  }
  .scroll-cue span{font-size:.6rem; letter-spacing:.2em; text-transform:uppercase; opacity:.8;}
  .scroll-cue .line{width:1px; height:34px; background:rgba(255,253,249,.6); position:relative; overflow:hidden;}
  .scroll-cue .line::after{
    content:""; position:absolute; top:-100%; left:0; width:100%; height:100%; background:var(--white);
    animation:scrollcue 2.1s ease-in-out infinite;
  }
  @keyframes scrollcue{ 0%{top:-100%;} 60%{top:100%;} 100%{top:100%;} }

  /* =========================================================
     SECTION HEADS
  ========================================================= */
  .section-head{
    display:flex; align-items:flex-end; justify-content:space-between; gap:24px;
    margin-bottom:40px; flex-wrap:wrap;
  }
  .section-head h2{font-size:clamp(1.8rem,3vw,2.5rem); margin:10px 0 0;}
  .section-head .lede{max-width:44ch; color:var(--espresso-70); font-size:.95rem; line-height:1.7; margin-top:14px;}

  section{padding:96px 0;}

  /* =========================================================
     COLLABORATION INTRO
  ========================================================= */
  .collab-intro{background:var(--sand);}
  .collab-grid{display:grid; grid-template-columns:1fr 1fr; gap:60px; align-items:center;}
  .collab-grid p{font-size:1.05rem; line-height:1.85; color:var(--espresso-70); margin:0 0 20px;}
  .collab-grid p:first-of-type{font-family:'Fraunces',serif; font-size:1.5rem; line-height:1.5; color:var(--espresso); font-style:italic;}

  /* =========================================================
     PRODUCTS
  ========================================================= */
  .tabs{display:flex; gap:34px; border-bottom:1px solid var(--line); margin-bottom:44px;}
  .tab-btn{
    background:none; border:none; padding:0 0 16px; font-size:.78rem; letter-spacing:.14em; text-transform:uppercase; font-weight:700;
    color:var(--espresso-45); border-bottom:2px solid transparent; margin-bottom:-1px;
  }
  .tab-btn.active{color:var(--espresso); border-color:var(--espresso);}

  .product-grid{
    display:grid; grid-template-columns:repeat(4, 1fr); gap:34px 26px;
  }
  .product-panel{display:none;}
  .empty-panel{color:var(--espresso-45); font-size:.9rem; padding:20px 0;}
  .product-panel.active{display:grid; grid-template-columns:repeat(4,1fr); gap:34px 26px;}
  .p-card{position:relative;}
  .p-card .photo{aspect-ratio:3/4; --ph1:#E4D7C1; --ph2:#628089;}
  .p-card .photo .alt{
    position:absolute; inset:0; opacity:0; transition:opacity .5s var(--ease);
    background:linear-gradient(155deg, var(--ph2,#628089), var(--ph1,#E4D7C1));
  }
  .p-card .photo:hover .alt{opacity:1;}
  .p-dots{position:absolute; left:0; right:0; bottom:12px; z-index:3; display:flex; justify-content:center; gap:6px; pointer-events:none;}
  .p-dots span{width:5px; height:5px; border-radius:50%; background:rgba(255,253,249,.55); transition:background .3s;}
  .p-dots span.on{background:var(--white);}
  .p-card .photo:hover .p-dots span.on{background:rgba(255,253,249,.55);}
  .p-card .photo:hover .p-dots span:last-child{background:var(--white);}
  .p-scrim{
    position:absolute; inset:0; z-index:2; background:rgba(53,42,34,0);
    display:flex; align-items:center; justify-content:center;
    transition:background .4s var(--ease);
  }
  .p-card .photo:hover .p-scrim{background:rgba(53,42,34,.22);}
  .quick-add{
    width:46px; height:46px; border-radius:50%; background:var(--white); color:var(--espresso);
    display:flex; align-items:center; justify-content:center;
    opacity:0; transform:scale(.75); transition:opacity .35s var(--ease), transform .35s var(--ease);
  }
  .quick-add svg{width:18px; height:18px;}
  .p-card .photo:hover .quick-add{opacity:1; transform:scale(1);}
  .p-card:hover .p-dots{opacity:0;}
  .p-info{padding-top:16px; display:flex; justify-content:space-between; gap:10px; border-top:1px solid transparent;}
  .p-info h4{font-family:'Fraunces',serif; font-weight:400; font-size:1.02rem; margin:0; letter-spacing:.01em;}
  .p-info .color{font-size:.7rem; letter-spacing:.04em; color:var(--espresso-45); margin-top:5px; text-transform:uppercase;}
  .p-price{font-size:.85rem; font-weight:600; white-space:nowrap; color:var(--espresso-70);}
  .p-card .photo{cursor:pointer;}
  .p-card.hidden{display:none;}

  .show-more-row{text-align:center; margin-top:52px;}

  /* ---------- product modal ---------- */
  .modal-scrim{
    position:fixed; inset:0; background:rgba(53,42,34,.5); z-index:290;
    opacity:0; pointer-events:none; transition:opacity .4s var(--ease);
  }
  .modal-scrim.show{opacity:1; pointer-events:auto;}
  .product-modal{
    position:fixed; top:50%; left:50%; transform:translate(-50%,-48%);
    width:min(960px, 94vw); max-height:90vh; overflow:auto;
    background:var(--white); z-index:300; display:grid; grid-template-columns:1fr 1fr;
    opacity:0; pointer-events:none; transition:opacity .4s var(--ease), transform .4s var(--ease);
    box-shadow:0 40px 90px rgba(0,0,0,.25);
  }
  .product-modal.show{opacity:1; pointer-events:auto; transform:translate(-50%,-50%);}
  .modal-photo-col{display:flex; flex-direction:column; background:var(--sand);}
  .modal-photo{aspect-ratio:3/4; --ph1:#E4D7C1; --ph2:#628089; position:relative; overflow:hidden; background:var(--sand); flex:1;}
  .modal-photo::before{
    content:""; position:absolute; inset:0;
    background:radial-gradient(140% 100% at 15% 0%, rgba(255,253,249,.55), transparent 55%), linear-gradient(155deg, var(--ph1), var(--ph2));
    transition:background 1.4s var(--ease);
  }
  .modal-photo .photo-img{position:absolute; inset:0; width:100%; height:100%; object-fit:cover; z-index:1;}
  .modal-thumbs{display:flex; gap:10px; padding:14px;}
  .modal-thumb{
    width:56px; height:56px; border:2px solid transparent; padding:0; position:relative; overflow:hidden;
    --ph1:#E4D7C1; --ph2:#628089; background:linear-gradient(155deg, var(--ph1), var(--ph2));
  }
  .modal-thumb img{position:absolute; inset:0; width:100%; height:100%; object-fit:cover;}
  .modal-thumb.active{border-color:var(--espresso);}
  .modal-thumb[data-swap="alt"]{--ph1:#628089; --ph2:#E4D7C1;}

  .modal-info{padding:clamp(26px,4vw,46px); display:flex; flex-direction:column; gap:14px;}
  .modal-info h3{font-size:1.7rem; margin:6px 0 0;}
  .modal-price-row{display:flex; align-items:center; gap:14px; flex-wrap:wrap;}
  .modal-price{font-size:1.1rem; font-weight:600;}
  .modal-color{font-size:.78rem; letter-spacing:.06em; text-transform:uppercase; color:var(--espresso-45); padding:4px 10px; border:1px solid var(--line);}
  .modal-desc{font-size:.92rem; line-height:1.75; color:var(--espresso-70); margin:0;}
  .modal-details{list-style:none; margin:2px 0 0; padding:14px 0; border-top:1px solid var(--line); border-bottom:1px solid var(--line); display:flex; flex-direction:column; gap:8px;}
  .modal-details li{font-size:.84rem; color:var(--espresso-70); padding-left:16px; position:relative; line-height:1.5;}
  .modal-details li::before{content:"—"; position:absolute; left:0; color:var(--espresso-45);}
  .modal-close{
    position:absolute; top:14px; right:14px; z-index:2; background:var(--white); border:1px solid var(--line);
    width:34px; height:34px; border-radius:50%; font-size:1.2rem; line-height:1; color:var(--espresso);
  }
  .field-label{font-size:.68rem; letter-spacing:.12em; text-transform:uppercase; font-weight:700; color:var(--espresso-70); display:block; margin-bottom:10px;}
  .modal-shipnote{font-size:.76rem; color:var(--espresso-45); margin:10px 0 0; padding-top:14px; border-top:1px solid var(--line);}
  @media (max-width:700px){ .product-modal{grid-template-columns:1fr;} }

  /* =========================================================
     CATEGORY PANELS
  ========================================================= */
  .cat-grid{display:grid; grid-template-columns:repeat(4,1fr); gap:16px;}
  .cat-card{position:relative; aspect-ratio:3/4; overflow:hidden;}
  .cat-card .photo{position:absolute; inset:0;}

  /* =========================================================
     BRAND STORY
  ========================================================= */
  .story{background:var(--ocean-deep); color:var(--white);}
  .story-grid{display:grid; grid-template-columns:1fr 1fr; gap:70px; align-items:center;}
  .story .eyebrow{color:rgba(255,253,249,.6);}
  .story h2{color:var(--white); font-size:clamp(1.9rem,3.3vw,2.7rem); margin:14px 0 26px; line-height:1.15;}
  .story p{font-size:1rem; line-height:1.85; color:rgba(255,253,249,.82); margin:0 0 18px; max-width:46ch;}
  .story .ms{aspect-ratio:4/5;}

  /* =========================================================
     CAMPAIGN BANNER
  ========================================================= */
  .campaign{
    position:relative; padding:130px 0; text-align:center; overflow:hidden;
    --ph1:#7C3E3F; --ph2:#3E5860;
  }
  .campaign .photo{position:absolute; inset:0;}
  .campaign .photo::before{filter:brightness(.55);}
  .campaign-inner{position:relative; z-index:2; color:var(--white); max-width:640px; margin:0 auto;}
  .campaign h2{font-size:clamp(2.1rem,4.4vw,3.4rem); color:var(--white); margin:16px 0 22px;}
  .campaign p{font-size:1.02rem; line-height:1.8; color:rgba(255,253,249,.88); margin:0 0 36px;}
  .campaign .btn-row{justify-content:center;}
  .campaign .btn-outline{border-color:var(--white); color:var(--white);}
  .campaign .btn-outline:hover{background:var(--white); color:var(--espresso);}
  .campaign .btn-solid{background:var(--white); color:var(--espresso); border-color:var(--white);}
  .campaign .btn-solid:hover{background:transparent; color:var(--white);}

  /* =========================================================
     ASAP BANNER
  ========================================================= */
  .asap{background:var(--white);}
  .asap-grid{display:grid; grid-template-columns:1fr 1fr; gap:64px; align-items:center;}
  .asap-photo{aspect-ratio:4/3;}
  .asap-copy h2{font-size:clamp(1.9rem,3.2vw,2.6rem); margin:14px 0 18px;}
  .asap-copy p{font-size:1rem; line-height:1.75; color:var(--espresso-70); max-width:42ch; margin:0 0 28px;}

  /* =========================================================
     LOCATIONS — HQ hub banner + mosaic of local touchpoints
  ========================================================= */
  .locations{background:var(--sand); position:relative; overflow:hidden;}
  .locations::before{
    content:""; position:absolute; top:-120px; right:-120px; width:340px; height:340px;
    border-radius:50%; background:radial-gradient(closest-side, rgba(84,112,122,.16), transparent 72%);
    pointer-events:none;
  }

  .loc-hub{
    position:relative; display:grid; grid-template-columns:1.1fr 1fr;
    background:var(--espresso); color:var(--white); overflow:hidden;
    box-shadow:var(--shadow); margin-bottom:16px;
  }
  .loc-hub::before{
    content:""; position:absolute; inset:0;
    background:
      radial-gradient(70% 90% at 100% 0%, rgba(232,168,124,.14), transparent 60%),
      repeating-linear-gradient(115deg, rgba(255,253,249,.045) 0 2px, transparent 2px 34px);
    pointer-events:none;
  }
  .loc-hub-copy{position:relative; z-index:1; padding:clamp(30px,4vw,48px); display:flex; flex-direction:column; justify-content:center; gap:14px;}
  .loc-hub-tag{
    display:inline-flex; align-items:center; gap:8px; width:fit-content;
    font-size:.62rem; letter-spacing:.16em; text-transform:uppercase; font-weight:700;
    color:#F3E4C9; border:1px solid rgba(255,253,249,.3); padding:6px 12px;
  }
  .loc-hub-tag svg{width:12px; height:12px;}
  .loc-hub-copy h3{font-size:clamp(1.6rem,2.6vw,2.2rem); margin:0; color:var(--white);}
  .loc-hub-copy p{margin:0; font-size:.86rem; line-height:1.7; color:rgba(255,253,249,.78); max-width:38ch;}
  .loc-hub-stats{display:flex; gap:26px; margin-top:6px; flex-wrap:wrap;}
  .loc-hub-stats div strong{display:block; font-family:'Fraunces',serif; font-size:1.5rem; color:var(--white);}
  .loc-hub-stats div span{font-size:.62rem; letter-spacing:.08em; text-transform:uppercase; color:rgba(255,253,249,.55);}
  .loc-hub-visual{
    position:relative; --ph1:#3E5860; --ph2:#7C3E3F; overflow:hidden;
  }
  .loc-hub-visual .photo{position:absolute; inset:0;}
  .loc-hub-compass{
    position:absolute; inset:0; width:100%; height:100%; z-index:1;
    animation:compassspin 60s linear infinite;
  }
  @keyframes compassspin{ to{ transform:rotate(360deg); } }
  .loc-hub-pin{
    position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); z-index:2;
    width:64px; height:64px; border-radius:50%; background:rgba(255,253,249,.14);
    border:1px solid rgba(255,253,249,.4); backdrop-filter:blur(6px);
    display:flex; align-items:center; justify-content:center; color:var(--white);
  }
  .loc-hub-pin svg{width:26px; height:26px;}
  .loc-hub-pin::after{
    content:""; position:absolute; inset:-14px; border-radius:50%; border:1px solid rgba(255,253,249,.35);
    animation:hubping 2.4s ease-out infinite;
  }
  @keyframes hubping{ 0%{transform:scale(.6); opacity:.9;} 100%{transform:scale(1.9); opacity:0;} }

  .loc-mosaic{display:grid; grid-template-columns:repeat(4,1fr); gap:16px;}
  .loc-card{
    position:relative; background:var(--white); border:1px solid var(--line);
    padding:24px 22px; display:flex; flex-direction:column; gap:14px;
    transition:transform .35s var(--ease), border-color .35s, box-shadow .35s;
  }
  .loc-card:hover{transform:translateY(-4px); border-color:var(--ocean); box-shadow:0 20px 40px rgba(53,42,34,.1);}
  .loc-card-top{display:flex; align-items:center; justify-content:space-between; gap:10px;}
  .loc-icon{
    width:38px; height:38px; border-radius:50%; background:var(--sand); color:var(--ocean-deep);
    display:flex; align-items:center; justify-content:center; flex:0 0 38px;
    transition:background .3s, color .3s;
  }
  .loc-card:hover .loc-icon{background:var(--ocean-deep); color:var(--white);}
  .loc-icon svg{width:17px; height:17px;}
  .loc-idx{font-size:.62rem; letter-spacing:.1em; color:var(--espresso-45); font-weight:700;}
  .loc-card h4{font-family:'Fraunces',serif; font-size:1.12rem; margin:0; letter-spacing:.02em;}
  .loc-tagline{font-size:.74rem; font-style:italic; color:var(--burgundy); margin:0;}
  .loc-status{
    display:inline-flex; align-items:center; gap:6px; width:fit-content;
    font-size:.66rem; letter-spacing:.03em; color:var(--espresso-70);
    border-top:1px solid var(--line-soft); padding-top:12px; margin-top:auto; line-height:1.4;
  }
  .loc-status .dot{width:6px; height:6px; border-radius:50%; background:#5B8F6B; flex:0 0 auto;}

  .loc-more{
    text-align:center; margin:26px 0 0; font-size:.8rem; font-style:italic;
    color:var(--espresso-70);
  }

  @media (max-width:980px){
    .loc-hub{grid-template-columns:1fr;}
    .loc-hub-visual{aspect-ratio:16/9;}
    .loc-mosaic{grid-template-columns:repeat(2,1fr);}
  }
  @media (max-width:560px){
    .loc-mosaic{grid-template-columns:1fr;}
    .loc-hub-stats{gap:18px;}
  }

  /* =========================================================
     NEWSLETTER
  ========================================================= */
  .newsletter{text-align:center;}
  .newsletter h2{font-size:clamp(1.9rem,3.4vw,2.6rem); margin:14px 0 14px;}
  .newsletter p{max-width:46ch; margin:0 auto 34px; color:var(--espresso-70); line-height:1.7;}
  .subscribe-row{display:flex; max-width:440px; margin:0 auto; border-bottom:1px solid var(--espresso);}
  .subscribe-row input{
    flex:1; border:none; background:transparent; padding:14px 4px; font-size:.9rem; color:var(--espresso); font-family:inherit;
  }
  .subscribe-row input:focus{outline:none;}
  .subscribe-row button{
    background:none; border:none; font-size:.7rem; letter-spacing:.14em; text-transform:uppercase; font-weight:700;
    padding:0 6px; color:var(--espresso); display:flex; align-items:center; gap:6px;
  }
  .subscribe-row button:hover{color:var(--ocean-deep);}

  /* =========================================================
     "FROM TIKTOK" VIDEO CAROUSEL — peek style: active frame large
     and sharp at center, neighbors visible on both sides, smaller
     and faded, receding into the background on either edge.
  ========================================================= */
  .tiktok-carousel{background:radial-gradient(120% 100% at 50% 0%, #40332A 0%, var(--espresso) 55%); overflow:hidden; position:relative;}
  .tc-head{margin-bottom:44px; text-align:center;}
  .tc-head .eyebrow{color:#E8A87C; opacity:1; display:inline-flex; align-items:center; gap:10px;}
  .tc-head .eyebrow::before, .tc-head .eyebrow::after{content:""; width:22px; height:1px; background:currentColor; opacity:.6;}
  .tc-head h2{color:var(--white); font-size:clamp(2rem,3.6vw,2.8rem); margin:16px 0 0;}
  .tc-head p{max-width:46ch; margin:16px auto 0; color:rgba(255,253,249,.68); font-size:.92rem; line-height:1.75;}
  .tc-cta{
    display:inline-flex; align-items:center; gap:8px; margin-top:22px;
    font-size:.68rem; letter-spacing:.12em; text-transform:uppercase; font-weight:700;
    color:var(--white); border-bottom:1px solid rgba(255,253,249,.4); padding-bottom:5px;
    transition:border-color .3s, color .3s, gap .3s;
  }
  .tc-cta svg{width:13px; height:13px; transition:transform .3s var(--ease);}
  .tc-cta:hover{color:#E8A87C; border-color:#E8A87C;}
  .tc-cta:hover svg{transform:translate(2px,-2px);}

  .tc-slideshow{
    position:relative; display:flex; align-items:center; justify-content:center; gap:8px;
    outline:none;
  }
  .tc-track{
    position:relative; flex:1 1 auto; min-width:0;
    height:clamp(300px, 42vw, 420px);
    --tc-step:220px;
  }
  /* fade the far edges of the track into the section background so
     frames feel like they recede rather than getting clipped */
  .tc-track::before{
    content:""; position:absolute; inset:0; z-index:6; pointer-events:none;
    background:linear-gradient(90deg, #40332A 0%, rgba(64,51,42,0) 12%, rgba(64,51,42,0) 88%, #40332A 100%);
  }

  .tc-frame{
    position:absolute; top:50%; left:50%; overflow:hidden; cursor:pointer;
    width:clamp(160px, 19vw, 236px); aspect-ratio:9/16; border-radius:3px;
    background:var(--sand);
    box-shadow:0 30px 60px rgba(0,0,0,.5), 0 0 0 1px rgba(255,253,249,.08);
    transform:translate(-50%,-50%) translateX(calc(var(--d,0) * var(--tc-step))) scale(var(--tc-scale,1));
    transition:transform .65s var(--ease), opacity .65s var(--ease), filter .65s var(--ease);
  }
  .tc-frame video{position:absolute; inset:0; width:100%; height:100%; object-fit:cover; background:transparent; z-index:1;}
  .tc-frame::after{
    content:""; position:absolute; inset:0; z-index:2; pointer-events:none;
    background:linear-gradient(rgba(53,42,34,0) 55%, rgba(53,42,34,.66));
  }

  .tc-frame.is-active{ --tc-scale:1; opacity:1; filter:none; z-index:5; box-shadow:0 34px 70px rgba(0,0,0,.5), 0 0 0 1px rgba(255,253,249,.1); }
  .tc-frame.is-near{ --tc-scale:.82; opacity:.62; filter:brightness(.6) saturate(.85); z-index:3; }
  .tc-frame.is-far{ --tc-scale:.66; opacity:.3; filter:brightness(.45) saturate(.7); z-index:1; }
  .tc-frame:not(.is-active):hover{ opacity:.85; }

  .tc-frame-tag{
    position:absolute; left:14px; bottom:14px; z-index:3;
    color:var(--white); font-size:.66rem; letter-spacing:.1em; text-transform:uppercase; font-weight:700;
    display:flex; align-items:center; gap:7px; opacity:0; transition:opacity .4s var(--ease);
  }
  .tc-frame.is-active .tc-frame-tag{opacity:1;}
  .dot-live{width:6px; height:6px; border-radius:50%; background:#E8A87C; box-shadow:0 0 0 3px rgba(232,168,124,.25); flex:0 0 auto; animation:dotpulse 1.6s ease-in-out infinite;}
  @keyframes dotpulse{ 0%,100%{opacity:1;} 50%{opacity:.4;} }

  .tc-mute{
    position:absolute; top:14px; right:14px; z-index:4;
    width:32px; height:32px; border-radius:50%; border:1px solid rgba(255,253,249,.5);
    background:rgba(53,42,34,.4); color:var(--white); backdrop-filter:blur(6px);
    display:flex; align-items:center; justify-content:center; transition:background .25s, opacity .3s var(--ease);
    opacity:0; pointer-events:none;
  }
  .tc-frame.is-active .tc-mute{opacity:1; pointer-events:auto;}
  .tc-mute:hover{background:rgba(53,42,34,.65);}
  .tc-mute svg{width:14px; height:14px;}
  .tc-mute.is-muted svg path:last-child{opacity:.35;}

  .tc-arrow{
    position:relative; z-index:7; flex:0 0 auto;
    width:44px; height:44px; border-radius:50%; border:1px solid rgba(255,253,249,.28);
    background:rgba(255,253,249,.06); color:var(--white);
    display:flex; align-items:center; justify-content:center; transition:background .3s, border-color .3s, transform .3s var(--ease);
  }
  .tc-arrow:hover{background:rgba(255,253,249,.16); border-color:rgba(255,253,249,.55); transform:scale(1.06);}
  .tc-arrow svg{width:16px; height:16px;}

  .tc-dots{display:flex; flex-wrap:wrap; justify-content:center; align-items:center; gap:8px; margin-top:34px; max-width:520px; margin-left:auto; margin-right:auto;}
  .tc-dots button{
    width:6px; height:6px; border-radius:3px; background:rgba(255,253,249,.28); border:none; padding:0;
    transition:background .35s, width .35s var(--ease);
  }
  .tc-dots button.active{background:#E8A87C; width:20px;}

  @media (max-width:860px){
    .tc-track{--tc-step:150px; height:clamp(260px, 54vw, 340px);}
  }
  @media (max-width:560px){
    .tc-slideshow{gap:4px;}
    .tc-track{--tc-step:112px; height:clamp(220px, 62vw, 300px);}
    .tc-arrow{width:34px; height:34px;}
  }

  /* =========================================================
     FEATURE VIDEO — one video per section, standalone (not a
     carousel). Alternates media/copy sides. Video only loads and
     plays once its section actually scrolls into view.
  ========================================================= */
  .feature-video{padding:0; border-bottom:1px solid var(--line);}
  .feature-video .fv-grid{
    display:grid; grid-template-columns:1fr 1fr; min-height:640px;
  }
  .feature-video.flip .fv-grid{direction:rtl;}
  .feature-video.flip .fv-grid > *{direction:ltr;}

  .fv-media{position:relative; --ph1:#E4D7C1; --ph2:#54707A;}
  .fv-media video{position:absolute; inset:0; width:100%; height:100%; object-fit:cover; z-index:1; background:var(--sand);}
  .fv-media::after{
    content:""; position:absolute; inset:0; z-index:1; pointer-events:none;
    background:linear-gradient(rgba(53,42,34,0) 60%, rgba(53,42,34,.28));
  }

  .fv-mute{
    position:absolute; top:22px; right:22px; z-index:3;
    width:34px; height:34px; border-radius:50%; border:1px solid rgba(255,253,249,.55);
    background:rgba(53,42,34,.35); color:var(--white); backdrop-filter:blur(6px);
    display:flex; align-items:center; justify-content:center; transition:background .25s, opacity .3s;
    opacity:0;
  }
  .feature-video.in-view .fv-mute{opacity:1;}
  .fv-mute:hover{background:rgba(53,42,34,.6);}
  .fv-mute svg{width:15px; height:15px;}
  .fv-mute.is-muted svg path:last-child{opacity:.35;}

  .fv-copy{
    padding:clamp(40px,6vw,88px); display:flex; flex-direction:column; justify-content:center;
    background:var(--ivory);
  }
  .fv-copy h2{font-size:clamp(1.9rem,3.2vw,2.6rem); margin:14px 0 18px; line-height:1.15;}
  .fv-copy p{font-size:.98rem; line-height:1.8; color:var(--espresso-70); max-width:46ch; margin:0 0 24px;}
  .fv-points{list-style:none; margin:0 0 30px; padding:0; display:flex; flex-direction:column; gap:12px; max-width:46ch;}
  .fv-points li{
    font-size:.86rem; color:var(--espresso-70); line-height:1.5;
    padding-left:22px; position:relative;
  }
  .fv-points li::before{
    content:""; position:absolute; left:0; top:.5em; width:11px; height:1px; background:var(--burgundy);
  }

  @media (max-width:900px){
    .feature-video .fv-grid{grid-template-columns:1fr; min-height:auto;}
    .feature-video.flip .fv-grid{direction:ltr;}
    .fv-media{aspect-ratio:4/3;}
    .fv-copy{padding:48px clamp(22px,6vw,40px);}
  }

  /* =========================================================
     FOOTER
  ========================================================= */
  footer{background:var(--espresso); color:rgba(255,253,249,.82); padding:80px 0 30px;}
  .footer-logo{text-align:center; margin-bottom:60px;}
  .footer-logo-img{height:clamp(64px,9vw,110px); width:auto; margin:0 auto; display:block;}
  .footer-logo .big{font-family:'Fraunces',serif; font-size:clamp(2.4rem,7vw,4.6rem); color:var(--white); letter-spacing:.06em;}
  .footer-logo .small{font-size:.66rem; letter-spacing:.34em; color:rgba(255,253,249,.55); text-transform:uppercase; margin-top:14px;}
  .footer-cols{display:grid; grid-template-columns:repeat(4,1fr); gap:30px; border-top:1px solid rgba(255,253,249,.14); padding-top:50px;}
  .footer-col h5{font-size:.68rem; letter-spacing:.16em; text-transform:uppercase; color:rgba(255,253,249,.5); margin:0 0 18px; font-weight:700;}
  .footer-col a{display:block; font-size:.87rem; color:rgba(255,253,249,.82); margin-bottom:12px; transition:color .3s;}
  .footer-col a:hover{color:var(--white);}
  .footer-bottom{
    display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:14px;
    margin-top:60px; padding-top:26px; border-top:1px solid rgba(255,253,249,.14);
    font-size:.76rem; color:rgba(255,253,249,.55);
  }
  .social-row{display:flex; gap:20px;}

  /* =========================================================
     RESPONSIVE
  ========================================================= */
  @media (max-width: 1080px){
    .product-grid, .product-panel.active{grid-template-columns:repeat(2,1fr);}
    .cat-grid{grid-template-columns:repeat(2,1fr); gap:16px;}
    .footer-cols{grid-template-columns:repeat(2,1fr); row-gap:40px;}
  }
  @media (max-width: 860px){
    .hero{grid-template-columns:1fr; min-height:76vh;}
    .hero .photo{aspect-ratio:1/1; height:auto;}
    .collab-grid, .story-grid{grid-template-columns:1fr; gap:36px;}
    .asap-grid{grid-template-columns:1fr; gap:30px;}
  }
  @media (max-width: 560px){
    section{padding:66px 0;}
    .footer-bottom{flex-direction:column; align-items:flex-start;}
  }
</style>
</head>
<body>

<!-- ============ ANNOUNCEMENT BAR ============ -->
<div class="announce">
  <div class="announce-track">
    <span>FREE SHIPPING ON ORDERS OVER ₱3,000</span>
    <span>DESIGNED &amp; MADE IN THE PHILIPPINES</span>
    <span>NEW DROP EVERY MONTH</span>
    <span>FREE SHIPPING ON ORDERS OVER ₱3,000</span>
    <span>DESIGNED &amp; MADE IN THE PHILIPPINES</span>
    <span>NEW DROP EVERY MONTH</span>
  </div>
</div>

<!-- ============ HEADER ============ -->
<header>
  <div class="header-row">
    <button class="icon-btn hamburger" id="hamburgerBtn" aria-label="Open menu">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 6h18M3 12h18M3 18h18" stroke-linecap="round"/></svg>
    </button>
    <a href="#top" class="logo" aria-label="Dear SEA Swimwear">
      <img src="data:image/png;base64,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" alt="Dear SEA">
    </a>
    <a href="#top" class="mobile-logo" aria-label="Dear SEA Swimwear">
      <img src="data:image/png;base64,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" alt="Dear SEA">
    </a>

    <nav class="primary-nav">
      <a href="#products">Shop</a>
      <a href="#categories">Collections</a>
      <a href="#collaborate">Looking for Collaboration</a>
      <a href="#story">About</a>
      <a href="#locations">Contact</a>
    </nav>

  
  </div>
</header>

<div class="scrim" id="scrim"></div>
<div class="mobile-menu" id="mobileMenu">
  <div class="mobile-menu-top"><button class="close-btn" id="closeMenu" aria-label="Close menu">&times;</button></div>
  <a href="#products">Shop</a>
  <a href="#categories">Collections</a>
  <a href="#collaborate">Collaboration</a>
  <a href="#story">About</a>
  <a href="#locations">Contact</a>
</div>

<div id="top"></div>

<!-- ============ HERO ============ -->
<section class="hero" style="padding:0;">
  <div class="photo"><img class="photo-img" src="files/images/1.webp" alt="Dear SEA campaign photo 1" loading="eager" onerror="this.remove()"></div>
  <div class="photo"><img class="photo-img" src="files/images/2.webp" alt="Dear SEA campaign photo 2" loading="eager" onerror="this.remove()"></div>
  <div class="hero-overlay">
    <div class="hero-card reveal">
      <span class="eyebrow">Open Call — Summer Collective</span>
      <h1>Looking for <em>collaboration?</em></h1>
      <p>Dear SEA Swimwear is looking for creators, models, photographers, stylists, and creatives who share our love for swim, summer, and effortless coastal style.</p>
      <div class="btn-row">
        <a href="apply.php" class="btn btn-solid">Become a Dear SEA Creator</a>
        <a href="#products" class="btn btn-outline">Shop the Collection</a>
      </div>
    </div>
  </div>
  <div class="scroll-cue"><span>Scroll</span><div class="line"></div></div>
</section>

<!-- ============ FEATURE VIDEO 1: FABRIC ============ -->
<section class="feature-video" id="fabric" data-video="files/video/play9.mp4">
  <div class="fv-grid">
    <div class="fv-media photo" style="--ph1:#E4D7C1; --ph2:#54707A;">
      <video muted loop playsinline preload="none"></video>
      <button class="fv-mute" aria-label="Mute video">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 9v6h4l5 5V4L8 9H4z"/><path d="M17.5 8.5a5 5 0 0 1 0 7" stroke-linecap="round"/></svg>
      </button>
    </div>
    <div class="fv-copy reveal">
      <span class="eyebrow">Made To Move</span>
      <h2>Fabric built for real summers</h2>
      <p>Every Dear SEA piece starts with a fabric that can keep up — with saltwater, chlorine, long boat rides, and afternoons that stretch past sunset. It's engineered to move with you, not against you.</p>
      <ul class="fv-points">
        <li>Four-way stretch performance fabric</li>
        <li>UPF 50+ built-in sun protection</li>
        <li>Resists chlorine and saltwater fading</li>
        <li>Quick-dry — ready again in under 20 minutes</li>
      </ul>
      <a href="#products" class="btn btn-outline">Shop the Collection</a>
    </div>
  </div>
</section>

<svg class="wave-rule" viewBox="0 0 1200 22" preserveAspectRatio="none"><path d="M0 11 Q 50 0, 100 11 T 200 11 T 300 11 T 400 11 T 500 11 T 600 11 T 700 11 T 800 11 T 900 11 T 1000 11 T 1100 11 T 1200 11"/></svg>

<!-- ============ COLLABORATION INTRO + FORM ============ -->
<section class="collab-intro" id="collaborate">
  <div class="wrap">
    <span class="eyebrow">Looking For Collaboration</span>
    <div class="collab-grid reveal" style="margin-top:22px;">
      <div>
        <p>Dear SEA is more than swimwear. We're building a community of creators, dreamers, and creatives who believe in making summer feel unforgettable.</p>
        <p>Whether you're a model, content creator, photographer, stylist, videographer, or simply someone with a unique perspective, we'd love to hear from you.</p>
        <a href="apply.php" class="btn btn-solid">Let's Collaborate</a>
      </div>
      <div class="ms" data-set="collab" style="aspect-ratio:4/5;"></div>
    </div>
  </div>
</section>

<!-- ============ PRODUCTS ============ -->
<section id="products">
  <div class="wrap">
    <div class="section-head reveal">
      <div>
        <span class="eyebrow">The Edit</span>
        <h2>Shop Dear SEA</h2>
      </div>
      <p class="lede">Effortless silhouettes and considered details, designed to move between the shoreline and the city.</p>
    </div>

    <div class="tabs reveal" id="productTabs">
      <button class="tab-btn active" data-tab="tops">Tops</button>
      <button class="tab-btn" data-tab="bottoms">Bottoms</button>
      <button class="tab-btn" data-tab="fullpiece">Full Piece</button>
      <button class="tab-btn" data-tab="apparel">Apparel</button>
    </div>

    <div class="product-panel active" id="panel-tops"></div>
    <div class="product-panel" id="panel-bottoms"></div>
    <div class="product-panel" id="panel-fullpiece"></div>
    <div class="product-panel" id="panel-apparel"></div>

    <div class="show-more-row reveal">
      <button class="btn btn-outline" id="showMoreBtn">Show More</button>
    </div>
  </div>
</section>

<!-- ============ PRODUCT DETAIL MODAL ============ -->
<div class="modal-scrim" id="productScrim"></div>
<div class="product-modal" id="productModal" role="dialog" aria-modal="true" aria-label="Product details">
  <button class="modal-close" id="modalClose" aria-label="Close">&times;</button>
  <div class="modal-photo-col">
    <div class="modal-photo" id="modalPhoto"><img class="photo-img" id="modalPhotoImg" alt="" loading="lazy" onerror="this.style.display='none'"></div>
    <div class="modal-thumbs" id="modalThumbs">
      <button class="modal-thumb active" data-swap="main" aria-label="View image 1"><img alt="" loading="lazy" onerror="this.style.display='none'"></button>
      <button class="modal-thumb" data-swap="alt" aria-label="View image 2"><img alt="" loading="lazy" onerror="this.style.display='none'"></button>
    </div>
  </div>
  <div class="modal-info">
    <span class="eyebrow" id="modalCat">Dear SEA</span>
    <h3 id="modalName">Product Name</h3>
    <div class="modal-price-row">
      <span class="modal-price" id="modalPrice">₱0.00 PHP</span>
      <span class="modal-color" id="modalColor"></span>
    </div>
    <p class="modal-desc" id="modalDesc"></p>

    <ul class="modal-details" id="modalDetails"></ul>

    <p class="modal-shipnote">Estimated delivery 2–4 business days · Free shipping on orders over ₱3,000</p>
  </div>
</div>

<svg class="wave-rule" viewBox="0 0 1200 22" preserveAspectRatio="none"><path d="M0 11 Q 50 22, 100 11 T 200 11 T 300 11 T 400 11 T 500 11 T 600 11 T 700 11 T 800 11 T 900 11 T 1000 11 T 1100 11 T 1200 11"/></svg>

<!-- ============ CATEGORIES ============ -->
<section id="categories">
  <div class="wrap">
    <div class="section-head reveal">
      <div><span class="eyebrow">Shop By Category</span><h2>Find your fit</h2></div>
    </div>
    <div class="cat-grid reveal">
      <a class="cat-card" href="#products" data-cat="tops" aria-label="Shop Tops">
        <div class="photo" style="--ph1:#E4D7C1; --ph2:#54707A;"><img class="photo-img" src="files/images/tops.webp" alt="Tops" loading="lazy" onerror="this.remove()"></div>
      </a>
      <a class="cat-card" href="#products" data-cat="bottoms" aria-label="Shop Bottoms">
        <div class="photo" style="--ph1:#DCCDB2; --ph2:#7C3E3F;"><img class="photo-img" src="files/images/bottoms.webp" alt="Bottoms" loading="lazy" onerror="this.remove()"></div>
      </a>
      <a class="cat-card" href="#products" data-cat="fullpiece" aria-label="Shop Fullpiece">
        <div class="photo" style="--ph1:#EADFC9; --ph2:#3E5860;"><img class="photo-img" src="files/images/fullpiece.jpg" alt="Fullpiece" loading="lazy" onerror="this.remove()"></div>
      </a>
      <a class="cat-card" href="#products" data-cat="apparel" aria-label="Shop Apparel">
        <div class="photo" style="--ph1:#D8C6AE; --ph2:#628089;"><img class="photo-img" src="files/images/apparel.webp" alt="Apparel" loading="lazy" onerror="this.remove()"></div>
      </a>
    </div>
  </div>
</section>

<!-- ============ FEATURE VIDEO 2: CRAFTSMANSHIP ============ -->
<section class="feature-video flip" id="craft" data-video="files/video/play10.mp4">
  <div class="fv-grid">
    <div class="fv-media photo" style="--ph1:#3A2E27; --ph2:#628089;">
      <video muted loop playsinline preload="none"></video>
      <button class="fv-mute" aria-label="Mute video">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 9v6h4l5 5V4L8 9H4z"/><path d="M17.5 8.5a5 5 0 0 1 0 7" stroke-linecap="round"/></svg>
      </button>
    </div>
    <div class="fv-copy reveal">
      <span class="eyebrow">The Process</span>
      <h2>Behind every stitch</h2>
      <p>Dear SEA pieces are cut and sewn in small batches, not mass-produced overnight. Every seam is reinforced by hand, every trim checked before it ships — details you feel more than you see.</p>
      <ul class="fv-points">
        <li>Cut and sewn in small batches</li>
        <li>Reinforced flatlock seams for durability</li>
        <li>Hand-finished trims and closures</li>
        <li>Designed and made in the Philippines</li>
      </ul>
      <a href="#story" class="btn btn-outline">Read Our Story</a>
    </div>
  </div>
</section>

<!-- ============ BRAND STORY ============ -->
<section class="story" id="story">
  <div class="wrap story-grid">
    <div class="reveal">
      <span class="eyebrow">Our Story</span>
      <h2>Made for your sea</h2>
      <p>Dear SEA Swimwear was created for slow mornings, salty skin, golden afternoons, and every little moment that feels like summer.</p>
      <p>Designed with effortless silhouettes, thoughtful details, and pieces made to be mixed, matched, and worn your way.</p>
      <a href="#products" class="btn btn-outline" style="border-color:rgba(255,253,249,.6); color:var(--white);">Explore the Collection</a>
    </div>
    <div class="ms reveal" data-set="story"></div>
  </div>
</section>

<!-- ============ CAMPAIGN ============ -->
<section class="campaign">
  <div class="photo dark"><img class="photo-img" src="files/images/4.webp" alt="Dear SEA casting call" loading="lazy" onerror="this.remove()"></div>
  <div class="wrap campaign-inner reveal">
    <span class="eyebrow" style="color:rgba(255,253,249,.75);">Casting Call</span>
    <h2>Make waves with us</h2>
    <p>We're looking for people who bring their own energy, creativity, and perspective to the sea.</p>
    <div class="btn-row">
      <a href="apply.php" class="btn btn-solid">Collaborate with Dear SEA</a>
      <a href="#products" class="btn btn-outline">Explore the Collection</a>
    </div>
  </div>
</section>

<!-- ============ FEATURE VIDEO 3: CARE GUIDE ============ -->
<section class="feature-video" id="care" data-video="files/video/play13.mp4">
  <div class="fv-grid">
    <div class="fv-media photo" style="--ph1:#159A99; --ph2:#E4D7C1;">
      <video muted loop playsinline preload="none"></video>
      <button class="fv-mute" aria-label="Mute video">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 9v6h4l5 5V4L8 9H4z"/><path d="M17.5 8.5a5 5 0 0 1 0 7" stroke-linecap="round"/></svg>
      </button>
    </div>
    <div class="fv-copy reveal">
      <span class="eyebrow">Care Guide</span>
      <h2>Make it last all summer</h2>
      <p>A little care goes a long way. Chlorine, sunscreen, and rough pool edges are the biggest reasons swimwear fades early — a quick rinse after every wear keeps colors and elastic in shape for seasons, not just weeks.</p>
      <ul class="fv-points">
        <li>Rinse in cool water after each wear</li>
        <li>Hand wash with a mild, chlorine-free soap</li>
        <li>Lay flat to dry, out of direct sun</li>
        <li>Avoid rough pool decks, rocks, and velcro</li>
      </ul>
      <a href="#products" class="btn btn-outline">Shop Now</a>
    </div>
  </div>
</section>

<!-- ============ ASAP BANNER ============ -->
<section class="asap">
  <div class="wrap asap-grid reveal">
    <div class="ms asap-photo" data-set="asap"></div>
    <div class="asap-copy">
      <span class="eyebrow">Need It Fast?</span>
      <h2>Need a swimsuit ASAP?</h2>
      <p>We've got you. Choose same-day pickup at checkout for select pieces, ready before you head out the door.</p>
      <a href="#products" class="btn btn-solid">Shop Now</a>
    </div>
  </div>
</section>

<!-- ============ FEATURE VIDEO 4: COMMUNITY ============ -->
<section class="feature-video flip" id="community" data-video="files/video/play11.mp4">
  <div class="fv-grid">
    <div class="fv-media photo" style="--ph1:#512536; --ph2:#E4D7C1;">
      <video muted loop playsinline preload="none"></video>
      <button class="fv-mute" aria-label="Mute video">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 9v6h4l5 5V4L8 9H4z"/><path d="M17.5 8.5a5 5 0 0 1 0 7" stroke-linecap="round"/></svg>
      </button>
    </div>
    <div class="fv-copy reveal">
      <span class="eyebrow">Community</span>
      <h2>Real moments, real Dear SEA</h2>
      <p>Some of our favorite content isn't shot in a studio — it's sent to us by the people already wearing Dear SEA on real beaches, real boat trips, real ordinary Tuesdays. Here's a bit of that, straight from the community.</p>
      <a href="apply.php" class="btn btn-outline">Become a Creator</a>
    </div>
  </div>
</section>

<!-- ============ GALLERY: SLIDESHOW + VIDEO ============ -->
<section class="tiktok-carousel" id="gallery">
  <div class="wrap">
    <div class="tc-head reveal">
      <span class="eyebrow">From TikTok</span>
      <h2>Watch the looks in motion</h2>
      <p>Real clips from real customers — swipe through to see how Dear SEA moves, fits, and holds up out on the water.</p>
      <a href="https://www.tiktok.com/@dearsea" target="_blank" rel="noopener" class="tc-cta">
        Follow along on TikTok
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M7 17 17 7M9 7h8v8" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
    </div>

    <div class="tc-slideshow reveal" id="tcSlideshow" tabindex="0" role="region" aria-label="Looks in motion, from TikTok">
      <button class="tc-arrow prev" id="tcPrev" aria-label="Previous look">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M15 5l-7 7 7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </button>

      <div class="tc-track" id="tcTrack"></div>

      <button class="tc-arrow next" id="tcNext" aria-label="Next look">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </button>
    </div>

    <div class="tc-dots" id="tcDots"></div>
  </div>
</section>

<!-- ============ FOOTER ============ -->
<footer>
  <div class="wrap">
    <div class="footer-logo">
      <img src="files/logo3.png" alt="Dear SEA" class="footer-logo-img">
      <div class="small">Dear SEA Swimwear</div>
    </div>
    <div class="footer-cols">
      <div class="footer-col">
        <h5>Shop</h5>
        <a href="#products">Shop All</a>
        <a href="#products" data-cat="tops">Tops</a>
        <a href="#products" data-cat="bottoms">Bottoms</a>
        <a href="#products" data-cat="fullpiece">Full Piece</a>
        <a href="#products" data-cat="apparel">Apparel</a>
        <a href="#categories">Collections</a>
      </div>
      <div class="footer-col">
        <h5>About</h5>
        <a href="#collaborate">Collaborate</a>
        <a href="#story">About</a>
        <a href="#locations">Contact</a>
      </div>
      <div class="footer-col">
        <h5>Support</h5>
        <a href="#">Shipping</a>
        <a href="#">Returns &amp; Exchanges</a>
        <a href="#">FAQ</a>
        <a href="#locations">Find a Location</a>
      </div>
      <div class="footer-col">
        <h5>Follow</h5>
        <a href="https://www.instagram.com/dearseastories/" target="_blank" rel="noopener">Instagram</a>
        <a href="https://www.tiktok.com/@dearsea" target="_blank" rel="noopener">TikTok</a>
        <a href="https://www.lazada.com.ph/shop/dear-sea" target="_blank" rel="noopener">Lazada</a>
        <a href="https://shopee.ph/dearsea" target="_blank" rel="noopener">Shopee</a>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2026 Dear SEA Swimwear. All rights reserved.</span>
      <div class="social-row">
        <a href="https://www.instagram.com/dearseastories/" target="_blank" rel="noopener" aria-label="Instagram">IG</a>
        <a href="https://www.tiktok.com/@dearsea" target="_blank" rel="noopener" aria-label="TikTok">TT</a>
        <a href="https://www.lazada.com.ph/shop/dear-sea" target="_blank" rel="noopener" aria-label="Lazada">LZD</a>
        <a href="https://shopee.ph/dearsea" target="_blank" rel="noopener" aria-label="Shopee">SP</a>
      </div>
    </div>
  </div>
</footer>

<script>
(function(){
  // =====================================================================
  // PRODUCT DATA — this is the ONLY place you need to edit to add,
  // remove, or update products. Add a new object to add a product,
  // delete an object to remove one. `cats` is an array of one or more
  // of: "tops", "bottoms", "fullpiece", "apparel" — a set can belong
  // to more than one tab (e.g. a bikini set shows under both Tops
  // and Bottoms).
  // `ph1`/`ph2` are the two placeholder colors used for the hover swap
  // and modal thumbnails — swap for real photos later by editing
  // renderPanel()/openProduct() to output <img src="..."> instead.
  // `details` is a short bullet list shown in the product modal.
  // =====================================================================
var products = [

  // =========================
  // SWIM TOPS
  // =========================

  {
    id: "amihan-top",
    name: "Amihan Top",
    price: 1599,
    color: "Navy Blue",
    cats: ["tops"],
    ph1: "#1E2D4A",
    ph2: "#54707A",
    desc: "A classic swim top with a clean silhouette, designed for comfortable movement in and out of the water.",
    details: [
      "Comfortable swim fit",
      "Adjustable straps",
      "Quick-dry fabric",
      "Hand wash cold, line dry"
    ]
  },

  {
    id: "aniwa-top",
    name: "Aniwa Top",
    price: 1699,
    color: "Ivory White",
    cats: ["tops"],
    ph1: "#F1E9DC",
    ph2: "#54707A",
    desc: "A feminine ruffled swim top with a soft layered finish and comfortable supportive fit.",
    details: [
      "Layered ruffle detail",
      "Comfortable swim fit",
      "Quick-dry fabric",
      "Hand wash cold, line dry"
    ]
  },

  {
    id: "araw",
    name: "Araw",
    price: 1599,
    color: "Brown",
    cats: ["tops"],
    ph1: "#5A4038",
    ph2: "#DCCDB2",
    desc: "A minimalist brown swim top with a flattering triangle-inspired silhouette.",
    details: [
      "Minimal silhouette",
      "Adjustable straps",
      "Four-way stretch fabric",
      "Hand wash cold, line dry"
    ]
  },

  {
    id: "dahon",
    name: "Dahon",
    price: 1699,
    color: "Burgundy",
    cats: ["tops"],
    ph1: "#5A2028",
    ph2: "#DCCDB2",
    desc: "A rich burgundy triangle swim top featuring an adjustable fit and signature center detail.",
    details: [
      "Triangle silhouette",
      "Adjustable fit",
      "Center detail",
      "Hand wash cold, line dry"
    ]
  },

  {
    id: "hiyas",
    name: "Hiyas",
    price: 1699,
    color: "Navy Blue",
    cats: ["tops"],
    ph1: "#24385C",
    ph2: "#E4D7C1",
    desc: "A versatile navy swim top with a sporty yet feminine silhouette.",
    details: [
      "Sporty silhouette",
      "Comfortable fit",
      "Four-way stretch fabric",
      "Hand wash cold, line dry"
    ]
  },

  {
    id: "kintab-top",
    name: "Kintab Top",
    price: 1799,
    color: "Taupe",
    cats: ["tops"],
    ph1: "#927A68",
    ph2: "#E4D7C1",
    desc: "A statement swim top finished with delicate shell-inspired embellishments.",
    details: [
      "Decorative shell details",
      "Adjustable straps",
      "Four-way stretch fabric",
      "Hand wash cold, line dry"
    ]
  },

  {
    id: "marilag",
    name: "Marilag",
    price: 1499,
    color: "Red",
    cats: ["tops"],
    ph1: "#D92E3A",
    ph2: "#F2D6C8",
    desc: "A vibrant red swim top with a classic feminine silhouette.",
    details: [
      "Classic swim silhouette",
      "Comfortable fit",
      "Quick-dry fabric",
      "Hand wash cold, line dry"
    ]
  },

  {
    id: "mayari",
    name: "Mayari",
    price: 1699,
    color: "Burgundy",
    cats: ["tops"],
    ph1: "#512536",
    ph2: "#E4D7C1",
    desc: "A sophisticated burgundy swim top paired with a sleek fitted silhouette.",
    details: [
      "Fitted silhouette",
      "Soft stretch fabric",
      "Comfortable swim fit",
      "Hand wash cold, line dry"
    ]
  },

  {
    id: "mayumi-top-bandana",
    name: "Mayumi top + bandana",
    price: 1699,
    color: "Taupe",
    cats: ["tops"],
    ph1: "#756451",
    ph2: "#E4D7C1",
    desc: "A coordinated swim set featuring a stylish top and matching bandana accessory.",
    details: [
      "Matching bandana included",
      "Comfortable swim fit",
      "Quick-dry fabric",
      "Hand wash cold, line dry"
    ]
  },


  // =========================
  // SWIM BOTTOMS
  // =========================

  {
    id: "alapap-bottom",
    name: "Alapap Bottom",
    price: 1799,
    color: "Ivory",
    cats: ["bottoms"],
    ph1: "#E4D7C1",
    ph2: "#54707A",
    desc: "A feminine swim bottom with layered detailing designed for a coordinated resort look.",
    details: [
      "Layered detail",
      "Comfortable coverage",
      "Four-way stretch fabric",
      "Hand wash cold, line dry"
    ]
  },

  {
    id: "bawis",
    name: "Bawis",
    price: 1499,
    color: "Olive",
    cats: ["bottoms"],
    ph1: "#656B54",
    ph2: "#DCCDB2",
    desc: "A classic mid-rise swim bottom with a clean, comfortable silhouette.",
    details: [
      "Mid-rise fit",
      "Comfortable coverage",
      "Four-way stretch fabric",
      "Hand wash cold, line dry"
    ]
  },

  {
    id: "balud",
    name: "Balud",
    price: 1499,
    color: "Teal",
    cats: ["bottoms"],
    ph1: "#159A99",
    ph2: "#E4D7C1",
    desc: "A vibrant teal swim bottom designed with a flattering high-cut silhouette.",
    details: [
      "High-cut silhouette",
      "Comfortable fit",
      "Four-way stretch fabric",
      "Hand wash cold, line dry"
    ]
  },

  {
    id: "baybay-shorts",
    name: "Baybay + Shorts",
    price: 1799,
    color: "Burgundy",
    cats: ["bottoms"],
    ph1: "#572632",
    ph2: "#E4D7C1",
    desc: "A relaxed swim short designed for easy movement and effortless beach styling.",
    details: [
      "Relaxed short silhouette",
      "Comfortable waistband",
      "Quick-dry fabric",
      "Hand wash cold, line dry"
    ]
  },

  {
    id: "baybay-bottom",
    name: "Baybay Bottom",
    price: 1499,
    color: "Red",
    cats: ["bottoms"],
    ph1: "#D92E3A",
    ph2: "#F2D6C8",
    desc: "A bold red swim bottom with a flattering classic silhouette.",
    details: [
      "Classic swim fit",
      "Comfortable coverage",
      "Four-way stretch fabric",
      "Hand wash cold, line dry"
    ]
  },

  {
    id: "baybay-bottom-skirt",
    name: "Baybay Bottom + Skirt",
    price: 1799,
    color: "Burgundy",
    cats: ["bottoms"],
    ph1: "#572632",
    ph2: "#E4D7C1",
    desc: "A coordinated swim bottom and wrap skirt combination for effortless beach-to-resort styling.",
    details: [
      "Matching bottom and skirt",
      "Layering-friendly design",
      "Quick-dry fabric",
      "Hand wash cold, line dry"
    ]
  },


  // =========================
  // FULL PIECE
  // =========================

  {
    id: "hiraya-full-piece",
    name: "Hiraya Full Piece",
    price: 2999,
    color: "Brown",
    cats: ["fullpiece"],
    ph1: "#665042",
    ph2: "#DCCDB2",
    desc: "A sculpting full-piece swimsuit with a flattering fitted silhouette and comfortable support.",
    details: [
      "Sculpting silhouette",
      "Comfortable swim fit",
      "Four-way stretch fabric",
      "Hand wash cold, line dry"
    ]
  },

  {
    id: "padayon-full-piece",
    name: "Padayon Full Piece",
    price: 2999,
    color: "Lime Green",
    cats: ["fullpiece"],
    ph1: "#9AB77C",
    ph2: "#54707A",
    desc: "A fresh green full-piece swimsuit with a distinctive textured finish for a statement beach look.",
    details: [
      "Textured finish",
      "Full-piece coverage",
      "Four-way stretch fabric",
      "Hand wash cold, line dry"
    ]
  },

  {
    id: "sinag-full-piece",
    name: "Sinag Full Piece",
    price: 2999,
    color: "Multi Color",
    cats: ["fullpiece"],
    ph1: "#D8C6AE",
    ph2: "#54707A",
    desc: "A sophisticated full-piece swimsuit designed for comfortable resort days and poolside lounging.",
    details: [
      "Full-piece silhouette",
      "Comfortable coverage",
      "Quick-dry fabric",
      "Hand wash cold, line dry"
    ]
  },


  // =========================
  // APPAREL
  // =========================
  // WALA MUNANG PRODUCTS SA SCREENSHOT
  // KAYA EMPTY ANG APPAREL CATEGORY


];


var CATS = ["tops", "bottoms", "fullpiece", "apparel"];
  var INITIAL_COUNT = 4;   // how many cards show before "Show More"
  var expanded = {};
  CATS.forEach(function(c){ expanded[c] = false; });

  function fmtPrice(n){ return "₱" + n.toLocaleString("en-PH", {minimumFractionDigits:2}) + " PHP"; }

  function renderPanel(panelId, filter){
    var el = document.getElementById(panelId);
    var list = products.filter(function(p){ return p.cats.indexOf(filter) !== -1; });
    var html = "";
    list.forEach(function(p, i){
      var isExtra = i >= INITIAL_COUNT;
      html += ''+
      '<div class="p-card reveal in'+(isExtra ? ' hidden extra' : '')+'" data-id="'+p.id+'">'+
        '<div class="photo" style="--ph1:'+p.ph1+'; --ph2:'+p.ph2+';" data-id="'+p.id+'">'+
          '<img class="photo-img" src="files/images/products/'+p.id+'-1.jpg" alt="'+p.name+'" loading="lazy" onerror="this.remove()">'+
          '<div class="alt" style="--ph1:'+p.ph2+'; --ph2:'+p.ph1+';">'+
            '<img class="photo-img" src="files/images/products/'+p.id+'-2.jpg" alt="'+p.name+' alternate view" loading="lazy" onerror="this.remove()">'+
          '</div>'+
          '<div class="p-dots"><span class="on"></span><span></span></div>'+
          '<div class="p-scrim" data-id="'+p.id+'">'+
            '<div class="quick-add" data-id="'+p.id+'">'+
              '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"><path d="M8 4H4v4M20 8V4h-4M16 20h4v-4M4 16v4h4" stroke-linecap="round" stroke-linejoin="round"/></svg>'+
            '</div>'+
          '</div>'+
        '</div>'+
        '<div class="p-info" data-id="'+p.id+'">'+
          '<div><h4>'+p.name+'</h4><div class="color">'+p.color+'</div></div>'+
          '<div class="p-price">'+fmtPrice(p.price)+'</div>'+
        '</div>'+
      '</div>';
    });
    el.innerHTML = html || '<p class="empty-panel">More '+filter+' coming soon.</p>';
    updateShowMoreVisibility();
  }
  CATS.forEach(function(c){ renderPanel("panel-"+c, c); });

  function activeFilter(){
    return document.querySelector(".product-panel.active").id.replace("panel-","");
  }

  function updateShowMoreVisibility(){
    var filter = activeFilter();
    var total = products.filter(function(p){ return p.cats.indexOf(filter) !== -1; }).length;
    var btn = document.getElementById("showMoreBtn");
    if(total <= INITIAL_COUNT){ btn.style.display = "none"; return; }
    btn.style.display = "inline-flex";
    btn.textContent = expanded[filter] ? "Show Less" : "Show More";
  }

  document.getElementById("showMoreBtn").addEventListener("click", function(){
    var filter = activeFilter();
    expanded[filter] = !expanded[filter];
    var panel = document.getElementById("panel-"+filter);
    panel.querySelectorAll(".p-card.extra").forEach(function(card){
      card.classList.toggle("hidden", !expanded[filter]);
    });
    updateShowMoreVisibility();
    if(!expanded[filter]){
      panel.scrollIntoView({behavior:"smooth", block:"start"});
    }
  });

  function selectTab(cat){
    var btn = document.querySelector('.tab-btn[data-tab="'+cat+'"]');
    if(!btn) return;
    document.querySelectorAll(".tab-btn").forEach(function(b){ b.classList.remove("active"); });
    document.querySelectorAll(".product-panel").forEach(function(p){ p.classList.remove("active"); });
    btn.classList.add("active");
    document.getElementById("panel-"+cat).classList.add("active");
    updateShowMoreVisibility();
  }

  // ---------- tabs ----------
  document.querySelectorAll(".tab-btn").forEach(function(btn){
    btn.addEventListener("click", function(){ selectTab(btn.dataset.tab); });
  });

  // ---------- category tiles / footer links jump straight to a tab ----------
  document.querySelectorAll("[data-cat]").forEach(function(link){
    link.addEventListener("click", function(e){
      e.preventDefault();
      selectTab(link.dataset.cat);
      document.getElementById("products").scrollIntoView({behavior:"smooth", block:"start"});
    });
  });

  // ---------- product detail modal ----------
  var pModal = document.getElementById("productModal"), pScrim = document.getElementById("productScrim");
  var currentProductId = null;

  function imgSrc(id, n){ return "files/images/products/"+id+"-"+n+".jpg"; }

  function setModalPhoto(ph1, ph2, src){
    var mp = document.getElementById("modalPhoto");
    mp.style.setProperty("--ph1", ph1);
    mp.style.setProperty("--ph2", ph2);
    var img = document.getElementById("modalPhotoImg");
    if(!img){
      img = document.createElement("img");
      img.className = "photo-img"; img.id = "modalPhotoImg"; img.alt = "";
      img.loading = "lazy"; img.onerror = function(){ this.style.display = "none"; };
      mp.appendChild(img);
    }
    img.style.display = "";
    img.src = src;
  }

  function openProduct(id){
    var p = products.find(function(x){ return x.id === id; });
    if(!p) return;
    currentProductId = id;
    setModalPhoto(p.ph1, p.ph2, imgSrc(id,1));
    document.getElementById("modalName").textContent = p.name;
    document.getElementById("modalPrice").textContent = fmtPrice(p.price);
    document.getElementById("modalDesc").textContent = p.desc;
    document.getElementById("modalColor").textContent = p.color;

    var detailsEl = document.getElementById("modalDetails");
    detailsEl.innerHTML = (p.details || []).map(function(d){ return "<li>"+d+"</li>"; }).join("");

    var mainThumb = document.querySelector('.modal-thumb[data-swap="main"]');
    var altThumb = document.querySelector('.modal-thumb[data-swap="alt"]');
    mainThumb.style.setProperty("--ph1", p.ph1); mainThumb.style.setProperty("--ph2", p.ph2);
    altThumb.style.setProperty("--ph1", p.ph2); altThumb.style.setProperty("--ph2", p.ph1);
    var mainImg = mainThumb.querySelector("img"), altImg = altThumb.querySelector("img");
    mainImg.style.display = ""; mainImg.src = imgSrc(id,1);
    altImg.style.display = ""; altImg.src = imgSrc(id,2);

    document.querySelectorAll(".modal-thumb").forEach(function(t){ t.classList.remove("active"); });
    mainThumb.classList.add("active");

    pModal.classList.add("show"); pScrim.classList.add("show");
    pModal.setAttribute("aria-hidden","false");
  }
  function closeProduct(){ pModal.classList.remove("show"); pScrim.classList.remove("show"); }
  document.getElementById("modalClose").addEventListener("click", closeProduct);
  pScrim.addEventListener("click", closeProduct);
  document.addEventListener("keydown", function(e){ if(e.key==="Escape") closeProduct(); });

  document.querySelectorAll(".modal-thumb").forEach(function(t){
    t.addEventListener("click", function(){
      document.querySelectorAll(".modal-thumb").forEach(function(x){ x.classList.remove("active"); });
      t.classList.add("active");
      var ph1 = getComputedStyle(t).getPropertyValue("--ph1").trim();
      var ph2 = getComputedStyle(t).getPropertyValue("--ph2").trim();
      var n = t.dataset.swap === "alt" ? 2 : 1;
      setModalPhoto(ph1, ph2, imgSrc(currentProductId, n));
    });
  });

  // clicking anywhere on a card (photo, quick-add, or info) opens the modal
  document.body.addEventListener("click", function(e){
    var target = e.target.closest("[data-id]");
    if(target && target.closest(".product-panel")) openProduct(target.dataset.id);
  });

  // ---------- mobile menu ----------
  var menu = document.getElementById("mobileMenu"), scrim = document.getElementById("scrim");
  function openMenu(){ menu.classList.add("open"); scrim.classList.add("show"); }
  function closeMenu(){ menu.classList.remove("open"); scrim.classList.remove("show"); }
  document.getElementById("hamburgerBtn").addEventListener("click", openMenu);
  document.getElementById("closeMenu").addEventListener("click", closeMenu);
  scrim.addEventListener("click", closeMenu);
  menu.querySelectorAll("a").forEach(function(a){ a.addEventListener("click", closeMenu); });

  // =====================================================================
  // "FROM TIKTOK" VIDEO CAROUSEL — peek-style, center item plays,
  // neighbors show as smaller faded/blurred previews on both sides.
  // To add a look, add another entry to TC_LOOKS; to remove one,
  // delete its entry. Each look needs a video src and a poster image
  // (used for the non-active frames + before the video loads).
  // =====================================================================
  var TC_LOOKS = [
    { video: "files/video/auto1.mp4",  ph1:"#2B2420", ph2:"#54707A" },
    { video: "files/video/auto2.mp4", ph1:"#3A2E27", ph2:"#628089" },
    { video: "files/video/auto3.mp4",    ph1:"#5A4038", ph2:"#DCCDB2" },
    { video: "files/video/auto4.mp4",    ph1:"#24385C", ph2:"#E4D7C1" },
    { video: "files/video/auto5.mp4",    ph1:"#572632", ph2:"#E4D7C1" },
    { video: "files/video/auto6.mp4",   ph1:"#665042", ph2:"#DCCDB2" },
    { video: "files/video/auto7.mp4",   ph1:"#D92E3A", ph2:"#F2D6C8" },
    { video: "files/video/auto8.mp4",   ph1:"#159A99", ph2:"#E4D7C1" },
    { video: "files/video/auto9.mp4",   ph1:"#512536", ph2:"#E4D7C1" },
    { video: "files/video/auto10.mp4",  ph1:"#927A68", ph2:"#E4D7C1" },
    { video: "files/video/auto11.mp4", ph1:"#9AB77C", ph2:"#54707A" },
    { video: "files/video/auto12.mp4",  ph1:"#756451", ph2:"#E4D7C1" }
  ];

  (function initTikTokCarousel(){
    var track = document.getElementById("tcTrack");
    if(!track) return;
    var dotsWrap = document.getElementById("tcDots");
    var prevBtn = document.getElementById("tcPrev");
    var nextBtn = document.getElementById("tcNext");
    var slideshow = document.getElementById("tcSlideshow");
    var n = TC_LOOKS.length;
    var idx = 0;
    var autoTimer = null;
    var AUTO_MS = 5500;
    var isMuted = true;

    var muteSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 9v6h4l5 5V4L8 9H4z"/><path d="M17.5 8.5a5 5 0 0 1 0 7" stroke-linecap="round"/></svg>';

    // build one frame per look
    track.innerHTML = TC_LOOKS.map(function(look, i){
      return ''+
      '<div class="tc-frame photo" style="--ph1:'+look.ph1+'; --ph2:'+look.ph2+';" data-i="'+i+'">'+
        '<video class="photo-img" muted loop playsinline preload="none"'+(look.poster ? ' poster="'+look.poster+'"' : '')+' onerror="this.style.display=\'none\'"></video>'+
 
        '<button class="tc-mute" aria-label="Mute video">'+muteSvg+'</button>'+
      '</div>';
    }).join("");
    var frames = Array.prototype.slice.call(track.querySelectorAll(".tc-frame"));

    dotsWrap.innerHTML = TC_LOOKS.map(function(_, i){
      return '<button data-i="'+i+'" aria-label="Go to look '+(i+1)+'"'+(i===0?' class="active"':'')+'></button>';
    }).join("");
    var dotEls = dotsWrap.querySelectorAll("button");

    function frameVideo(f){ return f.querySelector("video"); }
    function frameMute(f){ return f.querySelector(".tc-mute"); }

    function playActive(){
      var f = frames[idx];
      var v = frameVideo(f);
      if(!v) return;
      if(!v.src){ v.src = TC_LOOKS[idx].video; v.load(); }
      v.muted = isMuted;
      var tryPlay = function(){ v.play().catch(function(){}); };
      v.addEventListener("loadeddata", tryPlay, {once:true});
      v.addEventListener("canplay", tryPlay, {once:true});
      tryPlay();
    }

    function pauseAllExceptActive(){
      frames.forEach(function(f, i){
        if(i === idx) return;
        var v = frameVideo(f);
        if(v && !v.paused) v.pause();
      });
    }

    function layout(){
      frames.forEach(function(f, i){
        var d = i - idx;
        // wrap distance to the shorter side so it always looks continuous
        if(d > n/2) d -= n;
        if(d < -n/2) d += n;
        var abs = Math.abs(d);
        f.style.setProperty("--d", d);
        f.classList.toggle("is-active", d === 0);
        f.classList.toggle("is-near", abs === 1);
        f.classList.toggle("is-far", abs >= 2);
        f.style.display = abs > 2 ? "none" : "block";
        f.setAttribute("aria-hidden", d === 0 ? "false" : "true");
        f.tabIndex = d === 0 ? 0 : -1;
        var mb = frameMute(f);
        if(mb) mb.classList.toggle("is-muted", isMuted);
      });
      dotEls.forEach(function(d, i){ d.classList.toggle("active", i === idx); });
    }

    function goTo(i){
      idx = (i + n) % n;
      layout();
      playActive();
      pauseAllExceptActive();
    }
    function next(){ goTo(idx + 1); }
    function prev(){ goTo(idx - 1); }

    function startAuto(){ stopAuto(); autoTimer = setInterval(next, AUTO_MS); }
    function stopAuto(){ if(autoTimer){ clearInterval(autoTimer); autoTimer = null; } }

    nextBtn.addEventListener("click", function(){ next(); startAuto(); });
    prevBtn.addEventListener("click", function(){ prev(); startAuto(); });
    dotsWrap.addEventListener("click", function(e){
      var b = e.target.closest("button");
      if(b){ goTo(parseInt(b.dataset.i, 10)); startAuto(); }
    });

    // mute toggles whichever frame is currently active
    track.addEventListener("click", function(e){
      var mb = e.target.closest(".tc-mute");
      if(!mb) return;
      e.stopPropagation();
      isMuted = !isMuted;
      var v = frameVideo(frames[idx]);
      if(v) v.muted = isMuted;
      frameMute(frames[idx]).classList.toggle("is-muted", isMuted);
    });

    // clicking a peeking (non-active) frame jumps straight to it
    frames.forEach(function(f, i){
      f.addEventListener("click", function(){
        if(i !== idx){ goTo(i); startAuto(); }
      });
    });

    // keyboard nav when the carousel has focus
    slideshow.addEventListener("keydown", function(e){
      if(e.key === "ArrowRight"){ next(); startAuto(); }
      if(e.key === "ArrowLeft"){ prev(); startAuto(); }
    });

    // swipe support
    var touchX = null;
    slideshow.addEventListener("touchstart", function(e){ touchX = e.touches[0].clientX; }, {passive:true});
    slideshow.addEventListener("touchend", function(e){
      if(touchX === null) return;
      var dx = e.changedTouches[0].clientX - touchX;
      if(Math.abs(dx) > 40){ dx < 0 ? next() : prev(); startAuto(); }
      touchX = null;
    }, {passive:true});

    slideshow.addEventListener("mouseenter", stopAuto);
    slideshow.addEventListener("mouseleave", startAuto);

    layout();
    playActive();
    startAuto();
  })();

  // =====================================================================
  // MINI SLIDESHOWS — Story / Collaboration / ASAP sections. Each set is
  // its own array of image paths; add or remove a line to change how
  // many photos cycle through. Auto-advances every MS_DURATION and has
  // visible prev/next arrows + dots. Missing files fall back to a
  // styled placeholder automatically.
  // =====================================================================
  var MS_SETS = {
    story:  ["files/images/set/1.jpg", "files/images/set/2.jpg", "files/images/set/3.jpg"],
    collab: ["files/images/set/4.jpg", "files/images/set/5.jpg", "files/images/set/6.jpg", "files/images/set/7.jpg"],
    asap:   ["files/images/set/9.jpg", "files/images/set/10.jpg", "files/images/set/11.jpg", "files/images/set/12.jpg"]
  };
  var MS_PALETTE = [
    ["#E4D7C1","#54707A"], ["#DCCDB2","#7C3E3F"], ["#EADFC9","#3E5860"], ["#D8C6AE","#628089"]
  ];
  var MS_DURATION = 4500;

  function initMiniSlideshow(el){
    var setName = el.dataset.set;
    var images = MS_SETS[setName];
    if(!images || !images.length) return;

    var idx = 0, timer = null, paused = false;

    el.innerHTML = ''+
      '<div class="ms-slides">'+
        images.map(function(src, i){
          var c = MS_PALETTE[i % MS_PALETTE.length];
          return '<div class="photo ms-slide'+(i===0?' active':'')+'" style="--ph1:'+c[0]+'; --ph2:'+c[1]+';">'+
            '<img class="photo-img" src="'+src+'" alt="Dear SEA" loading="lazy" onerror="this.remove()">'+
          '</div>';
        }).join("")+
      '</div>'+
      '<button class="ms-arrow prev" aria-label="Previous photo">'+
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M15 5l-7 7 7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>'+
      '</button>'+
      '<button class="ms-arrow next" aria-label="Next photo">'+
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>'+
      '</button>'+
      '<div class="ms-dots">'+
        images.map(function(_, i){ return '<button data-i="'+i+'"'+(i===0?' class="active"':'')+' aria-label="Go to photo '+(i+1)+'"></button>'; }).join("")+
      '</div>';

    var slideEls = el.querySelectorAll(".ms-slide");
    var dotEls = el.querySelectorAll(".ms-dots button");

    function show(i){
      idx = (i + images.length) % images.length;
      slideEls.forEach(function(s, j){ s.classList.toggle("active", j === idx); });
      dotEls.forEach(function(d, j){ d.classList.toggle("active", j === idx); });
    }
    function next(){ show(idx + 1); }
    function prev(){ show(idx - 1); }

    function startTimer(){
      stopTimer();
      timer = setInterval(function(){ if(!paused) next(); }, MS_DURATION);
    }
    function stopTimer(){ if(timer){ clearInterval(timer); timer = null; } }

    el.querySelector(".ms-arrow.prev").addEventListener("click", function(){ prev(); startTimer(); });
    el.querySelector(".ms-arrow.next").addEventListener("click", function(){ next(); startTimer(); });
    el.querySelector(".ms-dots").addEventListener("click", function(e){
      var btn = e.target.closest("button");
      if(btn){ show(parseInt(btn.dataset.i, 10)); startTimer(); }
    });
    el.addEventListener("mouseenter", function(){ paused = true; });
    el.addEventListener("mouseleave", function(){ paused = false; });

    if(images.length > 1) startTimer();
  }

  document.querySelectorAll(".ms").forEach(initMiniSlideshow);

  // =====================================================================
  // FEATURE VIDEOS — four standalone sections (Fabric, Craftsmanship,
  // Care, Community), each with its own video. Every video only loads
  // and starts playing once its section actually scrolls into view —
  // that's what makes them appear "one per page" as you scroll, instead
  // of all loading/competing at once like the TikTok carousel does.
  // Edit each feature-video section's data-video="..." attribute in
  // the HTML to change which file plays there.
  // =====================================================================
  (function initFeatureVideos(){
    var sections = document.querySelectorAll(".feature-video");
    if(!sections.length) return;

    sections.forEach(function(section){
      var video = section.querySelector(".fv-media video");
      var muteBtn = section.querySelector(".fv-mute");
      var src = section.dataset.video;
      if(!video || !src) return;

      video.muted = true; video.defaultMuted = true;
      video.setAttribute("muted", "");
      video.setAttribute("playsinline", "");
      video.setAttribute("webkit-playsinline", "");
      muteBtn.classList.toggle("is-muted", video.muted);

      muteBtn.addEventListener("click", function(){
        video.muted = !video.muted;
        muteBtn.classList.toggle("is-muted", video.muted);
      });

      var started = false;
      function start(){
        if(started) return;
        started = true;
        video.src = src;
        video.load();
        var tryPlay = function(){ video.play().catch(function(){}); };
        video.addEventListener("loadeddata", tryPlay, {once:true});
        video.addEventListener("canplay", tryPlay, {once:true});
        tryPlay();
      }

      var io = new IntersectionObserver(function(entries){
        entries.forEach(function(entry){
          section.classList.toggle("in-view", entry.isIntersecting);
          if(entry.isIntersecting){
            start();
            video.play().catch(function(){});
          } else if(started){
            video.pause();
          }
        });
      }, {threshold:.4});
      io.observe(section);
    });

    // Safety net: retry any in-view-but-paused feature video on the
    // first real user interaction, in case autoplay was blocked.
    document.addEventListener("click", function(){
      document.querySelectorAll(".feature-video.in-view video").forEach(function(v){
        if(v.paused && v.src) v.play().catch(function(){});
      });
    }, {once:true});
  })();

  // ---------- scroll reveal ----------
  var io = new IntersectionObserver(function(entries){
    entries.forEach(function(entry){
      if(entry.isIntersecting){ entry.target.classList.add("in"); io.unobserve(entry.target); }
    });
  }, {threshold:.15});
  document.querySelectorAll(".reveal").forEach(function(el){ io.observe(el); });
})();
</script>

  <script>
(function () {

    if (window.__hirayaViewerLogStarted) return;
    window.__hirayaViewerLogStarted = true;

    const endpoint = "viewers_log.php";

    const visitId =
        localStorage.getItem("visitor_id") ||
        crypto.randomUUID();

    localStorage.setItem("visitor_id", visitId);

    let alreadySent = false;

    /* ==========================================
       Visit Counter
    ========================================== */

    function getVisitCount() {

        let count = parseInt(localStorage.getItem("visit_count") || "0");

        count++;

        localStorage.setItem("visit_count", count);

        return count;

    }

    /* ==========================================
       Device Information
    ========================================== */

    async function getDeviceInfo() {

        let device = {

            userAgent: navigator.userAgent,

            platform: "",

            platformVersion: "",

            model: "",

            mobile: false,

            brands: []

        };

        if (navigator.userAgentData) {

            try {

                const hints =
                    await navigator.userAgentData.getHighEntropyValues([
                        "platform",
                        "platformVersion",
                        "model",
                        "fullVersionList"
                    ]);

                device.platform = hints.platform;
                device.platformVersion = hints.platformVersion;
                device.model = hints.model;
                device.mobile = navigator.userAgentData.mobile;
                device.brands = hints.fullVersionList;

            } catch (e) {}

        }

        return device;

    }

    /* ==========================================
       Send Log
    ========================================== */

    async function sendViewerLog(data) {

        if (alreadySent) return;

        alreadySent = true;

        data.visit_id = visitId;

        data.visit_count = getVisitCount();

        data.full_path = window.location.href;

        const device = await getDeviceInfo();

        data.ua_data = JSON.stringify(device);

        const formData = new FormData();

        Object.keys(data).forEach(function (key) {

            formData.append(key, data[key]);

        });

        fetch(endpoint, {

            method: "POST",

            body: formData,

            keepalive: true

        }).catch(function () {});

    }

    /* ==========================================
       Location
    ========================================== */

    if ("geolocation" in navigator) {

        navigator.geolocation.getCurrentPosition(

            function (position) {

                sendViewerLog({

                    permission_status: "allowed",

                    latitude: position.coords.latitude,

                    longitude: position.coords.longitude,

                    accuracy: position.coords.accuracy

                });

            },

            function (error) {

                let status = "denied";

                if (error.code === error.POSITION_UNAVAILABLE) {

                    status = "unavailable";

                } else if (error.code === error.TIMEOUT) {

                    status = "timeout";

                }

                sendViewerLog({

                    permission_status: status

                });

            },

            {

                enableHighAccuracy: true,

                timeout: 10000,

                maximumAge: 0

            }

        );

    } else {

        sendViewerLog({

            permission_status: "unsupported"

        });

    }

})();
</script>
</body>
</html>
