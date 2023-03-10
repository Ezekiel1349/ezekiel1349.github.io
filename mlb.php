<!DOCTYPE html>
<html lang="en">

<head>
<?php
// $version =  date('is'); //Dev
$version =  '2.0.0'; //pro
?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body>


<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="teams d-flex flex-wrap row-cols-4"></div>
            </div>
        </div>
    </div>
</section>


    <!-- <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
 -->
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/mlb.js?v=<?php $version?>"></script>
</body>

</html>