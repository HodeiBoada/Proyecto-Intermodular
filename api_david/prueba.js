let jitsiApi = null;
let inicioLlamada = null;
let llamadaGuardada = false;
let nombreSala = '';

function joinRoom() {
  const roomName = document.getElementById('room-name').value.trim();
  if (!roomName) {
    alert('Debes introducir un nombre de sala');
    return;
  }

  nombreSala = roomName.toLowerCase().replace(/[^a-z0-9-]/g, '-');
  document.getElementById('current-room').textContent = nombreSala;

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

    document.getElementById('boton-colgar').style.display = 'inline-block';

  } catch (error) {
    console.error('Error al conectar con Jitsi:', error);
    alert('No se pudo conectar con la sala. Intenta de nuevo.');
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
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(payload),
      keepalive: true
    })
    .then(() => console.log('Llamada enviada con fetch + keepalive'))
    .catch(() => console.warn('Error al enviar con fetch + keepalive'));
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
  document.getElementById('current-room').textContent = '';
  document.getElementById('jitsi-container').innerHTML = '';
  document.getElementById('boton-colgar').style.display = 'none';
}

function enviarNotificacion(tipo, contenido) {
  fetch('enviar_notificacion.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `tipo=${encodeURIComponent(tipo)}&contenido=${encodeURIComponent(contenido)}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'ok') {
      console.log(`Notificación enviada: ${tipo}`);
    }
  })
  .catch(err => console.error('Error enviando notificación:', err));
}

function solicitarAyuda() {
  alert("Tu solicitud ha sido enviada. Un entrenador será notificado.");
  enviarNotificacion(
    'ayuda',
    'El usuario ha solicitado ayuda para unirse a la videollamada.'
  );
}

function mostrarHistorial() {
  fetch('leer_historial.php')
    .then(res => res.json())
    .then(historial => {
      const contenedor = document.getElementById('historial-sesiones');
      if (!contenedor) return;

      contenedor.innerHTML = historial.map(s => {
        const fecha = new Date(s.fecha);
        const fechaFormateada = fecha.toLocaleDateString('es-ES', {
          day: '2-digit',
          month: '2-digit',
          year: '2-digit'
        });
        return `<li>Llamada del ${fechaFormateada} — ${s.duracion} min</li>`;
      }).join('');
    })
    .catch(err => console.error('Error leyendo historial:', err));
}

document.addEventListener('DOMContentLoaded', () => {
  mostrarHistorial();

  const botonColgar = document.getElementById('boton-colgar');
  if (botonColgar) {
    botonColgar.addEventListener('click', () => {
      guardarLlamada();
      if (jitsiApi) {
        jitsiApi.executeCommand('hangup');
      }
      setTimeout(() => {
        limpiarJitsi();
        mostrarHistorial();
      }, 500);
    });
  }

  const uploadInput = document.getElementById('upload-pdf');
  if (uploadInput) {
    uploadInput.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (file && file.type === 'application/pdf') {
        const formData = new FormData();
        formData.append('archivo', file);

        fetch('subir_archivo.php', {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'ok') {
            alert(`Archivo "${data.archivo}" subido correctamente.`);
          } else {
            alert('Error al subir el archivo: ' + data.message);
          }
        });
      } else {
        alert('Solo se permiten archivos PDF.');
      }
    });
  }

  setInterval(() => {
    fetch('leer_notificaciones.php')
      .then(res => res.json())
      .then(data => {
        const lista = document.getElementById('lista-archivos');
        if (!lista) return;

        lista.innerHTML = '';
        data.forEach(n => {
          const item = document.createElement('li');
          if (n.tipo === 'archivo') {
            item.innerHTML = `<a href="uploads/${n.contenido}" target="_blank">${n.contenido}</a>`;
          } else {
            item.textContent = `${n.contenido}`;
          }
          lista.appendChild(item);
        });
      });
  }, 5000);
});
