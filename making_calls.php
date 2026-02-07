<?php
require 'core/csrf.php';
require 'core/auth.php'; 

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $isBulk = isset($_POST['bulk']);

    // ================== CSRF COMMON ==================
    if(!csrf_check($_POST['token'] ?? '')){
        die("Invalid CSRF Token");
    }

    // =================================================
    // SINGLE CALL
    // =================================================
    if (!$isBulk) {

        $name  = trim($_POST['name'] ?? '');
        $phone = preg_replace('/[^0-9]/', '', $_POST['phone'] ?? '');
        $date  = trim($_POST['date'] ?? '');

        if(empty($name) || empty($phone) || empty($date)){
            $message = "Name, phone and date are required";
        }
        else if (strlen($phone) < 10) {
          $message = "Phone number must be at least 10 digits";
        }
        else {

            $data = http_build_query([
                'phone' => $phone,
                'name'  => $name,
                'date'  => $date
            ]);

            try {
                @file_get_contents(
                    "http://localhost:5005/call",
                    false,
                    stream_context_create([
                        'http'=>[
                            'method'=>'POST',
                            'header'=>'Content-type: application/x-www-form-urlencoded',
                            'content'=>$data,
                            'timeout' => 5
                        ]
                    ])
                );

                $message = "✅ Call Initiated to $name ($phone)";

            } catch(Exception $e) {
                $message = "⚠ Bridge service not running";
            }
        }
    }

    // =================================================
    // BULK CALL
    // =================================================
    else {

        if(empty($_FILES['file']['tmp_name'])){
            $message = "Please upload file";
        }
        else {

            $file = $_FILES['file']['tmp_name'];
            $ext  = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

            $rows = [];

            if($ext == "csv"){
                $rows = array_map('str_getcsv', file($file));
            }
            else if($ext == "xlsx" || $ext == "xls"){

                require 'vendor/autoload.php';

                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
                $sheet = $spreadsheet->getActiveSheet();

                foreach ($sheet->getRowIterator() as $row) {
                    $cells = [];
                    foreach ($row->getCellIterator() as $cell) {
                        $cells[] = $cell->getValue();
                    }
                    $rows[] = $cells;
                }
            }
            else{
                $message = "❌ Only CSV or Excel allowed";
            }

            if(!empty($rows)){

                $header = array_map('strtolower', $rows[0]);

                $nameIndex  = array_search("name", $header);
                $phoneIndex = array_search("phone", $header);
                $dateIndex = array_search("date", $header);
                

                if($dateIndex === false) $dateIndex = 2;
                if($nameIndex === false)  $nameIndex = 0;
                if($phoneIndex === false) $phoneIndex = 1;

                unset($rows[0]);

                foreach($rows as $row){

                    $name  = trim($row[$nameIndex] ?? '');
                    $phone = preg_replace('/[^0-9]/', '', $row[$phoneIndex] ?? '');
                    $date  = trim($row[$dateIndex] ?? '');

                    if(empty($name) || empty($phone) || empty($date)) continue;
                    if(strlen($phone) < 10) continue;

                    $data = http_build_query([
                        'phone' => $phone,
                        'name'  => $name,
                        'date'  => $date
                    ]);

                    try{
                        @file_get_contents(
                            "http://localhost:5005/call",
                            false,
                            stream_context_create([
                                'http'=>[
                                    'method'=>'POST',
                                    'header'=>'Content-type: application/x-www-form-urlencoded',
                                    'content'=>$data,
                                    'timeout' => 5
                                ]
                            ])
                        );
                    }
                    catch(Exception $e){
                        $message = "⚠ Bridge service not running";
                    }

                    sleep(2);
                }

                $message = "🚀 Bulk Calling Started";
            }
        }
    }
}
?>




<!DOCTYPE html>
<html>
<head>
<title>AI Call Dashboard</title>

<script src="https://cdn.tailwindcss.com"></script>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>

<body class="bg-gray-100">

<?php include 'navbar.php' ?>

<!-- MAIN WRAPPER -->
<div class="
  w-full
  max-w-[1100px]
  mx-auto
  px-3 sm:px-4 md:px-6
  py-3 sm:py-6
">

<h1 class="
  text-lg sm:text-xl md:text-2xl
  font-semibold
  mb-3 sm:mb-4
">
📞 AI Calling Dashboard
</h1>

<?php if($message != ""): ?>
<div class="
  bg-green-50 text-green-800
  p-2 sm:p-3
  rounded
  mb-3 sm:mb-4
  text-sm sm:text-base
">
  <?php echo $message; ?>
</div>
<?php endif; ?>


<!-- ===== SINGLE CALL CARD ===== -->
<div class="
  bg-white
  rounded-lg
  shadow
  p-3 sm:p-4 md:p-6
  mb-3 sm:mb-4
">

<h3 class="font-medium mb-2 sm:mb-3">
Single Call
</h3>

<form method="POST">
<input type="hidden" name="token" value="<?= csrf_token() ?>">
<div class="
  grid
  grid-cols-1
  sm:grid-cols-2
  md:grid-cols-3
  gap-2 sm:gap-3
  items-end
">

<div>
<label class="text-xs sm:text-sm text-gray-600">
Name
</label>

<input
  name="name"
  required
  class="
    border rounded
    w-full
    p-2 sm:p-2.5
    mt-1
    text-sm sm:text-base
  "
  placeholder="Customer name">
</div>
<div>
  <label class="text-xs sm:text-sm text-gray-600">
    Operation Date
  </label>

  <input
    type="date"
    name="date"
    required
    class="
      border rounded
      w-full
      p-2 sm:p-2.5
      mt-1
      text-sm sm:text-base
    ">
</div>

<div>
<label class="text-xs sm:text-sm text-gray-600">
Phone
</label>

<input
  name="phone"
  required
  class="
    border rounded
    w-full
    p-2 sm:p-2.5
    mt-1
    text-sm sm:text-base
  "
  placeholder="98765xxxxx">
</div>

<div>
<button class="
  w-full
  bg-blue-600 hover:bg-blue-700
  text-white
  rounded
  p-2 sm:p-2.5
  w-full
  text-sm sm:text-base
" onclick="this.disabled=true;this.form.submit();">
  Call Now
</button>
</div>

</div>
</form>
</div>



<!-- ===== BULK CALL CARD ===== -->
<div class="
  bg-white
  rounded-lg
  shadow
  p-3 sm:p-4 md:p-6
">

<h3 class="font-medium mb-2 sm:mb-3">
Bulk Calling
</h3>

<form method="POST" enctype="multipart/form-data">
<input type="hidden" name="token" value="<?= csrf_token() ?>">
<div class="
  border-2 border-dashed
  rounded
  p-3 sm:p-4
  text-center
  text-xs sm:text-sm
  text-gray-600
">
Upload CSV with columns:
<b>name, phone, date</b>
</div>

<div class="mt-2 sm:mt-3">
<input
  type="file"
  name="file"
  accept=".csv"
  required
  class="
    w-full
    text-sm sm:text-base
  ">
  <input type="hidden" name="bulk" value="1">
</div>

<button
  class="
  mt-2 sm:mt-3
  bg-green-600 hover:bg-green-700
  text-white
  rounded
  p-2 sm:p-2.5
  w-full sm:w-auto
  text-sm sm:text-base
"
  name="Submit" onclick="this.disabled=true;this.form.submit();">
Start Bulk Calling
</button>

</form>

</div>

</div>
</body>
</html>
