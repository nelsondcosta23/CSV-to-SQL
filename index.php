<?php
//created by Nelson Costa @ Soc.Com.C.Santos SA
//https://www.linkedin.com/in/nelsondcosta/
//https://github.com/nelsondcosta23

include'configsys.php'; 
$conn = new mysqli($servername, $username, $password, $dbname);

if (isset($_POST["import"])) {
    
    $fileName = $_FILES["file"]["tmp_name"];
    
    if ($_FILES["file"]["size"] > 0) {
        
        $file = fopen($fileName, "r");
        
        while (($column = fgetcsv($file, 10000, ";")) !== FALSE) {
            //You can change the ";" to "," I recommend ";" because is easy to work with Excel, later export to CSV-MSDOS
            //"," normal .CSV files
            //"," Excel CSV-MSDOS files, better for work
            $sqlInsert = "INSERT into users (user_id,username,password,firstname,lastname)
                   values ('" . $column[0] . "','" . $column[1] . "','" . $column[2] . "','" . $column[3] . "','" . $column[4] . "')";
            $result = mysqli_query($conn, $sqlInsert);
            
            if (! empty($result)) {
                $type = "success";
                $message = " &#128512; CSV File Successfully Imported!";
            } else {
                $type = "error";
                $message = " &#x1F615; There was a problem Importing!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<!--
//created by Nelson Costa @ Soc.Com.C.Santos SA
//https://www.linkedin.com/in/nelsondcosta/
//https://github.com/nelsondcosta23
-->
<head>
<meta charset="UTF-8">
<script src="jquery-3.2.1.min.js"></script>
<link rel="stylesheet" href="stylish.css">

<script type="text/javascript">
$(document).ready(function() {
    $("#frmCSVImport").on("submit", function () {

	    $("#response").attr("class", "");
        $("#response").html("");
        var fileType = ".csv";
        var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + fileType + ")$");
        if (!regex.test($("#file").val().toLowerCase())) {
        	    $("#response").addClass("error");
        	    $("#response").addClass("display-block");
            $("#response").html("Invalid File. Upload : <b>" + fileType + "</b> Files.");
            return false;
        }
        return true;
    });
});
</script>
</head>

<body>
    <h2>Import CSV to SQL</h2>
    
    <div id="response" class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>"><?php if(!empty($message)) { echo $message; } ?></div>
    <div class="outer-scontainer">
        <div class="row">

            <form class="form-horizontal" action="" method="post"
                name="frmCSVImport" id="frmCSVImport" enctype="multipart/form-data">
                <div class="input-row">
                    <label class="col-md-4 control-label">Chosse .CSV File</label>
					<input type="file" name="file" id="file" accept=".csv"><br>
                    <button type="submit" id="submit" name="import" class="btn-submit">Import</button>
                    <br />
                </div>
            </form>

        </div>
            <?php
            $sqlSelect = "SELECT * FROM users ORDER BY user_id DESC LIMIT 50";
            $result = mysqli_query($conn, $sqlSelect);
            
            if (mysqli_num_rows($result) > 0) {
                ?>
            <table id='userTable'>
            <thead>
                <tr>
					<th>User ID</th>
                    <th>UserName</th>
                    <th>PassWord</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                </tr>
            </thead>

            <?php 
                while ($row = mysqli_fetch_array($result)) {
                    ?> 
                <tbody>
                <tr>
					<td><?php  echo $row['user_id']; ?></td>
                    <td><?php  echo $row['username']; ?></td>
                    <td><?php  echo $row['password']; ?></td>
                    <td><?php  echo $row['firstname']; ?></td>
                    <td><?php  echo $row['lastname']; ?></td>
                </tr>
                    <?php
                }
                ?>
                </tbody>
        </table>
        <?php } ?>
    </div>
</body>
</html>