/**
 * Arquivo de customização js.
 * 
 * Qualquer inicialização de função do materialize CSS deverá ser 
 * incorporada neste arquivo, bem como qualquer função JS personalizada.
 * 
 * @nando.correa
 */

/** collapsible element */
document.addEventListener('DOMContentLoaded', function() {
  var elems = document.querySelectorAll('.collapsible');
  var instances = M.Collapsible.init(elems);
});

/** dropdown do materialize css. é o menu usado na navbar, para listar categorias */
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.dropdown-trigger');
    var instances = M.Dropdown.init(elems, {
      alignment: 'right',
      coverTrigger: false,
      constrainWidth: false
    });
  });

/**
 * modal abstrato do materialize CSS
 * pode ser usado com qualquer formulário, 
 * desde que seja especificada a roda válida 
 * e a id do recurso a ser excluído
 */
/** Modal para form */
document.addEventListener('DOMContentLoaded', function() {
  var elems = document.querySelectorAll('.modal');
  var instances = M.Modal.init(elems);

  var deleteButtons = document.querySelectorAll('.modal-trigger');
  deleteButtons.forEach(function(button) {
      button.addEventListener('click', function() {
          var targetId = this.dataset.targetId;
          var targetUrl = this.dataset.targetUrl;
          var form = document.getElementById('delete-form');
          form.action = targetUrl + targetId;
      });
  });
});

/** Modal para link */
document.addEventListener('DOMContentLoaded', function() {
  var elems = document.querySelectorAll('.modal');
  var instances = M.Modal.init(elems);
  
  var confirmLinks = document.querySelectorAll('.confirm-link');
  confirmLinks.forEach(function(link) {
      link.addEventListener('click', function(e) {
          e.preventDefault();
          var modalInstance = M.Modal.getInstance(document.getElementById('confirmModal'));
          var deleteUrl = this.getAttribute('href');
          var confirmButton = document.getElementById('confirmDelete');
          confirmButton.href = deleteUrl; // define o atributo href do botão de confirmação com o link de exclusão
          modalInstance.open();
      });
  });
});

/** seletor de checkbox múltiplas */
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('select');
    var instances = M.FormSelect.init(elems);
});

/** Sidenav - painel de administração */
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.sidenav');
    var instances = M.Sidenav.init(elems);
    // var instance = M.Sidenav.getInstance(elems[0]);
    // instance.open();
  });

/** FAB do materialize, botão responsivo de navegação */
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.fixed-action-btn');
    var instances = M.FloatingActionButton.init(elems);
});

/** botao de imprimir */
function printPage() {
  window.print();
}

/** carrossel da pagina inicial */
document.addEventListener('DOMContentLoaded', function() {
  var elems = document.querySelectorAll('.carousel');
  var instances = M.Carousel.init(elems, {
    duration: 200,
    dist: -100,
    shift: 50,
    padding: 10,
    numVisible: 10,
    fullWidth: false,
    indicators: true,
    noWrap: false,
    onCycleTo: null
  });
});

/** tooltips */
document.addEventListener('DOMContentLoaded', function() {
  var elems = document.querySelectorAll('.tooltipped');
  var instances = M.Tooltip.init(elems, {
    enterDelay: 200,
    margin: 5,
    position: 'top',
    transitionMovement: 10
  });
});