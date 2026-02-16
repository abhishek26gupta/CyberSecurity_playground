<?php
session_start();
if (!isset($_SESSION['user_id'])) { 
    header("Location: index.php"); 
    exit(); 
}

$employees = [
    ['name' => 'Abhishek Researcher', 'dept' => 'Cybersecurity', 'EmpID' => 'SID10294'],
    ['name' => 'John Smith', 'dept' => 'Human Resources', 'EmpID' => 'SID10582'],
    ['name' => 'Sara Connor', 'dept' => 'Engineering', 'EmpID' => 'SID12353'],
    ['name' => 'Akshay Admin', 'dept' => 'CEO', 'EmpID' => 'SID10001']
];

$query = $_GET['q'] ?? '';
$results = [];

if ($query) {
    foreach ($employees as $e) {
        if (stripos($e['name'], $query) !== false) {
            $results[] = $e;
        }
    }
}

include('includes/header.php');
?>

<div class="animate__animated animate__fadeIn">
    <h2 class="fw-bold mb-4">Employee Directory</h2>
    
    <div class="card p-4 mb-4 shadow-sm border-0">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="q" class="form-control" placeholder="Search by name..." value="<?php echo htmlspecialchars($query); ?>">
            <button type="submit" class="btn btn-primary px-4">Search</button>
        </form>
    </div>

    <div class="row">
        <?php if (empty($results) && $query): ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">No records found matching your search.</p>
            </div>
        <?php else: ?>
            <?php foreach ($results as $r): ?>
            <div class="col-md-4 mb-3">
                <div class="card p-3 border-start border-primary border-4 shadow-sm h-100">
                    <h5 class="fw-bold mb-1"><?php echo $r['name']; ?></h5>
                    <p class="text-muted small mb-0"><?php echo $r['dept']; ?></p>
                    <div class="mt-2 text-primary fw-bold small">EmpID: <?php echo $r['EmpID']; ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>