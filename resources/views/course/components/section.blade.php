@php
    $courseItem = [
        [
            'name' => 'Vektor Eigen',
            'description' => 'Mahasiswa diminta untuk menonton video yang akan saya berikan',
        ],
        [
            'name' => 'Transformasi Linear',
            'description' => 'Mahasiswa diminta untuk membaca materi tentang transformasi linear',
        ],
        [
            'name' => 'Matriks dan Determinan',
            'description' => 'Mahasiswa diminta untuk mengerjakan soal-soal matriks dan determinan',
        ],
        [
            'name' => 'Ruang Vektor',
            'description' => 'Mahasiswa diminta untuk memahami konsep ruang vektor',
        ],
        [
            'name' => 'Basis dan Dimensi',
            'description' => 'Mahasiswa diminta untuk mempelajari basis dan dimensi',
        ],
        [
            'name' => 'Nilai Eigen dan Vektor Eigen',
            'description' => 'Mahasiswa diminta untuk menonton video tentang nilai eigen dan vektor eigen',
        ],
    ];
@endphp

@props(['section'])

<div class="flex flex-col gap-2">
    <x-bladewind::accordion>
        <x-bladewind::accordion.item>
            <x-slot:title>
                <div class="flex flex-col gap-4">
                    <h1 class="text-3xl font-bold">{{ $section->name }}</h1>
                    <p class="font-medium">{{ $section->description }}</p>
                </div>
            </x-slot:title>
            <div class="flex flex-col">
                @foreach ($courseItem as $item)
                    <div class="bg-white shadow-sm p-5 w-full border-[1px] rounded-xl flex items-center gap-4 mt-5">
                        <div class="w-10 h-10 bg-gray-300 rounded-full"></div>
                        <div>
                            <p class="font-semibold">{{ $item['name'] }}</p>
                            <p>{{ $item['description'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-bladewind::accordion.item>
    </x-bladewind::accordion>
</div>
