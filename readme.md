yii2-micro + vue.js 

## Установка

1. Создаем БД, прописываем свои логин, пароль, имя бд. (в файлах config.php и console.php). 

2. Переходим в корень проекта и устанавливаем пакеты через composer:

```
composer install
```

3. Выполняем миграции

```
vendor/bin/yii migrate/up --appconfig=console.php
```

