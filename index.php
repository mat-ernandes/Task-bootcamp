<?php
require_once __DIR__ . '/app/models/Usuario.php';
require_once __DIR__ . '/app/models/Tarefa.php';

$usuarioModel = new Usuario();
$usuarios = $usuarioModel->listar();

$tarefaModel = new Tarefa();
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

$modoEdicao = false;
$tarefaEmEdicao = null;
$erros = [];

$formData = [
    'title' => '',
    'description' => '',
    'responsible' => '',
    'priority' => '',
    'deadline' => '',
    'status' => 'Aguardando'
];

function getStatusClass(string $status): string
{
    return match ($status) {
        'Aguardando' => 'status-todo',
        'Em Andamento' => 'status-doing',
        'Concluído' => 'status-done',
        default => 'status-todo'
    };
}

function getPriorityClass(string $prioridade): string
{
    return match ($prioridade) {
        'Alta' => 'priority-high',
        'Média' => 'priority-medium',
        'Baixa' => 'priority-low',
        default => 'priority-low'
    };
}

function renderTaskCard(array $tarefa): string
{
    ob_start();
    ?>
    <article 
        class="task-card" 
        data-task-id="<?= $tarefa['id'] ?>"
        data-status="<?= htmlspecialchars($tarefa['status']) ?>"
        data-priority="<?= htmlspecialchars($tarefa['prioridade']) ?>"
        data-responsible="<?= $tarefa['usuario_id'] ?>"
    >
        <div class="task-view">
            <div class="task-card-header">
                <h3 class="task-title"><?= htmlspecialchars($tarefa['titulo']) ?></h3>

                <div class="task-tags">
                    <span class="tag <?= getStatusClass($tarefa['status']) ?>">
                        <?= htmlspecialchars($tarefa['status']) ?>
                    </span>

                    <span class="tag <?= getPriorityClass($tarefa['prioridade']) ?>">
                        <?= htmlspecialchars($tarefa['prioridade']) ?>
                    </span>
                </div>
            </div>

            <p class="task-description">
                <?= !empty($tarefa['descricao']) ? htmlspecialchars($tarefa['descricao']) : 'Sem descrição informada.' ?>
            </p>

            <div class="task-meta">
                <span><i class="fa-regular fa-user"></i> <?= htmlspecialchars($tarefa['responsavel']) ?></span>
                <span><i class="fa-regular fa-calendar"></i> <?= date('d/m/Y', strtotime($tarefa['prazo'])) ?></span>
            </div>

            <div class="task-card-footer">
                <div></div>

                <div class="task-actions">
                    <a href="index.php?edit=<?= $tarefa['id'] ?>" class="icon-button edit" aria-label="Editar tarefa">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>

                    <form method="POST" action="">
                        <input type="hidden" name="delete_id" value="<?= $tarefa['id'] ?>">
                        <button type="submit" class="icon-button delete" aria-label="Excluir tarefa">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </article>
    <?php
    return ob_get_clean();
}

if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $modoEdicao = true;
    $tarefaEmEdicao = $tarefaModel->buscarPorId((int) $_GET['edit']);

    if ($tarefaEmEdicao) {
        $formData = [
            'title' => $tarefaEmEdicao['titulo'],
            'description' => $tarefaEmEdicao['descricao'],
            'responsible' => $tarefaEmEdicao['usuario_id'],
            'priority' => $tarefaEmEdicao['prioridade'],
            'deadline' => $tarefaEmEdicao['prazo'],
            'status' => $tarefaEmEdicao['status']
        ];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['user_name']) || isset($_POST['user_email'])) {
        header('Content-Type: application/json; charset=utf-8');

        $nome = trim($_POST['user_name'] ?? '');
        $email = strtolower(trim($_POST['user_email'] ?? ''));

        if ($nome === '' || $email === '') {
            echo json_encode([
                'success' => false,
                'message' => 'Nome e e-mail são obrigatórios.'
            ]);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                'success' => false,
                'message' => 'Informe um e-mail válido.'
            ]);
            exit;
        }

        if ($usuarioModel->buscarPorEmail($email)) {
            echo json_encode([
                'success' => false,
                'message' => 'Já existe um responsável com este e-mail.'
            ]);
            exit;
        }

        $sucesso = $usuarioModel->cadastrar($nome, $email);

        if (!$sucesso) {
            echo json_encode([
                'success' => false,
                'message' => 'Erro ao cadastrar responsável.'
            ]);
            exit;
        }

        $novoUsuario = $usuarioModel->buscarUltimo();

        echo json_encode([
            'success' => true,
            'message' => 'Responsável cadastrado com sucesso!',
            'usuario' => $novoUsuario
        ]);
        exit;
    }

    if (isset($_POST['delete_id']) && !empty($_POST['delete_id'])) {
        $sucesso = $tarefaModel->excluir((int) $_POST['delete_id']);

        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => $sucesso,
                'message' => $sucesso ? 'Tarefa excluída com sucesso.' : 'Erro ao excluir tarefa.'
            ]);
            exit;
        }

        header('Location: index.php');
        exit;
    }

    $formData = [
        'title' => trim($_POST['title'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'responsible' => $_POST['responsible'] ?? '',
        'priority' => $_POST['priority'] ?? '',
        'deadline' => $_POST['deadline'] ?? '',
        'status' => $_POST['status'] ?? ''
    ];

    $prioridadesValidas = ['Alta', 'Média', 'Baixa'];
    $statusValidos = ['Aguardando', 'Em Andamento', 'Concluído'];

    if ($formData['title'] === '') {
        $erros[] = 'O título da tarefa é obrigatório.';
    }

    if ($formData['responsible'] === '') {
        $erros[] = 'Selecione um responsável.';
    }

    if (!in_array($formData['priority'], $prioridadesValidas, true)) {
        $erros[] = 'Selecione uma prioridade válida.';
    }

    if (!in_array($formData['status'], $statusValidos, true)) {
        $erros[] = 'Selecione um status válido.';
    }

    if ($formData['deadline'] === '') {
        $erros[] = 'O prazo da tarefa é obrigatório.';
    }

    if (empty($erros)) {
        $dados = [
            'titulo' => $formData['title'],
            'descricao' => $formData['description'],
            'prioridade' => $formData['priority'],
            'prazo' => $formData['deadline'],
            'status' => $formData['status'],
            'usuario_id' => (int) $formData['responsible']
        ];

        $isEdicao = isset($_POST['task_id']) && !empty($_POST['task_id']);

        if ($isEdicao) {
            $sucesso = $tarefaModel->atualizar((int) $_POST['task_id'], $dados);
            $mensagem = $sucesso ? 'Tarefa atualizada com sucesso.' : 'Erro ao atualizar tarefa.';
        } else {
            $sucesso = $tarefaModel->cadastrar($dados);
            $mensagem = $sucesso ? 'Tarefa cadastrada com sucesso.' : 'Erro ao cadastrar tarefa.';
        }

        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');

            $html = null;

            if ($sucesso && !$isEdicao) {
                $novaTarefa = $tarefaModel->buscarUltimaComUsuario();
                if ($novaTarefa) {
                    $html = renderTaskCard($novaTarefa);
                }
            }

            echo json_encode([
                'success' => $sucesso,
                'message' => $mensagem,
                'html' => $html
            ]);
            exit;
        }

        header('Location: index.php');
        exit;
    }

    if ($isAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'message' => implode(' ', $erros)
        ]);
        exit;
    }
}

$tarefas = $tarefaModel->listarComUsuarios();
$statusFiltro = $_GET['status'] ?? 'Todas';
$priorityFiltro = $_GET['priority'] ?? 'Todas';
$responsibleFiltro = $_GET['responsible'] ?? 'Todos';

$tarefas = array_filter($tarefas, function ($tarefa) use ($statusFiltro, $priorityFiltro, $responsibleFiltro) {
    $statusOk = $statusFiltro === 'Todas' || $tarefa['status'] === $statusFiltro;
    $priorityOk = $priorityFiltro === 'Todas' || $tarefa['prioridade'] === $priorityFiltro;
    $responsibleOk = $responsibleFiltro === 'Todos' || $tarefa['usuario_id'] == $responsibleFiltro;

    return $statusOk && $priorityOk && $responsibleOk;
});

$totalTasks = count($tarefas);
$todoTasks = count(array_filter($tarefas, fn($t) => $t['status'] === 'Aguardando'));
$doingTasks = count(array_filter($tarefas, fn($t) => $t['status'] === 'Em Andamento'));
$doneTasks = count(array_filter($tarefas, fn($t) => $t['status'] === 'Concluído'));
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task - Gestão de Tarefas</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="src/styles/style.css">
</head>
<body>
    <main class="app-shell">
        <div id="feedbackMessage" class="feedback-message hidden"></div>

        <?php if (!empty($erros)): ?>
            <div class="validation-errors">
                <ul>
                    <?php foreach ($erros as $erro): ?>
                        <li><?= htmlspecialchars($erro) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <section class="hero-card">
            <div>
                <span class="eyebrow">Bootcamp · Grupo 14</span>
                <h1>Task - Gestão de Tarefas</h1>
                <p class="hero-subtitle">
                    Sistema de gestão de tarefas para organização de atividades, responsáveis e andamento do projeto.
                </p>
            </div>

            <div class="hero-badges">
                <span class="badge">HTML + CSS + jQuery</span>
                <span class="badge">Interações assíncronas AJAX</span>
                <span class="badge">Backend PHP + PostgreSQL</span>
            </div>
        </section>

        <section class="dashboard-grid">
            <article class="summary-card">
                <div class="summary-header">
                    <span>Total de tarefas</span>
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <strong id="totalTasks"><?= $totalTasks ?></strong>
                <small>Todas as tarefas cadastradas no quadro</small>
            </article>

            <article class="summary-card warning">
                <div class="summary-header">
                    <span>Aguardando</span>
                    <i class="fa-regular fa-clock"></i>
                </div>
                <strong id="todoTasks"><?= $todoTasks ?></strong>
                <small>Tarefas aguardando início</small>
            </article>

            <article class="summary-card info">
                <div class="summary-header">
                    <span>Em andamento</span>
                    <i class="fa-solid fa-spinner"></i>
                </div>
                <strong id="doingTasks"><?= $doingTasks ?></strong>
                <small>Atividades em progresso</small>
            </article>

            <article class="summary-card success">
                <div class="summary-header">
                    <span>Concluídas</span>
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <strong id="doneTasks"><?= $doneTasks ?></strong>
                <small>Tarefas finalizadas</small>
            </article>
        </section>

        <section class="panel-grid">
            <div class="forms-column">
                <article class="panel">
                    <div class="panel-title-row">
                        <div>
                            <span class="section-label">Novo responsável</span>
                            <h2>Cadastrar responsável</h2>
                        </div>
                        <span class="panel-tag">Cadastro simples</span>
                    </div>

                    <form id="userForm" class="task-form" method="POST" action="">
                        <div class="input-group">
                            <label for="user_name">Nome</label>
                            <input type="text" id="user_name" name="user_name" required>
                        </div>

                        <div class="input-group">
                            <label for="user_email">E-mail</label>
                            <input type="email" id="user_email" name="user_email" required>
                        </div>

                        <button type="submit" class="primary-button">
                            <i class="fa-solid fa-user-plus"></i>
                            Cadastrar responsável
                        </button>
                    </form>
                </article>

                <article class="panel">
                    <div class="panel-title-row">
                        <div>
                            <span class="section-label"><?= $modoEdicao ? 'Editar tarefa' : 'Nova tarefa' ?></span>
                            <h2><?= $modoEdicao ? 'Atualize os dados da tarefa' : 'Cadastre uma nova tarefa' ?></h2>
                        </div>
                        <span class="panel-tag"><?= $modoEdicao ? 'Modo edição' : 'Formulário ativo' ?></span>
                    </div>

                    <form id="taskForm" class="task-form" method="POST" action="">
                        <?php if ($modoEdicao && $tarefaEmEdicao): ?>
                            <input type="hidden" name="task_id" value="<?= $tarefaEmEdicao['id'] ?>">
                        <?php endif; ?>

                        <div class="input-group">
                            <label for="title">Título</label>
                            <input type="text" id="title" name="title" value="<?= htmlspecialchars($formData['title']) ?>" required>
                        </div>

                        <div class="input-group">
                            <label for="description">Descrição</label>
                            <textarea id="description" name="description"><?= htmlspecialchars($formData['description']) ?></textarea>
                        </div>

                        <div class="form-row two-columns">
                            <div class="input-group">
                                <label for="responsible">Responsável</label>
                                <select id="responsible" name="responsible" required>
                                    <option value="">Selecione</option>
                                    <?php foreach ($usuarios as $usuario): ?>
                                        <option value="<?= $usuario['id'] ?>" <?= (string) $formData['responsible'] === (string) $usuario['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($usuario['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="input-group">
                                <label for="priority">Prioridade</label>
                                <select id="priority" name="priority" required>
                                    <option value="">Selecione</option>
                                    <option value="Alta" <?= $formData['priority'] === 'Alta' ? 'selected' : '' ?>>Alta</option>
                                    <option value="Média" <?= $formData['priority'] === 'Média' ? 'selected' : '' ?>>Média</option>
                                    <option value="Baixa" <?= $formData['priority'] === 'Baixa' ? 'selected' : '' ?>>Baixa</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row two-columns">
                            <div class="input-group">
                                <label for="deadline">Prazo</label>
                                <input type="date" id="deadline" name="deadline" value="<?= htmlspecialchars($formData['deadline']) ?>" required>
                            </div>

                            <div class="input-group">
                                <label for="status">Status</label>
                                <select id="status" name="status" required>
                                    <option value="Aguardando" <?= $formData['status'] === 'Aguardando' ? 'selected' : '' ?>>Aguardando</option>
                                    <option value="Em Andamento" <?= $formData['status'] === 'Em Andamento' ? 'selected' : '' ?>>Em Andamento</option>
                                    <option value="Concluído" <?= $formData['status'] === 'Concluído' ? 'selected' : '' ?>>Concluído</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="primary-button">
                                <i class="fa-solid fa-floppy-disk"></i>
                                <?= $modoEdicao ? 'Salvar alterações' : 'Cadastrar tarefa' ?>
                            </button>

                            <?php if ($modoEdicao): ?>
                                <a href="index.php" class="secondary-button cancel-link">Cancelar edição</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </article>
            </div>

            <article class="panel">
                <div class="panel-title-row">
                    <div>
                        <span class="section-label">Quadro de tarefas</span>
                        <h2>Acompanhe e filtre as atividades</h2>
                    </div>
                    <span class="panel-tag">Filtros dinâmicos</span>
                </div>

                <form class="filters-bar" onsubmit="return false;">
                    <div class="filter-group">
                        <label for="filterStatus">Status</label>
                        <select id="filterStatus">
                            <option value="Todas">Todas</option>
                            <option value="Aguardando">Aguardando</option>
                            <option value="Em Andamento">Em Andamento</option>
                            <option value="Concluído">Concluído</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="filterPriority">Prioridade</label>
                        <select id="filterPriority">
                            <option value="Todas">Todas</option>
                            <option value="Alta">Alta</option>
                            <option value="Média">Média</option>
                            <option value="Baixa">Baixa</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="filterResponsible">Responsável</label>
                        <select id="filterResponsible">
                            <option value="Todos">Todos</option>
                            <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?= $usuario['id'] ?>">
                                    <?= htmlspecialchars($usuario['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>

                <div id="emptyState" class="empty-state <?= empty($tarefas) ? '' : 'hidden' ?>">
                    <i class="fa-regular fa-folder-open"></i>
                    <p>Nenhuma tarefa encontrada com os filtros selecionados.</p>
                </div>

                <div id="taskList" class="task-list">
                    <?php foreach ($tarefas as $tarefa): ?>
                        <?= renderTaskCard($tarefa) ?>
                    <?php endforeach; ?>
                </div>
            </article>
        </section>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="src/javascript/script.js"></script>
</body>
</html>