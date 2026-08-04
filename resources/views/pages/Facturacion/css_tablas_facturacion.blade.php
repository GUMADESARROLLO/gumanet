<style>
  .panel-fact {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,.06);
    border: 1px solid #e5e7eb;
  }
  .panel-fact .panel-header {
    background: #1e3a6e;
    color: #fff;
    padding: .8rem 1.25rem;
    font-size: .82rem;
    font-weight: 700;
    letter-spacing: .04em;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .panel-fact .panel-header .count {
    font-weight: 500;
    font-size: .75rem;
    opacity: .8;
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
    background: #f7f8fa;
    color: #6b7280;
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .05em;
    text-transform: uppercase;
    padding: .65rem .9rem;
    border-bottom: 2px solid #eef0f3;
    white-space: nowrap;
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
  .panel-fact .num-cell {
    text-align: right;
    font-variant-numeric: tabular-nums;
    font-weight: 600;
  }
  .panel-fact .link-cell a {
    color: #1e5aa8;
    font-weight: 600;
    text-decoration: none;
  }
  .panel-fact .link-cell a:hover { text-decoration: underline; }
  .panel-fact .ruta-badge {
    display: inline-block;
    background: #eef2f8;
    color: #1e3a6e;
    font-weight: 700;
    font-size: .74rem;
    padding: .18rem .5rem;
    border-radius: 5px;
    min-width: 34px;
    text-align: center;
  }
  .panel-fact .cliente-nombre {
    font-weight: 600;
    display: block;
  }
  .panel-fact .cliente-ruc {
    font-size: .74rem;
    color: #8a93a3;
  }
  .panel-fact .tiempo-pill {
    display: inline-block;
    font-size: .74rem;
    font-weight: 700;
    padding: .2rem .55rem;
    border-radius: 20px;
  }
  .panel-fact .tiempo-verde { background: #e3f6ec; color: #0f7a4d; }
  .panel-fact .tiempo-ambar { background: #fdf1de; color: #a06416; }
  .panel-fact .tiempo-rojo  { background: #fde9e9; color: #b02a2a; }
  .panel-fact .cant-pill {
    display: inline-block;
    background: #eef2f8;
    color: #1e3a6e;
    font-weight: 700;
    font-size: .8rem;
    min-width: 26px;
    padding: .15rem .45rem;
    border-radius: 6px;
    text-align: center;
  }
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
  @media (max-width: 767px) {
    .panel-fact table.dataTable thead { display: none; }
    .panel-fact table.dataTable tbody,
    .panel-fact table.dataTable tr,
    .panel-fact table.dataTable td {
      display: block;
      width: 100%;
    }
    .panel-fact table.dataTable tbody tr {
      border-bottom: 1px solid #eef0f3;
      padding: .6rem .9rem;
    }
    .panel-fact table.dataTable tbody td {
      display: flex;
      justify-content: space-between;
      gap: .75rem;
      border: none;
      padding: .2rem 0;
    }
    .panel-fact table.dataTable tbody td::before {
      content: attr(data-label);
      font-size: .66rem;
      font-weight: 700;
      letter-spacing: .04em;
      text-transform: uppercase;
      color: #8a93a3;
      flex-shrink: 0;
    }
    .panel-fact .num-cell { text-align: right; }
    .panel-fact .panel-footer {
      flex-direction: column;
      gap: .5rem;
      align-items: flex-start;
    }
  }
</style>
