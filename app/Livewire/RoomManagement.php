<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Room;

class RoomManagement extends Component
{
    use WithPagination;

    public $name, $room_id;
    public $isEditing = false;
    public $showModal = false;
    public $showDeleteModal = false; // Untuk modal konfirmasi hapus
    public $roomIdToDelete; // Menyimpan ID ruangan yang akan dihapus
    public $search = ''; // Properti untuk pencarian
    public $orderField = 'name';
    public $orderDirection = 'asc';

    // Fungsi untuk reset pagination saat pencarian berubah
    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination saat pencarian diperbarui
    }

    // Fungsi untuk mengurutkan berdasarkan kolom
    public function sortBy($field)
    {
        if ($this->orderField === $field) {
            $this->orderDirection = $this->orderDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->orderField = $field;
            $this->orderDirection = 'asc';
        }
    }

    // Fungsi untuk membuka modal create/edit
    public function openCreateModal()
    {
        $this->reset(['name', 'room_id', 'isEditing']); // Reset state modal
        $this->showModal = true; // Tampilkan modal
    }

    // Fungsi untuk menyimpan atau memperbarui ruangan
    public function saveRoom()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:rooms,name,' . $this->room_id,
        ]);

        if ($this->isEditing) {
            // Update ruangan yang sudah ada
            $room = Room::findOrFail($this->room_id);
            $room->update(['name' => $this->name]);
            session()->flash('message', 'Ruangan berhasil diperbarui.');
        } else {
            // Menambah ruangan baru
            Room::create(['name' => $this->name]);
            session()->flash('message', 'Ruangan berhasil ditambahkan.');
        }

        $this->showModal = false; // Tutup modal setelah menyimpan
        $this->resetPage(); // Reset pagination untuk menampilkan data baru
    }

    // Fungsi untuk membuka modal edit
    public function editRoom($id)
    {
        $room = Room::findOrFail($id);
        $this->room_id = $room->id;
        $this->name = $room->name;
        $this->isEditing = true;
        $this->showModal = true;
    }

    // Fungsi untuk membuka modal konfirmasi hapus
    public function confirmDelete($id)
    {
        $this->roomIdToDelete = $id; // Menyimpan ID ruangan yang akan dihapus
        $this->showDeleteModal = true; // Menampilkan modal konfirmasi hapus
    }

    // Fungsi untuk menghapus ruangan
    public function deleteRoomConfirmed()
    {
        $room = Room::findOrFail($this->roomIdToDelete);
        $room->delete(); // Menghapus ruangan
        session()->flash('message', 'Ruangan berhasil dihapus.');

        $this->showDeleteModal = false; // Menutup modal konfirmasi
        $this->roomIdToDelete = null; // Reset ID yang dihapus
        $this->resetPage(); // Reset pagination
    }

    // Render view
    public function render()
    {
        $rooms = Room::query()
            ->where('name', 'like', '%' . $this->search . '%') // Filter berdasarkan pencarian
            ->orderBy($this->orderField, $this->orderDirection)
            ->paginate(10); // Pastikan menggunakan paginate

        return view('livewire.room-management', compact('rooms'))->layout('layouts.app');
    }
}
