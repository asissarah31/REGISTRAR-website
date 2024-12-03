<?php 
  // Redirection logic
  $corepage = explode('/', $_SERVER['PHP_SELF']);
  $corepage = end($corepage);
  if ($corepage !== 'index.php') {
    if ($corepage == $corepage) {
      $corepage = explode('.', $corepage);
      header('Location: index.php?page='.$corepage[0]);
    }
  }
?>
<div class="text-center">
    <img src="8.png" alt="Logo" style="width:1200px; height: auto; position: auto; max-width: 100%; height: auto;">
</div>
<h1><a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a> <small> VIEW</small></h1>


<div class="row student">
  <!-- Total Students Card -->
  <div class="col-sm-4">
    <div class="card text-white bg-primary mb-3">
      <div class="card-header">
        <div class="row">
          <div class="col-sm-4">
            <i class="fa fa-users fa-3x"></i>
          </div>
          <div class="col-sm-8">
            <div class="float-sm-right">
              <span style="font-size: 34px">
                <?php 
                  $stu = mysqli_query($db_con, 'SELECT * FROM `student_info`'); 
                  $stu = mysqli_num_rows($stu); 
                  echo $stu; 
                ?>
              </span>
            </div>
            <div class="clearfix"></div>
            <div class="float-sm-right">Total Students</div>
          </div>
        </div>
      </div>
      <div class="list-group-item-primary list-group-item list-group-item-action">
        <div class="row">
          <div class="col-sm-8">
            <p class="">All Students</p>
          </div>
          <div class="col-sm-4">
            <a href="all-student.php"><i class="fa fa-arrow-right float-sm-right"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Total Users Card -->
  <div class="col-sm-4">
    <div class="card text-white bg-info mb-3">
      <div class="card-header">
        <div class="row">
		
          <div class="col-sm-4">
            <i class="fa fa-users fa-3x"></i>
          </div>
          <div class="col-sm-8">
            <div class="float-sm-right">
              <span style="font-size: 34px">
                <?php 
                  $tusers = mysqli_query($db_con, 'SELECT * FROM `users`'); 
                  $tusers = mysqli_num_rows($tusers); 
                  echo $tusers; 
                ?>
              </span>
            </div>
            <div class="clearfix"> </div> 
            <div class="float-sm-right">Total Users </div>
          </div>
        </div>
      </div>
      <div class="list-group-item-primary list-group-item list-group-item-action">
        <a href="index.php?page=all-users">
          <div class="row"> 
            <div class="col-sm-8">
              <p class="">All Users</p>
            </div>
            <div class="col-sm-4">
              <i class="fa fa-arrow-right float-sm-right"></i>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>

  <!-- Profile Card -->
  <div class="col-sm-4">
    <div class="card text-black bg-warning mb-3">
      <div class="card-header">
        <div class="row">
          <?php 
            $usernameshow = $_SESSION['user_login']; 
            $userspro = mysqli_query($db_con, "SELECT * FROM `users` WHERE `username`='$usernameshow';"); 
            $userrow = mysqli_fetch_array($userspro); 
          ?>
          <div class="col-sm-6">
            <img class="showimg" src="images/<?php echo $userrow['photo']; ?>" alt="User Image">
            <div style="font-size: 20px"><?php echo ucwords($userrow['name']); ?></div>
          </div>
          <div class="col-sm-6">
            <div class="clearfix"></div>
            <div class="float-sm-right">Welcome!</div>
          </div>
        </div>
      </div>
      <div class="list-group-item-primary list-group-item list-group-item-action">
        <a href="index.php?page=user-profile">
          <div class="row">
            <div class="col-sm-8">
              <p class="">Your Profile</p>
            </div>
            <div class="col-sm-4">
              <i class="fa fa-arrow-right float-sm-right"></i>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>  
</div>
    <!-- Vision, Mission, and Goals Section -->
    <div class="alert alert-info mt-5"><h5>University Vision, Mission, and Goals</h5></div>
    <div class="row text-center">
        <!-- Vision Image -->
        <div class="col-md-4">
            <img src="vision1.png" alt="University Vision" class="img-fluid mb-2" style="width:100%; height:auto;">
            <h6><b>Vision</b></h6>
            <p>Our vision is to become a premier institution known for academic excellence and innovation.</p>
        </div>
        <!-- Mission Image -->
        <div class="col-md-4">
            <img src="mission1.png" alt="University Mission" class="img-fluid mb-2" style="width:100%; height:auto;">
            <h6><b>Mission</b></h6>
            <p>Our mission is to educate, inspire, and empower students to make a positive impact in their communities.</p>
        </div>
        <!-- Goals Image -->
        <div class="col-md-4">
            <img src="images/goals1.png" alt="University Goals" class="img-fluid mb-2" style="width:100%; height:auto;">
            <h6><b>Goals</b></h6>
            <p>Our goals focus on fostering research, promoting inclusivity, and ensuring lifelong learning.</p>
        </div>
    </div>
</div>

  </tbody>
</table>
