# Бандл для Symfony+Wordpress, дающий возможность использовать Twig 

***INTERNAL***

В результате в контейнере появляется сервис `twig.instance` (и `twig`) со сконфигурированным экземпляром Twig. Плюс Twig автоматически подцепляется к системе после загрузки контейнера.

## Установка

composer.json:

```json
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/proklung/timber.twig.bundle"
        }
    ]
```

```bash
composer require proklung/timber-twig-bundle
```

### Конфигурация

#### Подключение дополнительных функций Твига

Пример секции `twig` основного контейнера

```yaml
parameters:
  twig:
    functions:
      get_twig_header: Local\Services\TwigFunctionsExtended::renderHeader
      get_template_post: Local\Post::getTemplate

```