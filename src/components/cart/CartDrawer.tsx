"use client";

import { useCartStore } from "@/lib/cart-store";
import { formatPrice } from "@/lib/utils";
import Link from "next/link";
import Image from "next/image";
import styles from "./CartDrawer.module.css";

export default function CartDrawer() {
  const { items, isOpen, setIsOpen, removeItem, updateQuantity, subtotal, totalItems } =
    useCartStore();

  if (!isOpen) return null;

  return (
    <>
      <div
        className={styles.overlay}
        onClick={() => setIsOpen(false)}
        aria-hidden="true"
      />
      <aside className={styles.drawer}>
        {/* Header */}
        <div className={styles.header}>
          <div className={styles.title}>
            <h2>Tu Carrito</h2>
            {totalItems() > 0 && (
              <span className={styles.count}>{totalItems()} productos</span>
            )}
          </div>
          <button
            className={styles.closeBtn}
            onClick={() => setIsOpen(false)}
            aria-label="Cerrar carrito"
          >
            <CloseIcon />
          </button>
        </div>

        {/* Items */}
        {items.length === 0 ? (
          <div className={styles.empty}>
            <div className={styles.emptyIcon}>🛒</div>
            <h3>Tu carrito está vacío</h3>
            <p>Agrega productos para comenzar tu compra.</p>
            <button
              className={`btn btn-primary ${styles.shopBtn}`}
              onClick={() => setIsOpen(false)}
            >
              <Link href="/productos">Ver Productos</Link>
            </button>
          </div>
        ) : (
          <>
            <div className={styles.items}>
              {items.map((item) => (
                <div key={item.id} className={styles.item}>
                  <div className={styles.itemImage}>
                    {item.image ? (
                      <Image
                        src={item.image}
                        alt={item.name}
                        width={72}
                        height={72}
                        style={{ objectFit: "cover", width: "100%", height: "100%" }}
                      />
                    ) : (
                      <div className={styles.noImage}>📦</div>
                    )}
                  </div>
                  <div className={styles.itemInfo}>
                    <h4 className={styles.itemName}>{item.name}</h4>
                    <div className={styles.itemMeta}>
                      {item.size && <span>Talla: {item.size}</span>}
                      {item.color && <span>Color: {item.color}</span>}
                    </div>
                    <div className={styles.itemBottom}>
                      <div className={styles.qty}>
                        <button
                          onClick={() => updateQuantity(item.id, item.quantity - 1)}
                          className={styles.qtyBtn}
                          disabled={item.quantity <= 1}
                        >
                          −
                        </button>
                        <span className={styles.qtyNum}>{item.quantity}</span>
                        <button
                          onClick={() => updateQuantity(item.id, item.quantity + 1)}
                          className={styles.qtyBtn}
                        >
                          +
                        </button>
                      </div>
                      <span className={styles.itemPrice}>
                        {formatPrice(item.price * item.quantity)}
                      </span>
                    </div>
                  </div>
                  <button
                    className={styles.removeBtn}
                    onClick={() => removeItem(item.id)}
                    aria-label="Eliminar producto"
                  >
                    <TrashIcon />
                  </button>
                </div>
              ))}
            </div>

            {/* Footer */}
            <div className={styles.footer}>
              <div className={styles.subtotal}>
                <span>Subtotal</span>
                <span className={styles.subtotalAmount}>{formatPrice(subtotal())}</span>
              </div>
              <p className={styles.shipping}>Envío calculado en el checkout</p>
              <Link href="/checkout" onClick={() => setIsOpen(false)}>
                <button className={`btn btn-primary ${styles.checkoutBtn}`}>
                  PROCEDER AL CHECKOUT
                </button>
              </Link>
              <button
                className={`btn btn-outline ${styles.continueBtn}`}
                onClick={() => setIsOpen(false)}
              >
                Seguir Comprando
              </button>
            </div>
          </>
        )}
      </aside>
    </>
  );
}

function CloseIcon() {
  return (
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
      <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
    </svg>
  );
}
function TrashIcon() {
  return (
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
      <polyline points="3 6 5 6 21 6"/>
      <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
    </svg>
  );
}
