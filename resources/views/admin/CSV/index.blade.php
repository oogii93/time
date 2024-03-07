@extends('layouts.admin')
@section('content')
    @php
        $departments = App\Models\Department::all();

    @endphp
    <div class="card">
        <div class="card-header">
            月を選択してください
        </div>

        <div class="card-body">
            <form id="" action="{{ route('admin.CSV.download') }}" method="post">
                @csrf

                <div class="form-group">
                    <select name="department_id" class="form-select form-select-sm me-2" id="department">
                        <option selected disabled>選択</option>
                        <option value="0">All</option>
                        {{-- Loop through each user to create an option --}}
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                    <label for="month">月:</label>
                    <select name="month" id="month">
                        @php
                            // Set locale to Japanese for month names
                            \Carbon\Carbon::setLocale('ja');

                            // Get the current year
                            $currentYear = now()->year;

                            // Generate options for the months of the current year
                            for ($i = 1; $i <= 12; $i++) {
                                // Get the month name
                                $monthName = \Carbon\Carbon::create($currentYear, $i, 1)->translatedFormat('F');

                                // Output the option
                                echo "<option value='$currentYear-$i'>$currentYear 年 $monthName</option>";
                            }
                        @endphp
                    </select>
                </div>

                <button type="submit" class="btn btn-info">CSV　保存</button>

            </form>




        </div>
    </div>
@endsection
