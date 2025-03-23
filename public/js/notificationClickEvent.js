document.addEventListener("DOMContentLoaded", function () {
    // Function to fetch and display unread notifications
    async function fetchAndDisplayUnreadNotifications() {
        try {
            page = 1;
            loadingCount = 0;
            const response = await axios.get("/notifications/unread");
            const unreadNotifications = response.data.data;
            const unreadContainer =
                document.getElementById("tab-content-unread");
            unreadContainer.innerHTML = "";

            // Scroll to the top
            unreadContainer.scrollTop = 0; // For scrolling a specific container
            window.scrollTo({ top: 0, behavior: "instant" }); // For scrolling the whole page

            console.log("Modal inside all container:", modal);

            if (unreadNotifications.length > 0) {
                unreadNotifications.forEach((notification) => {
                    appendNotification(
                        notification,
                        "tab-content-unread",
                        "tab-unread"
                    );
                });

                debugger;
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

        debugger;
    }

    // Event listener for the "Unread" tab click
    document
        .getElementById("tab-unread")
        .addEventListener("click", function () {
            document
                .getElementById("tab-content-unread")
                .classList.remove("hidden");
            document.getElementById("tab-content-all").classList.add("hidden");
            document
                .getElementById("tab-content-archived")
                .classList.add("hidden");
            fetchAndDisplayUnreadNotifications();
            document
                .getElementById("tab-all")
                .classList.remove(
                    "text-[#F57D11]",
                    "border-[#F57D11]",
                    "font-semibold",
                    "border-b-2"
                );
            document
                .getElementById("tab-unread")
                .classList.add(
                    "text-[#F57D11]",
                    "border-[#F57D11]",
                    "font-semibold",
                    "border-b-2"
                );
            document
                .getElementById("tab-archived")
                .classList.remove(
                    "text-[#F57D11]",
                    "border-[#F57D11]",
                    "font-semibold",
                    "border-b-2"
                );
            document.getElementById("tab-all").classList.add("text-gray-500");
            document
                .getElementById("tab-archived")
                .classList.add("text-gray-500");
        });

    // Function to fetch and display archived notifications
    async function fetchAndDisplayArchivedNotifications() {
        try {
            page = 1;
            loadingCount = 0;
            const response = await axios.get("/notifications/archived");
            const archivedNotifications = response.data.data;
            const archivedContainer = document.getElementById(
                "tab-content-archived"
            );
            archivedContainer.innerHTML = "";

            // Scroll to the top
            archivedContainer.scrollTop = 0; // For scrolling a specific container
            window.scrollTo({ top: 0, behavior: "instant" }); // For scrolling the whole page

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

    // Event listener for the "Archived" tab click
    document
        .getElementById("tab-archived")
        .addEventListener("click", function () {
            document
                .getElementById("tab-content-archived")
                .classList.remove("hidden");
            document.getElementById("tab-content-all").classList.add("hidden");
            document
                .getElementById("tab-content-unread")
                .classList.add("hidden");
            fetchAndDisplayArchivedNotifications();
            document
                .getElementById("tab-all")
                .classList.remove(
                    "text-[#F57D11]",
                    "border-[#F57D11]",
                    "font-semibold",
                    "border-b-2"
                );
            document
                .getElementById("tab-unread")
                .classList.remove(
                    "text-[#F57D11]",
                    "border-[#F57D11]",
                    "font-semibold",
                    "border-b-2"
                );
            document
                .getElementById("tab-archived")
                .classList.add(
                    "text-[#F57D11]",
                    "border-[#F57D11]",
                    "font-semibold",
                    "border-b-2"
                );
            document.getElementById("tab-all").classList.add("text-gray-500");
            document
                .getElementById("tab-unread")
                .classList.add("text-gray-500");
        });

    // Function to fetch and display all notifications
    async function fetchAndDisplayAllNotifications() {
        try {
            page = 1;
            loadingCount = 0;
            const response = await axios.get("/notifications");
            const allNotifications = response.data.notifications.data;
            const allContainer = document.getElementById("tab-content-all");
            allContainer.innerHTML = "";

            // Scroll to the top
            allContainer.scrollTop = 0; // For scrolling a specific container
            window.scrollTo({ top: 0, behavior: "instant" }); // For scrolling the whole page

            if (allNotifications.length > 0) {
                allNotifications.forEach((notification) => {
                    appendNotification(
                        notification,
                        "tab-content-all",
                        "tab-all"
                    );
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

    // Event listener for the "All" tab click
    document.getElementById("tab-all").addEventListener("click", function () {
        document.getElementById("tab-content-all").classList.remove("hidden");
        document.getElementById("tab-content-unread").classList.add("hidden");
        document.getElementById("tab-content-archived").classList.add("hidden");
        fetchAndDisplayAllNotifications();
        document
            .getElementById("tab-all")
            .classList.add(
                "text-[#F57D11]",
                "border-[#F57D11]",
                "font-semibold",
                "border-b-2"
            );
        document
            .getElementById("tab-unread")
            .classList.remove(
                "text-[#F57D11]",
                "border-[#F57D11]",
                "font-semibold",
                "border-b-2"
            );
        document
            .getElementById("tab-archived")
            .classList.remove(
                "text-[#F57D11]",
                "border-[#F57D11]",
                "font-semibold",
                "border-b-2"
            );
        document.getElementById("tab-unread").classList.add("text-gray-500");
        document.getElementById("tab-archived").classList.add("text-gray-500");
    });

    //initial load
    fetchAndDisplayAllNotifications();
});
