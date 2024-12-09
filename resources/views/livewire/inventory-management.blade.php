<div>
    <div class="bg-white border-b border-gray-100">
        <x-card class="px-4 mx-auto shadow-none max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ __('Manajemen Inventaris') }}
                </h2>
                <div class="flex-none">
                    <x-button wire:click="openCreateModal" label="Tambah Inventaris" right-icon="pencil" hover="primary" />
                    <x-button wire:click="exportToPDF" label="Export to PDF" />
                </div>
            </div>
        </x-card>
    </div>
    <!-- Navigasi Ruangan -->
    <div class="py-4 bg-white border-b border-gray-200">
        <div class="px-4 mx-auto shadow-none max-w-7xl sm:px-6 lg:px-8">
            <!-- Wadah ini harus memiliki class overflow-x-auto untuk memungkinkan scroll horizontal -->
            <div class="flex items-center justify-start pb-4 space-x-4 overflow-x-auto">
                <!-- Tombol "Semua Ruangan" -->
                <x-button rounded="sm" warning wire:click="filterByRoom(null)" label="Semua Ruangan"
                    class="{{ is_null($roomFilter) ? 'bg-blue-500 text-white' : 'bg-gray-200' }}" />

                <!-- Navigasi ruangan -->
                @foreach ($rooms as $room)
                    <x-button rounded="sm" positive wire:click="filterByRoom({{ $room->id }})"
                        label="{{ $room->name }}"
                        class="{{ $room->id == $roomFilter ? 'bg-blue-500 text-white' : 'bg-gray-200' }}" />
                @endforeach
            </div>
        </div>
    </div>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Pesan Sukses -->
            @if (session()->has('message'))
                <div class="mb-4 text-green-500">
                    {{ session('message') }}
                </div>
            @endif

            <x-errors />
            <br>

            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <!-- Pencarian -->
                <div class="flex-grow">
                    <x-input icon="magnifying-glass" label="Kolom Pencarian" wire:model.live.300ms="search"
                        placeholder="Cari Inventaris..." class="w-full" />
                </div>
            </div>

            <!-- Daftar Inventaris -->
            <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="mb-4 text-xl font-semibold">Daftar Inventaris</h3>

                    @if ($inventories->isEmpty())
                        <p class="text-center text-gray-500">Tidak ada inventaris ditemukan.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table
                                class="min-w-full text-left bg-white border border-gray-200 divide-y divide-gray-200 rounded-lg">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-sm font-medium text-gray-700 uppercase">No</th>
                                        <th class="px-6 py-3 text-sm font-medium text-gray-700 uppercase">Ruangan</th>
                                        <th class="px-6 py-3 text-sm font-medium text-gray-700 uppercase">Nama
                                            Inventaris</th>
                                        <th class="px-6 py-3 text-sm font-medium text-gray-700 uppercase">Tipe</th>
                                        <th class="px-6 py-3 text-sm font-medium text-gray-700 uppercase">Kepemilikan
                                        </th>
                                        <th class="px-6 py-3 text-sm font-medium text-gray-700 uppercase">Spesifikasi
                                        </th>
                                        <th class="px-6 py-3 text-sm font-medium text-gray-700 uppercase">Tahun
                                            Pengadaan</th>
                                        <th class="px-6 py-3 text-sm font-medium text-gray-700 uppercase">Jumlah</th>
                                        <th class="px-6 py-3 text-sm font-medium text-gray-700 uppercase">Layak</th>
                                        <th class="px-6 py-3 text-sm font-medium text-gray-700 uppercase">Tidak Layak
                                        </th>
                                        <th class="px-6 py-3 text-sm font-medium text-gray-700 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($inventories as $index => $inventory)
                                        <tr class="hover:bg-gray-100">
                                            <td class="px-6 py-4 text-sm text-gray-800">
                                                {{ $inventories->firstItem() + $index }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-800">
                                                <x-badge icon-size="sm" lg icon="folder-open" primary
                                                    label="{{ $inventory->room->name ?? 'N/A' }}" />
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-800">
                                                {{ $inventory->name }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-800">
                                                {{ $inventory->type }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-800">
                                                {{ $inventory->ownership }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-800">
                                                {{ $inventory->specification }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-800">
                                                {{ $inventory->acquisition_year }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-800">
                                                {{ $inventory->quantity }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-800">
                                                <x-badge flat positive label="{{ $inventory->layak_count }}" />
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-800">
                                                <x-badge flat negative label="{{ $inventory->tidak_layak_count }}" />
                                            </td>
                                            <td class="px-6 py-4 text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <x-button wire:click="editInventory({{ $inventory->id }})"
                                                        label="Edit" rounded icon="pencil-square" flat primary
                                                        interaction="solid" />
                                                    <x-button wire:click="confirmDelete({{ $inventory->id }})"
                                                        label="Hapus" rounded icon="trash" flat gray
                                                        interaction="negative" />
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $inventories->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form dengan WireUI -->
    <x-modal wire:model.defer="showModal" align="center" blur="sm" persistent="true">
        <x-slot name="title">
            {{ $isEditing ? 'Edit Inventaris' : 'Tambah Inventaris' }}
        </x-slot>

        <form wire:submit.prevent="saveInventory">
            <div class="mb-5">
                <x-input-label for="name" value="Nama Inventaris" />
                <x-input wire:model="name" id="name" placeholder="Masukkan nama inventaris" required />

            </div>

            <div class="mb-5">
                <x-select label="Jenis Inventaris" placeholder="Pilih Jenis Inventaris" wire:model="type"
                    :options="['Elektronik', 'Non-Elektronik']" />

            </div>

            <div class="mb-5">
                <x-input-label for="ownership" value="Kepemilikan" />
                <x-input wire:model="ownership" id="ownership" placeholder="Masukkan jenis kepemilikan" required />

            </div>

            <div class="mb-5">
                <x-input-label for="specification" value="Spesifikasi" />
                <x-input wire:model="specification" id="specification" placeholder="Masukkan spesifikasi" />
            </div>

            <div class="mb-5">
                <x-input-label for="acquisition_year" value="Tahun Pengadaan" />
                <x-input wire:model="acquisition_year" id="acquisition_year" type="number"
                    placeholder="Tahun Pengadaan" required />

            </div>

            <div class="mb-5">
                <x-input-label for="quantity" value="Jumlah" />
                <x-input wire:model="quantity" id="quantity" type="number" min="1" required />
            </div>

            <div class="mb-5">
                <x-input-label for="room_id" value="Ruangan" />
                <x-select wire:model="room_id" id="room_id" required :options="$rooms
                    ->pluck('name', 'id')
                    ->map(function ($name, $id) {
                        return ['name' => $name, 'id' => $id];
                    })
                    ->toArray()" placeholder="Pilih Ruangan"
                    option-label="name" option-value="id" />
            </div>

            <div class="mb-5">
                <x-input-label for="layak_count" value="Jumlah Layak" />
                <x-input wire:model="layak_count" id="layak_count" type="number" min="0" required />

            </div>

            <div class="mb-5">
                <x-input-label for="tidak_layak_count" value="Jumlah Tidak Layak" />
                <x-input wire:model="tidak_layak_count" id="tidak_layak_count" type="number" min="0"
                    required />

            </div>

            <div class="flex justify-end">

                <x-button type="submit" right-icon="arrow-right" primary>{{ $isEditing ? 'Update' : 'Tambah' }}
                    Inventaris</x-button>
                <x-button wire:click="$set('showModal', false)" secondary class="ml-2">Batal</x-button>
            </div>
        </form>
    </x-modal>

    <!-- Modal Konfirmasi Hapus -->
    <x-modal wire:model.defer="showDeleteModal" class="fixed inset-0 flex items-center justify-center" align="center"
        blur="sm" persistent="true">
        <x-slot name="title">Konfirmasi Hapus</x-slot>

        <div class="p-6">
            <p>Apakah Anda yakin ingin menghapus data ini?</p>
        </div>

        <div class="flex justify-end p-4">
            <x-button wire:click="deleteInventory" primary>Ya, Hapus</x-button>
            <x-button wire:click="$set('showDeleteModal', false)" secondary class="ml-2">Batal</x-button>
        </div>
    </x-modal>

</div>
