import { formatDate, placeholderImage, truncateText } from '@/utils/helpers'
import Link from 'next/link'
import React from 'react'
import { FiCalendar } from 'react-icons/fi'
import { MdCategory } from "react-icons/md";

const FeedCard = ({ data, selectedCate }) => {
    return (
        <Link href={data?.url} title='detail-page' target='_blank'>
            <div className={`w-full rounded-lg h-max bg-white dark:secondaryBg p-3 commonRadius grid grid-cols-12 gap-3`}>
                {
                    data?.image_url &&
                    <div className='col-span-3'>
                        <img src={data?.image_url || ""} width={0} height={0} className='w-full h-auto commonRadius' alt={data?.title} loading='lazy' onError={placeholderImage} />
                    </div>
                }
                <div className={`flex flex-col gap-2 ${data?.image_url ? 'col-span-9' : 'col-span-12'}`}>
                    <h1 className='textPrimary font-[700] text-base md:text-lg line-clamp-2'>{data?.title}</h1>
                    {
                        data?.description &&
                        <h2 className='textPrimary font-[500] line-clamp-3'>{truncateText(data?.description, 120)}</h2>
                    }
                    <div className='flex items-center justify-between w-full border-t pt-2 flex-wrap gap-y-3'>
                        {
                            !selectedCate &&
                            <div className='flex items-center gap-1'>
                                <MdCategory className="" size={18} />
                                <span className='textPrimary font-bold text-[18px]'>
                                    {data?.source?.category?.category_name}
                                </span>
                            </div>
                        }
                        <div className="flex items-center gap-1 textSecondary">
                            <FiCalendar className="" size={18} />
                            <span className='textSecondary text-[18px] font-[500]'>
                                {formatDate(data?.published_at)}
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </Link>
    )
}

export default FeedCard