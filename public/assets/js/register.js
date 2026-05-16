/* =======================================================
   REGISTRO — validación viva + envío
   ======================================================= */

const formReg     = document.getElementById('registerForm');
const inNombre    = document.getElementById('nombre');
const inEmail     = document.getElementById('email');
const inPass      = document.getElementById('password');
const inPass2     = document.getElementById('password2');
const errorBoxR   = document.getElementById('errorMsg');
const submitBtnR  = document.getElementById('submitBtn');

function setError(id, msg) {
    const el = document.getElementById('err-' + id);
    const input = document.getElementById(id);
    if (!el) return;
    if (msg) {
        el.style.display = 'flex';
        el.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + msg;
        if (input) input.style.borderColor = '#fca5a5';
    } else {
        el.style.display = 'none';
        el.textContent = '';
        if (input) input.style.borderColor = '';
    }
}

function showBoxErr(msg) {
    errorBoxR.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + msg;
    errorBoxR.style.display = 'flex';
}
function hideBoxErr() { errorBoxR.style.display = 'none'; }

inPass.addEventListener('input', () => {
    const v = inPass.value;
    const rules = {
        len:   v.length >= 8,
        upper: /[A-Z]/.test(v),
        lower: /[a-z]/.test(v),
        num:   /[0-9]/.test(v),
    };
    document.querySelectorAll('#passwordRules .rule').forEach(div => {
        const k = div.dataset.rule;
        const ok = rules[k];
        div.classList.toggle('ok', ok);
        div.classList.toggle('fail', !ok && v.length > 0);
        const i = div.querySelector('i');
        i.className = 'fas ' + (ok ? 'fa-check-circle' : (v.length > 0 ? 'fa-times-circle' : 'fa-circle-notch'));
    });
});

[inNombre, inEmail, inPass, inPass2].forEach(el => {
    el.addEventListener('input', () => {
        setError(el.id, null);
        hideBoxErr();
    });
});

formReg.addEventListener('submit', async function(e) {
    e.preventDefault();
    hideBoxErr();

    const nombre = inNombre.value.trim();
    const email  = inEmail.value.trim();
    const pass   = inPass.value;
    const pass2  = inPass2.value;
    let ok = true;

    if (!nombre || nombre.length < 2) { setError('nombre', 'Mínimo 2 caracteres'); ok = false; }
    if (!email)                       { setError('email',  'Introduce tu email'); ok = false; }
    else if (!emailValido(email))     { setError('email',  'Email con formato no válido'); ok = false; }

    const pv = validarPassword(pass);
    if (!pv.ok) { ok = false; }

    if (pass !== pass2) {
        setError('password2', 'Las contraseñas no coinciden');
        ok = false;
    }

    if (!ok) {
        showToast('Revisa los campos marcados', 'error');
        if (!pv.ok) showBoxErr('La contraseña no cumple los requisitos.');
        return;
    }

    submitBtnR.disabled = true;
    submitBtnR.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creando cuenta...';

    try {
        const res = await fetch('/kronet/public/register', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'nombre=' + encodeURIComponent(nombre) +
                  '&email=' + encodeURIComponent(email) +
                  '&password=' + encodeURIComponent(pass)
        });
        const data = await res.json();
        if (data.ok) {
            showToast('Cuenta creada, ya puedes iniciar sesión', 'ok', 1500);
            setTimeout(() => { window.location.href = '/kronet/public/login'; }, 1000);
        } else {
            showBoxErr(data.msg || 'No se pudo crear la cuenta');
            showToast(data.msg || 'Error al registrarse', 'error');
            submitBtnR.disabled = false;
            submitBtnR.innerHTML = '<i class="fas fa-user-plus"></i> Registrarse';
        }
    } catch (err) {
        showBoxErr('Error de conexión.');
        showToast('Error de conexión', 'error');
        submitBtnR.disabled = false;
        submitBtnR.innerHTML = '<i class="fas fa-user-plus"></i> Registrarse';
    }
});
