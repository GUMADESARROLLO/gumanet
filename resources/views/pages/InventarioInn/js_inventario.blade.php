<script type="text/javascript">
/**
 * Inventario Innova — carga de articulos de PRODUCCION.dbo.inn_iweb_articulos.
 * La data se resuelve con promises (fetch) y se entrega ya construida a la DataTable.
 */
$(document).ready(function () {

    var URL_ARTICULOS = "{{ route('InventarioInn.getArticulos') }}";
    var URL_DETALLE   = "{{ url('/InventarioInn/getDetalle') }}";
    var tabla = null;

    var CABECERAS = {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    };

    function pedirJson(url) {
        return fetch(url, { method: 'GET', credentials: 'same-origin', headers: CABECERAS })
            .then(function (respuesta) {
                return respuesta.json()
                    .catch(function () { return {}; })
                    .then(function (json) {
                        if (!respuesta.ok) {
                            return Promise.reject(new Error(json.error || ('HTTP ' + respuesta.status)));
                        }
                        return json;
                    });
            });
    }

    function num(valor) {
        return numeral(valor || 0).format('0,0.00');
    }

    function txt(valor) {
        return (valor === null || valor === undefined || valor === '') ? '&mdash;' : String(valor);
    }

    /**
     * Filtro en memoria para las tablas del modal: coincidencia parcial,
     * sin distinguir mayúsculas, sobre los campos indicados.
     *
     * @param  {Array}  filas
     * @param  {string} termino
     * @param  {Array}  campos
     * @returns {Array}
     */
    function filtrar(filas, termino, campos) {
        var t = $.trim(termino || '').toLowerCase();

        if (t === '') { return filas; }

        return filas.filter(function (f) {
            for (var i = 0; i < campos.length; i++) {
                var v = f[campos[i]];
                if (v !== null && v !== undefined &&
                    String(v).toLowerCase().indexOf(t) !== -1) {
                    return true;
                }
            }
            return false;
        });
    }

    /**
     * @returns {Promise<Array>} filas [{ARTICULO, DESCRIPCION, EXISTENCIA}, ...]
     */
    function obtenerArticulos() {
        return pedirJson(URL_ARTICULOS).then(function (json) {
            return Array.isArray(json.data) ? json.data : [];
        });
    }

    /**
     * @param  {string} articulo
     * @returns {Promise<Object>} { articulo, bodegas, precios }
     */
    function obtenerDetalle(articulo) {
        return pedirJson(URL_DETALLE + '/' + encodeURIComponent(articulo));
    }

    function construirTabla(filas) {

        tabla = $('#tbl_articulos_inn').DataTable({
            "data": filas,
            "destroy": true,
            "info": true,
            // searching se deja activo porque alimenta tabla.search(), pero el input
            // nativo (f) y el length menu (l) no se generan: los omite el dom.
            "searching": true,
            "order": [[0, 'asc']],
            "pageLength": 25,
            "dom": '<"row"<"col-12"tr>>' +
                   '<"row mt-2"<"col-md-5"i><"col-md-7 d-flex justify-content-end"p>>',
            // los anchos se declaran aquí, no en el <th>: con scrollY el thead visible
            // es un clon y los estilos inline del markup no se propagan al cuerpo
            "autoWidth": false,
            "columns": [
                {
                    "data": "ARTICULO",
                    "width": "160px",
                    "className": "align-middle",
                    "render": function (dato) {
                        return '<span class="font-weight-bold">' + (dato || '') + '</span>';
                    }
                },
                {
                    "data": "DESCRIPCION",
                    "className": "align-middle",
                    "render": function (dato) {
                        return dato ? String(dato).toUpperCase() : '';
                    }
                },
                {
                    "data": "EXISTENCIA",
                    "width": "140px",
                    "className": "align-middle text-right",
                    // se ordena por el valor numerico, no por el texto formateado
                    "render": function (dato, tipo) {
                        if (tipo === 'sort' || tipo === 'type') {
                            return parseFloat(dato) || 0;
                        }
                        return num(dato);
                    }
                }
            ],
            "language": {
                "zeroRecords": "NO HAY COINCIDENCIAS",
                "emptyTable": "NO HAY ARTÍCULOS REGISTRADOS",
                "info": "MOSTRANDO _START_ A _END_ DE _TOTAL_ ARTÍCULOS",
                "infoEmpty": "SIN ARTÍCULOS",
                "infoFiltered": "(FILTRADOS DE _MAX_)",
                "paginate": {
                    "first": "Primera",
                    "last": "Última",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            // indica que la fila es clickeable (abre el modal de detalle)
            "createdRow": function (row) {
                $(row).css('cursor', 'pointer').attr('title', 'Ver detalle del artículo');
            },
            "scrollY": "600px",
            "scrollCollapse": true
            // sin "responsive": la extension Responsive no soporta tablas con
            // scroll y competia con el thead clonado que genera scrollY
        });

        // reajuste tras el primer draw y en cada resize, para que el thead clonado
        // siga cuadrando con el cuerpo
        tabla.columns.adjust();
        $(window).on('resize.inventarioInn', function () {
            tabla.columns.adjust();
        });

        // el search nativo ya no existe en el DOM: la búsqueda entra por este input
        $('#id_buscar_articulo').prop('disabled', false).on('keyup', function () {
            tabla.search(this.value).draw();
        });

        // click en fila -> modal de detalle. Se delega sobre el tbody ya movido
        // por scrollY, asi que hay que enlazarlo despues del init.
        $('#tbl_articulos_inn tbody').on('click', 'tr', function () {
            var fila = tabla.row(this).data();
            if (fila && fila.ARTICULO) {
                abrirDetalle(fila);
            }
        });
    }

    // ---------------------------------------------------------------- modal

    // data del articulo abierto; los buscadores de cada tab filtran sobre esto
    var detalleActual = { bodegas: [], precios: [] };

    // se enlazan una sola vez: los inputs viven en el markup del modal
    $('#md_buscar_bodegas').on('keyup', pintarBodegas);
    $('#md_buscar_precios').on('keyup', pintarPrecios);

    function pintarBodegas() {
        var filas = filtrar(detalleActual.bodegas,
                            $('#md_buscar_bodegas').val(),
                            ['BODEGA', 'NOMBRE']);
        var html = '';
        var total = 0;

        $.each(filas, function (i, b) {
            total += parseFloat(b.CANT_DISPONIBLE) || 0;
            html += '<tr>' +
                        '<td class="font-weight-bold">' + txt(b.BODEGA) + '</td>' +
                        '<td>' + txt(b.NOMBRE) + '</td>' +
                        '<td class="text-right">' + num(b.CANT_DISPONIBLE) + '</td>' +
                    '</tr>';
        });

        if (!filas.length) {
            html = '<tr><td colspan="3" class="text-center text-muted py-3">' +
                   (detalleActual.bodegas.length ? 'NO HAY COINCIDENCIAS'
                                                 : 'SIN EXISTENCIAS REGISTRADAS EN BODEGA') +
                   '</td></tr>';
        }

        $('#md_tbody_bodegas').html(html);
        // el total refleja lo que está en pantalla, igual que el footer de DataTables
        $('#md_total_bodegas').text(num(total));
    }

    function pintarPrecios() {
        var filas = filtrar(detalleActual.precios,
                            $('#md_buscar_precios').val(),
                            ['NIVEL_PRECIO', 'VERSION']);
        var html = '';

        $.each(filas, function (i, p) {
            html += '<tr>' +
                        '<td class="font-weight-bold">' + txt(p.NIVEL_PRECIO) + '</td>' +
                        '<td class="text-right">' + num(p.PRECIO) + '</td>' +
                        '<td class="text-center">' + txt(p.VERSION) + '</td>' +
                    '</tr>';
        });

        if (!filas.length) {
            html = '<tr><td colspan="3" class="text-center text-muted py-3">' +
                   (detalleActual.precios.length ? 'NO HAY COINCIDENCIAS'
                                                 : 'SIN PRECIOS REGISTRADOS') +
                   '</td></tr>';
        }

        $('#md_tbody_precios').html(html);
    }

    function abrirDetalle(fila) {

        // encabezado inmediato con lo que ya trae la tabla, para que el modal
        // no se abra vacio mientras resuelve la promise
        $('#modal_articulo_inn_label').text(fila.DESCRIPCION || '');
        $('#md_codigo').text(fila.ARTICULO);

        $('#md_contenido').hide();
        $('#md_error').hide().text('');
        $('#md_cargando').show();

        // limpiar el estado del articulo anterior
        detalleActual = { bodegas: [], precios: [] };
        $('#md_buscar_bodegas, #md_buscar_precios').val('');

        // siempre se abre en el tab de bodegas
        $('#tab_bodegas_link').tab('show');

        $('#modal_articulo_inn').modal('show');

        obtenerDetalle(fila.ARTICULO)
            .then(function (detalle) {
                var art = detalle.articulo || {};

                $('#modal_articulo_inn_label').text(art.DESCRIPCION || fila.DESCRIPCION || '');
                $('#md_codigo').text(art.ARTICULO || fila.ARTICULO);

                detalleActual = {
                    bodegas: Array.isArray(detalle.bodegas) ? detalle.bodegas : [],
                    precios: Array.isArray(detalle.precios) ? detalle.precios : []
                };


                pintarBodegas();
                pintarPrecios();

                $('#md_contenido').show();

                if (typeof feather !== 'undefined') { feather.replace(); }
            })
            .catch(function (error) {
                console.error('InventarioInn detalle:', error);
                $('#md_error').text(error.message).show();
            })
            .then(function () {
                $('#md_cargando').hide();
            });
    }

    function mostrarError(error) {
        console.error('InventarioInn:', error);
        $('#id_error')
            .text('No se pudieron cargar los artículos de Innova. ' + error.message)
            .show();
    }

    obtenerArticulos()
        .then(function (filas) {
            // el contenedor debe ser visible ANTES de inicializar: con scrollY
            // DataTables clona el thead y mide los anchos al construir, y en un
            // contenedor display:none todo mide 0 -> encabezados desalineados
            $('#id_contenedor_tabla').show();
            construirTabla(filas);

            if (typeof feather !== 'undefined') { feather.replace(); }
        })
        .catch(mostrarError)
        .then(function () {
            $('#id_cargando').hide();
        });

});
</script>
