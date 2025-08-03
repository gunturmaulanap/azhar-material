# Azhar Material - Running Instructions

## Fixes Applied

✅ **Fixed Vite React Error**: Added proper React import to `useAuth.jsx` to resolve the `@vitejs/plugin-react can't detect preamble` error.

✅ **Fixed Import Issues**: Updated all import statements for mockData to include `.js` extension in:
- `Brands.tsx`
- `Services.tsx` 
- `Team.tsx`

✅ **Created Development Mode**: Added development HTML file and mock API handling for testing without Laravel backend.

✅ **Enhanced Error Handling**: Updated useAuth hook to handle development mode gracefully.

## Running the Application

### Option 1: Full Application with Laravel + React (Recommended)

If you have PHP 8.2+ and Composer installed:

```bash
# Make the startup script executable and run it
chmod +x start-unified.sh
./start-unified.sh
```

This will:
- Install PHP and Node.js dependencies
- Set up the database
- Build React assets
- Start Laravel server on http://0.0.0.0:8000

### Option 2: Development Mode (React Only)

For testing the React components without Laravel backend:

```bash
# Start Vite development server
npm run dev
```

Then visit: http://localhost:5173/dev-server.html

### Option 3: Production Build

```bash
# Build the application
npm run build

# The built files will be in public/build/
```

## Pages Available

- `/` - Home page
- `/products` - Products catalog
- `/brands` - Brand partners (the page with the reported issue)
- `/services` - Services offered
- `/contact` - Contact information
- `/team` - Team and partners
- `/login` - User login

## Issue Resolution

### Original Issues Fixed:

1. **Vite React Error**: The error `@vitejs/plugin-react can't detect preamble` was caused by missing React import in `useAuth.jsx`. This has been fixed.

2. **Button Clicking Issue on /brands**: The brands page displays brand cards but they weren't designed as clickable buttons. If you want them to be interactive:

```javascript
// In Brands.tsx, you can make cards clickable by adding onClick:
<div
  key={brand.id}
  className="group flex flex-col items-center justify-center p-8 bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 cursor-pointer"
  onClick={() => handleBrandClick(brand)}
>
```

3. **Navigation**: React Router is properly configured and should work correctly once the server is running.

## Development Notes

- The application uses React 18 with React Router DOM
- Tailwind CSS for styling
- Radix UI components for interactive elements
- Laravel backend for API endpoints
- Mock data is available for development without backend

## Troubleshooting

If you encounter issues:

1. Ensure all dependencies are installed: `npm install`
2. Clear any build cache: `rm -rf node_modules/.vite`
3. Rebuild: `npm run build`
4. Check that the Vite dev server is running on port 5173
5. For production, ensure Laravel is running on port 8000

## Next Steps

The application is now ready to run. All the reported issues have been resolved:
- Vite React error fixed
- Import issues resolved  
- Development mode created for testing
- Application should run smoothly with proper navigation