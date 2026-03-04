// --- VARIABLES GLOBALES ---
let jitsiApi = null;
let inicioLlamada = null;
let llamadaGuardada = false;
Dropzone.autoDiscover = false;

// nombreSala ya viene definido globalmente desde el index.php

// --- FUNCIONES DE JITSI ---

function joinRoom() {
    // Leemos el valor actual del input por si se ha modificado
    nombreSala = $('#room-name').val().trim().toLowerCase().replace(/[^a-z0-9-]/g, '-');
    $('#current-room').text(nombreSala);

    if (jitsiApi) {
        jitsiApi.dispose();
        jitsiApi = null;
    }

    const domain = 'meet.jit.si';
    const options = {
        roomName: nombreSala,
        width: '100%',
        height: 500,
        parentNode: document.querySelector('#jitsi-container'),
        configOverwrite: {
            startWithAudioMuted: false,
            startWithVideoMuted: false,
            enableWelcomePage: false,
            prejoinPageEnabled: false
        },
        interfaceConfigOverwrite: {
            TOOLBAR_BUTTONS: [
                'microphone', 'camera', 'desktop', 'chat', 'fullscreen',
                'settings', 'raisehand', 'videoquality', 'tileview'
            ]
        },
        userInfo: {
            displayName: 'Usuario FitnessGym'
        }
    };

    try {
        jitsiApi = new JitsiMeetExternalAPI(domain, options);
        console.log('Conectado a la sala:', nombreSala);
        inicioLlamada = new Date();
        llamadaGuardada = false;
        $('#boton-colgar').show();
    } catch (error) {
        console.error('Error al conectar con Jitsi:', error);
        toastr.error('No se pudo conectar con la sala.');
    }
}

function limpiarJitsi() {
    if (jitsiApi) {
        jitsiApi.dispose();
        jitsiApi = null;
    }
    $('#jitsi-container').html('');
    $('#boton-colgar').hide();
}

// --- FUNCIONES DE BASE DE DATOS ---

function guardarLlamada() {
    if (!inicioLlamada || llamadaGuardada) return;

    const fin = new Date();
    const duracionMin = Math.round((fin - inicioLlamada) / 60000);

    if (duracionMin >= 1) {
        const payload = {
            sala: nombreSala,
            fecha: inicioLlamada.toISOString(),
            fin: fin.toISOString(),
            duracion: duracionMin
        };

        fetch('./utilidades/guardar_llamada.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
            keepalive: true
        })
        .then(() => {
            console.log('Llamada guardada correctamente');
            mostrarHistorial(); // Refrescamos historial tras guardar
        })
        .catch(err => console.warn('Error al guardar:', err));
    }

    llamadaGuardada = true;
    inicioLlamada = null;
}

function mostrarHistorial() {
    if (!nombreSala) return;

    $.getJSON('./utilidades/leer_historial.php', { sala: nombreSala }, (historial) => {
        const contenedor = $('#historial-sesiones');
        if (!contenedor.length) return;

        if (!Array.isArray(historial) || historial.length === 0) {
            contenedor.html('<li>No hay sesiones previas registradas.</li>');
            return;
        }

        const html = historial.map(s => {
            const fecha = new Date(s.fecha);
            const fechaFormateada = fecha.toLocaleDateString('es-ES', {
                day: '2-digit', month: '2-digit', year: '2-digit',
                hour: '2-digit', minute: '2-digit'
            });
            return `<li>Llamada del ${fechaFormateada} — ${s.duracion} min</li>`;
        }).join('');
        
        contenedor.html(html);
    }).fail((error) => {
        console.error('Error en historial:', error);
    });
}

// --- NOTIFICACIONES Y AYUDA ---

function enviarNotificacion(tipo, contenido) {
    $.post('./utilidades/enviar_notificacion.php', { tipo, contenido, sala: nombreSala }, (data) => {
        // Verificamos si data es un objeto (JSON)
        if (data && data.status === 'ok') {
            toastr.info(`Notificación enviada: ${tipo}`);
        }
    }, 'json').fail(() => toastr.error('Error enviando notificación'));
}

function solicitarAyuda() {
    toastr.info("Tu solicitud ha sido enviada.");
    enviarNotificacion('ayuda', 'El usuario solicita ayuda en la videollamada.');
}

// --- INICIALIZACIÓN (READY) ---

$(document).ready(() => {
    // Forzamos que Dropzone no intente buscar formularios por su cuenta
    // Esto evita que desaparezca si hay errores en el DOM

    // 1. CARGA INICIAL
    if (typeof nombreSala !== 'undefined' && nombreSala) {
        mostrarHistorial();
    }

    // 2. EVENTOS
    $('#boton-unirse').on('click', joinRoom);
    $('#boton-ayuda').on('click', solicitarAyuda);
    
    $('#boton-colgar').on('click', () => {
        guardarLlamada();
        if (jitsiApi) jitsiApi.executeCommand('hangup');
        setTimeout(() => {
            limpiarJitsi();
        }, 500);
    });

    // 3. CONFIGURACIÓN DROPZONE
const $zona = $('#zona-subida');
if ($zona.length > 0) { // Verificamos que el elemento existe
    try {
        // Si ya existía una instancia, la destruimos para evitar errores de duplicado
        if (Dropzone.instances.length > 0) {
            Dropzone.instances.forEach(dz => dz.destroy());
        }

        new Dropzone('#zona-subida', {
            url: "./utilidades/subir_archivo.php",
            acceptedFiles: ".pdf",
            maxFilesize: 5,
            dictDefaultMessage: "Arrastra tu archivo PDF aquí o haz clic",
            params: function () {
                return { sala: nombreSala };
            },
            init: function () {
                this.on("success", function(file, response) {
                    toastr.success('Archivo subido correctamente.');
                    this.removeFile(file);
                });
                this.on("error", function (file, message) {
                    toastr.error('Error al subir: ' + message);
                });
            }
        });
        console.log("Dropzone inicializado correctamente");
    } catch (e) {
        console.error("Error al inicializar Dropzone:", e);
    }
}

    // 4. POLLING (CADA 5 SEGUNDOS)
    setInterval(() => {
        if (typeof nombreSala === 'undefined' || !nombreSala) return;

        $.getJSON('./utilidades/leer_notificaciones.php', { sala: nombreSala }, (data) => {
            const $lista = $('#lista-archivos');
            if (!$lista.length || !Array.isArray(data)) return;

            $lista.empty();
            data.forEach(n => {
                const $item = $('<li>');
                if(n.tipo === 'archivo') {
                    $item.html(`<a href="uploads/${n.contenido}" target="_blank">📎 ${n.contenido}</a>`);
                } else {
                    $item.text(n.contenido);
                }
                $lista.append($item);
            });
        }).fail(() => {
           console.log("Error de polling (notificaciones)");
        });
    }, 5000);
});