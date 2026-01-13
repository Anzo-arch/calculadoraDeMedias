const tabs = document.querySelectorAll('.tab');
const contents = document.querySelectorAll('.tab-content');

tabs.forEach(tab => {
    tab.addEventListener('click', () => {
        const target = tab.dataset.tab;

        tabs.forEach(t => {
            t.classList.remove('bg-blue-100', 'dark:bg-blue-900', 'font-bold', 'text-blue-700', 'dark:text-blue-300');
            t.classList.add('text-gray-600', 'dark:text-gray-300');
        });

        tab.classList.add('bg-blue-100', 'dark:bg-blue-900', 'font-bold', 'text-blue-700', 'dark:text-blue-300');

        contents.forEach(c => c.classList.add('hidden'));
        document.getElementById(target).classList.remove('hidden');
    });
});
