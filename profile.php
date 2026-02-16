<?php
// 1. ALL LOGIC AT THE TOP
session_start();

// Security Check: Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) { 
    header("Location: index.php"); 
    exit(); 
}

/** * MOCK DATABASE: Complex String IDs (SIDs)
 * These must match exactly what you put in your search.php leaks.
 */
$profiles = [
    'SID10001' => ['name' => 'Akshay Admin', 'role' => 'CEO', 'salary' => '$500,000', 'flag' => 'FLAG{IDOR_SENSITIVE_LEAK}'],
    'SID10294' => ['name' => 'Abhishek Researcher', 'role' => 'Security Manager', 'salary' => '$90,000', 'flag' => ''],
    'SID10582' => ['name' => 'John Smith', 'role' => 'HR', 'salary' => '$50,000', 'flag' => '' ],
    'SID12353' => ['name' => 'Sara Connor', 'role' => 'Engineer', 'salary' => '$120,000', 'flag' => '']
];

// FIX: Remove (int) cast. We need the raw string (e.g., "SID10001").
// Default to Abhishek's SID if no ID is provided.
$id = isset($_GET['id']) ? $_GET['id'] : 'SID10294';
$data = $profiles[$id] ?? null;

// 2. START THE VISUAL OUTPUT
include('includes/header.php');
?>

<div class="row justify-content-center animate__animated animate__zoomIn">
    <div class="col-md-6">
        <?php if ($data): ?>
            <div class="card overflow-hidden shadow border-0">
                <div class="bg-primary p-5 text-center text-white">
                    <div class="mb-3">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($data['name']); ?>&background=random&size=100" class="rounded-circle border border-3 border-white shadow-sm" alt="Profile">
                    </div>
                    <h2 class="fw-bold"><?php echo $data['name']; ?></h2>
                    <span class="opacity-75"><?php echo $data['role']; ?></span>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Payroll Information</h5>
                        <span class="badge bg-light text-dark border font-monospace"><?php echo htmlspecialchars($id); ?></span>
                    </div>
                    
                    <p class="text-danger fs-4 fw-bold mb-4">Current Salary: <?php echo $data['salary']; ?></p>
                    
                    <hr>
                    
                    <h5 class="fw-bold text-muted small text-uppercase">Internal Security Notes</h5>
                    <div class="p-3 bg-light rounded border">
                        <p class="mb-0"><?php echo !empty($data['flag']) ? $data['flag'] : 'No sensitive notes for this personnel record.'; ?></p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-danger shadow-sm border-0 text-center animate__animated animate__shakeX">
                <h4 class="fw-bold">Reference Error</h4>
                <p>Employee ID <code><?php echo htmlspecialchars($id); ?></code> was not found in the payroll system.</p>
                <a href="dashboard.php" class="btn btn-danger btn-sm mt-2">Back to Dashboard</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>