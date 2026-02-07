<?php require_once __DIR__ . '/core/auth.php';?>
<?php require_once __DIR__ . '/core/session_secure.php';?>


<!DOCTYPE html>
<html>
<head>
  <title>Call Records Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100">
  <?php include __DIR__ . '/navbar.php'; ?>
<h1 class="m-5">Call Records</h1>

<!-- Summary Cards -->
<div class="grid grid-cols-5 gap-4 mb-6">
  <div class="bg-white p-4 rounded shadow">
    <p class="text-sm text-gray-500">Total Calls</p>
    <p class="text-2xl font-bold" id="totalCalls">0</p>
  </div>

  <div class="bg-white p-4 rounded shadow">
    <p class="text-sm text-gray-500">Answered Calls</p>
    <p class="text-2xl font-bold" id="answeredCalls">0</p>
  </div>

  <div class="bg-white p-4 rounded shadow">
    <p class="text-sm text-gray-500">Avg Call Duration</p>
    <p class="text-2xl font-bold" id="avgDuration">0s</p>
  </div>

  <div class="bg-white p-4 rounded shadow">
    <p class="text-sm text-gray-500">Answer Rate</p>
    <p class="text-2xl font-bold" id="answerRate">0%</p>
  </div>
  <div class="bg-white p-4 rounded shadow">
  <p class="text-sm text-gray-500">Total Bill</p>

  <p class="text-2xl font-bold text-red-600" id="totalBill">₹0</p>

  <p class="text-sm text-gray-600 mt-1">
    <span id="totalMinutes">0</span> minutes used
  </p>

</div>
 

</div>

<!-- Search + Actions -->
<div class="flex justify-between mb-4">
 
  <input id="search" placeholder="Search by phone number, call ID or DID..."
    class="border px-3 py-2 rounded w-1/2">
  <div>
    <input type="date" id="dateFilter" class="border rounded px-3 py-2">
    <button onclick="exportCSV()" class="border px-4 py-2 rounded mr-2">Export CSV</button>
  </div>
  
</div>




<!-- Table -->
<div class="bg-white rounded shadow p-4">
  <div class="h-[65vh] overflow-y-auto">
  <table class="w-full text-sm table-fixed">
    <thead class="sticky top-0 bg-white z-20 border-b">
       <tr>
         <th class="text-left p-2 w-[18%]">Date</th>
         <th class="text-left p-2 w-[12%]">Direction</th>
         <th class="text-left p-2 w-[16%]">From</th>
         <th class="text-left p-2 w-[16%]">To</th>
         <th class="text-left p-2 w-[8%]">Duration</th>
         <th class="text-left p-2 w-[10%]">Status</th>
         <th class="text-left p-2 pe-5 w-[20%]">Recording</th>
       </tr>
    </thead>
    <tbody id="tableBody">
      <tr>
        <td colspan="7" class="text-center text-gray-400 p-10">
          No call records found
        </td>
      </tr>
    </tbody>
  </table>
</div>
<div class="flex justify-center mt-4">
  <button
    id="loadMore"
    onclick="loadMore()"
    class="px-6 py-2 border rounded bg-white hover:bg-gray-100"
    style="display:none"
  >
    Load More
  </button>
</div>
</div>

<script src="function.js?v=<?= time() ?>"></script>
</body>
</html>
