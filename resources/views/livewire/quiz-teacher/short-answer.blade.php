<div class="card bg-base-100 w-full shadow-xl">
    <div class="card-body flex-row items-start">
        <span class="px-4 py-2 mr-2 mt-1 bg-black/10 rounded">{{ $num }}</span>
        <div>
            {{-- Pertanyaan dan nomor --}}
            <div class="flex items-start">
                {{-- Pertanyaan dan nomor --}}
                <h2 class="card-title mb-6">
                    {{ $question->content }}
                </h2>
                <button wire:click="$parent.deleteQuestion('{{$question->id}}')" class="px-4 py-2 mr-2 mt-1 bg-black/10 rounded">Del</button>
            </div>
            {{--Aksi--}}
            <div class="card-actions mt-4 gap-4 flex-col">
                <input wire:model="answer" class="input w-full" />
                <button wire:click="updateAnswer" class="btn btn-primary self-end">Simpan</button>
            </div>
        </div>
    </div>
</div>
