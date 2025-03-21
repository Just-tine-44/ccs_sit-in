<?php include("./conn_back/records_process.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Records | CCS Sit-In System</title>
    <?php include("icon.php"); ?>
    <link rel="stylesheet" href="../css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #c5c5c5;
        border-radius: 4px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #a0a0a0;
    }
    

    #recordsTable tbody tr {
        height: 64px;
    }
    
  
    #recordsTable thead {
        position: sticky;
        top: 0;
        z-index: 1;
        background-color: #f9fafb; 
    }
</style>
<style>
    /* Calculate: 8 rows × row height (64px) + header height (53px) = 565px */
    .max-h-\[500px\] {
        max-height: 565px;
    }
</style>
<body class="bg-gray-100">
    <?php include 'navbar_admin.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <!-- Page Title -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-history mr-2"></i> Session Records
            </h1>
            <div class="flex items-center gap-2">
                <button onclick="exportToCSV()" class="bg-purple-600 text-white px-3 py-2 rounded-lg hover:bg-purple-700 transition-colors flex items-center gap-2">
                    <i class="fas fa-file-csv"></i> CSV
                </button>
                            
                <button onclick="exportToExcel()" class="bg-green-500 text-white px-3 py-2 rounded-lg hover:bg-green-600 transition-colors flex items-center gap-2">
                    <i class="fas fa-file-excel"></i> Excel
                </button>
                
                <button onclick="exportToPDF()" class="bg-red-600 text-white px-3 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2">
                    <i class="fas fa-file-pdf"></i> PDF
                </button>
                
                <button onclick="printTable()" class="bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
        
        <!-- Stats Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <!-- Total Sessions -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                <h3 class="text-lg font-semibold text-blue-600">Total Sessions</h3>
                <div class="flex items-center mt-2">
                    <span class="text-3xl font-bold text-gray-800"><?php echo $stats['total_sessions']; ?></span>
                </div>
            </div>
            
            <!-- Active Sessions -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
                <h3 class="text-lg font-semibold text-green-600">Active Sessions</h3>
                <div class="flex items-center mt-2">
                    <span class="text-3xl font-bold text-gray-800"><?php echo $stats['active_sessions']; ?></span>
                </div>
            </div>
            
            <!-- Completed Sessions -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
                <h3 class="text-lg font-semibold text-purple-600">Completed</h3>
                <div class="flex items-center mt-2">
                    <span class="text-3xl font-bold text-gray-800"><?php echo $stats['completed_sessions']; ?></span>
                </div>
            </div>
            
            <!-- Unique Students -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
                <h3 class="text-lg font-semibold text-yellow-600">Unique Students</h3>
                <div class="flex items-center mt-2">
                    <span class="text-3xl font-bold text-gray-800"><?php echo $stats['unique_students']; ?></span>
                </div>
            </div>
            
            <!-- Total Hours -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500">
                <h3 class="text-lg font-semibold text-red-600">Total Hours</h3>
                <div class="flex items-center mt-2">
                    <span class="text-3xl font-bold text-gray-800"><?php echo floor($stats['total_minutes'] / 60); ?></span>
                    <span class="ml-2 text-sm text-gray-500">hrs</span>
                </div>
            </div>
        </div>
        
        <!-- Filters & Search -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <form method="GET" action="" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Laboratory Filter -->
                <div>
                    <label for="laboratory" class="block text-sm font-medium text-gray-700 mb-1">Room</label>
                    <select name="laboratory" id="laboratory" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Rooms</option>
                        <?php foreach($laboratories as $lab): ?>
                            <option value="<?php echo $lab; ?>" <?php echo ($filterLab == $lab) ? 'selected' : ''; ?>>
                                Room <?php echo $lab; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Date Filter -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <select name="date" id="date" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Dates</option>
                        <?php foreach($dates as $date): ?>
                            <option value="<?php echo $date; ?>" <?php echo ($filterDate == $date) ? 'selected' : ''; ?>>
                                <?php echo date('F j, Y', strtotime($date)); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status" class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="active" <?php echo ($filterStatus == 'active') ? 'selected' : ''; ?>>Active</option>
                        <option value="completed" <?php echo ($filterStatus == 'completed') ? 'selected' : ''; ?>>Completed</option>
                    </select>
                </div>
                
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" id="search" placeholder="Search by ID or name" 
                           class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           value="<?php echo htmlspecialchars($searchTerm); ?>">
                </div>
                
                <!-- Apply Filters Button -->
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors w-full">
                        <i class="fas fa-filter mr-2"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Records Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <?php if ($result->num_rows > 0): ?>
                    <div class="max-h-[500px] overflow-y-auto custom-scrollbar">
                        <table class="min-w-full divide-y divide-gray-200" id="recordsTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course & Year</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Laboratory</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time-In</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time-Out</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <?php 
                                        // Calculate duration
                                        $duration = "N/A";
                                        if ($row['check_out_time']) {
                                            $checkIn = new DateTime($row['check_in_time']);
                                            $checkOut = new DateTime($row['check_out_time']);
                                            $interval = $checkIn->diff($checkOut);
                                            $hours = $interval->h + ($interval->days * 24);
                                            $minutes = $interval->i;
                                            $duration = $hours . "h " . $minutes . "m";
                                        }
                                    ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo htmlspecialchars($row['idno']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars($row['lastname'] . ', ' . $row['firstname'] . ' ' . ($row['midname'] ? $row['midname'][0] . '.' : '')); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo htmlspecialchars($row['course'] . ' - ' . $row['level']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                <?php echo htmlspecialchars($row['laboratory']); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            <div class="max-w-xs truncate" title="<?php echo htmlspecialchars($row['purpose']); ?>">
                                                <?php echo htmlspecialchars($row['purpose']); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo date('M d, Y g:i A', strtotime($row['check_in_time'])); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo $row['check_out_time'] ? date('M d, Y g:i A', strtotime($row['check_out_time'])) : 'Still Active'; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo $duration; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?php echo $row['status'] == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                                                <?php echo ucfirst($row['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-10">
                        <div class="w-20 h-20 mx-auto flex items-center justify-center bg-gray-100 rounded-full">
                            <i class="fas fa-clipboard-list text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No Records Found</h3>
                        <p class="mt-1 text-sm text-gray-500">Try changing your search filters</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

    <script>
        // Existing CSV export function
        function exportToCSV() {
            const table = document.getElementById('recordsTable');
            let csv = [];
            const rows = table.querySelectorAll('tr');
            
            for (let i = 0; i < rows.length; i++) {
                const row = [], cols = rows[i].querySelectorAll('td, th');
                
                for (let j = 0; j < cols.length; j++) {
                    // Get the text content and replace any commas to avoid CSV issues
                    let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, ' ').replace(/,/g, ';');
                    row.push('"' + data + '"');
                }
                
                csv.push(row.join(','));
            }
            
            // Download CSV file
            const csvFile = new Blob([csv.join('\n')], {type: 'text/csv'});
            const downloadLink = document.createElement('a');
            
            downloadLink.download = 'sit_in_records_' + new Date().toISOString().slice(0,10) + '.csv';
            downloadLink.href = window.URL.createObjectURL(csvFile);
            downloadLink.style.display = 'none';
            
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }
        
        // New Excel export function
        function exportToExcel() {
            const table = document.getElementById('recordsTable');
            const wb = XLSX.utils.table_to_book(table, {sheet: "Session Records"});
            const filename = 'sit_in_records_' + new Date().toISOString().slice(0,10) + '.xlsx';
            
            XLSX.writeFile(wb, filename);
        }
        
        // New PDF export function 
        function exportToPDF() {
            // Initialize jsPDF
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('l', 'pt', 'a4');
            
            // Add title
            doc.setFontSize(18);
            doc.text('Session Records Report', 40, 40);
            
            // Add date
            doc.setFontSize(11);
            doc.setTextColor(100);
            doc.text('Generated on: ' + new Date().toLocaleString(), 40, 60);
            
            // From the table, create the PDF
            doc.autoTable({
                html: '#recordsTable',
                startY: 70,
                margin: { top: 70, right: 40, bottom: 40, left: 40 },
                styles: { fontSize: 8, cellPadding: 2 },
                columnStyles: {
                    0: { cellWidth: 'auto' }, // ID
                    1: { cellWidth: 'auto' }, // Student
                    2: { cellWidth: 'auto' }, // Course
                    3: { cellWidth: 40 }, // Laboratory
                    4: { cellWidth: 80 }, // Purpose
                    5: { cellWidth: 65 }, // Time-in
                    6: { cellWidth: 65 }, // Time-out
                    7: { cellWidth: 50 }, // Duration
                    8: { cellWidth: 50 }  // Status
                },
                didDrawCell: function(data) {
                    // Ensure text doesn't overflow
                    if (data.column.index === 4 && data.cell.section === 'body') {
                        const td = data.cell.raw;
                        if (td.offsetWidth > 80) {
                            doc.setFontSize(7);
                        }
                    }
                }
            });
            
            // Save PDF
            doc.save('sit_in_records_' + new Date().toISOString().slice(0,10) + '.pdf');
        }
        
        // Print function
        function printTable() {
            // Create a new window for printing
            const printWindow = window.open('', '_blank', 'height=600,width=800');
            
            // Get current page title and institution name
            const title = document.title || 'Session Records';
            const institutionName = 'CCS Sit-In System';
            
            // Build HTML content with title, date and table content
            printWindow.document.write(`
                <html>
                <head>
                    <title>${title} - Print View</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        h1 { text-align: center; font-size: 20px; margin-bottom: 5px; }
                        .institution { text-align: center; font-size: 16px; margin-bottom: 20px; }
                        .date { text-align: center; font-size: 14px; margin-bottom: 20px; color: #666; }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }
                        th { background-color: #f2f2f2; }
                        tr:nth-child(even) { background-color: #f9f9f9; }
                        .print-header { display: flex; justify-content: space-between; margin-bottom: 20px; }
                        .filters { margin-bottom: 20px; font-size: 12px; }
                        .filters strong { font-weight: bold; }
                        @media print {
                            button { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <div class="print-header">
                        <div>
                            <h1>${title}</h1>
                            <div class="institution">${institutionName}</div>
                            <div class="date">Generated on: ${new Date().toLocaleString()}</div>
                        </div>
                    </div>
                    
                    <div class="filters">
                        <strong>Filters:</strong> 
                        Room: ${document.getElementById('laboratory').value || 'All'} | 
                        Date: ${document.getElementById('date').value || 'All'} |
                        Status: ${document.getElementById('status').value || 'All'} |
                        Search: ${document.getElementById('search').value || 'None'}
                    </div>
                    
                    <table>${document.getElementById('recordsTable').outerHTML}</table>
                    
                    <div style="text-align: center; margin-top: 20px;">
                        <button onclick="window.print();window.close();" style="padding: 10px 20px;">
                            Print Document
                        </button>
                    </div>
                </body>
                </html>
            `);
            
            // Close the document
            printWindow.document.close();
            
            // Focus on the new window
            printWindow.focus();
        }
    </script>
</body>
</html>