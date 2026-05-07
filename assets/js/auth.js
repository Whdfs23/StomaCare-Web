// ============================================================
//  assets/js/auth.js
//  Toggle Login ↔ Register panel
// ============================================================

const toRegisterBtn = document.getElementById('toRegisterBtn');
const toLoginBtn    = document.getElementById('toLoginBtn');
const formLogin     = document.querySelector('.form-login');
const formRegister  = document.querySelector('.form-register');
const toggleLeft    = document.getElementById('toggleLeft');
const toggleRight   = document.getElementById('toggleRight');

if (toRegisterBtn) {
    toRegisterBtn.addEventListener('click', () => {
        formLogin.style.cssText    = 'transform:translateX(-100%);opacity:0;pointer-events:none;transition:all .5s ease;';
        formRegister.style.cssText = 'transform:translateX(0);opacity:1;pointer-events:all;transition:all .5s ease;';
        toggleLeft.classList.add('hidden');
        toggleRight.classList.remove('hidden');
    });
}

if (toLoginBtn) {
    toLoginBtn.addEventListener('click', () => {
        formLogin.style.cssText    = 'transform:translateX(0);opacity:1;pointer-events:all;transition:all .5s ease;';
        formRegister.style.cssText = 'transform:translateX(100%);opacity:0;pointer-events:none;transition:all .5s ease;';
        toggleLeft.classList.remove('hidden');
        toggleRight.classList.add('hidden');
    });
}

// Toggle show/hide password
function togglePass(inputId, icon) {
    const inp = document.getElementById(inputId);
    if (!inp) return;
    if (inp.type === 'password') {
        inp.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        inp.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

// Mobile hamburger menu
const menuToggle = document.getElementById('menuToggle');
const mobileMenu = document.getElementById('mobileMenu');
if (menuToggle && mobileMenu) {
    menuToggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
}
