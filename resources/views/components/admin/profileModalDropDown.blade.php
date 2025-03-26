<script>
    // Toggle dropdown
    function toggleDropdown() {
        let dropdown = document.getElementById("dropdown-show-profile");
        dropdown.classList.toggle("hidden");
    }

    // Close dropdown if clicked outside
    document.addEventListener("click", function (event) {
        let dropdown = document.getElementById("dropdown-show-profile");
        let profileButton = document.getElementById("dropdown-profile");

        if (
            !profileButton.contains(event.target) &&
            !dropdown.contains(event.target)
        ) {
            dropdown.classList.add("hidden");
        }
    });

    // Active state for dropdown items
    document.querySelectorAll(".dropdown-item").forEach((item) => {
        item.addEventListener("click", function () {
            document
                .querySelectorAll(".dropdown-item")
                .forEach((el) => el.classList.remove("bg-[#f56d11]", "text-white"));
            this.classList.add("bg-[#f56d11]", "text-white");
        });
    });

    // Open and Close Logout Modal
    function openLogoutModal() {
        document.getElementById("logoutModal").classList.remove("hidden");
    }

    function closeLogoutModal() {
        document.getElementById("logoutModal").classList.add("hidden");
    }
</script>