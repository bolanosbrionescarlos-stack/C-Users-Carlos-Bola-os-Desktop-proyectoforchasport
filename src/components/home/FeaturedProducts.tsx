import { prisma } from "@/lib/db";
import ProductCard from "@/components/products/ProductCard";
import Link from "next/link";
import styles from "./FeaturedProducts.module.css";

interface FeaturedProductsProps {
  title: string;
  subtitle?: string;
  filter: {
    featured?: boolean;
    isNew?: boolean;
    onSale?: boolean;
    categorySlug?: string;
  };
  viewAllHref: string;
}

export default async function FeaturedProducts({
  title,
  subtitle,
  filter,
  viewAllHref,
}: FeaturedProductsProps) {
  const where: Record<string, unknown> = { active: true };
  if (filter.featured) where.featured = true;
  if (filter.isNew) where.isNew = true;
  if (filter.onSale) where.onSale = true;
  if (filter.categorySlug) {
    where.category = { slug: filter.categorySlug };
  }

  const products = await prisma.product.findMany({
    where,
    include: { category: true },
    take: 8,
    orderBy: { createdAt: "desc" },
  });

  if (products.length === 0) return null;

  return (
    <section className={styles.section}>
      <div className="container">
        <div className={styles.header}>
          <div>
            <h2 className={styles.title}>{title}</h2>
            {subtitle && <p className={styles.subtitle}>{subtitle}</p>}
            <div className={styles.divider} />
          </div>
          <Link href={viewAllHref} className={`btn btn-outline ${styles.viewAll}`}>
            Ver Todos →
          </Link>
        </div>

        <div className={styles.grid}>
          {products.map((p) => (
            <ProductCard key={p.id} product={p} />
          ))}
        </div>
      </div>
    </section>
  );
}
