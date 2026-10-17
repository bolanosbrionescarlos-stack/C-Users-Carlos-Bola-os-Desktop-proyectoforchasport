// Format price in Peruvian soles
export function formatPrice(price: number): string {
  return new Intl.NumberFormat("es-PE", {
    style: "currency",
    currency: "PEN",
    minimumFractionDigits: 2,
  }).format(price);
}

// Generate unique order code
export function generateOrderCode(): string {
  const timestamp = Date.now().toString(36).toUpperCase();
  const random = Math.random().toString(36).substring(2, 6).toUpperCase();
  return `FS-${timestamp}-${random}`;
}

// Slugify string
export function slugify(text: string): string {
  return text
    .toString()
    .toLowerCase()
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .replace(/[^a-z0-9 -]/g, "")
    .replace(/\s+/g, "-")
    .replace(/-+/g, "-")
    .trim();
}

// Calculate discount percentage
export function discountPercent(price: number, comparePrice: number): number {
  if (!comparePrice || comparePrice <= price) return 0;
  return Math.round(((comparePrice - price) / comparePrice) * 100);
}

// Parse JSON safely
export function parseJson<T>(value: string, fallback: T): T {
  try {
    return JSON.parse(value) as T;
  } catch {
    return fallback;
  }
}

// Truncate text
export function truncate(text: string, length: number): string {
  if (text.length <= length) return text;
  return text.substring(0, length) + "...";
}

// Peruvian departments
export const DEPARTMENTS = [
  "Amazonas", "Áncash", "Apurímac", "Arequipa", "Ayacucho",
  "Cajamarca", "Callao", "Cusco", "Huancavelica", "Huánuco",
  "Ica", "Junín", "La Libertad", "Lambayeque", "Lima",
  "Loreto", "Madre de Dios", "Moquegua", "Pasco", "Piura",
  "Puno", "San Martín", "Tacna", "Tumbes", "Ucayali"
];

export const PAYMENT_METHODS = [
  { id: "yape", label: "Yape / Plin", icon: "📱" },
  { id: "transfer", label: "Transferencia Bancaria", icon: "🏦" },
  { id: "cod", label: "Pago Contra Entrega", icon: "💵" },
];

export const SHIPPING_METHODS = [
  { id: "lima", label: "Entrega en Lima Metropolitana", price: 10 },
  { id: "national", label: "Envío Nacional (Olva / Shalom)", price: 15 },
  { id: "express", label: "Envío Express Lima (24h)", price: 20 },
];

export const ORDER_STATUSES = [
  { id: "pending", label: "Pendiente de confirmación", color: "#F59E0B" },
  { id: "confirmed", label: "Confirmado", color: "#3B82F6" },
  { id: "preparing", label: "En preparación", color: "#8B5CF6" },
  { id: "shipped", label: "Enviado", color: "#06B6D4" },
  { id: "delivered", label: "Entregado", color: "#10B981" },
  { id: "cancelled", label: "Cancelado", color: "#EF4444" },
];

export const PAYMENT_STATUSES = [
  { id: "pending", label: "Pendiente", color: "#F59E0B" },
  { id: "paid", label: "Pagado", color: "#10B981" },
  { id: "failed", label: "Fallido", color: "#EF4444" },
  { id: "refunded", label: "Reembolsado", color: "#6B7280" },
];
