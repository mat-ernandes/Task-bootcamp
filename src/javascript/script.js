$(function () {
    function showFeedback(message, isSuccess = true) {
        const feedback = $('#feedbackMessage');

        feedback
            .removeClass('hidden success error')
            .addClass(isSuccess ? 'success' : 'error')
            .text(message);

        setTimeout(() => {
            feedback.addClass('hidden').text('');
        }, 3000);
    }

    function updateSummaryCards() {
        const visibleCards = $('.task-card:visible');

        $('#totalTasks').text(visibleCards.length);
        $('#todoTasks').text(visibleCards.filter('[data-status="Aguardando"]').length);
        $('#doingTasks').text(visibleCards.filter('[data-status="Em Andamento"]').length);
        $('#doneTasks').text(visibleCards.filter('[data-status="Concluído"]').length);
    }

    function applyFilters() {
        const status = $('#filterStatus').val();
        const priority = $('#filterPriority').val();
        const responsible = $('#filterResponsible').val();

        $('.task-card').each(function () {
            const card = $(this);

            const statusMatch = status === 'Todas' || card.data('status') === status;
            const priorityMatch = priority === 'Todas' || card.data('priority') === priority;
            const responsibleMatch = responsible === 'Todos' || String(card.data('responsible')) === String(responsible);

            card.toggle(statusMatch && priorityMatch && responsibleMatch);
        });

        const visible = $('.task-card:visible').length;
        $('#emptyState').toggleClass('hidden', visible !== 0);

        updateSummaryCards();
    }

    $('#userForm').on('submit', function (e) {
        e.preventDefault();

        const form = $(this);

        $.ajax({
            url: 'index.php',
            method: 'POST',
            data: form.serialize(),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    const usuario = response.usuario;

                    if (usuario) {
                        const optionHtml = `<option value="${usuario.id}" selected>${usuario.nome}</option>`;

                        $('#responsible').append(optionHtml);
                        $('#filterResponsible').append(`<option value="${usuario.id}">${usuario.nome}</option>`);
                        $('#responsible').val(String(usuario.id));
                    }

                    form[0].reset();
                    showFeedback(response.message, true);
                } else {
                    showFeedback(response.message || 'Erro ao cadastrar responsável.', false);
                }
            },
            error: function (xhr) {
                console.error('Erro ao cadastrar responsável:', xhr.responseText);
                showFeedback('Erro ao cadastrar responsável.', false);
            }
        });
    });

    $('#taskForm').on('submit', function (e) {
        e.preventDefault();

        const form = $(this);

        $.ajax({
            url: 'index.php',
            method: 'POST',
            data: form.serialize(),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    if (response.html) {
                        $('#taskList').prepend(response.html);
                    }

                    form[0].reset();
                    applyFilters();
                    showFeedback(response.message, true);
                } else {
                    showFeedback(response.message || 'Erro ao cadastrar tarefa.', false);
                }
            },
            error: function (xhr) {
                console.error('Erro ao cadastrar:', xhr.responseText);
                showFeedback('Erro ao cadastrar tarefa.', false);
            }
        });
    });

    $(document).on('submit', '.task-actions form', function (e) {
        e.preventDefault();

        const form = $(this);
        const card = form.closest('.task-card');

        if (!confirm('Tem certeza que deseja excluir?')) {
            return;
        }

        $.ajax({
            url: 'index.php',
            method: 'POST',
            data: form.serialize(),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    card.fadeOut(200, function () {
                        $(this).remove();
                        applyFilters();
                    });

                    showFeedback(response.message, true);
                } else {
                    showFeedback(response.message || 'Erro ao excluir tarefa.', false);
                }
            },
            error: function (xhr) {
                console.error('Erro ao excluir:', xhr.responseText);
                showFeedback('Erro ao excluir a tarefa.', false);
            }
        });
    });

    $('#filterStatus, #filterPriority, #filterResponsible').on('change', applyFilters);

    applyFilters();
});