import React from "react";
import { Handshake, Award, Users, Building2 } from "lucide-react";
import { Card, CardContent } from "../components/ui/card";
import { mockTeamPartners } from "../utils/mockData";

const Team: React.FC = () => {
  return (
    <div className="min-h-screen bg-gray-50">
      {/* Breadcrumb */}
      <div className="bg-white py-4 border-b">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <nav className="text-sm">
            <span className="text-gray-500">Home</span>
            <span className="mx-2 text-gray-400">/</span>
            <span className="text-primary font-medium">Team</span>
          </nav>
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        {/* Header */}
        <div className="text-center mb-16">
          <h1 className="text-4xl md:text-5xl font-bold text-neutral-800 mb-6">
            Mitra dan Kerjasama
          </h1>
          <p className="text-xl text-gray-600 max-w-3xl mx-auto">
            Kami bekerja sama dengan perusahaan konstruksi terpercaya untuk
            memberikan solusi material terbaik bagi proyek-proyek besar.
          </p>
        </div>

        {/* Partners Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
          {mockTeamPartners.map((partner) => (
            <Card
              key={partner.id}
              className="group border-0 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 rounded-2xl overflow-hidden"
            >
              <CardContent className="p-8 text-center">
                <div className="w-full h-20 flex items-center justify-center mb-6">
                  <img
                    src={partner.logo}
                    alt={partner.name}
                    className="max-w-full max-h-full object-contain grayscale group-hover:grayscale-0 transition-all duration-300"
                  />
                </div>
                <h3 className="text-lg font-semibold text-neutral-800">
                  {partner.name}
                </h3>
              </CardContent>
            </Card>
          ))}
        </div>

        {/* Partnership Benefits */}
        <div className="bg-white rounded-2xl p-8 md:p-12 shadow-lg mb-16">
          <h2 className="text-3xl font-bold text-neutral-800 mb-8 text-center">
            Keunggulan Kemitraan
          </h2>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div className="text-center">
              <div className="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <Handshake className="h-8 w-8 text-blue-600" />
              </div>
              <h3 className="text-lg font-semibold text-neutral-800 mb-2">
                Kemitraan Strategis
              </h3>
              <p className="text-gray-600">
                Hubungan jangka panjang dengan supplier dan kontraktor
                terpercaya.
              </p>
            </div>
            <div className="text-center">
              <div className="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <Award className="h-8 w-8 text-green-600" />
              </div>
              <h3 className="text-lg font-semibold text-neutral-800 mb-2">
                Standar Kualitas
              </h3>
              <p className="text-gray-600">
                Semua mitra telah teruji kualitas dan kredibilitasnya di
                industri konstruksi.
              </p>
            </div>
            <div className="text-center">
              <div className="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <Users className="h-8 w-8 text-purple-600" />
              </div>
              <h3 className="text-lg font-semibold text-neutral-800 mb-2">
                Tim Profesional
              </h3>
              <p className="text-gray-600">
                Didukung oleh tim ahli berpengalaman dalam berbagai jenis
                proyek.
              </p>
            </div>
            <div className="text-center">
              <div className="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <Building2 className="h-8 w-8 text-orange-600" />
              </div>
              <h3 className="text-lg font-semibold text-neutral-800 mb-2">
                Proyek Beragam
              </h3>
              <p className="text-gray-600">
                Pengalaman menangani proyek dari residential hingga commercial
                scale.
              </p>
            </div>
          </div>
        </div>

        {/* Partnership Types */}
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-16">
          <Card className="border-0 shadow-lg rounded-2xl overflow-hidden">
            <CardContent className="p-8">
              <h3 className="text-2xl font-bold text-neutral-800 mb-4">
                Supplier Partners
              </h3>
              <p className="text-gray-600 mb-6">
                Jaringan supplier material berkualitas yang menjamin
                ketersediaan stok dan kompetitifitas harga.
              </p>
              <ul className="space-y-3">
                <li className="flex items-center text-gray-700">
                  <span className="w-2 h-2 bg-primary rounded-full mr-3"></span>
                  Pabrik semen dan beton ready-mix
                </li>
                <li className="flex items-center text-gray-700">
                  <span className="w-2 h-2 bg-primary rounded-full mr-3"></span>
                  Distributor baja dan material logam
                </li>
                <li className="flex items-center text-gray-700">
                  <span className="w-2 h-2 bg-primary rounded-full mr-3"></span>
                  Importir keramik dan finishing material
                </li>
              </ul>
            </CardContent>
          </Card>

          <Card className="border-0 shadow-lg rounded-2xl overflow-hidden">
            <CardContent className="p-8">
              <h3 className="text-2xl font-bold text-neutral-800 mb-4">
                Contractor Partners
              </h3>
              <p className="text-gray-600 mb-6">
                Kolaborasi dengan kontraktor berpengalaman untuk memberikan
                solusi konstruksi menyeluruh.
              </p>
              <ul className="space-y-3">
                <li className="flex items-center text-gray-700">
                  <span className="w-2 h-2 bg-primary rounded-full mr-3"></span>
                  Kontraktor gedung dan infrastruktur
                </li>
                <li className="flex items-center text-gray-700">
                  <span className="w-2 h-2 bg-primary rounded-full mr-3"></span>
                  Spesialis interior dan finishing
                </li>
                <li className="flex items-center text-gray-700">
                  <span className="w-2 h-2 bg-primary rounded-full mr-3"></span>
                  Developer dan property management
                </li>
              </ul>
            </CardContent>
          </Card>
        </div>

        {/* CTA Section */}
        <div className="bg-primary text-white rounded-2xl p-8 md:p-12 text-center">
          <h2 className="text-3xl md:text-4xl font-bold mb-4">
            Ingin Bermitra Dengan Kami?
          </h2>
          <p className="text-xl mb-8 text-accent">
            Bergabunglah dengan jaringan mitra terpercaya dan kembangkan bisnis
            konstruksi Anda bersama kami.
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <button
              onClick={() =>
                window.open("https://wa.me/6281392854911", "_blank")
              }
              className="inline-flex items-center px-6 py-3 bg-white text-primary rounded-full font-semibold hover:bg-accent transition-all duration-300 transform hover:scale-105"
            >
              <Handshake className="h-5 w-5 mr-2" />
              Ajukan Kemitraan
            </button>
            <button
              onClick={() =>
                window.open("mailto:azharmaterial@gmail.com", "_blank")
              }
              className="inline-flex items-center px-6 py-3 border-2 border-white text-white rounded-full font-semibold hover:bg-white hover:text-primary transition-all duration-300 transform hover:scale-105"
            >
              Hubungi Tim Kami
            </button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Team;
