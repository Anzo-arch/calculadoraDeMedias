const btn = document.getElementById('theme-toggle');
const icon = document.getElementById('theme-icon');

function updateIcon() {
  icon.textContent = document.documentElement.classList.contains('dark')
    ? '🌞'
    : '🌙';
}

btn.addEventListener('click', () => {
  if (document.documentElement.classList.contains('dark')) {
    document.documentElement.classList.remove('dark');
    localStorage.theme = 'light';
  } else {
    document.documentElement.classList.add('dark');
    localStorage.theme = 'dark';
  }
  updateIcon();
});

// Sincroniza ícone ao carregar
updateIcon();
