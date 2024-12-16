import './bootstrap';


// Make every button inside a form is disabled and has text Submitting ... when submitting the form
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', () => {
        form.querySelectorAll('button[type="submit"]').forEach(button => {
            button.disabled = true;
            button.innerText = 'Memproses ...';
        });
    });
})
