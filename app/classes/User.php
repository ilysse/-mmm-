<?php
ob_start();

class User extends Objects {
	protected $pdo;
	
	// construct $pdo
	function __construct($pdo) {
		$this->pdo = $pdo;
	}

	// user login method to dashboard
	public function login($username, $pass) {
		try {
			// Prepare the statement
			$stmt = $this->pdo->prepare("SELECT * FROM user WHERE username = :username");
			$stmt->bindValue(":username", $username, PDO::PARAM_STR);
			$stmt->execute();
	
			// Fetch user
			$user = $stmt->fetch(PDO::FETCH_OBJ);
	
			if ($user && password_verify($pass, $user->password)) {
				// Set session variables
				$_SESSION['user_id'] = $user->id;
				$_SESSION['user_role'] = $user->user_role;
				
				// Redirect to the index page
				redirect("index.php");
			} else {
				// Set error message and redirect to login
				$_SESSION['login_error'] = "Invalid Username or Password";
				redirect("login.php");
			}
		} catch (PDOException $e) {
			// Log error and redirect to login
			error_log('Database error: ' . $e->getMessage());
			$_SESSION['login_error'] = "An error occurred. Please try again.";
			redirect("login.php");
		}
	}
	



	 // Get total records
	 public function getTotalRecords($searchValue) {
        $searchQuery = '';
        if ($searchValue != '') {
            $searchQuery = " WHERE (username LIKE :username)";
        }

        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS allcount FROM user" . $searchQuery);
        if ($searchValue != '') {
            $stmt->bindValue(':username', "%$searchValue%", PDO::PARAM_STR);
        }
        $stmt->execute();
        $records = $stmt->fetch();
        return $records['allcount'];
    }

    // Fetch users
    public function getUsers($searchValue, $columnName, $columnSortOrder, $start, $length) {
        $searchQuery = '';
        if ($searchValue != '') {
            $searchQuery = " WHERE (username LIKE :username)";
        }

        $stmt = $this->pdo->prepare("SELECT * FROM user" . $searchQuery . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT :start, :length");
        if ($searchValue != '') {
            $stmt->bindValue(':username', "%$searchValue%", PDO::PARAM_STR);
        }
        $stmt->bindValue(':start', (int)$start, PDO::PARAM_INT);
        $stmt->bindValue(':length', (int)$length, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
	public function getUserById($id) {
		error_log("Attempting to get user with ID: $id");
		$query = "SELECT id, username, user_role FROM user WHERE id = :id";
		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		
		try {
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			error_log("Query result: " . print_r($result, true));
			return $result;
		} catch (PDOException $e) {
			error_log("Database error in getUserById: " . $e->getMessage());
			return false;
		}
	}
	

    public function updateUser($id, $username, $userRole) {
        $query = "UPDATE user SET username = :username, user_role = :user_role WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':user_role', $userRole, PDO::PARAM_STR);
        return $stmt->execute();
    }
	public function is_admin(){
		if ($_SESSION['user_role'] === 'admin') {
			return true;
		}else{
			return false;
		}
	}

	public function redirect_unauth_users($page){
		if ($_SESSION['user_role'] === 'admin') {
			return true;
		}else{
			redirect($page);
		}
	}


	//is user loged in or not
	public function is_login() {
		if (!empty($_SESSION['user_id'])) {
			return true;
		} else {
			return false;
		}
	}


	public function logOut() {
		unset($_SESSION['user_id']);
		unset($_SESSION['user_role']);
		$_SESSION = array();
		session_destroy();
		redirect("login.php");
	}

	public function checkUser($username)
	{
	  $stmt = $this->pdo->prepare("SELECT username FROM user WHERE username = :username AND deleted_at = ''");
	  $stmt->bindParam(":username", $username, PDO::PARAM_STR);
	  $stmt->execute();
	  $count = $stmt->rowCount();
	  return ($count > 0)? true : false;
	}


	//check email if it is alrady sign up
	public function checkEmail($email)
	{
	  $stmt = $this->pdo->prepare("SELECT email FROM users WHERE email = :email AND deleted_at = ''");
	  $stmt->bindParam(":email", $email, PDO::PARAM_STR);
	  $stmt->execute();
	  $count = $stmt->rowCount();
	  return ($count > 0)? true : false;
	}

	public function userLog(){
		$stmt = $this->pdo->prepare("SELECT * FROM logs ORDER BY id DESC LIMIT 5 ");
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	// check email if it is alrady sign up
	public function checkUsername($username)
	{
	  $stmt = $this->pdo->prepare("SELECT username FROM user WHERE username = :username");
	  $stmt->bindParam(":username", $username, PDO::PARAM_STR);
	  $stmt->execute();
	  $count = $stmt->rowCount();
	  return ($count > 0)? true : false;
	}

	// user resigstration method
	public function register($username, $password, $user_role)
    {
        // Check if username already exists
        if ($this->checkUsername($username)) {
            return ['success' => false, 'message' => 'Username already exists'];
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

		$stmt = $this->pdo->prepare("INSERT INTO user (username, password, user_role, update_by, last_update_at) VALUES (:username, :password, :user_role, 1, UNIX_TIMESTAMP())");
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);
        $stmt->bindParam(":user_role", $user_role, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'User registered successfully'];
        } else {
            return ['success' => false, 'message' => 'Registration failed'];
        }
    }
	public function updateUserPassword($userId, $newPassword) {
		$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
		$stmt = $this->pdo->prepare("UPDATE user SET password = :password WHERE id = :id");
		$stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
		$stmt->bindParam(':id', $userId, PDO::PARAM_INT);
		return $stmt->execute();
	}
	public function deluser (){
		
	}
	public function deleteUser($userId) {
		try {
			$stmt = $this->pdo->prepare("DELETE FROM user WHERE id = :id");
			$stmt->bindParam(':id', $userId, PDO::PARAM_INT);
			
			if ($stmt->execute()) {
				return ['success' => true, 'message' => 'User deleted successfully'];
			} else {
				return ['success' => false, 'message' => 'Failed to delete user'];
			}
		} catch (PDOException $e) {
			error_log('Database error in deleteUser: ' . $e->getMessage());
			return ['success' => false, 'message' => 'An error occurred while deleting the user'];
		}
	}

}

?>
