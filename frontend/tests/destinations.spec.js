import {test,expect} from '@playwright/test';
const names=['Fès','Tanger','Casablanca'];
for(const width of [1440,390])test(`only three destinations and matching photos at ${width}px`,async({page,request})=>{
 await page.setViewportSize({width,height:1000});
 const response=await request.get('http://127.0.0.1:8000/api/destinations');expect(response.ok()).toBe(true);expect((await response.json()).map(d=>d.name)).toEqual(names);
 for(const route of ['/','/destinations']){
  await page.goto(route);const grid=page.locator('.destinations-public');await expect(grid.locator('h3')).toHaveText(names);await expect(grid.locator('.destination')).toHaveCount(3);
  await grid.scrollIntoViewIfNeeded();
  for(let i=0;i<3;i++){const card=grid.locator('.destination').nth(i);await expect(card).toHaveAttribute('href','/tours?destination='+encodeURIComponent(names[i]));await expect(card.locator('img')).toHaveAttribute('alt',new RegExp(names[i]));await expect.poll(()=>card.locator('img').evaluate(img=>img.naturalWidth)).toBeGreaterThan(0);}
  expect(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth)).toBe(true);
  if(route==='/destinations')await page.screenshot({path:`../docs/screenshots/destinations-${width}.png`,fullPage:true});
 }
 await page.locator('.destinations-public .destination').first().click();await expect(page).toHaveURL(/destination=F%C3%A8s/);await expect(page.locator('.card').first()).toBeVisible();
 await page.goto('/');await expect(page.locator('.search select[name=destination] option')).toHaveText(['Toutes les destinations',...names]);
});
