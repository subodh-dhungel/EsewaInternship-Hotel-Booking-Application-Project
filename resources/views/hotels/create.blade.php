<x-layout>
    <form action="{{ route('hotels.store') }}" method="POST">
        @csrf

        <div>
            <label for="name">Hotel Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}">

            @error('name')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="description">Description</label>
            <textarea name="description" id="description">{{ old('description') }}</textarea>

            @error('description')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="address">Address</label>
            <input type="text" name="address" id="address" value="{{ old('address') }}">

            @error('address')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="city_id">City ID</label>
            <input type="number" name="city_id" id="city_id" value="{{ old('city_id') }}">

            @error('city_id')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <button type="submit">
            Create Hotel
        </button>
    </form>
</x-layout>
