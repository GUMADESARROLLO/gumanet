<div class="qr-wrapper" id="qr-card" data-cliente="{{ $cliente }}" data-qr-src="{{ $qrUrl }}">
  <div class="qr-header-bg">
    <div class="avatar-floating">
      <div class="avatar-inside">
        <i class="fas fa-user"></i>
      </div>
    </div>
  </div>

  <div class="qr-body">
    <div class="qr-title">Hola, {{ $nombre ?? 'Cliente' }}</div>
    <p class="qr-subtitle">
      Muestra o comparte este <strong style="color:#1d2731;">Código QR</strong> con de consulta de Acciones — uso interno.
    </p>

    <div class="qr-main-box">
      <div id="qr-container"></div>
    </div>

    <div class="d-flex justify-content-center mb-2" id="area-botones-descarga">
      <button class="action-btn-custom" id="btn-descargar-qr" onclick="descargarQR()">
        <div class="icon-circle">
          <i class="fas fa-download"></i>
        </div>
        <span>Descargar</span>
      </button>
    </div>

    <div class="client-footer-info">
      <div class="text-start">
        <div class="footer-label">Número de cuenta / Cliente</div>
        <div class="footer-value" id="cuenta-numero">{{ $cliente }} - {{ $nombreCompleto ?? $nombre ?? '' }}</div>
      </div>
      <button class="copy-btn-custom" id="copy-btn" onclick="copiarCuentaQR()" title="Copiar">
        <i class="fas fa-copy" id="copy-icon"></i>
      </button>
    </div>

    <div class="qr-terminos" id="qr-terminos">
      <strong>Términos:</strong> Promoción válida del 08 de junio al 21 de noviembre de 2026. Por cada C$1,500.00 netos en compras de productos participantes, recibe una (1) acción electrónica. Aplica únicamente para clientes del canal farmacia privada con código activo en UNIMARK S.A. Las acciones anuladas por devoluciones, notas de crédito o falta de pago no participan. El sorteo se realizará con base en los resultados de la Lotería Nacional del 24 de noviembre de 2026. El ganador deberá estar solvente con UNIMARK S.A. Aplican restricciones. Consulte el reglamento completo en UNIMARK S.A.
    </div>
  </div>
</div>

<style>
.qr-wrapper {
  background: #ffffff;
  border-radius: 32px;
  width: 360px;
  margin: 0 auto;
  overflow: hidden;
  box-shadow: 0 10px 25px rgba(11, 60, 93, 0.08); /* Sombra sutil con tono azul de base */
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  position: relative;
}

/* PALETA ESTILO UNIMARK: Azul profundo a azul medio institucional */
.qr-header-bg {
  background: linear-gradient(135deg, #0b3c5d 0%, #328cc1 100%);
  height: 110px;
  position: relative;
  width: 100%;
}

.avatar-floating {
  position: absolute;
  bottom: -35px;
  left: 50%;
  transform: translateX(-50%);
  width: 74px;
  height: 74px;
  background: #ffffff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 10px rgba(0,0,0,0.06);
}

.avatar-inside {
  width: 60px;
  height: 60px;
  background: #f0f7f9;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.avatar-inside i {
  font-size: 26px;
  color: #0b3c5d;
}

.qr-body {
  padding: 50px 24px 24px;
  text-align: center;
}

.qr-title {
  font-size: 1.4rem;
  font-weight: 700;
  color: #1d2731;
  margin-bottom: 6px;
}

.qr-subtitle {
  font-size: 0.88rem;
  color: #5c6b73;
  line-height: 1.5;
  margin-bottom: 24px;
  padding: 0 10px;
}

.qr-main-box {
  background: #ffffff;
  padding: 10px;
  margin-bottom: 24px;
  display: inline-block;
  width: 100%;
}

#qr-container {
  width: 100%;
  max-width: 220px;
  height: auto;
  margin: 0 auto;
}

/* Clases renombradas para eliminar rastro visual de marcas previas */
.action-btn-custom {
  background: none;
  border: none;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  color: #5c6b73;
  font-size: 0.82rem;
  font-weight: 500;
  transition: all 0.2s;
}

.action-btn-custom .icon-circle {
  width: 48px;
  height: 48px;
  background: #f0f7f9;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid #d9e8ed;
  color: #328cc1;
  font-size: 1.2rem;
  transition: all 0.2s;
}

.action-btn-custom:hover .icon-circle {
  background: #0b3c5d;
  color: #ffffff;
  border-color: #0b3c5d;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(11, 60, 93, 0.25);
}

.client-footer-info {
  background: #f9fbfc;
  border-radius: 16px;
  padding: 14px 16px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-top: 20px;
  border: 1px solid #eef2f5;
}

.footer-label {
  font-size: 0.72rem;
  color: #94a3b8;
  margin-bottom: 2px;
}

.footer-value {
  font-size: 0.8rem;
  font-weight: 700;
  color: #1d2731;
  word-break: break-all;
  padding-right: 10px;
}

.copy-btn-custom {
  background: #ffffff;
  border: 1px solid #cbd5e1;
  border-radius: 50%;
  width: 36px;
  height: 36px;
  min-width: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #5c6b73;
  transition: all 0.15s;
}

.copy-btn-custom:hover, .copy-btn-custom.copied {
  border-color: #328cc1;
  color: #328cc1;
  background: #f0f7f9;
}

.qr-terminos {
  margin-top: 16px;
  padding: 10px 12px;
  border-top: 1px solid #eef2f5;
  font-size: 0.72rem;
  color: #5c6b73;
  line-height: 1.5;
  text-align: left;
}

.qr-terminos strong {
  color: #1d2731;
}
</style>

<script>
  (function() {
    var base64Str = "{{ $qrUrl }}";
    if (base64Str.includes(',')) { base64Str = base64Str.split(',')[1]; }
    var svgRaw = atob(base64Str);

    function renderizarQR() {
      var container = document.getElementById('qr-container');
      if (container) {
        container.innerHTML = svgRaw;
        var svgElement = container.querySelector('svg');
        if (svgElement) {
          svgElement.style.width = '100%';
          svgElement.style.height = 'auto';
          svgElement.style.display = 'block';
        }
        return true;
      }
      return false;
    }

    if (!renderizarQR()) {
      var checkInterval = setInterval(function() {
        if (renderizarQR()) { clearInterval(checkInterval); }
      }, 30);
      setTimeout(function() { clearInterval(checkInterval); }, 2000);
    }
  })();

  window.descargarQR = function() {
    var qrCard = document.getElementById('qr-card');
    if (!qrCard) return;

    function loadDomToImage(callback) {
        if (typeof domtoimage !== 'undefined') { callback(); return; }
        var s = document.createElement('script');
        s.src = 'https://cdnjs.cloudflare.com/ajax/libs/dom-to-image/2.6.0/dom-to-image.min.js';
        s.onload = callback;
        document.head.appendChild(s);
    }

    loadDomToImage(function() {
        var areaBotones = document.getElementById('area-botones-descarga');
        if (areaBotones) { areaBotones.style.display = 'none'; }

        domtoimage.toPng(qrCard, {
            bgcolor: '#ffffff',
            width: qrCard.offsetWidth,
            height: qrCard.offsetHeight,
            filter: function(node) {
                return (node.id !== 'area-botones-descarga' && node.id !== 'btn-descargar-qr' && node.id !== 'qr-terminos');
            },
            style: {
                transform: 'none',
                margin: '0',
                left: '0',
                top: '0'
            }
        })
        .then(function(dataUrl) {
            if (areaBotones) { areaBotones.style.display = 'flex'; }
            var cliente = qrCard.getAttribute('data-cliente') || 'cliente';
            var link = document.createElement('a');
            link.download = 'codigo-qr-' + cliente + '.png';
            link.href = dataUrl;
            link.click();
        })
        .catch(function(error) {
            console.error('Error al exportar:', error);
            if (areaBotones) { areaBotones.style.display = 'flex'; }
        });
    });
  };
</script>