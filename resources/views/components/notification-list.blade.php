@if ($notifications->isEmpty())
    <p class="text-gray-500 text-center text-lg">No notifications found.</p>
@else
    @php
        $newNotifications = $notifications->where('created_at', '>=', now()->subDays(1));
        $earlierNotifications = $notifications->where('created_at', '<', now()->subDays(1));
    @endphp

    @if ($newNotifications->isNotEmpty())
        <h3 class="notification-group-title">New</h3>
        @foreach ($newNotifications as $notification)
            <div class="notification-item">
                <div class="flex-shrink-0">
                    @if (!$notification->is_read)
                        <span class="notification-badge">New</span>
                    @endif
                </div>
                <img src="{{ \App\Models\Profile::myProfile($notification->from_user_id) ? asset(\App\Models\Profile::myProfile($notification->from_user_id)->path) : asset('resources/img/default_female.png')}}"
                    class="profile-image" alt="Profile Image">
                <div>
                    <p class="notification-message">
                        {{ $notification->message }}
                    </p>
                    <p class="notification-time">
                        {{ $notification->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
        @endforeach
    @endif

    @if ($earlierNotifications->isNotEmpty())
        <h3 class="notification-group-title">Earlier</h3>
        @foreach ($earlierNotifications as $notification)
            <div class="notification-item">
                <div class="flex-shrink-0">
                    @if (!$notification->is_read)
                        <span class="notification-badge">New</span>
                    @endif
                </div>
                <img src="{{ \App\Models\Profile::myProfile($notification->from_user_id) ? asset(\App\Models\Profile::myProfile($notification->from_user_id)->path) : asset('resources/img/default_female.png')}}"
                    class="profile-image" alt="Profile Image">
                <div>
                    <p class="notification-message">
                        {{ $notification->message }}
                    </p>
                    <p class="notification-time">
                        {{ $notification->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
        @endforeach
    @endif
@endif