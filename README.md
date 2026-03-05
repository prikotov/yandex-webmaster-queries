# Yandex Webmaster Queries

> Поисковые запросы из Яндекс.Вебмастера для SEO-анализа

Skill для получения популярных поисковых запросов с метриками:
- Показы, клики, CTR
- Средняя позиция показа и клика
- Сортировка по любой метрике

## Установка

### 1. Установите yandex-webmaster-core

```bash
cd /путь/к/проекту
mkdir -p .opencode/skills
cd .opencode/skills
git clone https://github.com/prikotov/yandex-webmaster-core.git
```

### 2. Установите yandex-webmaster-queries

```bash
cd .opencode/skills
git clone https://github.com/prikotov/yandex-webmaster-queries.git
```

### 3. Настройте конфигурацию

Создайте `yandex_webmaster_config.json` в корне проекта (см. [yandex-webmaster-core](https://github.com/prikotov/yandex-webmaster-core)).

## Использование

```bash
php .opencode/skills/yandex-webmaster-queries/queries.php [опции] [дата_от] [дата_до]
```

### Опции

| Опция | Сокращение | Описание | По умолчанию |
|-------|------------|----------|--------------|
| `--sort` | `-s` | Поле сортировки: `impressions`, `clicks`, `ctr`, `position` | `impressions` |
| `--order` | `-o` | Направление: `asc`, `desc` | `desc` |
| `--limit` | `-l` | Лимит записей | все |
| `--host` | `-h` | ID сайта (например, `https:site.ru:443`) | из конфига |

### Примеры

```bash
# Топ-10 запросов по показам
php .opencode/skills/yandex-webmaster-queries/queries.php -l 10

# Топ-20 запросов для конкретного сайта
php .opencode/skills/yandex-webmaster-queries/queries.php -l 20 --host "https:example.com:443"

# Запросы с лучшим CTR
php .opencode/skills/yandex-webmaster-queries/queries.php -s ctr -l 15

# Запросы с худшими позициями (для оптимизации)
php .opencode/skills/yandex-webmaster-queries/queries.php -s position -o asc -l 20

# За период
php .opencode/skills/yandex-webmaster-queries/queries.php -l 10 2025-01-01 2025-01-31
```

## Результат

Отчёты сохраняются в `yandex_webmaster_reports/YYYY-MM-DD/`:
- `queries_YYYY-MM-DD_HH-MM-SS.csv`
- `queries_YYYY-MM-DD_HH-MM-SS.md`

### Поля в отчете

| Поле | Описание |
|------|----------|
| `query` | Поисковый запрос |
| `impressions` | Показы |
| `clicks` | Клики |
| `ctr` | CTR (%) |
| `position` | Средняя позиция показа |

## SEO-анализ

### Приоритеты оптимизации

- **Высокие показы + низкий CTR** → улучшить title/description сниппета
- **Позиции 5-15** → близки к топу, точечная оптимизация
- **Позиции 10-20 с кликами** → скрытый потенциал роста
- **Позиции 20-50 с показами** → нужны новые страницы или контент

### Быстрые победы

1. Запросы на позициях 5-10 с хорошими показами →最容易 попасть в топ
2. Запросы с высоким CTR но низкой позицией → алгоритм уже любит ваш сниппет

## Требования

- PHP 7.4+
- [yandex-webmaster-core](https://github.com/prikotov/yandex-webmaster-core)
