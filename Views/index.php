<?php 
    $start = microtime(true);
    require_once("../Root.php");
    Root::UseBLL("PersonalBLL");

    $objectBLL = new PersonalBLL();

    //Calling custom method
    //$list = $objectBLL->selectTop(50,"rod");
    
    //GetAll example
    $list = $objectBLL->_getAll();
    
    //Update example
    // $obj = new Personal();
    // $obj->id_Persona = 294;
    // $obj->nombre = "Test_" . rand(100, 200);
    // $obj->nombre_2 = "Test_" . rand(100, 200);
    // $obj->apellido_pat = "Test_" . rand(100, 200);
    // $obj->apellido_mat = "Test_" . rand(100, 200);
    // $obj->tipo_persona = 2;
    // $obj->activo = true;
    // $affected_rows = $objectBLL->_update($obj);

    //Insert example
    // $obj = new Personal();
    // $obj->nombre = "Test_" . rand(100, 200);
    // $obj->nombre_2 = "Test_" . rand(100, 200);
    // $obj->apellido_pat = "Test_" . rand(100, 200);
    // $obj->apellido_mat = "Test_" . rand(100, 200);
    // $obj->tipo_persona = 2;
    // $obj->activo = true;
    // $inserted_id = $objectBLL->_insert($obj);

    //GetById example
    //$res_obj = $objectBLL->_getById(297);

    //Delete example
    //$affected_rows = $objectBLL->_delete(298);
    $time_elapsed_secs = microtime(true) - $start;
?>
 <!DOCTYPE html>
 <html lang="en">
 <head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="../Resources/Bootstrap/css/bootstrap.min.css">
     <title>Document</title>
 </head>
 <body>
     <div class="row">
         <div class="col-lg-4"></div>
         <div class="col-lg-4">
             <input id="nmbId" type="number">
             <button onclick="deleteRow()">Delete</button>
         </div>
         <div class="col-lg-4">
             <span id="result"></span>
         </div>
     </div>
    <div class="row text-center">
        <div class="col-lg-12">
            <pre>
                <?php 
                    echo count($list); 
                    //echo var_dump($affected_rows); 
                    //echo var_dump($inserted_id); 
                    //echo var_dump($res_obj);
                ?>
            </pre>
            <code>
                <?php
                    echo "Execution time: " . $time_elapsed_secs . "s";
                ?>
            </code>
        </div>
    </div>
 </body>
 </html>
 <!-- jQuery 3 -->
 <script src="..\Resources\jquery\jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../Resources/Bootstrap/js/bootstrap.min.js"></script>
 <script>
     function deleteRow() {
         var id = $("#nmbId").val();
        $.ajax({
            url: "../BLL/PersonalBLL.php",
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'Delete',
                id:id
            }
        })
        .done(function(data) {
            console.log(data);
            if(data.error){
                alert(data.message);
            }else{
                $("#result").text(data);
            }
        })
        .fail(function(error) {
            alert(error.responseText);
        })
        .always(function() {
        });
    }
 </script>