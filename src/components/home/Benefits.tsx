import styles from "./Benefits.module.css";

const BENEFITS = [
  {
    icon: "🚚",
    title: "Envío Rápido",
    desc: "Lima: 24-48h. Nacional: 3-7 días con Olva Courier y Shalom.",
  },
  {
    icon: "🔒",
    title: "Pago Seguro",
    desc: "Yape, Plin, transferencia bancaria y pago contra entrega disponibles.",
  },
  {
    icon: "↩️",
    title: "Cambios Fáciles",
    desc: "Tienes 7 días para cambiar o devolver tu producto sin complicaciones.",
  },
  {
    icon: "🏆",
    title: "Calidad Premium",
    desc: "Productos seleccionados con los mejores materiales deportivos del mercado.",
  },
];

export default function Benefits() {
  return (
    <section className={styles.section}>
      <div className="container">
        <div className={styles.grid}>
          {BENEFITS.map((b, i) => (
            <div key={i} className={styles.card}>
              <div className={styles.icon}>{b.icon}</div>
              <div className={styles.text}>
                <h3 className={styles.title}>{b.title}</h3>
                <p className={styles.desc}>{b.desc}</p>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
