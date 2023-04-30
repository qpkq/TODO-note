<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Item;
use App\Models\Tag;
use App\Models\TodoList;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // тестовый юзер
         User::factory()->create([
             'name' => 'qpkq',
             'email' => 'admin@admin.ru',
         ]);

         // тестовый список #1
        TodoList::factory()->create([
            'title' => 'Работа',
            'user_id' => 1,
        ]);

        // тестовый список #2
        TodoList::factory()->create([
            'title' => 'Личное',
            'user_id' => 1,
        ]);

        // тестовый список #3
        TodoList::factory()->create([
            'title' => 'Мечты',
            'user_id' => 1,
        ]);

        $files = collect(File::allFiles(storage_path('app/public')));
        $image = $files->random();
        $image = str_replace(storage_path('app/public/'), '', $image->getPathname());

        Item::factory()->create([
            'todo_list_id' => 1,
            'title' => 'Встреча с руководителем',
            'image' => $image,
            'content' => 'Встретиться в офисе с руководителем и обсудить важные дела.'
        ]);

        Tag::factory()->create([
            'item_id' => 1,
            'title' => 'Важное',
        ]);

        Item::factory()->create([
            'todo_list_id' => 1,
            'title' => 'Дать ТЗ команде #7',
            'image' => $image,
            'content' => 'Передать ТЗ и обсудить вопросы команды.'
        ]);

        Tag::factory()->create([
            'item_id' => 2,
            'title' => 'Важное',
        ]);

        Tag::factory()->create([
            'item_id' => 2,
            'title' => 'Срочно',
        ]);

        Item::factory()->create([
            'todo_list_id' => 2,
            'title' => 'Погулять с собакой в 19:00',
            'image' => $image,
            'content' => 'Вечером погулять с собакой и вынести мусор.'
        ]);

        Item::factory()->create([
            'todo_list_id' => 2,
            'title' => 'Позвонить родителям',
            'image' => $image,
            'content' => 'Позвонить родителям и узнать как здоровье. Спросить про лекарства.'
        ]);

        Tag::factory()->create([
            'item_id' => 4,
            'title' => 'Не забыть',
        ]);

        Item::factory()->create([
            'todo_list_id' => 3,
            'title' => 'Купить майбах',
            'image' => $image,
            'content' => 'Купить майбах и не пропускать людей на пешеходном.'
        ]);

        Item::factory()->create([
            'todo_list_id' => 3,
            'title' => 'Поехать в кругосветное путешествие',
            'image' => $image,
            'content' => 'Поехать в кругосветку и посетить как можно больше стран.'
        ]);

        Tag::factory()->create([
            'item_id' => 6,
            'title' => 'Обязательно',
        ]);

        Item::factory()->create([
            'todo_list_id' => 3,
            'title' => 'Получить оффер',
            'image' => $image,
            'content' => 'Получить оффер на работу в крутой компании :).'
        ]);

        Tag::factory()->create([
            'item_id' => 7,
            'title' => 'Стремление',
        ]);

        Tag::factory()->create([
            'item_id' => 7,
            'title' => 'Очень важно',
        ]);
    }
}
