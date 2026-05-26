# RideEasy Vehicle Rental

RideEasy is a responsive React and TypeScript vehicle rental web app based on the supplied UI reference. It includes fleet browsing, vehicle details, booking, customer dashboard, admin dashboard, authentication screens, and contact support.

## Tech Stack

- React
- TypeScript
- Vite
- React Router
- CSS
- Browser localStorage for demo booking persistence

## Run The Project

```bash
npm install
npm run dev
```

Open:

```txt
http://127.0.0.1:5174/
```

If Vite chooses a different port, use the URL printed in the terminal.

## Build

```bash
npm run build
```

## Main Pages

- `/` - Home page
- `/vehicles` - Vehicle listing and filters
- `/vehicles/:vehicleId` - Vehicle details
- `/booking` - Booking form
- `/booking/:vehicleId` - Booking for a selected vehicle
- `/customer` - Customer dashboard
- `/admin` - Admin dashboard
- `/login` - Login screen
- `/register` - Registration screen
- `/contact` - Contact and FAQ page

## Demo Behavior

When a user confirms a booking, the app saves the booking in browser localStorage. The customer dashboard and admin dashboard read from that saved booking data. If there are no saved bookings, they show sample demo data.

## Next Development Steps

- Add backend API with database persistence.
- Add admin vehicle CRUD.
- Add real authentication and protected routes.
- Add payment flow.
- Add booking status updates from the admin dashboard.
- Add Playwright end-to-end tests for booking and dashboard flows.
