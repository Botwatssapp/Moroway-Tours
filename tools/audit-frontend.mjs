// Read-only audit. Uses Babel already installed by the Vite React plugin.
import fs from 'node:fs';
import path from 'node:path';
import {createRequire} from 'node:module';
import {fileURLToPath} from 'node:url';
const root=fileURLToPath(new URL('../',import.meta.url));
const require=createRequire(path.join(root,'frontend/package.json'));
const {parse}=require('@babel/parser');
const traverse=require('@babel/traverse').default;
const relative=file=>path.relative(root,file).replaceAll('\\','/');
const walk=folder=>fs.readdirSync(folder,{withFileTypes:true}).flatMap(entry=>entry.isDirectory()?walk(path.join(folder,entry.name)):[path.join(folder,entry.name)]);
const files=walk(path.join(root,'frontend/src'));
const graph={}, unused=[], packages=new Set();
const resolve=(file,specifier)=>{
 const base=path.resolve(path.dirname(file),specifier);
 const found=[base,...['.js','.jsx','.json','.css','/index.js','/index.jsx'].map(ext=>base+ext)].find(f=>fs.existsSync(f)&&fs.statSync(f).isFile());
 if(!found)throw Error(`Unresolved import ${relative(file)}: ${specifier}`);
 return relative(found);
};
for(const file of files.filter(f=>/\.[jt]sx?$/.test(f))){
 const ast=parse(fs.readFileSync(file,'utf8'),{sourceType:'module',plugins:['jsx']});
 const edges=[];
 traverse(ast,{
  ImportDeclaration(p){const spec=p.node.source.value;if(spec.startsWith('.'))edges.push(resolve(file,spec));else packages.add(spec.startsWith('@')?spec.split('/').slice(0,2).join('/'):spec.split('/')[0]);},
  CallExpression(p){if(p.node.callee.type==='Import')edges.push({dynamic:true,expression:p.node.arguments[0]?.value||'non-literal'});},
  Scope(p){for(const [name,binding] of Object.entries(p.scope.bindings))if(!binding.referenced)unused.push({file:relative(file),name,kind:binding.kind,line:binding.identifier.loc.start.line});}
 });
 graph[relative(file)]=edges;
}
const reachable=new Set();
const visit=file=>{if(reachable.has(file))return;reachable.add(file);for(const edge of graph[file]||[])if(typeof edge==='string')visit(edge);};
visit('frontend/src/main.jsx');
const report={note:'Static evidence only. Dynamic data, Laravel, CSS and deployment references must also be reviewed before deletion.',graph,unusedTopLevelBindings:unused,unreachableSourceFiles:files.map(relative).filter(f=>!reachable.has(f)),runtimePackages:[...packages].sort()};
fs.writeFileSync(path.join(root,'docs/frontend-cleanup-audit.json'),JSON.stringify(report,null,2)+'\n');
console.log(JSON.stringify({unused:report.unusedTopLevelBindings,unreachable:report.unreachableSourceFiles,runtimePackages:report.runtimePackages},null,2));
