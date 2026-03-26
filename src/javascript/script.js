$(function () {
    const tasks = [
        {
            id: 1,
            title: 'Implementar cadastro de usuário',
            description: 'Criar a funcionalidade com validação backend',
            responsible: 'Mateus Ernandes',
            priority: 'Alta',
            deadline: '2026-04-10',
            status: 'Em Andamento'
        },
        {
            id: 2,
            title: 'Criar protótipo do dashboard',
            description: 'Montar a interface inicial frontend com cartões de resumo e filtros.',
            responsible: 'Mateus Ernandes',
            priority: 'Média',
            deadline: '2026-03-25',
            status: 'Concluído'
        },
        {
            id: 3,
            title: 'Revisar documentação do projeto',
            description: 'Organizar escopo, funcionalidades e preparar material para apresentação.',
            responsible: 'Mateus Ernandes',
            priority: 'Baixa',
            deadline: '2026-04-18',
            status: 'Aguardando'
        }
    ];

    let currentId = tasks.length + 1;

    function formatDate(dateString) {
        const [year, month, day] = dateString.split('-');
        return `${day}/${month}/${year}`;
    }

    function getStatusClass(status) {
        if (status === 'Aguardando') return 'status-todo';
        if (status === 'Em Andamento') return 'status-doing';
        return 'status-done';
    }

    function getPriorityClass(priority) {
        if (priority === 'Alta') return 'priority-high';
        if (priority === 'Média') return 'priority-medium';
        return 'priority-low';
    }

    function buildTaskCard(task) {
        return `
            <article class="task-card" data-id="${task.id}" data-status="${task.status}" data-priority="${task.priority}" data-responsible="${task.responsible}">
                <div class="task-view">
                    <div class="task-card-header">
                        <h3 class="task-title">${task.title}</h3>
                        <div class="task-tags">
                            <span class="tag ${getStatusClass(task.status)}">${task.status}</span>
                            <span class="tag ${getPriorityClass(task.priority)}">${task.priority}</span>
                        </div>
                    </div>

                    <p class="task-description">${task.description || 'Sem descrição informada.'}</p>

                    <div class="task-meta">
                        <span><i class="fa-regular fa-user"></i> ${task.responsible}</span>
                        <span><i class="fa-regular fa-calendar"></i> ${formatDate(task.deadline)}</span>
                    </div>

                    <div class="task-card-footer">
                        <div></div>
                        <div class="task-actions">
                            <button type="button" class="icon-button edit" data-action="edit" aria-label="Editar tarefa">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button type="button" class="icon-button delete" data-action="delete" aria-label="Excluir tarefa">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <form class="edit-form hidden">
                    <div class="input-group">
                        <label>Título</label>
                        <input type="text" name="title" value="${task.title}" required>
                    </div>

                    <div class="input-group">
                        <label>Descrição</label>
                        <textarea name="description" rows="4">${task.description}</textarea>
                    </div>

                    <div class="form-row two-columns">
                        <div class="input-group">
                            <label>Responsável</label>
                            <select name="responsible" required>
                                <option value="João Silva" ${task.responsible === 'João Silva' ? 'selected' : ''}>João Silva</option>
                                <option value="Maria Souza" ${task.responsible === 'Maria Souza' ? 'selected' : ''}>Maria Souza</option>
                                <option value="Ana Costa" ${task.responsible === 'Ana Costa' ? 'selected' : ''}>Ana Costa</option>
                            </select>
                        </div>

                        <div class="input-group">
                            <label>Prioridade</label>
                            <select name="priority" required>
                                <option value="Alta" ${task.priority === 'Alta' ? 'selected' : ''}>Alta</option>
                                <option value="Média" ${task.priority === 'Média' ? 'selected' : ''}>Média</option>
                                <option value="Baixa" ${task.priority === 'Baixa' ? 'selected' : ''}>Baixa</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row two-columns">
                        <div class="input-group">
                            <label>Prazo</label>
                            <input type="date" name="deadline" value="${task.deadline}" required>
                        </div>

                        <div class="input-group">
                            <label>Status</label>
                            <select name="status" required>
                                <option value="Aguardando" ${task.status === 'Aguardando' ? 'selected' : ''}>Aguardando</option>
                                <option value="Em Andamento" ${task.status === 'Em Andamento' ? 'selected' : ''}>Em Andamento</option>
                                <option value="Concluído" ${task.status === 'Concluído' ? 'selected' : ''}>Concluído</option>
                            </select>
                        </div>
                    </div>

                    <div class="edit-actions">
                        <button type="submit" class="primary-button">
                            <i class="fa-solid fa-check"></i>
                            Salvar alteração
                        </button>
                        <button type="button" class="secondary-button" data-action="cancel">
                            Cancelar
                        </button>
                    </div>
                </form>
            </article>
        `;
    }

    function renderTasks() {
        const statusFilter = $('#filterStatus').val();
        const priorityFilter = $('#filterPriority').val();
        const responsibleFilter = $('#filterResponsible').val();

        const filteredTasks = tasks.filter(task => {
            const statusMatch = statusFilter === 'Todas' || task.status === statusFilter;
            const priorityMatch = priorityFilter === 'Todas' || task.priority === priorityFilter;
            const responsibleMatch = responsibleFilter === 'Todos' || task.responsible === responsibleFilter;
            return statusMatch && priorityMatch && responsibleMatch;
        });

        $('#taskList').html(filteredTasks.map(buildTaskCard).join(''));
        $('#emptyState').toggleClass('hidden', filteredTasks.length > 0);
        updateSummary();
    }

    function updateSummary() {
        const total = tasks.length;
        const todo = tasks.filter(task => task.status === 'Aguardando').length;
        const doing = tasks.filter(task => task.status === 'Em Andamento').length;
        const done = tasks.filter(task => task.status === 'Concluído').length;

        $('#totalTasks').text(total);
        $('#todoTasks').text(todo);
        $('#doingTasks').text(doing);
        $('#doneTasks').text(done);
    }

    $('#taskForm').on('submit', function (e) {
        e.preventDefault();

        const newTask = {
            id: currentId++,
            title: $('#title').val().trim(),
            description: $('#description').val().trim(),
            responsible: $('#responsible').val(),
            priority: $('#priority').val(),
            deadline: $('#deadline').val(),
            status: $('#status').val()
        };

        tasks.unshift(newTask);
        this.reset();
        $('#status').val('Aguardando');
        renderTasks();
    });

    $('#filterStatus, #filterPriority, #filterResponsible').on('change', function () {
        renderTasks();
    });

    $('#taskList').on('click', '[data-action="edit"]', function () {
        const taskCard = $(this).closest('.task-card');
        taskCard.find('.task-view').addClass('hidden');
        taskCard.find('.edit-form').removeClass('hidden');
    });

    $('#taskList').on('click', '[data-action="cancel"]', function () {
        const taskCard = $(this).closest('.task-card');
        taskCard.find('.edit-form').addClass('hidden');
        taskCard.find('.task-view').removeClass('hidden');
    });

    $('#taskList').on('submit', '.edit-form', function (e) {
        e.preventDefault();

        const taskCard = $(this).closest('.task-card');
        const id = Number(taskCard.data('id'));
        const task = tasks.find(item => item.id === id);

        task.title = $(this).find('[name="title"]').val().trim();
        task.description = $(this).find('[name="description"]').val().trim();
        task.responsible = $(this).find('[name="responsible"]').val();
        task.priority = $(this).find('[name="priority"]').val();
        task.deadline = $(this).find('[name="deadline"]').val();
        task.status = $(this).find('[name="status"]').val();

        renderTasks();
    });

    $('#taskList').on('click', '[data-action="delete"]', function () {
        const taskCard = $(this).closest('.task-card');
        const id = Number(taskCard.data('id'));
        const index = tasks.findIndex(item => item.id === id);

        if (index !== -1) {
            tasks.splice(index, 1);
            renderTasks();
        }
    });

    renderTasks();
});
