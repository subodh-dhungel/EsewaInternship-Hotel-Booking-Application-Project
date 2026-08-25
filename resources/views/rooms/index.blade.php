<x-owner-layout>

    <main class="rooms-page">

        {{-- =========================================
             PAGE HEADER
             ========================================= --}}

        <div class="page-header">

            <div class="page-header-content">

                <p class="breadcrumb">
                    <a href="{{ route('hotels.index') }}">
                        My Hotels
                    </a>

                    <span>/</span>

                    <span>{{ $hotel->name }}</span>
                </p>

                <h1>
                    {{ $hotel->name }} — Rooms
                </h1>

                <p class="page-description">
                    Manage the individual rooms available in this hotel.
                </p>

            </div>

            <div class="page-header-actions">

                <a
                    href="{{ route('rooms.create', $hotel) }}"
                    class="btn"
                >
                    + Create Room
                </a>

            </div>

        </div>


        {{-- =========================================
             ROOM LIST
             ========================================= --}}

        @if ($rooms->isEmpty())

            <section class="empty-state">

                <div class="empty-state-content">

                    <h2>
                        No rooms yet
                    </h2>

                    <p>
                        This hotel doesn't have any individual rooms
                        configured yet.
                    </p>

                    <a
                        href="{{ route('rooms.create', $hotel) }}"
                        class="btn"
                    >
                        Create Your First Room
                    </a>

                </div>

            </section>

        @else

            <section class="rooms-section">

                <div class="section-heading">

                    <div>
                        <h2>
                            Rooms
                        </h2>

                        <p>
                            {{ $rooms->count() }}
                            {{ $rooms->count() === 1 ? 'room' : 'rooms' }}
                        </p>
                    </div>

                </div>


                <div class="rooms-grid">

                    @foreach ($rooms as $room)

                        <article class="room-card">

                            {{-- Room header --}}

                            <div class="room-card-header">

                                <div>

                                    <p class="room-number">
                                        Room {{ $room->room_number }}
                                    </p>

                                    <h3>
                                        {{ $room->name ?? $room->roomType->name }}
                                    </h3>

                                </div>


                                <span
                                    class="room-status room-status-{{ $room->status }}"
                                >
                                    {{ ucfirst($room->status) }}
                                </span>

                            </div>


                            {{-- Room information --}}

                            <div class="room-card-body">

                                <div class="room-detail">

                                    <span class="room-detail-label">
                                        Room Type
                                    </span>

                                    <strong>
                                        {{ $room->roomType->name }}
                                    </strong>

                                </div>

                                <div class="room-detail">

                                    <span class="room-detail-label">
                                        Capacity
                                    </span>

                                    <strong>
                                        {{ $room->roomType->capacity }}
                                        {{ $room->roomType->capacity == 1 ? 'guest' : 'guests' }}
                                    </strong>

                                </div>

                                <div class="room-detail">

                                    <span class="room-detail-label">
                                        Bed
                                    </span>

                                    <strong>
                                        {{ $room->roomType->bed_type }}
                                    </strong>

                                </div>

                                <div class="room-detail">

                                    <span class="room-detail-label">
                                        Price
                                    </span>

                                    <strong>
                                        Rs. {{ number_format($room->roomType->price, 2) }}
                                    </strong>

                                </div>

                            </div>


                            {{-- Actions --}}

                            <div class="room-card-actions">

                                <a
                                    href="{{ route('rooms.edit', [$hotel, $room]) }}"
                                    class="btn btn-secondary"
                                >
                                    Edit
                                </a>

                                <form
                                    action="{{ route('rooms.destroy', [$hotel, $room]) }}"
                                    method="POST"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-alert"
                                    >
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </article>

                    @endforeach

                </div>

            </section>

        @endif

    </main>

</x-owner-layout>