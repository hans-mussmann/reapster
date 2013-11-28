<?php 
//////////////////////////////////////////////////////////
// Create a CSV File (save as: writeGoogleSpreadSheet.php) 
//////////////////////////////////////////////////////////

header("Content-Type: text/plain");

// ToDo: Need to connect to a db so add connection details.
$conn = mysqli_connect(  
  $host, 
  $user, 
  $password, 
  $database,
  $port
);
$query = "
  select 
    firstname, 
    lastname, 
    telephone 
  from 
    contacts 
  limit 10;
";
// Query Data
$result = mysqli_query($query);
while($row = mysqli_fetch_assoc($result)){
  $rows[] = $row;
}
// Headings
echo implode(",\t", array_keys($rows[0])) . ",\n";
// Rows
foreach($rows as $row){
  echo implode(",\t", array_values($row)) . ",\n";
}
// Close Connection
mysqli_close($conn);
?>
