import styles from "./Hero.module.css";
import Link from "next/link";

export default function Hero() {
  return (
    <section className={styles.hero}>
      {/* Background */}
      <div className={styles.bg}>
        <div className={styles.bgGrad} />
        <div className={styles.bgPattern} />
      </div>

      {/* Content */}
      <div className={`container ${styles.content}`}>
        <div className={styles.left}>
          <span className={styles.eyebrow}>Nueva Colección 2025</span>
          <h1 className={styles.heading}>
            SUPERA<br />
            <span className={styles.accent}>TUS</span><br />
            LÍMITES.
          </h1>
          <p className={styles.sub}>
            Equípate para cada desafío. Descubre el estilo y el rendimiento que te acompañan en cada entrenamiento.
          </p>
          <div className={styles.ctas}>
            <Link href="/productos" className="btn btn-primary btn-lg">
              EXPLORAR COLECCIÓN
            </Link>
            <Link href="/productos?onSale=true" className="btn btn-secondary btn-lg">
              VER OFERTAS
            </Link>
          </div>
          {/* Stats */}
          <div className={styles.stats}>
            <div className={styles.stat}>
              <span className={styles.statNum}>500+</span>
              <span className={styles.statLabel}>Productos</span>
            </div>
            <div className={styles.statDivider} />
            <div className={styles.stat}>
              <span className={styles.statNum}>5K+</span>
              <span className={styles.statLabel}>Clientes</span>
            </div>
            <div className={styles.statDivider} />
            <div className={styles.stat}>
              <span className={styles.statNum}>100%</span>
              <span className={styles.statLabel}>Garantía</span>
            </div>
          </div>
        </div>

        {/* Right visual */}
        <div className={styles.right}>
          <div className={styles.heroCard}>
            <div className={styles.heroCardInner}>
              <div className={styles.heroIcon}>⚡</div>
              <div className={styles.heroVisual}>
                <div className={styles.circle1} />
                <div className={styles.circle2} />
                <div className={styles.circle3} />
                <div className={styles.heroLabel}>
                  <span>RENDIMIENTO</span>
                  <span>PREMIUM</span>
                </div>
              </div>
              <div className={styles.heroBadge}>
                <span className={styles.heroBadgeText}>🔥 Más vendido</span>
              </div>
            </div>
          </div>
          {/* Floating badges */}
          <div className={styles.floatBadge1}>
            <span>🚚</span> Envío gratis +S/200
          </div>
          <div className={styles.floatBadge2}>
            <span>⭐</span> Calidad garantizada
          </div>
        </div>
      </div>

      {/* Scroll indicator */}
      <div className={styles.scrollIndicator}>
        <div className={styles.scrollLine} />
        <span>SCROLL</span>
      </div>
    </section>
  );
}
