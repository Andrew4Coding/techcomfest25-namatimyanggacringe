import './bootstrap';

document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', () => {
        form.querySelectorAll('button[type="submit"]').forEach(button => {
            button.disabled = true;
            button.innerText = 'Memproses ...';
        });
    });
})


// Function to call toast with animation
export function toaster(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.classList.remove('hidden');
    toast.classList.add('bg-' + type);
    toast.innerText = message;
    toast.classList.add('fade-in');
    
    setTimeout(() => {
        toast.classList.remove('fade-in');
        toast.classList.add('fade-out');
    }, 3000);

    setTimeout(() => {
        toast.classList.add('hidden');
        toast.classList.remove('bg-' + type);
        toast.classList.remove('fade-out');
    }, 3500);
}