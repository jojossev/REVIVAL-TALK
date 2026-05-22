import Document, { Html, Head, Main, NextScript } from "next/document";

const CustomDocument = () => {
  return (
    <Html
      lang="fr"
      version={process.env.NEXT_PUBLIC_WEB_VERSION}
      seo={process.env.NEXT_PUBLIC_SEO}
      dir="ltr"
    >
      <Head>
        {/* Fonts */}
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" />
        <link
          href="https://fonts.googleapis.com/css2?family=Zilla+Slab:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
          rel="stylesheet"
        />

        <link
          href="https://cdn.quilljs.com/1.3.6/quill.snow.css"
          rel="stylesheet"
        />

        <meta
          name="google-site-verification"
          content="RHJnfAB6oAFPPdMOdqIAtJf3sFSr3RTNAYnPQ00u22Q"
        />

        {/* PWA */}
        <link rel="manifest" href="/manifest.json" />

        {/* Default favicon (fallback only) */}
        <link rel="icon" href="/favicon.png" />

        {/* Google Tag */}
        <script
          async
          src={`https://www.googletagmanager.com/gtag/js?id=${process.env.NEXT_PUBLIC_MEASUREMENT_ID}`}
        ></script>
        <script
          dangerouslySetInnerHTML={{
            __html: `
              window.dataLayer = window.dataLayer || [];
              function gtag(){dataLayer.push(arguments);}
              gtag('js', new Date());
              gtag('config', '${process.env.NEXT_PUBLIC_MEASUREMENT_ID}');
            `,
          }}
        />

        {/* AddToAny */}
        <script async src="https://static.addtoany.com/menu/page.js"></script>
      </Head>

      <body className="antialiased !pointer-events-auto">
        <Main />
        <NextScript />
      </body>
    </Html>
  );
};

CustomDocument.getInitialProps = async (ctx) => {
  const initialProps = await Document.getInitialProps(ctx);
  return { ...initialProps };
};

export default CustomDocument;