<?php

$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error)
{
	die("Connection failed: " . $conn->connect_error);
}

main($conn);

function main($connection)
{
	//Create database (SEP)
	createDatabase($connection);

	//Use database (SEP)
	useDatabase($connection);

	//Create tables (users, lists, tasks)
	createTables($connection);

	tests($connection);

	trialRun($connection);

	$connection->close();
}

// ------------------------ Database Setup ------------------------ 

function createDatabase($connection)
{
	$sql = "CREATE DATABASE SEP";

	if ($connection->query($sql) === TRUE)
	{
		echo "Database created successfully\n";
	}

	else
	{
		echo "Error creating database: " . $connection->error."\n";
	}
}

function useDatabase($connection)
{
	$sql = "USE SEP";

	if ($connection->query($sql) === TRUE)
	{
		echo "Using database successfully\n";
	}

	else
	{
		echo "Error using database: " . $connection->error."\n";
	}
}

function createTables($connection)
{
	//Users
	$sql = "CREATE TABLE users (username VARCHAR(30), firstname VARCHAR(30), lastname VARCHAR(30), email VARCHAR(50), password VARCHAR(30), lists INT(6), points INT(6))";

	if ($connection->query($sql) === TRUE)
	{
		echo "Users table created successfully\n";
	}

	else
	{
		echo "Error creating users table: " . $connection->error."\n";
	}

	//Lists
	$sql = "CREATE TABLE lists (id INT(6), username VARCHAR(30), name VARCHAR(100), colour VARCHAR(100), category VARCHAR(100))";

	if ($connection->query($sql) === TRUE)
	{
		echo "Lists table created successfully\n";
	}

	else
	{
		echo "Error creating lists table: " . $connection->error."\n";
	}

	//Tasks
	$sql = "CREATE TABLE tasks (id INT(6), listID INT(6), state BOOLEAN, content VARCHAR(100))";

	if ($connection->query($sql) === TRUE)
	{
		echo "Tasks table created successfully\n";
	}

	else
	{
		echo "Error creating tasks table: " . $connection->error."\n";
	}
}

function newID($connection, $table)
{
	orderTable($connection, $table);

	$currentID = 0;

	$sql = "SELECT id FROM $table";

	while(true)
	{
		$result = $connection->query($sql);

		if ($result->num_rows > 0)
		{
		    while($row = $result->fetch_assoc())
		    {
		        if ($row['id'] == $currentID)
		        {
		        	$currentID++;
		        }

		        else
		        {
		        	return $currentID;
		        }
		    }
		}

		else
		{
		    return 0;
		}
	}
}

function orderTable($connection, $table)
{
	$sql = "ALTER TABLE $table ORDER BY id ASC";

	if ($connection->query($sql) === TRUE)
	{
		echo "Table ordered successfully.\n";
	}

	else
	{
		echo "Error ordering table: " . $connection->error."\n";
	}
}

// ------------------------ Tests ------------------------ 

function tests($connection)
{
	//Add users
	register($connection, "5T3V3", "Steve", "Johnson", "s.john@gmail.com", "StevePassword");

	register($connection, "B4RB4R4", "Barbara", "Johnson", "b.john@gmail.com", "BarbaraPassword");

	//Test users
	register($connection, "5T3V3", "Steve", "Johnson", "s1.john@gmail.com", "StevePassword");

	register($connection, "B4RB4R4", "Barbara", "Johnson", "b1.john@gmail.com", "BarbaraPassword");

	register($connection, "5T3V31", "Steve", "Johnson", "s.john@gmail.com", "StevePassword");

	register($connection, "B4RB4R41", "Barbara", "Johnson", "b.john@gmail.com", "BarbaraPassword");

	//Change password
	changePassword($connection, "5T3V3", "s.john@gmail.com", "NewStevePassword");

	changePassword($connection, "B4RB4R4", "b.john@gmail.com", "NewBarbaraPassword");

	//Add points
	addPoints($connection, "5T3V3", "10");

	addPoints($connection, "B4RB4R4", "20");

	//Add lists
	addList($connection, "5T3V3", "List 1", "Green", "Sport");

	addList($connection, "B4RB4R4", "List 1", "Green", "Sport");

	addList($connection, "B4RB4R4", "List 2", "Red", "School");

	addList($connection, "B4RB4R4", "List 3", "Blue", "Shopping");

	//Remove lists
	removeList($connection, "B4RB4R4", "List 2");

	//Add tasks
	addTask($connection, 0, "Task 1");

	addTask($connection, 1, "Task 2");

	addTask($connection, 1, "Task 3");

	addTask($connection, 2, "Task 4");

	//Change task content
	changeTaskContent($connection, 1, "Changed Task");

	changeTaskContent($connection, 2, "Changed Task");

	//Change task state
	changeTaskState($connection, 1, "B4RB4R4");

	changeTaskState($connection, 2, "B4RB4R4");

	//Remove task
	removeTask($connection, 3);
}

function trialRun($connection)
{
	$username = "D4V3";
	$firstname = "Dave";
	$lastname = "Shooter";
	$email = "d.shooter@gmail.com";
	$password = "DavePassword";

	$listName = "My List";
	$colour = "Green";
	$category = "Personal";

	$listName2 = "Shopping List";
	$colour2 = "Blue";
	$category2 = "Shopping";

	$content1 = "Do my homework.";
	$content2 = "Clean my room.";
	$content3 = "Have a shower.";

	$content4 = "Oranges";
	$content5 = "Apples";
	$content6 = "Chocolate";

	echo "----------------------------------------------------------------\n";
	register($connection, $username, $firstname, $lastname, $email, $password);
	addList($connection, $username, $listName, $colour, $category);
	addTask($connection, getListID($connection, $username, $listName), $content1);
	addTask($connection, getListID($connection, $username, $listName), $content2);
	addTask($connection, getListID($connection, $username, $listName), $content3);
	displayLists($connection, $username);
	echo "----------------------------------------------------------------\n";
	changeTaskState($connection, getTaskID($connection, getListID($connection, $username, $listName), $content3), $username);
	displayLists($connection, $username);
	echo "================================================================\n";
	addList($connection, $username, $listName2, $colour2, $category2);
	addTask($connection, getListID($connection, $username, $listName2), $content4);
	addTask($connection, getListID($connection, $username, $listName2), $content5);
	addTask($connection, getListID($connection, $username, $listName2), $content6);
	displayLists($connection, $username);
	echo "================================================================\n";
	changeTaskState($connection, getTaskID($connection, getListID($connection, $username, $listName2), $content6), $username);
	displayLists($connection, $username);
	echo "================================================================\n";
	removeList($connection, $username, $listName2);
	displayLists($connection, $username);
}

// ------------------------ Users ------------------------ 

function checkPassword($connection, $username, $password)
{
	$sql = "SELECT password AND username FROM users";
	$result = $connection->query($sql);
	
	if ($result->num_rows > 0)
	{
		while ($result1 = $result->fetch_assoc())
		{
		    if($result1['username'] == $username && $result1['password'] == $password)
		    {
		    	return true;
		    }
		}
	}

	return false;
}

function checkUsername($connection, $username)
{
	$sql = "SELECT username FROM users";
	$result = $connection->query($sql);
	
	if ($result->num_rows > 0)
	{
		while ($result1 = $result->fetch_assoc())
		{
		    if($result1['username'] == $username)
		    {
		    	return true;
		    }
		}
	}

	return false;
}

function checkEmail($connection, $email)
{
	$sql = "SELECT email FROM users";
	$result = $connection->query($sql);
	
	if ($result->num_rows > 0)
	{
		while ($result1 = $result->fetch_assoc())
		{
		    if($result1['email'] == $email)
		    {
		    	return true;
		    }
		}
	}

	return false;
}

function register($connection, $username, $firstname, $lastname, $email, $password)
{
	$username = strtolower($username);
	$firstname = strtolower($firstname);
	$lastname = strtolower($lastname);
	$email = strtolower($email);

	if (checkUsername($connection, $username))
	{
		echo "Username already taken: $username\n";
	}

	else
	{
		if (checkEmail($connection, $email))
		{
			echo "Email already in use: $email\n";
		}

		else
		{
			$sql = "INSERT INTO users (username, firstname, lastname, email, password, lists, points) VALUES ('$username', '$firstname', '$lastname', '$email', '$password', '0', '0')";

			if ($connection->query($sql) === TRUE)
			{
				echo "User added successfully.\n";
			}

			else
			{
				echo "Error adding user: " . $connection->error."\n";
			}
		}
	}
}

function changePassword($connection, $username, $email, $newPassword)
{
	$username = strtolower($username);
	$email = strtolower($email);

	$sql = "UPDATE users SET password = '$newPassword' WHERE username = '$username' AND email = '$email'";

    if ($connection->query($sql) === TRUE)
    {
	    echo "Password changed.\n";
	}

	else
	{
	    echo "Error changing password: " . $connection->error."\n";
	}
}

function getPoints($connection, $username)
{
	$username = strtolower($username);

	$sql = "SELECT points FROM users WHERE username = '$username'";
	$result = $connection->query($sql);
	$result1 = $result->fetch_assoc();
	return $result1['points'];
}

function addPoints($connection, $username, $points)
{
	$username = strtolower($username);
	$points = getPoints($connection, $username) + $points;

	$sql = "UPDATE users SET points = '$points' WHERE username = '$username'";

    if ($connection->query($sql) === TRUE)
    {
	    echo "Points added.\n";
	}

	else
	{
	    echo "Error adding points: " . $connection->error."\n";
	}
}

function removePoints($connection, $username, $points)
{
	$username = strtolower($username);
	$points = getPoints($connection, $username) - $points;

	$sql = "UPDATE users SET points = '$points' WHERE username = '$username'";

    if ($connection->query($sql) === TRUE)
    {
	    echo "Points removed.\n";
	}

	else
	{
	    echo "Error removing points: " . $connection->error."\n";
	}
}

// ------------------------ Lists ------------------------ 

function displayLists($connection, $username)
{
	$sql = "SELECT id, name, colour, category FROM lists WHERE username = '$username'";
	$result = $connection->query($sql);
	
	if ($result->num_rows > 0)
	{
		while ($result1 = $result->fetch_assoc())
		{
		    echo $result1['name']." ".$result1['colour']." ".$result1['category']."\n";
		    displayTasks($connection, $result1['id']);
		    echo "\n\n";
		}
	}
}

function getListID($connection, $username, $name)
{
	$username = strtolower($username);

	$sql = "SELECT id FROM lists WHERE username = '$username' AND name = '$name'";
	$result = $connection->query($sql);
	$result1 = $result->fetch_assoc();
	return $result1['id'];
}

function getLists($connection, $username)
{
	$username = strtolower($username);

	$sql = "SELECT lists FROM users WHERE username = '$username'";
	$result = $connection->query($sql);
	$result1 = $result->fetch_assoc();
	return $result1['lists'];
}

function addList($connection, $username, $name, $colour, $category)
{
	$username = strtolower($username);
	$lists = getLists($connection, $username) + 1;
	if ($lists >= 6)
	{
		echo "Error increasing list number: Maximum amount of lists available\n"; 
	}

	else
	{	
		$sql = "INSERT INTO lists (id, username, name, colour, category) VALUES ('".newID($connection, "lists")."', '$username', '$name', '$colour', '$category')";

	    if ($connection->query($sql) === TRUE)
	    {
		    echo "List added successfully.\n";
		}

		else
		{
		    echo "Error adding list: " . $connection->error."\n";
		}

		$sql = "UPDATE users SET lists = '$lists' WHERE username = '$username'";

	    if ($connection->query($sql) === TRUE)
	    {
		    echo "List number increased.\n";
		}

		else
		{
		    echo "Error increasing list number: " . $connection->error."\n";
		}
	}
}

function removeList($connection, $username, $name)
{
	$username = strtolower($username);
	$lists = getLists($connection, $username) - 1;

	if ($lists <= 0)
	{
		echo "Error removing list: Minimum amount of lists available\n"; 
	}

	else
	{
		$sql = "DELETE FROM tasks WHERE listID = '".getListID($connection, $username, $name)."'";

	    if ($connection->query($sql) === TRUE)
	    {
		    echo "Tasks removed successfully.\n";
		}

		else
		{
		    echo "Error removing tasks: " . $connection->error."\n";
		}

		$sql = "DELETE FROM lists WHERE username = '$username' AND name = '$name'";

	    if ($connection->query($sql) === TRUE)
	    {
		    echo "List removed successfully.\n";
		}

		else
		{
		    echo "Error removing list: " . $connection->error."\n";
		}

		$sql = "UPDATE users SET lists = '$lists' WHERE username = '$username'";

	    if ($connection->query($sql) === TRUE)
	    {
		    echo "List number decreased.\n";
		}

		else
		{
		    echo "Error decreasing list number: " . $connection->error."\n";
		}
	}
}

// ------------------------ Tasks ------------------------ 

function getTaskID($connection, $listID, $content)
{
	$sql = "SELECT id FROM tasks WHERE listID = '$listID' AND content = '$content'";
	$result = $connection->query($sql);
	$result1 = $result->fetch_assoc();
	
	return $result1['id'];
}

function displayTasks($connection, $id)
{
	$sql = "SELECT state, content FROM tasks WHERE listID = '$id'";
	$result = $connection->query($sql);
	
	if ($result->num_rows > 0)
	{
		while ($result1 = $result->fetch_assoc())
		{
		    echo $result1['content']." ".$result1['state']."\n";
		}
	}
}

function addTask($connection, $listID, $content)
{
	$sql = "INSERT INTO tasks (id, listID, state, content) VALUES ('".newID($connection, "tasks")."', '$listID', FALSE, '$content')";

	if ($connection->query($sql) === TRUE)
	{
		echo "Task added successfully.\n";
	}

	else
	{
		echo "Error adding task: " . $connection->error."\n";
	}
}

function changeTaskContent($connection, $id, $newContent)
{
	$sql = "UPDATE tasks SET content = '$newContent' WHERE id = '$id'";

    if ($connection->query($sql) === TRUE)
    {
	    echo "Task content changed.\n";
	}

	else
	{
	    echo "Error changing task content: " . $connection->error."\n";
	}
}

function getTaskState($connection, $id)
{
	$sql = "SELECT state FROM tasks WHERE id = '$id'";
	$result = $connection->query($sql);
	$result1 = $result->fetch_assoc();
	
	if($result1['state'])
	{
		return 0;
	}

	return 1;
}

function changeTaskState($connection, $id, $username)
{
	$state = getTaskState($connection, $id);
	$sql = "UPDATE tasks SET state = '$state' WHERE id = '$id'";

    if ($connection->query($sql) === TRUE)
    {
	    echo "Task state changed.\n";

		if ($state)
		{
			addPoints($connection, $username, 10);
		}

		else
		{
			removePoints($connection, $username, 10);
		}
	}

	else
	{
	    echo "Error changing task state: " . $connection->error."\n";
	}
}

function removeTask($connection, $id)
{
	$sql = "DELETE FROM tasks WHERE id = '$id'";

    if ($connection->query($sql) === TRUE)
    {
	    echo "Task removed.\n";
	}

	else
	{
	    echo "Error removing task: " . $connection->error."\n";
	}
}

?>