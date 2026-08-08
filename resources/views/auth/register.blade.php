<x-layout>
    <main class="auth-page">

        <div class="auth-card">

            <div class="auth-header">
                <h1>Create an Account</h1>
                <p>Join eSewa Hotels and start booking your stay.</p>
            </div>

            <form action="{{ route('user.create') }}" method="POST">
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

                <button type="submit" class="btn auth-btn">
                    Create Account
                </button>

            </form>

            <div class="auth-footer">
                <p>
                    Already have an account?
                    <a href="{{ route('user.login') }}">Login</a>
                </p>
            </div>

        </div>

    </main>

</x-layout>
