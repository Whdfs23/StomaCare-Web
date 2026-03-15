(function () {
    "use strict";

  /* ── DOM refs ── */
    const card      = document.getElementById("card");
    const overlay   = document.getElementById("overlay");
    const ovContent = document.getElementById("ovContent");
    const ovTitle   = document.getElementById("ovTitle");
    const ovDesc    = document.getElementById("ovDesc");
    const switchBtn = document.getElementById("switchBtn");

    const rPassInput = document.getElementById("rPass");
    const lPassInput = document.getElementById("lPass");
    const rEyeIcon   = document.getElementById("rEye");
    const lEyeIcon   = document.getElementById("lEye");

    const btnRegister = document.getElementById("btnRegister");
    const btnLogin    = document.getElementById("btnLogin");

  /* ── State ── */
  // false = overlay on RIGHT, register visible on LEFT
  // true  = overlay on LEFT,  login visible on RIGHT area (but login panel is left)
    let showingLogin = false;
    let busy         = false;

  /* ── Easing ── */
    const EASE = "cubic-bezier(0.77, 0, 0.18, 1)";

    /* ══════════════════════════════════════════
    CORE SWITCH — direction-aware
    direction: "toLogin"    → overlay LEFT→RIGHT→LEFT  (lands LEFT)
                "toRegister" → overlay RIGHT→LEFT→RIGHT (lands RIGHT)
  ══════════════════════════════════════════ */
    function switchPanel(direction) {
        if (busy) return;
        busy = true;

    const goingToLogin = direction === "toLogin";

    /* ── PHASE 1 (0 ms): fade out overlay text ── */
    ovContent.classList.add("hidden");

    /* ── PHASE 2 (200 ms): expand overlay to full card width ──
        The expand origin matches where the overlay currently lives:
            toLogin:    overlay is on RIGHT → expand leftward (right stays fixed)
            toRegister: overlay is on LEFT  → expand rightward (left stays fixed)
    ── */
setTimeout(function () {
    if (goingToLogin) {
        /* Overlay currently on RIGHT — anchor right edge, grow leftward */
        overlay.style.transition =
            "width 0.48s " + EASE + ", border-radius 0.48s " + EASE;
        overlay.style.right        = "0";
        overlay.style.left         = "auto";
        overlay.style.width        = "100%";
        overlay.style.borderRadius = "0";
    } else {
        /* Overlay currently on LEFT — anchor left edge, grow rightward */
        overlay.style.transition =
            "width 0.48s " + EASE + ", border-radius 0.48s " + EASE;
        overlay.style.left         = "0";
        overlay.style.right        = "auto";
        overlay.style.width        = "100%";
        overlay.style.borderRadius = "0";
        }
}, 200);

    /* ── PHASE 3 (530 ms): swap form classes + update overlay text
        (safe to do while fully covered)
    ── */
setTimeout(function () {
    if (goingToLogin) {
        card.classList.add("show-login");
        ovTitle.innerHTML     = "Welcome<br>Back!";
        ovDesc.textContent    = "To keep connected with us please login with your personal info";
        switchBtn.textContent = "Register";
} else {
        card.classList.remove("show-login");
        ovTitle.innerHTML     = "Hello,<br>Friend!";
        ovDesc.textContent    = "Enter your personal details and start your journey with us";
        switchBtn.textContent = "Login";
    }
}, 530);

    /* ── PHASE 4 (610 ms): shrink overlay to its new position ──
        toLogin:    shrink from full → LEFT 45 %
        toRegister: shrink from full → RIGHT 45 %
    ── */
setTimeout(function () {
    if (goingToLogin) {
        /* Land on LEFT */
        overlay.style.transition =
        "width 0.52s " + EASE + ", border-radius 0.52s " + EASE;
        overlay.style.right        = "auto";
        overlay.style.left         = "0";
        overlay.style.width        = "45%";
        overlay.style.borderRadius = "0 28px 28px 0";
    } else {
        /* Land on RIGHT */
        overlay.style.transition =
            "width 0.52s " + EASE + ", border-radius 0.52s " + EASE;
        overlay.style.left         = "auto";
        overlay.style.right        = "0";
        overlay.style.width        = "45%";
        overlay.style.borderRadius = "28px 0 0 28px";
    }
}, 610);

    /* ── PHASE 5 (990 ms): fade text back in ── */
setTimeout(function () {
        ovContent.classList.remove("hidden");
}, 990);

    /* ── DONE (1 220 ms) ── */
setTimeout(function () {
        showingLogin = goingToLogin;
        busy         = false;
    }, 1220);
    }

    /* ══════════════════════════════════════════
        EVENT LISTENERS
  ══════════════════════════════════════════ */

  /* Overlay switch button */
    switchBtn.addEventListener("click", function () {
    switchPanel(showingLogin ? "toRegister" : "toLogin");
    });

    /* Register submit button — triggers toLogin transition
     (overlay moves right→left, reveals login) */
    btnRegister.addEventListener("click", function (e) {
    addRipple(e);
    /* Optional: add form validation here before switching */
    switchPanel("toLogin");
    });

    /* Login submit button — triggers toRegister transition
     (overlay moves left→right, reveals register) */
    btnLogin.addEventListener("click", function (e) {
    addRipple(e);
    /* Optional: add form validation here before switching */
    switchPanel("toRegister");
    });

  /* Eye toggles */
    rEyeIcon.addEventListener("click", function () {
    toggleEye(rPassInput, rEyeIcon);
    });

    lEyeIcon.addEventListener("click", function () {
    toggleEye(lPassInput, lEyeIcon);
    });

    /* ══════════════════════════════════════════
        HELPERS
    ══════════════════════════════════════════ */

    function toggleEye(input, icon) {
    if (input.type === "password") {
        input.type = "text";
        icon.classList.replace("fa-eye", "fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.replace("fa-eye-slash", "fa-eye");
    }
    }

    function addRipple(e) {
    var btn      = e.currentTarget;
    var existing = btn.querySelector(".rpl");
    if (existing) existing.remove();

    var circle   = document.createElement("span");
    var diameter = Math.max(btn.clientWidth, btn.clientHeight);
    var radius   = diameter / 2;
    var rect     = btn.getBoundingClientRect();

    circle.classList.add("rpl");
    circle.style.cssText = [
        "width:"  + diameter + "px",
        "height:" + diameter + "px",
        "left:"   + (e.clientX - rect.left  - radius) + "px",
        "top:"    + (e.clientY - rect.top   - radius) + "px"
    ].join(";");

    btn.appendChild(circle);
    }

})();
