import React from 'react';
import { X, CheckCircle, XCircle, Info, AlertTriangle } from 'lucide-react';
import { useAppStore } from '../stores/appStore';

const NotificationIcons = {
  success: CheckCircle,
  error: XCircle,
  info: Info,
  warning: AlertTriangle,
};

const NotificationColors = {
  success: 'bg-green-50 border-green-200 text-green-800',
  error: 'bg-red-50 border-red-200 text-red-800',
  info: 'bg-blue-50 border-blue-200 text-blue-800',
  warning: 'bg-yellow-50 border-yellow-200 text-yellow-800',
};

const IconColors = {
  success: 'text-green-500',
  error: 'text-red-500',
  info: 'text-blue-500',
  warning: 'text-yellow-500',
};

const Notifications: React.FC = () => {
  const { notifications, removeNotification } = useAppStore();

  if (notifications.length === 0) {
    return null;
  }

  return (
    <div className="fixed top-4 right-4 z-50 space-y-2">
      {notifications.map((notification) => {
        const Icon = NotificationIcons[notification.type];
        const colorClass = NotificationColors[notification.type];
        const iconColorClass = IconColors[notification.type];

        return (
          <div
            key={notification.id}
            className={`flex items-start space-x-3 p-4 rounded-lg border shadow-lg max-w-sm animate-in slide-in-from-right-full ${colorClass}`}
          >
            <Icon className={`h-5 w-5 flex-shrink-0 mt-0.5 ${iconColorClass}`} />
            <div className="flex-1 min-w-0">
              <p className="text-sm font-medium">{notification.message}</p>
            </div>
            <button
              onClick={() => removeNotification(notification.id)}
              className="flex-shrink-0 p-1 rounded-md hover:bg-black/5 transition-colors"
            >
              <X className="h-4 w-4" />
            </button>
          </div>
        );
      })}
    </div>
  );
};

export default Notifications;
