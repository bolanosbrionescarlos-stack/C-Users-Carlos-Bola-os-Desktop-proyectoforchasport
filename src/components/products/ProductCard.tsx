"use client";

import Link from "next/link";
import Image from "next/image";
import { formatPrice, discountPercent, parseJson } from "@/lib/utils";
import { useCartStore } from "@/lib/cart-store";
import styles from "./ProductCard.module.css";
import { useState } from "react";

interface Product {
  id: string;
  name: string;
  slug: string;
  price: number;
  comparePrice?: number | null;
  images: string;
  isNew?: boolean;
  onSale?: boolean;
  featured?: boolean;
  category?: { name: string; slug: string };
}

interface ProductCardProps {
  product: Product;
}

export default function ProductCard({ product }: ProductCardProps) {
  const images = parseJson<string[]>(product.images, []);
  const mainImage = images[0] || null;
  const discount = product.comparePrice
    ? discountPercent(product.price, product.comparePrice)
    : 0;
  const { addItem } = useCartStore();
  const [added, setAdded] = useState(false);

  const handleAddToCart = (e: React.MouseEvent) => {
    e.preventDefault();
    e.stopPropagation();
    addItem({
      id: "",
      productId: product.id,
      name: product.name,
      price: product.price,
      image: mainImage || "",
      quantity: 1,
      slug: product.slug,
    });
    setAdded(true);
    setTimeout(() => setAdded(false), 1500);
  };

  return (
    <Link href={`/producto/${product.slug}`} className={styles.card}>
      {/* Image */}
      <div className={styles.imageWrap}>
        {mainImage ? (
          <Image
            src={mainImage}
            alt={product.name}
            fill
            style={{ objectFit: "cover" }}
            sizes="(max-width: 600px) 50vw, 25vw"
          />
        ) : (
          <div className={styles.noImage}>
            <span>📦</span>
          </div>
        )}

        {/* Badges */}
        <div className={styles.badges}>
          {product.isNew && <span className="badge badge-new">Nuevo</span>}
          {discount > 0 && (
            <span className="badge badge-sale">-{discount}%</span>
          )}
        </div>

        {/* Hover overlay */}
        <div className={styles.overlay}>
          <button
            className={styles.addBtn}
            onClick={handleAddToCart}
            aria-label="Agregar al carrito"
          >
            {added ? "✓ AGREGADO" : "+ AGREGAR AL CARRITO"}
          </button>
          <Link
            href={`/producto/${product.slug}`}
            className={styles.viewBtn}
            onClick={(e) => e.stopPropagation()}
          >
            Ver Detalles
          </Link>
        </div>
      </div>

      {/* Info */}
      <div className={styles.info}>
        {product.category && (
          <span className={styles.catLabel}>{product.category.name}</span>
        )}
        <h3 className={styles.name}>{product.name}</h3>
        <div className={styles.pricing}>
          <span className={styles.price}>{formatPrice(product.price)}</span>
          {product.comparePrice && product.comparePrice > product.price && (
            <span className={styles.comparePrice}>
              {formatPrice(product.comparePrice)}
            </span>
          )}
        </div>
      </div>
    </Link>
  );
}
