@if ($errors->any())
    <div role="alert" class="alert alert-error z-50 fixed bottom-10 right-10 max-w-[250px] overflow-y-auto max-h-[200px] p-4 rounded-lg shadow-lg bg-red-100 text-red-800 flex flex-col items-start gap-2 animate__animated animate__bounceIn">
        <div class="flex items-center gap-4">
            <x-lucide-alert-triangle class="w-5 h-5 text-red-800" />
            <span class="font-semibold">Error</span>
        </div>
        <ul class="mt-2 list-none list-inside w-full text-xs">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    <script>
        // Make the alert disappear after 3 seconds with a springy fade-out animation
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            alert.classList.add('animate__animated', 'animate__bounceOut');
            setTimeout(() => {
                alert.classList.add('hidden');
            }, 1000);
        }, 4000);
    </script>
@endif