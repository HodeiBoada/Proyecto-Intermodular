    $(document).ready(function() {
        let ultimaAyudaDetectada = ""; // ID de la última notificación para evitar duplicados visuales
        let tituloOriginal = document.title;

        // Función para limpiar la notificación en la pestaña y en la Base de Datos
        function limpiarNotificacion() {
            document.title = tituloOriginal; 
            ultimaAyudaDetectada = ""; 
            // Llamada AJAX para marcar como leída en la BD y que no vuelva a saltar
            $.get('navbar.php?accion=limpiar_ayuda');
        }

        // Configuración de Toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "0", // No se quita sola para asegurar que el entrenador la vea
            "extendedTimeOut": "0",
            "onHidden": function() { limpiarNotificacion(); },
            "onCloseClick": function() { limpiarNotificacion(); }
        };

        function revisarAyudaGlobal() {
            // Consultamos el archivo JSON que busca registros 'pendientes'
            $.getJSON('notificaciones_globales.php', function(data) {
                if (data.hay_ayuda) {
                    // Solo mostramos si el mensaje es distinto (o el ID de ayuda es nuevo)
                    if (data.mensaje !== ultimaAyudaDetectada) {
                        ultimaAyudaDetectada = data.mensaje;
                        
                        toastr.error(data.mensaje, "¡SOLICITUD DE AYUDA!", {
                            onclick: function() {
                                // Al hacer clic, limpiamos y redirigimos a la sala
                                limpiarNotificacion();
                                window.location.href = data.url_sala;
                            }
                        });
                        
                        document.title = "⚠️ AYUDA PENDIENTE";
                    }
                } else {
                    // Si ya no hay ayudas pendientes, restauramos el título de la pestaña
                    if (document.title.includes("AYUDA PENDIENTE")) {
                        document.title = tituloOriginal;
                    }
                }
            });
        }
        // Revisión cada 10 segundos
        setInterval(revisarAyudaGlobal, 10000);
    });