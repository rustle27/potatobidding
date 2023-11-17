<?php
session_start();
$createworkslot = new managerAllocationWorkslotBoundary();
$createworkslot->displayAllocateWorkslotPage();

class managerAllocationWorkslotBoundary {
    public function displayAllocateWorkslotPage() {
        if ((isset($_GET['userData']))){
            $userData = json_decode($_GET['userData'], true);
            echo '
                <!DOCTYPE html>
                <html>
                <head>
                    <title>ALLOCATE WORKSLOT</title>
                    <link rel="stylesheet" type="text/css" href="../CSS/managerallocateworkslotboundaryphp.css">
                </head>
                <body>
                    <form action="../Controller/managerAllocateWorkslotController.php?sessionid=' . $_SESSION['id'] . '" method="post">
                        <div id="allocateworkslotbox"><a id="allocateworkslot">Allocate Workslot</a></div><br><br>';
                        if (isset($_SESSION['error_message'])) {
                            $error_message = $_SESSION['error_message'];
                            echo '<p class="error">' . $error_message . '</p>';
                            unset($_SESSION['error_message']); // Clear the session variable
                        }
                        if (isset($_SESSION['success_message'])) {
                            $success_message = $_SESSION['success_message'];
                            echo '<p class="success">' . $success_message . '</p>';
                            unset($_SESSION['success_message']); // Clear the session variable
                        }
                    echo '
                        <label>Staff: </label>
                        <select id="staff" name="staff">';
                            if (!empty($userData)) {
                                foreach ($userData as $user) {
                                    $userId = $user['id'];
                                    $name = $user['name'];
                                    $role = $user['role'];
                                    if ($role === "staff" || $role === "Staff"){
                                        echo '<option value="' . $userId . '">' . $name . '</option>';
                                    }
                                }
                            }
                    echo' </select><br><br>
                        <label for="month">Select a month:</label>
                        <select id="month" onchange="updateCalendar()">
                            <option value="0">January</option>
                            <option value="1">February</option>
                            <option value="2">March</option>
                            <option value="3">April</option>
                            <option value="4">May</option>
                            <option value="5">June</option>
                            <option value="6">July</option>
                            <option value="7">August</option>
                            <option value="8">September</option>
                            <option value="9">October</option>
                            <option value="10">November</option>
                            <option value="11">December</option>
                        </select>
                        <div id="calendar"></div><br>
                        <label>Select a time:</label>
                        <select id="time" name="time">
                            <option value="AM">AM</option>
                            <option value="PM">PM</option>
                        </select><br><br>
                        <label>Role: </label>
                        <select id="role" name="role">
                            <option value="cashier">Cashier</option>
                            <option value="cleaner">Cleaner</option>
                            <option value="waiter">Waiter</option>
                        </select><br><br>
                        <input type="hidden" name="status" value="Success">
                        <input type="hidden" id="selectedDay" name="selectedDay"> <!-- Hidden input to store the selected day -->
                        <input type="hidden" id="selectedMonth" name="selectedMonth"> <!-- Hidden input to store the selected month -->
                        <input type="hidden" id="selectedTime" name="selectedTime"> <!-- Hidden input to store the selected time -->
                        <a href="../Controller/viewWorkslotController.php?userid=' . $_SESSION['id'] .'" id="ca">BACK</a>
                        <button type="submit">Submit</button>
                    </form>

                    <script>
                        var selectedCell = null; // Track the previously selected cell

                        function updateCalendar() {
                            const monthSelect = document.getElementById("month");
                            const selectedMonth = parseInt(monthSelect.value); // Parse the selected value as an integer
                            const currentDate = new Date();
                            const currentYear = currentDate.getFullYear();

                            // Create a new date for the selected month
                            const selectedDate = new Date(currentYear, selectedMonth, 1);

                            // Get the number of days in the selected month
                            const lastDay = new Date(currentYear, selectedMonth + 1, 0).getDate();

                            // Generate the calendar
                            let calendarHtml = "<table>";
                            calendarHtml += "<tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>";

                            for (let i = 1; i <= lastDay; i++) {
                                selectedDate.setDate(i);
                                const dayOfWeek = selectedDate.getDay();
                                if (i === 1) {
                                    calendarHtml += "<tr>";
                                    for (let j = 0; j < dayOfWeek; j++) {
                                        calendarHtml += "<td></td>";
                                    }
                                }
                                // Pass the selected month and day to the handleDayClick function
                                calendarHtml += `<td onclick="handleDayClick(${selectedMonth + 1}, ${i}, this)">${i}</td>`;
                                if (dayOfWeek === 6) {
                                    calendarHtml += "</tr>";
                                }
                            }

                            calendarHtml += "</table>";
                            document.getElementById("calendar").innerHTML = calendarHtml;
                        }

                        // Handle the day click event and set the selected day, month, and time
                        function handleDayClick(month, day, cell) {
                            if (selectedCell) {
                                // Remove the "selected" class from the previously selected cell
                                selectedCell.classList.remove("selected");
                            }
                            // Set the selected month and day in the hidden inputs
                            document.getElementById("selectedMonth").value = month;
                            document.getElementById("selectedDay").value = day;
                            document.getElementById("selectedTime").value = document.getElementById("time").options[document.getElementById("time").selectedIndex].value;

                            // Add a CSS class to change the color of the clicked date
                            cell.classList.add("selected");
                            selectedCell = cell; // Update the selectedCell
                        }
                    </script>
                </body>
                </html>
            ';
        }
    }
}
?>
