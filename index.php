<?php
include 'db.php';
$errors=["Invalid Email Address"=>"","Invalid phone number"=>"","Invalid Phone Number"=>"","Invalid Name"=>"","Invalid Date"=>""];
if($_SERVER["REQUEST_METHOD"] == "POST") {
  if(isset($_POST["submit"])) {
    $name = trim($_POST["name"]);
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $service = $_POST["service"];
    $date=$_POST["date"];
    $time=$_POST["time"];
    
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      $errors["Invalid Email Address"] = "Invalid email address";
    }
    if(!is_numeric($phone) or strlen($phone) < 11) {
      $errors['Invalid Phone Number'] = "Invalid Phone Number";
    }
    if(strlen($name) < 3 or preg_match("/[0-09]/", $name)) {
      $errors['Invalid Name'] = "Invalid Name";

  }
  if($date<date("Y-m-d")) {
    $errors["Invalid Date"] = "Invalid Date";
  
  }
  if (empty(array_filter(array: $errors))) {
    $stmt = $conn->prepare("INSERT INTO data (name, email, phone, service, date, time) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $email, $phone, $service, $date, $time);

    if ($stmt->execute()) {
        echo "Booking successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    exit();
}

  
  }
}
else {

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Book Your Appointmendt</h1>
        <form  action=""  method="POST">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" placeholder="Enter your name" required>
            <span class='error' style='color:brown'><?php echo $errors["Invalid Name"]; ?></span>

          

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            <span class='error' style='color:red'><?php echo $errors["Invalid Email Address"]; ?></span>
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
            <span class='error' style='color:brown'><?php echo $errors["Invalid Phone Number"]; ?></span>
            <label for="service">Select Service</label>
            <select id="service" name="service" required>
                <option value="">Choose a service</option>
                <option value="consultation">Consultation</option>
                <option value="therapy">Therapy</option>
                <option value="follow-up">Follow-up</option>
            </select>

            <label for="date">Select Date</label>
            <input type="date" id="date" name="date" required>
              <span class='error' style='color:brown'><?php echo $errors["Invalid Date"]; ?></span>
            <label for="time">Select Time</label>
            <input type="time" id="time" name="time" required>

            <button type="submit" name="submit">Book Appointment</button>
        </form>
        <div id="confirmation" style="display: none;">
            <h2>Appointment Confirmed</h2>
            <p id="confirmationDetails"></p>
            <button id="editButton">Edit Booking</button>
        </div>
    </div>
    <!-- <script src="scripts.js"></script> -->
</body>
</html>
