import { Link } from "react-router-dom";
import type { Vehicle } from "../data/vehicles";
import { formatLkr } from "../utils/format";

type VehicleCardProps = {
  vehicle: Vehicle;
  compact?: boolean;
};

export default function VehicleCard({ vehicle, compact = false }: VehicleCardProps) {
  return (
    <article className={compact ? "vehicle-card compact" : "vehicle-card"}>
      <Link className="vehicle-media" to={`/vehicles/${vehicle.id}`}>
        <img src={vehicle.image} alt={vehicle.name} />
        {vehicle.badge && <span className="vehicle-badge">{vehicle.badge}</span>}
      </Link>

      <div className="vehicle-card-body">
        <div className="vehicle-title-row">
          <div>
            <p className="vehicle-type">{vehicle.category}</p>
            <h3>{vehicle.name}</h3>
          </div>
          <span className="rating">Star {vehicle.rating.toFixed(1)}</span>
        </div>

        {!compact && <p className="vehicle-copy">{vehicle.tagline}</p>}

        <div className="spec-row">
          <span>{vehicle.seats} seats</span>
          <span>{vehicle.transmission}</span>
          <span>{vehicle.fuelType}</span>
        </div>

        <div className="vehicle-card-footer">
          <div>
            <strong>{formatLkr(vehicle.pricePerDay)}</strong>
            <span>/ day</span>
          </div>
          <Link className="button-primary small" to={`/booking/${vehicle.id}`}>
            Rent Now
          </Link>
        </div>
      </div>
    </article>
  );
}
