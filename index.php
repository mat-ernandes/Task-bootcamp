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
        <section class="hero-card">
            <div>
                <span class="eyebrow">Bootcamp · Grupo 14</span>
                <h1>Task - Gestão de Tarefas</h1>
                <p class="hero-subtitle">
                    Sistema de gestão de tarefas para organização de atividades, responsáveis e andamento do projeto.
                </p>
            </div>

            <div class="hero-badges">
                <span class="badge">Front-end funcional</span>
                <span class="badge">HTML + CSS + jQuery</span>
                <span class="badge">Conexão PHP + PostgreSQL</span>
            </div>
        </section>

        <section class="dashboard-grid">
            <article class="summary-card">
                <div class="summary-header">
                    <span>Total de tarefas</span>
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <strong id="totalTasks">0</strong>
                <small>Todas as tarefas cadastradas no quadro</small>
            </article>

            <article class="summary-card warning">
                <div class="summary-header">
                    <span>A fazer</span>
                    <i class="fa-regular fa-circle"></i>
                </div>
                <strong id="todoTasks">0</strong>
                <small>Tarefas ainda não iniciadas</small>
            </article>

            <article class="summary-card info">
                <div class="summary-header">
                    <span>Em andamento</span>
                    <i class="fa-solid fa-spinner"></i>
                </div>
                <strong id="doingTasks">0</strong>
                <small>Atividades sendo executadas</small>
            </article>

            <article class="summary-card success">
                <div class="summary-header">
                    <span>Concluídas</span>
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <strong id="doneTasks">0</strong>
                <small>Demandas finalizadas</small>
            </article>
        </section>

        <section class="panel-grid">
            <article class="panel form-panel">
                <div class="panel-title-row">
                    <div>
                        <p class="section-label">Cadastro</p>
                        <h2>Nova tarefa</h2>
                    </div>
                    <span class="panel-tag">Escopo 1</span>
                </div>

                <form id="taskForm" class="task-form">
                    <div class="input-group">
                        <label for="title">Título</label>
                        <input type="text" id="title" name="title" placeholder="Ex.: Implementar tela de login" required>
                    </div>

                    <div class="input-group">
                        <label for="description">Descrição</label>
                        <textarea id="description" name="description" rows="4" placeholder="Descreva a atividade"></textarea>
                    </div>

                    <div class="form-row two-columns">
                        <div class="input-group">
                            <label for="responsible">Responsável</label>
                            <select id="responsible" name="responsible" required>
                                <option value="">Selecione</option>
                                <option value="João Silva">João Silva</option>
                                <option value="Maria Souza">Maria Souza</option>
                                <option value="Ana Costa">Ana Costa</option>
                            </select>
                        </div>

                        <div class="input-group">
                            <label for="priority">Prioridade</label>
                            <select id="priority" name="priority" required>
                                <option value="">Selecione</option>
                                <option value="Alta">Alta</option>
                                <option value="Média">Média</option>
                                <option value="Baixa">Baixa</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row two-columns">
                        <div class="input-group">
                            <label for="deadline">Prazo</label>
                            <input type="date" id="deadline" name="deadline" required>
                        </div>

                        <div class="input-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" required>
                                <option value="Aguardando">Aguardando</option>
                                <option value="Em Andamento">Em Andamento</option>
                                <option value="Concluído">Concluído</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="primary-button">
                        <i class="fa-solid fa-plus"></i>
                        Adicionar tarefa
                    </button>
                </form>
            </article>

            <article class="panel list-panel">
                <div class="panel-title-row list-header-mobile-fix">
                    <div>
                        <p class="section-label">Acompanhamento</p>
                        <h2>Lista de tarefas</h2>
                    </div>
                </div>

                <div class="filters-bar">
                    <div class="filter-group">
                        <label for="filterStatus">Filtrar por status</label>
                        <select id="filterStatus">
                            <option value="Todas">Todas</option>
                            <option value="A Fazer">A Fazer</option>
                            <option value="Em Andamento">Em Andamento</option>
                            <option value="Concluído">Concluído</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="filterPriority">Filtrar por prioridade</label>
                        <select id="filterPriority">
                            <option value="Todas">Todas</option>
                            <option value="Alta">Alta</option>
                            <option value="Média">Média</option>
                            <option value="Baixa">Baixa</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="filterResponsible">Filtrar por responsável</label>
                        <select id="filterResponsible">
                            <option value="Todos">Todos</option>
                            <option value="João Silva">João Silva</option>
                            <option value="Maria Souza">Maria Souza</option>
                            <option value="Ana Costa">Ana Costa</option>
                        </select>
                    </div>
                </div>

                <div id="emptyState" class="empty-state hidden">
                    <i class="fa-regular fa-folder-open"></i>
                    <p>Nenhuma tarefa encontrada com os filtros selecionados.</p>
                </div>

                <div id="taskList" class="task-list"></div>
            </article>
        </section>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="src/javascript/script.js"></script>
</body>
</html>
