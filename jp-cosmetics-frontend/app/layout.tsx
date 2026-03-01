import type { Metadata } from "next";
import "./globals.css";
import Header from "@/components/header/Header";
import Footer from "@/components/footer/Footer";
import ScrollToTop from "@/components/ScrollToTop";
import { getBusinessInfo } from "@/services/home.service";
import { ToastContainer } from "react-toastify";

// export const metadata: Metadata = {
//   title: "Cosmetica - Premium Beauty & Cosmetics",
//   description:
//     "Discover elegance and self-care with Cosmetica's exclusive collection of premium beauty products and cosmetics.",
// };

export const dynamic = "force-dynamic";

const BASE_URL =
  process.env.NEXT_PUBLIC_SITE_URL || "https://www.jpcosmetica.biz";

export async function generateMetadata(): Promise<Metadata> {
  const data = await getBusinessInfo();
  const siteName = data?.business_name || "Cosmetica";
  const siteDescription = `Premium Beauty & Cosmetics. Located at ${
    data?.address || "your location"
  }.`;
  const siteUrl = data?.website_url || BASE_URL;

  const socialImage =
    data?.header_advertisement || "/assets/img/jp-cosmetica-logo.png";
  const favicon = data?.favicon_icon || "/favicon.ico";

  return {
    metadataBase: new URL(siteUrl),

    title: {
      default: siteName,
      template: `%s | ${siteName}`,
    },
    description: siteDescription,

    // Icons
    icons: {
      icon: favicon,
      shortcut: favicon,
      apple: favicon,
    },

    openGraph: {
      title: siteName,
      description: siteDescription,
      url: siteUrl,
      siteName: siteName,
      images: [
        {
          url: socialImage,
          width: 1200,
          height: 630,
          alt: `${siteName} Preview`,
        },
      ],
      locale: "en_US",
      type: "website",
    },

    twitter: {
      card: "summary_large_image",
      title: siteName,
      description: siteDescription,
      images: [socialImage], // Uses the logo or ad image
    },
  };
}

export default async function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  const businessInfo = await getBusinessInfo();

  return (
    <html lang="en" className="scroll-smooth">
      <body className="antialiased bg-white text-gray-900">
        <Header data={businessInfo} />

        <main className="min-h-screen bg-gradient-to-br from-pink-50 via-purple-50 to-white">
          {children}
        </main>

        <Footer data={businessInfo} />

        <ToastContainer />
        <ScrollToTop />
      </body>
    </html>
  );
}
