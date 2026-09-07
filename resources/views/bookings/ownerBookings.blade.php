<x-owner-layout>

    <div class="p-6">

        <h1 class="text-2xl font-bold mb-6">
            Bookings
        </h1>

        @if ($bookings->isEmpty())

            <div class="bg-white p-6 rounded-lg shadow">
                <p class="text-gray-500">
                    No bookings found.
                </p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow overflow-hidden">

                <table class="w-full">

                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                Booking
                            </th>

                            <th class="px-6 py-3 text-left">
                                Guest
                            </th>

                            <th class="px-6 py-3 text-left">
                                Hotel
                            </th>

                            <th class="px-6 py-3 text-left">
                                Room Type
                            </th>

                            <th class="px-6 py-3 text-left">
                                Check In
                            </th>

                            <th class="px-6 py-3 text-left">
                                Check Out
                            </th>

                            <th class="px-6 py-3 text-left">
                                Status
                            </th>

                            <th class="px-6 py-3 text-left">
                                Action
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($bookings as $booking)
                            <tr class="border-t">

                                <td class="px-6 py-4">
                                    {{ $booking->booking_number }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $booking->user->name }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $booking->hotel->name }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $booking->roomType->name }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $booking->check_in->format('Y-m-d') }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $booking->check_out->format('Y-m-d') }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ ucfirst($booking->booking_status) }}
                                </td>

                                <td class="px-6 py-4">

                                    <a href="{{ route('owner.bookings', $booking) }}"
                                        class="text-blue-600 hover:underline">
                                        View
                                    </a>

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </div>

</x-owner-layout>
