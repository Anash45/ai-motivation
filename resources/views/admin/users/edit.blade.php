@extends('layouts.dashboard')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">Users</h1>

        <div class="card text-dark">
            <div class="card-header">
                <h4>Edit User</h4>
            </div>
            <div class="card-body table-responsive">
                @include('partials.messages')
                <form novalidate class="needs-validation" 
                    action="{{ route('admin.users.update', $user->id) }}" 
                    method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input name="name" type="text" value="{{ old('name', $user->name) }}"
                            class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input name="email" type="email" value="{{ old('email', $user->email) }}"
                            class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password <small class="text-muted">(Leave blank to keep
                                current)</small></label>
                        <input name="password" type="password" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input name="password_confirmation" type="password" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="voice_id">Voice <span class="text-danger">*</span></label>
                        <select name="voice_id" id="voice_id" class="input-lg input-circle form-control" required>
                            <option value="" selected disabled>Select a
                                Voice</option>
                            @foreach ($voices as $voice)
                                <option value="{{ $voice->id }}" {{ old('voice_id', $user->voice_id) == $voice->id ? 'selected' : '' }}>
                                    {{ ucfirst($voice->gender) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="age_range">Age Range</label>
                        <select name="age_range" id="age_range" class="input-lg input-circle form-control">
                            <option value="" disabled {{ old('age_range', $user->age_range) ? '' : 'selected' }}>Age Range
                            </option>
                            <option value="under_18" {{ old('age_range', $user->age_range) === 'under_18' ? 'selected' : '' }}>Under 18</option>
                            <option value="18_24" {{ old('age_range', $user->age_range) === '18_24' ? 'selected' : '' }}>18 -
                                24</option>
                            <option value="25_34" {{ old('age_range', $user->age_range) === '25_34' ? 'selected' : '' }}>25 -
                                34</option>
                            <option value="35_44" {{ old('age_range', $user->age_range) === '35_44' ? 'selected' : '' }}>35 -
                                44</option>
                            <option value="45_54" {{ old('age_range', $user->age_range) === '45_54' ? 'selected' : '' }}>45 -
                                54</option>
                            <option value="55_plus" {{ old('age_range', $user->age_range) === '55_plus' ? 'selected' : '' }}>
                                55+</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Profession</label>
                        <input name="profession" type="text" value="{{ old('profession', $user->profession) }}"
                            class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Interests (comma separated)</label>
                        <textarea name="interests" class="form-control"
                            rows="3">{{ old('interests', $user->interests) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-success">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
@endsection
