'use client'
import Image from 'next/image'
import React, { useState, useEffect } from 'react'
import Link from 'next/link'
import dynamic from 'next/dynamic'
const ProfileDropdown = dynamic(() => import('../dropdowns/ProfileDropdown'), { ssr: false })
const MorePagesDropdown = dynamic(() => import('../dropdowns/MorePagesDropdown'), { ssr: false })
const MobileNav = dynamic(() => import('../mobileNav/MobileNav'), { ssr: false })
const LoginModal = dynamic(() => import('../auth/LoginModal'), { ssr: false })
import { usePathname } from 'next/navigation'
import { useSelector } from 'react-redux'
import { settingsSelector } from '../store/reducers/settingsReducer'
import { checkNewsDataSelector } from '../store/reducers/CheckNewsDataReducer'
import { currentLangCode, defaultLanguageCode, isLogin, placeholderImage, } from '@/utils/helpers';
import { translate } from '@/utils/translation';
import { logoutUser, userDataSelector } from '../store/reducers/userReducer'
import toast from 'react-hot-toast'
import { useRouter } from 'next/router'
import FirebaseData from '@/utils/Firebase'
import { getAuth, signOut } from 'firebase/auth'
import { deleteAccountApi } from '@/utils/api/api'
import { themeSelector } from '../store/reducers/CheckThemeReducer'
import SearchModal from '../search/SearchModal'
import { PiWarningCircleFill } from "react-icons/pi";

import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from "@/components/ui/alert-dialog"

const Header = ({ totalMorePages, morePagesOffset, setMorePagesOffset, setIsLoadMorePages, morePagesLimit }) => {

  const currLangCode = currentLangCode();
  const defaultLangCode = defaultLanguageCode();
  const darkThemeMode = useSelector(themeSelector);

  const [isClient, setIsClient] = useState(false)
  const [logoutConfrm, setLogoutConfrm] = useState(false)
  const [deleteAccConfrm, setDeleteAccConfrm] = useState(false)

  useEffect(() => {
    setIsClient(true)
  }, [])

  const router = usePathname();
  const navigate = useRouter();
  const auth = getAuth()

  const settingsData = useSelector(settingsSelector);

  const darkLogo = settingsData?.data?.web_setting?.dark_header_logo
  const lightLogo = settingsData?.data?.web_setting?.light_header_logo

  const checkNewsData = useSelector(checkNewsDataSelector);

  const showLiveNewsPage = settingsData && settingsData?.data?.live_streaming_mode === '1' && checkNewsData && checkNewsData?.data?.isLiveNewsData;
  const showBreakingNewsPage = settingsData && settingsData?.data?.breaking_news_mode === '1' && checkNewsData && checkNewsData?.data?.isBreakingNewsData;

  const showRssFeed = settingsData && settingsData?.data?.rss_feed_mode === '1';

  const showAboutContactUs = !showBreakingNewsPage && !showLiveNewsPage;

  const userData = useSelector(userDataSelector)
  const userRoleStatus = userData?.userManageData?.data?.is_author
  const { authentication } = FirebaseData();

  let userName = ''

  const checkUserData = userData => {
    if (userData?.data && userData?.data?.name !== '') {
      return (userName = userData?.data?.name)
    } else if (userData?.data && userData?.data?.email !== '') {
      return (userName = userData?.data?.email)
    } else if (userData?.data && (userData?.data?.mobile !== null || userData?.data?.mobile !== '')) {
      return (userName = userData?.data?.mobile)
    }
  }

  const handleSignOut = async (modal) => {
    try {
      await new Promise((resolve, reject) => {
        signOut(authentication)
          .then(() => {
            logoutUser()
            window.recaptchaVerifier = null
            toast.success(translate(modal === 'deleteAcc' ? 'deletedAcc' : 'loginOutMsg'))
            navigate.push(`/${currLangCode ? currLangCode : defaultLangCode}`)
            setLogoutConfrm(false)
            setDeleteAccConfrm(false)
            resolve() // Resolve the promise when signOut is successful
          })
          .catch(error => {
            toast.error(error)
            reject(error) // Reject the promise if there's an error
          })
      })
    } catch (e) {
      console.log('Oops errors!')
    }
  }

  const handleAlert = async (modal) => {
    if (modal === 'deleteAcc') {
      setDeleteAccConfrm(true)
    }
    else {
      setLogoutConfrm(true)
    }
  }

  const deleteUserAccApi = async () => {
    try {
      const response = await deleteAccountApi.deleteAccount({
      });
      handleSignOut('deleteAcc')
    } catch (error) {
      console.error('Error:', error);
    } finally {
    }
  };

  // delete account
  const deleteAccount = async e => {
    e.preventDefault()
    try {
      await new Promise((resolve, reject) => {
        const user = auth.currentUser

        if (user) {
          user
            .delete()
            .then(() => {
              deleteUserAccApi()

            })
            .catch(error => {
              console.error('Error deleting user account:', error)

              // Check if the error is "auth/requires-recent-login"
              if (error.code === 'auth/requires-recent-login') {
                toast.error(translate('loginFirstBeforeDeleteAcc'))
              } else {
                toast.error(translate('anErrorOccurredWhileDeletingUserAccount'))
              }
            })
        }
        setTimeout(Math.random() > 0.5 ? resolve : reject, 1000)
      })
    } catch (error) {
      console.log(error)
    }
  }



  if (typeof window !== 'undefined') {
    window.addEventListener('beforeunload', () => {
      sessionStorage.setItem('manualRefresh_Notification', 'true')
    })

    window.addEventListener('load', () => {
      // Check if this is a manual refresh by checking if lastFetch is set
      if (!sessionStorage.getItem('lastFetch_Notification')) {
        sessionStorage.setItem('manualRefresh_Notification', 'true')
      }
    })
  }


  return (
    isClient &&
    <header className='sticky top-0 z-[10] bodyBgColor border-b borderColor'>
      <div className='container py-[16px]'>
        <div className="flex items-center justify-between">
          <div className="logoDiv">
            <Link href={`/${currLangCode ? currLangCode : defaultLangCode}`}>
              <Image src={darkThemeMode ? darkLogo : lightLogo} height={0} width={0} className='h-[60px] w-[180px] xl:w-[130px] 2xl:w-[180px] object-contain' alt='web-logo' loading='lazy' onError={placeholderImage} />
            </Link>
          </div>
          <div className="navbar hidden xl:flex items-center xl:gap-2 2xl:gap-6">
            <div className="navLinks">
              <ul className='flexCenter xl:gap-4 2xl:gap-6 navMenus'>

                <li className='list-none'>
                  <Link href={`/${currLangCode ? currLangCode : defaultLangCode}`} title={translate('home')} className={`${router === `/${currLangCode ? currLangCode : defaultLangCode}` ? 'activeLink after:h-[94%]' : ''} relative after:content-[""] after:absolute after:top-[1px] xl:after:-left-[6px] 2xl:after:-left-[8px] after:h-[0%] after:w-[4px] after:rounded-full after:primaryBg hover:primaryColor transition-all duration-500 hover:after:h-[94%] hover:after:transition-all hover:after:duration-[0.3s] textPrimary uppercase font-semibold navMenusItem`}>{translate('home')}</Link>
                </li>

                {
                  showAboutContactUs &&
                  <li className='list-none'>
                    <Link href={`/${currLangCode}/about-us`} title={translate('aboutus')} className={`${router === `/${currLangCode}/about-us` ? 'activeLink after:h-[94%]' : ''} relative after:content-[""] after:absolute after:top-[1px] xl:after:-left-[6px] 2xl:after:-left-[8px] after:h-[0%] after:w-[4px] after:rounded-full after:primaryBg hover:primaryColor transition-all duration-500 hover:after:h-[94%] hover:after:transition-all hover:after:duration-[0.3s] textPrimary uppercase font-semibold navMenusItem`}>{translate('aboutus')}</Link>
                  </li>
                }

                {
                  showLiveNewsPage &&
                  <li className='list-none'>
                    <Link href={`/${currLangCode}/live-news`} className={`${router === `/${currLangCode}/live-news` ? 'activeLink after:h-[94%]' : ''} relative after:content-[""] after:absolute after:top-[1px] xl:after:-left-[6px] 2xl:after:-left-[8px] after:h-[0%] after:w-[4px] after:rounded-full after:primaryBg hover:primaryColor transition-all duration-500 hover:after:h-[94%] hover:after:transition-all hover:after:duration-[0.3s] textPrimary uppercase font-semibold navMenusItem`} title={translate('livenews')}>{translate('livenews')}</Link>
                  </li>
                }

                {
                  showBreakingNewsPage &&
                  <li className='list-none'>
                    <Link href={`/${currLangCode}/all-breaking-news`} className={`${router === `/${currLangCode}/all-breaking-news` ? 'activeLink after:h-[94%]' : ''} relative after:content-[""] after:absolute after:top-[1px] xl:after:-left-[6px] 2xl:after:-left-[8px] after:h-[0%] after:w-[4px] after:rounded-full after:primaryBg hover:primaryColor transition-all duration-500 hover:after:h-[94%] hover:after:transition-all hover:after:duration-[0.3s] textPrimary uppercase font-semibold navMenusItem`} title={translate('breakingnews')}>{translate('breakingnews')}</Link>
                  </li>
                }

                <li className='list-none'>
                  <Link href={`/${currLangCode}/video-news`} className={`${router === `/${currLangCode}/video-news` ? 'activeLink after:h-[94%]' : ''} relative after:content-[""] after:absolute after:top-[1px] xl:after:-left-[6px] 2xl:after:-left-[8px] after:h-[0%] after:w-[4px] after:rounded-full after:primaryBg hover:primaryColor transition-all duration-500 hover:after:h-[94%] hover:after:transition-all hover:after:duration-[0.3s] textPrimary uppercase font-semibold navMenusItem`} title={translate('videosLbl')}> {translate('videosLbl')}</Link>
                </li>

                {
                  showAboutContactUs &&
                  <li className='list-none'>
                    <Link href={`/${currLangCode}/contact-us`} className={`${router === `/${currLangCode}/contact-us` ? 'activeLink after:h-[94%]' : ''} relative after:content-[""] after:absolute after:top-[1px] xl:after:-left-[6px] 2xl:after:-left-[8px] after:h-[0%] after:w-[4px] after:rounded-full after:primaryBg hover:primaryColor transition-all duration-500 hover:after:h-[94%] hover:after:transition-all hover:after:duration-[0.3s] textPrimary uppercase font-semibold navMenusItem`}
                      title={translate('contactus')}>{translate('contactus')}</Link>
                  </li>
                }

                {
                  showRssFeed &&
                  <li className='list-none'>
                    <Link href={`/${currLangCode}/rss-feed`} className={`${router === `/${currLangCode}/rss-feed` ? 'activeLink after:h-[94%]' : ''} relative after:content-[""] after:absolute after:top-[1px] xl:after:-left-[6px] 2xl:after:-left-[8px] after:h-[0%] after:w-[4px] after:rounded-full after:primaryBg hover:primaryColor transition-all duration-500 hover:after:h-[94%] hover:after:transition-all hover:after:duration-[0.3s] textPrimary uppercase font-semibold navMenusItem`}
                      title={translate('rssFeed')}>{translate('rssFeed')}</Link>
                  </li>
                }

                <li className='list-none'>
                  <Link href={`/${currLangCode}/enews`} className={`${router === `/${currLangCode}/enews` ? 'activeLink after:h-[94%]' : ''} relative after:content-[""] after:absolute after:top-[1px] xl:after:-left-[6px] 2xl:after:-left-[8px] after:h-[0%] after:w-[4px] after:rounded-full after:primaryBg hover:primaryColor transition-all duration-500 hover:after:h-[94%] hover:after:transition-all hover:after:duration-[0.3s] textPrimary uppercase font-semibold navMenusItem`}
                    title={translate('e_news')}>{translate('e_news')}</Link>
                </li>


                <li className='list-none'>
                  <MorePagesDropdown totalMorePages={totalMorePages} morePagesOffset={morePagesOffset} setMorePagesOffset={setMorePagesOffset} setIsLoadMorePages={setIsLoadMorePages} morePagesLimit={morePagesLimit} />
                </li>

              </ul>
            </div>

            <div className="btns flexCenter xl:gap-1 2xl:gap-3">
              <div className="authDiv">
                {
                  isLogin() && checkUserData(userData) ?
                    <ProfileDropdown userName={userName} userRole={userRoleStatus} handleAlert={handleAlert} checkUserData={checkUserData(userData)} />
                    :
                    <LoginModal />
                }
                <div>
                  {/* logout alert */}
                  <AlertDialog open={logoutConfrm} onOpenChange={setLogoutConfrm}>
                    <AlertDialogContent className='bg-white'>
                      <AlertDialogHeader>
                        <AlertDialogTitle className='flex items-center gap-2'><PiWarningCircleFill color='#faad14' size={24} />{translate('logoutLbl')}</AlertDialogTitle>
                      </AlertDialogHeader>
                      <AlertDialogDescription>
                        {translate('logoutTxt')}
                      </AlertDialogDescription>
                      <AlertDialogFooter>
                        <AlertDialogCancel onClick={() => setLogoutConfrm(false)}>{translate('noLbl')}</AlertDialogCancel>
                        <AlertDialogAction onClick={() => handleSignOut('logoutAcc')}>{`${translate('yesLbl')},${translate('logout')}`}</AlertDialogAction>
                      </AlertDialogFooter>
                    </AlertDialogContent>
                  </AlertDialog>

                  {/* delete acc alert */}
                  <AlertDialog open={deleteAccConfrm} onOpenChange={setDeleteAccConfrm}>
                    <AlertDialogContent className='bg-white'>
                      <AlertDialogHeader>
                        <AlertDialogTitle className='flex items-center gap-2'><PiWarningCircleFill color='#faad14' size={24} />{translate('deleteAcc')}</AlertDialogTitle>
                      </AlertDialogHeader>
                      <AlertDialogDescription>
                        <div className='flex flex-col gap-1'>
                          <p className='font-[500]'>{translate('deleteAccWarning')}</p>
                          <li className='font-[500] ltr:ml-4 rtl:mr-5' style={{ listStyle: 'disc' }}>{translate('profileInfo')}</li>
                          <li className='font-[500] ltr:ml-4 rtl:mr-5' style={{ listStyle: 'disc' }}>{translate('settings')}</li>
                          <li className='font-[500] ltr:ml-4 rtl:mr-5' style={{ listStyle: 'disc' }}>{translate('associatedContent')}</li>
                        </div>
                      </AlertDialogDescription>
                      <AlertDialogFooter>
                        <AlertDialogCancel onClick={() => setDeleteAccConfrm(false)}>{translate('noLbl')}</AlertDialogCancel>
                        <AlertDialogAction onClick={(e) => deleteAccount(e)}>{`${translate('yesLbl')},${translate('deleteTxt')}`}</AlertDialogAction>
                      </AlertDialogFooter>
                    </AlertDialogContent>
                  </AlertDialog>
                </div>
              </div>
              <div>
                <SearchModal />
              </div>
            </div>

          </div>
          <div className='xl:hidden'>
            <MobileNav userRole={userRoleStatus} handleAlert={handleAlert} totalMorePages={totalMorePages} morePagesOffset={morePagesOffset} setMorePagesOffset={setMorePagesOffset} setIsLoadMorePages={setIsLoadMorePages} morePagesLimit={morePagesLimit} />
          </div>
        </div>

        <div id='recaptcha-container' className='fixed z-[99]'></div>

      </div>
    </header>
  )
}

export default Header