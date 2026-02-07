<?php
require_once __DIR__ . "/core/db.php";

/* =========================
   VIEW SINGLE COMPLAINT
   ========================= */
if (isset($_GET['id'])) {

    $stmt = $pdo->prepare("SELECT * FROM call_analysis WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $complaint = $stmt->fetch();

    if (!$complaint) {
        die("Complaint not found");
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Complaint Details</title>
        <style>
            body { font-family: Arial; background:#f5f5f5; padding:20px; }
            .box { background:#fff; padding:20px; margin-bottom:15px; border-radius:5px; }
            pre { white-space:pre-wrap; background:#eee; padding:10px; }
            a { text-decoration:none; color:#0066cc; }
        </style>
    </head>
    <body>

    <a href="complaints.php">⬅ Back to complaints</a>

    <div class="box">
        <h3>Problem</h3>
        <p><?= htmlspecialchars($complaint['problem'] ?? '—') ?></p>
    </div>

    <div class="box">
        <h3>Summary</h3>
        <p><?= htmlspecialchars($complaint['summary'] ?? '—') ?></p>
    </div>

    <div class="box">
        <h3>Transcript</h3>
        <pre><?= htmlspecialchars($complaint['transcript'] ?? '—') ?></pre>
    </div>

    <div class="box">
        <strong>City:</strong> <?= htmlspecialchars($complaint['city'] ?? '—') ?><br>
        <strong>Status:</strong> <?= htmlspecialchars($complaint['status']) ?><br>
        <strong>Received At:</strong> <?= htmlspecialchars($complaint['created_at']) ?>
    </div>

    </body>
    </html>
    <?php
    exit;
}

/* =========================
   LIST ALL COMPLAINTS
   ========================= */
$stmt = $pdo->query("
    SELECT id,name, problem, city, status,complaint_no, created_at
    FROM call_analysis
    ORDER BY id DESC
");
$complaints = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Call Complaints</title>
    <style>
        body { font-family: Arial; background:#f5f5f5; }
        table {
            width:95%;
            margin:20px auto;
            border-collapse:collapse;
            background:#fff;
        }
        th, td {
            padding:10px;
            border:1px solid #ddd;
            text-align:left;
        }
        th { background:#333; color:#fff; }
        .NEW { color:green; font-weight:bold; }
        .FAILED { color:red; font-weight:bold; }
        a { text-decoration:none; color:#0066cc; }
    </style>
</head>
<body>

<h2 style="text-align:center">📞 Call Complaints</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Problem</th>
        <th>City</th>
        <th>Status</th>
        <th>Complaint No.</th>
        <th>Received At</th>
        <th>Action</th>
    </tr>

    <?php if (empty($complaints)): ?>
        <tr>
            <td colspan="6" style="text-align:center">No complaints found</td>
        </tr>
    <?php endif; ?>

    <?php foreach ($complaints as $row): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name'] ?? '—') ?></td>
            <td><?= htmlspecialchars($row['problem'] ?? '—') ?></td>
            <td><?= htmlspecialchars($row['city'] ?? '—') ?></td>
            <td class="<?= $row['status'] ?>"><?= $row['status'] ?></td>
            <td><?= htmlspecialchars($row['complaint_no'] ?? '—') ?></td>
            <td><?= $row['created_at'] ?></td>
            <td>
                <a href="complaints.php?id=<?= $row['id'] ?>">View</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
