@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center">
                <form id="todo-form">
                    <div class="mb-4">
                        <input id="todo-input" type="text" class="form-control" name="title" placeholder="Название нового списка">
                    </div>
                    <button type="submit" id="create" class="btn btn-success mb-4">Создать TO-DO список</button>
                </form>
            </div>
            <div class="card">
                <div class="card-header">{{ __('TO-DO списки') }}</div>
                <div id="todo-list" class="card-body">

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Получаем элементы html
    const form = document.querySelector('#todo-form');
    const input = document.querySelector('#todo-input');
    const todoList = document.querySelector('#todo-list');

    function createTodoElement(todo) {
        const card = document.createElement('div');
        card.classList.add('card', 'mb-3');

        const cardHeader = document.createElement('div');
        cardHeader.classList.add('card-header');
        cardHeader.textContent = todo.title;

        const cardBody = document.createElement('div');
        cardBody.classList.add('card-body');
        cardBody.textContent = todo.description;

        const deleteButton = document.createElement('button');
        deleteButton.classList.add('btn', 'btn-outline-danger');
        deleteButton.textContent = 'Удалить';

        const showButton = document.createElement('a');
        showButton.classList.add('btn', 'btn-outline-primary', 'mb-2');
        showButton.href = '{{ route('lists.show', '') }}' + '/' + todo.id;
        showButton.textContent = 'Просмотреть список';

        deleteButton.addEventListener('click', async () => {
            try {
                const response = await fetch(`http://localhost:80/api/todo/v1/lists/${todo.id}/delete`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                });

                if (response.ok) {
                    card.remove();
                    loadTodos()
                }
            } catch (error) {
                console.error(error);
            }
        });

        card.appendChild(cardHeader);
        card.appendChild(cardBody);
        card.appendChild(showButton);
        card.appendChild(deleteButton);

        return card;
    }

    // Загружаем листы из БД
    async function loadTodos() {
        try {
            const response = await fetch('http://localhost:80/api/todo/v1/lists', {
                withCredentials: true,
            });
            const todos = await response.json();
            todoList.innerHTML = '';
            const existingTodosIds = new Set();
            todos.forEach(todo => {
                const existingTodoElement = todoList.querySelector(`[data-id="${todo.id}"]`);
                if (existingTodoElement) {
                    existingTodosIds.add(todo.id);
                } else {
                    const todoElement = createTodoElement(todo);
                    todoList.prepend(todoElement);
                }
            });
            // Фильтруем todos по id исключая уже существующие элементы
            const newTodos = todos.filter(todo => !existingTodosIds.has(todo.id));
            todos.push(...newTodos); // Добавляем только новые элементы в конец массива todos
        } catch (error) {
            console.error(error);
        }
    }

    loadTodos();

    // Функция для обработки отправки формы
    async function handleFormSubmit(event) {
        event.preventDefault();

        const title = input.value;

        try {
            const response = await fetch('http://localhost:80/api/todo/v1/lists/create', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                credentials: 'include',
                body: JSON.stringify({ title }),
            });

            const todo = await response.json();

            // Проверяем что to-do был успешно добавлен в базу данных и не был добавлен ранее в список
            const existingTodoElement = todoList.querySelector(`[data-id="${todo.id}"]`);
            if (todo && todo.id && !existingTodoElement) {
                const todoElement = createTodoElement(todo);
                todoList.insertBefore(todoElement, todoList.firstChild);
                loadTodos();
            }

        } catch (error) {
            console.error(error);
        }

        input.value = '';
    }

    // Назначаем обработчик события отправки формы
    form.addEventListener('submit', handleFormSubmit);
</script>
@endsection
