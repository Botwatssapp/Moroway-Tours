import {test,expect} from '@playwright/test';
import photos from '../src/assets/photos.json' with {type:'json'};

test('verified photo registry and Laravel image variants',async({request})=>{
 expect(photos).toHaveLength(9);expect(new Set(photos.map(p=>p.source_sha256)).size).toBe(9);
 const response=await request.get('http://127.0.0.1:8000/api/tours');expect(response.ok()).toBe(true);const tours=await response.json();expect(tours).toHaveLength(3);
 for(const tour of tours){expect(tour.images).toHaveLength(3);for(const image of tour.images){expect(image.city).toBe(tour.start_city);expect(image.width).toBeGreaterThanOrEqual(1600);expect(image.src_set).toContain('480w');expect(image.license).toMatch(/^CC BY/);expect(image.license_url).toMatch(/^https:\/\//);expect(image.alt.length).toBeGreaterThan(15);expect((await request.get(image.path)).ok()).toBe(true);}}
 for(const photo of photos){expect(photo.source_width).toBeGreaterThanOrEqual(photo.width);for(const variant of photo.variants)expect((await request.get(variant.src)).ok()).toBe(true);}
 expect((await request.get('/photo-credits.html')).ok()).toBe(true);
});

for(const width of [1440,390])test(`all photo slots render at ${width}px`,async({page})=>{
 test.setTimeout(180000);await page.setViewportSize({width,height:1000});await page.emulateMedia({reducedMotion:'reduce'});const errors=[];page.on('pageerror',e=>errors.push(e.message));
 const paths=['/','/destinations','/tours','/about','/services','/gallery','/contact','/custom','/students',...['fes','tanger','casablanca'].flatMap(city=>['/tours/'+city+'-decouverte','/booking/'+city+'-decouverte'])];
 for(const path of paths){
  await page.goto(path);if(path==='/')await expect(page.locator('.card')).toHaveCount(3);else if(path==='/tours')await expect(page.locator('.card')).toHaveCount(3);else if(path==='/gallery')await expect(page.locator('.mw-gallery-item')).toHaveCount(9);else if(path.startsWith('/tours/'))await expect(page.locator('.mw-gallery-item')).toHaveCount(3);else if(path.startsWith('/booking/'))await expect(page.locator('.booking-photo')).toBeVisible();else if(path==='/services')await expect(page.locator('.service-photo')).toHaveCount(3);else await expect(page.locator('main img').first()).toBeVisible();
  for(const image of await page.locator('main img').all()){await image.scrollIntoViewIfNeeded();await expect.poll(()=>image.evaluate(img=>img.complete&&img.naturalWidth>0),{timeout:15000}).toBe(true);await expect(image).toHaveAttribute('alt',/.+/);await expect(image).toHaveAttribute('srcset',/\.webp \d+w/);}
  await expect(page.locator('main .photo-unavailable')).toHaveCount(0);expect(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth),path).toBe(true);
  if(['/','/services','/gallery','/booking/casablanca-decouverte'].includes(path)){await page.evaluate(()=>scrollTo(0,0));await page.screenshot({path:'../docs/screenshots/photos-'+(path==='/'?'home':path.split('/').filter(Boolean).join('-'))+'-'+width+'.png',fullPage:true});}
 }
 expect(errors).toEqual([]);
});

test('failed WebP falls back to the same city and total failure keeps a visible frame',async({page})=>{
 await page.route('**/assets/photos/**',route=>route.abort());await page.goto('/tours/tanger-decouverte');const hero=page.locator('.mw-hero-image');await expect(hero).toHaveAttribute('src','/assets/destinations/tanger.jpg');await expect(hero).toHaveAttribute('alt',/Tanger.*remplacement/);await expect.poll(()=>hero.evaluate(img=>img.naturalWidth)).toBeGreaterThan(0);
 await page.route('**/assets/destinations/*.jpg',route=>route.abort());await page.goto('/tours/casablanca-decouverte');const unavailable=page.locator('.mw-hero-image.photo-unavailable');await expect(unavailable).toBeVisible();await expect(unavailable).toContainText('Casablanca');expect((await unavailable.boundingBox()).height).toBeGreaterThan(100);
});
