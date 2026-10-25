"""Download only reviewed Commons photographs and retain source/licence metadata."""
from pathlib import Path
import urllib.request, urllib.parse, json, re, html, hashlib, time
ROOT=Path(__file__).resolve().parents[1]
FILES=[
 ('fes-gate','Fès','Bab Bou Jeloud (Fes, Marocco).jpg','Bab Bou Jeloud, porte de la médina de Fès','Bab Bou Jeloud, gateway to the medina of Fès'),
 ('fes-bou-inania','Fès','Bou inania DSCF4610.jpg','Cour de la médersa Bou Inania à Fès','Courtyard of Bou Inania Madrasa in Fès'),
 ('fes-qarawiyyin','Fès','Al-Qarawiyyin University, Fes, Morocco - Var 132.jpg','Cour de la mosquée Al-Qarawiyyin à Fès','Courtyard of Al-Qarawiyyin Mosque in Fès'),
 ('tanger-kasbah','Tanger','Kasbah of Tangier (87649).jpg','Architecture de la Kasbah de Tanger','Architecture of the Kasbah in Tanger'),
 ('tanger-seaside','Tanger','Tangier, near the Kasbah and the seaside (65193).jpg','Tanger, entre la Kasbah et le front de mer','Tanger, between the Kasbah and the waterfront'),
 ('tanger-socco','Tanger','Tangier - Grand Socco - 20200906181817.jpg','Place du Grand Socco à Tanger','Grand Socco square in Tanger'),
 ('casablanca-habous','Casablanca','Quartier Habous Casablanca.jpg','Architecture du quartier Habous à Casablanca','Architecture in the Habous district of Casablanca'),
 ('casablanca-hassan-ii','Casablanca','Framing Heritage - Hassan II Mosque.jpg','Mosquée Hassan II à Casablanca, vue à travers un cadre tenu par le photographe','Hassan II Mosque in Casablanca, seen through a frame held by the photographer'),
 ('casablanca-habous-arches','Casablanca','Houbous Casablanca 01.jpg','Arcades du quartier Habous à Casablanca','Arcades in the Habous district of Casablanca'),
]
SOURCE=ROOT/'backend/storage/app/private/media-sources';SOURCE.mkdir(parents=True,exist_ok=True)
records=[]
def fetch(url):
 for attempt in range(3):
  try:
   request=urllib.request.Request(url,headers={'User-Agent':'MorowayToursMediaImporter/1.0 (local asset preparation)'})
   with urllib.request.urlopen(request,timeout=45) as response:return response.read()
  except urllib.error.HTTPError as error:
   if error.code!=429 or attempt==2:raise
   print('Source rate limit; waiting before retry.',flush=True);time.sleep(20)
def plain(value):return html.unescape(re.sub('<[^>]+>','',value or '')).strip()
for key,city,filename,fr,en in FILES:
 cache=SOURCE/(key+'.json')
 if cache.exists():record=json.loads(cache.read_text(encoding='utf8'))
 else:
  query=urllib.parse.urlencode({'action':'query','format':'json','prop':'imageinfo','iiprop':'url|extmetadata|size','titles':'File:'+filename})
  response=json.loads(fetch('https://commons.wikimedia.org/w/api.php?'+query))
  page=next(iter(response['query']['pages'].values()));info=page['imageinfo'][0];meta=info['extmetadata']
  licence=plain(meta.get('LicenseShortName',{}).get('value',''))
  if not any(value in licence for value in ['CC BY','CC0','Public domain']):raise RuntimeError('Unapproved licence: '+filename+' '+licence)
  record={'id':key,'city':city,'filename':filename,'source_page':info['descriptionurl'],'download_url':info['url'],'author':plain(meta.get('Artist',{}).get('value','')),'license':licence,'license_url':meta.get('LicenseUrl',{}).get('value',''),'location_evidence':plain(meta.get('ImageDescription',{}).get('value','')),'source_width':info['width'],'source_height':info['height'],'alt':{'fr':fr,'en':en}}
  cache.write_text(json.dumps(record,ensure_ascii=False,indent=2),encoding='utf8')
 # The Commons summary conflates public-domain architecture with the photograph.
 # The file's Licensing section explicitly licenses Khonsali's photograph CC BY-SA 3.0.
 if key=='fes-qarawiyyin':
  record['license']='CC BY-SA 3.0';record['license_url']='https://creativecommons.org/licenses/by-sa/3.0/'
  record['license_verification']='Photograph: CC BY-SA 3.0; the public-domain tag on the source refers to the depicted architecture.'
  cache.write_text(json.dumps(record,ensure_ascii=False,indent=2),encoding='utf8')
 original=SOURCE/(key+Path(filename).suffix.lower())
 if not original.exists():original.write_bytes(fetch(record['download_url']))
 record['original_file']=str(original.relative_to(ROOT)).replace('\\','/');record['source_sha256']=hashlib.sha256(original.read_bytes()).hexdigest();records.append(record)
 print(key,record['source_width'],record['source_height'],record['license'],flush=True)
 time.sleep(4)
(SOURCE/'manifest.json').write_text(json.dumps(records,ensure_ascii=False,indent=2),encoding='utf8')
