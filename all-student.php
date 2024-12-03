<?php
// Redirect to index.php if the current page is not 'index.php'
$corepage = basename($_SERVER['PHP_SELF']);
if ($corepage !== 'index.php') {
    header('Location: index.php?page=' . pathinfo($corepage, PATHINFO_FILENAME));
    exit();
}
?>
<div class="text-center">
    <img src="8.png" alt="Logo" style="width:1200px; height: auto; max-width: 100%; height: auto;">
</div>
<h1 class="text-primary"><i class="fas fa-users"></i> All Students<small class="text-warning"> All Students List!</small></h1>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item" aria-current="page"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">
            <a href="all-student.php?page=all-students">All Students</a>
        </li>
    </ol>
</nav>
<style>
/* CSS for custom-colored action buttons */
.btn-edit {
    color: #FFC107; /* Yellow color for edit */
    background-color: yellow;
    border: 2px solid black; 
}
.btn-delete {
    color: #FF5733; /* Red color for delete */
    background-color: black;
    border: none;
}
.btn-view {
    color: #3498DB; /* Blue color for view */
    background-color: transparent;
    border: none;
}
/* Optional: Add hover effect */
.btn-edit:hover {
    color: #E0A800;
}
.btn-delete:hover {
    color: #C0392B;
}
.btn-view:hover {
    color: #1F618D;
}
#data .col-no {
    text-align: center;
    font-weight: bold;
    width: 5%;

}


/* Set black border for the table and table cells */
table.table {
    border: 2px solid black; /* Outer border of the table */
}

table.table th, table.table td {
    border: 1px solid black; /* Inner cell borders */
}
/* Adjust column widths */


#data .col-student-id {
    width: 11%; /* Adjust to fit 12 numbers */
}

#data .col-lrn {
    width: 2%; 
}
#data .col-name {
    width: 20%; /* Adjust to fit longer names */
}

#data .col-course-yr-sec {
    width: 2%; /* Adjust as needed */
}

#data .col-previous-school {
    width: 2%; /* Adjust to fit school names */
}
#data .col-academic_year {
    width: 10%; 
}
#data .col-student-type {
    width: 10%; /* Adjust to fit student types */
}

#data .col-photo {
    width: 5%; /* Adjust to fit photo */
}

#data .col-action {
    width: 12%; /* Adjust to fit action buttons */
}

.table img {
    max-width: 100%; /* Ensure image does not exceed cell width */
}
/* Ensure buttons are in a single row */
.col-action .btn-group {
    display: flex;
    gap: 5px; /* Adjust space between buttons */
    justify-content: center; /* Center buttons horizontally in the cell */
}

.col-action .btn {
    padding: 2px 2px; /* Adjust padding for a compact look */
    font-size: 1.3rem; /* Make icons smaller if desired */
}

/* Adjust tooltip positioning and styling */
[data-toggle="tooltip"] {
    position: relative;
}

</style>
<?php if (isset($_GET['delete']) || isset($_GET['edit']) || isset($_GET['view'])): ?>
    <div role="alert" aria-live="assertive" aria-atomic="true" class="toast fade show" data-autohide="true" data-animation="true" data-delay="2000">
        <div class="toast-header">
            <strong class="mr-auto">Student Action Alert</strong>
            <small><?php echo date('d-M-Y'); ?></small>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            <?php 
                if (isset($_GET['delete'])) {
                    echo $_GET['delete'] == 'success' ? 
                        "<p style='color: green; font-weight: bold;'>Student Deleted Successfully!</p>" : 
                        "<p style='color: red; font-weight: bold;'>Student Not Deleted!</p>";
                }
                if (isset($_GET['edit'])) {
                    echo $_GET['edit'] == 'success' ? 
                        "<p style='color: green; font-weight: bold;'>Student Edited Successfully!</p>" : 
                        "<p style='color: red; font-weight: bold;'>Student Not Edited!</p>";
                }
                if (isset($_GET['view'])) {
                    echo $_GET['view'] == 'success' ? 
                        "<p style='color: green; font-weight: bold;'>Student Viewed Successfully!</p>" : 
                        "<p style='color: red; font-weight: bold;'>Failed to View Student!</p>";
                }
            ?>
        </div>
    </div>
<?php endif; ?>


<table class="table table-striped table-hover table-bordered" id="data">
    <thead class="thead-dark">
        <tr> 
          <th scope="col" class="col-no"> <center>  No</center></th>  
          <th scope="col" class="col-student-id"> <center> Stud_ID </center></th> 
			<th scope="col" class="col-lrn"><center>LRN </center></th>
            <th scope="col" class="col-lname"><center>Name</center></th>
			 <th scope="col" class="col-course-yr-sec"><center>Course Yr/Sec</center></th>
       <span>     <th scope="col" class="col-previous-school"><center>Prev.School</center></th> </span>
			 <th scope="col" class="col-academic_year"><center>A.Y.</center></th>
            <th scope="col" class="col-student-type"><center>Stud_Type</center></th>
            <th scope="col" class="col-photo">Photo</th>
            <th scope="col" class="col-action"><center>Action</center></th>
        </tr> 
    </thead>
    <tbody>
        <?php 
    $query = mysqli_query($db_con, 'SELECT * FROM `student_info` ORDER BY `datetime` DESC');
    $i = 1;
    while ($result = mysqli_fetch_array($query)): ?>
        <tr>
            <td class="col-no"><?php echo $i; ?></td>
            <td class="col-student-id"><?php echo htmlspecialchars($result['student_id']); ?></td>
            <td class="col-lrn"><?php echo htmlspecialchars($result['lrn']); ?></td> <!-- LRN Display -->
            <td class="col-lname"><?php echo htmlspecialchars(ucwords($result['lname']. ', ' . $result['fname'] . ' ' . $result['mname']. ' ' . $result['suffix'])); ?></td>
            
			<td class="col-course-yr-sec">
                    <?php 
                        echo htmlspecialchars(ucwords($result['course'])) .'-'. 
                             htmlspecialchars($result['year_section']);
                    ?>
                </td> <!-- Combined Course, Year, Section -->
				
            <td class="col-previous-school"><?php echo htmlspecialchars(ucwords($result['previous_school'])); ?></td>
			<td class="col-academic_year"><?php echo htmlspecialchars($result['academic_year']); ?></td>
            <td class="col-student-type"><?php echo htmlspecialchars($result['student_type']); ?></td>
            <td class="col-photo"><img src="images/<?php echo htmlspecialchars($result['photo']); ?>" height="50px" alt="Student Photo"></td>
            <td class="col-action">
                <a class="btn btn-xs btn-warning" style="background-color: #FFC107;" style="color: black;"   href="index.php?page=editstudent&id=<?php echo base64_encode($result['id']); ?>&photo=<?php echo base64_encode($result['photo']); ?>"
                   data-toggle="tooltip"  title="Edit">
                    <i class="fa fa-edit"></i>
                </a>
                
                <a class="btn btn-xs btn-danger" style="background-color: red;" style="color: #000000;"  onclick="confirmationDelete(this); return false;"
                   href="index.php?page=delete&id=<?php echo base64_encode($result['id']); ?>&photo=<?php echo base64_encode($result['photo']); ?>"
                   data-toggle="tooltip" title="Delete">
                    <i class="fas fa-trash-alt"></i>
                </a>
               
                <a class="btn btn-xs btn-info" style="color: #EAEEE9;"  href="view_student.php?id=<?php echo base64_encode($result['id']); ?>"
                   data-toggle="tooltip" title="View">
                    <i class="fas fa-eye"></i>
                </a>
            </td>
        </tr>
<?php 
    $i++;
    endwhile; 
?>

    </tbody>
</table>

<script type="text/javascript">
    function confirmationDelete(anchor) {
        var conf = confirm('Are you sure you want to delete this record?');
        if (conf) {
            window.location = anchor.getAttribute("href");
        }
    }
</script>
<script>
    // Initialize Bootstrap tooltips
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

