<?php

namespace App\Livewire;

use App\Models\Room;
use App\Models\Inventory;
use Livewire\Component;
use Livewire\WithPagination;
use Dompdf\Dompdf;
use Dompdf\Options;

class InventoryManagement extends Component
{
    use WithPagination;

    public $room_id;
    public $name;
    public $type;
    public $ownership;
    public $specification;
    public $acquisition_year;
    public $quantity;
    public $layak_count = 0;
    public $tidak_layak_count = 0;
    public $showModal = false;
    public $search = '';
    public $roomFilter = null; // Menyimpan ruangan yang dipilih

    // Properti untuk modal konfirmasi penghapusan
    public $showDeleteModal = false; // Modal konfirmasi hapus
    public $inventoryIdToDelete = null; // ID untuk inventaris yang akan dihapus

    public $isEditing = false;
    public $inventory_id; // Digunakan untuk editing

    public function exportToPDF()
    {
        // Ambil data inventaris yang sudah difilter dan disiapkan
        $inventories = Inventory::when($this->roomFilter, function ($query) {
            return $query->where('room_id', $this->roomFilter);
        })
            ->where('name', 'like', '%' . $this->search . '%')
            ->get();

        // Buat tampilan HTML untuk PDF
        $html = view('pdf.inventory', compact('inventories'))->render();

        // Inisialisasi Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true); // Enable PHP dalam rendering HTML
        $dompdf = new Dompdf($options);

        // Muat HTML
        $dompdf->loadHtml($html);

        // Set ukuran kertas dan orientasi
        $dompdf->setPaper('A4', 'landscape');

        // Render PDF (jangan output langsung, kita ingin mengunduh)
        $dompdf->render();

        // Kirimkan PDF ke browser untuk diunduh
        return response()->stream(
            function () use ($dompdf) {
                echo $dompdf->output();
            },
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename=inventaris.pdf',
            ],
        );
    }

    public function render()
    {
        $rooms = Room::all(); // Ambil semua data ruangan

        // Ambil inventaris berdasarkan ruangan yang dipilih
        $inventories = Inventory::when($this->roomFilter, function ($query) {
            return $query->where('room_id', $this->roomFilter); // Filter berdasarkan ruangan
        })
            ->where('name', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.inventory-management', compact('inventories', 'rooms'))->layout('layouts.app');
    }

    // Fungsi untuk memilih ruangan
    public function filterByRoom($roomId)
    {
        $this->roomFilter = $roomId;
    }

    // Menampilkan modal untuk tambah inventaris
    public function openCreateModal()
    {
        $this->reset(['name', 'type', 'ownership', 'specification', 'acquisition_year', 'quantity', 'room_id']);
        $this->showModal = true;
        $this->isEditing = false;
    }

    public function saveInventory()
    {
        // Validasi inputan
        $this->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Elektronik,Non-Elektronik',
            'ownership' => 'required',
            'specification' => 'nullable|string',
            'acquisition_year' => 'required|integer|digits:4',
            'quantity' => 'required|integer|min:1',
            'room_id' => 'required|exists:rooms,id', // Validasi room_id ada di tabel rooms
            'layak_count' => 'nullable|integer|min:0',
            'tidak_layak_count' => 'nullable|integer|min:0',
        ]);

        // Validasi agar jumlah layak_count + tidak_layak_count sama dengan quantity
        if ($this->layak_count + $this->tidak_layak_count != $this->quantity) {
            $this->addError('quantity', 'Jumlah unit layak dan tidak layak harus sama dengan jumlah total inventaris.');
            return; // Stop eksekusi dan tampilkan pesan error
        }

        // Data untuk disimpan
        $inventoryData = [
            'name' => $this->name,
            'type' => $this->type,
            'ownership' => $this->ownership,
            'specification' => $this->specification,
            'acquisition_year' => $this->acquisition_year,
            'quantity' => $this->quantity,
            'layak_count' => $this->layak_count,
            'tidak_layak_count' => $this->tidak_layak_count,
            'room_id' => $this->room_id,
        ];

        // Simpan data inventaris
        if ($this->isEditing) {
            $inventory = Inventory::findOrFail($this->inventory_id);
            $inventory->update($inventoryData);
            session()->flash('message', 'Inventaris berhasil diperbarui.');
        } else {
            Inventory::create($inventoryData);
            session()->flash('message', 'Inventaris berhasil ditambahkan.');
        }

        $this->resetPage(); // Reset pagination setelah perubahan
        $this->showModal = false;
    }

    // Menangani edit inventaris
    public function editInventory($id)
    {
        $inventory = Inventory::findOrFail($id);
        $this->inventory_id = $inventory->id;
        $this->name = $inventory->name;
        $this->type = $inventory->type;
        $this->ownership = $inventory->ownership;
        $this->specification = $inventory->specification;
        $this->acquisition_year = $inventory->acquisition_year;
        $this->quantity = $inventory->quantity;
        $this->layak_count = $inventory->layak_count;
        $this->tidak_layak_count = $inventory->tidak_layak_count;
        $this->room_id = $inventory->room_id;
        $this->isEditing = true;
        $this->showModal = true;
    }

    // Menangani penghapusan inventaris, hanya jika konfirmasi diterima
    public function deleteInventory()
    {
        if ($this->inventoryIdToDelete) {
            $inventory = Inventory::findOrFail($this->inventoryIdToDelete);
            $inventory->delete();
            session()->flash('message', 'Inventaris berhasil dihapus.');
            $this->resetPage();
            $this->showDeleteModal = false; // Menutup modal konfirmasi
        }
    }

    // Menampilkan modal konfirmasi penghapusan
    public function confirmDelete($id)
    {
        $this->inventoryIdToDelete = $id;
        $this->showDeleteModal = true; // Tampilkan modal konfirmasi
    }

    // Menutup modal konfirmasi
    public function closeDeleteModal()
    {
        $this->showDeleteModal = false; // Menutup modal
    }
}
