<?php 
include('../includes/admheader.php'); 
require_once '../auth/auth_admin.php';

$con = mysqli_connect('localhost', 'root', '', 'florir');
if (!$con) {
    die("Erro na conexão: " . mysqli_connect_error());
}

// Verificar se o ID foi passado
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: list_produtos.php');
    exit;
}

$id_produto = intval($_GET['id']);

// Buscar dados do produto
$sql_produto = "SELECT * FROM produto WHERE id_produto = $id_produto";
$result_produto = mysqli_query($con, $sql_produto);

if (!$result_produto || mysqli_num_rows($result_produto) == 0) {
    header('Location: list_produtos.php');
    exit;
}

$produto = mysqli_fetch_assoc($result_produto);

// Configurar diretório de upload
$uploadDir = __DIR__ . "/uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$mensagem_sucesso = '';
$mensagem_erro = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_produto = mysqli_real_escape_string($con, $_POST['nome_produto']);
    $descricao = mysqli_real_escape_string($con, $_POST['descricao']);
    $preco = floatval($_POST['preco']);
    $estoque = intval($_POST['estoque']);
    $id_categoria = intval($_POST['id_categoria']);

    $imagem_url = $produto['imagem_url']; // Manter imagem atual
    
    // Upload de nova imagem se fornecida
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($ext, $extensoesPermitidas)) {
            $novoNome = uniqid("prod_") . "." . $ext;
            $destino = $uploadDir . $novoNome;

            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
                // Remover imagem antiga se existir
                if ($produto['imagem_url'] && file_exists(__DIR__ . '/' . $produto['imagem_url'])) {
                    unlink(__DIR__ . '/' . $produto['imagem_url']);
                }
                $imagem_url = "uploads/" . $novoNome;
            } else {
                $mensagem_erro = "Erro ao fazer upload da imagem.";
            }
        } else {
            $mensagem_erro = "Formato de imagem não suportado. Use JPG, PNG ou GIF.";
        }
    }

    if (!$mensagem_erro) {
        $sql = "UPDATE produto SET 
                nome_produto = '$nome_produto',
                descricao = '$descricao',
                preco = $preco,
                estoque = $estoque,
                imagem_url = '$imagem_url',
                id_categoria = $id_categoria
                WHERE id_produto = $id_produto";

        if (mysqli_query($con, $sql)) {
            $mensagem_sucesso = "✅ Produto atualizado com sucesso!";
            // Atualizar dados do produto
            $produto = array_merge($produto, [
                'nome_produto' => $nome_produto,
                'descricao' => $descricao,
                'preco' => $preco,
                'estoque' => $estoque,
                'imagem_url' => $imagem_url,
                'id_categoria' => $id_categoria
            ]);
        } else {
            $mensagem_erro = "❌ Erro ao atualizar o produto: " . mysqli_error($con);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto - Admin | Florir Aromas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/styleadm.css">
</head>
<body>

<main>
    <div class="form-container">
        <h1 class="form-title">✏️ Editar Produto</h1>

        <?php if($mensagem_sucesso): ?>
            <div class="message message-success"><?php echo $mensagem_sucesso; ?></div>
        <?php elseif($mensagem_erro): ?>
            <div class="message message-error"><?php echo $mensagem_erro; ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">Nome do Produto *</label>
                <input type="text" class="form-input" name="nome_produto" 
                       value="<?php echo htmlspecialchars($produto['nome_produto']); ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Descrição</label>
                <textarea class="form-textarea" name="descricao" rows="4"><?php echo htmlspecialchars($produto['descricao']); ?></textarea>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Preço *</label>
                    <input type="number" class="form-input" name="preco" 
                           value="<?php echo $produto['preco']; ?>" step="0.01" min="0" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Estoque *</label>
                    <input type="number" class="form-input" name="estoque" 
                           value="<?php echo $produto['estoque']; ?>" min="0" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Categoria *</label>
                <select class="form-select" name="id_categoria" required>
                    <?php
                    $result = mysqli_query($con, "SELECT id_categoria, nome_categoria FROM categoria ORDER BY nome_categoria");
                    while ($row = mysqli_fetch_assoc($result)) {
                        $selected = $produto['id_categoria'] == $row['id_categoria'] ? 'selected' : '';
                        echo "<option value='{$row['id_categoria']}' $selected>{$row['nome_categoria']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Imagem do Produto</label>
                
                <?php if($produto['imagem_url']): ?>
                    <div class="image-preview">
                        <img src="../produtos/<?php echo $produto['imagem_url']; ?>" 
                             alt="<?php echo htmlspecialchars($produto['nome_produto']); ?>">
                        <p style="color: var(--text-light); font-size: 0.8rem; margin-top: 0.5rem;">
                            Imagem atual
                        </p>
                    </div>
                <?php endif; ?>
                
                <input type="file" class="form-file" name="imagem" accept="image/*">
                <small style="color: var(--text-light); font-size: 0.8rem;">
                    Deixe em branco para manter a imagem atual
                </small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    💾 Atualizar Produto
                </button>
                <a href="list_produtos.php" class="btn-cancel">
                    ← Voltar à Lista
                </a>
            </div>
        </form>
    </div>
</main>

</body>
</html>