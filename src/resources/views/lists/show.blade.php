@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center">
                <a href="{{ route('home') }}" class="btn btn-outline-primary mb-4">Назад к спискам</a>
                <form id="item-form">
                    @csrf

                    <div class="mb-4">
                        <input id="item-title" type="text" class="form-control mb-2" name="title" placeholder="Название новой задачи*">
                        <input id="item-image" type="file" class="form-control mb-2">
                        <textarea id="item-content" type="text" class="form-control mb-2" name="content" placeholder="Описание новой задачи*" style="height: 100px"></textarea>
                    </div>
                    <button type="submit" id="create" class="btn btn-success mb-4">Создать задачу</button>
                </form>

                <input id="sort-title-tag" type="text" class="form-control my-3" name="title" placeholder="Название тега">
                <button type="submit" id="sort" class="btn btn-outline-success mb-4">Сортировать</button>

                <input id="search-item-title" type="text" class="form-control my-3" name="title" placeholder="Название пункта">
                <button type="submit" id="search" class="btn btn-primary mb-4">Поиск</button>
            </div>
            <div class="card">
                <div class="card-header">{{ __('Задачи') }}</div>
                <div id="items" class="card-body">

                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const listId = '{{ $list->id }}';

    // удаление пункта
    function deleteTodoItem(itemId) {
        $.ajax({
            url: '/api/todo/v1/lists/' + listId + '/items/' + itemId + '/delete',
            type: 'POST',
            success: function(response) {
                loadItems();
            },
        });
    }

    // удаление изображения пункта
    function deleteTodoImageItem(itemId) {
        $.ajax({
            url: '/api/todo/v1/lists/' + listId + '/items/' + itemId + '/delete-image',
            type: 'POST',
            success: function(response) {
                loadItems();
            },
        });
    }

    // загрузка нового изображения
    function updateTodoItemImage(itemId, image) {
        var formData = new FormData();
        var input = $('#add-item-image-' + itemId)[0];
        if (input.files.length > 0) {
            formData.append('image', input.files[0]);
        }

        $.ajax({
            url: '/api/todo/v1/lists/' + listId + '/items/' + itemId + '/update',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                loadItems();
            },
        });
    }

    // редактирование пункта
    function updateTodoItem(itemId) {
        var title = $('#edit-item-title-' + itemId);
        var content = $('#edit-item-content-' + itemId);
        var data = {};
        if (title.val() !== '') {
            data.title = title.val();
        }
        if (content.val() !== '') {
            data.content = content.val();
        }
        $.ajax({
            url: '/api/todo/v1/lists/' + listId + '/items/' + itemId + '/update',
            type: 'POST',
            data: data,
            success: function (response) {
                loadItems();
            },
        });
    }

    // добавление тега
    function updateTodoItemTags(itemId, tags) {
        var input = $('#add-item-tags-' + itemId);
        $.ajax({
            url: '/api/todo/v1/lists/' + listId + '/items/' + itemId + '/tags/create',
            type: 'POST',
            data: {title: input.val()},
            success: function (response) {
                loadItems();
            },
        });
    }

    // изменение цвета тега при наведении
    $(document).on('mouseenter', '.tag', function() {
        $(this).removeClass('bg-primary').addClass('bg-danger');
    });

    // изменение цвета тега обратно
    $(document).on('mouseleave', '.tag', function() {
        $(this).removeClass('bg-danger').addClass('bg-primary');
    });

    // удаление тега при нажатии
    $(document).on('click', '.tag-delete', function() {
        var itemId = $(this).data('item-id');
        var tagTitle = $(this).text();
        var tagId = $(this).data('tag-id');
        $.ajax({
            url: '/api/todo/v1/lists/' + listId + '/items/' + itemId + '/tags/' + tagId + '/delete',
            type: 'POST',
            data: { title: tagTitle },
            success: function(response) {
                loadItems();
            },
        });
    });


    // загрузка пунктов на страницу
    function loadItems() {
        $.ajax({
            url: '/api/todo/v1/lists/' + listId + '/items',
            type: 'GET',
            success: function(response) {
                var items = response.items;
                var html = '';
                items.forEach(function(item) {

                    var imagePath = '';
                    if (item.image) {
                        imagePath = window.location.origin + '/storage/' + item.image;
                    }

                    html += '<div class="card mb-4">';
                    html += '<div class="card-body">';
                    html += '<h5 class="card-title mb-3">' + item.title + '</h5>';
                    if (item.tags) {
                        html += '<div class="tags mb-3">';
                        item.tags.forEach(function(tag) {
                            html += '<span class="badge bg-primary tag tag-delete" data-item-id="' + item.id + '" data-tag-id="' + tag.id + '">' + tag.title + '</span> ';
                        });
                        html += '</div>';
                    }
                    if (imagePath) {
                        html += '<a href="' + imagePath + '" target="_blank">';
                        html += '<img src="' + imagePath + '" class="card-img-top" style="width: 150px; height: 150px;">';
                        html += '</a>';
                    }
                    html += '<p class="card-text mt-3 mb-5">' + item.content + '</p>';

                    html += '<div class="text-center border-top border-secondary">';
                    html += '<button type="button" class="btn btn-danger btn-sm delete-item mt-3 my-1" data-item-id="' + item.id + '">Удалить задачу</button>';
                    html += '</div>';

                    html += '<div class="text-center">';
                    html += '<input id="edit-item-title-' + item.id + '" type="text" class="form-control my-2" name="title" placeholder="Введите новое название">';
                    html += '<textarea id="edit-item-content-' + item.id + '" type="text" class="form-control my-2" name="title" placeholder="Введите новое описание" style="height: 100px"></textarea>';
                    html += '<button type="button" class="btn btn-primary btn-sm edit-item my-1" data-item-id="' + item.id + '">Редактировать задачу</button>';
                    html += '</div>';

                    html += '<div class="text-center">';
                    if (imagePath) {
                        html += '<button type="button" class="btn btn-danger btn-sm delete-image-item my-1" data-item-id="' + item.id + '">Удалить изображение</button>';
                    } else {
                        html += '<input id="add-item-image-' + item.id + '" type="file" class="form-control my-1">';
                        html += '<button type="button" class="btn btn-outline-success btn-sm upload-image-item" data-item-id="' + item.id + '">Добавить изображение</button>';
                    }
                    html += '</div>';


                    html += '<div class="text-center">';
                    html += '<input id="add-item-tags-' + item.id + '" type="text" class="form-control my-2" name="title" placeholder="Название тега">';
                    html += '<button type="button" class="btn btn-outline-primary btn-sm add-tag-item ms-3" data-item-id="' + item.id + '">Добавить тег</button>';
                    html += '</div>';

                    html += '</div>';
                    html += '</div>';
                });
                $('#items').html(html);
            }
        });

        // удаление пункта
        $(document).on('click', '.delete-item', function() {
            var itemId = $(this).data('item-id');
            deleteTodoItem(itemId);
        });

        // удаление изображения пункта
        $(document).on('click', '.delete-image-item', function() {
            var itemId = $(this).data('item-id');
            deleteTodoImageItem(itemId);
        });

        // добавление изображения к пункту
        $(document).on('click', '.upload-image-item', function() {
            var itemId = $(this).data('item-id');
            var image = $('#add-item-image-' + itemId)[0].files[0];

            updateTodoItemImage(itemId, image);
        });

        // добавление тега к пункту
        $(document).on('click', '.add-tag-item', function() {
            var itemId = $(this).data('item-id');
            var tags = $('#add-item-tags-' + itemId).val();
            updateTodoItemTags(itemId, tags);
        });

        // редактирование пункта
        $(document).on('click', '.edit-item', function() {
            var itemId = $(this).data('item-id');
            updateTodoItem(itemId);
        });
    }

    loadItems();

    // создание пункта
    function createTodoItem(title, content, image, tags) {
        var formData = new FormData();
        formData.append('title', title);
        formData.append('content', content);
        if (image) {
            formData.append('image', image);
        }
        if (tags) {
            formData.append('tags', JSON.stringify(tags));
        }

        $.ajax({
            url: '/api/todo/v1/lists/' + listId + '/items/create',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                loadItems();
            },
        });
    }

    // создание пункта
    $('#create').on('click', function(e) {
        e.preventDefault();
        var title = $('#item-title').val();
        var content = $('#item-content').val();
        var image = $('#item-image')[0].files[0];

        createTodoItem(title, content, image);
    });

    // загрузка отсортированных тегов
    function loadSortItems(title) {
        $.ajax({
            url: '/api/todo/v1/lists/' + listId + '/items/sort',
            type: 'POST',
            data: { title: title },
            success: function(response) {
                var items = response.items;
                var html = '';
                items.forEach(function(item) {

                    var imagePath = '';
                    if (item.image) {
                        imagePath = window.location.origin + '/storage/' + item.image;
                    }

                    html += '<div class="card mb-4">';
                    html += '<div class="card-body">';
                    html += '<h5 class="card-title mb-3">' + item.title + '</h5>';
                    if (item.tags) {
                        html += '<div class="tags mb-3">';
                        item.tags.forEach(function(tag) {
                            html += '<span class="badge bg-primary tag tag-delete" data-item-id="' + item.id + '" data-tag-id="' + tag.id + '">' + tag.title + '</span> ';
                        });
                        html += '</div>';
                    }
                    if (imagePath) {
                        html += '<a href="' + imagePath + '" target="_blank">';
                        html += '<img src="' + imagePath + '" class="card-img-top" style="width: 150px; height: 150px;">';
                        html += '</a>';
                    }
                    html += '<p class="card-text mt-3 mb-5">' + item.content + '</p>';
                    html += '<button type="button" class="btn btn-danger btn-sm delete-item me-3" data-item-id="' + item.id + '">Удалить задачу</button>';
                    if (imagePath) {
                        html += '<button type="button" class="btn btn-danger btn-sm delete-image-item" data-item-id="' + item.id + '">Удалить изображение</button>';
                    } else {
                        html += '<input id="add-item-image-' + item.id + '" type="file" class="form-control my-2">';
                        html += '<button type="button" class="btn btn-outline-success btn-sm upload-image-item" data-item-id="' + item.id + '">Добавить изображение</button>';
                    }
                    html += '<button type="button" class="btn btn-outline-primary btn-sm add-tag-item ms-3" data-item-id="' + item.id + '">Добавить тег</button>';
                    html += '<input id="add-item-tags-' + item.id + '" type="text" class="form-control my-2" name="title" placeholder="Название тега">';

                    html += '</div>';
                    html += '</div>';
                });
                $('#items').html(html);
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    }

    // вытягиваем название тега
    $('#sort').on('click', function(e) {
        e.preventDefault();
        var title = $('#sort-title-tag').val();
        loadSortItems(title);
    });







    // загрузка отсортированных тегов
    function loadSearchItems(title) {
        $.ajax({
            url: '/api/todo/v1/lists/' + listId + '/items/search',
            type: 'POST',
            data: { title: title },
            success: function(response) {
                var items = response.items;
                var html = '';
                items.forEach(function(item) {

                    var imagePath = '';
                    if (item.image) {
                        imagePath = window.location.origin + '/storage/' + item.image;
                    }

                    html += '<div class="card mb-4">';
                    html += '<div class="card-body">';
                    html += '<h5 class="card-title mb-3">' + item.title + '</h5>';
                    if (item.tags) {
                        html += '<div class="tags mb-3">';
                        item.tags.forEach(function(tag) {
                            html += '<span class="badge bg-primary tag tag-delete" data-item-id="' + item.id + '" data-tag-id="' + tag.id + '">' + tag.title + '</span> ';
                        });
                        html += '</div>';
                    }
                    if (imagePath) {
                        html += '<a href="' + imagePath + '" target="_blank">';
                        html += '<img src="' + imagePath + '" class="card-img-top" style="width: 150px; height: 150px;">';
                        html += '</a>';
                    }
                    html += '<p class="card-text mt-3 mb-5">' + item.content + '</p>';
                    html += '<button type="button" class="btn btn-danger btn-sm delete-item me-3" data-item-id="' + item.id + '">Удалить задачу</button>';
                    if (imagePath) {
                        html += '<button type="button" class="btn btn-danger btn-sm delete-image-item" data-item-id="' + item.id + '">Удалить изображение</button>';
                    } else {
                        html += '<input id="add-item-image-' + item.id + '" type="file" class="form-control my-2">';
                        html += '<button type="button" class="btn btn-outline-success btn-sm upload-image-item" data-item-id="' + item.id + '">Добавить изображение</button>';
                    }
                    html += '<button type="button" class="btn btn-outline-primary btn-sm add-tag-item ms-3" data-item-id="' + item.id + '">Добавить тег</button>';
                    html += '<input id="add-item-tags-' + item.id + '" type="text" class="form-control my-2" name="title" placeholder="Название тега">';

                    html += '</div>';
                    html += '</div>';
                });
                $('#items').html(html);
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    }

    // вытягиваем название тега
    $('#search').on('click', function(e) {
        e.preventDefault();
        var title = $('#search-item-title').val();
        loadSearchItems(title);
    });

</script>
@endsection
