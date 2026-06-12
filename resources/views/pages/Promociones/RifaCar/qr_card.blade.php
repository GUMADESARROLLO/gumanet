<div class="qr-wrapper" id="qr-card" style="background:#fff;border-radius:28px;width:340px;padding:32px 24px 24px;text-align:center;margin:0 auto" data-cliente="{{ $cliente }}" data-qr-src="{{ $qrUrl }}">
  <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#00c6a7,#1a9de0);display:flex;align-items:center;justify-content:center;margin:0 auto 14px;box-shadow:0 4px 14px rgba(0,198,167,0.35)">
    <i class="fas fa-user" style="font-size:28px;color:#fff"></i>
  </div>

  <div style="font-size:1.25rem;font-weight:700;color:#1a1a2e;margin-bottom:4px">Hola, {{ $nombre ?? 'Cliente' }}</div>
  <p style="font-size:0.82rem;color:#6b7280;margin-bottom:20px;line-height:1.4">
    C&oacute;digo de consulta de <strong style="color:#1a9de0">Acciones</strong> — uso interno, no compartir.
  </p>

  <div style="background:#f7fffe;border:2px solid #e0f7f3;border-radius:18px;padding:16px;margin-bottom:20px;display:inline-block;width:100%">
    <img id="qr-img" src="{{ $qrUrl }}" alt="Código QR" style="width:100%;max-width:220px;height:auto;display:block;margin:0 auto;border-radius:10px" />
  </div>

  <div class="d-flex justify-content-center mb-1">
    <button class="action-btn" id="btn-descargar-qr">
      <i class="fas fa-download" style="font-size:1.2rem;color:#00c6a7"></i>
      Descargar
    </button>
  </div>

  <div style="background:#f8fafc;border:1.5px solid #e5e7eb;border-radius:12px;padding:10px 14px;display:flex;align-items:center;justify-content:space-between;margin-top:16px">
    <div class="text-start">
      <div style="font-size:0.70rem;color:#9ca3af">Cliente</div>
      <div style="font-size:0.78rem;font-weight:700;color:#1a1a2e;letter-spacing:0.5px" id="cuenta-numero">{{ $cliente }} - {{ $nombreCompleto ?? $nombre ?? '' }}</div>
    </div>
    <button class="copy-btn" id="copy-btn" onclick="copiarCuenta()" title="Copiar">
      <i class="fas fa-copy" id="copy-icon"></i>
    </button>
  </div>
</div>

<style>
.action-btn {
  display:flex;flex-direction:column;align-items:center;gap:4px;
  background:#f0faf8;border:1.5px solid #c8ede8;border-radius:14px;
  padding:10px 18px;cursor:pointer;transition:all 0.18s;
  text-decoration:none;color:#1a9de0;font-size:0.78rem;font-weight:600;
}
.action-btn:hover { background:#d6f5ef;border-color:#00c6a7;transform:translateY(-2px);color:#0f7b6c; }
.copy-btn {
  background:none;border:1.5px solid #d1d5db;border-radius:8px;
  padding:5px 8px;cursor:pointer;color:#6b7280;transition:all 0.15s;
}
.copy-btn:hover { border-color:#00c6a7;color:#00c6a7; }
.copy-btn.copied { border-color:#00c6a7;color:#00c6a7; }
</style>
