const toggleButton = document.getElementById('theme-toggle');

// Carrega tema salvo
if(localStorage.getItem('theme')){
    document.body.setAttribute('data-theme', localStorage.getItem('theme'));
}

toggleButton.addEventListener('click', () => {
    const current = document.body.getAttribute('data-theme');
    const next = current === 'dark' ? 'light' : 'dark';
    document.body.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
});
