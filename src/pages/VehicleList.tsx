import { useMemo, useState } from "react";
import { useSearchParams } from "react-router-dom";
import VehicleCard from "../components/VehicleCard";
import { categories, vehicles, type VehicleCategory } from "../data/vehicles";

export default function VehicleList() {
  const [params] = useSearchParams();
  const initialCategory = params.get("category") as VehicleCategory | null;
  const [query, setQuery] = useState(params.get("location") ?? "");
  const [category, setCategory] = useState<"All" | VehicleCategory>(
    initialCategory && categories.includes(initialCategory) ? initialCategory : "All"
  );
  const [sort, setSort] = useState("featured");

  const filteredVehicles = useMemo(() => {
    const normalizedQuery = query.trim().toLowerCase();
    const results = vehicles.filter((vehicle) => {
      const matchesCategory = category === "All" || vehicle.category === category;
      const matchesSearch =
        !normalizedQuery ||
        vehicle.name.toLowerCase().includes(normalizedQuery) ||
        vehicle.category.toLowerCase().includes(normalizedQuery) ||
        vehicle.fuelType.toLowerCase().includes(normalizedQuery);

      return matchesCategory && matchesSearch;
    });

    return [...results].sort((a, b) => {
      if (sort === "price-low") return a.pricePerDay - b.pricePerDay;
      if (sort === "price-high") return b.pricePerDay - a.pricePerDay;
      if (sort === "rating") return b.rating - a.rating;
      return Number(Boolean(b.badge)) - Number(Boolean(a.badge));
    });
  }, [category, query, sort]);

  return (
    <section className="page-stack">
      <div className="page-title">
        <p className="eyebrow dark">Fleet search</p>
        <h1>Premium Fleet</h1>
        <p>
          Compare verified vehicles by comfort, range, luggage capacity, and
          daily rental price.
        </p>
      </div>

      <div className="filter-bar">
        <input
          value={query}
          onChange={(event) => setQuery(event.target.value)}
          placeholder="Search by name, fuel, or category"
        />
        <select
          value={category}
          onChange={(event) => setCategory(event.target.value as "All" | VehicleCategory)}
        >
          {categories.map((item) => (
            <option key={item} value={item}>
              {item}
            </option>
          ))}
        </select>
        <select value={sort} onChange={(event) => setSort(event.target.value)}>
          <option value="featured">Featured</option>
          <option value="rating">Highest rated</option>
          <option value="price-low">Price low to high</option>
          <option value="price-high">Price high to low</option>
        </select>
      </div>

      <div className="results-row">
        <span>{filteredVehicles.length} vehicles available</span>
        <span>Free cancellation on selected bookings</span>
      </div>

      <div className="vehicle-grid">
        {filteredVehicles.map((vehicle) => (
          <VehicleCard key={vehicle.id} vehicle={vehicle} />
        ))}
      </div>
    </section>
  );
}
