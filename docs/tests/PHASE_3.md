🧪 Recommended Tests
Since we just touched the Navigation, Settings, and Frontend Rendering, here is a checklist to ensure everything is wired correctly:

1. Menu System Test
Create: Go to Admin > Navigation Menus. Create a new menu named "Main Header" with slug main-menu (this slug is hardcoded in header.php for now).
Populate: Click Manage Links. Add two items:
Label: Home, URL: / (Order: 0)
Label: Test Page, URL: /non-existent-page (Order: 1)
Verify: Go to the homepage. Do you see the links in the top right?
Edit: Change the "Home" label to "Start". Refresh homepage. Does it update?
Delete: Delete the "Test Page" link. Does it disappear?

2. Breadcrumbs Test
Default State: Go to a specific Post (e.g., /hello-world). Do you see breadcrumbs? (They should be off by default if the database option hasn't been set, or off if the default in functions.php is '0').
Enable: Go to Admin > Site Settings. Check "Enable Breadcrumbs". Change the separator to ». Click Save.
Verify: Refresh the Post page. You should see: Home » Hello World.
Home Logic: Go to the Homepage. The breadcrumbs should either be hidden or just show "Home" (depending on logic).

3. Routing & Resilience
404: Visit /random-gibberish. You should see the custom 404 page, and the Menu/Header should still render correctly.
Deep Link: Visit a post. Ensure the CSS loads correctly. (If CSS breaks, BASE_URL might be misconfigured, but our <base> or absolute pathing usually handles this).