<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- Dashboard Overview -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

                <!-- Card 1: Total Inventaris -->
                <div class="p-6 bg-white rounded-lg shadow-lg">
                    <h3 class="text-lg font-medium text-gray-700">Total Inventaris</h3>
                    <div class="mt-4 text-2xl font-bold text-gray-900">
                        {{ $totalInventaris }}
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Jumlah total inventaris yang tercatat di sistem.</p>
                </div>

                <!-- Card 2: Inventaris Layak -->
                <div class="p-6 bg-white rounded-lg shadow-lg">
                    <h3 class="text-lg font-medium text-gray-700">Inventaris Layak</h3>
                    <div class="mt-4 text-2xl font-bold text-green-500">
                        {{ $totalLayak }}
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Inventaris yang dalam kondisi layak pakai.</p>
                </div>

                <!-- Card 3: Inventaris Tidak Layak -->
                <div class="p-6 bg-white rounded-lg shadow-lg">
                    <h3 class="text-lg font-medium text-gray-700">Inventaris Tidak Layak</h3>
                    <div class="mt-4 text-2xl font-bold text-red-500">
                        {{ $totalTidakLayak }}
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Inventaris yang perlu diperbaiki atau diganti.</p>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="p-6 mt-8 bg-white rounded-lg shadow-lg">
                <h3 class="text-lg font-medium text-gray-700">Aktivitas Terbaru</h3>
                <div class="mt-4">
                    <ul class="space-y-4">
                        @foreach ($recentActivities as $activity)
                            <li class="flex items-center">
                                <span
                                    class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($activity['date'])->diffForHumans() }}</span>
                                <span class="ml-2 text-sm text-gray-700">{{ $activity['activity'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
