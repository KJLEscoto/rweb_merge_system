// Shared Variables
let isLoading = false;

// Shared Functions
function appendNotification(notificationData, containerId, tabId) {
    let container = document.getElementById(containerId);

    if (!container) {
        console.error(`Container '${containerId}' not found.`);
        return;
    }

    let notificationDiv = document.createElement("div");
    notificationDiv.className = `flex items-center justify-between gap-5 p-3 w-full cursor-pointer hover:bg-gray-50 ${
        parseInt(notificationData.is_read) ? "bg-white" : "bg-gray-100"
    }`;

    notificationDiv.onclick = function (container) {
        openNotificationModal(
            notificationData.id,
            notificationData.message,
            notificationData.is_read,
            tabId,
            notificationData.type,
            notificationData.title,
            container
        );
    };

    let innerFlex = document.createElement("div");
    innerFlex.className = "flex items-center gap-3 w-2/3";

    let imageDiv = document.createElement("div");
    imageDiv.className = "w-auto h-auto";

    let imageContainer = document.createElement("div");
    imageContainer.className =
        "w-10 h-10 rounded-full border border-[#F57D11] overflow-hidden";

    let image = document.createElement("img");
    image.src = notificationData.file_path + "?t=" + Date.now() + "?s=100";
    image.className = "w-full h-full";

    imageContainer.appendChild(image);
    imageDiv.appendChild(imageContainer);
    innerFlex.appendChild(imageDiv);

    let textDiv = document.createElement("div");
    textDiv.className = "w-full truncate";

    let titleParagraph = document.createElement("p");
    titleParagraph.className = "text-sm text-red-500 font-semibold truncate";
    titleParagraph.textContent = notificationData.title;

    let messageParagraph = document.createElement("p");
    messageParagraph.className = "text-sm text-gray-500 font-semibold truncate";
    messageParagraph.textContent = notificationData.message;

    let timeParagraph = document.createElement("p");
    timeParagraph.className = "text-xs text-gray-500 truncate";
    timeParagraph.textContent = timeSince(notificationData.created_at);

    textDiv.appendChild(titleParagraph);
    textDiv.appendChild(messageParagraph);
    textDiv.appendChild(timeParagraph);
    innerFlex.appendChild(textDiv);

    let actionDiv = document.createElement("div");
    actionDiv.className = "flex items-center space-x-2";

    let archiveButtonDiv = document.createElement("div");
    archiveButtonDiv.className = "relative group";

    let archiveButton = document.createElement("button");
    archiveButton.className =
        "decline-btn px-2 py-1 bg-gray-400 hover:bg-black text-white rounded flex items-center justify-center gap-1";
    archiveButton.onclick = function (event) {
        event.stopPropagation();
        archiveNotification(notificationData.id);
    };

    let archiveIcon = document.createElement("span");
    archiveIcon.className = "material-symbols--archive-rounded w-4 h-4";
    archiveIcon.textContent = "archive";

    let archiveTooltip = document.createElement("span");
    archiveTooltip.className =
        "text-black absolute -top-4 opacity-0 group-hover:opacity-100 animate-transition text-[11px] font-semibold";
    archiveTooltip.textContent = "Archive";

    // Check if the container does not matched with the "tab-content-archived"
    if (containerId != "tab-content-archived") {
        archiveButton.appendChild(archiveIcon);
        archiveButton.appendChild(archiveTooltip);
        archiveButtonDiv.appendChild(archiveButton);
        actionDiv.appendChild(archiveButtonDiv);
    }

    debugger;
    if (parseInt(notificationData.is_read) == 0) {
        let unreadIndicator = document.createElement("span");
        unreadIndicator.className = "bg-[#F57D11] w-2 h-2 rounded-full";
        actionDiv.appendChild(unreadIndicator);
    }

    notificationDiv.appendChild(innerFlex);
    notificationDiv.appendChild(actionDiv);
    container.appendChild(notificationDiv);
}

function timeSince(dateString) {
    let date = new Date(dateString);
    let seconds = Math.floor((new Date() - date) / 1000);

    let interval = seconds / 31536000;

    if (interval > 1) {
        return Math.floor(interval) + " years ago";
    }
    interval = seconds / 2592000;
    if (interval > 1) {
        return Math.floor(interval) + " months ago";
    }
    interval = seconds / 86400;
    if (interval > 1) {
        return Math.floor(interval) + " days ago";
    }
    interval = seconds / 3600;
    if (interval > 1) {
        return Math.floor(interval) + " hours ago";
    }
    interval = seconds / 60;
    if (interval > 1) {
        return Math.floor(interval) + " minutes ago";
    }
    return Math.floor(seconds) + " seconds ago";
}

async function loadMoreNotifications(containerId, apiUrl) {
    let container = document.getElementById(containerId);
    // console.log(
    //     container.scrollHeight,
    //     container.scrollTop,
    //     container.clientHeight
    // );
    if (
        !isLoading &&
        container.scrollHeight - container.scrollTop <=
            container.clientHeight + 1
    ) {
        isLoading = true;
        page++;

        // console.log("THE PAGE IS LOADING!");

        let loadingDiv = document.createElement("div");

        loadingDiv.id = "custom-loading-notification";

        loadingDiv.style.display = "flex";

        loadingDiv.style.alignItems = "center";

        loadingDiv.style.justifyContent = "center";

        loadingDiv.style.padding = "10px";

        loadingDiv.style.fontWeight = "semibold";

        loadingDiv.style.color = "#F57D11"; // Highlight loading text

        loadingDiv.style.fontSize = "0.875rem";

        // Create the loading icon

        let loadingIcon = document.createElement("span");

        loadingIcon.classList.add("line-md--loading-loop", "animate-spin");

        loadingIcon.style.fontSize = "20px";

        loadingIcon.style.marginRight = "3px"; // Space between icon and text

        // Append icon and text

        loadingDiv.appendChild(loadingIcon);

        loadingDiv.appendChild(document.createTextNode("Loading..."));

        container.appendChild(loadingDiv);

        requestAnimationFrame(async () => {
            try {
                let response = null;

                if (apiUrl === "/notifications") {
                    // Fetch all notifications
                    try {
                        response = await axios.get(`${apiUrl}?page=${page}`);
                        // Process response.data for all notifications
                        console.log(
                            "Fetched all notifications:",
                            response.data.notifications.data
                        );
                        response = response.data.notifications.data; // or process and return relevant data
                    } catch (error) {
                        console.error(
                            "Error fetching all notifications:",
                            error
                        );
                        return null; // or handle error appropriately
                    }
                } else if (apiUrl === "/notifications/unread") {
                    // Fetch unread notifications
                    try {
                        response = await axios.get(`${apiUrl}?page=${page}`);
                        // Process response.data for unread notifications
                        console.log(
                            "Fetched unread notifications:",
                            response.data.data
                        );
                        response = response.data.data; // or process and return relevant data
                    } catch (error) {
                        console.error(
                            "Error fetching unread notifications:",
                            error
                        );
                        return null; // or handle error appropriately
                    }
                } else if (apiUrl === "/notifications/archived") {
                    // Fetch archived notifications
                    debugger;
                    try {
                        response = await axios.get(`${apiUrl}?page=${page}`);
                        // Process response.data for archived notifications
                        console.log(
                            "Fetched archived notifications:",
                            response.data.data
                        );
                        response = response.data.data; // or process and return relevant data
                    } catch (error) {
                        console.error(
                            "Error fetching archived notifications:",
                            error
                        );
                        return null; // or handle error appropriately
                    }
                } else {
                    // Handle unknown apiUrl
                    console.error("Unknown apiUrl:", apiUrl);
                    return null; // or throw an error
                }

                console.log("response", response);

                setTimeout(() => {
                    let customLoading = document.getElementById(
                        "custom-loading-notification"
                    );

                    if (customLoading) {
                        container.removeChild(customLoading);
                    }

                    if (response.length > 0) {
                        response.forEach((notification) => {
                            appendNotification(notification, containerId, "");
                        });

                        isLoading = false; // Allow further scrolling only if new data is loaded
                    } else {
                        // If no more notifications, display a message

                        let noMoreDiv = document.getElementById(
                            "no-more-notifications"
                        );

                        if (!noMoreDiv) {
                            noMoreDiv = document.createElement("div");

                            noMoreDiv.id = "no-more-notifications";

                            noMoreDiv.style.textAlign = "center";

                            noMoreDiv.style.padding = "10px";

                            noMoreDiv.style.fontWeight = "semibold";

                            noMoreDiv.style.color = "gray";

                            noMoreDiv.style.fontSize = "0.875rem";

                            noMoreDiv.textContent =
                                "No more notifications to load";

                            container.appendChild(noMoreDiv);
                        }

                        isLoading = true; // Prevent further requests
                    }
                }, 1000);
            } catch (error) {
                let customLoading = document.getElementById(
                    "custom-loading-notification"
                );
                if (customLoading) {
                    container.removeChild(customLoading);
                }
                isLoading = false;
            }
        });
    }
}

// Specific Functions
async function allTabLoadMoreNotifications() {
    await loadMoreNotifications("tab-content-all", "/notifications");
}

async function unreadTabLoadMoreNotifications() {
    await loadMoreNotifications("tab-content-unread", "/notifications/unread");
}

async function archivedTabLoadMoreNotifications() {
    await loadMoreNotifications(
        "tab-content-archived",
        "/notifications/archived"
    );
}

// Event Listeners
document
    .getElementById("tab-content-all")
    .addEventListener("scroll", allTabLoadMoreNotifications);
document
    .getElementById("tab-content-unread")
    .addEventListener("scroll", unreadTabLoadMoreNotifications);
document
    .getElementById("tab-content-archived")
    .addEventListener("scroll", archivedTabLoadMoreNotifications);

console.log("Script initialized. Event listeners attached.");
