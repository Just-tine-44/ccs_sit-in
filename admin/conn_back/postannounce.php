<?php 
    include(__DIR__ . '/../../conn/dbcon.php'); // Adjust the path as necessary

    // Handle form submission for new announcement
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_announcement'])) {
        $message = $_POST['message'];
        $admin_name = 'CCS-Admin'; // Default admin name

        $stmt = $conn->prepare("INSERT INTO announcements (admin_name, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $admin_name, $message);
        if ($stmt->execute()) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Announcement Posted',
                        text: 'Your announcement has been posted successfully!',
                        showConfirmButton: false,
                        timer: 2000,
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown' // Fade in from the top
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOut' // Fade out normally
                        }
                    }).then(function() {
                        window.location = 'admin_home.php';
                    });
                });
            </script>";
        } else {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error posting your announcement. Please try again.',
                        showConfirmButton: false,
                        timer: 2000,
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown' // Fade in from the top
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOut' // Fade out normally
                        }
                    });
                });
            </script>";
        }
        $stmt->close();
    }

    // Fetch announcements from the database
    $announcements = [];
    $result = $conn->query("SELECT * FROM announcements ORDER BY post_date DESC");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $announcements[] = $row;
        }
    }
?>



<style>
/* Default Gray Scrollbar */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

/* Default Track */
.custom-scrollbar::-webkit-scrollbar-track {
    background: #d1d5db; /* Gray background */
    border-radius: 10px;
}

/* Default Thumb (Gray) */
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #9ca3af; /* Default gray */
    border-radius: 10px;
    transition: background 0.5s ease-in-out;
}

/* Active Thumb (Blue Gradient) */
.custom-scrollbar.scrolling::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #3b82f6, #1e40af); /* Blue gradient */
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const scrollContainer = document.getElementById("announcementScroll");

    let scrollTimeout;

    scrollContainer.addEventListener("scroll", function () {
        scrollContainer.classList.add("scrolling");

        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
            scrollContainer.classList.remove("scrolling");
        }, 500); // Delay of 0.5s after scrolling stops
    });
});
</script>