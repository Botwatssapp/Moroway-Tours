from pathlib import Path
from PIL import Image, ImageOps, ImageDraw
import json, hashlib, html
ROOT=Path(__file__).resolve().parents[1]
staged=ROOT/'backend/storage/app/private/media-sources/manifest.json'
records=json.loads((staged if staged.exists() else ROOT/'backend/database/data/verified-photos.json').read_text(encoding='utf8'))
output=ROOT/'frontend/public/assets/photos';output.mkdir(parents=True,exist_ok=True)
manifest=[];sheet=Image.new('RGB',(1050,300*((len(records)+2)//3)), '#faf8f2');draw=ImageDraw.Draw(sheet)
for index,row in enumerate(records):
 with Image.open(ROOT/row['original_file']) as source:
  image=ImageOps.exif_transpose(source).convert('RGB');w,h=image.size
  variants=[]
  for width in [480,960,1600,2200]:
   if width>w:continue
   size=(width,round(h*width/w));scaled=image.resize(size,Image.Resampling.LANCZOS)
   name=f"{row['id']}-{width}.webp";scaled.save(output/name,'WEBP',quality=82,method=6)
   variants.append({'width':width,'height':size[1],'src':'/assets/photos/'+name,'bytes':(output/name).stat().st_size})
  main=next((v for v in variants if v['width']==1600),variants[-1])
  row.update({'path':main['src'],'width':main['width'],'height':main['height'],'src_set':', '.join(v['src']+' '+str(v['width'])+'w' for v in variants),'variants':variants,'position_css':'50% 50%','license_url':row['license_url'] or row['source_page']+'#Licensing','credit':row['author']+' — '+row['license'],'credit_url':row['source_page'],'fallback':'/assets/destinations/'+{'Fès':'fes','Tanger':'tanger','Casablanca':'casablanca'}[row['city']]+'.jpg','changes':'Orientation EXIF appliquée ; redimensionnement et compression WebP ; aucun contenu généré ou ajouté.'})
  manifest.append(row)
  thumb=ImageOps.fit(image,(330,250),Image.Resampling.LANCZOS);x=(index%3)*350;y=(index//3)*300;sheet.paste(thumb,(x,y));draw.text((x+8,y+255),row['id'],fill='#203b35')
(ROOT/'frontend/src/assets/photos.json').write_text(json.dumps(manifest,ensure_ascii=False,indent=2),encoding='utf8')
(ROOT/'backend/database/data/verified-photos.json').write_text(json.dumps(manifest,ensure_ascii=False,indent=2),encoding='utf8')
sheet.save(ROOT/'docs/screenshots/verified-photo-contact-sheet.jpg',quality=90)
credits='<!doctype html><html lang="fr"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Crédits photographiques — MOROWAY TOURS</title><style>body{font:16px/1.6 system-ui;max-width:1000px;margin:40px auto;padding:0 20px;color:#203b35;background:#faf8f2}article{border-bottom:1px solid #ccd1c4;padding:25px 0}img{width:240px;height:160px;object-fit:cover}</style><h1>Crédits photographiques</h1><p>Photographies réelles de Fès, Tanger et Casablanca. Les licences ci-dessous permettent la réutilisation commerciale dans le respect de leurs conditions, notamment attribution et partage à l’identique lorsque requis.</p>'
for row in manifest:
 credits+=f'<article id="{row["id"]}"><img loading="lazy" src="{row["path"]}" alt="{html.escape(row["alt"]["fr"])}"><h2>{html.escape(row["alt"]["fr"])}</h2><p>{html.escape(row["author"])} · <a href="{html.escape(row["source_page"],quote=True)}">Photographie source et localisation</a> · <a href="{html.escape(row["license_url"],quote=True)}">{html.escape(row["license"])}</a></p><p>{row["changes"]}</p></article>'
credits+='<p>Les trois photos JPEG antérieures sont conservées uniquement comme solutions de repli. Leurs crédits figurent sur <a href="/assets/destinations/credits.html">la page des crédits initiaux</a>.</p></html>'
(ROOT/'frontend/public/photo-credits.html').write_text(credits,encoding='utf8')
original_total=sum((ROOT/r['original_file']).stat().st_size for r in manifest);webp_total=sum((ROOT/'frontend/public'/r['path'].lstrip('/')).stat().st_size for r in manifest)
print(json.dumps({'photos':len(manifest),'source_bytes':original_total,'default_webp_bytes':webp_total,'reduction_percent':round(100*(1-webp_total/original_total),1),'variants':sum(len(r['variants']) for r in manifest)}))
