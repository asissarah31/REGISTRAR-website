<?php
// Redirect to index.php if the current page is not 'index.php'
$corepage = basename($_SERVER['PHP_SELF']);
if ($corepage !== 'index.php') {
    header('Location: index.php?page=' . pathinfo($corepage, PATHINFO_FILENAME));
    exit(); // Ensure to exit after redirection
}

// Check if form is submitted
if (isset($_POST['addstudent'])) {
    $student_id = $_POST['student_id'];
    $lrn = $_POST['lrn'];
	$lname = $_POST['lname'];
	$fname = $_POST['fname'];
	$mname = $_POST['mname'];
	$suffix = $_POST['suffix'];
    $course = $_POST['course'];
    $year = $_POST['year'];
    $section = $_POST['section'];
    $year_section = $year . $section;
	$previous_school = $_POST['previous_school']; // Capture previous school
    $academic_year = $_POST['academic_year'];
	$student_type = $_POST['student_type'];

    // Handling file upload for photo
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $allowed_ext = array("jpg", "jpeg", "png", "gif");
        $photo_ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        
        if (in_array(strtolower($photo_ext), $allowed_ext)) {
            $photo_name = $student_id . date('Y-m-d-m-s') . '.' . $photo_ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], 'images/' . $photo_name);
        } else {
            $datainsert['inserterror'] = '<p style="color: red;">Invalid file type for photo!</p>';
        }
    } else {
        $photo_name = null; // Handle case where no photo is uploaded
    }

    // Insert query with year_section and student_type 
    $query = "INSERT INTO `student_info`(`student_id`,`lrn`,`lname`,`fname`,`mname`,`suffix`,  `course`, `year_section`,`previous_school`,`academic_year`, `photo`, `student_type`) 
              VALUES ('$student_id', '$lrn', '$lname','$fname','$mname','$suffix','$course', '$year_section','$previous_school','$academic_year', '$photo_name', '$student_type')";
    
    if (mysqli_query($db_con, $query)) {
        $datainsert['insertsuccess'] = '<p style="color: green;">Student Inserted!</p>';
    } else {
        $datainsert['inserterror'] = '<p style="color: red;">Student Not Inserted: ' . mysqli_error($db_con) . '</p>';
    }
}
?>
<div class="text-center">
    <img src="8.png" alt="Logo" style="width:1200px; height: auto; max-width: 100%; height: auto;">
</div>
<h1 class="text-primary"><i class="fas fa-user-plus"></i> Add Student <small class="text-warning">Add New Student!</small></h1>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item" aria-current="page"><a href="index.php">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Student</li>
  </ol>
</nav>

<div class="row">
  <div class="col-sm-6">
    <?php if (isset($datainsert)) { ?>
      <div role="alert" class="toast fade show" data-autohide="true" data-delay="2000">
        <div class="toast-header">
          <strong class="mr-auto">Student Insert Alert</strong>
          <small><?php echo date('d-M-Y'); ?></small>
          <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="toast-body">
          <?php 
            if (isset($datainsert['insertsuccess'])) {
              echo $datainsert['insertsuccess'];
            }
            if (isset($datainsert['inserterror'])) {
              echo $datainsert['inserterror'];
            }
          ?>
        </div>
      </div>
    <?php } ?>
    <form enctype="multipart/form-data" method="POST" action="">
  <div class="form-row">
    <!-- Student ID on the first row -->
    <div class="form-group col-md-4">
      <label for="student_id">Student ID</label>
      <input name="student_id" type="text" class="form-control" id="student_id" required>
    </div>
	
	<div class="form-group col-md-4">
      <label for="lrn">LRN</label>
      <input name="lrn" type="text" class="form-control" id="lrn">
    </div>
	
  </div>
  
 <div class="form-row">
    <!-- Last Name, First Name, Middle Name, and Suffix on the same row -->
    <div class="form-group col-md-3">
      <label for="lname">Last Name</label>
      <input name="lname" type="text" class="form-control" id="lname" required>
    </div>
    
    <div class="form-group col-md-4">
      <label for="fname">First Name</label>
      <input name="fname" type="text" class="form-control" id="fname" required>
    </div>
    
    <div class="form-group col-md-3">
      <label for="mname">Middle Name</label>
      <input name="mname" type="text" class="form-control" id="mname">
    </div>
	
    <div class="form-group col-md-2">
      <label for="suffix">Suffix</label>
      <input name="suffix" type="text" class="form-control" id="suffix">
    </div>
</div>

		

      <div class="form-row">
        <!-- Course, Year, and Section on one line -->
        <div class="form-group col-md-4">
          <label for="course">Course</label>
          <select name="course" class="form-control" id="course" required>
            <option>Select</option>
            <option value="BSCS">BSCS</option>
            <option value="BSOA">BSOA</option>
            <option value="BAEL">BAEL</option>
            <option value="BSFT">BSFT</option>
          </select>
        </div>
        <div class="form-group col-md-4">
          <label for="year">Year</label>
          <select name="year" class="form-control" id="year" required>
            <option>Select</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
          </select>
        </div>
        <div class="form-group col-md-4">
          <label for="section">Section</label>  
          <select name="section" class="form-control" id="section" required>
            <option>Select</option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
          </select>
        </div>
      </div>
	  
	  <div class="form-row">
    <!-- Previous School and Academic Year on the same row -->
    <div class="form-group col-md-8">
        <label for="previous_school">Previous School</label>
        <input name="previous_school" type="text" class="form-control" id="previous_school" placeholder="Enter previous school" required>
    </div>
    <div class="form-group col-md-4">
        <label for="academic_year">A.Y.</label>
        <input name="academic_year" type="text" class="form-control" id="academic_year" placeholder="e.g., 2023-2024" required>
    </div>
</div>


      <div class="form-group">
        <label for="student_type">Student Type</label>
        <select name="student_type" class="form-control" id="student_type" required>
          <option value="">Select Type</option>
          <option value="Transferee">Transferee</option>
          <option value="SeniorHS Graduate">SeniorHS Graduate</option>
		   <option value="ALS">ALS</option>
        </select>
      </div>

      <div class="form-group">
        <label for="photo">Photo</label>
        <input name="photo" type="file" class="form-control" id="photo" required>
      </div>

      <div class="form-group text-center">
        <input name="addstudent" value="Add Student" type="submit" class="btn btn-danger">
      </div>
    </form>
  </div>
</div>
