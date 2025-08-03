import React from 'react';
import ReactDOM from 'react-dom/client';
import App from './App';
import './index.css';

// Get the root element for React app
const rootElement = document.getElementById('react-root');

if (rootElement) {
  const root = ReactDOM.createRoot(rootElement);
  root.render(
    <React.StrictMode>
      <App />
    </React.StrictMode>
  );
}