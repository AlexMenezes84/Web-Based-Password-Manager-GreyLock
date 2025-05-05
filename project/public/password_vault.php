<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Password Vault - Grey Lock</title>
    <link rel="stylesheet" type="text/css" href="assets/css/vault.css">
</head>
<body>
    <header>
        <h1>Password Vault</h1>
        <nav>
            <a href="/sites/GreyLock/project/public/">Home</a>
            <a href="/sites/GreyLock/project/public/logout">Logout</a>
        </nav>
    </header>
    <main>
        <h2>Saved Passwords</h2>
        <table>
            <thead>
                <tr>
                    <th>Service Name</th>
                    <th>Username</th>
                    <th>Password</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($passwords as $password): ?>
                    <tr>
                        <td><?= htmlspecialchars($password['service_name']) ?></td>
                        <td><?= htmlspecialchars($password['service_username']) ?></td>
                        <td>
                            <input type="password" value="<?= htmlspecialchars($password['encrypted_password']) ?>" readonly>
                            <button onclick="togglePassword(this)">Show</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <script>
        function togglePassword(button) {
            const input = button.previousElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                button.textContent = 'Hide';
            } else {
                input.type = 'password';
                button.textContent = 'Show';
            }
        }
    </script>
</body>
</html>