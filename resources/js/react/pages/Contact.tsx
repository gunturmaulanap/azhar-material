import React from 'react';
import { MessageCircle, Mail, Facebook, Instagram, Phone, MapPin } from 'lucide-react';
import { motion } from 'framer-motion';
import { Button } from '../components/ui/button';
import { Card, CardContent } from '../components/ui/card';

const Contact: React.FC = () => {
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
      y: -10,
      scale: 1.02,
      transition: {
        duration: 0.2,
        ease: "easeInOut"
      }
    }
  };

  const contactInfoVariants: any = {
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

  const contactMethods = [
    {
      id: 1,
      title: 'WhatsApp',
      subtitle: 'Chat langsung dengan tim kami',
      detail: '081392854911',
      icon: MessageCircle,
      color: 'bg-green-100 text-green-600',
      hoverColor: 'hover:bg-green-600 hover:text-white',
      action: () => window.open('https://wa.me/6281392854911', '_blank'),
      buttonText: 'Chat via WhatsApp',
      buttonStyle: 'bg-green-600 hover:bg-green-700 text-white'
    },
    {
      id: 2,
      title: 'Email',
      subtitle: 'Kirim pertanyaan detail',
      detail: 'azharmaterial@gmail.com',
      icon: Mail,
      color: 'bg-blue-100 text-blue-600',
      hoverColor: 'hover:bg-blue-600 hover:text-white',
      action: () => window.open('mailto:azharmaterial@gmail.com', '_blank'),
      buttonText: 'Kirim Email',
      buttonStyle: 'bg-blue-600 hover:bg-blue-700 text-white'
    },
    {
      id: 3,
      title: 'Facebook',
      subtitle: 'Follow untuk update terbaru',
      detail: '@azharmaterial',
      icon: Facebook,
      color: 'bg-indigo-100 text-indigo-600',
      hoverColor: 'hover:bg-indigo-600 hover:text-white',
      action: () => window.open('https://facebook.com/azharmaterial', '_blank'),
      buttonText: 'Visit Facebook',
      buttonStyle: 'bg-indigo-600 hover:bg-indigo-700 text-white'
    },
    {
      id: 4,
      title: 'Instagram',
      subtitle: 'Lihat galeri produk kami',
      detail: '@azharmaterial',
      icon: Instagram,
      color: 'bg-pink-100 text-pink-600',
      hoverColor: 'hover:bg-pink-600 hover:text-white',
      action: () => window.open('https://instagram.com/azharmaterial', '_blank'),
      buttonText: 'Follow Instagram',
      buttonStyle: 'bg-pink-600 hover:bg-pink-700 text-white'
    }
  ];

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
            <span className="text-primary font-medium">Contact</span>
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
            Hubungi Kami
          </h1>
          <p className="text-xl text-gray-600 max-w-3xl mx-auto">
            Siap membantu kebutuhan material bangunan Anda. Hubungi kami melalui platform yang Anda pilih.
          </p>
        </motion.div>

        {/* Contact Methods Grid */}
        <motion.div 
          className="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16"
          variants={containerVariants}
        >
          {contactMethods.map((method, index) => {
            const IconComponent = method.icon;
            
            return (
              <motion.div
                key={method.id}
                variants={cardVariants}
                whileHover="hover"
                transition={{ delay: index * 0.1 }}
              >
                <Card className="group border-0 shadow-lg hover:shadow-xl rounded-2xl overflow-hidden h-full">
                  <CardContent className="p-8">
                    <div className="flex items-start space-x-6">
                      <div className="flex-shrink-0">
                        <motion.div 
                          className={`w-16 h-16 rounded-full flex items-center justify-center transition-all duration-300 ${method.color}`}
                          whileHover={{ 
                            scale: 1.1, 
                            rotate: 360,
                            backgroundColor: method.id === 1 ? '#16a34a' : 
                                           method.id === 2 ? '#2563eb' :
                                           method.id === 3 ? '#4338ca' : '#db2777'
                          }}
                          transition={{ duration: 0.6 }}
                        >
                          <IconComponent className="h-8 w-8 group-hover:text-white" />
                        </motion.div>
                      </div>
                      <div className="flex-1">
                        <h3 className="text-xl font-semibold text-neutral-800 mb-2">
                          {method.title}
                        </h3>
                        <p className="text-gray-600 mb-3">
                          {method.subtitle}
                        </p>
                        <p className="text-primary font-semibold mb-4">
                          {method.detail}
                        </p>
                        <motion.div
                          whileHover={{ scale: 1.05 }}
                          whileTap={{ scale: 0.95 }}
                        >
                          <Button
                            onClick={method.action}
                            className={`${method.buttonStyle} rounded-full px-6 py-2 font-semibold transition-all duration-300`}
                          >
                            {method.buttonText}
                          </Button>
                        </motion.div>
                      </div>
                    </div>
                  </CardContent>
                </Card>
              </motion.div>
            );
          })}
        </motion.div>

        {/* Quick Contact Info */}
        <motion.div 
          className="bg-white rounded-2xl p-8 md:p-12 shadow-lg"
          variants={itemVariants}
        >
          <div className="text-center mb-8">
            <h2 className="text-3xl font-bold text-neutral-800 mb-4">
              Informasi Kontak
            </h2>
            <p className="text-gray-600">
              Tim customer service kami siap membantu Anda
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {[
              {
                icon: Phone,
                title: 'Telepon',
                detail: '081392854911',
                subtitle: 'Senin - Sabtu, 08:00 - 17:00'
              },
              {
                icon: Mail,
                title: 'Email',
                detail: 'azharmaterial@gmail.com',
                subtitle: 'Respon dalam 24 jam'
              },
              {
                icon: MapPin,
                title: 'Lokasi',
                detail: 'Area Layanan',
                subtitle: 'Jakarta & Sekitarnya'
              }
            ].map((info, index) => (
              <motion.div
                key={info.title}
                className="text-center"
                variants={contactInfoVariants}
                transition={{ delay: index * 0.2 }}
              >
                <motion.div 
                  className="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4"
                  whileHover={{ scale: 1.1, backgroundColor: 'hsl(28, 34%, 49%)' }}
                  transition={{ duration: 0.3 }}
                >
                  <info.icon className="h-8 w-8 text-primary" />
                </motion.div>
                <h3 className="text-lg font-semibold text-neutral-800 mb-2">{info.title}</h3>
                <p className="text-gray-600">{info.detail}</p>
                <p className="text-sm text-gray-500 mt-1">{info.subtitle}</p>
              </motion.div>
            ))}
          </div>
        </motion.div>

        {/* CTA Section */}
        <motion.div 
          className="mt-16 bg-primary text-white rounded-2xl p-8 md:p-12 text-center"
          variants={itemVariants}
          whileHover={{ scale: 1.02 }}
          transition={{ duration: 0.3 }}
        >
          <h2 className="text-3xl md:text-4xl font-bold mb-4">
            Butuh Bantuan Segera?
          </h2>
          <p className="text-xl mb-8 text-accent">
            Tim ahli kami siap memberikan konsultasi gratis untuk proyek Anda
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <motion.div
              whileHover={{ scale: 1.05, y: -2 }}
              whileTap={{ scale: 0.95 }}
            >
              <Button
                onClick={() => window.open('https://wa.me/6281392854911', '_blank')}
                className="bg-white text-primary hover:bg-accent rounded-full px-8 py-3 font-semibold transition-all duration-300"
              >
                <MessageCircle className="h-5 w-5 mr-2" />
                Chat Sekarang
              </Button>
            </motion.div>
            <motion.div
              whileHover={{ scale: 1.05, y: -2 }}
              whileTap={{ scale: 0.95 }}
            >
              <Button
                onClick={() => window.open('tel:081392854911', '_blank')}
                variant="outline"
                className="border-2 border-white text-white hover:bg-white hover:text-primary rounded-full px-8 py-3 font-semibold transition-all duration-300"
              >
                <Phone className="h-5 w-5 mr-2" />
                Telepon Langsung
              </Button>
            </motion.div>
          </div>
        </motion.div>
      </div>
    </motion.div>
  );
};

export default Contact;
