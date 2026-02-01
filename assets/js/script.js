function includeHTML(id, file) {
    const path = window.location.pathname;
    
    let prefix = "";
    if (path.includes("/public/auth/") || path.includes("/public/dashboard/") || path.includes("/public/perfil/")) {
        prefix = "../../";
    } else if (path.includes("/public/")) {
        prefix = "../";
    }

    const caminhoFinal = prefix + file;

    fetch(caminhoFinal)
        .then(response => {
            if (!response.ok) throw new Error("Não encontrado: " + caminhoFinal);
            return response.text();
        })
        .then(data => {
            const element = document.getElementById(id);
            if (element) {
                element.innerHTML = data;
                marcarLinkAtivo(element);
            }
        })
        .catch(err => console.error('Erro ao carregar:', caminhoFinal, err));
}

function marcarLinkAtivo(container) {
    const paginaAtual = window.location.pathname.split("/").pop() || "index.html";
    container.querySelectorAll('a').forEach(link => {
        const href = link.getAttribute('href');
        if (href && href.includes(paginaAtual)) {
            link.classList.add('nav_link_active');
        }
    });
}

// Chamadas de componentes
includeHTML('nav', 'components/nav.html');
includeHTML('header_index', 'components/header.html');

// --- LOGICA DO MODAL  ---
const botaoNovoFuncionario = document.querySelector('.filtro_funcionarios');
const modal = document.getElementById('modal_funcionario');
const fecharModal = document.getElementById('fechar_modal');

// Esta verificação impede o erro "null (reading addEventListener)"
if (botaoNovoFuncionario && modal) {
    
    botaoNovoFuncionario.addEventListener('click', () => {
        modal.classList.add('active');
    });

    if (fecharModal) {
        fecharModal.addEventListener('click', () => {
            modal.classList.remove('active');
        });
    }

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('active');
        }
    });
}

// Lógica da página financeiros
    function toggleGlobalValores() {
        const valores = document.querySelectorAll('.valor_sensivel');
        const iconeGeral = document.getElementById('icone_olho_geral');
            
        valores.forEach(v => {
            if (v.classList.contains('valor_oculto')) {
                    v.innerText = v.getAttribute('data-valor');
                    v.classList.remove('valor_oculto');
                    iconeGeral.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                    v.innerText = "•••••";
                    v.classList.add('valor_oculto');
                    iconeGeral.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        }