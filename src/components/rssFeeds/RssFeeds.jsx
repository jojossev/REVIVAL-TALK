'use client'
import React, { useState, useEffect } from 'react'
import Layout from '../layout/Layout'
import { useSelector } from 'react-redux';
import { NoDataFound } from '@/utils/helpers';
import { translate } from '@/utils/translation';
import { currentLanguageSelector } from '../store/reducers/languageReducer';
import { settingsSelector } from '../store/reducers/settingsReducer';
import { getRssFeedsApi } from '@/utils/api/api';
import RelatedNewSections from '../relatedNews/RelatedNewSections';
import FeedCardSkeleton from '../skeletons/FeedCardSkeleton';
import FeedCard from './FeedCard';
import LoadMoreBtn from '../commonComponents/loadermoreBtn/LoadmoreBtn';
import FeedsFilter from './FeedsFilter';
import Breadcrumb from '../breadcrumb/Breadcrumb';
import { rssFeedViewAllCateIds } from '../store/reducers/helperReducer';

const RssFeeds = () => {

    const currentLanguage = useSelector(currentLanguageSelector)
    const settings = useSelector(settingsSelector)
    const rssFeedsViewAllCateIdsData = useSelector(rssFeedViewAllCateIds);

    const dataPerPage = 20;

    const [data, setData] = useState([])

    const [isLoading, setIsLoading] = useState({
        loading: true,
        loadMoreLoading: false
    })
    const [loadMore, setLoadMore] = useState(false)
    const [offset, setOffset] = useState(1)
    const [totalData, setTotalData] = useState('')

    // Selected filter state (kept in parent)
    const [selectedFilters, setSelectedFilters] = useState({
        cateIds: [],
        subCateIds: [],
        feedsIds: [],
    });

    const handleLoadMore = () => {
        setLoadMore(true)
        setOffset(offset + 1)
    }

    // api call 
    const getAllRssFeeds = async () => {
        loadMore ? setIsLoading({ loadMoreLoading: true }) : setIsLoading({ loading: true })
        try {
            const { data } = await getRssFeedsApi.getAllRssFeeds({
                language_code: currentLanguage?.code,
                per_page: dataPerPage,
                page: offset,
                source_ids: selectedFilters.feedsIds?.length > 0 ? selectedFilters.feedsIds : undefined,
                category_ids: selectedFilters.cateIds?.length > 0 ? selectedFilters.cateIds.join(',') : rssFeedsViewAllCateIdsData ? rssFeedsViewAllCateIdsData : undefined,
                subcategory_ids: selectedFilters.subCateIds?.length > 0 ? selectedFilters.subCateIds.join(',') : undefined,
            })

            if (!data?.error) {
                if (loadMore) {
                    setData((prevData) => [...prevData, ...data?.data?.data]);
                }
                else {
                    setData(data?.data?.data)
                }
                setTotalData(data?.data?.total)
                setIsLoading({ loading: false })
                setIsLoading({ loadMoreLoading: false })
            }
            else {
                setData([])
                setIsLoading({ loading: false })
                console.log('error =>', data?.message)
            }
        } catch (error) {
            console.log(error)
            setData([])
            setIsLoading({ loading: false })
        }
    }

    useEffect(() => {
        if (currentLanguage?.code) {
            getAllRssFeeds()
        }
    }, [currentLanguage, offset, selectedFilters, rssFeedsViewAllCateIdsData]);

    return (
        <Layout>
            <Breadcrumb secondElement={translate('rssFeed')} />
            <div className='container commonMT'>
                <FeedsFilter
                    selectedFilters={selectedFilters}
                    setSelectedFilters={setSelectedFilters}
                    setOffset={setOffset}
                    setLoadMore={setLoadMore}
                />

                <div className='grid grid-cols-12 mt-12 gap-6'>
                    <div className='col-span-12 lg:col-span-8'>
                        <div className='grid sm:grid-cols-2 gap-6'>
                            {
                                isLoading.loading ? [...Array(4)].map((_, index) => (
                                    <div key={index}>
                                        <FeedCardSkeleton index={index} />
                                    </div>
                                ))
                                    :

                                    data && data?.length > 0 ?
                                        data?.map((item, index) => {
                                            return <div key={index}><FeedCard data={item} selectedCate={selectedFilters.cateIds?.length > 0 || rssFeedsViewAllCateIdsData} /></div>
                                        })
                                        :
                                        !isLoading.loading &&
                                        <div>
                                            {NoDataFound()}
                                        </div>


                            }
                        </div>
                        {
                            !isLoading.loading &&
                            <div className='my-12'>
                                {totalData > dataPerPage && totalData !== data?.length ? (
                                    <LoadMoreBtn handleLoadMore={handleLoadMore} loadMoreLoading={isLoading.loadMoreLoading} />
                                ) : null}
                            </div>
                        }
                    </div>
                    <div className='col-span-12 lg:col-span-4 detailPage'>
                        <RelatedNewSections rssFeedPage={true} />
                    </div>
                </div>
            </div>
        </Layout>
    )
}

export default RssFeeds