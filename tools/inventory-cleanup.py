"""Read-only inventory and verified local source snapshot before maintenance.

Dependency trees, builds and database/runtime data remain on disk. They are
inventoried but excluded from the source ZIP; this is not a database backup.
"""
import hashlib
import json
import os
from pathlib import Path
from datetime import datetime
import zipfile

ROOT = Path(__file__).resolve().parent.parent
OUT = ROOT / 'backend/storage/app/private/maintenance'
OUT.mkdir(parents=True, exist_ok=True)
stamp = datetime.now().strftime('%Y%m%d-%H%M%S')
archive = OUT / f'sources-before-cleanup-{stamp}.zip'
excluded = ('frontend/node_modules/', 'backend/vendor/', 'frontend/dist/',
            '.local-mysql/', 'backend/storage/', 'backend/bootstrap/cache/',
            'tools/.test-results/', '.git/')
files = []
with zipfile.ZipFile(archive, 'w', zipfile.ZIP_DEFLATED, compresslevel=6) as snapshot:
    for folder, directories, names in os.walk(ROOT, followlinks=False):
        directories[:] = sorted(d for d in directories if not (Path(folder) / d).is_symlink()
                                and Path(folder) / d != OUT)
        for name in sorted(names):
            file = Path(folder) / name
            if file.is_symlink():
                continue
            rel = file.relative_to(ROOT).as_posix()
            stat = file.stat()
            record = {'path': rel, 'bytes': stat.st_size, 'mtime_ns': stat.st_mtime_ns}
            private_media = ('backend/storage/app/private/media-sources/',
                             'backend/storage/app/private/archived-media/')
            if not rel.startswith(excluded) or rel.startswith(private_media):
                data = file.read_bytes()
                record['sha256'] = hashlib.sha256(data).hexdigest()
                snapshot.writestr(rel, data)
                record['backed_up'] = True
            else:
                record['backed_up'] = False
            files.append(record)
with zipfile.ZipFile(archive) as snapshot:
    if snapshot.testzip() is not None:
        raise RuntimeError('Invalid backup ZIP')
    for record in files:
        if record['backed_up'] and hashlib.sha256(snapshot.read(record['path'])).hexdigest() != record['sha256']:
            raise RuntimeError('Backup mismatch: ' + record['path'])
manifest = OUT / f'inventory-{stamp}.json'
manifest.write_text(json.dumps({'archive': archive.relative_to(ROOT).as_posix(),
    'archive_sha256': hashlib.sha256(archive.read_bytes()).hexdigest(),
    'excluded_from_source_snapshot': excluded, 'files': files}, ensure_ascii=False, indent=2), encoding='utf-8')
totals = {}
for record in files:
    group = record['path'].split('/')[0]
    row = totals.setdefault(group, {'files': 0, 'bytes': 0})
    row['files'] += 1
    row['bytes'] += record['bytes']
print(json.dumps({'manifest': str(manifest), 'archive': str(archive),
                  'backed_up_files': sum(x['backed_up'] for x in files),
                  'archive_bytes': archive.stat().st_size, 'totals': totals}, ensure_ascii=False, indent=2))
