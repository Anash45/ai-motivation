@extends('layouts.dashboard')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/jquery.dataTables.min.css"
        integrity="sha512-1k7mWiTNoyx2XtmI96o+hdjP8nn0f3Z2N4oF/9ZZRgijyV4omsKOXEnqL1gKQNPy2MTSP9rIEWGcH/CInulptA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('content')
    <div class="container py-4">
        <h1 class="mb-4">Users</h1>

        <div class="card text-dark">
            <div class="card-header">
                <h4>Registered Users</h4>
            </div>
            <div class="card-body table-responsive">
                @include('partials.messages')
                <table id="users-table" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subscription Status</th>
                            <th>Renew / End Date</th>
                            <th>Role</th>
                            <th>Quotes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->isOnTrial())
                                        <span class="badge bg-warning text-dark">Trial</span>
                                    @elseif($user->isSubscribed())
                                        <span class="badge bg-success">Subscribed</span>
                                    @elseif($user->isCancelled())
                                        <span class="badge bg-secondary">Cancelled</span>
                                    @elseif($user->hasExpiredSubscription())
                                        <span class="badge bg-danger">Expired</span>
                                    @else
                                        <span class="badge bg-dark">No Plan</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->isCancelled())
                                        {{ \Carbon\Carbon::parse($user->subscription_ends_at)->format('d M Y') }}
                                    @elseif($user->isSubscribed())
                                        {{ \Carbon\Carbon::parse($user->subscription_ends_at)->format('d M Y') }}
                                    @elseif($user->isOnTrial())
                                        {{ \Carbon\Carbon::parse($user->trial_ends_at)->format('d M Y') }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ ucfirst($user->role) }}</td>
                                <td>{{ $user->quotes_count }}</td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
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
            $('#users-table').DataTable();
        });
    </script>
@endpush