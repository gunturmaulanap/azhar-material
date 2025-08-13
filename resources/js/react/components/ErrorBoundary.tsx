import React, { Component, ReactNode } from "react";
import { useLocation } from "react-router-dom";
import { AlertTriangle, RefreshCw } from "lucide-react";

type FallbackRender = (args: {
  error?: Error;
  reset: () => void;
}) => React.ReactNode;

type BoundaryProps = {
  children: ReactNode;
  /** Bisa elemen langsung, atau function fallback(error, reset) */
  fallback?: ReactNode | FallbackRender;
  /** Kunci yang bila berubah akan mereset boundary (mis. pathname) */
  resetKeys?: unknown[];
};

type State = { hasError: boolean; error?: Error };

/** Kelas inti – tidak menyentuh DOM sama sekali */
class CoreErrorBoundary extends Component<BoundaryProps, State> {
  state: State = { hasError: false, error: undefined };

  static getDerivedStateFromError(error: Error): State {
    return { hasError: true, error };
  }

  componentDidCatch(error: Error, info: React.ErrorInfo) {
    console.error("ErrorBoundary caught:", error, info);
  }

  componentDidUpdate(prevProps: BoundaryProps) {
    // Reset otomatis saat resetKeys berubah (contoh: pindah route)
    if (this.state.hasError) {
      const a = this.props.resetKeys ?? [];
      const b = prevProps.resetKeys ?? [];
      const changed = a.length !== b.length || a.some((v, i) => v !== b[i]);
      if (changed) this.reset();
    }
  }

  reset = () => this.setState({ hasError: false, error: undefined });

  render() {
    if (this.state.hasError) {
      const { fallback } = this.props;

      if (typeof fallback === "function") {
        return (fallback as FallbackRender)({
          error: this.state.error,
          reset: this.reset,
        });
      }
      if (fallback) return fallback as React.ReactElement;

      // Default fallback simpel & aman
      return (
        <div className="min-h-screen flex items-center justify-center bg-gray-50">
          <div className="max-w-md w-full mx-auto p-6">
            <div className="bg-white rounded-lg shadow-lg p-8 text-center">
              <div className="flex justify-center mb-4">
                <AlertTriangle className="h-12 w-12 text-red-500" />
              </div>
              <h1 className="text-xl font-semibold text-gray-900 mb-2">
                Terjadi Kesalahan
              </h1>
              <p className="text-gray-600 mb-6">
                Maaf, terjadi kesalahan yang tidak terduga. Silakan muat ulang
                halaman atau coba lagi.
              </p>
              <div className="space-y-3">
                <button
                  onClick={() => window.location.reload()}
                  className="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                >
                  <RefreshCw className="h-4 w-4 mr-2" />
                  Muat Ulang Halaman
                </button>
                <button
                  onClick={this.reset}
                  className="w-full px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors"
                >
                  Coba Lagi
                </button>
              </div>

              {this.state.error && (
                <details className="mt-4 text-left">
                  <summary className="cursor-pointer text-sm text-gray-500">
                    Detail Error (untuk developer)
                  </summary>
                  <pre className="mt-2 text-xs text-red-600 bg-red-50 p-2 rounded overflow-auto">
                    {String(this.state.error)}
                  </pre>
                </details>
              )}
            </div>
          </div>
        </div>
      );
    }

    return this.props.children;
  }
}

/** Default export: wrapper yang reset saat route berubah */
const ErrorBoundary: React.FC<Omit<BoundaryProps, "resetKeys">> = ({
  children,
  fallback,
}) => {
  const location = useLocation(); // React Router v6
  return (
    <CoreErrorBoundary
      fallback={fallback}
      resetKeys={[location.pathname, location.key]}
    >
      {children}
    </CoreErrorBoundary>
  );
};

export default ErrorBoundary;
export { CoreErrorBoundary as RawErrorBoundary };
