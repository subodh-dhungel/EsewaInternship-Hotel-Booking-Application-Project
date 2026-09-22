<x-layout>
    <main class="auth-page">
        <div class="auth-shell">
            <aside class="auth-story auth-story--register">
                <div class="auth-story__shade"></div>
                <div class="auth-story__content">
                    <span class="auth-overline">Your next stay starts here</span>
                    <h1>Make room for<br><em>good days.</em></h1>
                    <p>Join a simpler way to discover hotels, resorts and memorable places across Nepal.</p>
                    <div class="auth-highlights"><span>✓</span><p>Verified properties</p><span>✓</span><p>Secure booking</p><span>✓</span><p>Local support</p></div>
                </div>
            </aside>

            <div class="auth-card">
                <div class="auth-header">
                    <span class="auth-card-kicker">eSewa Hotels</span>
                    <h2>Create your account</h2>
                    <p>Save stays and book your next escape faster.</p>
                </div>

            <form action="{{ route('user.register') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                        placeholder="Enter your full name" required autocomplete="name">

                    @error('name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        placeholder="Enter your email" required autocomplete="email">

                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Create a password" required
                        autocomplete="new-password">

                    @error('password')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        placeholder="Confirm your password" required autocomplete="new-password">
                </div>

                <button type="submit" class="btn auth-btn">Create account <span aria-hidden="true">→</span></button>

            </form>

            <div class="auth-footer">
                <p>
                    Already have an account?
                    <a href="{{ route('show.login') }}">Login</a>
                </p>
            </div>

            </div>
        </div>

    </main>

</x-layout>
