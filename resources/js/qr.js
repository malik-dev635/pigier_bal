import qrcode from 'qrcode-generator';

// Génère un QR code (SVG, net à toute taille) dans l'élément donné.
window.makeQr = function (el, text) {
    if (!el || !text) return;
    const qr = qrcode(0, 'M');
    qr.addData(text);
    qr.make();
    el.innerHTML = qr.createSvgTag({ cellSize: 6, margin: 1, scalable: true });
};

// Rend tous les éléments [data-qr] présents sur la page.
function renderAllQr() {
    document.querySelectorAll('[data-qr]').forEach((el) => {
        if (el.dataset.qrDone) return;
        window.makeQr(el, el.dataset.qr);
        el.dataset.qrDone = '1';
    });
}

document.addEventListener('DOMContentLoaded', renderAllQr);
document.addEventListener('livewire:navigated', renderAllQr);
