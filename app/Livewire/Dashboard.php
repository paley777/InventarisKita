<?php

namespace App\Livewire;

use App\Models\Inventory;
use App\Models\Room;
use Livewire\Component;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $totalInventaris;
    public $totalLayak;
    public $totalTidakLayak;
    public $recentActivities;

    public function mount()
    {
        // Ambil total inventaris
        $this->totalInventaris = Inventory::count();

        // Ambil total inventaris layak
        $this->totalLayak = Inventory::where('layak_count', '>', 0)->sum('layak_count');

        // Ambil total inventaris tidak layak
        $this->totalTidakLayak = Inventory::where('tidak_layak_count', '>', 0)->sum('tidak_layak_count');

        // Ambil aktivitas terbaru (5 aktivitas terakhir) dari Inventory dan Room
        $this->recentActivities = $this->getRecentActivities();
    }

    public function getRecentActivities()
    {
        // Ambil 5 aktivitas terakhir dari Inventory
        $inventoryActivities = Inventory::latest()
            ->take(5)
            ->get()
            ->map(function($inventory) {
                return [
                    'activity' => "Inventaris '{$inventory->name}' diperbarui",
                    'date' => $inventory->updated_at,
                    'type' => 'inventory'
                ];
            });

        // Ambil 5 aktivitas terakhir dari Room
        $roomActivities = Room::latest()
            ->take(5)
            ->get()
            ->map(function($room) {
                return [
                    'activity' => "Ruangan '{$room->name}' diperbarui",
                    'date' => $room->updated_at,
                    'type' => 'room'
                ];
            });

        // Gabungkan kedua koleksi dan urutkan berdasarkan tanggal
        $activities = $inventoryActivities->merge($roomActivities)
            ->sortByDesc('date')  // Urutkan berdasarkan tanggal terbaru
            ->take(5); // Ambil 5 aktivitas terbaru

        return $activities;
    }

    public function render()
    {
        return view('livewire.dashboard')->layout('layouts.app');
    }
}
