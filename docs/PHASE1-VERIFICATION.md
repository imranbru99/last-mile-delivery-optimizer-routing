# Phase 1–3 verification checklist

## Commands

```bash
cd /home/imran/project/new/last-mile-delivery-optimizer-route-planning
composer install
cp .env.example .env   # if needed; DB_SOCKET=/tmp/mysql.sock on this host
php artisan key:generate
php artisan migrate:fresh --seed
npm install && npm run build
php artisan serve --host=127.0.0.1 --port=8000
php artisan demo:simulate --loops=5 --sleep=1
php artisan test --compact tests/Feature/Modules tests/Feature/Routing tests/Feature/Tenancy
```

## Verified locally (2026-11-08)

| Check | Result |
|-------|--------|
| 83 modules synced to DB | Yes |
| 3 demo businesses with distinct module counts (61 / 40 / 27) | Yes |
| Module dependency enable/disable rules (Pest) | 4/4 pass |
| Native heuristic optimizer + capacity (Pest) | Pass |
| Tenant isolation on shipments (Pest) | Pass |
| Public `/` and `/modules` | 200 |
| Filament `/business/login`, `/operations/login` | 200 |
| Module Marketplace route | Registered (auth redirect) |
| API `GET /api/v1/health` | JSON ok |
| `demo:simulate` moves drivers | 24 locations/tick |
| Seeded ops data | 460 shipments, 45 routes, 30 drivers, 24 vehicles, 6 hubs |
| All 7 imrandevbd packages required | Yes (3 via `packages/` path for L13) |

## Demo logins (password: `password`)

- `superadmin@demo.test`
- `owner@swiftparcel.test` / `dispatcher@swiftparcel.test` / `driver1@swiftparcel.test`
- `owner@freshbasket.test`
- `owner@urbanchauffeur.test`

Panel URLs: `/super-admin`, `/business`, `/operations`, `/hub`, `/driver`, `/fleet`, `/finance`, `/support`, `/merchant`, `/franchise`, `/auditor`
