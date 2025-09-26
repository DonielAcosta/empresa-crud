<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Empresas</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        .table-responsive {
            max-height: 600px;
            overflow-y: auto;
        }
        
        .loading {
            display: none;
        }
        
        .error-message {
            display: none;
        }
        
        .success-message {
            display: none;
        }
        
        .btn-action {
            margin: 2px;
        }
        
        .search-container {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="#">
                            <i class="bi bi-building"></i> CRUD Empresas
                        </a>
                        <div class="navbar-nav ms-auto">
                            <span class="navbar-text">
                                <i class="bi bi-calendar"></i> <?= date('d/m/Y H:i') ?>
                            </span>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <div class="container mt-4">
            <!-- Estadísticas -->
            <div class="row">
                <div class="col-md-4">
                    <div class="stats-card">
                        <h5><i class="bi bi-building"></i> Total Empresas</h5>
                        <h3 id="total-empresas">0</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <h5><i class="bi bi-search"></i> Resultados</h5>
                        <h3 id="resultados-busqueda">0</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <h5><i class="bi bi-clock"></i> Última Actualización</h5>
                        <h6 id="ultima-actualizacion">-</h6>
                    </div>
                </div>
            </div>

            <!-- Búsqueda y Acciones -->
            <div class="search-container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control" id="search-input" 
                                   placeholder="Buscar por ID o razón social...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-success" id="btn-nueva-empresa">
                                <i class="bi bi-plus-circle"></i> Nueva Empresa
                            </button>
                            <button type="button" class="btn btn-info" id="btn-exportar-json">
                                <i class="bi bi-download"></i> Exportar JSON
                            </button>
                            <button type="button" class="btn btn-warning" id="btn-reporte-pdf">
                                <i class="bi bi-file-pdf"></i> Reporte PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mensajes -->
            <div class="alert alert-danger error-message" id="error-message" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <span id="error-text"></span>
            </div>
            
            <div class="alert alert-success success-message" id="success-message" role="alert">
                <i class="bi bi-check-circle"></i> <span id="success-text"></span>
            </div>

            <!-- Tabla de Empresas -->
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-table"></i> Listado de Empresas</h5>
                </div>
                <div class="card-body">
                    <div class="loading text-center" id="loading">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p>Cargando empresas...</p>
                    </div>
                    
                    <div class="table-responsive" id="table-container">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>RIF</th>
                                    <th>Razón Social</th>
                                    <th>Dirección</th>
                                    <th>Teléfono</th>
                                    <th>Fecha Creación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="empresas-table-body">
                                <!-- Los datos se cargarán aquí -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Crear/Editar Empresa -->
    <div class="modal fade" id="empresaModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Nueva Empresa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="empresa-form">
                        <input type="hidden" id="empresa-id">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rif" class="form-label">RIF *</label>
                                    <input type="text" class="form-control" id="rif" 
                                           placeholder="J-12345678-9" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Teléfono *</label>
                                    <input type="text" class="form-control" id="telefono" 
                                           placeholder="+58 212 123-4567" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="razon-social" class="form-label">Razón Social *</label>
                            <input type="text" class="form-control" id="razon-social" 
                                   placeholder="Nombre de la empresa" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección *</label>
                            <textarea class="form-control" id="direccion" rows="3" 
                                      placeholder="Dirección completa de la empresa" required></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btn-guardar">
                        <i class="bi bi-save"></i> Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación -->
    <div class="modal fade" id="confirmModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de que desea eliminar esta empresa?</p>
                    <p class="text-muted">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btn-confirmar-eliminar">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <script>
        // Variables globales
        let empresas         = [];
        let empresaAEliminar = null;
        let searchTimeout    = null;

        // Inicializar aplicación
        $(document).ready(function() {
            cargarEmpresas();
            configurarEventos();
        });

        // Configurar eventos
        function configurarEventos() {
            // Búsqueda con debounce
            $('#search-input').on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    cargarEmpresas();
                }, 500);
            });

            // Botones principales
            $('#btn-nueva-empresa').click(() => abrirModalEmpresa());
            $('#btn-exportar-json').click(() => exportarJSON());
            $('#btn-reporte-pdf').click(() => generarPDF());
            $('#btn-guardar').click(() => guardarEmpresa());
            $('#btn-confirmar-eliminar').click(() => eliminarEmpresa());
        }

        // Cargar empresas
        function cargarEmpresas() {
            mostrarLoading(true);
            ocultarMensajes();

            const search = $('#search-input').val();
            const url = search ? `api/empresas_list.php?search=${encodeURIComponent(search)}` : 'api/empresas_list.php';

            $.get(url)
                .done(function(response) {
                    if (response.success) {
                        empresas = response.data.empresas;
                        mostrarEmpresas(empresas);
                        actualizarEstadisticas(response.data.total, response.data.search);
                    } else {
                        mostrarError('Error al cargar empresas');
                    }
                })
                .fail(function(xhr) {
                    const error = xhr.responseJSON?.error || { message: 'Error de conexión' };
                    mostrarError(error.message);
                })
                .always(() => mostrarLoading(false));
        }

        // Mostrar empresas en tabla
        function mostrarEmpresas(empresas) {
            const tbody = $('#empresas-table-body');
            tbody.empty();

            if (empresas.length === 0) {
                tbody.append(`
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            <i class="bi bi-inbox"></i> No se encontraron empresas
                        </td>
                    </tr>
                `);
                return;
            }

            empresas.forEach(empresa => {
                const fecha = new Date(empresa.fecha_creacion).toLocaleDateString('es-ES');
                const row = `
                    <tr>
                        <td>${empresa.id_empresa}</td>
                        <td><span class="badge bg-primary">${empresa.rif}</span></td>
                        <td>${empresa.razon_social}</td>
                        <td>${empresa.direccion}</td>
                        <td>${empresa.telefono}</td>
                        <td>${fecha}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary btn-action" 
                                    onclick="editarEmpresa(${empresa.id_empresa})" 
                                    title="Editar">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger btn-action" 
                                    onclick="confirmarEliminar(${empresa.id_empresa})" 
                                    title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tbody.append(row);
            });
        }

        // Actualizar estadísticas
        function actualizarEstadisticas(total, search) {
            $('#total-empresas').text(total);
            $('#resultados-busqueda').text(empresas.length);
            $('#ultima-actualizacion').text(new Date().toLocaleTimeString('es-ES'));
        }

        // Abrir modal para nueva empresa
        function abrirModalEmpresa() {
            $('#modal-title').text('Nueva Empresa');
            $('#empresa-form')[0].reset();
            $('#empresa-id').val('');
            limpiarValidaciones();
            $('#empresaModal').modal('show');
        }

        // Editar empresa
        function editarEmpresa(id) {
            const empresa = empresas.find(e => e.id_empresa == id);
            if (!empresa) return;

            $('#modal-title').text('Editar Empresa');
            $('#empresa-id').val(empresa.id_empresa);
            $('#rif').val(empresa.rif);
            $('#razon-social').val(empresa.razon_social);
            $('#direccion').val(empresa.direccion);
            $('#telefono').val(empresa.telefono);
            
            limpiarValidaciones();
            $('#empresaModal').modal('show');
        }

        // Guardar empresa
        function guardarEmpresa() {
            if (!validarFormulario()) return;

            const data = {
                rif: $('#rif').val(),
                razon_social: $('#razon-social').val(),
                direccion: $('#direccion').val(),
                telefono: $('#telefono').val()
            };

            const id = $('#empresa-id').val();
            const url = id ? `api/empresas_update.php/${id}` : 'api/empresas_create.php';
            const method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                contentType: 'application/json',
                data: JSON.stringify(data)
            })
            .done(function(response) {
                if (response.success) {
                    mostrarExito(id ? 'Empresa actualizada exitosamente' : 'Empresa creada exitosamente');
                    $('#empresaModal').modal('hide');
                    cargarEmpresas();
                } else {
                    mostrarError('Error al guardar empresa');
                }
            })
            .fail(function(xhr) {
                const error = xhr.responseJSON?.error || { message: 'Error de conexión' };
                mostrarError(error.message);
            });
        }

        // Confirmar eliminación
        function confirmarEliminar(id) {
            empresaAEliminar = id;
            $('#confirmModal').modal('show');
        }

        // Eliminar empresa
        function eliminarEmpresa() {
            if (!empresaAEliminar) return;

            $.ajax({
                url: `api/empresas_delete.php/${empresaAEliminar}`,
                method: 'DELETE'
            })
            .done(function(response) {
                if (response.success) {
                    mostrarExito('Empresa eliminada exitosamente');
                    $('#confirmModal').modal('hide');
                    cargarEmpresas();
                } else {
                    mostrarError('Error al eliminar empresa');
                }
            })
            .fail(function(xhr) {
                const error = xhr.responseJSON?.error || { message: 'Error de conexión' };
                mostrarError(error.message);
            })
            .always(() => {
                empresaAEliminar = null;
            });
        }

        // Exportar JSON
        function exportarJSON() {
            const search = $('#search-input').val();
            const url = search ? `api/empresas_export_json.php?search=${encodeURIComponent(search)}` : 'api/empresas_export_json.php';
            window.open(url, '_blank');
        }

        // Generar PDF
        function generarPDF() {
            const search = $('#search-input').val();
            const url = search ? `api/empresas_report_pdf.php?search=${encodeURIComponent(search)}` : 'api/empresas_report_pdf.php';
            window.open(url, '_blank');
        }

        // Validar formulario
        function validarFormulario() {
            let valido = true;
            limpiarValidaciones();

            // Validar RIF
            const rif = $('#rif').val();
            if (!rif) {
                mostrarErrorCampo('rif', 'El RIF es requerido');
                valido = false;
            } else if (!/^[A-Za-z]-\d{8}-\d$/.test(rif)) {
                mostrarErrorCampo('rif', 'Formato inválido (ej. J-12345678-9)');
                valido = false;
            }

            // Validar razón social
            const razonSocial = $('#razon-social').val();
            if (!razonSocial) {
                mostrarErrorCampo('razon-social', 'La razón social es requerida');
                valido = false;
            }

            // Validar dirección
            const direccion = $('#direccion').val();
            if (!direccion) {
                mostrarErrorCampo('direccion', 'La dirección es requerida');
                valido = false;
            }

            // Validar teléfono
            const telefono = $('#telefono').val();
            if (!telefono) {
                mostrarErrorCampo('telefono', 'El teléfono es requerido');
                valido = false;
            } else if (!/^[\d\s\-\(\)\+]+$/.test(telefono)) {
                mostrarErrorCampo('telefono', 'Formato de teléfono inválido');
                valido = false;
            }

            return valido;
        }

        // Mostrar error en campo
        function mostrarErrorCampo(campoId, mensaje) {
            const campo = $(`#${campoId}`);
            campo.addClass('is-invalid');
            campo.siblings('.invalid-feedback').text(mensaje);
        }

        // Limpiar validaciones
        function limpiarValidaciones() {
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').text('');
        }

        // Mostrar/ocultar loading
        function mostrarLoading(mostrar) {
            $('#loading').toggle(mostrar);
            $('#table-container').toggle(!mostrar);
        }

        // Mostrar error
        function mostrarError(mensaje) {
            $('#error-text').text(mensaje);
            $('#error-message').show();
            setTimeout(ocultarMensajes, 5000);
        }

        // Mostrar éxito
        function mostrarExito(mensaje) {
            $('#success-text').text(mensaje);
            $('#success-message').show();
            setTimeout(ocultarMensajes, 3000);
        }

        // Ocultar mensajes
        function ocultarMensajes() {
            $('.error-message, .success-message').hide();
        }
    </script>
</body>
</html>
