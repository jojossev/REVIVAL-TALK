'use client'
import React, { useState, useEffect } from 'react'
import dynamic from 'next/dynamic'
import InfiniteScroll from 'react-infinite-scroll-component'
const StyleOne = dynamic(() => import('./feature-styles/styleOne/StyleOne'), { ssr: false })
const StyleTwo = dynamic(() => import('./feature-styles/styleTwo/StyleTwo'), { ssr: false })
const StyleThree = dynamic(() => import('./feature-styles/styleThree/StyleThree.jsx'), { ssr: false })
const StyleFour = dynamic(() => import('./feature-styles/styleFour/StyleFour'), { ssr: false })
const StyleFive = dynamic(() => import('./feature-styles/styleFive/StyleFive'), { ssr: false })
const StyleSix = dynamic(() => import('./feature-styles/styleSix/StyleSix'), { ssr: false })
const DefaultStyle = dynamic(() => import('./feature-styles/defaultStle/DefaultStyle'), { ssr: false })
import { getFeatureDataApi, getNewsApi } from '@/utils/api/api'
import { useSelector } from 'react-redux'
import { currentLanguageSelector } from '../store/reducers/languageReducer'
import { NoDataFound } from '@/utils/helpers'
import { translate } from '@/utils/translation';
import StyleOneSkeleton from '../skeletons/featureStyles/styleOne/StyleOneSkeleton.jsx'
import StyleTwoSkeleton from '../skeletons/featureStyles/styleTwo/StyleTwoSkeleton'
import StyleSixSkeleton from '../skeletons/featureStyles/styleSix/StyleSixSkeleton'
import StyleThreeSkeletonSkeleton from '../skeletons/featureStyles/styleThree/StyleThreeSkeleton'
import StyleFourSkeleton from '../skeletons/featureStyles/styleFour/StyleFourSkeleton'
import StyleFiveSkeleton from '../skeletons/featureStyles/styleFive/StyleFiveSkeleton'
import { settingsSelector } from '../store/reducers/settingsReducer'
import Layout from '../layout/Layout'
import { checkIsLangChange } from '../store/reducers/helperReducer'

const HomePage = () => {


  const [loading, setLoading] = useState(true)
  const [data, setData] = useState([])

  const isLangChange = useSelector(checkIsLangChange)

  const [noFeatureData, setNoFeatureData] = useState(false)
  const [newsDataFound, setNewsDataFound] = useState(true)
  const [isNoDataLoading, setNoDataIsLoading] = useState(false)
  const [defaultData, setDefaultData] = useState([])

  const currentLanguage = useSelector(currentLanguageSelector)

  const settingsData = useSelector(settingsSelector)
  const adsenseUrl = settingsData?.data?.web_setting?.google_adsense;

  const userToken = null;

  const newsLimit = 9;
  const sectionLimit = 4;

  const [loadmoreLoading, setLoadmoreLoading] = useState(false)
  const [hasMore, setHasMore] = useState(true)
  const [offset, setOffset] = useState(0)

  // Handle load more functionality using InfiniteScroll
  const handleLoadMore = () => {
    if (loadmoreLoading || !hasMore) return; // Prevent multiple calls

    setLoadmoreLoading(true) // Set loading state when starting to load more
    setOffset(prevOffset => prevOffset + 1)
  }

  const fetchFeatureData = async () => {
    try {
      // Only set main loading for initial load, not for load more
      if (offset === 0) {
        setLoading(true)
        // Reset "no data" flags so stale state from a previous language doesn't persist
        setNoFeatureData(false)
        setNewsDataFound(true)
        setNoDataIsLoading(false)
      }

      const { data } = await getFeatureDataApi.getFeatureData({
        section_limit: sectionLimit,
        section_offset: offset * sectionLimit,
        offset: 0,
        limit: newsLimit,
        isToken: userToken ? true : false,
        language_code: currentLanguage?.code,
      });

      const sectionsData = data?.data?.data

      const responseData = sectionsData;
      const total = data?.data?.total || 0;

      if (offset > 0 && !isLangChange) {
        // Append new data to existing data for load more
        setData((prevData) => [...prevData, ...responseData])
      } else {
        // Replace data for initial load
        setData(responseData)
      }

      // Calculate if there are more sections to load
      // Check if current loaded sections (offset + 1) * sectionLimit is less than total sections
      const currentLoadedSections = (offset + 1) * sectionLimit;
      const hasMoreSections = currentLoadedSections < total;

      setHasMore(hasMoreSections)
      setLoading(false)
      setLoadmoreLoading(false)

      if (data.error) {
        setNoDataIsLoading(true)
      }

    } catch (error) {
      console.error('Error:', error);
      setData([])
      setNoFeatureData(false)
      setNewsDataFound(true)
      setLoading(false)
      setLoadmoreLoading(false)
      setNoDataIsLoading(true)
    } finally {
      setLoading(false)
      setLoadmoreLoading(false)
    }
  };

  const getNewsWhenNoData = async () => {
    try {
      setLoading(true)
      const { data } = await getNewsApi.getNews({
        offset: '0',
        limit: 10, // {optional}
        language_code: currentLanguage?.code,
        latitude: settingsData?.lat,
        longitude: settingsData?.long,
      });
      setDefaultData(data?.data?.data)
      setNewsDataFound(false)
      setNoDataIsLoading(false)
      setLoading(false)

      if (data.error) {
        setNewsDataFound(true)
      }

    } catch (error) {
      console.error('Error:', error);
      setNewsDataFound(true)
      setNoDataIsLoading(false)
      setLoading(false)
      setNoFeatureData(true)
      setNewsDataFound(true)
    } finally {
      setLoading(false)
    }
  };

  // Fetch data when language changes (fresh load)
  useEffect(() => {
    if (currentLanguage?.code) {
      setOffset(0)
      setHasMore(true)
      fetchFeatureData()
    }
  }, [currentLanguage])

  // Fetch more data when offset increases (load more)
  useEffect(() => {
    if (currentLanguage?.code && offset > 0) {
      fetchFeatureData()
    }
  }, [offset])

  // Fetch default news when no feature data
  useEffect(() => {
    if (isNoDataLoading) {
      getNewsWhenNoData()
    }
  }, [isNoDataLoading])

  // console.log('data', data.length)

  // Render loading skeleton for infinite scroll
  const renderLoadingSkeleton = () => (
    <>
      <StyleOneSkeleton />
      <StyleTwoSkeleton />
      <StyleThreeSkeletonSkeleton />
      <StyleFourSkeleton />
      <StyleFiveSkeleton />
      <StyleSixSkeleton />
    </>
  )

  // Render individual style component
  const renderStyleComponent = (item, index) => {
    const styleType = item.style_web;

    switch (styleType) {
      case 'style_1':
        return <StyleOne key={index} Data={item} />
      case 'style_2':
        return <StyleTwo key={index} Data={item} />
      case 'style_3':
        return <StyleThree key={index} Data={item} />
      case 'style_4':
        return <StyleFour key={index} Data={item} />
      case 'style_5':
        return <StyleFive key={index} Data={item} />
      case 'style_6':
        return <StyleSix key={index} Data={item} loading={loading} setLoading={setLoading} />
      default:
        return null
    }
  }

  // Render news items based on type
  const renderNewsItems = () => {
    if (noFeatureData && newsDataFound) {
      return <>{NoDataFound()}</>
    }
    else {
      return data && data.map((item, index) => {
        // Render based on news type
        if (item.news_type === 'news' || item.news_type === 'breaking_news' ||
          item.news_type === 'videos' || item.news_type === 'user_choice' || item.news_type === 'rss_feeds_news') {
          return (
            <div key={index}>
              {renderStyleComponent(item, index)}
            </div>
          )
        }
        return null
      })
    }

  }

  const selectedComponent = renderNewsItems();

  useEffect(() => {
  }, [selectedComponent, data]);

  return (
    <Layout>
      <div className='flex flex-col gap-10 md:gap-12 lg:gap-14 mb-4 homePage commonMT'>
        {loading ? (
          renderLoadingSkeleton()
        ) : data && selectedComponent && selectedComponent.length > 0 ? (
          <InfiniteScroll
            dataLength={data.length}
            next={handleLoadMore}
            hasMore={hasMore}
            loader={renderLoadingSkeleton()}
            scrollableTarget="scrollableDiv"
            className='flex flex-col gap-10 md:gap-12 lg:gap-14 mb-4'
          >
            {selectedComponent}
          </InfiniteScroll>
        ) : !newsDataFound ? (
          <DefaultStyle isLoading={isNoDataLoading} Data={defaultData} />
        ) : (
          <p className='h-[64vh] flexCenter textPrimary font-[600] text-2xl'>{translate('noNews')}</p>
        )}
      </div>
      {process.env.NEXT_PUBLIC_SEO === 'false' ?
        adsenseUrl && adsenseUrl !== null || adsenseUrl && adsenseUrl !== undefined || adsenseUrl && adsenseUrl?.length > 0 ?
          <script async src={adsenseUrl}
            crossOrigin="anonymous"></script> : null
        : null
      }
    </Layout>
  )
}

export default HomePage