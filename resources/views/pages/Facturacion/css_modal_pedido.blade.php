<style>
    #mdl-detalle-pedido-factura .modal-content {
      border: none;
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 24px 60px rgba(0,0,0,.22);
    }
    #mdl-detalle-pedido-factura .modal-dialog { max-width: 760px; }
    #mdl-detalle-pedido-factura .modal-body { padding: 0; }
    #mdl-detalle-pedido-factura .modal-header {
      background: #1e3a6e;
      color: #fff;
      padding: 1.1rem 1.5rem;
      border: none;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    #mdl-detalle-pedido-factura .modal-header .modal-title {
      font-size: 1.05rem;
      font-weight: 600;
      color: #fff;
    }
    #mdl-detalle-pedido-factura .modal-header .modal-title small {
      display: block;
      font-size: .72rem;
      font-weight: 500;
      opacity: .75;
      letter-spacing: .06em;
      text-transform: uppercase;
      margin-bottom: 2px;
    }
    #mdl-detalle-pedido-factura .modal-header .btn-close-custom {
      background: none;
      border: none;
      color: #fff;
      font-size: 1.4rem;
      line-height: 1;
      opacity: .85;
      padding: 0;
      margin-left: 1rem;
    }
    #mdl-detalle-pedido-factura .modal-header .btn-close-custom:hover { opacity: 1; }
    #mdl-detalle-pedido-factura .info-cliente {
      background: #f7f8fa;
      padding: .85rem 1.5rem;
      border-bottom: 1px solid #e5e7eb;
    }
    #mdl-detalle-pedido-factura .info-cliente .label {
      font-size: .68rem;
      font-weight: 700;
      letter-spacing: .05em;
      text-transform: uppercase;
      color: #8a93a3;
      margin-bottom: .15rem;
    }
    #mdl-detalle-pedido-factura .info-cliente .valor {
      font-size: .95rem;
      font-weight: 600;
      color: #1f2937;
    }
    #mdl-detalle-pedido-factura .info-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1rem;
      padding: 1rem 1.5rem;
      background: #fff;
    }
    @media (max-width: 576px) {
      #mdl-detalle-pedido-factura .info-grid { grid-template-columns: 1fr; gap: .75rem; }
    }
    #mdl-detalle-pedido-factura .info-card {
      background: #f7f8fa;
      border-radius: 10px;
      padding: .75rem .9rem;
    }
    #mdl-detalle-pedido-factura .info-card .label {
      font-size: .66rem;
      font-weight: 700;
      letter-spacing: .05em;
      text-transform: uppercase;
      color: #8a93a3;
      margin-bottom: .3rem;
    }
    #mdl-detalle-pedido-factura .info-card .valor {
      font-size: .95rem;
      font-weight: 600;
      color: #1f2937;
    }
    #mdl-detalle-pedido-factura .badge-tiempo {
      background: #e3f6ec;
      color: #0f7a4d;
      font-weight: 700;
      font-size: .8rem;
      padding: .3rem .6rem;
      border-radius: 6px;
      display: inline-block;
    }
    #mdl-detalle-pedido-factura .pill {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: .75rem;
      font-weight: 700;
      padding: 3px 10px;
      border-radius: 20px;
    }
    #mdl-detalle-pedido-factura .pill.active   { background: #e6f9f2; color: #1DB87A; }
    #mdl-detalle-pedido-factura .pill.inactive { background: #fdecea; color: #E03C3C; }
    #mdl-detalle-pedido-factura .pill.warning  { background: #fff5e6; color: #F5A623; }
    #mdl-detalle-pedido-factura .badge-bonificado {
      background: #e3f6ec;
      color: #0f7a4d;
      font-weight: 700;
      font-size: .75rem;
      padding: .2rem .5rem;
      border-radius: 6px;
      display: inline-block;
    }
    #mdl-detalle-pedido-factura .buscador-wrap {
      padding: 0 1.5rem 1rem;
      background: #fff;
    }
    #mdl-detalle-pedido-factura .buscador-wrap .input-group-text {
      background: #f7f8fa;
      border-right: none;
      color: #8a93a3;
    }
    #mdl-detalle-pedido-factura .buscador-wrap input {
      border-left: none;
      background: #f7f8fa;
    }
    #mdl-detalle-pedido-factura .buscador-wrap input:focus {
      box-shadow: none;
      border-color: #ced4da;
      background: #fff;
    }
    #mdl-detalle-pedido-factura .tabla-wrap {
      padding: 0 1.5rem 1rem;
      background: #fff;
    }
    #mdl-detalle-pedido-factura table.tabla-articulos {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      margin: 0;
    }
    #mdl-detalle-pedido-factura table.tabla-articulos thead th {
      font-size: .68rem;
      font-weight: 700;
      letter-spacing: .05em;
      text-transform: uppercase;
      color: #8a93a3;
      border-bottom: 2px solid #eef0f3;
      padding: .6rem .5rem;
      background: #fff;
      border-top: none;
    }
    #mdl-detalle-pedido-factura table.tabla-articulos tbody td {
      padding: .75rem .5rem;
      font-size: .88rem;
      color: #1f2937;
      border-bottom: 1px solid #f0f1f3;
      vertical-align: middle;
    }
    #mdl-detalle-pedido-factura table.tabla-articulos .codigo {
      font-family: "SFMono-Regular", Consolas, monospace;
      font-size: .82rem;
      color: #6b7280;
    }
    #mdl-detalle-pedido-factura table.tabla-articulos .desc strong { display: block; font-weight: 600; }
    #mdl-detalle-pedido-factura table.tabla-articulos .desc span {
      font-size: .78rem;
      color: #8a93a3;
    }
    #mdl-detalle-pedido-factura .total-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #1e3a6e;
      color: #fff;
      padding: .85rem 1.5rem;
      border-radius: 10px;
      margin-top: .25rem;
    }
    #mdl-detalle-pedido-factura .total-bar .label {
      font-size: .75rem;
      letter-spacing: .06em;
      text-transform: uppercase;
      opacity: .85;
    }
    #mdl-detalle-pedido-factura .total-bar .valor {
      font-size: 1.35rem;
      font-weight: 700;
    }
    #mdl-detalle-pedido-factura .modal-footer-custom {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: .85rem 1.5rem;
      background: #f7f8fa;
      border-top: 1px solid #e5e7eb;
      font-size: .82rem;
      color: #6b7280;
    }
    #mdl-detalle-pedido-factura .modal-footer-custom .pagina-actual {
      background: #1e3a6e;
      color: #fff;
      width: 26px;
      height: 26px;
      border-radius: 6px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      margin: 0 .35rem;
    }
    #mdl-detalle-pedido-factura table.tabla-articulos tfoot { display: none; }
    #mdl-detalle-pedido-factura table.tabla-articulos thead { display: table-header-group; }
    @media (max-width: 576px) {
      #mdl-detalle-pedido-factura table.tabla-articulos thead { display: none; }
      #mdl-detalle-pedido-factura table.tabla-articulos,
      #mdl-detalle-pedido-factura table.tabla-articulos tbody,
      #mdl-detalle-pedido-factura table.tabla-articulos tr,
      #mdl-detalle-pedido-factura table.tabla-articulos td {
        display: block;
        width: 100%;
      }
      #mdl-detalle-pedido-factura table.tabla-articulos tr {
        border: 1px solid #eef0f3;
        border-radius: 10px;
        margin-bottom: .6rem;
        padding: .5rem .75rem;
      }
      #mdl-detalle-pedido-factura table.tabla-articulos td {
        border: none;
        padding: .3rem 0;
        display: flex;
        justify-content: space-between;
        gap: 1rem;
      }
      #mdl-detalle-pedido-factura table.tabla-articulos td::before {
        content: attr(data-label);
        font-size: .68rem;
        font-weight: 700;
        letter-spacing: .05em;
        text-transform: uppercase;
        color: #8a93a3;
        flex-shrink: 0;
      }
      #mdl-detalle-pedido-factura table.tabla-articulos td.desc {
        flex-direction: column;
        align-items: flex-start;
      }
    }
</style>
