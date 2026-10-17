import type { Metadata } from "next";
import "./globals.css";

export const metadata: Metadata = {
  title: {
    default: "FORCHA - SPORT | Tienda Deportiva Premium en Perú",
    template: "%s | FORCHA - SPORT",
  },
  description:
    "Descubre la mejor ropa deportiva, zapatillas y accesorios en FORCHA - SPORT. Calidad premium al mejor precio en el mercado peruano. Envíos a todo el Perú.",
  keywords: ["ropa deportiva", "zapatillas", "deporte", "Perú", "FORCHA SPORT"],
  openGraph: {
    type: "website",
    locale: "es_PE",
    url: "https://forchasport.com",
    siteName: "FORCHA - SPORT",
    title: "FORCHA - SPORT | Tienda Deportiva Premium en Perú",
    description:
      "Descubre la mejor ropa deportiva, zapatillas y accesorios en Perú.",
  },
  robots: {
    index: true,
    follow: true,
  },
};

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="es-PE">
      <body>{children}</body>
    </html>
  );
}
