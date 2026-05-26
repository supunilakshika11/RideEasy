export type BookingStatus = "Pending" | "Confirmed" | "In progress" | "Completed";

export type BookingRecord = {
  id: string;
  vehicleId: string;
  vehicleName: string;
  vehicleImage: string;
  customerName: string;
  email: string;
  phone: string;
  license: string;
  pickupDate: string;
  returnDate: string;
  pickupLocation: string;
  days: number;
  dailyRate: number;
  total: number;
  status: BookingStatus;
  createdAt: string;
};

const bookingKey = "rideeasy-bookings";

const isBrowser = () => typeof window !== "undefined";

export const getBookings = (): BookingRecord[] => {
  if (!isBrowser()) return [];

  const rawBookings = localStorage.getItem(bookingKey);
  if (!rawBookings) return [];

  try {
    const parsed = JSON.parse(rawBookings);
    return Array.isArray(parsed) ? parsed : [];
  } catch {
    return [];
  }
};

export const saveBooking = (booking: BookingRecord) => {
  if (!isBrowser()) return;

  const bookings = getBookings();
  localStorage.setItem(bookingKey, JSON.stringify([booking, ...bookings]));
};

export const updateBookingStatus = (bookingId: string, status: BookingStatus) => {
  if (!isBrowser()) return;

  const bookings = getBookings().map((booking) =>
    booking.id === bookingId ? { ...booking, status } : booking
  );

  localStorage.setItem(bookingKey, JSON.stringify(bookings));
};

export const makeBookingId = (vehicleId: string) => {
  const prefix = vehicleId.slice(0, 3).toUpperCase();
  const suffix = Math.floor(1000 + Math.random() * 9000);
  return `RE-${prefix}-${suffix}`;
};
