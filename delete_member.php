 <?php
include 'process_admin.php'; 
if (isset($_GET['memberId'])) {
    $memberId = $_GET['memberId'];
    deleteMember($memberId);
    echo "Memeber deleted successfully";
    //header('Location: admin_panel.php');
} else {
    echo "Memeber ID not provided";
}
?> 
