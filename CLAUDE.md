# CLAUDE.md — Инструкции для Claude Code

## Проект

Laravel-приложение (`ourcrm_admin`). Репозиторий: `origamiv/ourcrm_admin`.

## Ветки

- Разработка ведётся на ветке `claude/settings-json-permissions-aj0yj` (или другой ветке, назначенной для сессии).
- Целевая ветка для PR — `master`.

## Рабочий процесс Git

1. **Разрабатывай** все изменения на назначенной ветке (`claude/...`).
2. **Коммить** с понятными сообщениями на английском языке.
3. **Push** выполняй командой:
   ```bash
   git push -u origin <branch-name>
   ```
4. **После успешного push** — создай Pull Request в ветку `master`:
   ```bash
   gh pr create --base master --head <branch-name> --title "<заголовок>" --body "<описание>"
   ```
5. **После создания PR** — вызови метод деплоя и выдай его ответ:
   ```bash
   curl -s "https://install.our24.ru/merge/admin/master?from=<branch-name>"
   ```
   Результат ответа этого запроса нужно показать пользователю.

## Правила именования веток

- Ветка всегда начинается с `claude/` и заканчивается идентификатором сессии.
- Пример: `claude/settings-json-permissions-aj0yj`

## Повторные попытки при сетевых ошибках

При ошибках сети (`git push`, `git fetch`, `curl`) повторяй до 4 раз с экспоненциальной паузой: 2с → 4с → 8с → 16с.

## Стек

- PHP / Laravel
- Vite + Tailwind CSS
- Docker (`docker-compose.yml`)

## Запрещено

- Пушить в `master` напрямую.
- Пушить в чужие ветки без явного разрешения.
- Использовать `--no-verify` или пропускать хуки без явной просьбы пользователя.
