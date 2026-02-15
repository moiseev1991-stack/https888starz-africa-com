const fs = require('fs');
const path = require('path');
const sql = fs.readFileSync(path.join(__dirname, '..', 'wp_gugum.sql'), 'utf8');

// Find all INSERT INTO TVXFZYUMh_posts rows; post_name is 12th field. Rows are (id, author, date, date_gmt, content, title, excerpt, status, comment_status, ping_status, password, post_name, ...)
// Split by "),(" to get rows, but content has "),(" - so we need a different approach.
// Look for pattern: (ID, 1, 'date' and then later we have , 'slug', '', 'publish' or , 'slug', '', 'draft'
const targetIds = [13, 14, 15, 1198, 1201, 1203, 1205, 1207, 1209, 1211, 1213, 1216, 1219, 1221];
const slugs = ['privacy-policy', 'about', 'contacts', 'terms', 'responsible', 'cookies', 'aml', 'kyc', 'self-exclusion', 'dispute-resolution', 'fairness-rng-testing-methods', 'accounts-withdrawals-and-bonuses'];

// Simpler: search for (1198, 1, and then find the next occurrence of ', 'page') and work backwards for post_name
// In MySQL dump format: ..., 'post_name', 'to_ping', 'pinged', 'modified', 'modified_gmt', '', parent, 'guid', order, 'post_type', ...
// So after post_content (huge), we have: 'title', 'excerpt', 'status', ... then 'post_name'.
// Regex: for row starting with (1198, extract until 'page' and get the quoted string before 'page' - that's post_type. Before that we need post_name which is 5 fields before post_type? Actually order is: post_name, to_ping, pinged, post_modified, post_modified_gmt, post_content_filtered, post_parent, guid, menu_order, post_type. So post_name is 9 fields before post_type. Hard to regex.
// Just grep for known slugs in context of IDs.
const wantedSlugs = ['about', 'contacts', 'terms', 'responsible', 'privacy-policy', 'self-exclusion', 'dispute-resolution', 'fairness-rng-testing-methods', 'accounts-withdrawals-and-bonuses'];
wantedSlugs.forEach(slug => {
  const idx = sql.indexOf("'" + slug + "'");
  if (idx !== -1) {
    const snippet = sql.substring(Math.max(0, idx - 200), idx + 100);
    const idMatch = snippet.match(/\((\d+),\s*1,/);
    if (idMatch) console.log('slug', slug, '-> ID', idMatch[1]);
  }
});
