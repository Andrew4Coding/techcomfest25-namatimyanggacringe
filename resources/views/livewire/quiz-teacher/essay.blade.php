<div class="card bg-base-100 w-full shadow-xl">
    <div class="card-body flex flex-col md:flex-row gap-4 items-start w-full">
        <span class="min-w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">{{ $num }}</span>
        <div class="w-full">
            {{-- Pertanyaan dan nomor --}}
            <div class="flex items-center gap-2">
                <h2 class="card-title">
                    {{ $question->content }}
                </h2>
                <button wire:click="$parent.deleteQuestion('{{ $question->id }}')">
                    <x-lucide-trash class="w-4 h-4 hover:text-red-500 duration-300 hover:rotate-12" />
                </button>
            </div>

            {{-- Aksi --}}
            <div class="card-actions mt-4 gap-4 flex flex-col">
                <textarea wire:model="answer" class="textarea w-full"></textarea>
                <button wire:click="updateAnswer" class="btn btn-primary self-end">Simpan</button>
            </div>
        </div>
    </div>
</div>