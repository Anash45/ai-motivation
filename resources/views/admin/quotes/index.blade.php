@extends('layouts.dashboard')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/jquery.dataTables.min.css"
        integrity="sha512-1k7mWiTNoyx2XtmI96o+hdjP8nn0f3Z2N4oF/9ZZRgijyV4omsKOXEnqL1gKQNPy2MTSP9rIEWGcH/CInulptA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">Quotes</h1>

        <div class="card text-dark">
            <div class="card-header">
                <h4>All Quotes</h4>
            </div>
            <div class="card-body table-responsive">
                <table id="quotes-table" class="table table-striped">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Quote</th>
                            <th>Audio</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($quotes as $quote)
                            <tr>
                                <td>{{ $quote->user->name }}</td>
                                <td class="quote-cell">{{ $quote->quote }}</td>
                                <td>
                                    @if ($quote->audio_path)
                                        <a href="{{ asset('quote/' . $quote->uuid) }}" target="_blank" class="btn btn-sm btn-info">
                                            Listen
                                        </a>
                                    @else
                                        <span class="text-muted">No audio</span>
                                    @endif
                                </td>
                                <td>{{ $quote->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"
        integrity="sha512-BkpSL20WETFylMrcirBahHfSnY++H2O1W+UnEEO4yNIl+jI2+zowyoGJpbtk6bx97fBXf++WJHSSK2MV4ghPcg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function () {
    $('#quotes-table').DataTable({
        pageLength: 25,
        lengthMenu: [10, 25, 50, 100],
        order: [] // disables initial sorting
    });
});
    </script>
@endpush