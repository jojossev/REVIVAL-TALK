'use client'
import React, { useState, useEffect } from 'react'
import Layout from '../layout/Layout'
import Breadcrumb from '../breadcrumb/Breadcrumb'
import CommonCardSkeleton from '../skeletons/CommonCardSkeleton'
import VideoNewsCard from '../commonComponents/VideoNewsCard'
import { getLiveNewsApi } from '@/utils/api/api'
import { useSelector } from 'react-redux'
import { currentLanguageSelector } from '../store/reducers/languageReducer'
import { NoDataFound, } from '@/utils/helpers';
import { translate } from '@/utils/translation';
import LoadMoreBtn from '../commonComponents/loadermoreBtn/LoadmoreBtn'
import { settingsSelector } from '../store/reducers/settingsReducer'
import { useRouter } from 'next/router'

const LiveNews = () => {

    const settingsData = useSelector(settingsSelector);
    const showLiveNewsPage = settingsData && settingsData?.data?.live_streaming_mode === '1';
    const currentLanguage = useSelector(currentLanguageSelector)
    const dataPerPage = 6;

    const [isLoading, setIsLoading] = useState({
        loading: true,
        loadMoreLoading: false
    })
    const [loadMore, setLoadMore] = useState(false)
    const [liveNewsData, setLiveNewsData] = useState([])
    const [offset, setOffset] = useState(0)
    const [totalData, setTotalData] = useState('')
    const router = useRouter()

    useEffect(() => {
        if (!showLiveNewsPage) {
            router.replace(`/${currentLanguage?.code}`)
            return
        }

    }, [showLiveNewsPage])


    const handleLoadMore = () => {
        setLoadMore(true)
        setOffset(offset + 1)
    }
    // api call
    const getLiveNews = async () => {
        !loadMore ? setIsLoading({ loading: true }) : setIsLoading({ loadMoreLoading: true })
        try {
            const { data } = await getLiveNewsApi.getLiveNews({
                language_code: currentLanguage?.code,
                offset: offset * dataPerPage,
                limit: dataPerPage,
            })

            if (!data?.error) {
                setTotalData(data?.data?.total)
                setIsLoading({ loading: false })
                setIsLoading({ loadMoreLoading: false })
                if (loadMore) {
                    setLiveNewsData((prevData) => [...prevData, ...data?.data?.data])
                }
                else {
                    setLiveNewsData(data?.data?.data);
                }
            }
            else {
                console.log('error =>', data?.message)
                setLiveNewsData([]);
                setIsLoading({ loading: false })
            }
        } catch (error) {
            console.log(error)
            setLiveNewsData([])
            setIsLoading({ loading: false })
        }
    }

    useEffect(() => {
        if (currentLanguage?.code) {
            getLiveNews()
        }
    }, [currentLanguage, offset])


    return (
        <Layout>
            <>
                <Breadcrumb secondElement={translate('livenews')} />

                <section className='allBreakingNews container mt-8 sm:mt-12'>
                    {
                        isLoading?.loading ? <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            {[...Array(3)].map((_, index) => (
                                <div key={index}>
                                    <CommonCardSkeleton />
                                </div>
                            ))}
                        </div>
                            :
                            liveNewsData && liveNewsData?.length > 0 ? <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                {liveNewsData?.map((element) => {
                                    return <VideoNewsCard element={element} liveNewsPage={true} />
                                })}
                            </div> :
                                <div> {NoDataFound()}</div>
                    }
                    <div className='mt-12'>
                        {totalData > dataPerPage && totalData !== liveNewsData?.length ? (
                            <LoadMoreBtn handleLoadMore={handleLoadMore} loadMoreLoading={isLoading.loadMoreLoading} />
                        ) : null}
                    </div>

                </section>
            </>
        </Layout>
    )
}

export default LiveNews