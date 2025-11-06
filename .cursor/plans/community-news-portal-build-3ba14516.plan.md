<!-- 3ba14516-d589-4437-b1b6-263eab2894f0 f9e1e815-f3a5-4c7b-8377-9c62cd6a89de -->
# Social Share Tracking System Implementation

## Database Schema

1. Create migration `create_social_share_clicks_table.php`:

- `id` (primary key)
- `platform` (string/enum: facebook, twitter, whatsapp, telegram, email) - flexible for future additions
- `page_url` (string) - stores the shared page URL
- `page_type` (string, nullable) - 'home' or 'news' for filtering
- `news_post_id` (foreign key, nullable) - link to news post if applicable
- `ip_address` (string, nullable) - for analytics
- `user_agent` (string, nullable) - for analytics
- `created_at`, `updated_at` timestamps
- Indexes on `platform`, `page_type`, `created_at` for query performance

## Backend Implementation

2. Create `SocialShareClick` model (`src/app/Models/SocialShareClick.php`):

- Define fillable fields
- Relationships: `belongsTo(NewsPost::class)` (nullable)
- Constants for platform types: FACEBOOK, TWITTER, WHATSAPP, TELEGRAM, EMAIL
- Scope methods: `byPlatform()`, `byDateRange()`, `byPageType()`

3. Create API endpoint for tracking clicks:

- Route: `POST /api/social-share/track` in `src/routes/api.php` or web routes
- Controller method: `SocialShareController@track`
- Validation: platform (required, in:facebook,twitter,whatsapp,telegram,email), page_url (required, url), page_type (nullable), news_post_id (nullable)
- Store click data with request metadata (IP, user agent)
- Return JSON response

4. Create `SocialShareController` (`src/app/Http/Controllers/SocialShareController.php`):

- `track()` method for API endpoint
- `analytics()` method for admin dashboard (with filtering)

5. Create admin analytics route:

- Add to `src/routes/web.php`: `Route::get('/admin/analytics', [SocialShareController::class, 'analytics'])->name('admin.analytics')` inside admin middleware group

## Frontend Share Buttons

6. Create Livewire component `SocialShareButtons` (`src/app/Livewire/SocialShareButtons.php`):

- Properties: `$pageUrl`, `$pageTitle`, `$pageType`, `$newsPostId` (nullable)
- Method: `trackShare($platform)` - calls API endpoint via JavaScript fetch
- Render method returns view

7. Create Blade view `src/resources/views/livewire/social-share-buttons.blade.php`:

- 5 share buttons with icons: Facebook, X/Twitter, WhatsApp, Telegram, Email
- Each button opens share dialog and triggers tracking via JavaScript
- Use DaisyUI button styling
- JavaScript function to call `/api/social-share/track` endpoint

8. Add share buttons to home page:

- Update `src/resources/views/home.blade.php`
- Include `@livewire('social-share-buttons', ['pageUrl' => url('/'), 'pageTitle' => 'Community News Portal', 'pageType' => 'home'])`

9. Update news article page:

- Update `src/resources/views/news/show.blade.php`
- Replace existing share buttons with `@livewire('social-share-buttons', ['pageUrl' => request()->fullUrl(), 'pageTitle' => $post->title, 'pageType' => 'news', 'newsPostId' => $post->id])`

## Admin Analytics Dashboard

10. Install Chart.js via npm:

- Add to `src/package.json`: `"chart.js": "^4.4.0"`
- Run `npm install` in container

11. Create admin analytics view `src/resources/views/admin/analytics.blade.php`:

- Page title: "Social Share Analytics"
- Filter section: Date range picker, Platform dropdown (all platforms + "All"), Page type filter
- Chart container for main graph (bar/line chart)
- Summary cards: Total shares, Shares by platform, Top shared pages
- Include Chart.js CDN or bundled version

12. Update `SocialShareController@analytics`:

- Accept filter parameters: `start_date`, `end_date`, `platform`, `page_type`
- Query `SocialShareClick` with filters
- Prepare data for Chart.js:
- Shares by platform (for pie/bar chart)
- Shares over time (line chart with date grouping)
- Top shared pages (table)
- Return view with data

13. Add analytics link to admin navigation:

- Update `src/resources/views/layouts/app.blade.php` navbar
- Add "Analytics" link in admin menu section

## JavaScript Implementation

14. Create share tracking JavaScript (`src/resources/js/social-share.js`):

- Function `trackSocialShare(platform, pageUrl, pageType, newsPostId)`
- Makes POST request to `/api/social-share/track`
- Handles errors gracefully
- Called when share button is clicked

15. Update `src/resources/js/app.js`:

- Import/register social-share.js functions
- Ensure it's available globally or via Livewire

## Validation & Security

16. Create Form Request `src/app/Http/Requests/TrackSocialShareRequest.php`:

- Validate platform (required, in:facebook,twitter,whatsapp,telegram,email)
- Validate page_url (required, url, max:2048)
- Validate page_type (nullable, in:home,news)
- Validate news_post_id (nullable, exists:news_posts,id)

17. Add CSRF protection to API route (if using web routes) or use API token

## Testing

18. Create feature test `src/tests/Feature/SocialShareTrackingTest.php`:

- Test share click tracking API endpoint
- Test admin analytics page access (admin only)
- Test filtering functionality

## Documentation

19. Update README.md:

- Add section about social share tracking feature
- Document admin analytics access

### To-dos

- [ ] Create migration for social_share_clicks table with flexible schema (platform, page_url, page_type, news_post_id, timestamps, indexes)
- [ ] Create SocialShareClick model with relationships, constants, and scope methods
- [ ] Create API endpoint POST /api/social-share/track for tracking share clicks with validation
- [ ] Create Livewire SocialShareButtons component with 5 buttons (Facebook, X, WhatsApp, Telegram, Email)
- [ ] Add social share buttons to home page and news article pages using Livewire component
- [ ] Install Chart.js via npm and include in admin analytics page
- [ ] Create admin analytics controller method and view with Chart.js graphs showing share data
- [ ] Implement filtering by date range, platform, and page type in admin analytics
- [ ] Create Form Request for share tracking validation and add security measures
- [ ] Add Analytics link to admin navigation menu