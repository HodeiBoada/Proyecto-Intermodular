// Configuración global de Toastr
toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "timeOut": "3000"
};

// --- 1. LÓGICA DE LA API PYTHON (PANDAS) ---
document.getElementById('btnAnalizar')?.addEventListener('click', function() {
    const cal = document.getElementById('inputCalorias').value || 1000;
    const contenedor = document.getElementById('contenedor-api');
    const resultado = document.getElementById('resultado-api');

    contenedor.style.display = 'block';
    resultado.innerHTML = '<div class="text-center"><div class="spinner-border text-primary"></div></div>';

    fetch(`http://127.0.0.1:8000/comidas?max_cal=${cal}`)
        .then(res => res.json())
        .then(data => {
            if(data.length === 0) {
                resultado.innerHTML = '<div class="alert alert-warning">No hay comidas bajo ese límite calórico.</div>';
                return;
            }

            let html = `
                <div class="table-responsive mt-3">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Comida</th>
                                <th>Kcal</th>
                                <th>Categoría (Pandas)</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">`;
            
            data.forEach(item => {
                const color = item.categoria_energia === 'Baja en grasa' ? 'success' : 'warning';
                html += `
                    <tr>
                        <td>${item.nombre}</td>
                        <td>${item.calorias}</td>
                        <td><span class="badge bg-${color}">${item.categoria_energia}</span></td>
                    </tr>`;
            });
            
            html += '</tbody></table></div>';
            resultado.innerHTML = html;
            toastr.info(`Análisis completado: ${data.length} platos encontrados`);
        })
        .catch(() => {
            resultado.innerHTML = '<div class="alert alert-danger">API Python offline.</div>';
            toastr.error("No se pudo conectar con el servidor de análisis");
        });
});

// --- 2. LÓGICA CRUD (CON SWEETALERT2 Y TOASTR) ---

function eliminarComida(id) {
    Swal.fire({
        title: '¿Eliminar comida?',
        text: "Esta acción quitará el alimento de la base de datos.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`http://127.0.0.1:8000/comidas/${id}`, { method: 'DELETE' })
                .then(res => res.json())
                .then(() => {
                    Swal.fire('¡Borrado!', 'La comida ha sido eliminada.', 'success')
                    .then(() => location.reload());
                })
                .catch(() => toastr.error("Error al eliminar"));
        }
    });
}

function cargarEdicion(item) {
    document.getElementById('modalTitulo').innerText = "Editar Comida";
    document.getElementById('edit_id').value = item.id_comida;
    document.getElementById('nombre').value = item.nombre;
    document.getElementById('calorias').value = item.calorias;
    document.getElementById('tipo').value = item.tipo;
    
    const modal = new bootstrap.Modal(document.getElementById('modalComida'));
    modal.show();
}

function guardarComida() {
    const id = document.getElementById('edit_id').value;
    const nombre = document.getElementById('nombre').value;
    const cal = document.getElementById('calorias').value;
    const tipo = document.getElementById('tipo').value;

    if(!nombre || !cal) {
        toastr.warning("Por favor, rellena los campos obligatorios");
        return;
    }

    let url = `http://127.0.0.1:8000/comidas`;
    let metodo = 'POST';

    // Usamos encodeURIComponent para evitar errores con espacios o símbolos
    if(id) {
        if(id) {
    // Añadimos el tipo a la URL del PUT
    url += `/${id}?nombre=${encodeURIComponent(nombre)}&calorias=${cal}&tipo=${tipo}`;
    metodo = 'PUT';
}
    } else {
        url += `?nombre=${encodeURIComponent(nombre)}&calorias=${cal}&tipo=${tipo}`;
    }

    fetch(url, { method: metodo })
        .then(res => res.json())
        .then(() => {
            toastr.success(id ? "Comida actualizada correctamente" : "Comida añadida con éxito");
            setTimeout(() => location.reload(), 1200);
        })
        .catch(() => toastr.error("Error al guardar en la base de datos"));
}

function limpiarFormulario() {
    document.getElementById('modalTitulo').innerText = "Añadir Comida";
    document.getElementById('edit_id').value = "";
    document.getElementById('nombre').value = "";
    document.getElementById('calorias').value = "";
}

// 3. Inicialización de DataTable
$(document).ready(function() {
    $('#tablaComidas').DataTable({
        "language": {
            "sSearch": "Filtrar comidas:",
            "sLengthMenu": "Ver _MENU_",
            "sZeroRecords": "No hay comidas en tu plan",
            "sInfo": "Total: _TOTAL_ comidas",
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