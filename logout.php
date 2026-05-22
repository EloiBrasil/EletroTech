
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>logout</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

     <style>
        a{
            display: block;
            padding: 10px 20px;
            background-color: #ffd500;
            color: #000;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            margin: auto;
            text-align: center;
        }

     </style>
</head>
<body>
    <?php
    require __DIR__ . "/Biblioteca-PHP/FunctionsGPM/Auth/FunctionsSession.php";

    logout();
    echo "<a href='../EletroTech/Views/view-login/loginView.php'>Ir para a página de login</a>"
   
?>

</body>
</html>
