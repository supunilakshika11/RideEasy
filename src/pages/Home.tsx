import { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import VehicleCard from "../components/VehicleCard";
import { categories, vehicles } from "../data/vehicles";

const heroImage =
  "https://images.unsplash.com/photo-1502877338535-766e1452684a?auto=format&fit=crop&w=1600&q=80";

export default function Home() {
  const [location, setLocation] = useState("Colombo");
  const [pickupDate, setPickupDate] = useState("2026-05-28");
  const navigate = useNavigate();

  const handleSearch = () => {
    const params = new URLSearchParams({
      location,
      pickup: pickupDate,
    });

    navigate(`/vehicles?${params.toString()}`);
  };

  return (
    <>
      <section
        className="hero"
        style={{
          backgroundImage: `linear-gradient(90deg, rgba(8, 25, 50, 0.84), rgba(8, 25, 50, 0.4)), url(${heroImage})`,
        }}
      >
        <div className="hero-content">
          <p className="eyebrow">Premium vehicle rentals</p>
          <h1>
            Find your drive, <span>own the journey.</span>
          </h1>
          <p>
            Book clean, verified vehicles for city errands, airport transfers,
            business travel, and weekend escapes.
          </p>

          <div className="hero-actions">
            <Link className="button-primary" to="/vehicles">
              Explore Fleet
            </Link>
            <Link className="button-secondary light" to="/contact">
              Contact Support
            </Link>
          </div>
        </div>

        <form className="search-panel" onSubmit={(event) => event.preventDefault()}>
          <label>
            Location
            <input
              value={location}
              onChange={(event) => setLocation(event.target.value)}
              placeholder="Pickup city"
            />
          </label>
          <label>
            Pick-up
            <input
              type="date"
              value={pickupDate}
              onChange={(event) => setPickupDate(event.target.value)}
            />
          </label>
          <label>
            Drop-off
            <input type="date" defaultValue="2026-05-31" />
          </label>
          <button className="button-primary" onClick={handleSearch} type="button">
            Search Vehicles
          </button>
        </form>
      </section>

      <section className="section-block">
        <div className="section-heading-row">
          <div>
            <p className="eyebrow dark">Browse by category</p>
            <h2>Pick a vehicle for every plan</h2>
          </div>
          <Link className="text-link" to="/vehicles">
            View all
          </Link>
        </div>

        <div className="category-grid">
          {categories.slice(1).map((category) => {
            const vehicle = vehicles.find((item) => item.category === category) ?? vehicles[0];

            return (
              <Link
                className="category-tile"
                key={category}
                to={`/vehicles?category=${category}`}
              >
                <img src={vehicle.image} alt={`${category} vehicle`} />
                <span>{category}</span>
              </Link>
            );
          })}
        </div>
      </section>

      <section className="section-block">
        <div className="section-heading-row">
          <div>
            <p className="eyebrow dark">Premium fleet</p>
            <h2>Popular cars ready now</h2>
          </div>
          <Link className="text-link" to="/vehicles">
            Compare fleet
          </Link>
        </div>

        <div className="vehicle-grid">
          {vehicles.slice(0, 3).map((vehicle) => (
            <VehicleCard key={vehicle.id} vehicle={vehicle} />
          ))}
        </div>
      </section>

      <section className="how-section">
        <div className="section-heading-row centered">
          <div>
            <p className="eyebrow dark">How it works</p>
            <h2>Book in three clean steps</h2>
          </div>
        </div>

        <div className="steps-grid">
          {[
            ["1", "Browse and choose", "Filter by category, price, seats, and travel style."],
            ["2", "Instant booking", "Confirm trip details and lock in transparent pricing."],
            ["3", "Pick up and drive", "Collect your vehicle with digital booking support."],
          ].map(([number, title, copy]) => (
            <article className="step-card" key={title}>
              <span>{number}</span>
              <h3>{title}</h3>
              <p>{copy}</p>
            </article>
          ))}
        </div>
      </section>

      <section className="cta-band">
        <div>
          <h2>Need a car today?</h2>
          <p>Our support team can help you match a vehicle to your route and budget.</p>
        </div>
        <Link className="button-primary" to="/booking">
          Rent Now
        </Link>
      </section>
    </>
  );
}
