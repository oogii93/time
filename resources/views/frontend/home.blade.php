@extends('layouts.frontend')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card" style="height: 500px; all -border-radius:30px">

                    <div class="card-header" style="background-color:black; color: #f4f155; height: 40px; ">
                        {{ __('ようこそ') }}, {{ auth()->user()->name }}{{ __(' 様') }}
                    </div>



                    <style>
                        /* Square buttons */
                        .btn {
                            border-radius: 0 !important;
                        }
                    </style>




                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <!-- Display current date above current time in Tokyo -->
                        <div class="text-center" style="font-size: 24px;">
                            <p id="current-date" style="margin-bottom: 0;"></p>
                            <p id="current-time" style="font-size: 72px;"></p>
                        </div>

                        <!-- Buttons for recording arrival and departure -->


                        <!--shine nemelj-t-->
                        <br><br><br><br>

                        <form action="{{ route('frontend.time.record.manual') }}" method="POST">
                            <input value="{{ now()->format('Y-m-d') }}T08:30" type="datetime-local" name="recorded_at"
                                class="form-control"
                                style="max-width: 300px; border-radius: 10px; padding: 12px; font-size: 16px;">

                            <br>
                            <div>

                                <button class="btn btn-info btn-block btn-lg mb-3 rounded-pill" name="button"
                                    value="ArrivalRecord" onclick="return true;" style="color: black;">出勤</button>

                                <button class="btn btn-warning btn-block btn-lg mb-3 rounded-pill" name="button"
                                    value="DepartureRecord" onclick="return true;" style="color: black;">退社</button>



                            </div>


                            <!--departure-->

                            @method('PUT')
                            @csrf
                        </form>
                        {{--
                        <div class="text-center mt-3">
                            <form action="{{ route('frontend.time.record') }}" method="POST">

                                @csrf
                                <br><br><br><br><br><br><br><br>
                                <button class="btn btn-info btn-block btn-lg mb-3" name="record_arrival" value="出勤"
                                    onclick="return true;">出勤</button>
                                <button class="btn btn-warning btn-block btn-lg" name="record_departure" value="退勤"
                                    onclick="return true;">退勤</button>

                            </form>
                        </div> --}}


                        {{--
                        @if (!App\Models\ArrivalRecord::where('user_id', auth()->user()->id)->whereDate('recorded_at', today())->first())
                            <!-- Input field for datetime and button -->
                            <div class="text-center mt-4">
                                <form action="{{ route('frontend.time.record.manual') }}" method="POST">
                                    <input value="{{ now()->format('Y-m-d') }}T08:30" type="datetime-local"
                                        name="recorded_at" class="form-control" style="width: 300px;">

                                    <button class="btn btn-success mt-2">時間選ぶ</button>
                                    @method('PUT')
                                    @csrf
                                </form>
                            </div>
                        @endif --}}
                    </div>
                </div>

                <a
                    href="{{ route('frontend.home.omnoh', [
                        'year' => Carbon\Carbon::now()->subMonth()->format('Y'),
                        'month' => Carbon\Carbon::now()->subMonth()->format('m'),
                    ]) }}">

                    <P style="color: black;" onmouseover="this.style.color='green'" onmouseout="this.style.color='black'">
                        ＜前月のリポート見る</P>
                </a>

            </div>
            <div class="col-md-6" style="overflow-x: auto; border-radius: 25px;">
                <!-- Show arrival and departure time on the Japanese calendar from the model -->
                <div class="table-responsive">


                    <table class="table table-bordered table-hover table-light table-sm table-smaller">
                        <!-- Table headers -->
                        <thead class="thead-dark">
                            <tr>
                                <th>日付け</th>
                                <th>出勤時間</th>
                                <th>退勤時間</th>
                                <th>労働時間</th>
                                <th>定時超1</th>
                                <th>定時超2</th>


                                <!-- Add more table headers as needed -->
                            </tr>
                        </thead>
                        <tbody>
                            {!! $tbody !!}
                        </tbody>
                    </table>





                </div>
            </div>

        </div>
    </div>

    <script>
        // Function to update the current time and date in Tokyo
        function updateTime() {
            // Get current date and time in Tokyo timezone
            var tokyoTime = new Date(new Date().toLocaleString("en-US", {
                timeZone: "Asia/Tokyo"
            }));

            // Format the time as HH:MM:SS
            var timeString = tokyoTime.getHours().toString().padStart(2, '0') + ':' +
                tokyoTime.getMinutes().toString().padStart(2, '0') + ':' +
                tokyoTime.getSeconds().toString().padStart(2, '0');

            // // Format the date as YYYY 年 MM 月 DD 日 （曜日）
            // var options = {
            //     year: 'numeric',
            //     month: '2-digit',
            //     day: '2-digit',
            //     weekday: 'long',
            //     era: 'short'
            // };
            var dateString = tokyoTime.toLocaleDateString('ja-JP', options);

            // Update the content of the elements with id="current-date" and id="current-time"
            document.getElementById('current-date').innerText = dateString;
            document.getElementById('current-time').innerText = timeString;
        }

        // Update the time initially
        updateTime();

        // Set interval to update time every second
        setInterval(updateTime, 1000);
    </script>
@endsection
