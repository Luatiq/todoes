// Styles
import './styles/app.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import '@fortawesome/fontawesome-free/css/all.min.css';
import 'tingle.js/dist/tingle.min.css';
import 'toastify-js/src/toastify.css';

// JS
import 'bootstrap/dist/js/bootstrap.bundle.min';
import '@fortawesome/fontawesome-free/js/all.min';

const Tingle = require('tingle.js/dist/tingle.min');
window.Toastify = require('toastify-js/src/toastify');

window.confirmModal = function(el) {
    let elData = el.dataset;
    const modal = new Tingle.modal({
        footer: true,
        closeMethods: ['overlay', 'escape'],
        cssClass: ['confirmModal'],
        onClose() {modal.destroy()},
    });

    modal.setContent(`<h1>${elData.confText}</h1>`);
    modal.addFooterBtn(elData.confCancelText, 'btn btn-secondary mx-1', () => {modal.close()});
    modal.addFooterBtn(elData.confButtonText, `btn ${elData.confButtonClass}`, function () {
        window.location = elData.confPath;
    });

    modal.open();
}