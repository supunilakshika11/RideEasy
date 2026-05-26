type StatCardProps = {
  label: string;
  value: string;
  trend?: string;
  tone?: "default" | "accent" | "dark";
};

export default function StatCard({
  label,
  value,
  trend,
  tone = "default",
}: StatCardProps) {
  return (
    <article className={`stat-card ${tone}`}>
      <span>{label}</span>
      <strong>{value}</strong>
      {trend && <small>{trend}</small>}
    </article>
  );
}
