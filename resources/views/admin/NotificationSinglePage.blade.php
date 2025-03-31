<head>
    <title>{{ env('APP_NAME') }} | SMM | Create Direct Job Order</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }

        .notification-item {
            padding: 1.25rem;
            border-bottom: 1px solid #e5e7eb;
            transition: background-color 0.2s ease-in-out;
            display: flex;
            align-items: center;
            gap: 1rem;
            border-radius: 0.5rem;
            background-color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item:hover {
            background-color: #f9fafb;
        }

        .notification-badge {
            background-color: #ef4444;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .notification-time {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .notification-message {
            font-weight: 500;
            color: #374151;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .profile-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .tab-button {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.2s ease;
            font-weight: 500;
        }

        .tab-button.active {
            background-color: #e5e7eb;
        }

        .notification-group-title {
            font-weight: 600;
            color: #1f2937;
            padding: 1rem 0;
            font-size: 1.125rem;
        }

        .notification-container {
            max-width: 90rem;
            margin: -8rem auto;
            padding: 1.5rem;
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            position: relative;
        }

        .notification-header {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }

        #loading-indicator {
            text-align: center;
            padding: 1rem 0;
            display: none;
        }
    </style>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
</head>

<x-main-layout breadcumb="Hello" page="hello">
    <div>
        <section class="flex items-center gap-2 hidden">
            <div class="dropdown relative inline-flex self-center">
                <button type="button" id="dropdown-notification-fake"
                    class="dropdown-notification-fake w-10 h-10 relative text-orange-500 bg-white p-2 rounded-full hover:bg-gray-100 cursor-pointer">
                    <span class="mi--notification bg-blue-600 w-full h-full relative"></span>
                    @if ($notifications->where('is_read', 0)->count())
                        <div class="absolute -top-2 -right-2">
                        </div>
                    @endif
                </button>
            </div>
        </section>
    </div>

    <div class="notification-container">
        <div class="notification-header">
            <h2 class="text-2xl font-semibold text-gray-800">Notifications</h2>
            <div class="mt-4 flex gap-2">
                <button class="tab-button active" data-tab="all">All</button>
                <button class="tab-button" data-tab="unread">Unread</button>
            </div>
        </div>
        <div id="notification-list">
            @include('components.notification-list', ['notifications' => $notifications])
        </div>
        <div id="loading-indicator">Loading...</div>
        <div id="no-more-notifications" style="display: none;" class="text-center py-4">No more notifications found.
        </div>
    </div>

    <script>
        (function () {
            document.addEventListener('DOMContentLoaded', function () {

                const dropdownNotification = document.getElementById('dropdown-notification');
                if (dropdownNotification) {
                    dropdownNotification.classList.add('hidden');
                }
                const dropdownNotificationFake = document.getElementById('dropdown-notification-fake');
                const sectionElement = document.querySelector('section.flex.items-center.gap-2');
                const breadCumbSectionElement = document.querySelector('section.flex.flex-col.gap-3.select-none');

                if (sectionElement && dropdownNotificationFake && breadCumbSectionElement) {
                    sectionElement.appendChild(dropdownNotificationFake);
                    dropdownNotificationFake.classList.remove('hidden');
                    breadCumbSectionElement.classList.add('hidden');
                } else {
                    if (!sectionElement) {
                        console.error('Section element not found.');
                    }
                    if (!dropdownNotificationFake) {
                        console.error('dropdown-notification-fake element not found.');
                    }
                }

                let currentPage = 1;
                let isLoading = false;
                const notificationList = document.getElementById('notification-list');
                const loadingIndicator = document.getElementById('loading-indicator');
                const noMoreNotifications = document.getElementById('no-more-notifications');
                let currentTab = 'all';
                let viewedNotifications = new Set(); // Track viewed notifications

                function loadMoreNotifications() {
                    if (isLoading) return;
                    isLoading = true;
                    currentPage++;
                    loadingIndicator.style.display = 'block';

                    fetch(`/notifications/seeAll/?tab=${currentTab}&page=${currentPage}`)
                        .then(response => response.json())
                        .then(notifications => {
                            loadingIndicator.style.display = 'none';
                            if (notifications.notifications.length > 0) {
                                displayNotifications(notifications);
                            } else {
                                noMoreNotifications.style.display = 'block';
                            }
                            isLoading = false;
                        })
                        .catch(() => {
                            loadingIndicator.style.display = 'none';
                            isLoading = false;
                        });
                }

                function displayNotifications(notifications) {
                    const newNotifications = notifications.notifications.filter(notification =>
                        new Date(notification.created_at) >= new Date(Date.now() - 86400000) && !viewedNotifications.has(notification.id)
                    );
                    const earlierNotifications = notifications.notifications.filter(notification =>
                        new Date(notification.created_at) < new Date(Date.now() - 86400000) || viewedNotifications.has(notification.id)
                    );

                    if (newNotifications.length > 0) {
                        if (currentPage === 1) {
                            notificationList.innerHTML += '<h3 class="notification-group-title">New</h3>';
                        }
                        newNotifications.forEach(notification => {
                            notificationList.innerHTML += createNotificationItem(notification);
                        });
                    }

                    if (earlierNotifications.length > 0) {
                        if (currentPage === 1 && newNotifications.length > 0) {
                            notificationList.innerHTML += '<h3 class="notification-group-title">Earlier</h3>';
                        } else if (currentPage === 1 && newNotifications.length === 0) {
                            notificationList.innerHTML += '<h3 class="notification-group-title">Earlier</h3>';
                        }
                        earlierNotifications.forEach(notification => {
                            notificationList.innerHTML += createNotificationItem(notification);
                        });
                    }
                }

                function createNotificationItem(notification) {
                    const profileImageUrl = notification.from_user_id
                        ? `/get-profile-image/${notification.from_user_id}`
                        : 'resources/img/default_female.png';

                    return `
                        <div class="notification-item" data-notification-id="${notification.id}">
                            <div class="flex-shrink-0">
                                ${notification.is_read == 0 ? '<span class="notification-badge">New</span>' : ''}
                            </div>
                            <img src="${profileImageUrl}" class="profile-image" alt="Profile Image">
                            <div>
                                <p class="notification-message">${notification.message}</p>
                                <p class="notification-time">${timeSince(new Date(notification.created_at))}</p>
                            </div>
                        </div>
                    `;
                }

                function timeSince(date) {
                    const seconds = Math.floor((new Date() - date) / 1000);
                    let interval = Math.floor(seconds / 31536000);

                    if (interval > 1) return interval + " years ago";
                    interval = Math.floor(seconds / 2592000);
                    if (interval > 1) return interval + " months ago";
                    interval = Math.floor(seconds / 86400);
                    if (interval > 1) return interval + " days ago";
                    interval = Math.floor(seconds / 3600);
                    if (interval > 1) return interval + " hours ago";
                    interval = Math.floor(seconds / 60);
                    if (interval > 1) return interval + " minutes ago";
                    return Math.floor(seconds) + " seconds ago";
                }

                const tabButtons = document.querySelectorAll('.tab-button');

                tabButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        tabButtons.forEach(btn => btn.classList.remove('active'));
                        this.classList.add('active');
                        currentTab = this.dataset.tab;
                        currentPage = 1;
                        notificationList.innerHTML = '';
                        noMoreNotifications.style.display = 'none';
                        fetch(`/notifications/seeAll/?tab=${currentTab}&page=${currentPage}`)
                            .then(response => response.json())
                            .then(notifications => {
                                displayNotifications(notifications);
                            });
                    });
                });

                window.addEventListener('scroll', () => {
                    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 500) {
                        loadMoreNotifications();
                    }
                });

                // Mark notification as viewed when clicked
                notificationList.addEventListener('click', function (event) {
                    const notificationItem = event.target.closest('.notification-item');
                    if (notificationItem) {
                        const notificationId = notificationItem.dataset.notificationId;
                        if (notificationId) {
                            viewedNotifications.add(parseInt(notificationId));
                            // Refresh the list after marking as viewed
                            currentPage = 1;
                            notificationList.innerHTML = '';
                            fetch(`/notifications/seeAll/?tab=${currentTab}&page=${currentPage}`)
                                .then(response => response.json())
                                .then(notifications => {
                                    displayNotifications(notifications);
                                });
                        }
                    }
                });
            });
        })();
    </script>
</x-main-layout>