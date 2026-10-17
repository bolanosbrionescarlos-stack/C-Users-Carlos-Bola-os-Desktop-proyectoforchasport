import { prisma } from "@/lib/db";
import ProductCard from "@/components/products/ProductCard";
import styles from "./page.module.css";
import type { Metadata } from "next";

export const metadata: Metadata = {
  title: "Catálogo de Productos",
  description:
    "Explora toda la colección de ropa deportiva, zapatillas y accesorios FORCHA - SPORT. Envíos a todo el Perú.",
};

interface SearchParams {
  q?: string;
  categoria?: string;
  onSale?: string;
  isNew?: string;
  orderBy?: string;
  minPrice?: string;
  maxPrice?: string;
  page?: string;
}

const PAGE_SIZE = 16;

export default async function ProductsPage({
  searchParams,
}: {
  searchParams: Promise<SearchParams>;
}) {
  const params = await searchParams;
  const page = parseInt(params.page || "1");
  const skip = (page - 1) * PAGE_SIZE;

  const where: Record<string, unknown> = { active: true };
  if (params.q) {
    where.name = { contains: params.q, mode: "insensitive" };
  }
  if (params.categoria) {
    where.category = { slug: params.categoria };
  }
  if (params.onSale === "true") where.onSale = true;
  if (params.isNew === "true") where.isNew = true;
  if (params.minPrice || params.maxPrice) {
    where.price = {};
    if (params.minPrice) (where.price as Record<string, number>).gte = parseFloat(params.minPrice);
    if (params.maxPrice) (where.price as Record<string, number>).lte = parseFloat(params.maxPrice);
  }

  let orderBy: Record<string, string> = { createdAt: "desc" };
  if (params.orderBy === "price-asc") orderBy = { price: "asc" };
  if (params.orderBy === "price-desc") orderBy = { price: "desc" };
  if (params.orderBy === "name") orderBy = { name: "asc" };

  const [products, total, categories] = await Promise.all([
    prisma.product.findMany({
      where,
      include: { category: true },
      orderBy,
      skip,
      take: PAGE_SIZE,
    }),
    prisma.product.count({ where }),
    prisma.category.findMany({ where: { active: true }, orderBy: { order: "asc" } }),
  ]);

  const totalPages = Math.ceil(total / PAGE_SIZE);

  return (
    <div className={styles.page}>
      <div className="container">
        {/* Header */}
        <div className={styles.header}>
          <div>
            <h1 className={styles.title}>
              {params.q
                ? `Resultados para "${params.q}"`
                : params.categoria
                ? categories.find((c) => c.slug === params.categoria)?.name || "Productos"
                : "TODOS LOS PRODUCTOS"}
            </h1>
            <p className={styles.count}>{total} productos encontrados</p>
          </div>
        </div>

        <div className={styles.layout}>
          {/* Sidebar Filters */}
          <aside className={styles.sidebar}>
            <div className={styles.filterGroup}>
              <h3 className={styles.filterTitle}>Categorías</h3>
              <a
                href="/productos"
                className={`${styles.filterLink} ${!params.categoria ? styles.active : ""}`}
              >
                Todos
              </a>
              {categories.map((cat) => (
                <a
                  key={cat.id}
                  href={`/productos?categoria=${cat.slug}`}
                  className={`${styles.filterLink} ${params.categoria === cat.slug ? styles.active : ""}`}
                >
                  {cat.name}
                </a>
              ))}
            </div>

            <div className={styles.filterGroup}>
              <h3 className={styles.filterTitle}>Filtros</h3>
              <a
                href={`/productos?${new URLSearchParams({ ...params, onSale: "true" }).toString()}`}
                className={`${styles.filterLink} ${params.onSale === "true" ? styles.active : ""}`}
              >
                🏷️ Solo ofertas
              </a>
              <a
                href={`/productos?${new URLSearchParams({ ...params, isNew: "true" }).toString()}`}
                className={`${styles.filterLink} ${params.isNew === "true" ? styles.active : ""}`}
              >
                ✨ Nuevos
              </a>
            </div>

            <div className={styles.filterGroup}>
              <h3 className={styles.filterTitle}>Ordenar por</h3>
              {[
                { v: "", l: "Más recientes" },
                { v: "price-asc", l: "Precio: menor a mayor" },
                { v: "price-desc", l: "Precio: mayor a menor" },
                { v: "name", l: "Nombre A-Z" },
              ].map(({ v, l }) => (
                <a
                  key={v}
                  href={`/productos?${new URLSearchParams({ ...params, orderBy: v }).toString()}`}
                  className={`${styles.filterLink} ${(params.orderBy || "") === v ? styles.active : ""}`}
                >
                  {l}
                </a>
              ))}
            </div>
          </aside>

          {/* Products Grid */}
          <div className={styles.content}>
            {products.length === 0 ? (
              <div className={styles.empty}>
                <span>🔍</span>
                <h3>No encontramos productos</h3>
                <p>Intenta con otros filtros o términos de búsqueda.</p>
                <a href="/productos" className="btn btn-primary">
                  Ver todos los productos
                </a>
              </div>
            ) : (
              <>
                <div className={styles.grid}>
                  {products.map((p) => (
                    <ProductCard key={p.id} product={p} />
                  ))}
                </div>

                {/* Pagination */}
                {totalPages > 1 && (
                  <div className={styles.pagination}>
                    {Array.from({ length: totalPages }, (_, i) => i + 1).map((p) => (
                      <a
                        key={p}
                        href={`/productos?${new URLSearchParams({ ...params, page: String(p) }).toString()}`}
                        className={`${styles.pageBtn} ${page === p ? styles.pageBtnActive : ""}`}
                      >
                        {p}
                      </a>
                    ))}
                  </div>
                )}
              </>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
