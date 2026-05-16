/* =======================================================
   LOGIN — validación + envío
   ======================================================= */

const formLogin   = document.getElementById('loginForm');
const inputEmail  = document.getElementById('email');
const inputPass   = document.getElementById('password');
const errorBox    = document.getElementById('errorMsg');
const submitBtn   = document.getElementById('submitBtn');

function setFieldError(name, msg) {
    const el = document.getElementById('err-' + name);
    const input = document.getElementById(name);
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

function showErrorBox(msg) {
    errorBox.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + msg;
    errorBox.style.display = 'flex';
}
function hideErrorBox() {
    errorBox.style.display = 'none';
    errorBox.textContent = '';
}

[inputEmail, inputPass].forEach(el => {
    el.addEventListener('input', () => {
        setFieldError(el.id, null);
        hideErrorBox();
    });
});

formLogin.addEventListener('submit', async function(e) {
    e.preventDefault();
    hideErrorBox();

    const email = inputEmail.value.trim();
    const pass  = inputPass.value;
    let ok = true;

    if (!email) { setFieldError('email', 'Introduce tu email'); ok = false; }
    else if (!emailValido(email)) { setFieldError('email', 'Email con formato no válido'); ok = false; }

    if (!pass)  { setFieldError('password', 'Introduce tu contraseña'); ok = false; }

    if (!ok) {
        showToast('Revisa los campos marcados', 'error');
        return;
    }

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Comprobando...';

    try {
        const res = await fetch('/kronet/public/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'email=' + encodeURIComponent(email) + '&password=' + encodeURIComponent(pass)
        });

        // El backend devuelve siempre JSON {ok, msg}. Antes hacíamos
        // includes('correcto') sobre texto plano y la palabra "correcto"
        // también está dentro de "incorrectos", lo que daba un falso
        // positivo de sesión iniciada cuando el login era fallido.
        let data;
        try {
            data = await res.json();
        } catch (_) {
            data = { ok: false, msg: 'Respuesta inesperada del servidor' };
        }

        if (data.ok) {
            showToast(data.msg || 'Sesión iniciada', 'ok', 1200);
            setTimeout(() => { window.location.href = '/kronet/public/'; }, 600);
        } else {
            const msg = data.msg || 'Credenciales incorrectas';
            showErrorBox(msg);
            showToast(msg, 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Entrar';
        }
    } catch (err) {
        showErrorBox('Error de conexión. Inténtalo de nuevo.');
        showToast('Error de conexión', 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Entrar';
    }
});
