"use client";

import { useState } from "react";
import styles from "./FAQ.module.css";

const FAQS = [
  {
    q: "¿Cuánto tiempo demora el envío?",
    a: "En Lima Metropolitana: 24 a 48 horas hábiles. Para provincias: 3 a 7 días hábiles mediante Olva Courier o Shalom. Recibirás un número de seguimiento una vez despachado tu pedido.",
  },
  {
    q: "¿Cómo puedo pagar mi pedido?",
    a: "Aceptamos Yape, Plin, transferencia bancaria (BCP, Interbank, BBVA) y pago contra entrega en Lima. Una vez realizado el pedido, te enviamos los datos de pago por WhatsApp.",
  },
  {
    q: "¿Puedo cambiar o devolver un producto?",
    a: "Sí. Tienes 7 días calendario desde que recibes tu pedido para solicitar un cambio o devolución, siempre que el producto esté sin uso, con etiquetas y empaque original. Escríbenos por WhatsApp para coordinar.",
  },
  {
    q: "¿Los productos tienen garantía?",
    a: "Todos nuestros productos son de calidad verificada. Si recibes un artículo defectuoso o dañado, contáctanos dentro de las 48 horas de recibido y lo reemplazamos sin costo.",
  },
  {
    q: "¿Cómo sé qué talla elegir?",
    a: "En cada producto encontrarás una guía de tallas con medidas en centímetros. Si tienes dudas, escríbenos por WhatsApp y te asesoramos para que elijas la talla correcta.",
  },
  {
    q: "¿Realizan envíos a todo el Perú?",
    a: "Sí, enviamos a todos los departamentos del Perú a través de empresas de transporte confiables. El costo y el tiempo de entrega varía según la ubicación.",
  },
];

export default function FAQ() {
  const [open, setOpen] = useState<number | null>(null);

  return (
    <section className={styles.section}>
      <div className="container">
        <div className="section-heading">
          <h2>PREGUNTAS FRECUENTES</h2>
          <div className="divider" />
          <p>Resolvemos tus dudas más comunes</p>
        </div>

        <div className={styles.list}>
          {FAQS.map((faq, i) => (
            <div
              key={i}
              className={`${styles.item} ${open === i ? styles.active : ""}`}
            >
              <button
                className={styles.question}
                onClick={() => setOpen(open === i ? null : i)}
                aria-expanded={open === i}
              >
                <span>{faq.q}</span>
                <span className={styles.icon}>{open === i ? "−" : "+"}</span>
              </button>
              {open === i && (
                <div className={styles.answer}>
                  <p>{faq.a}</p>
                </div>
              )}
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
