# Настройка и запуск

## Запуск сервера с Docker Compose

1. Перейдите в каталог `.ci`:
    ```bash
    cd .ci
    ```

2. Запустите сервер в фоновом режиме с помощью `docker compose`:
    ```bash
    docker compose up -d
    ```

3. Обновите зависимости с помощью `composer`:
    ```bash
    docker compose run --rm composer update
    ```

## Настройка констант в файле `amo-add-leads.php`

В файле `src/public/amo-add-leads.php` нужно установить следующие константы:

```php
define('AMO_DOMAIN', 'your_domain_here');
define('AMO_ACCESS_TOKEN', 'your_access_token_here');
define('AMO_PIPELINE_STATUS_ID', 'your_pipeline_status_id_here');
define('AMO_30_SEC_CUSTOM_FIELD_ID', 'your_custom_field_id_here');
```
Замените значения на соответствующие данные из вашей AmoCRM.