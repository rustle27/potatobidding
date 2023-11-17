<?php
# connection to xampp database
$sname = "localhost";
$uname = "root";
$pass = "";
$db_name = "test";

$conn = mysqli_connect($sname, $uname, $pass, $db_name);
if (!$conn){
    echo "Connection failed!";
}

class Account{
    private $conn;

    public function __construct($connection){
        $this->conn = $connection;
    }

    public function authenticateUser($username, $password){
        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = mysqli_query($this->conn, $sql);

        if (mysqli_num_rows($result) === 1){
            $row = mysqli_fetch_assoc($result);
            if ($row['username'] === $username && $row['password'] === $password){
                // return new Account($row['id'], $row['username'], $row['name'], $row['days'], $row['role']);
                $userData = [
                    'id' => $row['id'],
                    'username' => $row['username'],
                    'name' => $row['name'],
                    'days' => $row['days'],
                    'role' => $row['role']
                ];
                return $userData;
            }
        } else {
            return null;
        }
    }

    public function registerUser($username, $password, $name, $role) {
        // Check if the username already exists
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    
        if (mysqli_num_rows($result) > 0) {
            header("Location: ../Boundary/registerBoundary.php?error=Username has been taken");
            exit();
        } else {
            // The username is available, so proceed with user registration
            $sql2 = "INSERT INTO users (username, password, name, role) VALUES (?, ?, ?, ?)";
            $stmt2 = mysqli_prepare($this->conn, $sql2);
    
            mysqli_stmt_bind_param($stmt2, "ssss", $username, $password, $name, $role);
    
            if (mysqli_stmt_execute($stmt2)) {
                // User registration was successful
                return true;
            } else {
                // User registration failed
                return false;
            }
        }
    }

    public function getUserById($userId){
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $userData = [
            'id' => $row['id'],
            'username' => $row['username'],
            'name' => $row['name'],
            'days' => $row['days'],
            'role' => $row['role']
        ];
        return $userData;
    }

    public function getUserByUname($username){
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $userData = [
            'id' => $row['id'],
            'username' => $row['username'],
            'name' => $row['name'],
            'days' => $row['days'],
            'role' => $row['role']
        ];
        return $userData;
    }

    public function getUsers() {
        $sql = "SELECT * FROM users";
        $result = mysqli_query($this->conn, $sql);
        $users = array(); // Create an array to store user data
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Append each user's data to the users array
                $users[] = $row;
            }
            mysqli_free_result($result); // Free the result set
        }
        return $users; // Return the array of user data
    }

    public function updateUser($id, $username, $name, $days, $role){
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) !== 1) {
            return false;
        } else {
            $sql2 = "UPDATE users SET username = ?, name = ?, days = ?, role = ? WHERE id = ?";
            $stmt2 = mysqli_prepare($this->conn, $sql2);
            mysqli_stmt_bind_param($stmt2, "ssisi", $username, $name, $days, $role, $id);
            if (mysqli_stmt_execute($stmt2)) {
                // Update successful
                return true;
            } else {
                return false;
            }
        }
    }

    public function changePassword($id, $npass){
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) !== 1) {
            return false;
        } else {
            $sql2 = "UPDATE users SET password = ? WHERE id = ?";
            $stmt2 = mysqli_prepare($this->conn, $sql2);
            mysqli_stmt_bind_param($stmt2, "si", $npass, $id);
            if (mysqli_stmt_execute($stmt2)) {
                // Update successful
                return true;
            } else {
                return false;
            }
        }
    }

    public function deleteUser($userId) {
        // Check if the user with the given ID exists
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 0) {
            // The user with the given ID does not exist
            return false;
        } else {
            // The user exists, so proceed with user deletion
            $sql2 = "DELETE FROM users WHERE id=?";
            $stmt2 = mysqli_prepare($this->conn, $sql2);
            mysqli_stmt_bind_param($stmt2, "i", $userId);

            if (mysqli_stmt_execute($stmt2)) {
                // User deletion was successful
                return true;
            } else {
                return false;
            }
        }
    }

    public function searchUsers($search) {
        // Perform a search query to find users based on a search criteria
        $sql = "SELECT * FROM users WHERE username LIKE ? OR name LIKE ? OR role LIKE ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        // Add wildcard symbols to the search string for partial matching
        $search = '%' . $search . '%';
        mysqli_stmt_bind_param($stmt, "sss", $search, $search, $search);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $userData = array();

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $userData[] = $row;
            }
            mysqli_free_result($result); // Free the result set
            return $userData;
        } else {
            // No results found
            return false;
        }
    }
}

class Bids{
    private $conn;

    public function __construct($connection){
        $this->conn = $connection;
    }

    public function viewWorkslot() {
        $sql = "SELECT * FROM bids";
        $result = mysqli_query($this->conn, $sql);
        $bidData = array(); // Create an array to store user data
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Append each user's data to the users array
                $bidData[] = $row;
            }
            mysqli_free_result($result); // Free the result set
        }
        return $bidData; // Return the array of user data
    }

    public function createBid($uname, $selectedMonth, $selectedDay, $selectedTime, $dayOfWeek, $selectedRole){
        // Check if there is any duplicated workslot
        $sql = "SELECT * FROM bids WHERE username = ? AND month = ? AND day = ? AND time = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssis", $uname, $selectedMonth, $selectedDay, $selectedTime);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            return false;
        } else {
            // The username is available, so proceed with user registration
            $sql2 = "INSERT INTO bids (username, month, day, time, dayofweek, role, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')";
            $stmt2 = mysqli_prepare($this->conn, $sql2);
            mysqli_stmt_bind_param($stmt2, "ssisss", $uname, $selectedMonth, $selectedDay, $selectedTime, $dayOfWeek, $selectedRole);
            if (mysqli_stmt_execute($stmt2)) {
                // User registration was successful
                return true;
            } else {
                // User registration failed
                return false;
            }
        }
    }

    public function managerCreateBid($uname, $selectedMonth, $selectedDay, $selectedTime, $dayOfWeek, $selectedRole){
        // Check if there is any duplicated workslot
        $sql = "SELECT * FROM bids WHERE username = ? AND month = ? AND day = ? AND time = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssis", $uname, $selectedMonth, $selectedDay, $selectedTime);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            return false;
        } else {
            // The username is available, so proceed with user registration
            $sql2 = "INSERT INTO bids (username, month, day, time, dayofweek, role, status) VALUES (?, ?, ?, ?, ?, ?, 'success')";
            $stmt2 = mysqli_prepare($this->conn, $sql2);
            mysqli_stmt_bind_param($stmt2, "ssisss", $uname, $selectedMonth, $selectedDay, $selectedTime, $dayOfWeek, $selectedRole);
            if (mysqli_stmt_execute($stmt2)) {
                // User registration was successful
                return true;
            } else {
                // User registration failed
                return false;
            }
        }
    }

    public function getBidsByUName($username) {
        $sql = "SELECT * FROM bids WHERE username = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $userData = array(); // Initialize an array to store the results
        while ($row = mysqli_fetch_assoc($result)) {
            $userData[] = array(
                'id' => $row['id'],
                'username' => $row['username'],
                'month' => $row['month'],
                'day' => $row['day'],
                'time' => $row['time'],
                'dayofweek' => $row['dayofweek'],
                'role' => $row['role'],
                'status' => $row['status']
            );
        }
        return $userData;
    }

    public function getBidsById($bidid) {
        $sql = "SELECT * FROM bids WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $bidid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        // Fetch a single record
        $row = mysqli_fetch_assoc($result);
        
        if ($row) {
            return array(
                'id' => $row['id'],
                'username' => $row['username'],
                'month' => $row['month'],
                'day' => $row['day'],
                'time' => $row['time'],
                'dayofweek' => $row['dayofweek'],
                'role' => $row['role'],
                'status' => $row['status']
            );
        } else {
            return null; // Return null if no record was found
        }
    }
    

    public function updateBid($id, $selectedMonth, $selectedDay, $selectedTime, $dayOfWeek, $role){
        $sql = "SELECT * FROM bids WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) !== 1) {
            return false;
        } else {
            $sql2 = "UPDATE bids SET month = ?, day = ?, time = ?, dayofweek = ?, role = ?, status = 'pending' WHERE id = ?";
            $stmt2 = mysqli_prepare($this->conn, $sql2);
            mysqli_stmt_bind_param($stmt2, "sssssi", $selectedMonth, $selectedDay, $selectedTime, $dayOfWeek, $role, $id);
            if (mysqli_stmt_execute($stmt2)) {
                // Update successful
                return true;
            } else {
                return false;
            }
        }
    }

    public function acceptBid($id) {
        // Check if the bid exists
        $sql = "SELECT * FROM bids WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) !== 1) {
            return false; // Bid not found
        } else {
            // Update the bid status to 'success'
            $sql2 = "UPDATE bids SET status = 'success' WHERE id = ?";
            $stmt2 = mysqli_prepare($this->conn, $sql2);
            mysqli_stmt_bind_param($stmt2, "i", $id);
            if (mysqli_stmt_execute($stmt2)) {
                // Update successful
                return true;
            } else {
                return false;
            }
        }
    }

    public function rejectBid($id) {
        // Check if the bid exists
        $sql = "SELECT * FROM bids WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) !== 1) {
            return false; // Bid not found
        } else {
            // Update the bid status to 'success'
            $sql2 = "UPDATE bids SET status = 'rejected' WHERE id = ?";
            $stmt2 = mysqli_prepare($this->conn, $sql2);
            mysqli_stmt_bind_param($stmt2, "i", $id);
            if (mysqli_stmt_execute($stmt2)) {
                // Update successful
                return true;
            } else {
                return false;
            }
        }
    }
    

    public function deleteBid($bidid) {
        // Check if the user with the given ID exists
        $sql = "SELECT * FROM bids WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $bidid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 0) {
            // The bid with the given ID does not exist
            return false;
        } else {
            // The bid exists, so proceed with bid deletion
            $sql2 = "DELETE FROM bids WHERE id = ?";
            $stmt2 = mysqli_prepare($this->conn, $sql2);
            mysqli_stmt_bind_param($stmt2, "i", $bidid);

            if (mysqli_stmt_execute($stmt2)) {
                // User deletion was successful
                return true;
            } else {
                return false;
            }
        }
    }

    public function deleteBidByUname($uname) {
        // Check if the user with the given ID exists
        $sql = "SELECT * FROM bids WHERE username = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $uname);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 0) {
            // The bid with the given ID does not exist
            return false;
        } else {
            // The bid exists, so proceed with bid deletion
            $sql2 = "DELETE FROM bids WHERE username = ?";
            $stmt2 = mysqli_prepare($this->conn, $sql2);
            mysqli_stmt_bind_param($stmt2, "s", $uname);

            if (mysqli_stmt_execute($stmt2)) {
                // User deletion was successful
                return true;
            } else {
                return false;
            }
        }
    }

    public function searchBid($search, $username) {
        // Perform a search query to find users based on a search criteria
        $sql = "SELECT * FROM bids WHERE (month LIKE ? OR day LIKE ? OR time LIKE ? OR role LIKE ? OR status LIKE ?) AND username = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        // Add wildcard symbols to the search string for partial matching
        $search = '%' . $search . '%';
        mysqli_stmt_bind_param($stmt, "ssssss", $search, $search, $search, $search, $search, $username);
        mysqli_stmt_execute($stmt);
        $result2 = mysqli_stmt_get_result($stmt);
        $result = array();

        if (mysqli_num_rows($result2) > 0) {
            while ($row = mysqli_fetch_assoc($result2)) {
                $result[] = $row;
            }
            mysqli_free_result($result2); // Free the result set
            return $result;
        } else {
            // No results found
            return false;
        }
    }

    public function managerSearchBid($search){
        // Perform a search query to find users based on a search criteria
        $sql = "SELECT * FROM bids WHERE username LIKE ? OR day LIKE ? OR month LIKE ? OR time LIKE ? OR role LIKE ? OR status LIKE ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        // Add wildcard symbols to the search string for partial matching
        $search = '%' . $search . '%';
        mysqli_stmt_bind_param($stmt, "ssssss", $search, $search, $search, $search, $search, $search);
        mysqli_stmt_execute($stmt);
        $result2 = mysqli_stmt_get_result($stmt);
        $result = array();

        if (mysqli_num_rows($result2) > 0) {
            while ($row = mysqli_fetch_assoc($result2)) {
                $result[] = $row;
            }
            mysqli_free_result($result2); // Free the result set
            return $result;
        } else {
            // No results found
            return false;
        }
    }
}

class Workslot {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    public function createWorkslot($selectedMonth, $selectedDay, $selectedTime, $dayOfWeek) {
        $sql = "SELECT * FROM workslots WHERE month = ? AND day = ? AND time = ? AND dayofweek = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $selectedMonth, $selectedDay, $selectedTime, $dayOfWeek);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 0){
            // Create a new workslot entry in the database with the status set to "pending"
            $sql2 = "INSERT INTO workslots (`month`, `day`, `time`, `dayofweek`, `status`) VALUES (?, ?, ?, ?, 'not full')";
            $stmt2 = mysqli_prepare($this->conn, $sql2);
            mysqli_stmt_bind_param($stmt2, "ssss", $selectedMonth, $selectedDay, $selectedTime, $dayOfWeek);
            if (mysqli_stmt_execute($stmt2)) {
                // Workslot creation was successful
                return true;
            } else {
                // Workslot creation failed
                return false;
            }
        } else {
            return false;
        }
    }

    public function getAllWorkslot() {
        // Retrieve all workslots
        $sql = "SELECT * FROM workslots";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $workslotData = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $workslotData[] = $row;
        }
        return $workslotData;
    }

    public function updateWorkslot($workslotid, $selectedDay, $selectedMonth, $selectedTime, $dayOfWeek){
        $sql = "SELECT * FROM workslots WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $workslotid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 1) {
            $sql2 = "SELECT * FROM workslots WHERE month = ? AND day = ? AND time = ?";
            $stmt2 = mysqli_prepare($this->conn, $sql2);
            mysqli_stmt_bind_param($stmt2, "sis", $selectedMonth, $selectedDay, $selectedTime);
            mysqli_stmt_execute($stmt2);
            $result2 = mysqli_stmt_get_result($stmt2);

            if (mysqli_num_rows($result2) === 0){
                $sql3 = "UPDATE workslots SET month = ?, day = ?, time = ?, dayofweek = ? WHERE id = ?";
                $stmt3 = mysqli_prepare($this->conn, $sql3);
                mysqli_stmt_bind_param($stmt3, "sissi", $selectedMonth, $selectedDay, $selectedTime, $dayOfWeek, $workslotid);
                if (mysqli_stmt_execute($stmt3)) {
                    // Update successful
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    

    // public function updateWorkslot($id, $role, $name) {
    //     // Update a workslot for a specific id
    //     if ($role === 'cashier') {
    //         $sql = "UPDATE workslots SET cashier = ? WHERE id = ? AND cashier = ''";
    //     } elseif ($role === 'cleaner') {
    //         $sql = "UPDATE workslots SET cleaner = ? WHERE id = ? AND cleaner = ''";
    //     } elseif ($role === 'waiter') {
    //         $sql = "UPDATE workslots SET waiter = ? WHERE id = ? AND waiter = ''";
    //     }
    //     $stmt = mysqli_prepare($this->conn, $sql);
    //     mysqli_stmt_bind_param($stmt, "si", $name, $id);
    //     if (mysqli_stmt_execute($stmt)) {
    //         $affectedRows = mysqli_stmt_affected_rows($stmt);
    //         if ($affectedRows > 0) {
    //             // Workslot update was successful
    //             return true;
    //         } else {
    //             // Workslot update didn't happen because the column was not empty
    //             return false;
    //         }
    //     } else {
    //         // Workslot update failed
    //         return false;
    //     }
    // }
    
    public function updateWorkslotRole($month, $day, $time, $name, $role){
        $sql = "SELECT * FROM workslots WHERE month = ? AND day = ? AND time = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $month, $day, $time);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 1) {
            if ($role == 'cashier'){
                $sql2 = "UPDATE workslots SET cashier = ? WHERE month = ? AND day = ? AND time = ?";
            } else if ($role == 'cleaner'){
                $sql2 = "UPDATE workslots SET cleaner = ? WHERE month = ? AND day = ? AND time = ?";
            } else if ($role == 'waiter'){
                $sql2 = "UPDATE workslots SET waiter = ? WHERE month = ? AND day = ? AND time = ?";
            }
            $stmt2 = mysqli_prepare($this->conn, $sql2);
            mysqli_stmt_bind_param($stmt2, "ssss", $name, $month, $day, $time);
            if (mysqli_stmt_execute($stmt2)) {
                // Update successful
                return true;
            } else {
                return false;
            }
        } else {
            // No results found or multiple results found
            return false;
        }
    }

    public function retriveWorkslotRoleIdByName($name){
        $sql = "SELECT * FROM workslots WHERE cashier = ? OR cleaner = ? OR waiter = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $name, $name, $name);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $workslots = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $workslots[] = $row;
        }
        return $workslots;
    }

    public function deleteWorkslotCashierRoleById($id, $name){
        $sql = "UPDATE workslots SET cashier = '' WHERE id = ? AND cashier = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "is", $id, $name);
        if (mysqli_stmt_execute($stmt)) {
            // Update successful
            return true;
        } else {
            return false;
        }
    }

    public function deleteWorkslotCleanerRoleById($id, $name){
        $sql = "UPDATE workslots SET cleaner = '' WHERE id = ? AND cleaner = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "is", $id, $name);
        if (mysqli_stmt_execute($stmt)) {
            // Update successful
            return true;
        } else {
            return false;
        }
    }

    public function deleteWorkslotWaiterRoleById($id, $name){
        $sql = "UPDATE workslots SET waiter = '' WHERE id = ? AND waiter = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "is", $id, $name);
        if (mysqli_stmt_execute($stmt)) {
            // Update successful
            return true;
        } else {
            return false;
        }
    }


    public function updateWorkslotCashierRoleById($id, $oldName, $name){
        $sql = "UPDATE workslots SET cashier = ? WHERE id = ? AND cashier = ?";  
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "sis", $name, $id, $oldName);
    
        if (mysqli_stmt_execute($stmt)) {
            // Update successful
            return true;
        } else {
            return false;
        }
    }

    public function updateWorkslotCleanerRoleById($id, $oldName, $name){
        $sql = "UPDATE workslots SET cleaner = ? WHERE id = ? AND cleaner = ?";  
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "sis", $name, $id, $oldName);
    
        if (mysqli_stmt_execute($stmt)) {
            // Update successful
            return true;
        } else {
            return false;
        }
    }

    public function updateWorkslotWaiterRoleById($id, $oldName, $name){
        $sql = "UPDATE workslots SET waiter = ? WHERE id = ? AND waiter = ?";  
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "sis", $name, $id, $oldName);
    
        if (mysqli_stmt_execute($stmt)) {
            // Update successful
            return true;
        } else {
            return false;
        }
    }
    
    // Workslot full or not full
    public function updateWorkslotStatus($id) {
        // Find the workslot by ID
        $sql = "SELECT cashier, cleaner, waiter FROM workslots WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) !== 1) {
            // Workslot with the provided ID was not found
            return false;
        } else {
            $row = mysqli_fetch_assoc($result);
            $cashier = $row['cashier'];
            $cleaner = $row['cleaner'];
            $waiter = $row['waiter'];
    
            // Check if all roles are not empty
            if (!empty($cashier) && !empty($cleaner) && !empty($waiter)) {
                // Update status to 'full'
                $updateSql = "UPDATE workslots SET status = 'full' WHERE id = ?";
                $updateStmt = mysqli_prepare($this->conn, $updateSql);
                mysqli_stmt_bind_param($updateStmt, "i", $id);
                if (mysqli_stmt_execute($updateStmt)) {
                    // Workslot status updated to 'full' successfully
                    return true;
                } else {
                    // Status update failed
                    return false;
                }
            
            // Check if any role is empty
            } else if (empty($cashier) || empty($cleaner) || empty($waiter)) {
                // Update status to 'full'
                $updateSql2 = "UPDATE workslots SET status = 'not full' WHERE id = ?";
                $updateStmt2 = mysqli_prepare($this->conn, $updateSql2);
                mysqli_stmt_bind_param($updateStmt2, "i", $id);
                if (mysqli_stmt_execute($updateStmt2)) {
                    // Workslot status updated to 'not full' successfully
                    return true;
                } else {
                    // Status update failed
                    return false;
                }
            }
        }
    }

    public function deleteWorkslotById($workslotid) {
        // Delete a workslot for a specific id
        $sql = "DELETE FROM workslots WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $workslotid);

        if (mysqli_stmt_execute($stmt)) {
            // Workslot deletion was successful
            return true;
        } else {
            // Workslot deletion failed
            return false;
        }
    }

    public function searchWorkslot($search) {
        // Perform a search query to find users based on a search criteria
        $sql = "SELECT * FROM workslots WHERE id LIKE ? OR month LIKE ? OR day LIKE ? OR time LIKE ? OR dayofweek LIKE ? OR cashier LIKE ? OR cleaner LIKE ? OR waiter LIKE ? OR status LIKE ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        // Add wildcard symbols to the search string for partial matching
        $search = '%' . $search . '%';
        mysqli_stmt_bind_param($stmt, "sssssssss", $search, $search, $search, $search, $search, $search, $search, $search, $search);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $workslots = array();

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $workslots[] = $row;
            }
            mysqli_free_result($result); // Free the result set
            return $workslots;
        } else {
            // No results found
            return false;
        }
    }
    
    public function searchWorkslotByOtherInfo($month, $day, $time) {
        $sql = "SELECT * FROM workslots WHERE month = ? AND day = ? AND time = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $month, $day, $time);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            $userData = [
                'id' => $row['id'],
                'month' => $row['month'],
                'day' => $row['day'],
                'time' => $row['time'],
                'cashier' => $row['cashier'],
                'cleaner' => $row['cleaner'],
                'waiter' => $row['waiter'],
                'status' => $row['status']
            ];
            return $userData;
        } else {
            return false;
        }
    }

    public function findWorkslot($month, $day, $time) {
        // Perform a search query to find a workslot based on the provided criteria
        $sql = "SELECT * FROM workslots WHERE month = ? AND day = ? AND time = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $month, $day, $time);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    
        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            mysqli_free_result($result); // Free the result set
            return $row; // Return the single row as an associative array
        } else {
            // No results found or multiple results found
            return false;
        }
    }   
}
?>