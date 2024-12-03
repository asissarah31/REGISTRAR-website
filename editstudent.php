<?php
ob_start(); // Start output buffering

require_once 'db_con.php';

// Retrieve page name
$corepage = explode('/', $_SERVER['PHP_SELF']);
$corepage = end($corepage);

// Validate required GET parameters
if (!isset($_GET['id']) || !isset($_GET['photo'])) {
    header('Location: index.php');
    exit();
}

// Decode parameters
$id = base64_decode($_GET['id']);
$oldPhoto = base64_decode($_GET['photo']);

// Handle form submission for updating student
if (isset($_POST['updatestudent'])) {
    $student_id = mysqli_real_escape_string($db_con, $_POST['student_id']);
    $lrn = mysqli_real_escape_string($db_con, $_POST['lrn']);
    $lname = mysqli_real_escape_string($db_con, $_POST['lname']);
    $fname = mysqli_real_escape_string($db_con, $_POST['fname']);
    $mname = mysqli_real_escape_string($db_con, $_POST['mname']);
    $suffix = mysqli_real_escape_string($db_con, $_POST['suffix']);
    $course = mysqli_real_escape_string($db_con, $_POST['course']);
    $year = mysqli_real_escape_string($db_con, $_POST['year']);
    $section = mysqli_real_escape_string($db_con, $_POST['section']);
    $student_type = mysqli_real_escape_string($db_con, $_POST['student_type']);
    $previous_school = mysqli_real_escape_string($db_con, $_POST['previous_school']);
    $year_section = $year . $section;

    // Check if a new photo is uploaded
    if ($_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoName = $_FILES['photo']['name'];
        $photoTmpName = $_FILES['photo']['tmp_name'];
        $photoExt = strtolower(pathinfo($photoName, PATHINFO_EXTENSION));
        $photo = $student_id . '_' . date('YmdHis') . '.' . $photoExt;

        // Validate file type and size
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($photoExt, $allowedExts)) {
            header('Location: index.php?page=all-student&edit=invalid_file_type');
            exit();
        } elseif ($_FILES['photo']['size'] > 2000000) {
            header('Location: index.php?page=all-student&edit=file_too_large');
            exit();
        }

        // Move uploaded file and delete old photo if successful
        $targetDir = 'images/';
        if (move_uploaded_file($photoTmpName, $targetDir . $photo)) {
            if (file_exists($targetDir . $oldPhoto) && $oldPhoto != 'default.png') {
                unlink($targetDir . $oldPhoto);
            }
        } else {
            header('Location: index.php?page=all-student&edit=file_upload_failed');
            exit();
        }
    } else {
        // Use old photo if no new one is uploaded
        $photo = $oldPhoto;
    }

    // Update student information
    $stmt = $db_con->prepare("UPDATE `student_info` SET `student_id`=?, `lrn`=?, `fname`=?, `lname`=?,`mname`=?,`suffix`=?, `course`=?,`year_section`=?, `previous_school`=?, `student_type`=?, `photo`=? WHERE `id`=?");
    $stmt->bind_param("sssssssssssi", $student_id, $lrn, $fname, $lname, $mname, $suffix, $course, $year_section, $previous_school, $student_type, $photo, $id);
    $stmt->execute();
    $stmt->close();

    header('Location: all-student.php');
    exit();
}

// Fetch student information for pre-population
if (isset($id)) {
    $stmt = $db_con->prepare("SELECT `id`, `student_id`, `lrn`, `lname`, `fname`, `mname`, `suffix`, `course`, `year_section`, `student_type`, `photo`, `documents`, `previous_school` FROM `student_info` WHERE `id`=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Pre-populate form values
    $student_id = htmlspecialchars($row['student_id']);
    $lrn = htmlspecialchars($row['lrn']);
    $lname = htmlspecialchars($row['lname']);
    $fname = htmlspecialchars($row['fname']);
    $mname = htmlspecialchars($row['mname']);
    $suffix = htmlspecialchars($row['suffix']);
    $course = htmlspecialchars($row['course']);
    $year_section = htmlspecialchars($row['year_section']);
    $student_type = htmlspecialchars($row['student_type']);
    $photo = htmlspecialchars($row['photo']);
    $previous_school = htmlspecialchars($row['previous_school']);

    // Split year_section into year and section
    $year = substr($year_section, 0, 1);
    $section = substr($year_section, 1);

    $stmt->close();
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <style>
        .form-row { display: flex; flex-wrap: wrap; gap: 10px; }
        .form-group { flex: 1; margin-bottom: 10px; }
        .update-button { margin-top: 20px; }
        .form-group img { margin-bottom: 10px; }
    </style>
</head>
<body>
    <h1 class="text-primary"><i class="fas fa-user-plus"></i> Edit Student Information</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="index.php?page=all-student">All Students</a></li>
            <li class="breadcrumb-item active">Edit Student</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-sm-6">
            <form enctype="multipart/form-data" method="POST" action="">
                <div class="form-row">
                    <!-- Student ID -->
                    <div class="form-group col-md-4">
                        <label for="student_id">Student ID</label>
                        <input name="student_id" type="text" class="form-control" id="student_id" value="<?= $student_id; ?>" required>
                    </div>
                    <!-- LRN -->
                    <div class="form-group col-md-4">
                        <label for="lrn">LRN</label>
                        <input name="lrn" type="text" class="form-control" id="lrn" value="<?= $lrn; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <!-- Names -->
                    <div class="form-group col-md-3">
                        <label for="lname">Last Name</label>
                        <input name="lname" type="text" class="form-control" id="lname" value="<?= $lname; ?>" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="fname">First Name</label>
                        <input name="fname" type="text" class="form-control" id="fname" value="<?= $fname; ?>" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="mname">Middle Name</label>
                        <input name="mname" type="text" class="form-control" id="mname" value="<?= $mname; ?>">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="suffix">Suffix</label>
                        <input name="suffix" type="text" class="form-control" id="suffix" value="<?= $suffix; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <!-- Course -->
                    <div class="form-group col-md-4">
                        <label for="course">Student Course</label>
                        <select name="course" class="form-control" id="course" required>
                            <option>Select</option>
                            <option value="BSCS" <?= ($course == 'BSCS') ? 'selected' : ''; ?>>BSCS</option>
                            <option value="BSOA" <?= ($course == 'BSOA') ? 'selected' : ''; ?>>BSOA</option>
                            <option value="BAEL" <?= ($course == 'BAEL') ? 'selected' : ''; ?>>BAEL</option>
                            <option value="BSFT" <?= ($course == 'BSFT') ? 'selected' : ''; ?>>BSFT</option>
                        </select>
                    </div>
                    <!-- Year -->
                    <div class="form-group col-md-4">
                        <label for="year">Year</label>
                        <select name="year" class="form-control" id="year" required>
                            <option value="" disabled>Select</option>
                            <option value="1" <?= ($year == '1') ? 'selected' : ''; ?>>1</option>
                            <option value="2" <?= ($year == '2') ? 'selected' : ''; ?>>2</option>
                            <option value="3" <?= ($year == '3') ? 'selected' : ''; ?>>3</option>
                            <option value="4" <?= ($year == '4') ? 'selected' : ''; ?>>4</option>
                        </select>
                    </div>
                    <!-- Section -->
                    <div class="form-group col-md-4">
                        <label for="section">Section</label>
                        <select name="section" class="form-control" id="section" required>
                            <option value="" disabled>Select</option>
                            <option value="A" <?= ($section == 'A') ? 'selected' : ''; ?>>A</option>
                            <option value="B" <?= ($section == 'B') ? 'selected' : ''; ?>>B</option>
                            <option value="C" <?= ($section == 'C') ? 'selected' : ''; ?>>C</option>
                            <option value="D" <?= ($section == 'D') ? 'selected' : ''; ?>>D</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <!-- Student Type -->
                    <div class="form-group col-md-4">
                        <label for="student_type">Student Type</label>
                        <select name="student_type" class="form-control" id="student_type" required>
                            <option value="Transferee" <?= ($student_type == 'Transferee') ? 'selected' : ''; ?>>Transferee</option>
                            <option value="SeniorHS Graduate" <?= ($student_type == 'SeniorHS Graduate') ? 'selected' : ''; ?>>SeniorHS Graduate</option>
                            <option value="ALS" <?= ($student_type == 'ALS') ? 'selected' : ''; ?>>ALS</option>
                        </select>
                    </div>
                    <!-- Previous School -->
                    <div class="form-group col-md-8">
                        <label for="previous_school">Previous School</label>
                        <input name="previous_school" type="text" class="form-control" id="previous_school" value="<?= $previous_school; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <img src="images/<?= $photo; ?>" alt="Student Photo" style="width: 2in; height: 2in;">
                    <input type="file" name="photo" class="form-control-file">
                </div>

                <button name="updatestudent" type="submit" class="btn btn-primary update-button">Update Student</button>
            </form>
        </div>
    </div>
</body>
</html>
