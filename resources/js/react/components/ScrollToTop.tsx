import React, { useEffect, useRef } from 'react';
import { useLocation } from 'react-router-dom';

interface ScrollToTopProps {
  /**
   * Smooth scroll behavior
   * @default true
   */
  smooth?: boolean;
  /**
   * Delay before scrolling (in ms)
   * @default 0
   */
  delay?: number;
  /**
   * Scroll only on pathname change, ignore search params and hash
   * @default false
   */
  pathnameOnly?: boolean;
  /**
   * Disable on initial load to prevent conflict with useScrollRestoration
   * @default true
   */
  skipInitialLoad?: boolean;
}

const ScrollToTop: React.FC<ScrollToTopProps> = ({ 
  smooth = true, 
  delay = 0,
  pathnameOnly = false,
  skipInitialLoad = true
}) => {
  const location = useLocation();
  const isInitialLoad = useRef(true);

  useEffect(() => {
    // Skip scroll on initial load to let useScrollRestoration handle it
    if (skipInitialLoad && isInitialLoad.current) {
      isInitialLoad.current = false;
      return;
    }

    const scrollToTop = () => {
      window.scrollTo({
        top: 0,
        left: 0,
        behavior: smooth ? 'smooth' : 'auto'
      });
    };

    if (delay > 0) {
      const timeoutId = setTimeout(scrollToTop, delay);
      return () => clearTimeout(timeoutId);
    } else {
      scrollToTop();
    }
  }, [pathnameOnly ? location.pathname : location, smooth, delay, skipInitialLoad]);

  // This component doesn't render anything
  return null;
};

export default ScrollToTop;
