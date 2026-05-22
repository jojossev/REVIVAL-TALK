'use client'
import Image from 'next/image'
import Link from 'next/link'
import { useState, useEffect } from 'react'
import playstore from '../../assets/Images/playStore.svg'
import appleStore from '../../assets/Images/appleStore.svg'
import { settingsSelector } from '../store/reducers/settingsReducer'
import { useSelector } from 'react-redux'
import { currentLanguageSelector } from '../store/reducers/languageReducer'
import { currentLangCode, decryptGeminiKey, defaultLanguageCode, placeholderImage, } from '@/utils/helpers';
import { translate } from '@/utils/translation';
import { checkNewsDataSelector } from '../store/reducers/CheckNewsDataReducer'
import moment from 'moment'
import { themeSelector } from '../store/reducers/CheckThemeReducer'
import { categoriesSelector } from '../store/reducers/CategoriesReducer'
import { translateFooterDescription } from '../../gemini-api/AiCreateNewsApi'

const Footer = () => {

  const currLangCode = currentLangCode();
  const defaultLangCode = defaultLanguageCode();
  const darkThemeMode = useSelector(themeSelector);
  const settingsData = useSelector(settingsSelector)
  const categories = useSelector(categoriesSelector);

  const geminiApiKey = settingsData?.data?.google_gemini_api_key;

  const defaultLanguageName = settingsData?.data?.default_language?.language;
  // Test your specific key
  const decryptedGeminiKey = decryptGeminiKey(geminiApiKey);

  const darkLogo = settingsData?.data?.web_setting?.dark_footer_logo;
  const lightLogo = settingsData?.data?.web_setting?.light_footer_logo;

  const catData = Math.ceil(categories && categories?.length / 2);

  const checkNewsData = useSelector(checkNewsDataSelector);
  const currentLanguage = useSelector(currentLanguageSelector);
  const socialMedias = settingsData.data?.social_media;

  // State for translated footer description
  const [translatedFooterDesc, setTranslatedFooterDesc] = useState('');
  const [isTranslating, setIsTranslating] = useState(false);

  // Get original footer description from settings
  const originalFooterDesc = settingsData.data?.web_setting?.web_footer_description;

  // Effect to translate footer description when language changes
  useEffect(() => {
    const translateFooter = async () => {
      // If no original description, set empty string
      if (!originalFooterDesc) {
        setTranslatedFooterDesc('');
        return;
      }

      // If current language is English or not set, use original description (no translation needed)
      if (!currentLanguage?.name || currentLanguage?.name.toLowerCase() === defaultLanguageName || currentLanguage?.code === defaultLangCode) {
        setTranslatedFooterDesc(originalFooterDesc);
        return;
      }

      // Check if we already have a cached translation for this language and description
      // Use encodeURIComponent instead of btoa to handle Unicode characters safely
      const cacheKey = `footer_desc_${currentLanguage.code}_${encodeURIComponent(originalFooterDesc).substring(0, 30)}`;
      if (typeof window !== 'undefined') {
        const cachedTranslation = sessionStorage.getItem(cacheKey);
        if (cachedTranslation) {
          setTranslatedFooterDesc(cachedTranslation);
          return;
        }
      }
      setIsTranslating(true);
      try {
        const translatedText = await translateFooterDescription(
          decryptedGeminiKey,
          originalFooterDesc,
          currentLanguage.name,
          currentLanguage.code
        );
        setTranslatedFooterDesc(translatedText);
      } catch (error) {
        console.error('Error translating footer description:', error);
        // Fallback to original description if translation fails
        setTranslatedFooterDesc(originalFooterDesc);
      } finally {
        setIsTranslating(false);
      }
    };

    // Execute translation when dependencies change
    translateFooter();
  }, [originalFooterDesc, currentLanguage?.name, currentLanguage?.code]);

  return (
    <footer className="secondaryBg text-white pt-10 commonMT">
      <div className="container">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <div className='flex flex-col gap-8'>
            <Link href={`/${currLangCode ? currLangCode : defaultLangCode}`}>
              <Image src={darkThemeMode ? darkLogo : lightLogo} height={0} width={0} alt='logo' loading='lazy' className='w-[180px] h-[60px] object-contain' onError={placeholderImage} />
            </Link>
            <div className=''>
              <p className=' text-[14px]'>
                {isTranslating ? (
                  <span className="text-gray-400 italic">Translating...</span>
                ) : (
                  translatedFooterDesc || originalFooterDesc || ''
                )}
              </p>
            </div>

            {
              socialMedias?.length > 0 || settingsData && settingsData?.data?.rss_feed_mode === '1' ?
                <div>
                  <span className='font-[700] text-[18px]'>{translate('followus')}</span>
                  <div className='flex items-center gap-4 mt-3'>
                    {
                      socialMedias?.map((item, index) => {
                        return <Link href={item?.link} target='_blank' className='bg-[#57657dc4] transition-all duration-300 hover:primaryBg hover:shadow-[0_6px_24px_-5px] hover:shadow-gray-500 h-[32px] w-[32px] block rounded-full flexCenter' key={index}>
                          <Image src={item?.image} height={0} width={0} alt='social-medias' loading='lazy' className='h-[16px] w-[16px]' onError={placeholderImage} />
                        </Link>
                      })
                    }
                  </div>
                </div>
                : null
            }

          </div>

          <div className='lg:pl-12 flex flex-col gap-8'>
            <div>
              <span className='font-[500] text-[18px] border-b-[2px] border-[#ffffff29]'>{translate('navigations')}</span>
            </div>
            <div className='flex flex-col gap-3'>
              <Link href={`/${currLangCode ? currLangCode : defaultLangCode}`} className='transition-all duration-300 hover:primaryColor' title={translate('home')}>{translate('home')}</Link>

              <Link href={`/${currLangCode}/about-us`} className='transition-all duration-300 hover:primaryColor' title={translate('aboutus')}>{translate('aboutus')}</Link>

              {
                settingsData && settingsData.data?.live_streaming_mode === '1' && checkNewsData && checkNewsData?.data?.isLiveNewsData &&
                <Link href={`/${currLangCode}/live-news`} className='transition-all duration-300 hover:primaryColor' title={translate('livenews')}>{translate('livenews')}</Link>
              }

              {
                settingsData && settingsData.data?.breaking_news_mode === '1' && checkNewsData && checkNewsData?.data?.isBreakingNewsData &&
                <Link href={`/${currLangCode}/all-breaking-news`} className='transition-all duration-300 hover:primaryColor' title={translate('breakingnews')}>{translate('breakingnews')}</Link>
              }

              <Link href={`/${currLangCode}/video-news`} className='transition-all duration-300 hover:primaryColor' title={translate('videosLbl')}>{translate('videosLbl')}</Link>

              <Link href={`/${currLangCode}/contact-us`} className='transition-all duration-300 hover:primaryColor' title={translate('contactus')}>{translate('contactus')}</Link>

              <Link href={`/${currLangCode}/rss-feed`} className='transition-all duration-300 hover:primaryColor' title={translate('rssFeed')}>{translate('rssFeed')}</Link>

              <Link href={`/${currLangCode}/enews`} className='transition-all duration-300 hover:primaryColor' title={translate('e_news')}>{translate('e_news')}</Link>
            </div>
          </div>

          {
            settingsData?.data?.category_mode === '1' && categories && categories?.length > 0 &&
            <div className='flex flex-col gap-8'>
              <div>
                <span className='font-[500] text-[18px] border-b-[2px] border-[#ffffff29]'>{translate('categories')}</span>
              </div>

              <div className='flex items-center gap-16'>
                <div className='flex flex-col gap-3'>
                  {
                    categories.length > 10 ? categories.slice(0, catData).map((item, index) => {
                      return <Link href={`/${currLangCode}/categories-news/${item?.slug}`} title={item?.category_name} className='transition-all duration-300 hover:primaryColor cursor-pointer' key={index}>{item?.category_name}</Link>
                    })
                      :
                      <div className='flex flex-col gap-3'>
                        {
                          categories.map((item, index) => {
                            return <Link href={`/${currLangCode}/categories-news/${item?.slug}`} title={item?.category_name} className='transition-all duration-300 hover:primaryColor cursor-pointer' key={index}>{item?.category_name}</Link>
                          })
                        }
                      </div>
                  }

                </div>
                {
                  categories.length > 10 &&
                  <div className='flex flex-col gap-3'>
                    {
                      categories.slice(catData).map((item, index) => {
                        return <Link href={`/${currLangCode}/categories-news/${item?.slug}`} title={item?.category_name} className='transition-all duration-300 hover:primaryColor cursor-pointer' key={index}>{item?.category_name}</Link>
                      })
                    }

                  </div>
                }
              </div>

            </div>
          }

          {
            settingsData && settingsData.data?.web_setting?.android_app_link || settingsData && settingsData.data?.web_setting?.ios_app_link ?
              <div className='flex flex-col gap-8'>
                <div>
                  <span className='font-[500] text-[18px] border-b-[2px] border-[#ffffff29]'>{translate('downloadapp')}</span>
                </div>
                <div>
                  <span>{translate('magicofapp')}</span>
                </div>
                <div className='flex items-center flex-wrap 2xl:flex-nowrap -ml-[12px]'>
                  {
                    settingsData.data?.web_setting?.android_app_link &&
                    <Link href={settingsData.data?.web_setting?.android_app_link} target='_blank' title='play-store'>
                      <Image src={playstore} height={0} width={0} alt='playstore' loading='lazy' className='w-[164px] h-auto' onError={placeholderImage} />
                    </Link>
                  }
                  {
                    settingsData.data?.web_setting?.ios_app_link &&
                    <Link href={settingsData.data?.web_setting?.ios_app_link} target='_blank' title='apple-store'>
                      <Image src={appleStore} height={0} width={0} alt='appleStore' loading='lazy' className='w-[164px] h-auto' onError={placeholderImage} />
                    </Link>
                  }
                </div>
              </div> : null
          }

        </div>
      </div>

      <div className='border-t border-[#ffffff29] text-white font-[400] py-6 mt-8 md:mt-12'>
        <div className='container flex items-center justify-between flex-wrap gap-3 sm:flex-nowrap sm:gap-0'>
          <div className=''> {translate('copyright')} © {moment().year()} {translate('allrights')}{' '} <span className="font-[700]">{settingsData && settingsData.data?.web_setting?.web_name}</span></div>
          <div className='flex items-center gap-2'>
            <Link href={`/${currLangCode}/policy-page/terms-condition`} className='transition-all duration-300 hover:primaryColor' title={translate('termsandcondition')}>{translate('termsandcondition')}</Link>
            <span>|</span>
            <Link href={`/${currLangCode}/policy-page/privacy-policy`} className='transition-all duration-300 hover:primaryColor' title={translate('priPolicy')}>{translate('priPolicy')}</Link>
          </div>
        </div>
      </div>
    </footer>
  )
}

export default Footer
