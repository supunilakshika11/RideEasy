import { Link } from "react-router-dom";
import VehicleCard from "../components/VehicleCard";
import { vehicles } from "../data/vehicles";
import { getBookings, type BookingRecord } from "../utils/bookings";
import { formatLkr } from "../utils/format";

const demoBookings: BookingRecord[] = [
  {
    id: "RE-TES-3821",
    vehicleId: "tesla-model-3",
    vehicleName: "Tesla Model 3",
    vehicleImage: vehicles[0].image,
    customerName: "Alex",
    email: "alex@rideeasy.lk",
    phone: "0771234567",
    license: "B1234567",
    pickupDate: "2026-05-28",
    returnDate: "2026-05-31",
    pickupLocation: "Colombo Fort",
    days: 3,
    dailyRate: vehicles[0].pricePerDay,
    total: 86200,
    status: "Confirmed",
    createdAt: "2026-05-25T08:30:00.000Z",
  },
];

export default function CustomerDashboard() {
  const user = localStorage.getItem("rideeasy-user") ?? "Alex";
  const bookings = getBookings();
  const visibleBookings = bookings.length ? bookings : demoBookings;
  const activeBooking = visibleBookings[0];

  return (
    <section className="dashboard-page">
      <div className="dashboard-hero">
        <div>
          <p className="eyebrow">Customer dashboard</p>
          <h1>Welcome back, {user}</h1>
          <p>Your next rental, booking history, and recommended vehicles are ready.</p>
        </div>
        <Link className="button-primary" to="/vehicles">
          New Booking
        </Link>
      </div>

      <div className="dashboard-grid">
        <article className="active-booking-card">
          <span className="vehicle-badge">Active booking</span>
          <img src={activeBooking.vehicleImage} alt={activeBooking.vehicleName} />
          <h2>{activeBooking.vehicleName}</h2>
          <div className="info-list compact">
            <div>
              <span>Pickup</span>
              <strong>{activeBooking.pickupDate}</strong>
            </div>
            <div>
              <span>Total</span>
              <strong>{formatLkr(activeBooking.total)}</strong>
            </div>
          </div>
          <Link className="button-secondary full" to={`/booking/${activeBooking.vehicleId}`}>
            Manage Reservation
          </Link>
        </article>

        <section className="panel">
          <div className="section-heading-row">
            <h2>Booking History</h2>
            <Link className="text-link" to="/vehicles">
              View all
            </Link>
          </div>
          <div className="simple-table">
            {visibleBookings.map((booking) => (
              <div key={booking.id}>
                <span>{booking.id}</span>
                <strong>{booking.vehicleName}</strong>
                <span>{booking.pickupDate}</span>
                <span className={`status ${booking.status.toLowerCase()}`}>
                  {booking.status}
                </span>
              </div>
            ))}
          </div>
        </section>

        <section className="panel loyalty-card">
          <h2>RideEasy Plus</h2>
          <p>Gold member</p>
          <strong>1,250 points</strong>
          <span>Next reward unlocks at 1,500 points.</span>
        </section>
      </div>

      <section className="section-block flush">
        <div className="section-heading-row">
          <h2>Recommended For You</h2>
          <Link className="text-link" to="/vehicles">
            Explore
          </Link>
        </div>
        <div className="vehicle-grid">
          {vehicles.slice(1, 4).map((vehicle) => (
            <VehicleCard compact key={vehicle.id} vehicle={vehicle} />
          ))}
        </div>
      </section>
    </section>
  );
}
