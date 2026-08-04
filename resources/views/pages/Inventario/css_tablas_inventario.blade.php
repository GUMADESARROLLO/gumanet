<style>
  .btn-export-inventario {
    width: 100%;
    min-width: 0;
    box-sizing: border-box;
  }

  .panel-fact {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,.06);
    border: 1px solid #e5e7eb;
  }
  .panel-fact .table-responsive-wrap {
    overflow-x: auto;
  }
  .panel-fact table.dataTable {
    width: 100% !important;
    border-collapse: collapse;
    font-size: .85rem;
    margin: 0 !important;
  }
  .panel-fact table.dataTable thead th {
    background: {{ $Style['Color'] }} !important;
    color: #fff !important;
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .05em;
    text-transform: uppercase;
    padding: .65rem .9rem;
    border-bottom: 2px solid {{ $Style['Color'] }};
    white-space: normal !important;
    word-break: normal;
    overflow-wrap: normal;
    line-height: 1.25;
    text-align: center;
    vertical-align: bottom;
    position: sticky;
    top: 0;
    border-top: none;
  }
  .panel-fact table.dataTable thead th.sorting,
  .panel-fact table.dataTable thead th.sorting_asc,
  .panel-fact table.dataTable thead th.sorting_desc {
    background-image: none !important;
  }
  .panel-fact table.dataTable tbody td {
    padding: .65rem .9rem;
    border-bottom: 1px solid #f0f1f3;
    vertical-align: middle;
  }
  .panel-fact table.dataTable tbody tr:hover { background: #f7f9fc; }
  .panel-fact table.dataTable tbody tr:last-child td { border-bottom: none; }
  .panel-fact .panel-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: .7rem 1.1rem;
    background: #f7f8fa;
    border-top: 1px solid #e5e7eb;
    font-size: .8rem;
    color: #6b7280;
  }
  .panel-fact .paginacion-custom {
    display: inline-flex;
    align-items: center;
    flex-wrap: wrap;
    justify-content: flex-end;
  }
  .panel-fact .paginacion-custom .pagina {
    width: 26px;
    height: 26px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    font-weight: 600;
    margin: 0 2px;
    color: #6b7280;
    text-decoration: none;
    font-size: .8rem;
  }
  .panel-fact .paginacion-custom .pagina.activa {
    background: #1e3a6e;
    color: #fff;
  }
  .panel-fact .paginacion-custom .pagina-nav {
    color: #6b7280;
    text-decoration: none;
    font-size: .8rem;
    margin: 0 4px;
  }
  .panel-fact .paginacion-custom .pagina-nav.disabled {
    opacity: .4;
    pointer-events: none;
  }
  .panel-fact .paginacion-custom .paginas-ellipsis {
    color: #c1c5cc;
    margin: 0 2px;
  }
</style>
