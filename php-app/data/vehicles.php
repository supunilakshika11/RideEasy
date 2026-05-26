<?php

declare(strict_types=1);

function image_url(string $id): string
{
    return "https://images.unsplash.com/{$id}?auto=format&fit=crop&w=1200&q=80";
}

function vehicles(): array
{
    return [
        [
            'id' => 'tesla-model-3',
            'name' => 'Tesla Model 3',
            'category' => 'Electric',
            'tagline' => 'Silent, quick, and efficient for modern city travel.',
            'description' => 'A premium electric sedan with instant torque, a refined cabin, and long-range comfort for work trips or weekend escapes.',
            'pricePerDay' => 27900,
            'rating' => 4.9,
            'reviews' => 126,
            'seats' => 5,
            'transmission' => 'Automatic',
            'fuelType' => 'Electric',
            'range' => '480 km',
            'luggage' => '3 bags',
            'badge' => 'Top rated',
            'image' => image_url('photo-1617788138017-80ad40651399'),
            'gallery' => [
                image_url('photo-1617788138017-80ad40651399'),
                image_url('photo-1560958089-b8a1929cea89'),
                image_url('photo-1533473359331-0135ef1b58bf'),
            ],
            'features' => ['Autopilot assist', 'Glass roof', 'Fast charging', 'Premium audio'],
            'specs' => [
                'Engine' => 'Dual motor electric',
                'Acceleration' => '0-100 km/h in 4.4s',
                'Top speed' => '225 km/h',
                'Mileage' => '14.7 kWh / 100 km',
            ],
        ],
        [
            'id' => 'bmw-m4',
            'name' => 'BMW M4 Competition',
            'category' => 'Sports',
            'tagline' => 'Track-inspired performance with executive comfort.',
            'description' => 'A sharp coupe for drivers who want precise handling, strong acceleration, and a premium cockpit.',
            'pricePerDay' => 38500,
            'rating' => 4.8,
            'reviews' => 92,
            'seats' => 4,
            'transmission' => 'Automatic',
            'fuelType' => 'Petrol',
            'range' => '610 km',
            'luggage' => '2 bags',
            'badge' => 'Premium',
            'image' => image_url('photo-1555215695-3004980ad54e'),
            'gallery' => [
                image_url('photo-1555215695-3004980ad54e'),
                image_url('photo-1556800572-1b8aeef2c54f'),
                image_url('photo-1503376780353-7e6692767b70'),
            ],
            'features' => ['Sport exhaust', 'Adaptive suspension', 'Carbon trim', 'Launch control'],
            'specs' => [
                'Engine' => '3.0L twin-turbo I6',
                'Acceleration' => '0-100 km/h in 3.9s',
                'Top speed' => '290 km/h',
                'Mileage' => '10.2 km / L',
            ],
        ],
        [
            'id' => 'audi-q5',
            'name' => 'Audi Q5',
            'category' => 'SUV',
            'tagline' => 'A refined SUV for families, business, and airport transfers.',
            'description' => 'Comfortable seating, generous cargo room, and confident road manners make this SUV an easy all-rounder.',
            'pricePerDay' => 24200,
            'rating' => 4.7,
            'reviews' => 118,
            'seats' => 5,
            'transmission' => 'Automatic',
            'fuelType' => 'Hybrid',
            'range' => '720 km',
            'luggage' => '5 bags',
            'image' => image_url('photo-1606664515524-ed2f786a0bd6'),
            'gallery' => [
                image_url('photo-1606664515524-ed2f786a0bd6'),
                image_url('photo-1609521263047-f8f205293f24'),
                image_url('photo-1542362567-b07e54358753'),
            ],
            'features' => ['Quattro AWD', 'Panoramic roof', 'Child seat ready', 'Lane assist'],
            'specs' => [
                'Engine' => '2.0L turbo hybrid',
                'Acceleration' => '0-100 km/h in 6.1s',
                'Top speed' => '237 km/h',
                'Mileage' => '16.8 km / L',
            ],
        ],
        [
            'id' => 'range-rover-sport',
            'name' => 'Range Rover Sport',
            'category' => 'Luxury',
            'tagline' => 'Luxury presence with real long-distance comfort.',
            'description' => 'A flagship SUV experience with a calm cabin, flexible drive modes, and space for important journeys.',
            'pricePerDay' => 46800,
            'rating' => 4.9,
            'reviews' => 84,
            'seats' => 5,
            'transmission' => 'Automatic',
            'fuelType' => 'Diesel',
            'range' => '760 km',
            'luggage' => '5 bags',
            'badge' => 'Executive',
            'image' => image_url('photo-1606016159991-dfe4f2746ad5'),
            'gallery' => [
                image_url('photo-1606016159991-dfe4f2746ad5'),
                image_url('photo-1549927681-0b673b8243ab'),
                image_url('photo-1535732820275-9ffd998cac22'),
            ],
            'features' => ['Air suspension', 'Massage seats', 'Terrain response', '360 camera'],
            'specs' => [
                'Engine' => '3.0L diesel mild hybrid',
                'Acceleration' => '0-100 km/h in 6.6s',
                'Top speed' => '234 km/h',
                'Mileage' => '13.5 km / L',
            ],
        ],
        [
            'id' => 'porsche-911',
            'name' => 'Porsche 911 Carrera',
            'category' => 'Sports',
            'tagline' => 'A timeless sports car for special drives.',
            'description' => 'Iconic design, balanced handling, and a driver-focused interior make the 911 a memorable rental.',
            'pricePerDay' => 59500,
            'rating' => 4.9,
            'reviews' => 73,
            'seats' => 4,
            'transmission' => 'Automatic',
            'fuelType' => 'Petrol',
            'range' => '590 km',
            'luggage' => '1 bag',
            'badge' => 'Limited',
            'image' => image_url('photo-1503736334956-4c8f8e92946d'),
            'gallery' => [
                image_url('photo-1503736334956-4c8f8e92946d'),
                image_url('photo-1544636331-e26879cd4d9b'),
                image_url('photo-1525609004556-c46c7d6cf023'),
            ],
            'features' => ['Sport chrono', 'Heated seats', 'Bose audio', 'Ceramic brakes'],
            'specs' => [
                'Engine' => '3.0L twin-turbo flat-six',
                'Acceleration' => '0-100 km/h in 4.2s',
                'Top speed' => '293 km/h',
                'Mileage' => '9.8 km / L',
            ],
        ],
        [
            'id' => 'polestar-2',
            'name' => 'Polestar 2',
            'category' => 'Electric',
            'tagline' => 'Clean Scandinavian design with confident electric range.',
            'description' => 'A practical electric fastback with a premium cabin, strong safety tech, and efficient range.',
            'pricePerDay' => 25500,
            'rating' => 4.6,
            'reviews' => 67,
            'seats' => 5,
            'transmission' => 'Automatic',
            'fuelType' => 'Electric',
            'range' => '520 km',
            'luggage' => '4 bags',
            'image' => image_url('photo-1619767886558-efdc259cde1a'),
            'gallery' => [
                image_url('photo-1619767886558-efdc259cde1a'),
                image_url('photo-1597007066704-67bf2068d5b6'),
                image_url('photo-1592853625601-bb9d23da12fc'),
            ],
            'features' => ['Google built-in', 'Pilot pack', 'Vegan interior', 'Rapid charge'],
            'specs' => [
                'Engine' => 'Single motor electric',
                'Acceleration' => '0-100 km/h in 7.4s',
                'Top speed' => '205 km/h',
                'Mileage' => '16.1 kWh / 100 km',
            ],
        ],
    ];
}

function categories(): array
{
    return ['All', 'Sedan', 'SUV', 'Electric', 'Luxury', 'Sports'];
}

function get_vehicle(?string $id): array
{
    foreach (vehicles() as $vehicle) {
        if ($vehicle['id'] === $id) {
            return $vehicle;
        }
    }

    return vehicles()[0];
}

function demo_recent_bookings(): array
{
    return [
        ['id' => 'BK-2184', 'customerName' => 'Sarah Lee', 'vehicleName' => 'Tesla Model 3', 'status' => 'Confirmed', 'total' => 83700],
        ['id' => 'BK-2183', 'customerName' => 'Nimal Perera', 'vehicleName' => 'Audi Q5', 'status' => 'In progress', 'total' => 48400],
        ['id' => 'BK-2182', 'customerName' => 'Maya Chen', 'vehicleName' => 'Porsche 911', 'status' => 'Pending', 'total' => 119000],
        ['id' => 'BK-2181', 'customerName' => 'Alex Morgan', 'vehicleName' => 'Range Rover Sport', 'status' => 'Completed', 'total' => 140400],
    ];
}
