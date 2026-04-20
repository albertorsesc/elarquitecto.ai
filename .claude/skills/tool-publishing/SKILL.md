---
name: tool-publishing
description: End-to-end playbook for publishing a new AI tool on elarquitecto.ai. Use whenever the user says "publish tool:", "research tool:", "agregar herramienta", pastes a tool name + URL asking to add it to the site, or invokes /tool. Covers research, validation, monetization discovery, Spanish/LATAM content drafting, local file creation, and sync to production.
---

# Tool Publishing Playbook

This skill fires when Alberto wants to add a new AI tool to `elarquitecto.ai`. The end goal is a draft markdown file at `storage/tools/{slug}.md` that `php artisan tools:scan` can sync to the database, locally first and then in production on deploy.

**Never publish without Alberto's approval.** You draft, he reviews, he sets `published_at`, he commits. You never flip `published_at` from `null` to a real date on your own.

## The 7 phases

Follow them in order. Do not skip. If a phase reveals a blocker, stop and report instead of continuing.

### Phase 1 — Research

Goal: gather enough signal to form an honest opinion of the tool. Parallelize these searches whenever possible — they don't depend on each other:

- `WebSearch`: `{tool} review`, `{tool} vs alternatives`, `{tool} pricing`, `{tool} producthunt`, `{tool} news.ycombinator`
- `WebFetch` the official site and the pricing page to extract canonical info (tagline, feature list, pricing tiers)
- If the tool looks young or controversial: search for funding, team, shutdown rumors, privacy incidents

Capture for the frontmatter:
- Official name (exact capitalization)
- One-line value prop (in your own words, not marketing copy)
- Full pricing tiers with currencies
- Competitors mentioned by reviews
- Recent news (last 6 months)

### Phase 2 — Validation (go / no-go)

Score the tool 1-10 against these five dimensions:

1. **Maturity** — Live product with real users? Pricing page exists? Not vaporware?
2. **LATAM fit** — Works for Spanish speakers? Pricing reasonable in pesos? Solves a LATAM-relevant problem?
3. **Unique angle** — Why this vs. the 10 others in the space? What's the wedge?
4. **Business model clarity** — Can you explain pricing in one sentence?
5. **No red flags** — No scam accusations, no privacy disasters, no paused development, no AI-washing fluff

Write a one-sentence justification per dimension. Report the total score.

**Soft rule:** if score < 7, recommend NOT publishing and explain why. Alberto decides — never refuse outright. Your job is honest signal, not gatekeeping.

### Phase 3 — Monetization (find affiliate program)

Goal: determine if this tool pays affiliates, and if so, capture the exact link Alberto needs.

- `WebSearch`: `{tool} affiliate program`, `{tool} partner program`, `{tool} refer a friend`
- Check common affiliate networks: Impact, PartnerStack, Rewardful, Tolt, GetRewardful, FirstPromoter
- Check the footer of the official site for "Affiliates", "Partners", "Referrals"
- Look at competitor affiliate patterns as a signal

**What to capture:**
- `signup_url` — where Alberto registers
- `commission` — e.g. "20% recurring for 12 months"
- `notes` — payout minimum, cookie duration, any gotchas

**Never fabricate an affiliate URL.** If you find the program but don't have Alberto's referral code, leave `affiliate.url` empty and note in the frontmatter that Alberto needs to sign up and paste his code. The playbook explicitly prefers no monetization over a broken/fake affiliate link.

If no affiliate program exists, say so clearly. The tool can still be published — monetization is optional.

### Phase 4 — Content drafting

Write in Spanish with Alberto's LATAM-aware voice. Reference recent newsletters (storage/newsletters/) for tone calibration. Required sections in the markdown body:

- `## ¿Qué es {Tool}?` — direct explanation of what it does. No marketing fluff.
- `## Características principales` — 3-5 bullets that prove the tool's real capabilities.
- `## ¿Para quién es?` — the specific audience and use case. Be concrete.
- `## Precio` — every tier with the actual numbers. Link to the pricing page.
- `## Mi veredicto` — honest take including at least one weakness. No unqualified praise.
- `## 💡 Tip práctico` — one concrete action the reader can take today.

Avoid: superlatives without evidence, "game changer" / "revolutionary" language, feature lists that read like press releases.

### Phase 5 — Local file creation

Generate the slug: `Str::slug($title)` equivalent (kebab-case, lowercase, no accents).

Write `storage/tools/{slug}.md` with populated YAML frontmatter:

```yaml
---
title: "Cursor"
slug: "cursor"
excerpt: "Editor de código AI-first basado en VS Code"
business_model: "subscription"  # one of: free, freemium, paid, subscription, one_time, open_source

website_url: "https://cursor.sh"
pricing_url: "https://cursor.sh/pricing"
documentation_url: "https://docs.cursor.sh"

# Only include this block if an affiliate program exists AND you have the URL.
# If Alberto still needs to sign up, leave it commented and note it in research.why_include.
affiliate:
  url: "https://cursor.sh/?ref=ALBERTO_CODE"
  program: "Cursor Partner Program"
  commission: "20% recurring for 12 months"
  signup_url: "https://affiliate.cursor.sh"
  notes: "Cookie 30d, min payout $50"

research:
  score: 9
  validated_at: "2026-04-25"
  sources:
    - "https://producthunt.com/posts/cursor"
    - "https://news.ycombinator.com/item?id=37876512"
  alternatives_considered:
    - "Windsurf (Codeium)"
    - "GitHub Copilot"
  why_include: "Market leader in AI coding IDEs, LATAM developer adoption is accelerating, clear wedge on speed and UX vs. Copilot"

meta_title: "Cursor — Editor de código con IA"
meta_description: "Editor AI-first basado en VS Code. Autocompletado contextual, refactors en lenguaje natural, chat con tu código."
meta_keywords: ["IDE", "AI coding", "VS Code"]

categories: ["programming", "ai"]
tags: ["ide", "ai-coding", "vscode-fork"]

is_featured: false
# featured_image: "featured.png"  # drop the file in storage/tools/assets/cursor/ if you have one
published_at: null   # Alberto sets this when ready to publish
timezone: "America/Tijuana"
---

## ¿Qué es Cursor?

...
```

**Featured image handling:**
- If you find a clean official logo/screenshot, download it to `storage/tools/assets/{slug}/featured.png` (or `.jpg`/`.webp` — match the source)
- Uncomment the `featured_image:` line in the frontmatter
- If you don't have a clean image, skip — leave the line commented. Alberto can add later.

**Never set `published_at` yourself.** Leave it `null`. Alberto reviews and publishes.

### Phase 6 — Verify

Run `php artisan tools:scan --dry-run` to confirm the file is detected. Then run without `--dry-run` to upsert.

Expected output:
- ✅ Processed: 1 (or more if you added multiple)
- ⏭️ Skipped (unchanged): previous tools

If the scan reports a failure, read `storage/logs/laravel.log` for the specific error, fix the frontmatter, and rescan.

### Phase 7 — Handoff

Report to Alberto:
1. **Research summary** — score + one-line reason + any red flags
2. **Monetization status** — affiliate found/not found, signup URL if Alberto needs to register
3. **File location** — `storage/tools/{slug}.md` (and assets path if applicable)
4. **Next actions** for Alberto:
   - Review the markdown content
   - Sign up for affiliate program if applicable; paste referral URL into `affiliate.url`
   - Set `published_at` in the frontmatter when ready
   - Commit with message: `Add tool: {Title}`
   - Push — production auto-scans on deploy

## Boundaries

**Do not:**
- Publish without Alberto's explicit review (never set `published_at`)
- Fabricate affiliate URLs (if you don't have the code, leave it empty)
- Inflate the validation score to please Alberto (honest signal > comfortable signal)
- Skip the "Mi veredicto" weakness — every tool has one
- Write generic marketing copy. If you're tempted to use "revolutionary", "game changer", "must-have" — stop and rewrite

**Do:**
- Ask Alberto for his referral code if the affiliate program requires signup
- Flag low scores clearly — don't bury a 5/10 in prose
- Reference existing tools on the site (via `categories`/`tags`) so the library feels cohesive
- Keep the research data for audit trail — future-you will thank present-you

## Artifacts the playbook produces

- `storage/tools/{slug}.md` — the tool content + metadata
- `storage/tools/assets/{slug}/featured.{ext}` — featured image (optional)
- A summary message to Alberto with score, monetization status, and next actions

## Commands reference

```bash
# Create a boilerplate file
php artisan tools:create "Cursor" --url=https://cursor.sh

# Check what would be synced without changes
php artisan tools:scan --dry-run

# Actually sync files to the database
php artisan tools:scan
```

In production, `tools:scan` runs automatically on deploy — no manual step needed.
