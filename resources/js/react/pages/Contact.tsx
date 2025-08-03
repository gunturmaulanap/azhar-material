import React from 'react';
import { MessageCircle, Mail, Facebook, Instagram, Phone, MapPin } from 'lucide-react';
import { Button } from '../components/ui/button';
import { Card, CardContent } from '../components/ui/card';

const Contact: React.FC = () => {
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
    <div className="min-h-screen bg-gray-50">
      {/* Breadcrumb */}
      <div className="bg-white py-4 border-b">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <nav className="text-sm">
            <span className="text-gray-500">Home</span>
            <span className="mx-2 text-gray-400">/</span>
            <span className="text-primary font-medium">Contact</span>
          </nav>
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        {/* Header */}
        <div className="text-center mb-16">
          <h1 className="text-4xl md:text-5xl font-bold text-neutral-800 mb-6">
            Hubungi Kami
          </h1>
          <p className="text-xl text-gray-600 max-w-3xl mx-auto">
            Siap membantu kebutuhan material bangunan Anda. Hubungi kami melalui platform yang Anda pilih.
          </p>
        </div>

        {/* Contact Methods Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
          {contactMethods.map((method) => {
            const IconComponent = method.icon;
            
            return (
              <Card key={method.id} className="group border-0 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 rounded-2xl overflow-hidden">
                <CardContent className="p-8">
                  <div className="flex items-start space-x-6">
                    <div className="flex-shrink-0">
                      <div className={`w-16 h-16 rounded-full flex items-center justify-center transition-all duration-300 ${method.color} group-${method.hoverColor}`}>
                        <IconComponent className="h-8 w-8" />
                      </div>
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
                      <Button
                        onClick={method.action}
                        className={`${method.buttonStyle} rounded-full px-6 py-2 font-semibold transition-all duration-300 transform hover:scale-105`}
                      >
                        {method.buttonText}
                      </Button>
                    </div>
                  </div>
                </CardContent>
              </Card>
            );
          })}
        </div>

        {/* Quick Contact Info */}
        <div className="bg-white rounded-2xl p-8 md:p-12 shadow-lg">
          <div className="text-center mb-8">
            <h2 className="text-3xl font-bold text-neutral-800 mb-4">
              Informasi Kontak
            </h2>
            <p className="text-gray-600">
              Tim customer service kami siap membantu Anda
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div className="text-center">
              <div className="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <Phone className="h-8 w-8 text-primary" />
              </div>
              <h3 className="text-lg font-semibold text-neutral-800 mb-2">Telepon</h3>
              <p className="text-gray-600">081392854911</p>
              <p className="text-sm text-gray-500 mt-1">Senin - Sabtu, 08:00 - 17:00</p>
            </div>

            <div className="text-center">
              <div className="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <Mail className="h-8 w-8 text-primary" />
              </div>
              <h3 className="text-lg font-semibold text-neutral-800 mb-2">Email</h3>
              <p className="text-gray-600">azharmaterial@gmail.com</p>
              <p className="text-sm text-gray-500 mt-1">Respon dalam 24 jam</p>
            </div>

            <div className="text-center">
              <div className="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <MapPin className="h-8 w-8 text-primary" />
              </div>
              <h3 className="text-lg font-semibold text-neutral-800 mb-2">Lokasi</h3>
              <p className="text-gray-600">Area Layanan</p>
              <p className="text-sm text-gray-500 mt-1">Jakarta & Sekitarnya</p>
            </div>
          </div>
        </div>

        {/* CTA Section */}
        <div className="mt-16 bg-primary text-white rounded-2xl p-8 md:p-12 text-center">
          <h2 className="text-3xl md:text-4xl font-bold mb-4">
            Butuh Bantuan Segera?
          </h2>
          <p className="text-xl mb-8 text-accent">
            Tim ahli kami siap memberikan konsultasi gratis untuk proyek Anda
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <Button
              onClick={() => window.open('https://wa.me/6281392854911', '_blank')}
              className="bg-white text-primary hover:bg-accent rounded-full px-8 py-3 font-semibold transition-all duration-300 transform hover:scale-105"
            >
              <MessageCircle className="h-5 w-5 mr-2" />
              Chat Sekarang
            </Button>
            <Button
              onClick={() => window.open('tel:081392854911', '_blank')}
              variant="outline"
              className="border-2 border-white text-white hover:bg-white hover:text-primary rounded-full px-8 py-3 font-semibold transition-all duration-300 transform hover:scale-105"
            >
              <Phone className="h-5 w-5 mr-2" />
              Telepon Langsung
            </Button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Contact;