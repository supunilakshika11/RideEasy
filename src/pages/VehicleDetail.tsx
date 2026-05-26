import { useState } from "react";
import { Link, useParams } from "react-router-dom";
import { getVehicleById } from "../data/vehicles";
import { formatLkr } from "../utils/format";

export default function VehicleDetail() {
  const { vehicleId } = useParams();
  const vehicle = getVehicleById(vehicleId);
  const [selectedImage, setSelectedImage] = useState(vehicle.gallery[0]);

  return (
    <section className="detail-layout">
      <div>
        <div className="detail-gallery">
          <img className="detail-main-image" src={selectedImage} alt={vehicle.name} />
          <div className="thumbnail-row">
            {vehicle.gallery.map((image) => (
              <button
                className={image === selectedImage ? "thumbnail active" : "thumbnail"}
                key={image}
                onClick={() => setSelectedImage(image)}
                type="button"
              >
                <img src={image} alt={`${vehicle.name} gallery`} />
              </button>
            ))}
          </div>
        </div>

        <div className="detail-section">
          <p className="eyebrow dark">{vehicle.category}</p>
          <h1>{vehicle.name}</h1>
          <p>{vehicle.description}</p>

          <div className="spec-card-grid">
            <div>
              <span>Seats</span>
              <strong>{vehicle.seats}</strong>
            </div>
            <div>
              <span>Range</span>
              <strong>{vehicle.range}</strong>
            </div>
            <div>
              <span>Fuel</span>
              <strong>{vehicle.fuelType}</strong>
            </div>
            <div>
              <span>Luggage</span>
              <strong>{vehicle.luggage}</strong>
            </div>
          </div>
        </div>

        <div className="detail-section">
          <h2>Technical Specifications</h2>
          <div className="info-list">
            {Object.entries(vehicle.specs).map(([label, value]) => (
              <div key={label}>
                <span>{label}</span>
                <strong>{value}</strong>
              </div>
            ))}
          </div>
        </div>

        <div className="detail-section">
          <h2>Premium Features</h2>
          <div className="feature-list">
            {vehicle.features.map((feature) => (
              <span key={feature}>{feature}</span>
            ))}
          </div>
        </div>

        <div className="detail-section">
          <h2>Customer Reviews</h2>
          <div className="review-card">
            <strong>Excellent condition and smooth handover.</strong>
            <p>
              The car was spotless, fully charged, and the booking team confirmed
              every detail before pickup.
            </p>
            <span>Star {vehicle.rating.toFixed(1)} from {vehicle.reviews} reviews</span>
          </div>
        </div>
      </div>

      <aside className="booking-card">
        <span className="availability-dot">Available today</span>
        <h2>{formatLkr(vehicle.pricePerDay)} <small>/ day</small></h2>
        <p>{vehicle.tagline}</p>
        <div className="info-list compact">
          <div>
            <span>Transmission</span>
            <strong>{vehicle.transmission}</strong>
          </div>
          <div>
            <span>Rating</span>
            <strong>{vehicle.rating.toFixed(1)} / 5</strong>
          </div>
        </div>
        <Link className="button-primary full" to={`/booking/${vehicle.id}`}>
          Reserve This Vehicle
        </Link>
        <Link className="button-secondary full" to="/vehicles">
          Back to Fleet
        </Link>
      </aside>
    </section>
  );
}
