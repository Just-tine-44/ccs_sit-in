<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
$user = $_SESSION['user'];
$profileImg = isset($user['profileImg']) ? $user['profileImg'] : 'images/person.jpg';
$stud_session = isset($_SESSION['stud_session']) ? $_SESSION['stud_session'] : ['session' => 'N/A'];
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (isset($_SESSION['just_logged_in'])): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Welcome!',
            text: 'Hello <?php echo $user['firstname'] . ' ' . $user['midname'] . ' ' . $user['lastname'] . ' '?>👨‍🎓',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    });
</script>
<?php unset($_SESSION['just_logged_in']); endif; ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <script src="script.js"></script>
    <link rel="icon" type="image/png" href="images/ccslogo.png">
    <link href="css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-100">
<?php include 'navbar.php'; ?>
<div class="container mx-auto p-4 grid grid-cols-1 md:grid-cols-6 lg:grid-cols-8 gap-4">
    <!-- Student Information Card (Smaller width) -->
    <div class="container mx-auto md:col-span-2 lg:col-span-2">
        <div class="bg-blue-500 p-2 text-white font-bold rounded-t-lg text-center text-2xl">
            Student Information
        </div>
        <div class="bg-white p-6 rounded-b-lg shadow">
            <div class="border-b-2 border-black pb-4 mb-4">
                <img src="<?php echo $profileImg; ?>" alt="Profile Pic" class="w-32 h-32 mx-auto rounded-full">
            </div>
            <p class="text-gray-700 mb-2"><i class="fas fa-user"></i> Name: <?php echo $user['firstname'] . ' ' . $user['midname'] . ' ' . $user['lastname']; ?></p>
            <p class="text-gray-700 mb-2"><i class="fas fa-book"></i> Course: <?php echo $user['course']; ?></p>
            <p class="text-gray-700 mb-2"><i class="fas fa-calendar"></i> Year: <?php echo $user['level']; ?></p>
            <p class="text-gray-700 mb-2"><i class="fas fa-envelope"></i> Email: <?php echo $user['email']; ?></p>
            <p class="text-gray-700 mb-2"><i class="fas fa-home"></i> Address: <?php echo $user['address']; ?></p>
            <p class="text-gray-700 mb-2"><i class="fas fa-clock"></i> Session: <?php echo $stud_session['session']; ?></p>
        </div>
    </div>

    <!-- Announcements Card (Slightly Bigger) -->
    <div class="container mx-auto md:col-span-2 lg:col-span-3">
        <div class="bg-blue-500 p-2 text-white font-bold rounded-t-lg text-center text-2xl">
            <i class="fas fa-bullhorn"></i> Announcements
        </div>
        <div class="bg-white p-6 rounded-b-lg shadow h-64 overflow-y-auto">
            <p class="text-gray-700">No new announcements.</p>
        </div>
    </div>

    <!-- Rules and Regulation Card (Reduced width slightly) -->
    <div class="container mx-auto md:col-span-4 lg:col-span-3">
        <div class="text-xl font-bold text-center bg-blue-500 text-white p-3 rounded">
            <i class="fas fa-balance-scale"></i> Rules and Regulation
        </div>
        <div class="bg-white p-6 rounded-b-lg shadow overflow-y-auto w-full" style="max-height: 460px;">
            <div class="text-center mb-6">
                <h2 class="text-xl font-bold">University of Cebu</h2>
                <p class="font-bold">COLLEGE OF INFORMATION & COMPUTER STUDIES</p>
            </div>
            <h3 class="text-xl font-bold mb-2">LABORATORY RULES AND REGULATIONS</h3>
            <p class="text-gray-700 mb-4" style="text-align: justify;">To avoid embarrassment and maintain camaraderie with your friends and superiors at our laboratories, please observe the following:</p>
            
            <p class="text-gray-700 mb-4" style="text-align: justify;">1. Maintain silence, proper decorum, and discipline inside the laboratory. Mobile phones, walkmans and other personal pieces of equipment must be switched off.</p>
            <p class="text-gray-700 mb-4" style="text-align: justify;">2. Games are not allowed inside the lab. This includes computer-related games, card games and other games that may disturb the operation of the lab.</p>
            <p class="text-gray-700 mb-4" style="text-align: justify;">3. Surfing the Internet is allowed only with the permission of the instructor. Downloading and installing of software are strictly prohibited.</p>
            <p class="text-gray-700 mb-4" style="text-align: justify;">4. Getting access to other websites not related to the course (especially pornographic and illicit sites) is strictly prohibited.</p>
            <p class="text-gray-700 mb-4" style="text-align: justify;">5. Deleting computer files and changing the set-up of the computer is a major offense.</p>
            <p class="text-gray-700 mb-4" style="text-align: justify;">6. Observe computer time usage carefully. A fifteen-minute allowance is given for each use. Otherwise, the unit will be given to those who wish to "sit-in".</p>
            <p class="text-gray-700 mb-4" style="text-align: justify;">7. Observe proper decorum while inside the laboratory.</p>
            <ul class="list-disc pl-5 text-gray-700 mb-4" style="text-align: justify;">
                <li class="mb-2">Do not get inside the lab unless the instructor is present.</li>
                <li class="mb-2">All bags, knapsacks, and the likes must be deposited at the counter.</li>
                <li class="mb-2">Follow the seating arrangement of your instructor.</li>
                <li class="mb-2">At the end of class, all software programs must be closed.</li>
                <li class="mb-2">Return all chairs to their proper places after using.</li>
            </ul>
            <p class="text-gray-700 mb-4" style="text-align: justify;">8. Chewing gum, eating, drinking, smoking, and other forms of vandalism are prohibited inside the lab.</p>
            <p class="text-gray-700 mb-4" style="text-align: justify;">9. Anyone causing a continual disturbance will be asked to leave the lab. Acts or gestures offensive to the members of the community, including public display of physical intimacy, are not tolerated.</p>
            <p class="text-gray-700 mb-4" style="text-align: justify;">10. Persons exhibiting hostile or threatening behavior such as yelling, swearing, or disregarding requests made by lab personnel will be asked to leave the lab.</p>
            <p class="text-gray-700 mb-4" style="text-align: justify;">11. For serious offense, the lab personnel may call the Civil Security Office (CSU) for assistance.</p>
            <p class="text-gray-700 mb-4" style="text-align: justify;">12. Any technical problem or difficulty must be addressed to the laboratory supervisor, student assistant or instructor immediately.</p>

            <!-- Disciplinary action section -->
            <h3 class="text-xl font-bold mb-2">DISCIPLINARY ACTION</h3>
                <ul class="list-disc pl-5 text-gray-700 mb-4" style="text-align: justify;">
                <li class="mb-2">First Offense - The Head or the Dean or OIC recommends to the Guidance Center for a suspension from classes for each offender.</li>
                <li class="mb-2">Second and Subsequent Offenses - A recommendation for a heavier sanction will be endorsed to the Guidance Center.</li>
            </ul>
        </div>
    </div>
</div>
</body>
</html>