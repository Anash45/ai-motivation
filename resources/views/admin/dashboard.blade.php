{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.dashboard')

@section('content')
    <div class="container py-4 bg-dark text-white">
        <h1 class="mb-4">Admin Dashboard</h1>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5>Total Users</h5>
                        <h2>{{ $totalUsers }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-dark">
                    <div class="card-body">
                        <h5>Trial Users</h5>
                        <h2>{{ $trialUsers }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h5>Subscribed Users</h5>
                        <h2>{{ $subscribedUsers }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <form method="GET" class="mb-4">
            <label class="form-label">Select Week Start:</label>
            <input type="date" name="week_start" value="{{ $weekStart->format('Y-m-d') }}"
                class="form-control w-auto d-inline-block">
            <button class="btn btn-primary">Update</button>
        </form>

        <div class="card bg-white text-white">
            <div class="card-body">
                <h5 class="mb-3">Quotes This Week</h5>
                <canvas id="quotesChart"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('quotesChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json(collect($chartData)->pluck('day')),
                datasets: [{
                    label: 'Quotes',
                    data: @json(collect($chartData)->pluck('count')),
                    backgroundColor: '#0d6efd'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: { grid: { color: '#444' } },
                    y: { grid: { color: '#444' } }
                }
            }
        });
    </script>
@endpush