// Styles
import './styles/app.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import '@fortawesome/fontawesome-free/css/all.min.css';
import 'tingle.js/dist/tingle.min.css';

// JS
import 'bootstrap/dist/js/bootstrap.bundle.min';
import '@fortawesome/fontawesome-free/js/all.min';

const Tingle = require('tingle.js/dist/tingle.min');

window.confirmDelete = function(el) {
    let elData = el.dataset;
    const modal = new Tingle.modal({
        footer: true,
        closeMethods: ['overlay', 'escape'],
        cssClass: ['confirmModal'],
        onClose() {modal.destroy()},
    });

    modal.setContent(`<h1>${elData.delConfirmText}</h1>`);
    modal.addFooterBtn(elData.delCancelText, 'btn btn-secondary mx-1', () => {modal.close()});
    modal.addFooterBtn(elData.delButtonText, 'btn btn-danger', function () {
        window.location = elData.delPath.replace('0', elData.delId);
    });

    modal.open();
}

let confirmDeleteElements = document.getElementsByClassName('confirmDelete');
for (let i = 0; i < confirmDeleteElements.length; i++) {
    confirmDeleteElements[i].setAttribute('onclick', 'confirmDelete(this)')
}