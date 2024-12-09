<div>
    <div class="bg-white border-b border-gray-100">
        <x-card class="px-4 mx-auto shadow-none max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ __('Manajemen Ruangan') }}
                </h2>
                <div class="flex-none">
                    <x-button wire:click="openCreateModal" label="Tambah Ruangan" right-icon="pencil" hover="primary" />
                </div>
            </div>
        </x-card>
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
                        placeholder="Cari ruangan..." class="w-full" />
                </div>
            </div>

            <!-- Daftar Ruangan -->
            <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="mb-4 text-xl font-semibold">Daftar Ruangan</h3>

                    @if ($rooms->isEmpty())
                        <p class="text-center text-gray-500">Tidak ada ruangan ditemukan.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table
                                class="min-w-full text-left bg-white border border-gray-200 divide-y divide-gray-200 rounded-lg">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-sm font-medium text-gray-700 uppercase">No</th>
                                        <th class="px-6 py-3 text-sm font-medium text-gray-700 uppercase">
                                            <button wire:click="sortBy('name')" class="flex items-center">
                                                Nama Ruangan
                                                @if ($orderField === 'name')
                                                    @if ($orderDirection === 'asc')
                                                        <x-icon name="chevron-up" class="w-4 h-4 ml-2" />
                                                    @else
                                                        <x-icon name="chevron-down" class="w-4 h-4 ml-2" />
                                                    @endif
                                                @endif
                                            </button>
                                        </th>
                                        <th class="px-6 py-3 text-sm font-medium text-gray-700 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($rooms as $index => $room)
                                        <tr class="hover:bg-gray-100">
                                            <td class="px-6 py-4 text-sm text-gray-800">
                                                {{ $rooms->firstItem() + $index }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-800">
                                                 <x-badge icon-size="sm" lg icon="folder-open" primary label="{{ $room->name }}" />
                                            </td>
                                            <td class="px-6 py-4 text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <x-button wire:click="editRoom({{ $room->id }})"  label="Edit"
                                                        rounded icon="pencil-square" flat primary interaction="solid" />

                                                    <!-- Tombol Hapus yang Menampilkan Modal Konfirmasi -->
                                                    <x-button wire:click="confirmDelete({{ $room->id }})"
                                                        label="Hapus" rounded icon="trash" flat gray
                                                        interaction="negative" />
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $rooms->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Form dengan WireUI -->
    <x-modal wire:model.defer="showModal" class="fixed inset-0 flex items-center justify-center" align="center"
        blur="sm">
        <x-slot name="title">
            {{ $isEditing ? 'Edit Ruangan' : 'Tambah Ruangan' }}
        </x-slot>

        <form wire:submit.prevent="saveRoom">
            <div class="mb-5">
                <x-input-label for="name" value="Nama Ruangan" />
                <x-input wire:model="name" id="name" placeholder="Masukkan nama ruangan" required />
            </div>

            <div class="flex justify-end">
                <x-button type="submit" right-icon="arrow-right" primary>{{ $isEditing ? 'Update' : 'Tambah' }} Ruangan</x-button>
                <x-button wire:click="$set('showModal', false)" secondary class="ml-2">Batal</x-button>
            </div>
        </form>
    </x-modal>

    <!-- Modal Konfirmasi Hapus -->
    <x-modal wire:model.defer="showDeleteModal" class="fixed inset-0 flex items-center justify-center" align="center"
        blur="sm">
        <x-slot name="title">
            {{ __('Konfirmasi Hapus') }}
        </x-slot>

        <div class="p-4">
            <p>Apakah Anda yakin ingin menghapus ruangan ini?</p>
        </div>

        <div class="flex justify-end p-4">
            <x-button wire:click="deleteRoomConfirmed" primary>Hapus</x-button>
            <x-button wire:click="$set('showDeleteModal', false)" secondary class="ml-2">Batal</x-button>
        </div>
    </x-modal>
</div>
