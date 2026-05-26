import { NavLink, Link } from "react-router-dom";

const navItems = [
  { label: "Home", to: "/" },
  { label: "Vehicles", to: "/vehicles" },
  { label: "Booking", to: "/booking" },
  { label: "Customer", to: "/customer" },
  { label: "Admin", to: "/admin" },
  { label: "Contact", to: "/contact" },
];

export default function Navbar() {
  return (
    <header className="site-header">
      <Link className="brand" to="/" aria-label="RideEasy home">
        <span className="brand-mark">R</span>
        <span>RideEasy</span>
      </Link>

      <nav className="nav-links" aria-label="Primary navigation">
        {navItems.map((item) => (
          <NavLink
            className={({ isActive }) => (isActive ? "nav-link active" : "nav-link")}
            key={item.to}
            to={item.to}
          >
            {item.label}
          </NavLink>
        ))}
      </nav>

      <Link className="header-action" to="/login">
        Sign in
      </Link>
    </header>
  );
}
