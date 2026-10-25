import {test,expect} from '@playwright/test';

// Read-only verification against the configured local database. No fixtures or resets.
test('repaired MySQL catalogue is returned by Laravel and displayed by React',async({page,request})=>{
 const response=await request.get('http://127.0.0.1:8000/api/tours');
 expect(response.status()).toBe(200);
 const tours=await response.json();expect(tours.length).toBeGreaterThan(0);
 expect(tours.every(tour=>tour.published===true)).toBe(true);
 const errors=[];page.on('pageerror',error=>errors.push(error.message));
 await page.goto('/');await expect(page.locator('.hero')).toBeVisible();await expect(page.locator('.card')).toHaveCount(3);
 await page.goto('/tours');await expect(page.locator('.card')).toHaveCount(tours.length);
 await expect(page.locator('.card h3').first()).toHaveText(tours[0].title.fr);
 const image=page.locator('.card img').first();await image.scrollIntoViewIfNeeded();await expect.poll(()=>image.evaluate(img=>img.naturalWidth)).toBeGreaterThan(0);
 await page.screenshot({path:'../docs/screenshots/database-repair-tours.png'});
 await page.locator('.card').first().click();await expect(page.locator('.mw-tour-hero h1')).toHaveText(tours[0].title.fr);
 expect(errors).toEqual([]);
});
