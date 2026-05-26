import { useState } from "react";
import { Link, useNavigate } from "react-router-dom";

type AuthProps = {
  mode: "login" | "register";
};

export default function Auth({ mode }: AuthProps) {
  const [role, setRole] = useState<"Customer" | "Admin">("Customer");
  const [name, setName] = useState("");
  const [email, setEmail] = useState("alex@rideeasy.lk");
  const [password, setPassword] = useState("");
  const navigate = useNavigate();
  const isRegister = mode === "register";

  const submitAuth = () => {
    if (!email || !password || (isRegister && !name)) return;

    localStorage.setItem("rideeasy-role", role);
    localStorage.setItem("rideeasy-user", name || email.split("@")[0]);
    navigate(role === "Admin" ? "/admin" : "/customer");
  };

  return (
    <section className="auth-layout">
      <div className="auth-card">
        <Link className="auth-brand" to="/">
          RideEasy
        </Link>
        <div className="auth-tabs">
          <Link className={mode === "login" ? "active" : ""} to="/login">
            Login
          </Link>
          <Link className={mode === "register" ? "active" : ""} to="/register">
            Register
          </Link>
        </div>

        <div className="page-title compact-title">
          <p className="eyebrow dark">{isRegister ? "Create account" : "Welcome back"}</p>
          <h1>{isRegister ? "Start renting with RideEasy" : "Welcome back"}</h1>
          <p>
            {isRegister
              ? "Save details, manage bookings, and access role-based dashboards."
              : "Sign in to continue your booking or manage fleet operations."}
          </p>
        </div>

        <div className="segmented-control" aria-label="Account role">
          {["Customer", "Admin"].map((item) => (
            <button
              className={role === item ? "active" : ""}
              key={item}
              onClick={() => setRole(item as "Customer" | "Admin")}
              type="button"
            >
              {item}
            </button>
          ))}
        </div>

        <div className="form-stack">
          {isRegister && (
            <label>
              Full name
              <input
                value={name}
                onChange={(event) => setName(event.target.value)}
                placeholder="Your name"
              />
            </label>
          )}

          <label>
            Email address
            <input
              type="email"
              value={email}
              onChange={(event) => setEmail(event.target.value)}
              placeholder="you@example.com"
            />
          </label>

          <label>
            Password
            <input
              type="password"
              value={password}
              onChange={(event) => setPassword(event.target.value)}
              placeholder="Enter password"
            />
          </label>

          <button className="button-primary full" onClick={submitAuth} type="button">
            {isRegister ? "Create Account" : "Sign In"}
          </button>
        </div>

        <p className="auth-switch">
          {isRegister ? "Already have an account?" : "New to RideEasy?"}{" "}
          <Link to={isRegister ? "/login" : "/register"}>
            {isRegister ? "Sign in" : "Create account"}
          </Link>
        </p>
      </div>
    </section>
  );
}
