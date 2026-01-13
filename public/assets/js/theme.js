const btn = document.getElementById('theme-toggle');
const icon = document.getElementById('theme-icon');

function setTheme(theme) {
  if (theme === 'dark') {
    document.documentElement.classList.add('dark');
    icon.textContent = '🌞'; // clique para clarear
  } else {
    document.documentElement.classList.remove('dark');
    icon.textContent = '🌙'; // clique para escurecer
  }
  localStorage.setItem('theme', theme);
}

// Alternar tema ao clicar no botão
btn.addEventListener('click', () => {
  const isDark = document.documentElement.classList.contains('dark');
  setTheme(isDark ? 'light' : 'dark');
});

// Aplicar tema salvo ao carregar a página
window.addEventListener('load', () => {
  const savedTheme = localStorage.getItem('theme');
  if (savedTheme) {
    setTheme(savedTheme);
  } else {
    setTheme('light'); // padrão
  }
});
