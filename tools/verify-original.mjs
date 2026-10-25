import {execFileSync} from 'node:child_process';
import {fileURLToPath} from 'node:url';
// Read archived originals without restoring obsolete HTML into the active tree.
const verify=`import json,hashlib,zipfile
from pathlib import Path
root=Path.cwd()
point=json.loads((root/'backend/storage/app/private/maintenance/restore-point.json').read_text())
archive=root/point['archive']
assert hashlib.sha256(archive.read_bytes()).hexdigest()==point['sha256'],'Restore archive changed'
inventory=json.loads((root/'docs/original-manifest.json').read_text())
with zipfile.ZipFile(archive) as snapshot:
 for row in inventory:
  assert hashlib.sha256(snapshot.read(row['file'])).hexdigest()==row['sha256'],row['file']
print(f"{len(inventory)} archived original files verified unchanged (SHA-256).")
`;
execFileSync('python',['-c',verify],{cwd:fileURLToPath(new URL('../',import.meta.url)),stdio:'inherit'});
