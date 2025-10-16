# Каталог интернет-магазина
Стек технологий: Laravel + Docker + PHP, MySQL
---

* Использован `DB::table` для упрощённого запроса к `prices` (алиас `price`)
* Для подсчёта количества товаров в подгруппах рекурсивная функция
* `.env.example` настроен под Docker-среду
* Для списка товаров использован один запрос LEFT JOIN prices
* UI - по тз, отказалась от стороннего CSS-фреймворка
* Данные groups, products, prices загружаются из дампа test.sql
  
---
* Корневые группы (id_parent = 0) в левой колонке, UI полностью по тз
* Список товаров с сортировкой (по цене и названию) и пагинацией (6 товаров на странице)
* Количество товаров у группы учитывает товары во всех подгруппах.
* При выборе группы показываются товары из неё и всех её подгрупп.
* Карточка товара: название, цена, хлебные крошки 
* Использование Docker Compose

---
## Запуск проекта

1. Клонировать репозиторий.
2. Скопировать `.env.example` -> `.env` и при необходимости отредактировать. Скопировать файл окружения:

   ```bash
   cp .env.example .env
   ```

3. Поднять контейнеры:

   ```bash
   docker compose up -d --build
   ```

4. Установить зависимости Laravel:

   ```bash
   docker compose exec app composer install
   docker compose exec app php artisan key:generate
   ```

5. Применить миграции:
   ```bash
   docker compose exec app php artisan migrate
   ```
   
7. Импортировать дамп test.sql (базу данных):

   ```bash
   docker exec -i db mysql -u root -proot test < test.sql
   ```

8. Перейти в браузере на:

   ```
   http://localhost:8080/catalog
   ```

---
