<?php
session_start();

// Security Check: Ensure user is logged in
if (!isset($_SESSION['user_id'])) { 
    header("Location: index.php"); 
    exit(); 
}

$fetch_status = "";
$debug_info = "";
$preview_content = "";
$is_image = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['url'])) {
    $url = $_POST['url'];
    
    /** * ALTERNATIVE SSRF VECTOR: file_get_contents()
     * This works even if cURL is disabled. 
     * It also supports wrappers like file:// and php://
     */
    $options = [
        "http" => [
            "header" => "User-Agent: SecureCorp-Avatar-Sync/1.0\r\n",
            "timeout" => 5,
            "ignore_errors" => true // Still get the content even on 404/500
        ]
    ];
    $context = stream_context_create($options);
    
    // Fetch the data
    $raw_response = @file_get_contents($url, false, $context);

    if ($raw_response === false) {
        $fetch_status = "Error: System could not reach the remote resource.";
    } else {
        $fetch_status = "Resource synchronized successfully.";
        
        // Basic Image Detection based on extension for the UI
        $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
        $image_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($ext, $image_exts)) {
            $is_image = true;
            $preview_content = 'data:image/' . $ext . ';base64,' . base64_encode($raw_response);
        } else {
            // Render HTML/Text for SSRF exploration
            $preview_content = $raw_response;
        }
    }

    // SSRF Recognition Logic for Flags
    if (preg_match('/localhost|127\.0\.0\.1|10\.0\.5\./', $url)) {
        $debug_info = "🏁 FLAG{SSRF_FILE_GET_CONTENTS_BYPASS}";
    }
}

include('includes/header.php');
?>

<div class="row justify-content-center animate__animated animate__fadeIn">
    <div class="col-md-5 mb-4">
        <div class="card p-4 text-center border-danger border-2 shadow-sm">
            <h3 class="fw-bold text-danger">Avatar Sync (Legacy)</h3>
            <p class="text-muted small">Using native stream wrappers to sync assets.</p>
            
            <?php if($fetch_status) echo "<div class='alert alert-danger py-2 small'>$fetch_status</div>"; ?>
            
            <form method="POST" class="mt-3">
                <div class="mb-3">
                    <input type="text" name="url" class="form-control border-danger" placeholder="http://images.com/photo.jpg" value="<?php echo htmlspecialchars($url ?? ''); ?>">
                </div>
                <button type="submit" class="btn btn-outline-danger w-100 shadow-sm">Preview Asset</button>
            </form>

            <?php if ($preview_content): ?>
                <div class="mt-4 animate__animated animate__fadeIn">
                    <hr>
                    <button onclick="alert('Success: Request for avatar update has been sent!')" class="btn btn-danger w-100 shadow-sm">
                        Confirm & Update
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-md-7 mb-4">
        <div class="card border-0 shadow-sm h-100 bg-light">
            <div class="card-header bg-dark text-white fw-bold small">Preview & Stream Data</div>
            <div class="card-body">
                <?php if ($debug_info): ?>
                    <div class="bg-dark text-warning p-2 rounded mb-3 text-center animate__animated animate__flash">
                        <small class="fw-bold"><?php echo $debug_info; ?></small>
                    </div>
                <?php endif; ?>

                <div class="preview-window border rounded bg-white overflow-auto" style="min-height: 350px; max-height: 500px;">
                    <?php if ($preview_content): ?>
                        <?php if ($is_image): ?>
                            <div class="text-center p-4">
                                <img src="<?php echo $preview_content; ?>" class="img-fluid rounded border shadow-sm">
                            </div>
                        <?php else: ?>
                            <div class="p-3 font-monospace small text-dark">
                                <?php echo htmlspecialchars($preview_content); // Using htmlspecialchars here for safer lab display ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-broadcast text-muted display-1"></i>
                            <p class="text-muted mt-2">Ready to stream remote data...</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>