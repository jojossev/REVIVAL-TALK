'use client'
import React, { useState, useEffect } from 'react'
import { useSelector } from 'react-redux'
import { morePagesSelector } from '../store/reducers/morePagesReducer'
import Layout from '../layout/Layout'
import Breadcrumb from '../breadcrumb/Breadcrumb'
import { useRouter } from 'next/router'
import DetailPageSkeleton from '../skeletons/DetailPageSkeleton'
import { NoDataFound, } from '@/utils/helpers';
import { translate } from '@/utils/translation';
import RichTextContent from './RichTextContent'

const SocialPages = () => {

    const [loading, setLoading] = useState(false)
    const [data, setData] = useState([])
    const router = useRouter();
    const slug = router?.query?.slug;
    const translatedSlug = slug && slug === 'about-us' ? translate('aboutus') : slug === 'contact-us' ? translate('contactus') : slug


    const pageData = useSelector(morePagesSelector)

    useEffect(() => {
        if (slug && pageData?.length > 0) {
            // Find the page object with matching slug
            const page = pageData.find(page => page.slug === slug)
            // If a matching page is found, set its data
            if (page) {
                setData([page])
                setLoading(false)
            } else {
                // If no matching page is found, handle accordingly (e.g., show a not found message)
                setLoading(false)
                setData([])
            }
        }
    }, [slug, pageData])

    useEffect(() => {
        // console.log('dataPagesMore =>', data)
    }, [data])



    return (
        <Layout>
            <Breadcrumb secondElement={translatedSlug} />
            {
                loading ? <DetailPageSkeleton />
                    :
                    <section className='morePagesSect container mt-8 md:mt-12 pb-1'>
                        {
                            data[0]?.page_content ?
                                <RichTextContent content={data[0]?.page_content} />
                                :
                                <div>
                                    <NoDataFound />
                                </div>
                        }
                    </section>
            }
        </Layout>
    )
}

export default SocialPages