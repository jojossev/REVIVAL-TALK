/* eslint-disable @typescript-eslint/no-var-requires */
const path = require('path')
require('dotenv').config()

/** @type {import('next').NextConfig} */
const nextConfig = {
  images: {
    domains: process.env.NEXT_PUBLIC_API_URL
      ? [process.env.NEXT_PUBLIC_API_URL.replace(/^https?:\/\//, '')]
      : [],
    unoptimized: true
  },

  trailingSlash: false,
  reactStrictMode: false,

  webpack: (config) => {
    config.resolve.alias = {
      ...config.resolve.alias,
      apexcharts: path.resolve(__dirname, './node_modules/apexcharts-clevision')
    }
    return config
  }
}

module.exports = nextConfig