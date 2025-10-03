<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Notifications') }}
            </h2>
            @if($unreadCount > 0)
                <button id="mark-all-read" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                    Mark All as Read ({{ $unreadCount }})
                </button>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($notifications->count() > 0)
                        <div class="space-y-4">
                            @foreach($notifications as $notification)
                                <div class="notification-item flex items-start space-x-4 p-4 rounded-xl {{ $notification->is_read ? 'bg-gray-50' : 'bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200' }} transition-all duration-200 hover:shadow-md" data-id="{{ $notification->id }}">
                                    <div class="flex-shrink-0 mt-1">
                                        <div class="h-3 w-3 rounded-full {{ $notification->is_read ? 'bg-gray-400' : 'bg-gradient-to-r from-blue-500 to-indigo-500' }}"></div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <p class="text-sm font-bold text-gray-900">{{ $notification->title }}</p>
                                                <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                                                <div class="flex items-center mt-2 space-x-4">
                                                    <p class="text-xs text-gray-400 font-medium">{{ $notification->created_at->diffForHumans() }}</p>
                                                    @if($notification->type)
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $notification->type_color }}-100 text-{{ $notification->type_color }}-800">
                                                            {{ ucfirst($notification->type) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2 ml-4">
                                                @if(!$notification->is_read)
                                                    <button class="mark-as-read text-blue-600 hover:text-blue-800 text-sm font-medium" data-id="{{ $notification->id }}">
                                                        Mark as Read
                                                    </button>
                                                @endif
                                                <button class="delete-notification text-red-600 hover:text-red-800 text-sm font-medium" data-id="{{ $notification->id }}">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-2H4v2zM4 15h8v-2H4v2zM4 11h8V9H4v2z"></path>
                                </svg>
                            </div>
                            <p class="text-lg font-medium text-gray-500">No notifications</p>
                            <p class="text-sm text-gray-400 mt-1">You're all caught up!</p>
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center mt-4 text-blue-600 hover:text-blue-800 font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to Dashboard
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mark single notification as read
            document.querySelectorAll('.mark-as-read').forEach(button => {
                button.addEventListener('click', function() {
                    const notificationId = this.getAttribute('data-id');
                    markAsRead(notificationId);
                });
            });

            // Mark all notifications as read
            const markAllReadBtn = document.getElementById('mark-all-read');
            if (markAllReadBtn) {
                markAllReadBtn.addEventListener('click', function() {
                    markAllAsRead();
                });
            }

            // Delete notification
            document.querySelectorAll('.delete-notification').forEach(button => {
                button.addEventListener('click', function() {
                    const notificationId = this.getAttribute('data-id');
                    deleteNotification(notificationId);
                });
            });

            function markAsRead(notificationId) {
                fetch(`/notifications/${notificationId}/read`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const notificationItem = document.querySelector(`[data-id="${notificationId}"]`);
                        notificationItem.classList.remove('bg-gradient-to-r', 'from-blue-50', 'to-indigo-50', 'border', 'border-blue-200');
                        notificationItem.classList.add('bg-gray-50');

                        const indicator = notificationItem.querySelector('.h-3.w-3');
                        indicator.classList.remove('bg-gradient-to-r', 'from-blue-500', 'to-indigo-500');
                        indicator.classList.add('bg-gray-400');

                        const markAsReadBtn = notificationItem.querySelector('.mark-as-read');
                        if (markAsReadBtn) {
                            markAsReadBtn.remove();
                        }

                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to mark notification as read');
                });
            }

            function markAllAsRead() {
                fetch('/notifications/mark-all-read', {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to mark all notifications as read');
                });
            }

            function deleteNotification(notificationId) {
                if (confirm('Are you sure you want to delete this notification?')) {
                    fetch(`/notifications/${notificationId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.querySelector(`[data-id="${notificationId}"]`).remove();
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to delete notification');
                    });
                }
            }
        });
    </script>
</x-app-layout>