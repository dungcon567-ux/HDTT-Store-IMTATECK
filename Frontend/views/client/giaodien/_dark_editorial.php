<?php /* Dark Premium override for pages built on the editorial (--ed-*) cream theme.
        Include right before _footer.php. Remaps the cream palette to dark and
        repaints the ink-as-background components (buttons, eyebrows, icons). */ ?>
<style>
    /* Remap the editorial palette → dark premium (this :root comes AFTER the page's
       inline :root, so it wins). --ed-ink flips to LIGHT because it's used mostly for text. */
    :root{
        --ed-cream:#0B0B12 !important;
        --ed-cream-2:#15151F !important;
        --ed-cream-3:#1B1B28 !important;
        --ed-paper:#101018 !important;
        --ed-ink:#ECECF5 !important;
        --ed-ink-soft:#B4B4CC !important;
        --ed-cocoa:#7E7E9A !important;
        --ed-rust:#A78BFA !important;
        --ed-mustard:#FCD34D !important;
    }
    body{background:var(--bg) !important;color:var(--text) !important}

    /* Structural surfaces & borders (dark glass) */
    .cart-section,.ord-card,.ord-stat,.pay-section,.res-card,.empty-cart,.ord-empty,.summary-promo,.pay-banks{
        background:var(--surface) !important;border:1px solid var(--border) !important;backdrop-filter:blur(12px);
    }
    .cart-section-head,.pay-section-head,.ord-card-head{background:var(--surface-2) !important;border-bottom:1px solid var(--border) !important}
    .cart-item,.summary-row,.pay-row,.pay-item,.res-row,.ord-card-foot,.ord-card-body,.vnp-secure,.vnp-footer{border-color:var(--border) !important}
    .ord-card:hover{border-color:var(--border-glow) !important;box-shadow:0 16px 36px rgba(0,0,0,.5) !important}

    /* Sharpen everything (brutalist) */
    .cart-section,.ord-card,.ord-stat,.pay-section,.res-card,.empty-cart,.ord-empty,.summary-promo,
    .pay-banks,.cart-eyebrow,.ord-eyebrow,.pay-eyebrow,.ord-action,.summary-checkout,.cart-btn-link,
    .res-btn,.ord-back-btn,.cart-item-attr,.cart-qty-update,.cart-qty-input,.ord-stat-icon,.pay-item img,
    .cart-item-img,.ord-payment-method,.pay-banks .bank,.vnp-info,.countdown-bar,.res-badge,.ord-badge,
    .pay-cancel,.badge,.res-circle,.pay-logo,.vnp-brand,.vnp-btn{border-radius:0 !important}

    /* Ink-as-background components → acid (black text) */
    .cart-eyebrow,.ord-eyebrow,.pay-eyebrow{background:var(--accent) !important;color:#000 !important;font-family:'Space Mono',monospace !important}
    .summary-checkout,.ord-action.primary,.res-btn.primary,.cart-btn-link.solid{background:var(--accent) !important;color:#000 !important;border:2px solid var(--accent) !important;text-transform:uppercase;font-weight:800}
    .summary-checkout:hover,.ord-action.primary:hover,.res-btn.primary:hover,.cart-btn-link.solid:hover{transform:translate(-2px,-2px);box-shadow:4px 4px 0 #000;color:#000 !important}
    .cart-qty-update{background:transparent !important;color:var(--accent) !important;border:2px solid var(--border-2) !important;text-transform:uppercase}
    .cart-qty-update:hover{background:var(--accent) !important;color:#000 !important;border-color:var(--accent) !important}
    .ord-stat-icon.c1{background:var(--grad) !important}
    .ord-stat-icon.c2{background:linear-gradient(135deg,#f59e0b,#fbbf24) !important}
    .ord-stat-icon.c3{background:var(--accent) !important}
    .ord-stat-icon.c4{background:var(--surface-3) !important;color:var(--text) !important}
    .ord-action.success{background:linear-gradient(135deg,#10b981,#34d399) !important;color:#04170F !important}
    .ord-action.warning{background:linear-gradient(135deg,#f59e0b,#fbbf24) !important;color:#241701 !important}
    .ord-action.muted{background:var(--surface-2) !important;color:var(--text-3) !important}
    .ord-payment-method{background:var(--surface-2) !important;color:var(--text) !important}

    /* Outline buttons */
    .cart-btn-link.outline,.ord-back-btn,.res-btn.outline,.pay-cancel{color:var(--text) !important;border:1.5px solid var(--border-2) !important;background:transparent !important}
    .cart-btn-link.outline:hover,.ord-back-btn:hover,.res-btn.outline:hover,.pay-cancel:hover{background:var(--surface-3) !important;color:#fff !important}

    /* Totals / heavy borders */
    .summary-total,.pay-total-row{border-top:2px solid var(--border-2) !important}

    /* Attribute chips, small surfaces */
    .cart-item-attr,.ord-payment-method,.pay-item img,.res-row .mono{background:var(--surface-2) !important}
    .cart-item-attr{color:var(--text-2) !important}
    .pay-banks .bank{background:var(--surface-3) !important;color:var(--text) !important;border:1px solid var(--border-2) !important}
    .pay-banks-label{color:var(--text-3) !important}

    /* Inputs */
    .cart-qty-input{background:var(--surface-2) !important;border:1px solid var(--border-2) !important;color:var(--text) !important}
    .cart-qty-input:focus{border-color:var(--accent) !important;background:var(--surface-3) !important}

    /* Item images */
    .cart-item-img,.pay-item img{background:var(--bg-3) !important}

    /* Prices → gradient text */
    .summary-total-num,.pay-total-num,.cart-item-price,.ord-info-value.price,.pay-item-price,.res-row .price{
        color:transparent !important;background:var(--grad);-webkit-background-clip:text;background-clip:text;
    }

    /* Titles → Sora, upright */
    .cart-title,.ord-title,.pay-title,.res-body h2,.cart-section-head h3,.pay-section-head h3,
    .ord-toolbar h3,.ord-card-id,.cart-item-name,.empty-cart h4,.ord-empty h4,.res-info-title,
    .pay-total-label,.summary-total-label,.ord-stat-num{
        font-family:'Archivo',sans-serif !important;font-style:normal !important;color:var(--text) !important;
    }
    .cart-title span,.ord-title span,.pay-title span{
        color:transparent !important;background:var(--grad);-webkit-background-clip:text;background-clip:text;
    }
    .cart-sub,.ord-sub,.pay-sub,.res-lead{color:var(--text-2) !important;font-family:'Inter',sans-serif !important;font-style:normal !important}

    /* VNPAY info box → dark amber */
    .vnp-info{background:rgba(251,191,36,.12) !important;border:1px dashed rgba(251,191,36,.35) !important;color:#FDE68A !important}
    .vnp-info i{color:#FBBF24 !important}
    .vnp-info code{background:var(--surface-3) !important;color:#FDE68A !important}
    .countdown-bar{background:rgba(251,113,133,.14) !important;color:#FDA4AF !important}
    .res-message,.res-badge.ok,.res-badge.no{backdrop-filter:blur(6px)}

    /* Payresult colored header keeps its semantic gradient; darken text container */
    .res-lead{color:var(--text-2) !important}
    .empty-cart p,.ord-empty p{color:var(--text-2) !important}
    .empty-cart i,.ord-empty i{color:var(--text-3) !important}
</style>
