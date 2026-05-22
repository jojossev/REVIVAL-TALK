const fs = require('fs')
const axios = require('axios')
require('dotenv').config()

// Static routes
const staticRoutes = [
  '/',
  '/about-us',
  '/live-news',
  '/all-breaking-news',
  '/video-news',
  '/contact-us',
  '/policy-page/privacy-policy',
  '/policy-page/terms-condition',
  '/rss-feed',
]

// Safe fetch wrapper (NEVER crash build)
const safePost = async (url, data = {}) => {
  try {
    const res = await axios.post(url, data, {
      timeout: 10000,
    })
    return res.data
  } catch (err) {
    console.error(`API failed: ${url}`, err.message)
    return null
  }
}

const generateSitemap = async () => {
  try {
    const WEB_URL = process.env.NEXT_PUBLIC_WEB_URL

    if (!WEB_URL) {
      console.log('Missing WEB_URL, skipping sitemap')
      return
    }

    let urls = []

    // STATIC URLS
    staticRoutes.forEach((route) => {
      urls.push(`
  <url>
    <loc>${WEB_URL}${route}</loc>
    <lastmod>${new Date().toISOString()}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>`)
    })

    // OPTIONAL: dynamic routes (safe fail)
    const API = process.env.NEXT_PUBLIC_API_URL
    const ENDPOINT = process.env.NEXT_PUBLIC_END_POINT

    if (API && ENDPOINT) {
      const settings = await safePost(`${API}/${ENDPOINT}/get_settings`)

      if (settings?.data?.default_language?.code) {
        const lang = settings.data.default_language.code

        const news = await safePost(`${API}/${ENDPOINT}/get_news`, {
          offset: 0,
          limit: 50,
          language_code: lang,
        })

        if (news?.data?.data) {
          news.data.data.forEach((item) => {
            if (item?.slug) {
              urls.push(`
  <url>
    <loc>${WEB_URL}/news/${item.slug}</loc>
    <lastmod>${new Date().toISOString()}</lastmod>
    <changefreq>daily</changefreq>
    <priority>0.9</priority>
  </url>`)
            }
          })
        }
      }
    }

    const sitemap = `<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
${urls.join('\n')}
</urlset>`

    // ensure folder exists
    if (!fs.existsSync('public')) {
      fs.mkdirSync('public', { recursive: true })
    }

    fs.writeFileSync('public/sitemap.xml', sitemap)
    console.log('Sitemap generated successfully')
  } catch (err) {
    console.error('Sitemap error (non-blocking):', err.message)
  }
}

// run safely
generateSitemap()