@extends('layouts.app')

@section('content')
    <div class="login-box" style="background-color: #3498db;">
        <div class="login-logo">
            <a href="{{ route('admin.home') }}">
                <p style="font-size: 24px; font-weight: bold; color: #ffffff;">太成ホールディングス</p>
            </a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg" style="font-size: 20px; font-weight: bold; color: #ffffff;">ログイン</p>
                @if (session()->has('message'))
                    <div class="alert alert-info">
                        {{ session()->get('message') }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="メールアドレス" name="email"
                            value="{{ old('email') }}" required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="パスワード" name="password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" name="remember" id="remember">
                                <label for="remember">覚える</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">ログイン</button>
                        </div>
                    </div>
                </form>

                <p class="mt-3">
                    <a href="{{ route('password.request') }}" style="font-size: 14px; color: #311e11;">パスワードを忘れた場合</a>
                </p>
                <p class="mt-3">
                    <a href="{{ route('register') }}" style="font-size: 14px; color: #311e11;">新規登録する</a>
                </p>

            </div>

        </div>
    </div>
@endsection
