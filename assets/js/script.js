function includeHTML(id, file) {
    fetch(file)
        .then(response => response.text())
        .then(data => {
            document.getElementById(id).innerHTML = data;
        })
        .catch(err => console.error('Erro ao carregar:', file));
}

includeHTML('nav', './layout/nav.html');
