import Meta from '@/components/commonComponents/seo/Meta'
import JsonLd from '@/components/Schema/JsonLd.jsx'
import { fetchMetaInfo } from '@/utils/fetchMetaInfo.js'
import dynamic from 'next/dynamic'

const LiveNews = dynamic(() => import('../../../components/pagesComponent/LiveNews.jsx'), { ssr: false })

const Index = ({ metadata }) => {
  return (
    <>
      <Meta
        title={metadata?.title}
        description={metadata?.description}
        keywords={metadata?.keywords}
        ogImage={metadata?.ogImage}
        pathName={metadata?.pathName}
      />
      <LiveNews />
      <JsonLd data={metadata?.schema} />
    </>
  )
}

let serverSidePropsFunction = null;

if (process.env.NEXT_PUBLIC_SEO === "true") {
  serverSidePropsFunction = async (context) => {
    const { req } = context;
    const { params } = req[Symbol.for('NextInternalRequestMeta')].match;
    const language_code = params.langCode
    
    const currentURL = process.env.NEXT_PUBLIC_WEB_URL + `/${language_code}/live-news/`;

    const metadata = await fetchMetaInfo({
      pageType: 'live_streaming_news',
      langCode: language_code,
      currentURL: currentURL
    });

    return {
      props: {
        metadata
      }
    };
  };
}

export const getServerSideProps = serverSidePropsFunction

export default Index
