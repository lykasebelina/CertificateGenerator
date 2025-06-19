<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Logs System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .audit-log-entry {
            border-left: 4px solid;
            transition: all 0.2s ease;
        }
        .log-success {
            border-left-color: #10B981;
        }
        .log-error {
            border-left-color: #EF4444;
        }
        .log-warning {
            border-left-color: #F59E0B;
        }
        .log-info {
            border-left-color: #3B82F6;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-2">Audit Logs System</h1>
        <p class="text-center text-gray-600 mb-8">Comprehensive tracking of all system activities</p>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <!-- Filters and Search -->
            <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
                <div class="flex flex-wrap gap-2">
                    <button id="filterAll" class="px-3 py-1 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200 transition">
                        All Logs
                    </button>
                    <button id="filterSuccess" class="px-3 py-1 bg-green-100 text-green-800 rounded-md hover:bg-green-200 transition">
                        Success Only
                    </button>
                    <button id="filterErrors" class="px-3 py-1 bg-red-100 text-red-800 rounded-md hover:bg-red-200 transition">
                        Errors Only
                    </button>
                    <button id="filterWarnings" class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-md hover:bg-yellow-200 transition">
                        Warnings
                    </button>
                </div>
                
                <div class="relative flex-1 max-w-md">
                    <input type="text" id="logSearch" placeholder="Search logs..." 
                           class="w-full pl-4 pr-10 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Log Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-blue-800">Total Logs</h3>
                    <p id="totalLogs" class="text-2xl font-bold text-blue-600">0</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-green-800">Success</h3>
                    <p id="successLogs" class="text-2xl font-bold text-green-600">0</p>
                </div>
                <div class="bg-red-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-red-800">Errors</h3>
                    <p id="errorLogs" class="text-2xl font-bold text-red-600">0</p>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-yellow-800">Warnings</h3>
                    <p id="warningLogs" class="text-2xl font-bold text-yellow-600">0</p>
                </div>
            </div>
            
            <!-- Log Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Timestamp
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User/IP
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Details
                            </th>
                        </tr>
                    </thead>
                    <tbody id="auditLogsTable" class="bg-white divide-y divide-gray-200">
                        <!-- Logs will be inserted here -->
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="flex items-center justify-between mt-4">
                <div class="text-sm text-gray-700">
                    Showing <span id="logsFrom">0</span> to <span id="logsTo">0</span> of <span id="totalLogsCount">0</span> entries
                </div>
                <div class="flex space-x-2">
                    <button id="prevPage" class="px-3 py-1 border rounded-md hover:bg-gray-50 disabled:opacity-50" disabled>
                        Previous
                    </button>
                    <button id="nextPage" class="px-3 py-1 border rounded-md hover:bg-gray-50 disabled:opacity-50" disabled>
                        Next
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Configuration
        const API_URL = 'api.php';
        const logsPerPage = 10;
        let currentPage = 1;
        let currentFilter = 'ALL';
        let currentSearch = '';
        
        // Load logs from API
        async function loadLogs(page = 1, filter = 'ALL', search = '') {
            try {
                const url = new URL(API_URL, window.location.href);
                url.searchParams.set('page', page);
                url.searchParams.set('limit', logsPerPage);
                if (filter !== 'ALL') {
                    url.searchParams.set('filter', filter);
                }
                if (search) {
                    url.searchParams.set('search', search);
                }
                
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                
                const data = await response.json();
                
                // Update UI with logs
                renderLogs(data.logs);
                
                // Update pagination info
                updatePaginationInfo(data.page, data.total, data.pages);
                
                // Update stats
                updateStats(data.stats);
                
                // Update current state
                currentPage = page;
                currentFilter = filter;
                currentSearch = search;
                
            } catch (error) {
                console.error('Error loading logs:', error);
                alert('Failed to load logs. Please try again.');
            }
        }
        
        // Render logs to the table
        function renderLogs(logs) {
            const tableBody = document.getElementById('auditLogsTable');
            tableBody.innerHTML = '';
            
            logs.forEach(log => {
                const row = document.createElement('tr');
                row.className = audit-log-entry log-${log.status.toLowerCase()};
                
                // Timestamp
                const timestampCell = document.createElement('td');
                timestampCell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-500';
                timestampCell.textContent = new Date(log.timestamp).toLocaleString();
                
                // Action
                const actionCell = document.createElement('td');
                actionCell.className = 'px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900';
                actionCell.textContent = log.action.replace(/_/g, ' ');
                
                // User/IP
                const userCell = document.createElement('td');
                userCell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-500';
                userCell.innerHTML = `
                    <div class="font-medium">${log.user || 'System'}</div>
                    <div class="text-gray-400">${log.ip}</div>
                `;
                
                // Status
                const statusCell = document.createElement('td');
                statusCell.className = 'px-6 py-4 whitespace-nowrap text-sm';
                
                let statusClass = '';
                let statusText = log.status;
                
                if (log.status === 'SUCCESS') {
                    statusClass = 'bg-green-100 text-green-800';
                } else if (log.status === 'ERROR') {
                    statusClass = 'bg-red-100 text-red-800';
                } else if (log.status === 'WARNING') {
                    statusClass = 'bg-yellow-100 text-yellow-800';
                } else {
                    statusClass = 'bg-blue-100 text-blue-800';
                }
                
                statusCell.innerHTML = `
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                        ${statusText}
                    </span>
                `;
                
                // Details
                const detailsCell = document.createElement('td');
                detailsCell.className = 'px-6 py-4 text-sm text-gray-500';
                detailsCell.textContent = log.details;
                
                // Append cells to row
                row.appendChild(timestampCell);
                row.appendChild(actionCell);
                row.appendChild(userCell);
                row.appendChild(statusCell);
                row.appendChild(detailsCell);
                
                // Append row to table
                tableBody.appendChild(row);
            });
        }
        
        // Update pagination information
        function updatePaginationInfo(page, total, pages) {
            document.getElementById('logsFrom').textContent = ((page - 1) * logsPerPage) + 1;
            document.getElementById('logsTo').textContent = Math.min(page * logsPerPage, total);
            document.getElementById('totalLogsCount').textContent = total;
            
            // Update pagination buttons
            document.getElementById('prevPage').disabled = page <= 1;
            document.getElementById('nextPage').disabled = page >= pages;
        }
        
        // Update statistics
        function updateStats(stats) {
            document.getElementById('totalLogs').textContent = stats.total;
            document.getElementById('successLogs').textContent = stats.success;
            document.getElementById('errorLogs').textContent = stats.error;
            document.getElementById('warningLogs').textContent = stats.warning;
        }
        
        // Add a new log entry
        async function addLog(action, user, ip, status, details) {
            try {
                const response = await fetch(API_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        action,
                        user,
                        ip,
                        status,
                        details
                    })
                });
                
                if (!response.ok) {
                    throw new Error('Failed to add log');
                }
                
                // Refresh logs after adding
                loadLogs(currentPage, currentFilter, currentSearch);
                
            } catch (error) {
                console.error('Error adding log:', error);
            }
        }
        
        // Event Listeners
        document.getElementById('filterAll').addEventListener('click', () => {
            loadLogs(1, 'ALL', currentSearch);
        });
        
        document.getElementById('filterSuccess').addEventListener('click', () => {
            loadLogs(1, 'SUCCESS', currentSearch);
        });
        
        document.getElementById('filterErrors').addEventListener('click', () => {
            loadLogs(1, 'ERROR', currentSearch);
        });
        
        document.getElementById('filterWarnings').addEventListener('click', () => {
            loadLogs(1, 'WARNING', currentSearch);
        });
        
        document.getElementById('logSearch').addEventListener('input', (e) => {
            loadLogs(1, currentFilter, e.target.value);
        });
        
        document.getElementById('prevPage').addEventListener('click', () => {
            if (currentPage > 1) {
                loadLogs(currentPage - 1, currentFilter, currentSearch);
            }
        });
        
        document.getElementById('nextPage').addEventListener('click', () => {
            loadLogs(currentPage + 1, currentFilter, currentSearch);
        });
        
        // Initialize
        loadLogs();
        
        // Add sample logs periodically for demonstration
        setInterval(() => {
            const actions = [
                { action: 'USER_LOGIN', status: 'SUCCESS', details: 'User logged in successfully' },
                { action: 'DATA_UPDATE', status: 'SUCCESS', details: 'Updated customer records' },
                { action: 'API_CALL', status: 'SUCCESS', details: 'Called external API endpoint' },
                { action: 'PERMISSION_DENIED', status: 'ERROR', details: 'Access denied to restricted area' },
                { action: 'INVALID_REQUEST', status: 'WARNING', details: 'Received malformed request' }
            ];
            
            const randomAction = actions[Math.floor(Math.random() * actions.length)];
            const users = ['admin@example.com', 'user1@example.com', 'user2@example.com', 'system'];
            const ips = ['192.168.1.' + Math.floor(Math.random() * 150), '10.0.0.' + Math.floor(Math.random() * 50)];
            
            addLog(
                randomAction.action,
                users[Math.floor(Math.random() * users.length)],
                ips[Math.floor(Math.random() * ips.length)],
                randomAction.status,
                randomAction.details
            );
        }, 15000); // Add a new log every 15 seconds
    </script>
</body>
</html>

ibdex.html