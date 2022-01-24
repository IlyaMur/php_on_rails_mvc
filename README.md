# PHP On Rails

![CodeSniffer-PSR-12](https://github.com/IlyaMur/php_on_rails_mvc/workflows/CodeSniffer-PSR-12/badge.svg)
![PHPUnit-Tests](https://github.com/IlyaMur/php_on_rails_mvc/workflows/PHPUnit-Tests/badge.svg)
[![Maintainability](https://api.codeclimate.com/v1/badges/673249eff3f090fe3f06/maintainability)](https://codeclimate.com/github/IlyaMur/php_on_rails_mvc/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/673249eff3f090fe3f06/test_coverage)](https://codeclimate.com/github/IlyaMur/php_on_rails_mvc/test_coverage)

**[🇬🇧 English readme](https://github.com/IlyaMur/php_on_rails_mvc/blob/master/README_en.md)**

**Содержание**
  - [О фреймворке](#о-фреймворке)
  - [Установка](#установка)
    - [Использованные библиотеки](#использованные-библиотеки)
  - [Как использовать](#как-использовать)
    - [Конфигурация](#конфигурация)
    - [Маршрутизация](#Маршрутизация)
    - [Контроллеры](#контроллеры)
      - [Экшены](#экшены)
      - [Параметры маршрутов](#параметры-маршрутов)
      - [Экшен фильтры](#экшен-фильтры)
    - [Представления](#представления)
    - [Модели](#модели)
    - [Ошибки](#ошибки)
  - [Направления для дальнейшего развития](#направления-для-дальнейшего-развития)

## О Фрейморвке  

**PHP On Rails** - MVC-фреймворк для построения веб-приложений на языке PHP.  
Фреймворк создан в целях обучения, но предлагает некоторые продвинутые опции, такие как гибкая [система маршрутизации](#маршрутизация) и [экшен-фильтры](#экшен-фильтры). Основой для [представлений](#представления) является шаблонизатор Twig.

Поставленная цель - создание с нуля фреймворка полностью базирующегося на паттерне Модель-Представление-Контроллер.  
Не смотря на то, что PHP On Rails в первую очередь учебный проект, он полностью работоспособен и готов к построению приложений.  

На базе PHP On Rails выполнен полноценный блог - [myPosts](https://github.com/IlyaMur/myposts_app).   
В [myPosts](https://github.com/IlyaMur/myposts_app) реализованы: системы авторизации/аутентификации, рассылка уведомлений на почту, хранение данных (локально и на базе AWS S3), восстановление пароля, защита от спама, работа с хэштегами, админ-панель и другой функционал.

## Установка  

- Версия PHP >= 8.0 (фреймворк использует именованные аргументы и некоторые другие нововведения PHP).
- `$ git clone` данный репозиторий.
- `$ make install` для установки зависимостей.
- В настройках веб-сервера установить root в директории public. 
- В `config/config.php` ввести данные доступа к БД.

Для человекопонятных URL необходимы настройки rewrite rules сервера. [.htaccess](public/.htaccess) файл доступен в директории `public`.

### Использованные библиотеки  

В процессе создания фреймворка стояла цель написания решений с нуля.  
Единственная зависимость PHP On Rails:
-  Twig Template Engine.

## Как Использовать

### Конфигурация  

Настройки конфигурации доступны в файле `config/config.php`.

Настройки по умолчанию включают в себя:
- Данные подключения к серверу БД. 
- Логирование ошибок.
- Установки для вывода/скрытия детализации ошибок.

Для переопределения настроек в конфигурационном файле доступны соответствующие константы.

### Маршрутизация  

[Маршрутизатор](src/Service/Router.php) распознает запрошенный URL и вызывает соответствующий экшн (метод) определенного контроллера. Маршруты добавляются в [фронт контроллер](public/index.php). 

В качестве примера доступен маршрут, который соответствует экшену `index` в [Home controller](src/Controllers/Home.php).

Маршруты добавляются методом `add`. Возможно добавление фиксированных маршрутов с определением конкретного контроллера и экшена: 
```php
// При запросе на root, будет вызван метод index, контроллера Home. 
$router->add('', ['controller' => 'Home', 'action' => 'index']);

// При запросе на posts/index, будет вызван метод index, контроллера Posts. 
$router->add('posts/index', ['controller' => 'Posts', 'action' => 'index']);
```

Так же возможно определение гибких маршрутов, с переменными **controller** и **action**:
```php
// В данном случае маршрутизатор будет сопоставлять доступные контроллеры и экшены с указанными в запросе
$router->add('{controller}/{action}');
```

В дополнение к переменным **controller** и **action** возможно определить любой желаемый параметр поставив его в фигурные скобки, так же определив для него регулярное выражение:
```php
// В данном случае параметр: id. 
$router->add('{controller}/{id:\d+}/{action}');

// Для маршрутизации к определенному посту с помощью его id. 
$router->add('posts/{action}/{id:\d+}', ['controller' => 'posts']);
```

Доступно определение неймспейса для контроллера, что может быть удобно для создания "админок":
```php
$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);
```

### Контроллеры

В PHP On Rails контроллеры - это классы с наследованием от класса Базового Контроллера [src\Controllers\BaseController.php](src/Controllers/BaseController.php)

Контроллеры хранятся в директории `src/Controllers`. Как образец доступен [Home controller](src/Controllers/Home.php).  
Классы контроллеров должны находиться в неймспейсе `Ilyamur\PhpOnRails\Controllers`.  
Доступно добавление поддиректорий для организации контроллеров, для этого при добавлении маршрута необходимо указать и неймспейс (описано в секции [маршрутизации](#маршрутизация))

#### Экшены  

Классы контроллеров содержат методы - экшены.  
Чтобы создать экшен, необходимо добавить суффикс **`Action`** к имени метода. В контроллере [src/Controllers/Home.php](src/Controllers/Home.php), доступном для образца, таким образом определен экшен `index`  

#### Параметры маршрутов  

В контроллерах доступны параметры маршрута (для примера: параметр **id**). Для доступа к ним в экшенах необходимо обратиться к свойству `$this->route_params`

#### Экшен-фильтры

Контроллеры могут иметь методы-фильтры **before** и **after**. Эти методы вызываются до и после **каждого** экшена в контроллере. 
Вызов экшен-фильтров осуществлен через магический метод `__call` определенный в [BaseController](src/Controllers/BaseController.php).

Экшен-фильтры могут быть очень полезны, например, для аутентификации, когда необходимо убедиться, что пользователь совершил логин до того как вызывать необходимый экшен.

Добавление экшен фильтров опционально.  
Для **before-фильтра** добавление выглядит так:
```php
/**
 * Before-фильтр. При переопределении в наследнике может возвращать false.
 * Для предовращения исполнения экшена обозначенного маршрутизатором.
 */
protected function before()
{
  // ...
}
```

Добавление **after-фильтра**:

```php
/**
 * After-фильтр. Выполняется после обозначенного маршрутизатором экшена.
 */
protected function after()
{
  // ...
}
```

### Представления

В PHP On Rails представления помещаются в директорию `src/Views`.  
Основным форматом для представлений выбран шаблонизатор [Twig](https://twig.symfony.com/). Выбор был сделан на основе удобства и гибкости данного движка.  
Twig предоставляет более безопасные (с защитой от XSS) и простые шаблоны с возможностью наследования.  

Пример рендера представления с передачей в него данных:

```php
BaseView::renderTemplate('Home/index', [
    'name' => 'John Doe',
    'colors' => ['red', 'green', 'blue'],
]);
```

Образец шаблона представления находится в [src/Views/Home/index.html.twig](src/Views/Home/index.html.twig), данный шаблон наследуется от базового шаблона [src/Views/base.html.twig](src/Views/base.html.twig).

### Модели

В PHP On Rails модели наследуются от класса `BaseModel` и используют [PDO](http://php.net/manual/ru/book.pdo.php) для доступа к БД.  
Модели хранятся в директории `src/Models`. В качестве образца доступна модель [src/Models/User.php](src/Models/User.php).

Получить инстанс подключения к базе данных с помощью PDO:
```php
$db = static::getDB();
```

### Ошибки

Ошибки преобразуются в исключения. Обработчиками обозначены:
```
set_error_handler('Ilyamur\PhpMvc\Service\ErrorHandler::errorHandler');
set_exception_handler('Ilyamur\PhpMvc\Service\ErrorHandler::exceptionHandler');
```

При константе `SHOW_ERRORS` (настраивается в [config.php](config/config.php)) равной `true`, в случае исключения или ошибки в браузер будет выведена полная детализация.   
Если `SHOW_ERRORS` присвоено значение `false` будет показано лишь общее сообщение из шаблонов [404.html.twig](src/Views/404.html.twig) или [500.html.twig](src/Views/500.html.twig) в зависимости от ошибки.  
Детализированная информация в данном случае будет логироваться в директории `logs/`.


## Направления для дальнейшего развития

Не смотря на то, что фреймворк протестирован и находится в полностью работоспособном состоянии, очевидны места для дальнейшего улучшения.  
Видимый на данный момент To-Do List:
- Переработка в сторону DI класса `BaseModel`. Доступные на данный момент модели тестируются не лучшим образом.
- Альтернатива методу `__call` в классе `BaseController`. Данное возможное улучшение позволит коду стать более прозрачным.
