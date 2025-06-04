<?php include './admin_session_validation.php'; ?>
<?php
include '../connection.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] != 3) {
  echo "<script>alert('You don’t have access to this page'); window.location.href = '../login.php';</script>";
  exit();
}

$conn = db_connect();

// Fetch complaint types
$query = "SELECT id, name, is_active FROM complaint_type";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Add complaint type
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_complaint_type'])) {
  $name = $_POST['complaint_name'];
  $is_active = $_POST['is_active'];
  $insert = mysqli_prepare($conn, "INSERT INTO complaint_type (name, is_active) VALUES (?, ?)");
  mysqli_stmt_bind_param($insert, "si", $name, $is_active);
  mysqli_stmt_execute($insert);
  header("Location: complaint_type.php");
  exit();
}

// Update complaint type
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_complaint_type'])) {
  $id = $_POST['complaint_id'];
  $name = $_POST['edit_complaint_name'];
  $is_active = $_POST['edit_is_active'];
  $update = mysqli_prepare($conn, "UPDATE complaint_type SET name = ?, is_active = ? WHERE id = ?");
  mysqli_stmt_bind_param($update, "sii", $name, $is_active, $id);
  mysqli_stmt_execute($update);
  header("Location: complaint_type.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Complaint Types</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <link rel="stylesheet" href="../admin/css/admin.css">


  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">

</head>

<body>
  <?php include './nav.php'; ?>

  <div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 >Complaint Types</h2>
      <button class="btn btn-success" onclick="openAddModal()">Add Complaint Type</button>
    </div>

    <div class="table-responsive bg-white p-3 rounded shadow-sm">
  <table id="complaintTypeTable" class="table table-bordered table-striped text-center w-100 mb-0">
    <thead class="table-success">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($result)) {
        $status = $row['is_active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
        $safeName = htmlspecialchars($row['name'], ENT_QUOTES); // Escape name for JS
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td>{$status}</td>
                <td>
                    <button class='btn btn-sm btn-warning' onclick=\"openEditModal({$row['id']}, '{$safeName}', {$row['is_active']})\">Edit</button>
                </td>
              </tr>";
      } ?>
    </tbody>
  </table>
</div>

  </div>

  <!-- Add Modal -->
  <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" class="modal-content">
        <div class="modal-header">
          <h5>Add Complaint Type</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <label>Name</label>
          <input type="text" name="complaint_name" class="form-control mb-2" required>
          <label>Status</label>
          <select name="is_active" class="form-select">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="submit" name="add_complaint_type" class="btn btn-success">Add</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Edit Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" class="modal-content">
        <div class="modal-header">
          <h5>Edit Complaint Type</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="complaint_id" id="complaint_id">
          <label>Name</label>
          <input type="text" name="edit_complaint_name" id="edit_complaint_name" class="form-control mb-2" required>
          <label>Status</label>
          <select name="edit_is_active" id="edit_is_active" class="form-select">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="submit" name="update_complaint_type" class="btn btn-warning">Update</button>
        </div>
      </form>
    </div>
  </div>

  <!-- JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#complaintTypeTable').DataTable({
        responsive: true,
        scrollX: true
      });
    });

    function openAddModal() {
      $('#addModal').modal('show');
    }

    function openEditModal(id, name, status) {
      $('#complaint_id').val(id);
      $('#edit_complaint_name').val(name);
      $('#edit_is_active').val(status);
      $('#editModal').modal('show');
    }
  </script>
</body>

</html>

<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>