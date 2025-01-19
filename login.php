<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./frontend/css/output.css">
</head>
<body class="background-login">
    <div class="flex justify-center items-center ">
    <div class="w-[400px] h-[500px] bg-white bg-transparent rounded-[10px] p-5 mt-[100px]">
    <div class="text-xl flex justify-center mt-10 font-semibold">
    <h2>Login</h2><br><br>
    </div>

    <?php if (isset($_GET['error'])) { ?>
    <div style="color: red;">
        <p><?php echo htmlspecialchars($_GET['error']); ?></p>
    </div>
<?php } ?>

<div class="text-center mt-[50px]">
<form method="POST" action="http://localhost/SIMPEGDLHP/backend/fungsi/login.php">
    <label for="username">Username:</label><br>
    <input type="text" id="username" name="username" required class="bg-slate-300 w-[300px] h-[30px] rounded-[30px] pl-2.5"><br><br>

    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" required class="bg-slate-300 w-[300px] h-[30px] rounded-[30px]"><br><br>


    <button class="bg-green-600 h-[40px] w-[350px] rounded-[40px] mt-[100px] text-white " type="submit">Login</button>
    
</form>
</div>
    </div>
    </div>
    
    



<!-- Tampilkan pesan error jika ada -->



</body>
</html>
