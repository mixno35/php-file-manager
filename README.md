# StormGuardian Files

```Все нововведения тестируются в браузере Firefox, Chrome```

В данный момент __StormGuardian Files__ доступен на более чем 6 языках, таких как: ```Английский```, ```Немецкий```, ```Испанский```, ```Французский```, ```Португальский```, ```Китайский (упрощенный)```, ```Японский```, ```Хинди```, ```Русский```, ```Белорусский```, ```Украинский```.

__StormGuardian Files__ (название не окончательное) - это веб-файловый менеджер, который упрощает взаимодействие с файлами на вашем сервере или хостинге. Для корректной работы предоставьте разрешение _**777**_ для __StormGuardian Files__.

Для авторизации под Администратором, используйте: ```Логин: admin``` ```Пароль: admin```. При использовании __StormGuardian Files__ на вашем сервере или хотинге, рекомендуется изменить пароль и логин - ```/php/class/CheckSession.php``` > ```SESSION_USERS```.

Для корректной работы __StormGuardian Files__ рекомендуется версия PHP ```7.4``` и выше.


#### ГОТОВО:
Здесь будут функции, которые уже готовы на стороне клиента(готово - :ballot_box_with_check:, не готово - :blue_square:) и/или на строне бекенда(готово - :white_check_mark:, не готово - :green_square:).
- :ballot_box_with_check::white_check_mark: Предпросмотр директорий
- :ballot_box_with_check::white_check_mark: Просмотр файлов и директорий
- :ballot_box_with_check::white_check_mark: Удаление файлов и директорий - <kbd>Ctrl</kbd> <kbd>Delete</kbd>
- :ballot_box_with_check::white_check_mark: Создание файла - <kbd>Ctrl</kbd> <kbd>Alt</kbd> <kbd>F</kbd>
- :ballot_box_with_check::white_check_mark: Создание директории - <kbd>Ctrl</kbd> <kbd>Alt</kbd> <kbd>D</kbd>
- :ballot_box_with_check: Мультивыделение
- :ballot_box_with_check::white_check_mark: Просмотр информации о файле и директории
- :ballot_box_with_check::white_check_mark: Поиск
- :ballot_box_with_check::white_check_mark: Настройки
- :ballot_box_with_check::white_check_mark: Медиаплеер (для воспроизведения аудио и видео файлов)
- :ballot_box_with_check::white_check_mark: Предпросмотр шрифтов
- :ballot_box_with_check: Мобильная версия
- :ballot_box_with_check::green_square: Загрузка файлов


#### В РАЗРАБОТКЕ:
Здесь будут функции, которые находятся в разработке (разрабатывается - :green_circle:, отложено - :white_circle:).
- :green_circle: Изменение разрешений
- :white_circle: Перенос файлов
- :white_circle: Сохранение изменений в файлах
- :white_circle: Управление MySQL (аналог phpMyAdmin)
- :white_circle: Редактор пользователей __StormGuardian Files__

[Предложить идею](https://t.me/mixno35)

### Изменения за 07.12.2024:
- Улучшена безопасность при взаимодействии с командами (бекенд-функциями) и переработана система проверки авторизации.
- Добавлены два новых языка: русский и белорусский.
- Улучшено взаимодействие с мобильными устройствами.
- Исправлены некоторые ошибки в отображении интерфейса.
- Доработаны привилегии для пользователей; теперь они влияют на доступность возможностей.
