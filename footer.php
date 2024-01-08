<footer>
     <h4>Admin login:</h4>
     <form action="index.php" method="post" class="footerForm">
          <input type="text" name="username" placeholder="username">
          <input type="password" name="password" placeholder="API key">
          <button type="submit">Login</button>
     </form>
</footer>
</body>

</html>

<?php

$username = htmlspecialchars($_POST['username']);

$user = [
     'username' => 'hugo',
     'password' => 'b9a6417f-4df0-4b5f-8b19-319b67fe8d43'
];

if (isset($_POST['username']) && isset($_POST['password'])) {
     $username = $_POST['username'];
     $password = $_POST['password'];
     if ($_POST['username'] === $user['username'] && $_POST['password'] === $user['password']) {
          header('Location: admin.php');
          exit;
     } else {
          echo 'Wrong username or password';
     }
}

?>