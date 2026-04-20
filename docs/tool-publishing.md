# Tool Publishing Workflow

End-to-end system for researching, authoring, and publishing new AI tools on
`elarquitecto.ai`. Markdown files in `storage/tools/` are the source of truth;
the DB is a derived cache that stays in sync via `php artisan tools:scan`.

## The flow

1. **Author locally** — paste `publish tool: {Name} — {URL}` to Claude. The
   tool-publishing skill (`.claude/skills/tool-publishing/SKILL.md`) runs the
   7-phase playbook: research, validation, monetization discovery, Spanish
   content drafting, file creation, verification, handoff.
2. **Review** — the playbook never sets `published_at`. You review the
   generated `storage/tools/{slug}.md`, edit as needed, set `published_at`
   when ready.
3. **Commit** — commit the markdown file + featured image (if present).
4. **Push** — Forge picks up the push and runs the deploy script, which
   includes `php artisan tools:scan`. Production DB is now in sync.

## Commands

```bash
# Create a boilerplate markdown file with frontmatter template
php artisan tools:create "Cursor" --url=https://cursor.sh

# Preview which files would be synced (no DB writes)
php artisan tools:scan --dry-run

# Actually sync markdown files → database
php artisan tools:scan
```

`tools:scan` is idempotent. Running it repeatedly with no file changes is a
no-op (files are skipped based on MD5 hash stored in `research_data.file_hash`).

## Markdown file format

Every file in `storage/tools/*.md` has YAML frontmatter + a markdown body.
See `.claude/skills/tool-publishing/SKILL.md` (Phase 5) for the full frontmatter
schema with examples. Required fields: `title`, `slug`.

### Taxonomy — strict validation

`categories` and `tags` must reference slugs that already exist in the DB.
The scanner rejects unknown slugs with a helpful error listing valid options.
Canonical taxonomy is seeded from `CategoryEnum` and `TagEnum`:

- **Categories:** `ai`, `machine-learning`, `automation`, `agents`, `content-creation`, `programming`
- **Tags by category:**
  - `ai`: `fine-tuning`, `prompt-engineering`
  - `agents`: `multi-agent`, `reasoning-agent`, `planning`
  - `machine-learning`: `deep-learning`, `neural-networks`, `transformers`
  - `automation`: `workflow`, `scripting`, `task-management`
  - `content-creation`: `blog-writing`, `social-media`
  - `programming`: `code-generation`, `debugging`

Adding a new category or tag is a deliberate code change (update the enum +
seeder) — not something that happens implicitly from a markdown file.

## Production deploy — one-time Forge setup

Add `php artisan tools:scan` to the Forge deploy script so pushes
auto-publish tools. No SSH required — edit via Forge's web UI:

**Sites → elarquitecto.ai → Deployments → Deploy Script:**

```bash
cd /home/forge/elarquitecto.ai
git pull origin main
$FORGE_COMPOSER install --no-dev --no-interaction --prefer-dist --optimize-autoloader
$FORGE_PHP artisan migrate --force
$FORGE_PHP artisan tools:scan          # ← add this
$FORGE_PHP artisan config:cache
$FORGE_PHP artisan route:cache
# ...existing restart steps
```

If `tools:scan` fails on production (e.g., a typo in a category slug), the
deploy log will show the exact error. The rest of the app continues to work
— only the new/changed tool fails to sync. Fix locally, push again.

## Images

Featured images go in `storage/tools/assets/{slug}/featured.{ext}` and are
committed to git alongside the markdown. On scan, they're copied into the
`Media` polymorphic store (`storage/app/public/tool/{id}/featured/...`).
The HasMedia trait handles this; don't touch `public/storage/` directly.

## Monetization (affiliate links)

Affiliate URLs go in the `affiliate:` block in the frontmatter. When present:

- The tool page's "Sitio web oficial" button routes through the affiliate URL
- `rel="sponsored noopener noreferrer"` is added for FTC/CONDUSEF compliance
- A disclosure line appears below the links:
  *"Este enlace contiene un identificador de afiliado. Recibo una pequeña
  comisión si compras, sin costo extra para ti."*

If `affiliate.url` is empty or the block is missing, the tool page falls
back to the canonical `website_url` with no disclosure and no `sponsored` rel.

## Idempotency and safety guarantees

- **Hash-based skip:** files whose content hash matches the last-scanned hash
  are skipped (stored in `research_data.file_hash`)
- **Transaction wrap:** each file's sync is wrapped in `DB::transaction`, so
  a partial failure (bad taxonomy, image copy error) rolls back the tool row
  — no orphan rows
- **Taxonomy validation before writes:** unknown slugs are caught before any
  DB writes; the file is reported failed and the DB stays clean
- **Deterministic output:** given the same input files, local and production
  scans converge on the same DB state (different numeric IDs don't matter —
  URLs use slugs, image paths use IDs that are each env's own)

## Tests

`tests/Feature/ToolMarkdownScanTest.php` covers 10 scenarios:
scan creates tool, idempotency, reprocessing on change, taxonomy sync,
unknown category rejected, unknown tag rejected, transaction rollback,
affiliate URL in `display_url`, website fallback, required field validation.

Run with:
```bash
php artisan test --filter ToolMarkdownScanTest
```
