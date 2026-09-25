<style>
:root {
  --brand:        #1B3A6B;
  --brand-mid:    #254D94;
  --accent:       #00A8E8;
  --success:      #1DB87A;
  --warning:      #F5A623;
  --danger:       #E03C3C;
  --neutral-50:   #F7F9FC;
  --neutral-100:  #EDF1F7;
  --neutral-300:  #C4CDD8;
  --neutral-600:  #5E718A;
  --neutral-900:  #14243A;
  --radius-lg:    14px;
  --radius-sm:    8px;
}

#ModalCliente .modal-content {
  border: none;
  border-radius: var(--radius-lg);
  overflow: hidden;
  box-shadow: 0 24px 60px rgba(0,0,0,.22);
}

#ModalCliente .modal-header-custom {
  background: linear-gradient(135deg, var(--brand) 0%, var(--brand-mid) 100%);
  padding: 20px 24px 16px;
  position: relative;
}

#ModalCliente .client-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: rgba(255,255,255,.12);
  border: 1px solid rgba(255,255,255,.2);
  border-radius: 20px;
  padding: 3px 10px 3px 7px;
  font-size: .72rem;
  font-weight: 600;
  letter-spacing: .04em;
  color: rgba(255,255,255,.85);
  margin-bottom: 8px;
}

#ModalCliente .client-badge .dot {
  width: 6px; height: 6px;
  background: var(--success);
  border-radius: 50%;
  display: inline-block;
}

#ModalCliente .client-badge .dot.inactive {
  background: var(--danger);
}

#ModalCliente .modal-header-custom h5 {
  font-size: 1.35rem;
  font-weight: 700;
  color: #fff;
  margin: 0 0 2px;
  letter-spacing: -.01em;
}

#ModalCliente .modal-header-custom .sub {
  font-size: .78rem;
  color: rgba(255,255,255,.6);
}

#ModalCliente .btn-close-custom {
  position: absolute;
  top: 16px; right: 16px;
  background: rgba(255,255,255,.12);
  border: none;
  border-radius: 50%;
  width: 32px; height: 32px;
  display: flex; align-items: center; justify-content: center;
  color: #fff;
  font-size: 1rem;
  cursor: pointer;
  transition: background .15s;
}
#ModalCliente .btn-close-custom:hover { background: rgba(255,255,255,.22); }

#ModalCliente .nav-tabs-custom {
  display: flex;
  gap: 2px;
  padding: 0 24px;
  background: var(--brand);
  border-bottom: 2px solid var(--brand-mid);
}

#ModalCliente .nav-tabs-custom .tab-link {
  color: rgba(255,255,255,.55);
  font-size: .8rem;
  font-weight: 600;
  letter-spacing: .04em;
  text-transform: uppercase;
  padding: 10px 14px;
  border: none;
  border-bottom: 2px solid transparent;
  margin-bottom: -2px;
  background: none;
  transition: color .15s, border-color .15s;
  cursor: pointer;
}

#ModalCliente .nav-tabs-custom .tab-link.active {
  color: #fff;
  border-bottom-color: var(--accent);
}

#ModalCliente .nav-tabs-custom .tab-link:hover:not(.active) {
  color: rgba(255,255,255,.8);
}

#ModalCliente .modal-body-custom {
  background: var(--neutral-50);
  padding: 24px;
}

#ModalCliente .info-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1px;
  background: var(--neutral-300);
  border-radius: var(--radius-sm);
  overflow: hidden;
  margin-bottom: 20px;
}

#ModalCliente .info-cell {
  background: #fff;
  padding: 14px 16px;
}

#ModalCliente .info-cell.full { grid-column: 1 / -1; }

#ModalCliente .info-cell label {
  display: block;
  font-size: .68rem;
  font-weight: 700;
  letter-spacing: .08em;
  text-transform: uppercase;
  color: var(--neutral-600);
  margin-bottom: 4px;
}

#ModalCliente .info-cell .value {
  font-size: .92rem;
  font-weight: 600;
  color: var(--neutral-900);
}

#ModalCliente .info-cell .value.mono {
  font-family: 'Courier New', monospace;
  font-size: .85rem;
  color: var(--brand-mid);
}

#ModalCliente .pill {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  font-size: .75rem;
  font-weight: 700;
  padding: 3px 10px;
  border-radius: 20px;
}
#ModalCliente .pill.active   { background: #e6f9f2; color: var(--success); }
#ModalCliente .pill.inactive { background: #fdecea; color: var(--danger); }
#ModalCliente .pill.warning  { background: #fff5e6; color: var(--warning); }

#ModalCliente .status-strip {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 12px;
  margin-bottom: 0;
}

#ModalCliente .status-card {
  background: #fff;
  border-radius: var(--radius-sm);
  padding: 14px 16px;
  border: 1px solid var(--neutral-100);
}

#ModalCliente .status-card .s-label {
  font-size: .68rem;
  font-weight: 700;
  letter-spacing: .07em;
  text-transform: uppercase;
  color: var(--neutral-600);
  margin-bottom: 6px;
  display: flex;
  align-items: center;
  gap: 5px;
}

#ModalCliente .status-card .s-value {
  font-size: 1.05rem;
  font-weight: 700;
  color: var(--neutral-900);
}

#ModalCliente .status-card .s-value.green { color: var(--success); }
#ModalCliente .status-card .s-value.blue  { color: var(--brand-mid); }

#ModalCliente .modal-footer-custom {
  background: #fff;
  border-top: 1px solid var(--neutral-100);
  padding: 14px 24px;
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}

#ModalCliente .btn-outline-brand {
  border: 1.5px solid var(--brand);
  color: var(--brand);
  background: none;
  border-radius: var(--radius-sm);
  font-size: .85rem;
  font-weight: 600;
  padding: 7px 18px;
  transition: background .15s;
  cursor: pointer;
}
#ModalCliente .btn-outline-brand:hover { background: var(--neutral-100); }

#ModalCliente .btn-brand {
  background: var(--brand);
  color: #fff;
  border: none;
  border-radius: var(--radius-sm);
  font-size: .85rem;
  font-weight: 600;
  padding: 7px 18px;
  transition: background .15s;
  cursor: pointer;
}
#ModalCliente .btn-brand:hover { background: var(--brand-mid); color: #fff; }

#dtClientes tr.moroso-row,
#dtClientes tr.moroso-row td {
    background-color: #fff0f0 !important;
}
</style>
