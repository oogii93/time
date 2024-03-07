<form method ="post" action="{{ route('admin.departure-records.filter') }}">
    @csrf
    <select name="department_id" id="">
        <option value="0">All</option>
        @foreach ($formData['departments'] as $department)
            <option value="{{ $department->id }}">{{ $department->name }}</option>
        @endforeach

    </select>
    <select name="user_id" id="">
        <option value="0">All</option>
        @foreach ($formData['users'] as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach

    </select>
    <input type="date" name="from" value="{{ old('from', now()->format('Y-m-d')) }}">
    <input type="date" name="to" value="{{ old('from', now()->format('Y-m-d')) }}">
    <button class="btn btn-info">
        検索
    </button>
</form>
