import { useMemo, useState } from "react";
import { Link, useParams } from "react-router-dom";
import { getVehicleById } from "../data/vehicles";
import { makeBookingId, saveBooking } from "../utils/bookings";
import { formatLkr } from "../utils/format";

export default function Booking() {
  const { vehicleId } = useParams();
  const vehicle = getVehicleById(vehicleId);
  const [pickupDate, setPickupDate] = useState("2026-05-28");
  const [returnDate, setReturnDate] = useState("2026-05-31");
  const [pickupLocation, setPickupLocation] = useState("Colombo Fort");
  const [customerName, setCustomerName] = useState("");
  const [email, setEmail] = useState("");
  const [phone, setPhone] = useState("");
  const [license, setLicense] = useState("");
  const [confirmed, setConfirmed] = useState(false);
  const [bookingCode, setBookingCode] = useState("");

  const days = useMemo(() => {
    const start = new Date(pickupDate).getTime();
    const end = new Date(returnDate).getTime();
    const diff = Math.ceil((end - start) / 86400000);
    return Math.max(1, diff || 1);
  }, [pickupDate, returnDate]);

  const subtotal = vehicle.pricePerDay * days;
  const protection = Math.round(subtotal * 0.08);
  const serviceFee = 2500;
  const total = subtotal + protection + serviceFee;
  const canConfirm = customerName && email && phone && license && pickupLocation;

  const confirmBooking = () => {
    if (!canConfirm) return;

    const nextBookingCode = makeBookingId(vehicle.id);
    saveBooking({
      id: nextBookingCode,
      vehicleId: vehicle.id,
      vehicleName: vehicle.name,
      vehicleImage: vehicle.image,
      customerName,
      email,
      phone,
      license,
      pickupDate,
      returnDate,
      pickupLocation,
      days,
      dailyRate: vehicle.pricePerDay,
      total,
      status: "Confirmed",
      createdAt: new Date().toISOString(),
    });

    localStorage.setItem("rideeasy-user", customerName);
    setBookingCode(nextBookingCode);
    setConfirmed(true);
  };

  return (
    <section className="booking-layout">
      <div className="page-title">
        <p className="eyebrow dark">Booking</p>
        <h1>Complete Your Reservation</h1>
        <p>Confirm trip details, renter information, and transparent pricing.</p>
      </div>

      {confirmed ? (
        <div className="success-panel">
          <h2>Booking Confirmed</h2>
          <p>
            Your reservation for <strong>{vehicle.name}</strong> is ready. Use
            booking code <strong>{bookingCode}</strong> at pickup.
          </p>
          <div className="success-actions">
            <Link className="button-primary" to="/customer">
              Open Customer Dashboard
            </Link>
            <Link className="button-secondary" to="/vehicles">
              Browse More Vehicles
            </Link>
          </div>
        </div>
      ) : (
        <div className="booking-grid">
          <form className="form-stack wide" onSubmit={(event) => event.preventDefault()}>
            <section className="form-section">
              <h2>Trip Details</h2>
              <div className="two-column">
                <label>
                  Pick-up date
                  <input
                    type="date"
                    value={pickupDate}
                    onChange={(event) => setPickupDate(event.target.value)}
                  />
                </label>
                <label>
                  Return date
                  <input
                    type="date"
                    value={returnDate}
                    onChange={(event) => setReturnDate(event.target.value)}
                  />
                </label>
              </div>
              <label>
                Pick-up location
                <input
                  value={pickupLocation}
                  onChange={(event) => setPickupLocation(event.target.value)}
                  placeholder="Pickup branch"
                />
              </label>
            </section>

            <section className="form-section">
              <h2>Renter Information</h2>
              <label>
                Full name
                <input
                  value={customerName}
                  onChange={(event) => setCustomerName(event.target.value)}
                  placeholder="Full name"
                />
              </label>
              <div className="two-column">
                <label>
                  Email
                  <input
                    type="email"
                    value={email}
                    onChange={(event) => setEmail(event.target.value)}
                    placeholder="you@example.com"
                  />
                </label>
                <label>
                  Phone
                  <input
                    value={phone}
                    onChange={(event) => setPhone(event.target.value.replace(/\D/g, ""))}
                    placeholder="0771234567"
                  />
                </label>
              </div>
              <label>
                Driving license number
                <input
                  value={license}
                  onChange={(event) => setLicense(event.target.value)}
                  placeholder="License ID"
                />
              </label>
            </section>

            <button
              className="button-primary full"
              disabled={!canConfirm}
              onClick={confirmBooking}
              type="button"
            >
              Confirm Booking
            </button>
          </form>

          <aside className="booking-card">
            <img className="summary-image" src={vehicle.image} alt={vehicle.name} />
            <h2>{vehicle.name}</h2>
            <p>{vehicle.tagline}</p>
            <div className="info-list compact">
              <div>
                <span>Days</span>
                <strong>{days}</strong>
              </div>
              <div>
                <span>Daily rate</span>
                <strong>{formatLkr(vehicle.pricePerDay)}</strong>
              </div>
              <div>
                <span>Protection</span>
                <strong>{formatLkr(protection)}</strong>
              </div>
              <div>
                <span>Service fee</span>
                <strong>{formatLkr(serviceFee)}</strong>
              </div>
              <div className="total-row">
                <span>Total</span>
                <strong>{formatLkr(total)}</strong>
              </div>
            </div>
          </aside>
        </div>
      )}
    </section>
  );
}
