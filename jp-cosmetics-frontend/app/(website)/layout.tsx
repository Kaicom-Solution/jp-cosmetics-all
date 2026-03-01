import PopUpPromotion from "@/components/PopUpPromotion";

export default function WebLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <>
      {children}
      <PopUpPromotion/>
    </>
  );
}