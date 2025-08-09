import { useEffect, useRef, useState } from 'react';

interface UseScrollAnimationOptions {
  threshold?: number;
  rootMargin?: string;
  triggerOnce?: boolean;
  delay?: number;
}

export const useScrollAnimation = (options: UseScrollAnimationOptions = {}) => {
  const {
    threshold = 0.1,
    rootMargin = '0px',
    triggerOnce = true,
    delay = 0
  } = options;

  const [isVisible, setIsVisible] = useState(false);
  const [hasTriggered, setHasTriggered] = useState(false);
  const elementRef = useRef<HTMLElement>(null);

  useEffect(() => {
    const element = elementRef.current;
    if (!element) return;

    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting && (!triggerOnce || !hasTriggered)) {
          if (delay > 0) {
            setTimeout(() => {
              setIsVisible(true);
              if (triggerOnce) setHasTriggered(true);
            }, delay);
          } else {
            setIsVisible(true);
            if (triggerOnce) setHasTriggered(true);
          }
        } else if (!triggerOnce) {
          setIsVisible(false);
        }
      },
      {
        threshold,
        rootMargin
      }
    );

    observer.observe(element);

    return () => {
      observer.unobserve(element);
    };
  }, [threshold, rootMargin, triggerOnce, delay, hasTriggered]);

  return { elementRef, isVisible };
};

// Animation variant configurations
export const animationVariants = {
  fadeInUp: {
    hidden: {
      opacity: 0,
      y: 30
    },
    visible: {
      opacity: 1,
      y: 0,
      transition: {
        duration: 0.6,
        ease: 'easeOut'
      }
    }
  },
  fadeInScale: {
    hidden: {
      opacity: 0,
      scale: 0.95,
      y: 20
    },
    visible: {
      opacity: 1,
      scale: 1,
      y: 0,
      transition: {
        duration: 0.6,
        ease: 'easeOut'
      }
    }
  },
  slideInLeft: {
    hidden: {
      opacity: 0,
      x: -30
    },
    visible: {
      opacity: 1,
      x: 0,
      transition: {
        duration: 0.6,
        ease: 'easeOut'
      }
    }
  },
  slideInRight: {
    hidden: {
      opacity: 0,
      x: 30
    },
    visible: {
      opacity: 1,
      x: 0,
      transition: {
        duration: 0.6,
        ease: 'easeOut'
      }
    }
  },
  staggerContainer: {
    visible: {
      transition: {
        staggerChildren: 0.1,
        delayChildren: 0.2
      }
    }
  },
  staggerItem: {
    hidden: {
      opacity: 0,
      y: 30
    },
    visible: {
      opacity: 1,
      y: 0,
      transition: {
        duration: 0.6,
        ease: 'easeOut'
      }
    }
  }
};

// CSS class generator for animations
export const getAnimationClasses = (
  animationType: 'fadeInUp' | 'fadeInScale' | 'slideInLeft' | 'slideInRight' | 'pulseGlow',
  isVisible: boolean,
  delay: number = 0
) => {
  const baseClass = `animate-${animationType.replace(/([A-Z])/g, '-$1').toLowerCase()}`;
  const delayClass = delay > 0 ? `animate-delay-${delay}` : '';
  
  if (isVisible) {
    return `${baseClass} ${delayClass}`.trim();
  }
  
  return 'opacity-0';
};

// Utility function to create staggered animations
export const createStaggeredAnimation = (
  itemCount: number,
  baseDelay: number = 100
) => {
  return Array.from({ length: itemCount }, (_, index) => ({
    delay: index * baseDelay,
    animationDelay: `${index * baseDelay}ms`
  }));
};

// Enhanced scroll animation hook with more features
export const useAdvancedScrollAnimation = (options: UseScrollAnimationOptions & {
  enableParallax?: boolean;
  parallaxSpeed?: number;
}) => {
  const {
    threshold = 0.1,
    rootMargin = '0px',
    triggerOnce = true,
    delay = 0,
    enableParallax = false,
    parallaxSpeed = 0.5
  } = options;

  const [isVisible, setIsVisible] = useState(false);
  const [scrollY, setScrollY] = useState(0);
  const elementRef = useRef<HTMLElement>(null);

  useEffect(() => {
    const element = elementRef.current;
    if (!element) return;

    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          if (delay > 0) {
            setTimeout(() => setIsVisible(true), delay);
          } else {
            setIsVisible(true);
          }
        } else if (!triggerOnce) {
          setIsVisible(false);
        }
      },
      { threshold, rootMargin }
    );

    observer.observe(element);

    // Parallax effect
    if (enableParallax) {
      const handleScroll = () => {
        const rect = element.getBoundingClientRect();
        const elementTop = rect.top + window.scrollY;
        const windowScrollY = window.scrollY;
        const parallaxValue = (windowScrollY - elementTop) * parallaxSpeed;
        setScrollY(parallaxValue);
      };

      window.addEventListener('scroll', handleScroll, { passive: true });
      
      return () => {
        observer.unobserve(element);
        window.removeEventListener('scroll', handleScroll);
      };
    }

    return () => observer.unobserve(element);
  }, [threshold, rootMargin, triggerOnce, delay, enableParallax, parallaxSpeed]);

  return { 
    elementRef, 
    isVisible, 
    parallaxStyle: enableParallax ? { 
      transform: `translateY(${scrollY}px)` 
    } : {}
  };
};

export default useScrollAnimation;
