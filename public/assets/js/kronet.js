/* =======================================================
   KRONET — JS GLOBAL
   ======================================================= */

/**
 * Muestra una notificación tipo toast en la esquina superior derecha.
 * @param {string} msg
 * @param {'ok'|'error'|'warn'|'info'} tipo
 * @param {number} ms  Tiempo en milisegundos (0 = no se cierra solo)
 */
function showToast(msg, tipo = 'ok', ms = 3500) {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const t = document.createElement('div');
    t.className = 'toast toast-' + tipo;

    const iconos = {
        ok: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warn: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    const icon = iconos[tipo] || iconos.info;

    t.innerHTML = '<i class="fas ' + icon + '"></i><span>' + msg + '</span>';
    container.appendChild(t);

    if (ms > 0) {
        setTimeout(() => {
            t.classList.add('fade-out');
            setTimeout(() => t.remove(), 280);
        }, ms);
    }
    return t;
}

/**
 * Hace fetch a una URL devolviendo JSON, gestionando errores básicos.
 */
async function apiPost(url, formDataOrObj) {
    let body;
    if (formDataOrObj instanceof FormData) {
        body = formDataOrObj;
    } else if (formDataOrObj && typeof formDataOrObj === 'object') {
        body = new FormData();
        for (const k in formDataOrObj) body.append(k, formDataOrObj[k]);
    }
    const res = await fetch(url, { method: 'POST', body });
    const txt = await res.text();
    try {
        return JSON.parse(txt);
    } catch (e) {
        return { ok: false, msg: txt || 'Respuesta inesperada del servidor' };
    }
}

/**
 * Confirm con look uniforme. Por ahora envoltura sobre confirm() nativo.
 */
function confirmar(msg) {
    return window.confirm(msg);
}

/**
 * Validación de email simple (sólo formato).
 */
function emailValido(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

/**
 * Reglas de contraseña: 8+ caracteres, una mayúscula, una minúscula, un número.
 * Devuelve {ok, fails: [string]}
 */
function validarPassword(pass) {
    const fails = [];
    if (pass.length < 8)         fails.push('Al menos 8 caracteres');
    if (!/[A-Z]/.test(pass))     fails.push('Una letra mayúscula');
    if (!/[a-z]/.test(pass))     fails.push('Una letra minúscula');
    if (!/[0-9]/.test(pass))     fails.push('Un número');
    return { ok: fails.length === 0, fails };
}
