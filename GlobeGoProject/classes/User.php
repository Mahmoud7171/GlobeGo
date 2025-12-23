<?php
require_once __DIR__ . '/../config/config.php';

class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $email;
    public $password;
    public $first_name;
    public $last_name;
    public $role;
    public $phone;
    public $profile_image;
    public $bio;
    public $languages;
    public $verified;
    public $status;
    public $national_id;
    public $date_of_birth;
    public $address;
    public $criminal_records;
    public $application_status;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Register new user
    public function register() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET email=:email, password=:password, first_name=:first_name, 
                      last_name=:last_name, role=:role, phone=:phone";
        
        $stmt = $this->conn->prepare($query);
        
        // Hash password
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        
        // Bind values
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":role", $this->role);
        $stmt->bindParam(":phone", $this->phone);
        
        return $stmt->execute();
    }

    // Auto-unsuspend users whose suspension period has expired
    public function autoUnsuspendExpired() {
        $query = "UPDATE " . $this->table_name . " 
                  SET status = 'active', suspend_until = NULL 
                  WHERE status = 'suspended' 
                  AND suspend_until IS NOT NULL 
                  AND suspend_until <= NOW()";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }

    // Login user - returns array with 'success' and optional 'suspension_info'
    public function login($email, $password) {
        // First, auto-unsuspend any expired suspensions
        $this->autoUnsuspendExpired();
        
        $query = "SELECT id, email, password, first_name, last_name, role, verified, status, suspend_until 
                  FROM " . $this->table_name . " 
                  WHERE email = :email";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Check if user is suspended and suspension hasn't expired
            if ($row['status'] === 'suspended' && $row['suspend_until'] && strtotime($row['suspend_until']) > time()) {
                // Return suspension info
                return [
                    'success' => false,
                    'suspended' => true,
                    'suspend_until' => $row['suspend_until']
                ];
            }
            
            // If suspended but expired, auto-unsuspend
            if ($row['status'] === 'suspended' && (!$row['suspend_until'] || strtotime($row['suspend_until']) <= time())) {
                $this->unsuspendUser($row['id']);
                $row['status'] = 'active';
            }
            
            // Only allow login if status is active
            if ($row['status'] !== 'active') {
                return ['success' => false];
            }
            
            if(password_verify($password, $row['password'])) {
                $this->id = $row['id'];
                $this->email = $row['email'];
                $this->first_name = $row['first_name'];
                $this->last_name = $row['last_name'];
                $this->role = $row['role'];
                $this->verified = $row['verified'];
                $this->status = $row['status'];
                return ['success' => true];
            }
        }
        return ['success' => false];
    }

    // Get user by ID
    public function getUserById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->email = $row['email'];
            $this->first_name = $row['first_name'];
            $this->last_name = $row['last_name'];
            $this->role = $row['role'];
            $this->phone = $row['phone'];
            $this->profile_image = $row['profile_image'];
            $this->bio = $row['bio'];
            $this->languages = $row['languages'];
            $this->verified = $row['verified'];
            $this->status = $row['status'];
            return true;
        }
        return false;
    }

    // Check if email exists
    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Update user profile
    public function updateProfile() {
        $query = "UPDATE " . $this->table_name . " 
                  SET first_name=:first_name, last_name=:last_name, phone=:phone, 
                      profile_image=:profile_image, bio=:bio, languages=:languages 
                  WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":profile_image", $this->profile_image);
        $stmt->bindParam(":bio", $this->bio);
        $stmt->bindParam(":languages", $this->languages);
        $stmt->bindParam(":id", $this->id);
        
        return $stmt->execute();
    }

    // Get all users (for admin)
    public function getAllUsers() {
        $query = "SELECT id, email, first_name, last_name, role, verified, status, 
                         application_status, phone, national_id, suspend_until, created_at 
                  FROM " . $this->table_name . " 
                  ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update user status (for admin)
    public function updateUserStatus($id, $status) {
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // Suspend user with duration (for admin)
    public function suspendUser($id, $duration_days) {
        $suspend_until = null;
        if ($duration_days > 0) {
            $suspend_until = date('Y-m-d H:i:s', strtotime("+{$duration_days} days"));
        }
        
        $query = "UPDATE " . $this->table_name . " 
                  SET status = 'suspended', suspend_until = :suspend_until 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":suspend_until", $suspend_until);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // Unsuspend user (for admin)
    public function unsuspendUser($id) {
        $query = "UPDATE " . $this->table_name . " 
                  SET status = 'active', suspend_until = NULL 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // Delete user (for admin)
    public function deleteUser($id) {
        // Prevent deleting admin users
        $check_query = "SELECT role FROM " . $this->table_name . " WHERE id = :id";
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(":id", $id);
        $check_stmt->execute();
        $user_data = $check_stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user_data && $user_data['role'] === 'admin') {
            return false; // Cannot delete admin
        }
        
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // Approve user/guide application (for admin)
    public function approveUser($id) {
        // Check if user is a guide
        $user_query = "SELECT role, application_status FROM " . $this->table_name . " WHERE id = :id";
        $user_stmt = $this->conn->prepare($user_query);
        $user_stmt->bindParam(":id", $id);
        $user_stmt->execute();
        $user_data = $user_stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user_data && $user_data['role'] === 'guide') {
            // For guides: set status to active, application_status to approved, and verify them automatically
            $query = "UPDATE " . $this->table_name . " 
                      SET status = 'active', application_status = 'approved', verified = 1 
                      WHERE id = :id";
        } else {
            // For regular users: just set status to active
            $query = "UPDATE " . $this->table_name . " 
                      SET status = 'active' 
                      WHERE id = :id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // Reject user/guide application (for admin)
    public function rejectUser($id) {
        // Check if user is a guide
        $user_query = "SELECT role FROM " . $this->table_name . " WHERE id = :id";
        $user_stmt = $this->conn->prepare($user_query);
        $user_stmt->bindParam(":id", $id);
        $user_stmt->execute();
        $user_data = $user_stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user_data && $user_data['role'] === 'guide') {
            // For guides: set status to suspended and application_status to rejected
            $query = "UPDATE " . $this->table_name . " 
                      SET status = 'suspended', application_status = 'rejected' 
                      WHERE id = :id";
        } else {
            // For regular users: just set status to suspended
            $query = "UPDATE " . $this->table_name . " 
                      SET status = 'suspended' 
                      WHERE id = :id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // Verify guide (for admin) - also activates if pending
    public function verifyGuide($id) {
        // Get current status
        $user_query = "SELECT status FROM " . $this->table_name . " WHERE id = :id";
        $user_stmt = $this->conn->prepare($user_query);
        $user_stmt->bindParam(":id", $id);
        $user_stmt->execute();
        $user_data = $user_stmt->fetch(PDO::FETCH_ASSOC);
        
        $new_status = ($user_data && $user_data['status'] === 'pending') ? 'active' : $user_data['status'];
        
        // Update verified status and activate if pending
        $query = "UPDATE " . $this->table_name . " 
                  SET verified = 1, status = :status 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $new_status);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // Register new guide (with additional fields)
    public function registerGuide() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET email=:email, password=:password, first_name=:first_name, 
                      last_name=:last_name, role='guide', phone=:phone, 
                      national_id=:national_id, date_of_birth=:date_of_birth, 
                      address=:address, criminal_records=:criminal_records, 
                      application_status='pending', status='pending'";
        
        $stmt = $this->conn->prepare($query);
        
        // Hash password
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        
        // Bind values
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":national_id", $this->national_id);
        $stmt->bindParam(":date_of_birth", $this->date_of_birth);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":criminal_records", $this->criminal_records);
        
        return $stmt->execute();
    }

    // Get user by email
    public function getUserByEmail($email) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->email = $row['email'];
            $this->first_name = $row['first_name'];
            $this->last_name = $row['last_name'];
            $this->role = $row['role'];
            return true;
        }
        return false;
    }

    // Create password reset token
    public function createPasswordResetToken($email) {
        // Check if email exists
        if (!$this->getUserByEmail($email)) {
            return false;
        }

        // Generate token
        $token = generateToken();
        $expires = date('Y-m-d H:i:s', strtotime('+24 hours'));

        // Check if reset_token column exists, if not we'll need to add it
        // For now, we'll try to update it
        $query = "UPDATE " . $this->table_name . " 
                  SET reset_token = :token, reset_token_expires = :expires 
                  WHERE email = :email";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":token", $token);
            $stmt->bindParam(":expires", $expires);
            $stmt->bindParam(":email", $email);
            
            if ($stmt->execute()) {
                return $token;
            }
        } catch (PDOException $e) {
            // Column might not exist, we'll handle this in the migration
            error_log("Password reset token error: " . $e->getMessage());
        }
        
        return false;
    }

    // Verify password reset token
    public function verifyPasswordResetToken($token) {
        $query = "SELECT id, email, reset_token_expires FROM " . $this->table_name . " 
                  WHERE reset_token = :token AND reset_token_expires > NOW()";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":token", $token);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->email = $row['email'];
            return true;
        }
        return false;
    }

    // Reset password using token
    public function resetPassword($token, $new_password) {
        // Verify token first
        if (!$this->verifyPasswordResetToken($token)) {
            return false;
        }

        // Hash new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password and clear reset token
        $query = "UPDATE " . $this->table_name . " 
                  SET password = :password, reset_token = NULL, reset_token_expires = NULL 
                  WHERE reset_token = :token";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":token", $token);
        
        return $stmt->execute();
    }
}
?>
