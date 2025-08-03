@extends('layouts.app')

@section('title', 'Website Analytics')

@section('content')
    @livewire('content.analytics')
@endsection

@push('scripts')
<script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Connect to Socket.IO server
    const socket = io('http://localhost:3001');
    
    // Connection status
    socket.on('connect', function() {
        console.log('Connected to Socket.IO server');
        // Update connection status in Livewire
        @this.set('isConnected', true);
    });
    
    socket.on('disconnect', function() {
        console.log('Disconnected from Socket.IO server');
        @this.set('isConnected', false);
    });
    
    // Listen for real-time analytics updates
    socket.on('analytics-update', function(data) {
        console.log('Real-time analytics update received:', data);
        
        // Update Livewire component with new data
        @this.call('handleRealTimeUpdate', data);
    });
    
    // Track current page visit
    socket.emit('track-visitor', {
        page_visited: window.location.pathname,
        user_agent: navigator.userAgent,
        timestamp: new Date().toISOString()
    });
    
    // Auto-refresh every 30 seconds as fallback
    setInterval(function() {
        @this.call('loadAnalytics');
    }, 30000);
});
</script>
@endpush
