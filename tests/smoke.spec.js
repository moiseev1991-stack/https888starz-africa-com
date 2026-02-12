/**
 * Smoke tests for static export. Run after building dist.
 * Usage: npx playwright test (from project root)
 * Serves dist on port 3000 if not BASE_URL.
 */

const { test, expect } = require('@playwright/test');

test.describe('Static site smoke', () => {
  test('homepage loads', async ({ page }) => {
    const res = await page.goto('/', { waitUntil: 'domcontentloaded' });
    expect(res.status()).toBe(200);
    await expect(page.locator('body')).toBeVisible();
  });

  test('homepage has no critical 404s', async ({ page }) => {
    const failures = [];
    page.on('requestfailed', req => {
      const u = req.url();
      if (req.failure()?.errorText?.includes('404') && !/google|analytics|gtag/i.test(u))
        failures.push(u);
    });
    await page.goto('/', { waitUntil: 'networkidle' }).catch(() => {});
    expect(failures).toEqual([]);
  });
});
