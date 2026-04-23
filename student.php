<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student View - Office Hours Queue</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Office Hours Queue</h1>
        
        <form action="api.php?action=join" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required placeholder="Enter your name">
            </div>
            <div class="form-group">
                <label for="reason">Reason for Visit:</label>
                <textarea id="reason" name="reason" rows="3" required placeholder="What do you need help with?"></textarea>
            </div>
            <button type="submit">Join Queue</button>
        </form>

        <h2 style="margin-top: 40px;">Live Queue Status</h2>
        <ul id="queue-container" class="queue-list">
            <!-- Queue items will be injected here by JS -->
            <p class="empty-msg">Loading queue...</p>
        </ul>

        <div class="links">
            <a href="professor.php">Switch to Professor View</a>
        </div>
    </div>

    <script>
        function fetchQueue() {
            fetch('api.php?action=get')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('queue-container');
                    container.innerHTML = '';
                    
                    if (data.length === 0) {
                        container.innerHTML = '<p class="empty-msg">The queue is currently empty. You\'re up next!</p>';
                        return;
                    }

                    data.forEach((student, index) => {
                        const li = document.createElement('li');
                        li.className = 'queue-item';
                        // Highlight the student who is first in line
                        if (index === 0) {
                            li.style.borderLeftColor = '#28a745';
                        }
                        li.innerHTML = `
                            <div>
                                <h3>#${index + 1} - ${escapeHtml(student.name)}</h3>
                                <p>${escapeHtml(student.reason)}</p>
                            </div>
                            ${index === 0 ? '<span style="color: #28a745; font-size: 0.9em; font-weight: bold;">[Current]</span>' : ''}
                        `;
                        container.appendChild(li);
                    });
                })
                .catch(error => console.error('Error fetching queue:', error));
        }

        // Prevent XSS
        function escapeHtml(unsafe) {
            return unsafe
                 .replace(/&/g, "&amp;")
                 .replace(/</g, "&lt;")
                 .replace(/>/g, "&gt;")
                 .replace(/"/g, "&quot;")
                 .replace(/'/g, "&#039;");
        }

        // Fetch immediately, then every 10 seconds
        fetchQueue();
        setInterval(fetchQueue, 10000);
    </script>
</body>
</html>
