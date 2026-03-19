<?php
ob_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercicio 02</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 24px;
            background: #f4f4f4;
        }

        .container {
            max-width: 640px;
            margin: 0 auto;
            background: white;
            border: 1px solid #ddd;
            padding: 20px;
        }

        h1 {
            margin: 0 0 10px;
        }

        .info-box {
            background: #f7f7f7;
            border: 1px solid #ddd;
            padding: 12px;
            margin-bottom: 16px;
        }

        .form-group {
            margin-bottom: 12px;
        }

        label {
            display: block;
            margin-bottom: 4px;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }

        textarea {
            min-height: 80px;
        }

        button {
            padding: 8px 14px;
            border: none;
            background: #333;
            color: white;
            cursor: pointer;
        }

        .task-list {
            list-style: none;
            margin: 16px 0 0;
            padding: 0;
        }

        .task-item {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 8px;
        }

        .task-title {
            font-weight: bold;
        }

        .task-desc {
            margin: 6px 0;
        }

        .delete-btn {
            background: #b00020;
        }

        .empty-state {
            padding: 10px;
            background: #f7f7f7;
            border: 1px solid #ddd;
        }

        footer {
            margin-top: 16px;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Exercicio 02</h1>
        <div class="info-box">
            Página simples de tarefas com PHP + MySQL.
        </div>

        <form method="POST" action="">
            <h3>Nova tarefa</h3>
            <div class="form-group">
                <label for="title">Título</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Descrição</label>
                <textarea id="description" name="description"></textarea>
            </div>
            <button type="submit">Salvar</button>
        </form>

        <h3 style="margin-top: 16px;">Tarefas</h3>

        <?php
        $servername = "mysql-lab02";
        $username = "lab02_user";
        $password = "lab02_pass";
        $dbname = "lab02_db";
        
        try {
            $conn = new PDO(
                "mysql:host=$servername;dbname=$dbname",
                $username,
                $password,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );

            $sql_create = "CREATE TABLE IF NOT EXISTS tasks (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                description TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            $conn->exec($sql_create);

            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title'])) {
                $title = $_POST['title'];
                $description = $_POST['description'] ?? '';

                $sql_insert = "INSERT INTO tasks (title, description) VALUES (?, ?)";
                $stmt = $conn->prepare($sql_insert);
                $stmt->execute([$title, $description]);

                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            }

            if (isset($_GET['delete'])) {
                $id = $_GET['delete'];
                $sql_delete = "DELETE FROM tasks WHERE id = ?";
                $stmt = $conn->prepare($sql_delete);
                $stmt->execute([$id]);

                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            }

            $sql_select = "SELECT * FROM tasks ORDER BY created_at DESC";
            $result = $conn->query($sql_select);
            $tasks = $result->fetchAll(PDO::FETCH_ASSOC);

            if (empty($tasks)) {
                echo '<div class="empty-state">
                    <p>Nenhuma tarefa cadastrada.</p>
                </div>';
            } else {
                echo '<ul class="task-list">';
                foreach ($tasks as $task) {
                    echo '<li class="task-item">
                            <div class="task-title">' . htmlspecialchars($task['title']) . '</div>';
                    if (!empty($task['description'])) {
                        echo '<div class="task-desc">' . htmlspecialchars($task['description']) . '</div>';
                    }
                    echo '<small>' . date('d/m/Y H:i', strtotime($task['created_at'])) . '</small><br><br>
                        <a href="?delete=' . $task['id'] . '" onclick="return confirm(\'Tem certeza?\')">
                            <button class="delete-btn">Deletar</button>
                        </a>
                    </li>';
                }
                echo '</ul>';
            }

        } catch(PDOException $e) {
            echo '<div class="info-box">
                <strong>Erro ao conectar no MySQL.</strong><br>
                <small>' . htmlspecialchars($e->getMessage()) . '</small>
            </div>';
        } finally {
            $conn = null;
        }
        ?>

        <footer>
            Exercicio 02 - Checkpoint Docker
        </footer>
    </div>
</body>
</html>
