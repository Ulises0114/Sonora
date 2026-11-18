// Reproductor SIMULADO: no hay audio; un temporizador avanza el progreso.
const pistas = window.pistas ?? [];
let i = Number(sessionStorage.getItem("sonora_i") ?? 0); // pista actual
let t = Number(sessionStorage.getItem("sonora_t") ?? 0); // segundos transcurridos
let aleatorio = false;
let temporizador = null;

if (i < 0 || i >= pistas.length) i = 0;

const $ = (sel) => document.querySelector(sel);
const $$ = (sel) => document.querySelectorAll(sel);
const fmt = (s) =>
    `${String(Math.floor(s / 60)).padStart(2, "0")}:${String(s % 60).padStart(2, "0")}`;

function guardarEstado() {
    try {
        sessionStorage.setItem("sonora_i", String(i));
        sessionStorage.setItem("sonora_t", String(t));
    } catch (_) {}
}

function pintar() {
    const p = pistas[i];
    if (!p) return;

    const pct = Math.min(100, Math.round((t / p.dur) * 100));

    if ($("#titulo")) $("#titulo").textContent = p.titulo;
    if ($("#artista")) $("#artista").textContent = p.artista;
    if ($("#tiempo")) $("#tiempo").textContent = fmt(t);
    if ($("#duracion")) $("#duracion").textContent = fmt(p.dur);
    if ($("#relleno")) $("#relleno").style.width = `${pct}%`;
    if ($("#barra")) $("#barra").setAttribute("aria-valuenow", String(pct));

    // 2. Vista dedicada Reproductor (NowPlaying) si está abierta
    $$("[data-titulo-actual]").forEach((el) => (el.textContent = p.titulo));
    $$("[data-artista-actual]").forEach((el) => (el.textContent = p.artista));
    $$("[data-album-actual]").forEach((el) => (el.textContent = p.album ?? ""));
    $$("[data-calidad-actual]").forEach(
        (el) => (el.textContent = `${p.calidad ?? "320 kbps"} · 48 KHZ`),
    );
    $$("[data-tiempo-actual]").forEach((el) => (el.textContent = fmt(t)));
    $$("[data-duracion-actual]").forEach((el) => (el.textContent = fmt(p.dur)));
    $$("[data-relleno-actual]").forEach((el) => (el.style.width = `${pct}%`));
    $$("[data-barra-progreso]").forEach((el) =>
        el.setAttribute("aria-valuenow", String(pct)),
    );

    // 3. Resaltar la fila de canción activa en cualquier lista visible
    $$("[data-pista]").forEach((fila) => {
        if (Number(fila.dataset.pista) === i) {
            fila.setAttribute("aria-current", "true");
        } else {
            fila.removeAttribute("aria-current");
        }
    });

    guardarEstado();
}

function reproduciendo() {
    return temporizador !== null;
}

function pintarBoton() {
    const activo = reproduciendo();

    $("#icono-play")?.classList.toggle("hidden", activo);
    $("#icono-pausa")?.classList.toggle("hidden", !activo);

    $$("[data-icono-play]").forEach((el) =>
        el.classList.toggle("hidden", activo),
    );
    $$("[data-icono-pausa]").forEach((el) =>
        el.classList.toggle("hidden", !activo),
    );

    $$('[data-accion="alternar"]').forEach((btn) => {
        btn.setAttribute("aria-label", activo ? "Pausar" : "Reproducir");
    });
}

function reproducir() {
    if (!pistas.length) return;
    clearInterval(temporizador);
    temporizador = setInterval(() => {
        t++;
        if (t >= pistas[i].dur) {
            cambiar(
                aleatorio ? Math.floor(Math.random() * pistas.length) : i + 1,
            );
            return;
        }
        pintar();
    }, 1000);
    pintarBoton();
}

function pausar() {
    clearInterval(temporizador);
    temporizador = null;
    pintarBoton();
}

function cambiar(nueva) {
    if (!pistas.length) return;
    i = (nueva + pistas.length) % pistas.length;
    t = 0;
    pintar();
}

// Delegación de eventos para controles, filas, barras de progreso y chips
document.addEventListener("click", (e) => {
    const btnAccion = e.target.closest("[data-accion]");
    const accion = btnAccion?.dataset.accion;

    if (accion === "alternar") {
        reproduciendo() ? pausar() : reproducir();
        return;
    }
    if (accion === "siguiente") {
        cambiar(aleatorio ? Math.floor(Math.random() * pistas.length) : i + 1);
        return;
    }
    if (accion === "anterior") {
        cambiar(i - 1);
        return;
    }
    if (accion === "aleatorio") {
        aleatorio = !aleatorio;
        $$('[data-accion="aleatorio"]').forEach((b) =>
            b.setAttribute("aria-pressed", aleatorio ? "true" : "false"),
        );
        return;
    }
    if (accion === "favorito") {
        const presionado = btnAccion.getAttribute("aria-pressed") === "true";
        $$('[data-accion="favorito"]').forEach((b) =>
            b.setAttribute("aria-pressed", presionado ? "false" : "true"),
        );
        return;
    }
    if (accion === "silenciar") {
        const silenciado = btnAccion.getAttribute("aria-pressed") === "true";
        const nuevoEstado = !silenciado;
        btnAccion.setAttribute("aria-pressed", nuevoEstado ? "true" : "false");
        const aviso = $("#aviso-silencio");
        if (aviso) {
            aviso.classList.toggle("hidden", !nuevoEstado);
            aviso.classList.toggle("inline-flex", nuevoEstado);
        }
        return;
    }

    // Clic en una fila de canción (<x-song-row>)
    const fila = e.target.closest("[data-pista]");
    if (fila) {
        cambiar(Number(fila.dataset.pista));
        reproducir();
        return;
    }

    const barra = e.target.closest("#barra, [data-barra-progreso]");
    if (barra && pistas[i]) {
        const rect = barra.getBoundingClientRect();
        const ratio = Math.max(
            0,
            Math.min(1, (e.clientX - rect.left) / rect.width),
        );
        t = Math.floor(ratio * pistas[i].dur);
        pintar();
        return;
    }

    const chip = e.target.closest("button[aria-pressed]");
    if (chip && !chip.dataset.accion) {
        const grupo = chip.closest('[role="group"]');
        if (grupo) {
            grupo
                .querySelectorAll("button[aria-pressed]")
                .forEach((c) => c.setAttribute("aria-pressed", "false"));
            chip.setAttribute("aria-pressed", "true");
        } else {
            const actual = chip.getAttribute("aria-pressed") === "true";
            chip.setAttribute("aria-pressed", actual ? "false" : "true");
        }
    }
});

// Soporte de teclado (flechas izquierda/derecha) en las barras de progreso
document.addEventListener("keydown", (e) => {
    const barra = e.target.closest("#barra, [data-barra-progreso]");
    if (!barra || !pistas[i]) return;
    if (e.key === "ArrowRight") {
        e.preventDefault();
        t = Math.min(pistas[i].dur - 1, t + 5);
        pintar();
    } else if (e.key === "ArrowLeft") {
        e.preventDefault();
        t = Math.max(0, t - 5);
        pintar();
    }
});

// Tema claro/oscuro, recordado en el navegador (localStorage)
const raiz = document.documentElement;
try {
    raiz.dataset.theme = localStorage.getItem("tema") ?? "dark";
} catch (_) {}

document.addEventListener("click", (e) => {
    if (e.target.closest('#tema, [data-accion="tema"]')) {
        raiz.dataset.theme = raiz.dataset.theme === "dark" ? "light" : "dark";
        try {
            localStorage.setItem("tema", raiz.dataset.theme);
        } catch (_) {}
    }
});

pintar();
// Filtrado en vivo en la pantalla Buscar
const campoBuscar = $("#campo-buscar");
const btnLimpiar = $("#limpiar-busqueda");
let tipoFiltro = "todo";

function filtrarCatalogo() {
    if (!campoBuscar) return;
    const termino = campoBuscar.value.trim().toLowerCase();
    let visibles = 0;

    $$("[data-item-busqueda]").forEach((item) => {
        const tipo = item.dataset.itemBusqueda; // 'lista' o 'pista'
        const texto = item.dataset.texto ?? "";
        const coincideTexto = termino === "" || texto.includes(termino);
        const coincideTipo =
            tipoFiltro === "todo" ||
            (tipoFiltro === "listas" && tipo === "lista") ||
            (tipoFiltro === "pistas" && tipo === "pista");

        const mostrar = coincideTexto && coincideTipo;
        item.classList.toggle("hidden", !mostrar);
        if (mostrar) visibles++;
    });

    $("#seccion-listas")?.classList.toggle("hidden", tipoFiltro === "pistas");
    $("#seccion-pistas")?.classList.toggle("hidden", tipoFiltro === "listas");

    const contador = $("#contador-resultados");
    if (contador) {
        contador.textContent = `${String(visibles).padStart(2, "0")} resultados disponibles`;
    }

    const alertaVacio = $("#sin-resultados");
    if (alertaVacio) {
        alertaVacio.classList.toggle("hidden", visibles > 0);
        alertaVacio.classList.toggle("flex", visibles === 0);
    }
}

campoBuscar?.addEventListener("input", filtrarCatalogo);

btnLimpiar?.addEventListener("click", () => {
    if (!campoBuscar) return;
    campoBuscar.value = "";
    filtrarCatalogo();
    campoBuscar.focus();
});

document.addEventListener("click", (e) => {
    const chipTipo = e.target.closest("[data-filtro-tipo]");
    if (chipTipo) {
        tipoFiltro = chipTipo.dataset.filtroTipo;
        filtrarCatalogo();
    }
});
