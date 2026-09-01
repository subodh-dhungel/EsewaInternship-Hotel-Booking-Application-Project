<x-layout>

    <main class="auth-page">
        <div class="auth-card">

            <div class="auth-header">
                <h1>Welcome Back</h1>
                <p>Login to your Esewa Hotels account</p>
            </div>

            <form action="/login" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        placeholder="Enter your email" required>

                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>

                    @error('password')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-options">
                    <label class="remember">
                        <input type="checkbox" name="remember">
                     
                    <span>Remember me</span>
                    </label>

                    <a href="/forgot-password">Forgot password?</a>
                </div>

                @if ($errors->any())
                    <div class="login-error">
                        {{ $errors->first() }}
                    </div>
                @endif

                <button type="submit" class="login-btn">
                    Login
                </button>
            </form>

            <div class="register-link">
                <p>
                    Don't have an account?
                    <a href="/register">Create an account</a>
                </p>
            </div>

        </div>
    </main>

</x-layout>
