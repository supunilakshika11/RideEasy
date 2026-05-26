import { Navigate, Route, Routes } from "react-router-dom";
import BottomNav from "./components/BottomNav";
import Footer from "./components/Footer";
import Navbar from "./components/Navbar";
import AdminDashboard from "./pages/AdminDashboard";
import Auth from "./pages/Auth";
import Booking from "./pages/Booking";
import Contact from "./pages/Contact";
import CustomerDashboard from "./pages/CustomerDashboard";
import Home from "./pages/Home";
import VehicleDetail from "./pages/VehicleDetail";
import VehicleList from "./pages/VehicleList";

export default function App() {
  return (
    <div className="app-shell">
      <Navbar />

      <main className="page-container">
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/vehicles" element={<VehicleList />} />
          <Route path="/fleet" element={<Navigate to="/vehicles" replace />} />
          <Route path="/vehicles/:vehicleId" element={<VehicleDetail />} />
          <Route path="/booking" element={<Booking />} />
          <Route path="/booking/:vehicleId" element={<Booking />} />
          <Route path="/customer" element={<CustomerDashboard />} />
          <Route path="/admin" element={<AdminDashboard />} />
          <Route path="/contact" element={<Contact />} />
          <Route path="/login" element={<Auth mode="login" />} />
          <Route path="/register" element={<Auth mode="register" />} />
          <Route path="*" element={<Navigate to="/" replace />} />
        </Routes>
      </main>

      <Footer />
      <BottomNav />
    </div>
  );
}
