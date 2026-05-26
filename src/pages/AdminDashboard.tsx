import StatCard from "../components/StatCard";
import { recentBookings, vehicles } from "../data/vehicles";
import { getBookings } from "../utils/bookings";
import { formatLkr } from "../utils/format";

export default function AdminDashboard() {
  const savedBookings = getBookings();
  const dashboardBookings = savedBookings.length
    ? savedBookings.map((booking) => ({
        id: booking.id,
        customer: booking.customerName,
        vehicle: booking.vehicleName,
        status: booking.status,
        amount: booking.total,
      }))
    : recentBookings;
  const revenue = dashboardBookings.reduce((sum, booking) => sum + booking.amount, 0);
  const availableFleet = vehicles.length - 1;

  return (
    <section className="dashboard-page">
      <div className="page-title">
        <p className="eyebrow dark">Admin dashboard</p>
        <h1>Operations Overview</h1>
        <p>Monitor bookings, revenue, fleet health, and live activity.</p>
      </div>

      <div className="stats-grid">
        <StatCard label="Revenue" value={formatLkr(revenue)} trend="+14% this week" tone="accent" />
        <StatCard
          label="Bookings"
          value={savedBookings.length ? String(savedBookings.length) : "1,402"}
          trend={savedBookings.length ? "Saved locally" : "+8% monthly"}
        />
        <StatCard label="Fleet Available" value={`${availableFleet}/${vehicles.length}`} trend="1 in service" />
        <StatCard label="Customers" value="8,924" trend="+312 active" tone="dark" />
      </div>

      <div className="admin-grid">
        <section className="panel wide-panel">
          <div className="section-heading-row">
            <h2>Recent Bookings</h2>
            <span className="text-link">View all</span>
          </div>
          <div className="simple-table">
            {dashboardBookings.map((booking) => (
              <div key={booking.id}>
                <span>{booking.id}</span>
                <strong>{booking.customer}</strong>
                <span>{booking.vehicle}</span>
                <span className={`status ${booking.status.toLowerCase().replace(" ", "-")}`}>
                  {booking.status}
                </span>
                <strong>{formatLkr(booking.amount)}</strong>
              </div>
            ))}
          </div>
        </section>

        <aside className="panel">
          <h2>Fleet Health</h2>
          <div className="health-list">
            <div>
              <span>Available vehicles</span>
              <strong>84%</strong>
            </div>
            <div>
              <span>Maintenance queue</span>
              <strong>3</strong>
            </div>
            <div>
              <span>Average utilization</span>
              <strong>72%</strong>
            </div>
          </div>
          <div className="progress-track">
            <span style={{ width: "84%" }} />
          </div>
        </aside>

        <section className="panel map-panel">
          <div>
            <h2>Live Map</h2>
            <p>Active rentals clustered around Colombo, Negombo, and Kandy.</p>
          </div>
          <div className="map-visual" aria-hidden="true">
            <span className="pin one" />
            <span className="pin two" />
            <span className="pin three" />
          </div>
        </section>

        <section className="panel">
          <h2>Fleet Updates</h2>
          <div className="update-list">
            <p><strong>Tesla Model 3</strong> returned and charging.</p>
            <p><strong>Audi Q5</strong> inspection completed.</p>
            <p><strong>Porsche 911</strong> awaiting customer pickup.</p>
          </div>
        </section>
      </div>
    </section>
  );
}
