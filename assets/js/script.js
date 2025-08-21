
(function(){
  'use strict';
  document.addEventListener('DOMContentLoaded', function(){
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form){
      form.addEventListener('submit', function(event){
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  });
})();

function toggleSenha(idInput, btn) {
  var input = document.getElementById(idInput);
  if (!input) return;
  // alterna o type
  if (input.type === 'password') {
    input.type = 'text';
    btn.innerHTML = '<i class="bi bi-eye-slash"></i>';
  } else {
    input.type = 'password';
    btn.innerHTML = '<i class="bi bi-eye"></i>';
  }
}

//  mascara de telefone
(function(){
  document.addEventListener('DOMContentLoaded', function(){
    ['telefone','emergencia_telefone','telefoneInstrutor']
      .forEach(function(id){
        var el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('input', function(){
          var v = this.value.replace(/\D/g,'').slice(0,11)
            .replace(/^(\d{2})(\d)/,'($1)$2')
            .replace(/(\d{5})(\d)/,'$1-$2');
          this.value = v;
        });
      });
  });
})();

document.addEventListener('DOMContentLoaded', function(){
  var toastEl = document.getElementById('liveToast');
  if (toastEl) {
    new bootstrap.Toast(toastEl).show();
  }
});

//  Filtro Tabela Instrutores
document.addEventListener('DOMContentLoaded', function(){
  var fNome = document.getElementById('filtroNome');
  var fEsp  = document.getElementById('filtroEspecialidade');
  var fDisp = document.getElementById('filtroDisponibilidade');
  var tblI  = document.getElementById('instrutoresTable');
  if (fNome && fEsp && fDisp && tblI) {
    var corpo = tblI.tBodies[0];
    function filtrarI() {
      var n = fNome.value.toLowerCase();
      var e = fEsp.value;
      var d = fDisp.value;
      Array.prototype.forEach.call(corpo.rows, function(r){
        var nome = r.cells[2].textContent.toLowerCase();
        var esp  = r.cells[3].textContent.trim();
        var disp = r.cells[6].textContent.trim();
        var show = true;
        if (n && nome.indexOf(n)===-1) show = false;
        if (e && esp!==e) show = false;
        if (d && disp!==d) show = false;
        r.style.display = show ? '' : 'none';
      });
    }
    fNome.addEventListener('input',   filtrarI);
    fEsp.addEventListener('change',    filtrarI);
    fDisp.addEventListener('change',   filtrarI);
  }
});

// Filtro Tabela Membros
document.addEventListener('DOMContentLoaded', function(){
  var fMNome  = document.getElementById('filtroNome');
  var fStat   = document.getElementById('filtroStatus');
  var fPlano  = document.getElementById('filtroPlano');
  var fInst   = document.getElementById('filtroInstrutor');
  var tblM    = document.getElementById('membrosTable');
  if (fMNome && fStat && fPlano && fInst && tblM) {
    var corpo = tblM.tBodies[0];
    function filtrarM() {
      var n = fMNome.value.toLowerCase();
      var s = fStat.value;
      var p = fPlano.value;
      var i = fInst.value;
      Array.prototype.forEach.call(corpo.rows, function(r){
        var nome  = r.cells[2].textContent.toLowerCase();
        var stat  = r.cells[3].textContent.trim();
        var plano = r.cells[4].textContent.trim();
        var inst  = r.cells[8].textContent.trim();
        var show = true;
        if (n && nome.indexOf(n)===-1) show = false;
        if (s && stat!==s) show = false;
        if (p && plano!==p) show = false;
        if (i && inst!==i) show = false;
        r.style.display = show ? '' : 'none';
      });
    }
    fMNome.addEventListener('input',   filtrarM);
    fStat.addEventListener('change',   filtrarM);
    fPlano.addEventListener('change',  filtrarM);
    fInst.addEventListener('change',   filtrarM);
  }
});

document.addEventListener('DOMContentLoaded', () => {
  const fNome  = document.getElementById('filtroNome');
  const fEsp   = document.getElementById('filtroEspecialidade');
  const fDisp  = document.getElementById('filtroDisponibilidade');

  if (!fNome || !fEsp || !fDisp) return;

  function filtrar() {
    const nome  = fNome.value.trim().toLowerCase();
    const esp   = fEsp.value;
    const disp  = fDisp.value;

    // filtra linhas da tabela
    document
      .querySelectorAll('#instrutoresTable tbody tr')
      .forEach(row => {
        const cols    = row.querySelectorAll('td');
        const nomeVal = cols[2]?.textContent.toLowerCase()  || '';
        const espVal  = cols[3]?.textContent                 || '';
        const dispVal = cols[6]?.textContent                 || '';
        const ok = (!nome || nomeVal.includes(nome))
                && (!esp  || espVal === esp)
                && (!disp || dispVal === disp);
        row.style.display = ok ? '' : 'none';
      });

  }

  [fNome, fEsp, fDisp].forEach(el => {
    el.addEventListener('input',  filtrar);
    el.addEventListener('change', filtrar);
  });

  filtrar();
});

