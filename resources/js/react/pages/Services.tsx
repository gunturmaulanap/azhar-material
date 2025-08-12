import React from 'react';
import { Truck, Users, Building, Package, Clock, Shield, MapPin, Phone } from 'lucide-react';
import { motion } from 'framer-motion';
import { Card, CardContent } from '../components/ui/card';

const Services: React.FC = () => {
  // Animation variants
  const containerVariants: any = {
    hidden: { opacity: 0 },
    visible: {
      opacity: 1,
      transition: {
        duration: 0.6,
        staggerChildren: 0.15
      }
    }
  };

  const itemVariants: any = {
    hidden: { opacity: 0, y: 30 },
    visible: {
      opacity: 1,
      y: 0,
      transition: {
        duration: 0.6,
        ease: "easeOut"
      }
    }
  };

  const cardVariants: any = {
    hidden: { opacity: 0, scale: 0.9, y: 20 },
    visible: {
      opacity: 1,
      scale: 1,
      y: 0,
      transition: {
        duration: 0.5,
        ease: "easeOut"
      }
    },
    hover: {
      y: -8,
      scale: 1.02,
      transition: {
        duration: 0.2,
        ease: "easeInOut"
      }
    }
  };

  const benefitVariants: any = {
    hidden: { opacity: 0, y: 20 },
    visible: {
      opacity: 1,
      y: 0,
      transition: {
        duration: 0.5,
        ease: "easeOut"
      }
    }
  };

  const iconMap = {
    truck: Truck,
    users: Users,
    building: Building,
    package: Package,
  };

  return (
    <motion.div 
      className="min-h-screen bg-gray-50"
      initial="hidden"
      animate="visible"
      variants={containerVariants}
    >
      {/* Breadcrumb */}
      <motion.div 
        className="bg-white py-4 border-b"
        variants={itemVariants}
      >
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <nav className="text-sm">
            <span className="text-gray-500">Home</span>
            <span className="mx-2 text-gray-400">/</span>
            <span className="text-primary font-medium">Services</span>
          </nav>
        </div>
      </motion.div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        {/* Header */}
        <motion.div 
          className="text-center mb-16"
          variants={itemVariants}
        >
          <h1 className="text-4xl md:text-5xl font-bold text-neutral-800 mb-6">
            Our Services
          </h1>
          <p className="text-xl text-gray-600 max-w-3xl mx-auto">
            Comprehensive construction material solutions designed to support your building projects from start to finish.
          </p>
        </motion.div>

        {/* Main Services Grid */}
        <motion.div 
          className="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16"
          variants={containerVariants}
        >
          {[
            {
              id: 1,
              title: 'Pengantaran Bahan Bangunan',
              description: 'Layanan antar langsung ke lokasi proyek Anda dengan armada terpercaya dan jadwal yang fleksibel.',
              icon: 'truck'
            },
            {
              id: 2,
              title: 'Konsultasi Kebutuhan Material',
              description: 'Konsultasi gratis untuk menentukan kebutuhan material proyek Anda dengan tim ahli berpengalaman.',
              icon: 'users'
            },
            {
              id: 3,
              title: 'Kerjasama Proyek Besar',
              description: 'Solusi material untuk proyek skala besar dengan harga khusus dan dukungan teknis lengkap.',
              icon: 'building'
            },
            {
              id: 4,
              title: 'Pembelian Grosir dan Eceran',
              description: 'Melayani pembelian dalam jumlah besar maupun eceran dengan sistem pembayaran yang fleksibel.',
              icon: 'package'
            }
          ].map((service, index) => {
            const IconComponent = iconMap[service.icon as keyof typeof iconMap];
            
            return (
              <motion.div
                key={service.id}
                variants={cardVariants}
                whileHover="hover"
                transition={{ delay: index * 0.1 }}
              >
                <Card className="group border-0 shadow-lg hover:shadow-xl rounded-2xl overflow-hidden h-full">
                  <CardContent className="p-8">
                    <div className="flex items-start space-x-6">
                      <div className="flex-shrink-0">
                        <motion.div 
                          className="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-all duration-300"
                          whileHover={{ rotate: 360 }}
                          transition={{ duration: 0.6 }}
                        >
                          <IconComponent className="h-8 w-8 text-primary group-hover:text-white" />
                        </motion.div>
                      </div>
                      <div className="flex-1">
                        <h3 className="text-xl font-semibold text-neutral-800 mb-3">
                          {service.title}
                        </h3>
                        <p className="text-gray-600 leading-relaxed">
                          {service.description}
                        </p>
                      </div>
                    </div>
                  </CardContent>
                </Card>
              </motion.div>
            );
          })}
        </motion.div>

        {/* Additional Services */}
        <motion.div 
          className="bg-white rounded-2xl p-8 md:p-12 shadow-lg mb-16"
          variants={itemVariants}
        >
          <h2 className="text-3xl font-bold text-neutral-800 mb-8 text-center">
            Additional Benefits
          </h2>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {[
              {
                icon: Clock,
                title: 'Fast Delivery',
                description: 'Same-day delivery available for orders placed before 2 PM within the city area.',
                color: 'green'
              },
              {
                icon: Shield,
                title: 'Quality Guarantee',
                description: 'All materials come with quality assurance and warranty coverage.',
                color: 'blue'
              },
              {
                icon: MapPin,
                title: 'Wide Coverage',
                description: 'We deliver across the region with flexible scheduling options.',
                color: 'purple'
              }
            ].map((benefit, index) => (
              <motion.div
                key={benefit.title}
                className="text-center"
                variants={benefitVariants}
                transition={{ delay: index * 0.2 }}
              >
                <motion.div 
                  className={`w-16 h-16 bg-${benefit.color}-100 rounded-full flex items-center justify-center mx-auto mb-4`}
                  whileHover={{ scale: 1.1, rotate: 5 }}
                  transition={{ duration: 0.3 }}
                >
                  <benefit.icon className={`h-8 w-8 text-${benefit.color}-600`} />
                </motion.div>
                <h3 className="text-lg font-semibold text-neutral-800 mb-2">{benefit.title}</h3>
                <p className="text-gray-600">{benefit.description}</p>
              </motion.div>
            ))}
          </div>
        </motion.div>

        {/* CTA Section */}
        <motion.div 
          className="bg-primary text-white rounded-2xl p-8 md:p-12 text-center"
          variants={itemVariants}
          whileHover={{ scale: 1.02 }}
          transition={{ duration: 0.3 }}
        >
          <h2 className="text-3xl md:text-4xl font-bold mb-4">
            Need Custom Solutions?
          </h2>
          <p className="text-xl mb-8 text-accent">
            Contact us for personalized service packages tailored to your project requirements.
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <motion.a
              href="https://wa.me/6281392854911"
              className="inline-flex items-center px-6 py-3 bg-white text-primary rounded-full font-semibold hover:bg-accent transition-all duration-300"
              whileHover={{ scale: 1.05, y: -2 }}
              whileTap={{ scale: 0.95 }}
            >
              <Phone className="h-5 w-5 mr-2" />
              Call Us Now
            </motion.a>
            <motion.a
              href="mailto:azharmaterial@gmail.com"
              className="inline-flex items-center px-6 py-3 border-2 border-white text-white rounded-full font-semibold hover:bg-white hover:text-primary transition-all duration-300"
              whileHover={{ scale: 1.05, y: -2 }}
              whileTap={{ scale: 0.95 }}
            >
              Get Quote
            </motion.a>
          </div>
        </motion.div>
      </div>
    </motion.div>
  );
};

export default Services;
