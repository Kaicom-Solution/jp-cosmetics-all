import type { Metadata } from "next";

export const metadata: Metadata = {
  title: "About Us | KaicomBD Cosmetics",
  description:
    "KaicomBD: Your trusted online store for premium cosmetics and beauty products in Bangladesh. Learn about our mission, vision, and passion for beauty.",
};

export default function AboutPage() {
  return (
    <main className="min-h-screen bg-white text-gray-800">
      {/* Hero Section */}
      <section className="bg-pink-600 text-white py-20 px-6 text-center">
        <h1 className="text-4xl md:text-5xl font-bold mb-4">About KaicomBD</h1>
        <p className="max-w-2xl mx-auto text-lg opacity-90">
          Premium cosmetics and beauty products delivered to your doorstep.
        </p>
      </section>

      {/* Who We Are */}
      <section className="max-w-6xl mx-auto px-6 py-16">
        <div className="grid md:grid-cols-2 gap-12 items-center">
          <div>
            <h2 className="text-3xl font-bold mb-6">Who We Are</h2>
            <p className="text-gray-600 leading-relaxed mb-4">
              KaicomBD is Bangladesh’s premier online cosmetics store. We curate
              high-quality makeup, skincare, and beauty products from trusted
              brands to help you look and feel your best.
            </p>
            <p className="text-gray-600 leading-relaxed">
              Our mission is to bring premium beauty products closer to you with
              convenience, authenticity, and excellent customer service.
            </p>
          </div>

          <div className="bg-gray-100 p-10 rounded-2xl shadow-sm">
            <h3 className="text-xl font-semibold mb-4">Our Values</h3>
            <ul className="space-y-3 text-gray-600">
              <li>✔ 100% Authentic Products</li>
              <li>✔ Affordable Prices</li>
              <li>✔ Fast Delivery Across Bangladesh</li>
              <li>✔ Customer Satisfaction Guarantee</li>
            </ul>
          </div>
        </div>
      </section>

      {/* Mission & Vision */}
      <section className="bg-gray-50 py-16 px-6">
        <div className="max-w-6xl mx-auto grid md:grid-cols-2 gap-12">
          <div className="bg-white p-10 rounded-2xl shadow-sm">
            <h2 className="text-2xl font-bold mb-4">Our Mission</h2>
            <p className="text-gray-600 leading-relaxed">
              To provide premium, authentic cosmetics to customers across
              Bangladesh, making beauty accessible and enjoyable for all.
            </p>
          </div>

          <div className="bg-white p-10 rounded-2xl shadow-sm">
            <h2 className="text-2xl font-bold mb-4">Our Vision</h2>
            <p className="text-gray-600 leading-relaxed">
              To become Bangladesh’s most trusted online cosmetics store, known
              for quality, reliability, and exceptional shopping experiences.
            </p>
          </div>
        </div>
      </section>

      {/* Why Choose Us */}
      <section className="max-w-6xl mx-auto px-6 py-16">
        <h2 className="text-3xl font-bold text-center mb-12">Why Choose Us</h2>
        <div className="grid md:grid-cols-3 gap-8 text-center">
          <div className="p-8 border rounded-2xl hover:shadow-lg transition">
            <h3 className="text-xl font-semibold mb-4">Authenticity</h3>
            <p className="text-gray-600">
              All our products are sourced directly from verified brands.
            </p>
          </div>

          <div className="p-8 border rounded-2xl hover:shadow-lg transition">
            <h3 className="text-xl font-semibold mb-4">Fast Delivery</h3>
            <p className="text-gray-600">
              Get your favorite products delivered quickly anywhere in
              Bangladesh.
            </p>
          </div>

          <div className="p-8 border rounded-2xl hover:shadow-lg transition">
            <h3 className="text-xl font-semibold mb-4">Customer Support</h3>
            <p className="text-gray-600">
              Our support team is always ready to assist you with your needs.
            </p>
          </div>
        </div>
      </section>

      {/* CTA */}
      <section className="bg-pink-600 text-white py-16 text-center px-6">
        <h2 className="text-3xl font-bold mb-6">
          Shop the Best Beauty Products
        </h2>
        <p className="mb-8 opacity-90">
          Explore our collection of premium cosmetics and find your perfect
          look.
        </p>
        <a
          href="/shop"
          className="bg-white text-pink-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition"
        >
          Start Shopping
        </a>
      </section>
    </main>
  );
}
