<?php
// 1. LOGIC AT THE TOP
session_start();

// Security Check: Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) { 
    header("Location: index.php"); 
    exit(); 
}

/** * GLOBAL MESSAGE STORE: 
 * We use a shared session key to simulate a database. 
 * This includes prepopulated history from established users.
 */
if (!isset($_SESSION['all_feedback'])) {
    $_SESSION['all_feedback'] = [
        ["user" => "Akshay Admin", "msg" => "Welcome to the Support Portal. Please keep reports professional.", "time" => "09:00"],
        ["user" => "John Smith", "msg" => "The coffee machine in HR is leaking again. Can IT look?", "time" => "10:15"],
        ["user" => "Sara Connor", "msg" => "Has anyone seen my red stapler?", "time" => "11:45"],
        ["user" => "System", "msg" => "Maintenance scheduled for midnight tonight.", "time" => "13:00"]
    ];
}

$success_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['message'])) {
    // 1. Process the message
    $new_msg = $_POST['message'];
    $_SESSION['all_feedback'][] = [
        "user" => $_SESSION['username'], 
        "msg" => $new_msg,
        "time" => date('H:i')
    ];
    
    // 2. Set a session variable for the success message instead of a local one
    $_SESSION['success_flash'] = "Your feedback has been posted to the community board!";
    
    // 3. REDIRECT to clear the POST data
    header("Location: feedback.php");
    exit();
}

// Pull the success message from the session if it exists
$success_msg = "";
if (isset($_SESSION['success_flash'])) {
    $success_msg = $_SESSION['success_flash'];
    unset($_SESSION['success_flash']); // Clear it so it only shows once
}

// 2. START THE VISUAL OUTPUT
include('includes/header.php');
?>

<style>
    /* Professional XSS indicator */
    .xss-hit { border: 2px dashed #ffc107 !important; background-color: #fffdf5 !important; position: relative; }
    .xss-hit::before { 
        content: "⚠️ UNSANITIZED INPUT DETECTED"; 
        position: absolute; top: -10px; right: 10px; 
        background: #ffc107; color: #000; font-size: 9px; 
        font-weight: bold; padding: 2px 6px; border-radius: 4px;
        z-index: 10;
    }
</style>

<div class="row animate__animated animate__fadeIn">
    <div class="col-md-4">
        <h2 class="fw-bold mb-3">Support Board</h2>
        <div class="card p-4 shadow-sm border-0">
            <p class="text-muted small">Submit reports or feedback visible to all employees and the <strong>CEO</strong>.</p>
            
            <?php if($success_msg) echo "<div class='alert alert-success py-2 small'>$success_msg</div>"; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold small">Message</label>
                    <textarea name="message" class="form-control" rows="5" placeholder="Report an issue..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100 shadow-sm">Post to Board</button>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <h4 class="fw-bold mb-3">Community Thread</h4>
        <div class="mt-2" style="max-height: 600px; overflow-y: auto; padding-right: 10px;">
            <?php foreach (array_reverse($_SESSION['all_feedback']) as $m): ?>
                <?php 
                    // Enhanced detection for visual feedback
                    $is_injected = preg_match('/<script|<img|<svg|alert|onload|onerror|<details|<iframe/i', $m['msg']);
                ?>
                <div class="card mb-3 border-0 shadow-sm <?php echo $is_injected ? 'xss-hit' : ''; ?>">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-bold text-primary">@<?php echo htmlspecialchars($m['user']); ?></span>
                            <span class="text-muted small"><?php echo $m['time']; ?></span>
                        </div>
                        <div class="p-3 rounded bg-white border">
                            <?php 
                                // VULNERABLE RENDER: This is where the script executes
                                echo $m['msg']; 
                            ?>
                        </div>
                        
                        <?php if ($is_injected): ?>
                            <div class="mt-3 p-2 bg-dark text-warning rounded d-flex align-items-center justify-content-between animate__animated animate__pulse">
                                <span class="small fw-bold">🏁 FLAG{STORED_XSS_ACHIEVED}</span>
                                <span class="badge bg-warning text-dark">INJECTION SUCCESS</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>