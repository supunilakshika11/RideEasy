export type VehicleCategory = "Sedan" | "SUV" | "Electric" | "Luxury" | "Sports";

export type Vehicle = {
  id: string;
  name: string;
  category: VehicleCategory;
  tagline: string;
  description: string;
  pricePerDay: number;
  rating: number;
  reviews: number;
  seats: number;
  transmission: "Automatic" | "Manual";
  fuelType: "Petrol" | "Diesel" | "Hybrid" | "Electric";
  range: string;
  luggage: string;
  image: string;
  gallery: string[];
  badge?: string;
  features: string[];
  specs: {
    engine: string;
    acceleration: string;
    topSpeed: string;
    mileage: string;
  };
};

const image = (id: string) =>
  `https://images.unsplash.com/${id}?auto=format&fit=crop&w=1200&q=80`;

export const vehicles: Vehicle[] = [
  {
    id: "tesla-model-3",
    name: "Tesla Model 3",
    category: "Electric",
    tagline: "Silent, quick, and efficient for modern city travel.",
    description:
      "A premium electric sedan with instant torque, a refined cabin, and long-range comfort for work trips or weekend escapes.",
    pricePerDay: 27900,
    rating: 4.9,
    reviews: 126,
    seats: 5,
    transmission: "Automatic",
    fuelType: "Electric",
    range: "480 km",
    luggage: "3 bags",
    image: image("photo-1617788138017-80ad40651399"),
    gallery: [
      image("photo-1617788138017-80ad40651399"),
      image("photo-1560958089-b8a1929cea89"),
      image("photo-1533473359331-0135ef1b58bf"),
    ],
    badge: "Top rated",
    features: ["Autopilot assist", "Glass roof", "Fast charging", "Premium audio"],
    specs: {
      engine: "Dual motor electric",
      acceleration: "0-100 km/h in 4.4s",
      topSpeed: "225 km/h",
      mileage: "14.7 kWh / 100 km",
    },
  },
  {
    id: "bmw-m4",
    name: "BMW M4 Competition",
    category: "Sports",
    tagline: "Track-inspired performance with executive comfort.",
    description:
      "A sharp coupe for drivers who want precise handling, strong acceleration, and a premium cockpit.",
    pricePerDay: 38500,
    rating: 4.8,
    reviews: 92,
    seats: 4,
    transmission: "Automatic",
    fuelType: "Petrol",
    range: "610 km",
    luggage: "2 bags",
    image: image("photo-1555215695-3004980ad54e"),
    gallery: [
      image("photo-1555215695-3004980ad54e"),
      image("photo-1556800572-1b8aeef2c54f"),
      image("photo-1503376780353-7e6692767b70"),
    ],
    badge: "Premium",
    features: ["Sport exhaust", "Adaptive suspension", "Carbon trim", "Launch control"],
    specs: {
      engine: "3.0L twin-turbo I6",
      acceleration: "0-100 km/h in 3.9s",
      topSpeed: "290 km/h",
      mileage: "10.2 km / L",
    },
  },
  {
    id: "audi-q5",
    name: "Audi Q5",
    category: "SUV",
    tagline: "A refined SUV for families, business, and airport transfers.",
    description:
      "Comfortable seating, generous cargo room, and confident road manners make this SUV an easy all-rounder.",
    pricePerDay: 24200,
    rating: 4.7,
    reviews: 118,
    seats: 5,
    transmission: "Automatic",
    fuelType: "Hybrid",
    range: "720 km",
    luggage: "5 bags",
    image: image("photo-1606664515524-ed2f786a0bd6"),
    gallery: [
      image("photo-1606664515524-ed2f786a0bd6"),
      image("photo-1609521263047-f8f205293f24"),
      image("photo-1542362567-b07e54358753"),
    ],
    features: ["Quattro AWD", "Panoramic roof", "Child seat ready", "Lane assist"],
    specs: {
      engine: "2.0L turbo hybrid",
      acceleration: "0-100 km/h in 6.1s",
      topSpeed: "237 km/h",
      mileage: "16.8 km / L",
    },
  },
  {
    id: "range-rover-sport",
    name: "Range Rover Sport",
    category: "Luxury",
    tagline: "Luxury presence with real long-distance comfort.",
    description:
      "A flagship SUV experience with a calm cabin, flexible drive modes, and space for important journeys.",
    pricePerDay: 46800,
    rating: 4.9,
    reviews: 84,
    seats: 5,
    transmission: "Automatic",
    fuelType: "Diesel",
    range: "760 km",
    luggage: "5 bags",
    image: image("photo-1606016159991-dfe4f2746ad5"),
    gallery: [
      image("photo-1606016159991-dfe4f2746ad5"),
      image("photo-1549927681-0b673b8243ab"),
      image("photo-1535732820275-9ffd998cac22"),
    ],
    badge: "Executive",
    features: ["Air suspension", "Massage seats", "Terrain response", "360 camera"],
    specs: {
      engine: "3.0L diesel mild hybrid",
      acceleration: "0-100 km/h in 6.6s",
      topSpeed: "234 km/h",
      mileage: "13.5 km / L",
    },
  },
  {
    id: "porsche-911",
    name: "Porsche 911 Carrera",
    category: "Sports",
    tagline: "A timeless sports car for special drives.",
    description:
      "Iconic design, balanced handling, and a driver-focused interior make the 911 a memorable rental.",
    pricePerDay: 59500,
    rating: 4.9,
    reviews: 73,
    seats: 4,
    transmission: "Automatic",
    fuelType: "Petrol",
    range: "590 km",
    luggage: "1 bag",
    image: image("photo-1503736334956-4c8f8e92946d"),
    gallery: [
      image("photo-1503736334956-4c8f8e92946d"),
      image("photo-1544636331-e26879cd4d9b"),
      image("photo-1525609004556-c46c7d6cf023"),
    ],
    badge: "Limited",
    features: ["Sport chrono", "Heated seats", "Bose audio", "Ceramic brakes"],
    specs: {
      engine: "3.0L twin-turbo flat-six",
      acceleration: "0-100 km/h in 4.2s",
      topSpeed: "293 km/h",
      mileage: "9.8 km / L",
    },
  },
  {
    id: "polestar-2",
    name: "Polestar 2",
    category: "Electric",
    tagline: "Clean Scandinavian design with confident electric range.",
    description:
      "A practical electric fastback with a premium cabin, strong safety tech, and efficient range.",
    pricePerDay: 25500,
    rating: 4.6,
    reviews: 67,
    seats: 5,
    transmission: "Automatic",
    fuelType: "Electric",
    range: "520 km",
    luggage: "4 bags",
    image: image("photo-1619767886558-efdc259cde1a"),
    gallery: [
      image("photo-1619767886558-efdc259cde1a"),
      image("photo-1597007066704-67bf2068d5b6"),
      image("photo-1592853625601-bb9d23da12fc"),
    ],
    features: ["Google built-in", "Pilot pack", "Vegan interior", "Rapid charge"],
    specs: {
      engine: "Single motor electric",
      acceleration: "0-100 km/h in 7.4s",
      topSpeed: "205 km/h",
      mileage: "16.1 kWh / 100 km",
    },
  },
];

export const categories: Array<"All" | VehicleCategory> = [
  "All",
  "Sedan",
  "SUV",
  "Electric",
  "Luxury",
  "Sports",
];

export const recentBookings = [
  { id: "BK-2184", customer: "Sarah Lee", vehicle: "Tesla Model 3", status: "Confirmed", amount: 83700 },
  { id: "BK-2183", customer: "Nimal Perera", vehicle: "Audi Q5", status: "In progress", amount: 48400 },
  { id: "BK-2182", customer: "Maya Chen", vehicle: "Porsche 911", status: "Pending", amount: 119000 },
  { id: "BK-2181", customer: "Alex Morgan", vehicle: "Range Rover Sport", status: "Completed", amount: 140400 },
];

export const getVehicleById = (id: string | undefined) =>
  vehicles.find((vehicle) => vehicle.id === id) ?? vehicles[0];
