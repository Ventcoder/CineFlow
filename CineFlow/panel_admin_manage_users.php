<?php
include 'header.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<div class='container'><h2>Access Denied. Admins Only.</h2></div>";
    include 'footer.php';
    exit();
}

$toast = '';

// Handle Delete User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $id = (int)$_POST['user_id'];
    if ($id === $_SESSION['user_id']) {
        $toast = "You cannot delete yourself.";
    } else {
        pg_query_params($db, "DELETE FROM users WHERE id = $1", [$id]);
        $toast = "User deleted.";
    }
}
?>

<div class="admin-layout">
    <div class="admin-sidebar">
        <h3>Admin Panel</h3>
        <ul class="sidebar-nav">
            <li><a href="panel_admin_dashboard.php">Dashboard</a></li>
            <li><a href="panel_admin_manage_02_page_catalog.php">Manage Movies</a></li>
            <li><a href="panel_admin_manage_users.php">Manage Users</a></li>
        </ul>
    </div>
    
    <div class="admin-main">
        <h2>Manage Users</h2>
        <?php if ($toast) echo "<div class='toast success'>$toast</div>"; ?>

        <table>
            <thead>
                <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php
                $res = pg_query($db, "SELECT * FROM users ORDER BY id ASC");
                while ($row = pg_fetch_assoc($res)) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>" . htmlspecialchars($row['name']) . "</td>
                        <td>" . htmlspecialchars($row['email']) . "</td>
                        <td>{$row['role']}</td>
                        <td>
                            <form method='POST' onsubmit='return confirm(\"Delete user?\");'>
                                <input type='hidden' name='user_id' value='{$row['id']}'>
                                <button type='submit' name='delete_user' class='btn btn-danger'>Delete</button>
                            </form>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
