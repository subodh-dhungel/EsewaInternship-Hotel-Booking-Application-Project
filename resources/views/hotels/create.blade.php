<x-owner-layout>

    <div class="hotel-create-page">

        <div class="hotel-create-header">
            <h1>Create Hotel</h1>
            <p>Add a new hotel to your property portfolio.</p>
        </div>

        <form 
            action="{{ route('owner.hotels.store') }}" 
            method="POST" 
            class="hotel-form"
            enctype="multipart/form-data">
            @csrf

            <div class="form-section">
                <div class="form-section-title">
                    <h2>Basic Information</h2>
                    <p>Provide the basic details of the hotel.</p>
                </div>

                <div class="form-grid">

                    <div class="form-group">
                        <label for="name">Hotel Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="star_rating">Star Rating</label>
                        <select id="star_rating" name="star_rating" required>
                            <option value="">Select rating</option>
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ old('star_rating') == $i ? 'selected' : '' }}>
                                    {{ $i }} Star
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" required>{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="price">Per night price</lable>
                        <input type="number" id="price" name="price" required>
                    </div>

                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">
                    <h2>Location</h2>
                </div>

                <div class="form-grid">

                    <div class="form-group full-width">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" value="{{ old('address') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="district">District</label>
                        <input type="text" id="district" name="district" value="{{ old('district') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" id="country" name="country" value="{{ old('country', 'Nepal') }}"
                            required>
                    </div>

                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">
                    <h2>Contact Information</h2>
                </div>

                <div class="form-grid">

                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                    </div>

                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">
                    <h2>Hotel Hours</h2>
                </div>

                <div class="form-grid">

                    <div class="form-group">
                        <label for="checkin_time">Check-in Time</label>
                        <input type="time" id="checkin_time" name="checkin_time" value="{{ old('checkin_time') }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="check_out_time">Check-out Time</label>
                        <input type="time" id="check_out_time" name="check_out_time"
                            value="{{ old('check_out_time') }}" required>
                    </div>

                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">
                    <h2>Hotel Image</h2>
                </div>

                <div class="form-group">
                    <label for="featured_image">Featured Image</label>
                    <input type="file" id="featured_image" name="featured_image" accept="image/*">
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('hotels.index') }}" class="btn btn-secondary">
                    Cancel
                </a>

                <button type="submit" class="btn btn-primary">
                    Create Hotel
                </button>
            </div>

        </form>

    </div>

</x-owner-layout>
