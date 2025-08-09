import { useEffect, useRef, useCallback } from 'react';
import { useLocation } from 'react-router-dom';

interface UseScrollRestorationOptions {
  /**
   * Whether to restore scroll position on page refresh
   * @default true
   */
  restoreOnRefresh?: boolean;
  /**
   * Whether to scroll to top on route change
   * @default true
   */
  scrollToTopOnRouteChange?: boolean;
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
}

const useScrollRestoration = (options: UseScrollRestorationOptions = {}) => {
  const {
    restoreOnRefresh = true,
    scrollToTopOnRouteChange = true,
    smooth = true,
    delay = 0
  } = options;
  
  const location = useLocation();
  const isInitialLoad = useRef(true);

  // Custom smooth scroll with easing for better visual effect
  const smoothScrollToTop = useCallback((duration: number = 800) => {
    const startPosition = window.pageYOffset;
    const startTime = performance.now();

    const easeInOutCubic = (t: number): number => {
      return t < 0.5 ? 4 * t * t * t : (t - 1) * (2 * t - 2) * (2 * t - 2) + 1;
    };

    const animateScroll = (currentTime: number) => {
      const elapsed = currentTime - startTime;
      const progress = Math.min(elapsed / duration, 1);
      const easedProgress = easeInOutCubic(progress);
      
      const currentPosition = startPosition * (1 - easedProgress);
      window.scrollTo(0, currentPosition);

      if (progress < 1) {
        requestAnimationFrame(animateScroll);
      }
    };

    if (startPosition > 0) {
      requestAnimationFrame(animateScroll);
    }
  }, []);

  useEffect(() => {
    const scrollToPosition = (top = 0, left = 0) => {
      if (smooth && isInitialLoad.current && window.pageYOffset > 0) {
        // On initial load/refresh, use custom smooth animation if we're not at top
        smoothScrollToTop(600); // Shorter duration for refresh
      } else {
        // Regular scroll for route changes
        window.scrollTo({
          top,
          left,
          behavior: smooth ? 'smooth' : 'auto'
        });
      }
    };

    const handleScrollRestoration = () => {
      if (restoreOnRefresh && 'scrollRestoration' in history) {
        // Disable browser's default scroll restoration
        history.scrollRestoration = 'manual';
      }

      if (scrollToTopOnRouteChange) {
        if (delay > 0) {
          setTimeout(() => {
            scrollToPosition();
            isInitialLoad.current = false;
          }, delay);
        } else {
          scrollToPosition();
          isInitialLoad.current = false;
        }
      } else {
        isInitialLoad.current = false;
      }
    };

    handleScrollRestoration();
  }, [location, restoreOnRefresh, scrollToTopOnRouteChange, smooth, delay, smoothScrollToTop]);

  // Manual scroll to top function
  const scrollToTop = () => {
    window.scrollTo({
      top: 0,
      left: 0,
      behavior: smooth ? 'smooth' : 'auto'
    });
  };

  return { scrollToTop };
};

export default useScrollRestoration;
