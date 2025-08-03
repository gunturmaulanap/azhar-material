import React from "react";
import { useAuth } from "../hooks/useAuth";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "../components/ui/card";
import { Button } from "../components/ui/button";
import {
  FileText,
  Image,
  Users as UsersIcon,
  Settings,
  LogOut,
  Edit3,
  Globe,
  Camera,
  MessageSquare,
} from "lucide-react";
import { useNavigate } from "react-router-dom";

const ContentAdminDashboard = () => {
  const { user, logout } = useAuth();
  const navigate = useNavigate();

  const handleLogout = async () => {
    await logout();
    navigate("/login");
  };

  const contentCards = [
    {
      title: "Hero Sections",
      description: "Manage homepage banners",
      icon: Image,
      value: "5",
      color: "bg-blue-500",
    },
    {
      title: "Products",
      description: "Manage product catalog",
      icon: FileText,
      value: "120+",
      color: "bg-green-500",
    },
    {
      title: "Team Members",
      description: "Manage team profiles",
      icon: UsersIcon,
      value: "15",
      color: "bg-orange-500",
    },
    {
      title: "Brand Partners",
      description: "Manage brand logos",
      icon: Globe,
      value: "25+",
      color: "bg-purple-500",
    },
  ];

  const contentActions = [
    {
      title: "Edit Hero Section",
      description: "Update homepage banners and promotions",
      icon: Image,
      action: () => navigate("/admin/content/hero"),
    },
    {
      title: "Manage Products",
      description: "Add, edit, or remove products",
      icon: FileText,
      action: () => navigate("/admin/content/products"),
    },
    {
      title: "Team & About Us",
      description: "Update team member profiles",
      icon: UsersIcon,
      action: () => navigate("/admin/content/team"),
    },
    {
      title: "Services",
      description: "Manage service offerings",
      icon: Settings,
      action: () => navigate("/admin/content/services"),
    },
    {
      title: "Brand Partners",
      description: "Manage partner brand logos",
      icon: Globe,
      action: () => navigate("/admin/content/brands"),
    },
    {
      title: "Website Gallery",
      description: "Manage website images",
      icon: Camera,
      action: () => navigate("/admin/content/gallery"),
    },
  ];

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <header className="bg-white shadow-sm border-b">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center h-16">
            <div className="flex items-center">
              <div className="w-8 h-8 bg-gradient-to-r from-green-600 to-blue-500 rounded-lg flex items-center justify-center mr-3">
                <Edit3 className="h-4 w-4 text-white" />
              </div>
              <div>
                <h1 className="text-xl font-semibold text-gray-900">
                  Content Management
                </h1>
                <p className="text-sm text-gray-500">
                  Azhar Material Website Content
                </p>
              </div>
            </div>

            <div className="flex items-center space-x-4">
              <Button
                variant="outline"
                size="sm"
                onClick={() => navigate("/")}
                className="flex items-center space-x-2"
              >
                <Globe className="h-4 w-4" />
                <span>View Website</span>
              </Button>
              <div className="text-right">
                <p className="text-sm font-medium text-gray-900">
                  {user?.name}
                </p>
                <p className="text-xs text-gray-500 capitalize">{user?.role}</p>
              </div>
              <Button
                variant="outline"
                size="sm"
                onClick={handleLogout}
                className="flex items-center space-x-2"
              >
                <LogOut className="h-4 w-4" />
                <span>Logout</span>
              </Button>
            </div>
          </div>
        </div>
      </header>

      {/* Main Content */}
      <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {/* Welcome Section */}
        <div className="mb-8">
          <h2 className="text-2xl font-bold text-gray-900 mb-2">
            Welcome, {user?.name}!
          </h2>
          <p className="text-gray-600">
            Manage and update the Azhar Material website content from here.
          </p>
        </div>

        {/* Content Statistics */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          {contentCards.map((card, index) => (
            <Card key={index} className="hover:shadow-lg transition-shadow">
              <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                <CardTitle className="text-sm font-medium">
                  {card.title}
                </CardTitle>
                <div className={`p-2 rounded-lg ${card.color}`}>
                  <card.icon className="h-4 w-4 text-white" />
                </div>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">{card.value}</div>
                <p className="text-xs text-muted-foreground">
                  {card.description}
                </p>
              </CardContent>
            </Card>
          ))}
        </div>

        {/* Content Management Actions */}
        <div className="mb-8">
          <h3 className="text-lg font-semibold text-gray-900 mb-4">
            Content Management
          </h3>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            {contentActions.map((action, index) => (
              <Card
                key={index}
                className="hover:shadow-md transition-shadow cursor-pointer"
                onClick={action.action}
              >
                <CardHeader className="text-center">
                  <div className="mx-auto w-12 h-12 bg-gradient-to-r from-green-100 to-blue-100 rounded-lg flex items-center justify-center mb-2">
                    <action.icon className="h-6 w-6 text-green-600" />
                  </div>
                  <CardTitle className="text-sm">{action.title}</CardTitle>
                  <CardDescription className="text-xs">
                    {action.description}
                  </CardDescription>
                </CardHeader>
              </Card>
            ))}
          </div>
        </div>

        {/* Quick Tips */}
        <Card>
          <CardHeader>
            <CardTitle className="flex items-center space-x-2">
              <MessageSquare className="h-5 w-5" />
              <span>Content Management Tips</span>
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              {[
                "Keep hero section images high quality and relevant to current promotions",
                "Update product information regularly to reflect current stock and prices",
                "Ensure team member information is accurate and professional",
                "Optimize images for web to improve page loading speed",
                "Review content for spelling and grammar before publishing",
              ].map((tip, index) => (
                <div
                  key={index}
                  className="flex items-start space-x-3 p-3 bg-green-50 rounded-lg"
                >
                  <div className="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                  <span className="text-sm text-gray-700">{tip}</span>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>
      </main>
    </div>
  );
};

export default ContentAdminDashboard;
