import Link from "next/link";
import styles from "./Categories.module.css";

const CATEGORIES = [
  {
    id: "ropa-deportiva",
    name: "Ropa Deportiva",
    slug: "ropa-deportiva",
    emoji: "👕",
    description: "Camisetas, shorts, licras y más",
    color: "#FF5500",
  },
  {
    id: "zapatillas",
    name: "Zapatillas",
    slug: "zapatillas",
    emoji: "👟",
    description: "Running, entrenamiento y lifestyle",
    color: "#1A1A1A",
  },
  {
    id: "accesorios",
    name: "Accesorios",
    slug: "accesorios",
    emoji: "🎒",
    description: "Mochilas, gorras, medias y más",
    color: "#FF7A00",
  },
  {
    id: "entrenamiento",
    name: "Entrenamiento",
    slug: "entrenamiento",
    emoji: "🏋️",
    description: "Equipamiento para gym y fitness",
    color: "#222222",
  },
  {
    id: "equipamiento",
    name: "Equipamiento",
    slug: "equipamiento",
    emoji: "⚽",
    description: "Balones, protecciones y más",
    color: "#CC4400",
  },
];

export default function Categories() {
  return (
    <section className={styles.section}>
      <div className="container">
        <div className="section-heading">
          <h2>CATEGORÍAS</h2>
          <div className="divider" />
          <p>Encuentra exactamente lo que necesitas para tu deporte</p>
        </div>

        <div className={styles.grid}>
          {CATEGORIES.map((cat, index) => (
            <Link
              key={cat.id}
              href={`/productos?categoria=${cat.slug}`}
              className={styles.card}
              style={{ "--cat-color": cat.color, "--delay": `${index * 0.1}s` } as React.CSSProperties}
            >
              <div className={styles.cardBg} />
              <div className={styles.emoji}>{cat.emoji}</div>
              <h3 className={styles.name}>{cat.name}</h3>
              <p className={styles.desc}>{cat.description}</p>
              <div className={styles.arrow}>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
                  <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
              </div>
            </Link>
          ))}
        </div>
      </div>
    </section>
  );
}
