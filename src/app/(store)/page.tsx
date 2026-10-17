import Hero from "@/components/home/Hero";
import Categories from "@/components/home/Categories";
import Benefits from "@/components/home/Benefits";
import FeaturedProducts from "@/components/home/FeaturedProducts";
import FAQ from "@/components/home/FAQ";

export default function HomePage() {
  return (
    <>
      <Hero />
      <Benefits />
      <Categories />
      <FeaturedProducts
        title="MÁS VENDIDOS"
        subtitle="Los productos favoritos de nuestros clientes"
        filter={{ featured: true }}
        viewAllHref="/productos"
      />
      <FeaturedProducts
        title="NUEVOS PRODUCTOS"
        subtitle="Las últimas novedades que llegaron a la tienda"
        filter={{ isNew: true }}
        viewAllHref="/productos?isNew=true"
      />
      <FeaturedProducts
        title="OFERTAS ESPECIALES"
        subtitle="Precios increíbles por tiempo limitado"
        filter={{ onSale: true }}
        viewAllHref="/productos?onSale=true"
      />
      <FAQ />
    </>
  );
}
