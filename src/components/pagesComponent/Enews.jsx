'use client';
import { useEffect, useState } from 'react';
import Layout from '../layout/Layout';
import PdfViewer from '../commonComponents/PdfViewer';
import { getENewsApi } from '@/utils/api/api';
import { useSelector } from 'react-redux';
import { currentLanguageSelector } from '../store/reducers/languageReducer';
import { NoDataFound, placeholderImage } from '@/utils/helpers';
import LoadMoreBtn from '../commonComponents/loadermoreBtn/LoadmoreBtn';
import CommonCardSkeleton from '../skeletons/CommonCardSkeleton';
import RichTextContent from '../commonComponents/RichTextContent';
import { FiCalendar } from 'react-icons/fi';
import Image from 'next/image';
import Breadcrumb from '../breadcrumb/Breadcrumb';
import { translate } from '@/utils/translation';

const Enews = () => {

    const currentLanguage = useSelector(currentLanguageSelector)

    const [selectedPdf, setSelectedPdf] = useState(null);

    const dataPerPage = 8 // number of posts per page
    const [isLoading, setIsLoading] = useState({
        loading: true,
        loadMoreLoading: false
    })
    const [loadMore, setLoadMore] = useState(false)
    const [eNewsData, setENewsData] = useState([])
    const [offset, setOffset] = useState(1)
    const [totalData, setTotalData] = useState('')


    const handleLoadMore = () => {
        setLoadMore(true)
        setOffset(offset + 1)
    }

    const fetchENews = async () => {

        if (currentLanguage?.code) {
            !loadMore ? setIsLoading({ loading: true }) : setIsLoading({ loadMoreLoading: true })
            try {
                const { data } = await getENewsApi.getENews({
                    page: offset,
                    per_page: dataPerPage,
                    language_code: currentLanguage?.code,
                })

                const newsData = data?.data?.data

                if (!data?.error) {
                    newsData.sort((a, b) => new Date(b.date) - new Date(a.date));
                    setTotalData(data?.data?.total)
                    setIsLoading({ loading: false })
                    setIsLoading({ loadMoreLoading: false })
                    if (loadMore) {
                        setENewsData((prevData) => [...prevData, ...newsData])
                    }
                    else {
                        setENewsData(newsData);
                    }
                }
                else {
                    console.log('error =>', data?.message)
                    setENewsData([]);
                    setIsLoading({ loading: false })
                    setIsLoading({ loadMoreLoading: false })
                }

            } catch (error) {
                console.log(error)
                setENewsData([])
                setIsLoading({ loading: false })
            }
        }
    };

    useEffect(() => {
        fetchENews()
    }, [currentLanguage?.code, offset])

    useEffect(() => {
    }, [totalData, isLoading, eNewsData])

    useEffect(() => {
        setLoadMore(false)
        setOffset(1)
    }, [])

    return (
        <Layout>
            <Breadcrumb secondElement={translate('e_news')} />
            <section className="container py-6 commonMT">
                <div className="grid sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    {
                        isLoading.loading ? [...Array(4)].map((_, index) => (
                            <div key={index}>
                                <CommonCardSkeleton />
                            </div>
                        )) :
                            eNewsData?.map((item) => (
                                <div className={`w-full rounded-2xl h-max p-4 bg-white dark:secondaryBg`} key={item?.title}
                                >
                                    {/* Image Section */}
                                    <div className="relative">
                                        <Image
                                            src={item?.thumbnail}
                                            alt={item?.title}
                                            width={0}
                                            height={0}
                                            onError={placeholderImage}
                                            loading='lazy'
                                            className={`object-cover w-full h-[250px] md:h-[300px] rounded-[8px] transition-all duration-500 hover:-translate-y-2`}
                                        />
                                    </div>

                                    {/* Text Section */}
                                    <div className="p-4 relative text-start">
                                        <div className='h-[150px] overflow-hidden'>
                                            <h4 className={`text-[20px] font-[600] mb-2 line-clamp-2 textPrimary`}>
                                                {item?.title}
                                            </h4>
                                            {
                                                item?.description &&
                                                <div className='line-clamp-3 lg:text-lg'>
                                                    <RichTextContent content={item?.description} />
                                                </div>
                                            }
                                        </div>
                                        <div className='border borderColor my-2.5' />
                                        <div className='flex items-center justify-between mt-3 flex-wrap gap-y-4'>

                                            {
                                                item?.date &&
                                                <div className="flex items-center textSecondary">
                                                    <FiCalendar className="mr-2 mt-1" size={18} />
                                                    <span className='textSecondary text-[18px] font-[500]'>
                                                        {item?.date}
                                                    </span>
                                                </div>
                                            }
                                            <button className='commonBtn w-1/2' onClick={() => setSelectedPdf(item?.attachment)}>View Paper</button>
                                        </div>
                                    </div>
                                </div >
                            ))}
                </div>
                {
                    !isLoading.loading && eNewsData?.length < 1 &&
                    <NoDataFound />
                }

                {totalData > dataPerPage && totalData !== eNewsData.length ? (
                    <div className='mt-12'>
                        <LoadMoreBtn handleLoadMore={handleLoadMore} loadMoreLoading={isLoading.loadMoreLoading} />
                    </div>
                ) : null}
            </section>

            {/* PDF Modal */}
            {selectedPdf && (
                <PdfViewer
                    pdf={selectedPdf}
                    onClose={() => setSelectedPdf(null)}
                />
            )}
        </Layout>
    );
};

export default Enews;
