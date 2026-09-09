import type { Metadata } from 'next';
import './globals.css';
import Header from '@/components/Header';
import Footer from '@/components/Footer';

export const metadata: Metadata = {
  title: 'Competencia Deportiva Macroregional 2026',
  description:
    'Plataforma oficial de fixtures, resultados, árboles de eliminatorias y tabla de posiciones de la Competencia Deportiva Macroregional.',
  icons: {
    icon: 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><rect width="100" height="100" rx="20" fill="%230f172a"/><text y="65" font-size="50" font-weight="bold" fill="white" x="15">MR</text></svg>',
  },
};

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="es" className="h-full">
      <body className="flex min-h-full flex-col font-sans bg-[var(--canvas)] text-[var(--text-main)]">
        <Header />
        <main className="flex-1">{children}</main>
        <Footer />
      </body>
    </html>
  );
}
