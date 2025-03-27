<script>
    let page = 1;
    function setupNotifications() {
        let jsonData = window.notificationsData;
        let userId = window.userIdData;
        let modal = this.document.getElementById("AllNotificationModals"); //this is the default modal

        console.log("funcational");

        axios
            .get("{{ route('user.recieve.notification.index') }}", { // Use route() here
                params: { user_id: userId },
            })
            .then((response) => {
                console.log(response.data); // Handle data
            })
            .catch((error) => {
                console.error("Error fetching notifications:", error);
            });

        // Convert to an array
        let notifications = Object.values(jsonData);

        console.log(notifications);

        const dropdownButton = document.getElementById("dropdown-notification");
        const dropdownMenu = document.getElementById("dropdown-show-notification");
        const tabAllButton = document.getElementById("tab-all");
        const tabUnreadButton = document.getElementById("tab-unread");
        const tabArchivedButton = document.getElementById("tab-archived");
        const tabContentAll = document.getElementById("tab-content-all");
        const tabContentUnread = document.getElementById("tab-content-unread");
        const tabContentArchived = document.getElementById("tab-content-archived");
        const notificationCountSpan = document.getElementById("notification-count");

        async function countUnreadNotifications() {
            try {
                const response = await axios.get(
                    "{{ route('user.recieve.notification.totalUnreadCount') }}", // Use route() here
                    {
                        params: { user_id: userId },
                    }
                );
                console.log("Notifications:", response.data);
                return response.data; // Correctly return the count
            } catch (error) {
                console.error("Error fetching notifications:", error);
                return 0; // Return 0 in case of error
            }
        }

        // Function to update notification count
        async function updateNotificationCount() {
            const unreadCount = await countUnreadNotifications();

            if (unreadCount > 0) {
                notificationCountSpan.textContent = `(${unreadCount})`;
            } else {
                notificationCountSpan.textContent = "";
            }

            // update the counter in the button itself
            const buttonCountDiv = dropdownButton.querySelector("div div p");
            if (buttonCountDiv) {
                console.log("UnRead Count:", unreadCount);
                if (unreadCount <= 99) {
                    buttonCountDiv.textContent = unreadCount;
                } else {
                    buttonCountDiv.textContent = "99+";
                }
            }

            async function updateTabCounts(notifications) {
                try {
                    // All tab count
                    let allCount = 0;
                    const allResponse = await axios.get("{{ route('user.recieve.notification.allCount') }}", { // Use route() here
                        params: { user_id: userId },
                    });
                    allCount = allResponse.data;
                    tabAllButton.textContent = `All (${allCount})`;
                    console.log(`All tab count: ${allCount}`);

                    // Unread tab count
                    let unreadCount = 0;
                    const unreadResponse = await axios.get(
                        "{{ route('user.recieve.notification.unreadCount') }}", // Use route() here
                        {
                            params: { user_id: userId },
                        }
                    );
                    unreadCount = unreadResponse.data;
                    tabUnreadButton.textContent = `Unread (${unreadCount})`;
                    console.log(`Unread tab count: ${unreadCount}`);

                    // Archived tab count
                    let archivedCount = 0;
                    const archivedResponse = await axios.get(
                        "{{ route('user.recieve.notification.archivedCount') }}", // Use route() here
                        {
                            params: { user_id: userId },
                        }
                    );
                    archivedCount = archivedResponse.data;
                    tabArchivedButton.textContent = `Archived (${archivedCount})`;
                    console.log(`Archived tab count: ${archivedCount}`);
                } catch (error) {
                    console.error("Error fetching notification counts:", error);
                }
            }

            // Assuming you have these buttons in your HTML and they are accessible in your JavaScript:
            const tabAllButton = document.getElementById("tab-all");
            const tabUnreadButton = document.getElementById("tab-unread");
            const tabArchivedButton = document.getElementById("tab-archived");

            updateTabCounts(notifications);
        }

        window.addEventListener("click", function (event) {
            const dropdownButton = document.getElementById("dropdown-notification");
            const dropdownMenu = document.getElementById(
                "dropdown-show-notification"
            );

            console.log("Click Event: Target -", event.target); // Log the clicked element

            if (
                !dropdownButton.contains(event.target) &&
                !dropdownMenu.contains(event.target) &&
                !modal.contains(event.target)
            ) {
                console.log(
                    "Condition 1: Hiding dropdown menu (click outside button and menu)."
                );
                dropdownMenu.classList.add("hidden");
            } else if (
                !dropdownMenu.contains(event.target) &&
                modal.contains(event.target)
            ) {
                console.log(
                    "Condition 2: Hiding dropdown menu (click outside menu and modal)."
                );
                modal.classList.add("hidden");
            } else if (!dropdownMenu.contains(event.target)) {
                console.log("Condition 3: Toggling dropdown menu.");
                dropdownMenu.classList.toggle("hidden");
            } else {
                console.log(
                    "No Condition met. The dropdown menu is not hidden or toggled"
                );
            }

            console.log(
                "Dropdown Menu Visibility:",
                dropdownMenu.classList.contains("hidden") ? "hidden" : "visible"
            ); // Log the menu's visibility after the click
        });

        // Function to show a specific tab
        function showTab(tabId) {
            tabContentAll.classList.add("hidden");
            tabContentUnread.classList.add("hidden");
            tabContentArchived.classList.add("hidden");

            tabAllButton.classList.remove(
                "border-b-2",
                "border-[#F57D11]",
                "text-[#F57D11]"
            );
            tabAllButton.classList.add("text-gray-500");
            tabUnreadButton.classList.remove(
                "border-b-2",
                "border-[#F57D11]",
                "text-[#F57D11]"
            );
            tabUnreadButton.classList.add("text-gray-500");
            tabArchivedButton.classList.remove(
                "border-b-2",
                "border-[#F57D11]",
                "text-[#F57D11]"
            );
            tabArchivedButton.classList.add("text-gray-500");

            if (tabId === "tab-all") {
                tabContentAll.classList.remove("hidden");
                tabAllButton.classList.add(
                    "border-b-2",
                    "border-[#F57D11]",
                    "text-[#F57D11]"
                );
                tabAllButton.classList.remove("text-gray-500");
            } else if (tabId === "tab-unread") {
                tabContentUnread.classList.remove("hidden");
                tabUnreadButton.classList.add(
                    "border-b-2",
                    "border-[#F57D11]",
                    "text-[#F57D11]"
                );
                tabUnreadButton.classList.remove("text-gray-500");
            } else if (tabId === "tab-archived") {
                tabContentArchived.classList.remove("hidden");
                tabArchivedButton.classList.add(
                    "border-b-2",
                    "border-[#F57D11]",
                    "text-[#F57D11]"
                );
                tabArchivedButton.classList.remove("text-gray-500");
            }
        }

        // Function to open notification modal
        window.openNotificationModal = async function (
            notificationId,
            message,
            isRead,
            tab,
            type,
            title,
            container
        ) {
            console.log(
                "Opening notification modal for ID:",
                notificationId,
                "Tab:",
                tab
            );
            if (tab === "tab-all") {
                modal = document.getElementById("AllNotificationModals");
                messageElement = document.getElementById("allNotificationMessage");
                dateElement = document.getElementById("DateNotificationMessage");
                pageTitleElement = document.getElementById("pageTitle");
                containerDateElement = document.getElementById(
                    "ContainerDateNotificationMessage"
                );
                console.log(
                    "Tab is 'tab-all'. Modal:",
                    modal,
                    "Message Element:",
                    messageElement,
                    "Date Element:",
                    dateElement
                );
            } else if (tab === "tab-unread") {
                modal = document.getElementById("UnreadNotificationModal");
                messageElement = document.getElementById(
                    "unreadNotificationMessage"
                );
                dateElement = document.getElementById(
                    "UnreadDateNotificationMessage"
                );
                pageTitleElement = document.getElementById("unReadPageTitle");
                containerDateElement = document.getElementById(
                    "UnreadContainerDateNotificationMessage"
                );
                console.log(
                    "Tab is 'tab-unread'. Modal:",
                    modal,
                    "Message Element:",
                    messageElement,
                    "Date Element:",
                    dateElement
                );
            } else if (tab === "tab-archive") {
                modal = document.getElementById("ArchiveNotificationModal");
                messageElement = document.getElementById(
                    "archiveNotificationMessage"
                );
                dateElement = document.getElementById(
                    "ArchiveDateNotificationMessage"
                );
                pageTitleElement = document.getElementById("archivePageTitle");
                containerDateElement = document.getElementById(
                    "ArchiveContainerDateNotificationMessage"
                );
                console.log(
                    "Tab is 'tab-archive'. Modal:",
                    modal,
                    "Message Element:",
                    messageElement,
                    "Date Element:",
                    dateElement
                );
            }

            if (modal && messageElement) {
                console.log("Modal and elements found.");
                console.log("Notifications array:", notifications);

                //get the notification id
                let notification = await searchNotificationData(notificationId);
                console.log("Found notification:", notification);

                if (notification) {
                    pageTitleElement.innerHTML = notification.title;
                    messageElement.textContent = notification.message;
                    console.log("Notification message:", notification.message);

                    const notificationDate = new Date(notification.created_at);
                    console.log(
                        "Notification created_at:",
                        notification.created_at,
                        "Parsed date:",
                        notificationDate
                    );

                    if (!isNaN(notificationDate)) {
                        if (type == "user.dtr.download.request") {
                            containerDateElement.classList.remove("opacity-0");
                            dateElement.textContent =
                                notificationDate.toLocaleDateString();
                            console.log("Formatted date:", dateElement.textContent);
                        } else {
                            containerDateElement.classList.add("opacity-0");
                        }
                    } else {
                        console.error(
                            "Invalid date format:",
                            notification.created_at
                        );
                    }

                    modal.classList.remove("hidden");
                    console.log("Modal shown.");

                    if (!parseInt(isRead)) {
                        console.log(
                            "Marking notification as read:",
                            notificationId
                        );
                        markNotificationAsRead(notificationId);
                    }
                } else {
                    console.error("Notification not found for ID:", notificationId);
                }
            } else {
                console.error("Modal or elements not found for tab:", tab);
            }
        };

        // Function to close notification modal
        window.closeNotificationModal = function (tab) {
            if (modal) {
                modal.classList.add("hidden");
            }
        };

        async function searchNotificationData(notificationId) {
            try {
                const response = await axios.get(
                    `{{ route('user.recieve.notification.find', ['id' => ':notificationId']) }}`.replace(':notificationId', notificationId),
                    {
                        params: { user_id: userId },
                    }
                );
                console.log("Notifications:", response.data);
                return response.data; // Correctly return the count
            } catch (error) {
                console.error("Error fetching notifications:", error);
                return 0; // Return 0 in case of error
            }
        }

        // Function to mark notification as read
        async function markNotificationAsRead(notificationId) {
            try {
                const response = await axios.post(
                    `{{ route('admin.dtr.recieve.notification', ['id' => ':notificationId']) }}`.replace(':notificationId', notificationId),
                    {
                        // If your backend expects any data in the request body, add it here
                    },
                    {
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content"),
                        },
                    }
                );

                if (response.status === 200) {
                    updateNotificationCount();
                } else {
                    console.error(
                        `Failed to mark notification as read. Status: ${response.status}`
                    );
                }
            } catch (error) {
                console.error("Error marking notification as read:", error);
            }
        }

        function setupNotificationModalButtons() {
            const showNotificationModalButtons = document.querySelectorAll(
                '[onClick="showNotificationModal()"]'
            );

            showNotificationModalButtons.forEach((button) => {
                button.addEventListener("click", function () {
                    showNotificationModal(); // Call the showNotificationModal function
                });
            });
        }

        // Assuming you have a showNotificationModal function defined elsewhere
        function showNotificationModal() {
            // Your logic to show the notification modal
            console.log("Show Notification Modal Function Called");
            window.location.href = `{{ route('admin.dtr.approvals') }}`; // Example:
            // document.getElementById('yourModalId').classList.remove('hidden');
        }

        // Call the setup function when the DOM is ready
        document.addEventListener(
            "DOMContentLoaded",
            setupNotificationModalButtons
        );

        // Function to archive notification
        window.archiveNotification = async function (notificationId) {
            try {
                const response = await axios.post(
                    `{{ route('user.recieve.notification.archive', ['id' => ':notificationId']) }}`.replace(':notificationId', notificationId),
                    {
                        // If your backend expects any data in the request body, add it here
                    },
                    {
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content"),
                        },
                    }
                );

                if (response.status === 200) {
                    updateNotificationCount();
                    await fetchAndDisplayUnreadNotifications();
                    await fetchAndDisplayArchivedNotifications();
                    await fetchAndDisplayAllNotifications();
                } else {
                    console.error(
                        `Failed to archive notification. Status: ${response.status}`
                    );
                }
            } catch (error) {
                console.error("Error archiving notification:", error);
            }
        };

        // Event listeners
        //dropdownButton.addEventListener('click', toggleDropdown);
        tabAllButton.addEventListener("click", () => showTab("tab-all"));
        tabUnreadButton.addEventListener("click", () => showTab("tab-unread"));
        tabArchivedButton.addEventListener("click", () => showTab("tab-archived"));

        // Initial setup
        updateNotificationCount();
        showTab("tab-all");
    }

    async function fetchAndDisplayUnreadNotifications() {
        try {
            const response = await axios.get("{{ route('user.recieve.notification.unread') }}");
            const unreadNotifications = response.data.data;
            const unreadContainer = document.getElementById("tab-content-unread");
            unreadContainer.innerHTML = "";

            console.log("Modal inside all container:", modal);

            if (unreadNotifications.length > 0) {
                unreadNotifications.forEach((notification) => {
                    appendNotification(
                        notification,
                        "tab-content-unread",
                        "tab-unread"
                    );
                });
            } else {
                const noNotificationsDiv = document.createElement("div");
                noNotificationsDiv.className =
                    "w-full h-full flex justify-center items-center text-gray-600 text-sm";
                noNotificationsDiv.textContent = "Nothing to see here.";
                unreadContainer.appendChild(noNotificationsDiv);
            }
        } catch (error) {
            console.error("Error fetching unread notifications:", error);
        }
    }

    // Function to fetch and display archived notifications
    async function fetchAndDisplayArchivedNotifications() {
        try {
            const response = await axios.get("{{ route('user.recieve.notification.archived') }}");
            const archivedNotifications = response.data.data;
            const archivedContainer = document.getElementById(
                "tab-content-archived"
            );
            archivedContainer.innerHTML = "";

            if (archivedNotifications.length > 0) {
                archivedNotifications.forEach((notification) => {
                    appendNotification(
                        notification,
                        "tab-content-archived",
                        "tab-archived"
                    );
                });
            } else {
                const noNotificationsDiv = document.createElement("div");
                noNotificationsDiv.className =
                    "w-full h-full flex justify-center items-center text-gray-600 text-sm";
                noNotificationsDiv.textContent = "Nothing to see here.";
                archivedContainer.appendChild(noNotificationsDiv);
            }
        } catch (error) {
            console.error("Error fetching archived notifications:", error);
        }
    }

    // Function to fetch and display all notifications
    async function fetchAndDisplayAllNotifications() {
        try {
            const response = await axios.get("{{ route('user.recieve.notification.index') }}");
            const allNotifications = response.data.notifications.data;
            const allContainer = document.getElementById("tab-content-all");
            allContainer.innerHTML = "";

            if (allNotifications.length > 0) {
                allNotifications.forEach((notification) => {
                    appendNotification(notification, "tab-content-all", "tab-all");
                });
            } else {
                const noNotificationsDiv = document.createElement("div");
                noNotificationsDiv.className =
                    "w-full h-full flex justify-center items-center text-gray-600 text-sm";
                noNotificationsDiv.textContent = "Nothing to see here.";
                allContainer.appendChild(noNotificationsDiv);
            }
        } catch (error) {
            console.error("Error fetching all notifications:", error);
        }
    }

    function appendNotification(notification, containerId, tab) {
        const container = document.getElementById(containerId);
        if (!container) {
            console.error(`Container with ID ${containerId} not found.`);
            return;
        }

        const notificationDiv = document.createElement("div");
        notificationDiv.className =
            "p-4 border-b border-gray-200 cursor-pointer hover:bg-gray-50";

        notificationDiv.innerHTML = `
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-sm font-semibold">${notification.title}</h3>
                    <p class="text-xs text-gray-600">${notification.message}</p>
                </div>
                <div class="flex items-center space-x-2">
                    ${tab === 'tab-all' || tab === 'tab-unread' ? `<button onclick="archiveNotification(${notification.id})" class="text-xs text-gray-500 hover:text-gray-700">Archive</button>` : ''}
                    <button onclick="openNotificationModal(${notification.id}, '${notification.message.replace(/'/g, "\\'")}', ${notification.is_read}, '${tab}', '${notification.type}', '${notification.title.replace(/'/g, "\\'")}')" class="text-xs text-blue-500 hover:text-blue-700">View</button>
                </div>
            </div>
        `;

        container.appendChild(notificationDiv);
    }

    setupNotifications();
</script>