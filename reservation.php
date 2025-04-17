<?php
// Start session and include necessary connections/functions
session_start();
include('conn/dbcon.php');

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Get user information - FIXED: Using 'users' table instead of 'students'
$user_id = $_SESSION['user']['id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Get remaining sessions from stud_session table
$sql_sessions = "SELECT session FROM stud_session WHERE id = ?";
$stmt_sessions = $conn->prepare($sql_sessions);
$stmt_sessions->bind_param("i", $user_id);
$stmt_sessions->execute();
$result_sessions = $stmt_sessions->get_result();

$remaining_sessions = 0;
if ($result_sessions->num_rows > 0) {
    $sessions_data = $result_sessions->fetch_assoc();
    $remaining_sessions = $sessions_data['session'];
}

// Get available labs (placeholder - replace with actual query)
$available_labs = ['524', '526', '528', '530', '542', '544', '517'];

// Form submission will be handled by AJAX
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Reservation | CCS Lab System</title>
    <link rel="icon" type="image/png" href="images/wbccs.png">
    <link href="css/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f4f8;
        }
        
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-scale-in {
            animation: scaleIn 0.4s ease-out forwards;
        }
        
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        .reservation-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        }
        
        .input-focus {
            transition: all 0.2s ease;
        }
        
        .input-focus:focus {
            border-color: rgba(37, 99, 235, 0.5);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25);
        }
        
        .form-card {
            background: linear-gradient(145deg, #ffffff, #f8fafc);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8, #4338ca);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
        }
        
        .sessions-card {
            background: linear-gradient(135deg, #1e40af, #3b82f6);
        }
        
        .input-icon {
            color: #94a3b8;
            transition: color 0.2s ease;
        }
        
        .input-focus:focus + .input-icon-container .input-icon {
            color: #3b82f6;
        }
        
        .section-header {
            position: relative;
        }
        
        .section-header::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            height: 3px;
            width: 40px;
            background: linear-gradient(90deg, #3b82f6, #4f46e5);
            border-radius: 3px;
        }
        
        .highlight-text {
            background: linear-gradient(90deg, #2563eb, #4f46e5);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .custom-timepicker::-webkit-calendar-picker-indicator {
            background: transparent;
            color: transparent;
            cursor: pointer;
            height: 20px;
            position: absolute;
            right: 10px;
            width: 20px;
        }
        
        .info-card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .card-content {
            flex-grow: 1;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <?php include "navbar.php"; ?>
    
    <div class="container mx-auto px-4 py-8 sm:py-12 max-w-6xl">
        <!-- Page Header -->
        <div class="text-center mb-8 animate-fade-in">
            <div class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 mb-2">
                <i class="fas fa-calendar-plus mr-2"></i> Schedule a Lab Session
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">
                Lab <span class="highlight-text">Reservation</span>
            </h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Secure your spot in the computer lab by submitting a reservation request below.
            </p>
        </div>
        
        <!-- Info Cards Row - MOVED TO THE TOP -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8 animate-fade-in" style="animation-delay: 0.1s;">
            <!-- Sessions Balance -->
            <div class="form-card rounded-2xl p-5 reservation-shadow animate-scale-in info-card">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center section-header">
                    <i class="fas fa-ticket-alt text-blue-500 mr-2"></i> Your Available Sessions
                </h3>
                <div class="card-content">
                    <div class="sessions-card rounded-xl p-4 text-white mt-5">
                        <div class="text-sm opacity-90 mb-1 font-medium">Available Sessions</div>
                        <div class="text-3xl font-bold mb-2"><?php echo $remaining_sessions; ?></div>
                        <div class="h-2 bg-white/20 rounded-full overflow-hidden">
                            <div class="h-full bg-white rounded-full" style="width: <?php echo min(100, $remaining_sessions * 10); ?>%"></div>
                        </div>
                        <div class="flex justify-between items-center text-xs mt-1 font-medium">
                            <span>0</span>
                            <span>10</span>
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-gray-600">
                        <p class="flex items-center">
                            <span class="h-5 w-5 rounded-full bg-blue-100 flex items-center justify-center mr-2 flex-shrink-0">
                                <i class="fas fa-info text-blue-600 text-xs"></i>
                            </span>
                            Each reservation requires 1 session credit
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Lab Hours -->
            <div class="form-card rounded-2xl p-5 reservation-shadow animate-scale-in info-card" style="animation-delay: 0.15s">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center section-header">
                    <i class="far fa-clock text-blue-500 mr-2"></i> Lab Operating Hours
                </h3>
                <div class="card-content">
                    <div class="space-y-2 mt-5">
                        <div class="flex items-start bg-gray-50 p-2.5 rounded-lg border border-gray-100">
                            <div class="text-blue-500 mr-2.5">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Monday - Friday</h4>
                                <p class="text-gray-600 text-xs mt-0.5">7:00 AM - 8:00 PM</p>
                            </div>
                        </div>
                        <div class="flex items-start bg-gray-50 p-2.5 rounded-lg border border-gray-100">
                            <div class="text-blue-500 mr-2.5">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Saturday</h4>
                                <p class="text-gray-600 text-xs mt-0.5">8:00 AM - 5:00 PM</p>
                            </div>
                        </div>
                        <div class="flex items-start bg-gray-50 p-2.5 rounded-lg border border-gray-100">
                            <div class="text-blue-500 mr-2.5">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Sunday & Holidays</h4>
                                <p class="text-gray-600 text-xs mt-0.5">Closed</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Reservation Guidelines -->
            <div class="form-card rounded-2xl p-5 reservation-shadow animate-scale-in info-card" style="animation-delay: 0.2s">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center section-header">
                    <i class="fas fa-clipboard-list text-blue-500 mr-2"></i> Guidelines
                </h3>
                <div class="card-content">
                    <ul class="space-y-2 mt-5">
                        <li class="flex items-start">
                            <div class="h-5 w-5 rounded-full bg-green-100 flex items-center justify-center mr-2 mt-0.5 flex-shrink-0">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <span class="text-sm text-gray-600">Reservations can be made up to 7 days in advance</span>
                        </li>
                        <li class="flex items-start">
                            <div class="h-5 w-5 rounded-full bg-green-100 flex items-center justify-center mr-2 mt-0.5 flex-shrink-0">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <span class="text-sm text-gray-600">Standard sessions are 1 hour in duration</span>
                        </li>
                        <li class="flex items-start">
                            <div class="h-5 w-5 rounded-full bg-green-100 flex items-center justify-center mr-2 mt-0.5 flex-shrink-0">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <span class="text-sm text-gray-600">Arrive 5 minutes before your scheduled time slot</span>
                        </li>
                        <li class="flex items-start">
                            <div class="h-5 w-5 rounded-full bg-green-100 flex items-center justify-center mr-2 mt-0.5 flex-shrink-0">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <span class="text-sm text-gray-600">Cancel reservations at least 2 hours before the scheduled time</span>
                        </li>
                        <li class="flex items-start">
                            <div class="h-5 w-5 rounded-full bg-amber-100 flex items-center justify-center mr-2 mt-0.5 flex-shrink-0">
                                <i class="fas fa-exclamation text-amber-600 text-xs"></i>
                            </div>
                            <span class="text-sm text-gray-600">No-shows will result in session point deduction</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Reservation Form - NOW FULL WIDTH -->
        <div class="animate-fade-in" style="animation-delay: 0.25s;">
            <div class="form-card rounded-2xl p-6 md:p-8 reservation-shadow">
                <div class="flex items-center mb-8">
                    <div class="bg-blue-100 rounded-full p-3 mr-4">
                        <i class="fas fa-clipboard-list text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 section-header">Reservation Details</h2>
                    </div>
                </div>
                
                <!-- Reservation Form -->
                <form id="reservationForm" class="space-y-7">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-7">
                        <!-- ID Number (readonly) -->
                        <div class="animate-fade-in" style="animation-delay: 0.3s;">
                            <label for="idNumber" class="block text-sm font-medium text-gray-700 mb-2">Student ID Number</label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="idNumber" 
                                    value="<?php echo htmlspecialchars($user['idno'] ?? ''); ?>" 
                                    class="pl-11 pr-4 py-3 block w-full border border-gray-300 rounded-lg text-gray-700 bg-gray-50 input-focus"
                                    readonly
                                >
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none input-icon-container">
                                    <i class="fas fa-id-card input-icon"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Student Name (readonly) -->
                        <div class="animate-fade-in" style="animation-delay: 0.35s;">
                            <label for="studentName" class="block text-sm font-medium text-gray-700 mb-2">Student Name</label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="studentName" 
                                    value="<?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname'] ?? ''); ?>" 
                                    class="pl-11 pr-4 py-3 block w-full border border-gray-300 rounded-lg text-gray-700 bg-gray-50 input-focus"
                                    readonly
                                >
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none input-icon-container">
                                    <i class="fas fa-user input-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="h-px bg-gray-200 my-3"></div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-7">
                        <!-- Purpose -->
                        <div class="animate-fade-in" style="animation-delay: 0.4s;">
                            <label for="purpose" class="block text-sm font-medium text-gray-700 mb-2">Purpose of Visit</label>
                            <div class="relative">
                                <select 
                                    id="purpose" 
                                    name="purpose" 
                                    class="pl-11 pr-4 py-3 block w-full border border-gray-300 rounded-lg text-gray-700 appearance-none input-focus"
                                    required
                                >
                                    <option value="">Select Purpose</option>
                                    <option value="C Programming">C Programming</option>
                                    <option value="Java Programming">Java Programming</option>
                                    <option value="C#">C#</option>
                                    <option value="PHP">PHP</option>
                                    <option value="ASP.Net">ASP.Net</option>
                                    <option value="Database">Database</option>
                                    <option value="Digital Logic & Design">Digital Logic & Design</option>
                                    <option value="Embedded System % IOT">Embedded System % IOT</option>
                                    <option value="Python Programming">Python Programming</option>
                                    <option value="Systems Integration & Architecture">Systems Integration & Architecture</option>
                                    <option value="Computer Application">Computer Application</option>
                                    <option value="Web Design & Development">Web Design & Development</option>
                                    <option value="Project Management">Project Management</option>
                                    <option value="Other">Other</option>
                                </select>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none input-icon-container">
                                    <i class="fas fa-tasks input-icon"></i>
                                </div>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Lab Room -->
                        <div class="animate-fade-in" style="animation-delay: 0.45s;">
                            <label for="labRoom" class="block text-sm font-medium text-gray-700 mb-2">Laboratory Room</label>
                            <div class="relative">
                                <select 
                                    id="labRoom" 
                                    name="labRoom" 
                                    class="pl-11 pr-4 py-3 block w-full border border-gray-300 rounded-lg text-gray-700 appearance-none input-focus"
                                    required
                                >
                                    <option value="">Select Lab Room</option>
                                    <?php foreach($available_labs as $lab): ?>
                                        <option value="<?php echo $lab; ?>">Room <?php echo $lab; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none input-icon-container">
                                    <i class="fas fa-door-open input-icon"></i>
                                </div>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Date -->
                        <div class="animate-fade-in" style="animation-delay: 0.5s;">
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Reservation Date</label>
                            <div class="relative">
                                <input 
                                    type="date" 
                                    id="date" 
                                    name="date" 
                                    min="<?php echo date('Y-m-d'); ?>"
                                    class="pl-11 pr-4 py-3 block w-full border border-gray-300 rounded-lg text-gray-700 input-focus"
                                    required
                                >
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none input-icon-container">
                                    <i class="fas fa-calendar-alt input-icon"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Time In (Changed to time input with validation) -->
                        <div class="animate-fade-in" style="animation-delay: 0.55s;">
                            <label for="timeIn" class="block text-sm font-medium text-gray-700 mb-2">Time In</label>
                            <div class="relative">
                                <input 
                                    type="time" 
                                    id="timeIn" 
                                    name="timeIn"
                                    class="pl-11 pr-10 py-3 block w-full border border-gray-300 rounded-lg text-gray-700 input-focus custom-timepicker"
                                    required
                                >
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none input-icon-container">
                                    <i class="fas fa-clock input-icon"></i>
                                </div>
                                <div class="absolute right-0 top-0 mt-2 mr-3">
                                    <span class="text-xs text-gray-500">(7:00 AM - 11:59 PM)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="flex justify-end mt-8 animate-fade-in" style="animation-delay: 0.6s;">
                        <button 
                            type="submit" 
                            id="reserveButton"
                            class="btn-primary py-3 px-6 text-white rounded-lg font-medium shadow-md flex items-center"
                        >
                            <i class="fas fa-calendar-check mr-2"></i> 
                            Submit Reservation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('reservationForm');
            const timeInInput = document.getElementById('timeIn');
            const remainingSessions = <?php echo $remaining_sessions; ?>;
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form values
                const purpose = document.getElementById('purpose').value;
                const labRoom = document.getElementById('labRoom').value;
                const date = document.getElementById('date').value;
                const timeIn = timeInInput.value;
                
                // Basic validation
                if (!purpose || !labRoom || !date || !timeIn) {
                    Swal.fire({
                        title: 'Missing Information',
                        text: 'Please fill in all required fields.',
                        icon: 'warning',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }
                
                // Check if user has enough sessions
                if (remainingSessions <= 0) {
                    Swal.fire({
                        title: 'No Available Sessions',
                        text: 'You don\'t have any available sessions. Please earn more sessions or contact lab administration.',
                        icon: 'error',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }
                
                // Validate time (not between 12:00 AM and 6:30 AM)
                const timeValue = timeIn.split(':');
                const hours = parseInt(timeValue[0]);
                const minutes = parseInt(timeValue[1]);
                
                // Convert to minutes since midnight for easy comparison
                const timeInMinutes = hours * 60 + minutes;
                const earlyMorningStart = 0; // 12:00 AM
                const earlyMorningEnd = 6 * 60 + 30; // 6:30 AM
                
                if (timeInMinutes >= earlyMorningStart && timeInMinutes <= earlyMorningEnd) {
                    Swal.fire({
                        title: 'Invalid Time',
                        text: 'The lab is closed between 12:00 AM and 6:30 AM. Please select a time during operating hours.',
                        icon: 'warning',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }
                
                // Check if date is in the past
                const selectedDate = new Date(date);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (selectedDate < today) {
                    Swal.fire({
                        title: 'Invalid Date',
                        text: 'Please select a current or future date.',
                        icon: 'warning',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }
                
                // Format time for display in 12-hour format
                let displayHours = hours % 12;
                if (displayHours === 0) displayHours = 12;
                const ampm = hours >= 12 ? 'PM' : 'AM';
                const displayTime = `${displayHours}:${minutes.toString().padStart(2, '0')} ${ampm}`;
                
                // Success message (normally this would be after AJAX call to backend)
                Swal.fire({
                    title: 'Reservation Confirmed',
                    html: `
                        <div class="text-left p-1">
                            <div class="mb-3 pb-3 border-b border-gray-200">
                                <p class="text-sm text-gray-500 mb-1">Reservation Details:</p>
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="font-semibold text-gray-700">Lab Room:</div>
                                    <div>${labRoom}</div>
                                    <div class="font-semibold text-gray-700">Date:</div>
                                    <div>${new Date(date).toLocaleDateString()}</div>
                                    <div class="font-semibold text-gray-700">Time:</div>
                                    <div>${displayTime}</div>
                                    <div class="font-semibold text-gray-700">Purpose:</div>
                                    <div>${purpose}</div>
                                </div>
                            </div>
                            <p class="text-sm text-green-600 flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                Your reservation has been confirmed!
                            </p>
                        </div>
                    `,
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Done'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Normally we would redirect or reload after server processing
                        // For demo, we'll just reset the form
                        form.reset();
                    }
                });
                
                // In a real implementation, you would send the data to the server via AJAX here
                // and handle the response accordingly
            });
        });
    </script>
</body>
</html>