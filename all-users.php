<?php 
  $corepage = explode('/', $_SERVER['PHP_SELF']);
  $corepage = end($corepage);
  if ($corepage !== 'index.php') {
    if ($corepage == $corepage) {
      $corepage = explode('.', $corepage);
      header('Location: index.php?page=' . $corepage[0]);
    }
  }
?>
<div class="text-center">
    <img src="8.png" alt="Logo" style="width:1200px; height: auto; max-width: 100%; height: auto;">
</div>
<h1 class="text-primary"><i class="fas fa-users-cog"></i>  All Users<small class="text-warning"> All Users List!</small></h1>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
     <li class="breadcrumb-item" aria-current="page"><a href="index.php">Dashboard </a></li>
     <li class="breadcrumb-item active" aria-current="page">All Users</li>
  </ol>
</nav>

<table class="table  table-striped table-hover table-bordered" id="data">
  <thead class="thead-dark">
    <tr>
      <th scope="col">No</th>
      <th scope="col">Name</th>
      <th scope="col">Username</th>
      <th scope="col">Photo</th>
      <th scope="col">Status</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php 
      $query = mysqli_query($db_con, 'SELECT * FROM `users`');
      $i = 1;
      while ($result = mysqli_fetch_array($query)) { ?>
      <tr>
        <?php 
        echo '<td>' . $i . '</td>
          <td>' . ucwords($result['name']) . '</td>
          <td>' . ucwords($result['username']) . '</td>
          <td><img src="images/' . $result['photo'] . '" height="50px"></td>
          <td>
            <select class="form-control status-dropdown" data-userid="' . $result['id'] . '">
              <option value="active" ' . ($result['status'] == 'active' ? 'selected' : '') . '>Active</option>
              <option value="inactive" ' . ($result['status'] == 'inactive' ? 'selected' : '') . '>Inactive</option>
            </select>
          </td>'; ?>
          <td>
            <!-- Delete Button with confirmation prompt -->
            <button class="btn btn-danger btn-delete" data-userid="<?php echo $result['id']; ?>">Delete</button>
          </td>
      </tr>  
     <?php $i++; } ?>
  </tbody>
</table>

<!-- Include jQuery for AJAX functionality -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
    // When the status dropdown is changed
    $('.status-dropdown').change(function(){
      var user_id = $(this).data('userid');
      var new_status = $(this).val();

      // Send AJAX request to update status in the database
      $.ajax({
        url: 'update_status.php',
        type: 'POST',
        data: {
          'user_id': user_id,
          'status': new_status
        },
        success: function(response) {
          if (response == 'success') {
            alert('Status updated successfully.');
          } else {
            alert('Error updating status.');
          }
        }
      });
    });

    // When the delete button is clicked
    $('.btn-delete').click(function(){
      var user_id = $(this).data('userid');
      
      // Confirm deletion
      if(confirm('Are you sure you want to delete this user?')) {
        // Send AJAX request to delete user
        $.ajax({
          url: 'delete_user.php',
          type: 'POST',
          data: {
            'user_id': user_id
          },
          success: function(response) {
            if (response == 'success') {
              alert('User deleted successfully.');
              location.reload(); // Reload the page to update the table
            } else {
              alert('Error deleting user.');
            }
          }
        });
      }
    });
  });
</script>
