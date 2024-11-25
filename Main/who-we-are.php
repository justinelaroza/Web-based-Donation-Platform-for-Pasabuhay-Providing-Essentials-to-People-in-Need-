<?php 
    require_once '../Database/db.php';
    session_start();

    function sanitizeInput($field, $filter = FILTER_SANITIZE_SPECIAL_CHARS) { //function to sanitize inputs
        return filter_input(INPUT_POST, $field, $filter);
    }

    $db = new DataBase();

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit-message'])) {

        if(empty(trim($_POST['fullname'])) || empty(trim($_POST['phone'])) || empty(trim($_POST['message']))) {
            $error = "Please fill in all fields.";
        }
        else {
            $fullname = sanitizeInput('fullname');
            $phone = sanitizeInput('phone');
            $message = sanitizeInput('message');

            $stmt = $db->getConnection()->prepare("INSERT INTO volunteer(name, phone, message) VALUES (?, ?, ?) ");
            $stmt->bind_param("sss", $fullname, $phone, $message);
            $stmt->execute();
            $stmt->close();

            $success = "Welcome to Pasabuhay! Please do wait for our response.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="who-we-are.css">
</head>
<body>
    <?php 
        include_once 'header.php';
    ?>

    <div class="whole-upper"></div>
    <div class="upper-text">
        <h2>Pasabuhay – A New Beginning for Community Support</h2>
        <p>Your chance to make a <b>difference</b> starts here. <i><strong><b>Join us</b></strong></i> in our mission to <i>uplift lives</i> and support communities in need.</p>
    </div>
    <div class="info-wrapper">
        <div class="child-info" style="border-right: solid 1px black;">
            <div class="text-info">
                <p><b>Pasabuhay</b> – A New Chapter in Community Care and Support</p>
                <br>
                <p><i>Founded with a vision</i> of uplifting communities, <b>Pasabuhay</b> empowers individuals and partners to help make a real difference. Since 2024, <b>Pasabuhay</b> has connected people from all walks of life, allowing everyone—from volunteers and donors to local organizations and leaders—to join forces and bring hope to those in need.</p>
                <br>
                <p>Through <b>Pasabuhay</b>, we support initiatives that provide food, clothing, and essential resources to underprivileged families. <i>Our sustainable projects</i> create pathways for others to contribute, spreading compassion across the Philippines. <i><b>Together</b></i>, we are building a foundation that generations to come can rely on, ensuring that every act of kindness leaves a lasting impact on our communities.</p>
            </div>
        </div>
        <div class="child-info">
            <div class="image-info">
                <img src="../-Pictures/pic7.jpeg" alt="pasabuhay members">
            </div>
            <div class="smaller-pic">
                <img src="../-Pictures/pic8.jpg" alt="pasabuhay members">
            </div>
        </div>
    </div>
    <div class="become-member" id="become-member">
        <div class="member-text">
            <h2>The Philippines needs more compassionate changemakers.</h2>
            <p>Help pave the way for a future where every community thrives. Join <b>PASABUHAY</b> and make a lasting impact today.</p>
        </div>
        <div class="input-member">
            <label class="become">BECOME ONE OF US, BE A VOLUNTEER !</label>
            <form action="who-we-are.php" method="post" class="form-member">
                <div class="name-wrapper">
                    <div class="name-first">
                        <label>FULLNAME: <strong>*</strong> </label>
                        <input type="text" name="fullname" class="fullname" required placeholder="Full name"></input>
                    </div>
                    <div class="name-first">
                        <label>PHONE NUMBER: <strong>*</strong></label>
                        <input type="number" name="phone" class="fullname" required placeholder="Phone number"></input>
                    </div>
                </div>
                <div class="text">
                    <p>MESSAGE TO PASABUHAY <strong>*</strong></p>
                    <textarea name="message" class="message" placeholder="Type your message here..." maxlength="1000" required></textarea>
                </div>
                <div class="error-message" >
                    <p style="text-align: center;">
                        <?php echo isset($error) ? $error : ''; ?>
                        <?php echo isset($success) ? $success : ''; ?>
                    </p>
                </div>
                <div class="submit-message">
                    <button name="submit-message">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <?php 
        include_once 'footer.php';
    ?>
</body>
</html>