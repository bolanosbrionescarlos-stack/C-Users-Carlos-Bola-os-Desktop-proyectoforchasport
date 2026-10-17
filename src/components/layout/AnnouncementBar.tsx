"use client";

import Link from "next/link";
import styles from "./AnnouncementBar.module.css";

const announcements = [
  "🚚 Envío gratis en compras mayores a S/ 200",
  "🏆 Calidad deportiva premium garantizada",
  "📱 Paga con Yape o Plin — fácil y seguro",
];

export default function AnnouncementBar() {
  return (
    <div className={styles.bar}>
      <div className={styles.inner}>
        <div className={styles.track}>
          {[...announcements, ...announcements].map((msg, i) => (
            <span key={i} className={styles.item}>
              {msg}
            </span>
          ))}
        </div>
      </div>
    </div>
  );
}
