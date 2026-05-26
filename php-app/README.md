# RideEasy PHP Version

This folder contains a PHP version of the RideEasy vehicle rental project. It does not require React, Vite, or Node to run.

## Run

From this folder:

```bash
php -S 127.0.0.1:8080
```

Open:

```txt
http://127.0.0.1:8080/
```

## Pages

- `index.php` - Main PHP router and all page rendering
- `index.php?page=vehicles` - Vehicle listing
- `index.php?page=vehicle&id=tesla-model-3` - Vehicle detail
- `index.php?page=booking&id=tesla-model-3` - Booking form
- `index.php?page=customer` - Customer dashboard
- `index.php?page=admin` - Admin dashboard
- `index.php?page=login` - Login
- `index.php?page=register` - Register
- `index.php?page=contact` - Contact page

## Data Files

- `data/vehicles.php` - Vehicle catalog
- `data/bookings.php` - Booking read/write helpers
- `storage/bookings.json` - Saved booking records
- `assets/styles.css` - Responsive UI styling

## Notes

Bookings are saved in `storage/bookings.json`. The admin dashboard can update booking status when real saved bookings exist.
