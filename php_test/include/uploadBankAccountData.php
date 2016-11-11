<pre>
<?php
$target_dir = getcwd() . "/../uploads/source_files/";

function filter_rows(&$rows,$num_cols) {
    $num_row = 0;
    for ($num_row = 0; $num_row<count($rows); $num_row++) {
        if (count($rows[$num_row]) != $num_cols) {
            echo "Removing row " . $num_row . PHP_EOL;
            array_splice($rows,$num_row,1);
            $num_row--;
        }
    }
}
function validate_upload($uploaded_file) {
    $uploadOk = TRUE;
    $file_type = pathinfo($uploaded_file["name"],PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        echo $file_type;    
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }
    // Check file size
    if ($uploaded_file["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if( $file_type != "csv" ) {
        echo "Sorry, only .csv files are allowed.";
        $uploadOk = 0;
    }
    return $uploadOk;
}

if (!validate_upload($_FILES["uploadedFile"])) {
    echo "Sorry, your file was not uploaded.";
    exit;
}
$target_file = $target_dir . basename($_FILES["uploadedFile"]["name"]);
if (move_uploaded_file($_FILES["uploadedFile"]["tmp_name"], $target_file)) {
    echo "The file ". basename($_FILES["uploadedFile"]["name"]). " has been uploaded." . PHP_EOL;
} else {
    echo "Sorry, there was an error uploading your file.";
    exit;
}

$file = fopen($target_file, r);
if (!$file) {
    echo "Could not open the target file.";
    exit;
}

$delimiter = ",";
$enclosure = '"';
$escape    = "\\";
$rows      = [];;
while ($line = fgets($file)) {
    $fields = str_getcsv($line,$delimiter,$enclosure,$escape);
    array_push($rows,$fields);
}
$num_cols = count($rows[count($rows)-1]);
echo $num_cols;

filter_rows($rows, $num_cols);

$rows_json = [
    'data' => []
];
foreach ($rows as $row) {
    $row_json = [];
    foreach ($row as $col) {
        array_push($row_json, $col);
    }
    array_push($rows_json['data'],$row_json);
}

echo json_encode($rows_json,JSON_PRETTY_PRINT);
?>
</pre>