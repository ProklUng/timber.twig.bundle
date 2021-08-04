# Бандл для Symfony+Wordpress, дающий возможность использовать Twig (через Timber) 

***INTERNAL***

В результате в контейнере появляется сервис `twig.instance` со сконфигурированным экземпляром Twig. Плюс Twig автоматически подцепляется к системе после загрузки контейнера.

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
    renders:
      default:
        class: Local\Renders\ViewContent
        template: './sectionsItems/content.twig'
 
    functions:
      get_twig_header: Local\Services\TwigFunctionsExtended::renderHeader
      get_template_post: Local\Post::getTemplate

```