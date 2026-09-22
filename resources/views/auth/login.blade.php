<x-layout>

    <main class="auth-page">
        <div class="auth-shell">
            <aside class="auth-story auth-story--login">
                <div class="auth-story__shade"></div>
                <div class="auth-story__content">
                    <span class="auth-overline">Stay closer to what matters</span>
                    <h1>Welcome back<br><em>to Nepal.</em></h1>
                    <p>Pick up where you left off and find a place worth arriving at.</p>
                    <div class="auth-proof"><span>★</span><div><strong>4.8 / 5</strong><small>average guest rating</small></div></div>
                </div>
            </aside>

            <div class="auth-card">
                <div class="auth-header">
                    <span class="auth-card-kicker">Your account</span>
                    <h2>Sign in</h2>
                    <p>Access your bookings and saved stays.</p>
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

                <button type="submit" class="login-btn">Sign in <span aria-hidden="true">→</span></button>
            </form>

            <div class="register-link">
                <p>
                    Don't have an account?
                    <a href="/register">Create an account</a>
                </p>
            </div>

            </div>
        </div>
    </main>

</x-layout>
