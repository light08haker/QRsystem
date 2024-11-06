<!-- navbar.php -->

<style>
/* Navbar Styling */
.navbar {
    background-color: #0b6e22;
}

.navbar .nav-link {
    color: white !important;
    padding: 10px 15px; /* Add padding for better spacing */
    transition: background-color 0.3s, color 0.3s;
    border-radius: 5px; /* Optional: for rounded corners */
}

.navbar .nav-link:hover {
    background-color: #28a745; /* Highlight color */
    color: white !important; /* Keep the text color white */
}
</style>

<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="userForm.php">My Profile</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
      <li class="nav-item">
          <a class="nav-link" href="time.php?id=<?= $user['id']; ?>">My Time</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="edit.php?id=<?= $user['id']; ?>">Edit</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="changePass.php?id=<?= $user['id']; ?>">Manage Password</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Log Out</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
