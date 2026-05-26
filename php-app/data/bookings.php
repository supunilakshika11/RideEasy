<?php

declare(strict_types=1);

const BOOKINGS_FILE = __DIR__ . '/../storage/bookings.json';

function ensure_booking_file(): void
{
    $directory = dirname(BOOKINGS_FILE);
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    if (!file_exists(BOOKINGS_FILE)) {
        file_put_contents(BOOKINGS_FILE, json_encode([], JSON_PRETTY_PRINT));
    }
}

function get_bookings(): array
{
    ensure_booking_file();
    $raw = file_get_contents(BOOKINGS_FILE);
    $bookings = json_decode($raw ?: '[]', true);

    return is_array($bookings) ? $bookings : [];
}

function save_booking(array $booking): void
{
    $bookings = get_bookings();
    array_unshift($bookings, $booking);
    file_put_contents(BOOKINGS_FILE, json_encode($bookings, JSON_PRETTY_PRINT), LOCK_EX);
}

function update_booking_status(string $bookingId, string $status): void
{
    $bookings = array_map(
        fn (array $booking) => $booking['id'] === $bookingId
            ? array_merge($booking, ['status' => $status])
            : $booking,
        get_bookings()
    );

    file_put_contents(BOOKINGS_FILE, json_encode($bookings, JSON_PRETTY_PRINT), LOCK_EX);
}

function make_booking_id(string $vehicleId): string
{
    return 'RE-' . strtoupper(substr($vehicleId, 0, 3)) . '-' . random_int(1000, 9999);
}

function find_booking(string $bookingId): ?array
{
    foreach (get_bookings() as $booking) {
        if (($booking['id'] ?? '') === $bookingId) {
            return $booking;
        }
    }

    return null;
}
