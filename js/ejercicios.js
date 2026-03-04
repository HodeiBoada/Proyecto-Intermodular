// Configuración global de Toastr
toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "timeOut": "3000"
};

// --- 1. LÓGICA DE ANÁLISIS PANDAS ---
document.getElementById('btnAnalizarEjercicios')?.addEventListener('click', function() {
    const cat = document.getElementById('selectCategoria').value;
    const resultado = document.getElementById('resultado-api-ejercicios');
    const contenedor = document.getElementById('contenedor-api-ejercicios');

    contenedor.style.display = 'block';
    resultado.innerHTML = '<div class="text-center"><div class="spinner-border text-success"></div></div>';

    fetch(`http://127.0.0.1:8000/ejercicios?categoria=${cat}`)
        .then(res => res.json())
        .then(data => {
            if(data.length === 0) {
                resultado.innerHTML = '<div class="alert alert-warning">No hay ejercicios en esta categoría.</div>';
                return;
            }
            
            // Renderizamos la tabla que devuelve Pandas
            let html = `
                <div class="table-responsive mt-3">
                    <table class="table table-sm table-bordered">
                        <thead class="table-success text-center">
                            <tr>
                                <th>Ejercicio</th>
                                <th>Dificultad</th>
                                <th>Descanso Sugerido</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">`;
            
            data.forEach(item => {
                html += `
                    <tr>
                        <td>${item.nombre}</td>
                        <td>${item.dificultad}</td>
                        <td><span class="badge bg-dark">${item.descanso_sugerido}</span></td>
                    </tr>`;
            });

            html += '</tbody></table></div>';
            resultado.innerHTML = html;
            toastr.info(`Análisis de ${data.length} ejercicios completado`);
        })
        .catch(() => toastr.error("Error al conectar con la API de Python"));
});

// --- 2. LÓGICA CRUD CON SWEETALERT2 Y TOASTR ---

function eliminarEjercicio(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Se eliminará permanentemente de la biblioteca y de las rutinas asociadas.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`http://127.0.0.1:8000/ejercicios/${id}`, { method: 'DELETE' })
                .then(res => res.json())
                .then(() => {
                    Swal.fire('¡Eliminado!', 'El ejercicio ha sido borrado.', 'success')
                    .then(() => location.reload());
                });
        }
    });
}

function cargarEdicionEjercicio(item) {
    document.getElementById('modalTituloE').innerText = "Editar Ejercicio";
    document.getElementById('edit_id_e').value = item.id_ejercicio;
    document.getElementById('nombre_e').value = item.nombre;
    document.getElementById('categoria_e').value = item.categoria.toLowerCase();
    document.getElementById('dificultad_e').value = item.dificultad;
    
    new bootstrap.Modal(document.getElementById('modalEjercicio')).show();
}

function guardarEjercicio() {
    const id = document.getElementById('edit_id_e').value;
    const nombre = document.getElementById('nombre_e').value;
    const cat = document.getElementById('categoria_e').value;
    const dif = document.getElementById('dificultad_e').value;

    if(!nombre) {
        toastr.error("El nombre es obligatorio");
        return;
    }

    let url = `http://127.0.0.1:8000/ejercicios`;
    let metodo = id ? 'PUT' : 'POST';
    
    // Construcción de URL con parámetros
    if(id) url += `/${id}?nombre=${encodeURIComponent(nombre)}&categoria=${cat}&dificultad=${dif}`;
    else url += `?nombre=${encodeURIComponent(nombre)}&categoria=${cat}&dificultad=${dif}`;

    fetch(url, { method: metodo })
        .then(res => res.json())
        .then(() => {
            toastr.success(id ? "Ejercicio actualizado" : "Ejercicio creado");
            setTimeout(() => location.reload(), 1200);
        })
        .catch(() => toastr.error("Error al guardar"));
}

function limpiarFormularioEjercicio() {
    document.getElementById('modalTituloE').innerText = "Nuevo Ejercicio";
    document.getElementById('edit_id_e').value = "";
    document.getElementById('nombre_e').value = "";
}

$(document).ready(function() {
    $('#tablaEjercicios').DataTable({
        "language": {
            "sSearch": "Filtrar ejercicios:",
            "sLengthMenu": "Ver _MENU_",
            "sZeroRecords": "No hay coincidencias",
            "sInfo": "Mostrando _TOTAL_ ejercicios",
            "sInfoEmpty": "Sin datos",
            "oPaginate": {
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        },
        "lengthChange": false,
        "pageLength": 5,
        "order": [[0, 'asc']],
        "responsive": true
    });
});