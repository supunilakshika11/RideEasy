<?php

declare(strict_types=1);

session_start();

require __DIR__ . '/data/vehicles.php';
require __DIR__ . '/data/bookings.php';

function h(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function lkr(int|float $amount): string
{
    return 'LKR ' . number_format((float) $amount, 0);
}

function app_url(array $params = []): string
{
    return 'index.php' . ($params ? '?' . http_build_query($params) : '');
}

function status_class(string $status): string
{
    return strtolower(trim((string) preg_replace('/[^a-z0-9]+/i', '-', $status), '-'));
}

function rental_days(string $pickupDate, string $returnDate): int
{
    try {
        $pickup = new DateTimeImmutable($pickupDate);
        $return = new DateTimeImmutable($returnDate);
        $days = (int) $pickup->diff($return)->format('%r%a');

        return max(1, $days);
    } catch (Throwable) {
        return 1;
    }
}

function redirect_to(array $params): never
{
    header('Location: ' . app_url($params));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'auth') {
        $_SESSION['rideeasy_user'] = trim((string) ($_POST['name'] ?? '')) ?: trim((string) ($_POST['email'] ?? 'Guest'));
        $_SESSION['rideeasy_role'] = $_POST['role'] === 'Admin' ? 'Admin' : 'Customer';
        redirect_to(['page' => $_SESSION['rideeasy_role'] === 'Admin' ? 'admin' : 'customer']);
    }

    if ($action === 'create_booking') {
        $vehicle = get_vehicle((string) ($_POST['vehicle_id'] ?? ''));
        $pickupDate = (string) ($_POST['pickup_date'] ?? date('Y-m-d'));
        $returnDate = (string) ($_POST['return_date'] ?? date('Y-m-d', strtotime('+2 days')));
        $days = rental_days($pickupDate, $returnDate);
        $subtotal = $vehicle['pricePerDay'] * $days;
        $protection = (int) round($subtotal * 0.08);
        $serviceFee = 2500;
        $bookingId = make_booking_id($vehicle['id']);
        $customerName = trim((string) ($_POST['customer_name'] ?? 'Customer'));

        save_booking([
            'id' => $bookingId,
            'vehicleId' => $vehicle['id'],
            'vehicleName' => $vehicle['name'],
            'vehicleImage' => $vehicle['image'],
            'customerName' => $customerName,
            'email' => trim((string) ($_POST['email'] ?? '')),
            'phone' => trim((string) ($_POST['phone'] ?? '')),
            'license' => trim((string) ($_POST['license'] ?? '')),
            'pickupDate' => $pickupDate,
            'returnDate' => $returnDate,
            'pickupLocation' => trim((string) ($_POST['pickup_location'] ?? 'Colombo Fort')),
            'days' => $days,
            'dailyRate' => $vehicle['pricePerDay'],
            'total' => $subtotal + $protection + $serviceFee,
            'status' => 'Confirmed',
            'createdAt' => date(DATE_ATOM),
        ]);

        $_SESSION['rideeasy_user'] = $customerName;
        $_SESSION['rideeasy_role'] = 'Customer';
        redirect_to(['page' => 'booking-success', 'code' => $bookingId]);
    }

    if ($action === 'update_status') {
        update_booking_status((string) ($_POST['booking_id'] ?? ''), (string) ($_POST['status'] ?? 'Confirmed'));
        redirect_to(['page' => 'admin']);
    }

    if ($action === 'contact') {
        $_SESSION['flash'] = 'Message sent. Our team will respond shortly.';
        redirect_to(['page' => 'contact']);
    }
}

$page = $_GET['page'] ?? 'home';

function is_active(string $pageName): string
{
    return ($_GET['page'] ?? 'home') === $pageName ? 'active' : '';
}

function render_vehicle_card(array $vehicle, bool $compact = false): void
{
    ?>
    <article class="vehicle-card <?= $compact ? 'compact' : '' ?>">
        <a class="vehicle-media" href="<?= h(app_url(['page' => 'vehicle', 'id' => $vehicle['id']])) ?>">
            <img src="<?= h($vehicle['image']) ?>" alt="<?= h($vehicle['name']) ?>">
            <?php if (!empty($vehicle['badge'])): ?>
                <span class="vehicle-badge"><?= h($vehicle['badge']) ?></span>
            <?php endif; ?>
        </a>
        <div class="vehicle-card-body">
            <div class="vehicle-title-row">
                <div>
                    <p class="vehicle-type"><?= h($vehicle['category']) ?></p>
                    <h3><?= h($vehicle['name']) ?></h3>
                </div>
                <span class="rating">Star <?= h(number_format((float) $vehicle['rating'], 1)) ?></span>
            </div>
            <?php if (!$compact): ?>
                <p class="vehicle-copy"><?= h($vehicle['tagline']) ?></p>
            <?php endif; ?>
            <div class="spec-row">
                <span><?= h($vehicle['seats']) ?> seats</span>
                <span><?= h($vehicle['transmission']) ?></span>
                <span><?= h($vehicle['fuelType']) ?></span>
            </div>
            <div class="vehicle-card-footer">
                <div>
                    <strong><?= h(lkr($vehicle['pricePerDay'])) ?></strong>
                    <span>/ day</span>
                </div>
                <a class="button-primary small" href="<?= h(app_url(['page' => 'booking', 'id' => $vehicle['id']])) ?>">Rent Now</a>
            </div>
        </div>
    </article>
    <?php
}

function render_stat(string $label, string $value, string $trend = '', string $tone = ''): void
{
    ?>
    <article class="stat-card <?= h($tone) ?>">
        <span><?= h($label) ?></span>
        <strong><?= h($value) ?></strong>
        <?php if ($trend): ?><small><?= h($trend) ?></small><?php endif; ?>
    </article>
    <?php
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RideEasy Vehicle Rental PHP</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header class="site-header">
        <a class="brand" href="<?= h(app_url()) ?>">
            <span class="brand-mark">R</span>
            <span>RideEasy</span>
        </a>
        <nav class="nav-links" aria-label="Primary navigation">
            <a class="nav-link <?= h(is_active('home')) ?>" href="<?= h(app_url()) ?>">Home</a>
            <a class="nav-link <?= h(is_active('vehicles')) ?>" href="<?= h(app_url(['page' => 'vehicles'])) ?>">Vehicles</a>
            <a class="nav-link <?= h(is_active('booking')) ?>" href="<?= h(app_url(['page' => 'booking'])) ?>">Booking</a>
            <a class="nav-link <?= h(is_active('customer')) ?>" href="<?= h(app_url(['page' => 'customer'])) ?>">Customer</a>
            <a class="nav-link <?= h(is_active('admin')) ?>" href="<?= h(app_url(['page' => 'admin'])) ?>">Admin</a>
            <a class="nav-link <?= h(is_active('contact')) ?>" href="<?= h(app_url(['page' => 'contact'])) ?>">Contact</a>
        </nav>
        <a class="header-action" href="<?= h(app_url(['page' => 'login'])) ?>">Sign in</a>
    </header>

    <main class="page-container">
        <?php if ($page === 'home'): ?>
            <section class="hero" style="background-image: linear-gradient(90deg, rgba(8, 25, 50, 0.84), rgba(8, 25, 50, 0.4)), url('https://images.unsplash.com/photo-1502877338535-766e1452684a?auto=format&fit=crop&w=1600&q=80')">
                <div class="hero-content">
                    <p class="eyebrow">Premium vehicle rentals</p>
                    <h1>Find your drive, <span>own the journey.</span></h1>
                    <p>Book clean, verified vehicles for city errands, airport transfers, business travel, and weekend escapes.</p>
                    <div class="hero-actions">
                        <a class="button-primary" href="<?= h(app_url(['page' => 'vehicles'])) ?>">Explore Fleet</a>
                        <a class="button-secondary light" href="<?= h(app_url(['page' => 'contact'])) ?>">Contact Support</a>
                    </div>
                </div>
                <form class="search-panel" method="get" action="index.php">
                    <input type="hidden" name="page" value="vehicles">
                    <label>Location <input name="search" value="Colombo" placeholder="Pickup city"></label>
                    <label>Pick-up <input name="pickup" type="date" value="2026-05-28"></label>
                    <label>Drop-off <input name="dropoff" type="date" value="2026-05-31"></label>
                    <button class="button-primary" type="submit">Search Vehicles</button>
                </form>
            </section>

            <section class="section-block">
                <div class="section-heading-row">
                    <div>
                        <p class="eyebrow dark">Browse by category</p>
                        <h2>Pick a vehicle for every plan</h2>
                    </div>
                    <a class="text-link" href="<?= h(app_url(['page' => 'vehicles'])) ?>">View all</a>
                </div>
                <div class="category-grid">
                    <?php foreach (array_slice(categories(), 1) as $category): ?>
                        <?php $categoryVehicle = current(array_filter(vehicles(), fn ($v) => $v['category'] === $category)) ?: vehicles()[0]; ?>
                        <a class="category-tile" href="<?= h(app_url(['page' => 'vehicles', 'category' => $category])) ?>">
                            <img src="<?= h($categoryVehicle['image']) ?>" alt="<?= h($category) ?>">
                            <span><?= h($category) ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="section-block">
                <div class="section-heading-row">
                    <div>
                        <p class="eyebrow dark">Premium fleet</p>
                        <h2>Popular cars ready now</h2>
                    </div>
                    <a class="text-link" href="<?= h(app_url(['page' => 'vehicles'])) ?>">Compare fleet</a>
                </div>
                <div class="vehicle-grid">
                    <?php foreach (array_slice(vehicles(), 0, 3) as $vehicle): ?>
                        <?php render_vehicle_card($vehicle); ?>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="how-section">
                <div class="section-heading-row centered">
                    <div>
                        <p class="eyebrow dark">How it works</p>
                        <h2>Book in three clean steps</h2>
                    </div>
                </div>
                <div class="steps-grid">
                    <article class="step-card"><span>1</span><h3>Browse and choose</h3><p>Filter by category, price, seats, and travel style.</p></article>
                    <article class="step-card"><span>2</span><h3>Instant booking</h3><p>Confirm trip details and lock in transparent pricing.</p></article>
                    <article class="step-card"><span>3</span><h3>Pick up and drive</h3><p>Collect your vehicle with digital booking support.</p></article>
                </div>
            </section>

            <section class="cta-band">
                <div><h2>Need a car today?</h2><p>Our support team can help you match a vehicle to your route and budget.</p></div>
                <a class="button-primary" href="<?= h(app_url(['page' => 'booking'])) ?>">Rent Now</a>
            </section>

        <?php elseif ($page === 'vehicles'): ?>
            <?php
            $search = strtolower(trim((string) ($_GET['search'] ?? '')));
            $category = (string) ($_GET['category'] ?? 'All');
            $sort = (string) ($_GET['sort'] ?? 'featured');
            $filteredVehicles = array_values(array_filter(vehicles(), function (array $vehicle) use ($search, $category): bool {
                $matchesCategory = $category === 'All' || $vehicle['category'] === $category;
                $haystack = strtolower($vehicle['name'] . ' ' . $vehicle['category'] . ' ' . $vehicle['fuelType']);
                return $matchesCategory && ($search === '' || str_contains($haystack, $search));
            }));
            usort($filteredVehicles, function (array $a, array $b) use ($sort): int {
                return match ($sort) {
                    'price-low' => $a['pricePerDay'] <=> $b['pricePerDay'],
                    'price-high' => $b['pricePerDay'] <=> $a['pricePerDay'],
                    'rating' => $b['rating'] <=> $a['rating'],
                    default => (int) !empty($b['badge']) <=> (int) !empty($a['badge']),
                };
            });
            ?>
            <section class="page-stack">
                <div class="page-title">
                    <p class="eyebrow dark">Fleet search</p>
                    <h1>Premium Fleet</h1>
                    <p>Compare verified vehicles by comfort, range, luggage capacity, and daily rental price.</p>
                </div>
                <form class="filter-bar" method="get" action="index.php">
                    <input type="hidden" name="page" value="vehicles">
                    <input name="search" value="<?= h($_GET['search'] ?? '') ?>" placeholder="Search by name, fuel, or category">
                    <select name="category">
                        <?php foreach (categories() as $item): ?>
                            <option value="<?= h($item) ?>" <?= $category === $item ? 'selected' : '' ?>><?= h($item) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="sort">
                        <option value="featured" <?= $sort === 'featured' ? 'selected' : '' ?>>Featured</option>
                        <option value="rating" <?= $sort === 'rating' ? 'selected' : '' ?>>Highest rated</option>
                        <option value="price-low" <?= $sort === 'price-low' ? 'selected' : '' ?>>Price low to high</option>
                        <option value="price-high" <?= $sort === 'price-high' ? 'selected' : '' ?>>Price high to low</option>
                    </select>
                    <button class="button-primary" type="submit">Apply</button>
                </form>
                <div class="results-row"><span><?= count($filteredVehicles) ?> vehicles available</span><span>Free cancellation on selected bookings</span></div>
                <div class="vehicle-grid">
                    <?php foreach ($filteredVehicles as $vehicle): ?>
                        <?php render_vehicle_card($vehicle); ?>
                    <?php endforeach; ?>
                </div>
            </section>

        <?php elseif ($page === 'vehicle'): ?>
            <?php
            $vehicle = get_vehicle((string) ($_GET['id'] ?? ''));
            $photoIndex = max(0, min(count($vehicle['gallery']) - 1, (int) ($_GET['photo'] ?? 0)));
            ?>
            <section class="detail-layout">
                <div>
                    <div class="detail-gallery">
                        <img class="detail-main-image" src="<?= h($vehicle['gallery'][$photoIndex]) ?>" alt="<?= h($vehicle['name']) ?>">
                        <div class="thumbnail-row">
                            <?php foreach ($vehicle['gallery'] as $index => $image): ?>
                                <a class="thumbnail <?= $index === $photoIndex ? 'active' : '' ?>" href="<?= h(app_url(['page' => 'vehicle', 'id' => $vehicle['id'], 'photo' => $index])) ?>">
                                    <img src="<?= h($image) ?>" alt="<?= h($vehicle['name']) ?> thumbnail">
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="detail-section">
                        <p class="eyebrow dark"><?= h($vehicle['category']) ?></p>
                        <h1><?= h($vehicle['name']) ?></h1>
                        <p><?= h($vehicle['description']) ?></p>
                        <div class="spec-card-grid">
                            <div><span>Seats</span><strong><?= h($vehicle['seats']) ?></strong></div>
                            <div><span>Range</span><strong><?= h($vehicle['range']) ?></strong></div>
                            <div><span>Fuel</span><strong><?= h($vehicle['fuelType']) ?></strong></div>
                            <div><span>Luggage</span><strong><?= h($vehicle['luggage']) ?></strong></div>
                        </div>
                    </div>
                    <div class="detail-section">
                        <h2>Technical Specifications</h2>
                        <div class="info-list">
                            <?php foreach ($vehicle['specs'] as $label => $value): ?>
                                <div><span><?= h($label) ?></span><strong><?= h($value) ?></strong></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="detail-section">
                        <h2>Premium Features</h2>
                        <div class="feature-list">
                            <?php foreach ($vehicle['features'] as $feature): ?><span><?= h($feature) ?></span><?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <aside class="booking-card">
                    <span class="availability-dot">Available today</span>
                    <h2><?= h(lkr($vehicle['pricePerDay'])) ?> <small>/ day</small></h2>
                    <p><?= h($vehicle['tagline']) ?></p>
                    <div class="info-list compact">
                        <div><span>Transmission</span><strong><?= h($vehicle['transmission']) ?></strong></div>
                        <div><span>Rating</span><strong><?= h(number_format((float) $vehicle['rating'], 1)) ?> / 5</strong></div>
                    </div>
                    <a class="button-primary full" href="<?= h(app_url(['page' => 'booking', 'id' => $vehicle['id']])) ?>">Reserve This Vehicle</a>
                    <a class="button-secondary full" href="<?= h(app_url(['page' => 'vehicles'])) ?>">Back to Fleet</a>
                </aside>
            </section>

        <?php elseif ($page === 'booking'): ?>
            <?php
            $vehicle = get_vehicle((string) ($_GET['id'] ?? ''));
            $defaultPickup = '2026-05-28';
            $defaultReturn = '2026-05-31';
            $days = rental_days($defaultPickup, $defaultReturn);
            $subtotal = $vehicle['pricePerDay'] * $days;
            $protection = (int) round($subtotal * 0.08);
            $serviceFee = 2500;
            $total = $subtotal + $protection + $serviceFee;
            ?>
            <section class="booking-layout">
                <div class="page-title">
                    <p class="eyebrow dark">Booking</p>
                    <h1>Complete Your Reservation</h1>
                    <p>Confirm trip details, renter information, and transparent pricing.</p>
                </div>
                <div class="booking-grid">
                    <form class="form-stack wide" method="post" action="index.php?page=booking">
                        <input type="hidden" name="action" value="create_booking">
                        <input type="hidden" name="vehicle_id" value="<?= h($vehicle['id']) ?>">
                        <section class="form-section">
                            <h2>Trip Details</h2>
                            <div class="two-column">
                                <label>Pick-up date <input name="pickup_date" type="date" value="<?= h($defaultPickup) ?>" required></label>
                                <label>Return date <input name="return_date" type="date" value="<?= h($defaultReturn) ?>" required></label>
                            </div>
                            <label>Pick-up location <input name="pickup_location" value="Colombo Fort" required></label>
                        </section>
                        <section class="form-section">
                            <h2>Renter Information</h2>
                            <label>Full name <input name="customer_name" placeholder="Full name" required></label>
                            <div class="two-column">
                                <label>Email <input name="email" type="email" placeholder="you@example.com" required></label>
                                <label>Phone <input name="phone" placeholder="0771234567" required></label>
                            </div>
                            <label>Driving license number <input name="license" placeholder="License ID" required></label>
                        </section>
                        <button class="button-primary full" type="submit">Confirm Booking</button>
                    </form>
                    <aside class="booking-card">
                        <img class="summary-image" src="<?= h($vehicle['image']) ?>" alt="<?= h($vehicle['name']) ?>">
                        <h2><?= h($vehicle['name']) ?></h2>
                        <p><?= h($vehicle['tagline']) ?></p>
                        <div class="info-list compact">
                            <div><span>Days</span><strong><?= h($days) ?></strong></div>
                            <div><span>Daily rate</span><strong><?= h(lkr($vehicle['pricePerDay'])) ?></strong></div>
                            <div><span>Protection</span><strong><?= h(lkr($protection)) ?></strong></div>
                            <div><span>Service fee</span><strong><?= h(lkr($serviceFee)) ?></strong></div>
                            <div class="total-row"><span>Total</span><strong><?= h(lkr($total)) ?></strong></div>
                        </div>
                    </aside>
                </div>
            </section>

        <?php elseif ($page === 'booking-success'): ?>
            <?php $booking = find_booking((string) ($_GET['code'] ?? '')); ?>
            <section class="success-panel">
                <h2>Booking Confirmed</h2>
                <?php if ($booking): ?>
                    <p>Your reservation for <strong><?= h($booking['vehicleName']) ?></strong> is ready. Use booking code <strong><?= h($booking['id']) ?></strong> at pickup.</p>
                <?php else: ?>
                    <p>Your reservation is ready. Open the customer dashboard to view recent bookings.</p>
                <?php endif; ?>
                <div class="success-actions">
                    <a class="button-primary" href="<?= h(app_url(['page' => 'customer'])) ?>">Open Customer Dashboard</a>
                    <a class="button-secondary" href="<?= h(app_url(['page' => 'vehicles'])) ?>">Browse More Vehicles</a>
                </div>
            </section>

        <?php elseif ($page === 'customer'): ?>
            <?php
            $bookings = get_bookings();
            if (!$bookings) {
                $vehicle = vehicles()[0];
                $bookings = [[
                    'id' => 'RE-TES-3821',
                    'vehicleId' => $vehicle['id'],
                    'vehicleName' => $vehicle['name'],
                    'vehicleImage' => $vehicle['image'],
                    'pickupDate' => '2026-05-28',
                    'status' => 'Confirmed',
                    'total' => 86200,
                ]];
            }
            $activeBooking = $bookings[0];
            $user = $_SESSION['rideeasy_user'] ?? ($activeBooking['customerName'] ?? 'Alex');
            ?>
            <section class="dashboard-page">
                <div class="dashboard-hero">
                    <div>
                        <p class="eyebrow">Customer dashboard</p>
                        <h1>Welcome back, <?= h($user) ?></h1>
                        <p>Your next rental, booking history, and recommended vehicles are ready.</p>
                    </div>
                    <a class="button-primary" href="<?= h(app_url(['page' => 'vehicles'])) ?>">New Booking</a>
                </div>
                <div class="dashboard-grid">
                    <article class="active-booking-card">
                        <span class="vehicle-badge">Active booking</span>
                        <img src="<?= h($activeBooking['vehicleImage'] ?? vehicles()[0]['image']) ?>" alt="<?= h($activeBooking['vehicleName']) ?>">
                        <h2><?= h($activeBooking['vehicleName']) ?></h2>
                        <div class="info-list compact">
                            <div><span>Pickup</span><strong><?= h($activeBooking['pickupDate'] ?? '2026-05-28') ?></strong></div>
                            <div><span>Total</span><strong><?= h(lkr((float) ($activeBooking['total'] ?? 0))) ?></strong></div>
                        </div>
                        <a class="button-secondary full" href="<?= h(app_url(['page' => 'booking', 'id' => $activeBooking['vehicleId'] ?? 'tesla-model-3'])) ?>">Manage Reservation</a>
                    </article>
                    <section class="panel">
                        <div class="section-heading-row"><h2>Booking History</h2><a class="text-link" href="<?= h(app_url(['page' => 'vehicles'])) ?>">View all</a></div>
                        <div class="simple-table customer-table">
                            <?php foreach ($bookings as $booking): ?>
                                <div>
                                    <span><?= h($booking['id']) ?></span>
                                    <strong><?= h($booking['vehicleName']) ?></strong>
                                    <span><?= h($booking['pickupDate'] ?? '-') ?></span>
                                    <span class="status <?= h(status_class($booking['status'] ?? 'Confirmed')) ?>"><?= h($booking['status'] ?? 'Confirmed') ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                    <section class="panel loyalty-card">
                        <h2>RideEasy Plus</h2>
                        <p>Gold member</p>
                        <strong>1,250 points</strong>
                        <span>Next reward unlocks at 1,500 points.</span>
                    </section>
                </div>
                <section class="section-block flush">
                    <div class="section-heading-row"><h2>Recommended For You</h2><a class="text-link" href="<?= h(app_url(['page' => 'vehicles'])) ?>">Explore</a></div>
                    <div class="vehicle-grid">
                        <?php foreach (array_slice(vehicles(), 1, 3) as $vehicle): ?>
                            <?php render_vehicle_card($vehicle, true); ?>
                        <?php endforeach; ?>
                    </div>
                </section>
            </section>

        <?php elseif ($page === 'admin'): ?>
            <?php
            $savedBookings = get_bookings();
            $adminBookings = $savedBookings ?: demo_recent_bookings();
            $revenue = array_reduce($adminBookings, fn ($sum, $booking) => $sum + (float) ($booking['total'] ?? 0), 0);
            ?>
            <section class="dashboard-page">
                <div class="page-title">
                    <p class="eyebrow dark">Admin dashboard</p>
                    <h1>Operations Overview</h1>
                    <p>Monitor bookings, revenue, fleet health, and live activity.</p>
                </div>
                <div class="stats-grid">
                    <?php render_stat('Revenue', lkr($revenue), '+14% this week', 'accent'); ?>
                    <?php render_stat('Bookings', $savedBookings ? (string) count($savedBookings) : '1,402', $savedBookings ? 'Saved locally' : '+8% monthly'); ?>
                    <?php render_stat('Fleet Available', '5/6', '1 in service'); ?>
                    <?php render_stat('Customers', '8,924', '+312 active', 'dark'); ?>
                </div>
                <div class="admin-grid">
                    <section class="panel wide-panel">
                        <div class="section-heading-row"><h2>Recent Bookings</h2><span class="text-link">View all</span></div>
                        <div class="simple-table admin-table">
                            <?php foreach ($adminBookings as $booking): ?>
                                <div>
                                    <span><?= h($booking['id']) ?></span>
                                    <strong><?= h($booking['customerName']) ?></strong>
                                    <span><?= h($booking['vehicleName']) ?></span>
                                    <?php if ($savedBookings): ?>
                                        <form method="post" action="index.php?page=admin">
                                            <input type="hidden" name="action" value="update_status">
                                            <input type="hidden" name="booking_id" value="<?= h($booking['id']) ?>">
                                            <select class="status-select" name="status" onchange="this.form.submit()">
                                                <?php foreach (['Pending', 'Confirmed', 'In progress', 'Completed'] as $status): ?>
                                                    <option value="<?= h($status) ?>" <?= ($booking['status'] ?? '') === $status ? 'selected' : '' ?>><?= h($status) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </form>
                                    <?php else: ?>
                                        <span class="status <?= h(status_class($booking['status'])) ?>"><?= h($booking['status']) ?></span>
                                    <?php endif; ?>
                                    <strong><?= h(lkr((float) ($booking['total'] ?? 0))) ?></strong>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                    <aside class="panel">
                        <h2>Fleet Health</h2>
                        <div class="health-list">
                            <div><span>Available vehicles</span><strong>84%</strong></div>
                            <div><span>Maintenance queue</span><strong>3</strong></div>
                            <div><span>Average utilization</span><strong>72%</strong></div>
                        </div>
                        <div class="progress-track"><span style="width:84%"></span></div>
                    </aside>
                    <section class="panel map-panel">
                        <div><h2>Live Map</h2><p>Active rentals clustered around Colombo, Negombo, and Kandy.</p></div>
                        <div class="map-visual" aria-hidden="true"><span class="pin one"></span><span class="pin two"></span><span class="pin three"></span></div>
                    </section>
                    <section class="panel">
                        <h2>Fleet Updates</h2>
                        <div class="update-list">
                            <p><strong>Tesla Model 3</strong> returned and charging.</p>
                            <p><strong>Audi Q5</strong> inspection completed.</p>
                            <p><strong>Porsche 911</strong> awaiting customer pickup.</p>
                        </div>
                    </section>
                </div>
            </section>

        <?php elseif ($page === 'login' || $page === 'register'): ?>
            <?php $isRegister = $page === 'register'; ?>
            <section class="auth-layout">
                <div class="auth-card">
                    <a class="auth-brand" href="<?= h(app_url()) ?>">RideEasy</a>
                    <div class="auth-tabs">
                        <a class="<?= !$isRegister ? 'active' : '' ?>" href="<?= h(app_url(['page' => 'login'])) ?>">Login</a>
                        <a class="<?= $isRegister ? 'active' : '' ?>" href="<?= h(app_url(['page' => 'register'])) ?>">Register</a>
                    </div>
                    <div class="page-title compact-title">
                        <p class="eyebrow dark"><?= $isRegister ? 'Create account' : 'Welcome back' ?></p>
                        <h1><?= $isRegister ? 'Start renting with RideEasy' : 'Welcome back' ?></h1>
                        <p><?= $isRegister ? 'Save details, manage bookings, and access role-based dashboards.' : 'Sign in to continue your booking or manage fleet operations.' ?></p>
                    </div>
                    <form class="form-stack" method="post" action="index.php?page=<?= h($page) ?>">
                        <input type="hidden" name="action" value="auth">
                        <div class="segmented-control">
                            <label class="role-option"><input type="radio" name="role" value="Customer" checked> Customer</label>
                            <label class="role-option"><input type="radio" name="role" value="Admin"> Admin</label>
                        </div>
                        <?php if ($isRegister): ?>
                            <label>Full name <input name="name" placeholder="Your name" required></label>
                        <?php endif; ?>
                        <label>Email address <input name="email" type="email" value="alex@rideeasy.lk" required></label>
                        <label>Password <input name="password" type="password" placeholder="Enter password" required></label>
                        <button class="button-primary full" type="submit"><?= $isRegister ? 'Create Account' : 'Sign In' ?></button>
                    </form>
                    <p class="auth-switch">
                        <?= $isRegister ? 'Already have an account?' : 'New to RideEasy?' ?>
                        <a href="<?= h(app_url(['page' => $isRegister ? 'login' : 'register'])) ?>"><?= $isRegister ? 'Sign in' : 'Create account' ?></a>
                    </p>
                </div>
            </section>

        <?php elseif ($page === 'contact'): ?>
            <?php $flash = $_SESSION['flash'] ?? ''; unset($_SESSION['flash']); ?>
            <section class="contact-layout">
                <div class="page-title">
                    <p class="eyebrow dark">Support</p>
                    <h1>Contact Our Team</h1>
                    <p>Get help with bookings, vehicle availability, payments, and returns.</p>
                </div>
                <div class="contact-grid">
                    <form class="form-stack wide" method="post" action="index.php?page=contact">
                        <input type="hidden" name="action" value="contact">
                        <h2>Send us a message</h2>
                        <label>Full name <input name="name" placeholder="Your name" required></label>
                        <label>Email <input name="email" type="email" placeholder="you@example.com" required></label>
                        <label>Subject <input name="subject" placeholder="Booking question" required></label>
                        <label>Message <textarea name="message" placeholder="How can we help?" required></textarea></label>
                        <button class="button-primary full" type="submit">Send Message</button>
                        <?php if ($flash): ?><p class="inline-success"><?= h($flash) ?></p><?php endif; ?>
                    </form>
                    <aside class="contact-aside">
                        <section class="panel contact-info">
                            <h2>Contact Information</h2>
                            <p><strong>Hotline</strong> +94 77 123 4567</p>
                            <p><strong>Email</strong> support@rideeasy.lk</p>
                            <p><strong>Office</strong> 24 Marine Drive, Colombo</p>
                            <p><strong>Hours</strong> Daily, 7:00 AM - 10:00 PM</p>
                        </section>
                        <section class="panel">
                            <h2>Frequently Asked Questions</h2>
                            <div class="faq-list">
                                <div class="faq-item"><span>What documents are required?</span><small>A valid driving license, national ID or passport, and a payment method are required.</small></div>
                                <div class="faq-item"><span>Can I extend my booking?</span><small>Yes. Contact support before the return time.</small></div>
                                <div class="faq-item"><span>Do rentals include insurance?</span><small>Basic protection is included with optional premium protection.</small></div>
                            </div>
                        </section>
                    </aside>
                </div>
            </section>
        <?php else: ?>
            <section class="success-panel">
                <h2>Page not found</h2>
                <p>The requested page does not exist.</p>
                <a class="button-primary" href="<?= h(app_url()) ?>">Go Home</a>
            </section>
        <?php endif; ?>
    </main>

    <footer class="site-footer">
        <div>
            <strong>RideEasy</strong>
            <p>Vehicle rental, booking, and fleet operations in one PHP app.</p>
        </div>
        <div class="footer-links"><span>Terms</span><span>Privacy</span><span>Support</span></div>
    </footer>

    <nav class="bottom-nav" aria-label="Mobile navigation">
        <a class="bottom-nav-item <?= h(is_active('home')) ?>" href="<?= h(app_url()) ?>"><span>H</span>Home</a>
        <a class="bottom-nav-item <?= h(is_active('vehicles')) ?>" href="<?= h(app_url(['page' => 'vehicles'])) ?>"><span>V</span>Fleet</a>
        <a class="bottom-nav-item <?= h(is_active('booking')) ?>" href="<?= h(app_url(['page' => 'booking'])) ?>"><span>B</span>Book</a>
        <a class="bottom-nav-item <?= h(is_active('admin')) ?>" href="<?= h(app_url(['page' => 'admin'])) ?>"><span>A</span>Admin</a>
    </nav>
</body>
</html>
