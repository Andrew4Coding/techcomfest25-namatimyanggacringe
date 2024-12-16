import './bootstrap';

document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', () => {
        form.querySelectorAll('button[type="submit"]').forEach(button => {
            button.disabled = true;
            button.innerText = 'Memproses ...';
        });
    });
})