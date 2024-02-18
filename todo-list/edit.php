<?php
    // Check if the user is logged in
    if (!isset($_COOKIE['userid'])) {
        header("Location: /");
        exit();
    }
    require_once 'fw/header.php';
?>

<?php if (isset($_GET['id'])) { ?>
    <h1>Edit Task</h1>
<?php }else { ?>
    <h1>Create Task</h1>
<?php } ?>

<form id="form" method="post" action="savetask.php">
    <div class="form-group">
        <label for="title">Description</label>
        <input type="text" class="form-control" name="title" id="title">
    </div>
    <div class="form-group">
        <label for="status">Status</label>
        <select name="status" id="status">
            <option value="open">Open</option>
            <option value="in progress">In Progress</option>
            <option value="done">Done</option>
        </select>
    </div>
    
    <input type="submit" class="btn" value="Submit" />
</form>

<?php
    require_once 'fw/footer.php';
?>