const { test, expect } = require('@playwright/test');

const urls = [
  'https://xpatly.eu/',
  'https://xpatly.eu/listings',
  'https://xpatly.eu/login',
];

test('public pages load with non-empty titles', async ({ page }) => {
  for (const url of urls) {
    const response = await page.goto(url, { waitUntil: 'domcontentloaded' });
    expect(response && response.ok()).toBeTruthy();
    await expect(page.locator('body')).toBeVisible();
    const title = await page.title();
    expect(title.length).toBeGreaterThan(0);
  }
});
