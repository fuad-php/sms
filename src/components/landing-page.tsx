'use client'

import Link from 'next/link'
import { motion } from 'framer-motion'
import { 
  DocumentTextIcon, 
  CloudArrowUpIcon, 
  PencilSquareIcon,
  ShareIcon,
  ShieldCheckIcon,
  ChartBarIcon
} from '@heroicons/react/24/outline'

const features = [
  {
    name: 'Multi-Format Upload',
    description: 'Support for PDF, images, Word documents, and more',
    icon: CloudArrowUpIcon,
  },
  {
    name: 'Smart OCR Processing',
    description: 'Automatically extract ticket details using advanced OCR',
    icon: DocumentTextIcon,
  },
  {
    name: 'Professional Branding',
    description: 'Transform any ticket into your branded format',
    icon: PencilSquareIcon,
  },
  {
    name: 'Easy Export & Share',
    description: 'Export as PDF or share via email with watermarks',
    icon: ShareIcon,
  },
  {
    name: 'Secure & Private',
    description: 'Enterprise-grade security with role-based access',
    icon: ShieldCheckIcon,
  },
  {
    name: 'Analytics Dashboard',
    description: 'Track usage and manage your ticket library',
    icon: ChartBarIcon,
  },
]

export default function LandingPage() {
  return (
    <div className="min-h-screen bg-gradient-to-br from-primary-50 to-secondary-50">
      {/* Navigation */}
      <nav className="bg-white/80 backdrop-blur-sm border-b border-gray-200">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center h-16">
            <div className="flex items-center">
              <div className="flex-shrink-0">
                <h1 className="text-2xl font-bold text-gradient">Pro Ticketer</h1>
              </div>
            </div>
            <div className="flex items-center space-x-4">
              <Link href="/auth/signin" className="btn-secondary">
                Sign In
              </Link>
              <Link href="/auth/signup" className="btn-primary">
                Get Started
              </Link>
            </div>
          </div>
        </div>
      </nav>

      {/* Hero Section */}
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div className="text-center">
          <motion.h1
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8 }}
            className="text-4xl md:text-6xl font-bold text-gray-900 mb-6"
          >
            Standardize Your
            <span className="text-gradient block">Ticket Copies</span>
          </motion.h1>
          
          <motion.p
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8, delay: 0.2 }}
            className="text-xl text-gray-600 mb-8 max-w-3xl mx-auto"
          >
            Transform tickets from any source into professional, branded documents. 
            Upload, extract, standardize, and export with ease.
          </motion.p>
          
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8, delay: 0.4 }}
            className="flex flex-col sm:flex-row gap-4 justify-center"
          >
            <Link href="/auth/signup" className="btn-primary text-lg px-8 py-3">
              Start Free Trial
            </Link>
            <Link href="#features" className="btn-secondary text-lg px-8 py-3">
              Learn More
            </Link>
          </motion.div>
        </div>
      </div>

      {/* Features Section */}
      <div id="features" className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div className="text-center mb-16">
          <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            Everything You Need
          </h2>
          <p className="text-xl text-gray-600 max-w-2xl mx-auto">
            A comprehensive solution for ticket standardization and branding
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {features.map((feature, index) => (
            <motion.div
              key={feature.name}
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.5, delay: index * 0.1 }}
              className="card hover:shadow-lg transition-shadow duration-300"
            >
              <div className="flex items-center mb-4">
                <div className="flex-shrink-0">
                  <feature.icon className="h-8 w-8 text-primary-600" />
                </div>
                <h3 className="ml-3 text-lg font-semibold text-gray-900">
                  {feature.name}
                </h3>
              </div>
              <p className="text-gray-600">
                {feature.description}
              </p>
            </motion.div>
          ))}
        </div>
      </div>

      {/* CTA Section */}
      <div className="bg-primary-600 py-20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h2 className="text-3xl md:text-4xl font-bold text-white mb-4">
            Ready to Get Started?
          </h2>
          <p className="text-xl text-primary-100 mb-8 max-w-2xl mx-auto">
            Join thousands of professionals who trust Pro Ticketer for their document standardization needs.
          </p>
          <Link href="/auth/signup" className="bg-white text-primary-600 hover:bg-gray-50 font-medium py-3 px-8 rounded-lg transition-colors duration-200">
            Create Your Account
          </Link>
        </div>
      </div>

      {/* Footer */}
      <footer className="bg-gray-900 text-white py-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center">
            <h3 className="text-2xl font-bold mb-4">Pro Ticketer</h3>
            <p className="text-gray-400 mb-4">
              Professional ticket standardization made simple
            </p>
            <p className="text-gray-500 text-sm">
              © 2024 Pro Ticketer. All rights reserved.
            </p>
          </div>
        </div>
      </footer>
    </div>
  )
}