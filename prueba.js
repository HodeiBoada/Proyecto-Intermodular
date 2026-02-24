let jitsiApi = null;
let inicioLlamada = null;
let llamadaGuardada = false;
let nombreSala = '';

/** -----------------------------
 * FUNCIONES JITSI
 * -----------------------------
 */
function joinRoom() {
  let roomName = $('#room-name').val().trim();
  if (!roomName) {
    toastr.warning('Debes introducir un nombre de sala');
    return;
  }

  nombreSala = roomName.toLowerCase().replace(/[^a-z0-9-]/g, '-');
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
    toastr.error('No se pudo conectar con la sala. Intenta de nuevo.');
  }
}

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

    fetch('guardar_llamada.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
      keepalive: true
    })
      .then(() => console.log('Llamada guardada con fetch + keepalive'))
      .catch(() => console.warn('Error al enviar llamada'));
  } else {
    console.log('Llamada demasiado corta, no se guarda');
  }

  llamadaGuardada = true;
  inicioLlamada = null;
}

function limpiarJitsi() {
  if (jitsiApi) {
    jitsiApi.dispose();
    jitsiApi = null;
  }
  $('#current-room').text('');
  $('#jitsi-container').html('');
  $('#boton-colgar').hide();
}

/** -----------------------------
 * FUNCIONES NOTIFICACIONES
 * -----------------------------
 */
function enviarNotificacion(tipo, contenido) {
  $.post('enviar_notificacion.php', { tipo, contenido }, (data) => {
    if (data.status === 'ok') {
      toastr.info(`Notificación enviada: ${tipo}`);
    }
  }, 'json').fail(() => toastr.error('Error enviando notificación'));
}

function solicitarAyuda() {
  toastr.info("Tu solicitud ha sido enviada. Un entrenador será notificado.");
  enviarNotificacion(
    'ayuda',
    'El usuario ha solicitado ayuda para unirse a la videollamada.'
  );
}

/** -----------------------------
 * HISTORIAL DE SESIONES
 * -----------------------------
 */
function mostrarHistorial() {
  $.getJSON('leer_historial.php', (historial) => {
    const contenedor = $('#historial-sesiones');
    if (!contenedor.length) return;

    contenedor.html(
      historial.map(s => {
        const fecha = new Date(s.fecha);
        const fechaFormateada = fecha.toLocaleDateString('es-ES', {
          day: '2-digit',
          month: '2-digit',
          year: '2-digit'
        });
        return `<li>Llamada del ${fechaFormateada} — ${s.duracion} min</li>`;
      }).join('')
    );
  }).fail(() => toastr.error('Error leyendo historial'));
}

/** -----------------------------
 * DOM READY
 * -----------------------------
 */
$(document).ready(() => {

  mostrarHistorial();

  // Botones Jitsi
  $('#boton-unirse').on('click', joinRoom);
  $('#boton-ayuda').on('click', solicitarAyuda);
  $('#boton-colgar').on('click', () => {
    guardarLlamada();
    if (jitsiApi) jitsiApi.executeCommand('hangup');

    setTimeout(() => {
      limpiarJitsi();
      mostrarHistorial();
    }, 500);
  });

  /** -----------------------------
   * DROPZONE
   * -----------------------------
   */
  const $zona = $('#zona-subida');
  if ($zona.length) {
    Dropzone.autoDiscover = false;
    $zona.addClass('dropzone');

    new Dropzone('#zona-subida', {
      url: "subir_archivo.php",
      acceptedFiles: ".pdf",
      maxFilesize: 5,
      dictDefaultMessage: "Arrastra tu archivo PDF aquí o haz clic para subir",
      init: function () {
        this.on("success", function(file, response){
          if(response.status === 'ok') {
            toastr.success(`Archivo "${response.archivo}" subido correctamente.`);
          } else {
            toastr.error('Error al subir el archivo: ' + response.message);
          }
        });
        this.on("error", function () {
          toastr.error('Error al subir el archivo.');
        });
      }
    });
  }

  /** -----------------------------
   * LISTA DE ARCHIVOS / NOTIFICACIONES
   * -----------------------------
   */
  setInterval(() => {
    $.getJSON('leer_notificaciones.php', (data) => {
      const $lista = $('#lista-archivos');
      if (!$lista.length) return;

      $lista.empty();
      data.forEach(n => {
        const $item = $('<li>');
        if(n.tipo === 'archivo') {
          $item.html(`<a href="uploads/${n.contenido}" target="_blank">${n.contenido}</a>`);
        } else {
          $item.text(n.contenido);
        }
        $lista.append($item);
      });
    });
  }, 5000);

});