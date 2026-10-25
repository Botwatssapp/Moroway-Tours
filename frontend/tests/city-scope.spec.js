import {test,expect} from '@playwright/test';
const excluded=/Marrakech|Merzouga|Sahara|Chefchaouen|Essaouira|\bAtlas\b|Ouarzazate|Errachidia|Rabat|Todra|Tétouan|Tétouan|Volubilis|Agafay|Ouzoud|Ourika|Dadès|Midelt|Ben Haddou/i;
const citySlugs=['fes','tanger','casablanca'];
const routes=['/','/destinations','/tours','/about','/services','/gallery','/contact','/custom','/students','/faq','/terms','/privacy',...citySlugs.flatMap(c=>['/tours/'+c+'-decouverte','/booking/'+c+'-decouverte'])];
for(const width of [1440,390])for(const lang of ['fr','en'])test(`public city scope ${width}px ${lang}`,async({page,request})=>{
 test.setTimeout(180000);await page.setViewportSize({width,height:1000});await page.emulateMedia({reducedMotion:'reduce'});
 await page.addInitScript(language=>localStorage.setItem('moroway-language',language),lang);
 const errors=[];page.on('pageerror',e=>errors.push(e.message));
 await page.goto('/');await expect(page.locator('.card')).toHaveCount(3);
 for(const route of routes){
  await page.evaluate(path=>{history.pushState({},'',path);dispatchEvent(new PopStateEvent('popstate'))},route);
  if(route.startsWith('/tours/'))await expect(page.locator('.mw-tour-hero h1')).toBeVisible();
  else if(route==='/tours')await expect(page.locator('.card')).toHaveCount(3);
  else if(route.startsWith('/booking/')||['/custom','/students'].includes(route))await expect(page.locator('input[name=name]')).toBeVisible();
  else if(route==='/gallery')await expect(page.locator('.mw-gallery-item')).toHaveCount(9);
  else if(route==='/')await expect(page.locator('.hero h1')).toBeVisible();
  else await expect(page.locator('main')).not.toBeEmpty();
  expect(await page.locator('body').innerText(),route).not.toMatch(excluded);
  for(const link of await page.locator('main a').evaluateAll(links=>links.map(a=>a.getAttribute('href'))))expect(decodeURIComponent(link||''),route).not.toMatch(excluded);
  for(const src of await page.locator('main img').evaluateAll(images=>images.map(i=>i.getAttribute('src'))))expect(src,route).toMatch(/^\/assets\/(?:destinations\/(?:fes|tanger|casablanca)\.jpg|photos\/(?:fes|tanger|casablanca)-[a-z0-9-]+\.webp|moroway[^/]*\.png)$/);
  expect(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth),route).toBe(true);
 }
 await page.goto('/tours?q=Marrakech');await expect(page.locator('.card')).toHaveCount(0);await expect(page.locator('.empty')).toBeVisible();
 await page.goto('/tours?destination=F%C3%A8s');await expect(page.locator('.card')).toHaveCount(1);await expect(page.locator('.card h3')).toContainText('Fès');
 for(const route of ['/tours/marrakech-fes-3','/booking/marrakech-fes-3']){await page.goto(route);await expect(page.locator('h1')).toContainText(lang==='fr'?route.startsWith('/booking')?'indisponible':'introuvable':route.startsWith('/booking')?'unavailable':'not found');expect(await page.locator('main').innerText()).not.toMatch(excluded);}
 await page.goto('/');await expect(page.locator('.card')).toHaveCount(3);await page.locator('.card').first().scrollIntoViewIfNeeded();
 await page.screenshot({path:`../docs/screenshots/city-scope-home-${width}-${lang}.png`,fullPage:true});
 if(width===390){await page.locator('.mw-menu-toggle').click();await expect(page.locator('#nav')).toBeVisible();expect(await page.locator('#nav').innerText()).not.toMatch(excluded);}
 const api=await request.get('http://127.0.0.1:8000/api/tours');expect(api.ok()).toBe(true);const tours=await api.json();expect(tours).toHaveLength(3);expect(JSON.stringify(tours)).not.toMatch(excluded);
 for(const tour of tours){expect((await request.get('http://127.0.0.1:8000/api/tours/'+tour.slug)).ok()).toBe(true);for(const image of tour.images)expect((await request.get(image.path)).ok()).toBe(true);}
 expect(errors).toEqual([]);
});
