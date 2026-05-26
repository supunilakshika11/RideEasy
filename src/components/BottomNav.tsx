import { NavLink } from "react-router-dom";

const items = [
  { label: "Home", to: "/", icon: "H" },
  { label: "Fleet", to: "/vehicles", icon: "V" },
  { label: "Book", to: "/booking", icon: "B" },
  { label: "Admin", to: "/admin", icon: "A" },
];

export default function BottomNav() {
  return (
    <nav className="bottom-nav" aria-label="Mobile navigation">
      {items.map((item) => (
        <NavLink
          className={({ isActive }) =>
            isActive ? "bottom-nav-item active" : "bottom-nav-item"
          }
          key={item.to}
          to={item.to}
        >
          <span>{item.icon}</span>
          {item.label}
        </NavLink>
      ))}
    </nav>
  );
}
