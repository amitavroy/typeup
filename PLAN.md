I am looking to build an analytics capturing tool. 
I want that I will have a script tag almost how Google analytics gives me a script to add to my website. 
And then, I would be able to capture analytics data.

The analytics that I want to capture is:
1. When a user does a search on a website
2. When a user clicks on any card from the search result

I would like to generate a unique identifier when a search is done. And so, during the search, I will capture the event and send that to the server. Like USID (unique search id).

And when the search cards are created which are the search results, it should have that usid as a data attribute. Each card will also have a unique id (which can be the content's primary key id or uuid or something which the user can decide. But it has to be unique.)

So, when the cards are show, they have data attributes with the usid and the content id.

On click of any card, the javascript should capture that event and send an event to an api of this laravel application '/api/track-event'. It should send the usid, content_id and also the site's unique id so that I can identify which site send this event data. 

Do note, I can have multiple sites from which events will come. 

So, create a step by step plan for this.

---

## **Phase 1: Database & Backend Setup**

### 1. Database Schema Design
- **sites table**: Store registered websites
  - `id`, `name`, `domain`, `site_key` (unique identifier for each site), `created_at`, `updated_at`
- **searches table**: Track search events
  - `id`, `site_id`, `usid` (unique search id), `search_query`, `timestamp`, `metadata` (JSON for additional data)
- **click_events table**: Track card click events
  - `id`, `site_id`, `usid`, `content_id`, `timestamp`, `metadata` (JSON)
- **Indexes**: Add indexes on `site_key`, `usid`, `timestamp` for fast queries

### 2. Laravel Backend Components
- **Site Management**:
  - Create `Site` model and migrations
  - Admin interface to register new sites and generate unique `site_key`
  - API endpoint to validate site_key

- **API Endpoints**:
  - `POST /api/track-search`: Receive search events (site_key, usid, search_query)
  - `POST /api/track-click`: Receive click events (site_key, usid, content_id)
  - Add CORS middleware to allow cross-origin requests
  - Rate limiting to prevent abuse

- **Analytics Dashboard**:
  - Routes to view analytics per site
  - Search volume metrics
  - Click-through rate (CTR) calculations
  - Popular content tracking

## **Phase 2: JavaScript SDK Development**

### 3. Client-Side Script (`typeup-analytics.js`)
- **Core Functions**:
  - `generateUSID()`: Generate unique search identifier (UUID v4)
  - `trackSearch(query)`: Capture search event and send to API
  - `initializeTracking()`: Set up click event listeners
  - `trackClick(usid, contentId)`: Send click event to API

- **Auto-injection Features**:
  - Automatically add USID to search result cards as `data-usid` attribute
  - Listen for clicks on elements with `data-usid` and `data-content-id`
  - Store current USID in sessionStorage/memory for the session

- **Configuration**:
  - Accept site_key during initialization
  - Configurable API endpoint URL
  - Optional debug mode

### 4. Script Embedding System
- **Script Tag Generation**: Create a simple snippet users can copy:
  ```html
  <script src="https://yourdomain.com/js/typeup-analytics.js"></script>
  <script>
    TypeUpAnalytics.init({ siteKey: 'YOUR_SITE_KEY' });
  </script>
  ```

## **Phase 3: Integration Pattern**

### 5. Website Integration Requirements
- **For Search Functionality**:
  - Call `TypeUpAnalytics.trackSearch(searchQuery)` when search is performed
  - This returns a USID

- **For Search Results**:
  - Add `data-usid` and `data-content-id` attributes to clickable cards:
  ```html
  <div class="search-result-card" data-usid="xxx" data-content-id="yyy">
  ```
  - Or use JavaScript API: `TypeUpAnalytics.attachUSID(cardElement, usid, contentId)`

### 6. API Communication
- Use `fetch()` with POST requests
- Include error handling and retry logic
- Queue events if offline (optional localStorage buffering)
- Send events asynchronously (non-blocking)

## **Phase 4: Features & Refinements**

### 7. Additional Features
- **Privacy & Compliance**:
  - Option to anonymize IP addresses
  - GDPR-compliant data retention policies

- **Performance Optimization**:
  - Batch multiple events before sending
  - Minimize script size (minification)
  - CDN hosting for the JavaScript file

- **Error Handling**:
  - Graceful degradation if API is unavailable
  - Logging mechanism for debugging

### 8. Admin Dashboard
- Site registration and management
- Generate and regenerate site_keys
- View analytics:
  - Total searches per site
  - Click-through rates
  - Most clicked content
  - Time-based graphs

## **Implementation Order**

1. ✅ Create database migrations (sites, searches, click_events)
2. ✅ Build Laravel models and relationships
3. ✅ Create API routes with controllers (`TrackSearchController`, `TrackClickController`)
4. ✅ Add CORS and rate limiting middleware
5. ✅ Build JavaScript SDK with core tracking functions
6. ✅ Create script hosting endpoint/route
7. ✅ Build site registration system
8. ✅ Create admin dashboard for analytics viewing
9. ✅ Testing: Integration tests for API endpoints
10. ✅ Testing: JavaScript SDK on sample website
11. ✅ Documentation: Integration guide for users
