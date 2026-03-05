---
name: yandex-webmaster-queries
description: Поисковые запросы из Яндекс.Вебмастера для SEO-анализа
---

## Когда использовать

- Анализ позиций сайта в поиске
- Поиск запросов с высоким потенциалом
- Определение запросов для оптимизации
- Анализ показов, кликов и CTR

## Запуск

```bash
php .opencode/skills/yandex-webmaster-queries/queries.php [опции] [дата_от] [дата_до]
```

### Параметры дат

- `дата_от` — начало периода (формат: YYYY-MM-DD), по умолчанию 30 дней назад
- `дата_до` — конец периода (формат: YYYY-MM-DD), по умолчанию сегодня

### Опции

| Опция | Сокращение | Описание | Значения | По умолчанию |
|-------|------------|----------|----------|--------------|
| `--site` | | Имя сайта из конфига | имя сайта | default_host |
| `--sort` | `-s` | Поле сортировки | `impressions`, `clicks`, `ctr`, `position` | `impressions` |
| `--order` | `-o` | Направление сортировки | `asc`, `desc` | `desc` |
| `--limit` | `-l` | Лимит записей | число | все записи |

### Примеры

```bash
# Топ-10 запросов по показам (сайт из конфига)
php .opencode/skills/yandex-webmaster-queries/queries.php -l 10

# Для конкретного сайта
php .opencode/skills/yandex-webmaster-queries/queries.php --site task.ai-aid.pro -l 20

# Топ-20 запросов по кликам
php .opencode/skills/yandex-webmaster-queries/queries.php -s clicks -l 20

# Запросы с лучшим CTR
php .opencode/skills/yandex-webmaster-queries/queries.php --sort ctr --limit 15

# Запросы с худшими позициями (для оптимизации)
php .opencode/skills/yandex-webmaster-queries/queries.php -s position -o asc -l 20
```

## Результат

`yandex_webmaster_reports/YYYY-MM-DD/`:
- `yandex_webmaster_queries_YYYY-MM-DD_HH-MM-SS.csv` / `.md`

### Поля в отчете

| Поле | Описание |
|------|----------|
| `query` | Поисковый запрос |
| `impressions` | Показы |
| `clicks` | Клики |
| `ctr` | CTR (%) |
| `position` | Средняя позиция показа |

## SEO-анализ

### Высокий потенциал
- Высокие показы, низкие клики → улучшить сниппет
- Позиции 5-15 → близки к топу, точечная оптимизация
- Позиции 10-20 с кликами → скрытый потенциал
