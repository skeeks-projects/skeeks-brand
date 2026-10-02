// Rasterize the code-native SVG lockups and create a review sheet. Requires sharp.
const fs=require('node:fs'),path=require('node:path');
const sharp=require('sharp');
const root=__dirname,manifest=JSON.parse(fs.readFileSync(path.join(root,'manifest.json')));
(async()=>{
let cells=[];
for(const [i,m] of manifest.entries()){
 const file=path.join(root,m.product,`logo-${m.variant}.svg`);
 for(const h of [64,128,256]){
  const png=await sharp(file,{density:300}).resize({height:h}).png().toBuffer();
  fs.writeFileSync(path.join(root,m.product,`logo-${m.variant}-${h}h.png`),png);
  await sharp(png).webp({lossless:true}).toFile(path.join(root,m.product,`logo-${m.variant}-${h}h.webp`));
 }
 const row=Math.floor(i/2),col=i%2,x=24+col*620,y=78+row*190;
 const vector=fs.readFileSync(file).toString('base64');
 cells.push(`<rect x="${x}" y="${y}" width="596" height="166" rx="14" fill="${col?'#f5f6f8':'#141b25'}"/><text x="${x+22}" y="${y+25}" font-size="14" font-family="Arial" fill="${col?'#626b79':'#9da8b9'}">${m.label} · ${col?'для светлого фона':'для тёмного фона'}</text><image x="${x+22}" y="${y+45}" width="${Math.min(m.width,552)}" height="110" preserveAspectRatio="xMinYMid meet" href="data:image/svg+xml;base64,${vector}"/>`);
}
const preview=`<svg xmlns="http://www.w3.org/2000/svg" width="1268" height="856"><rect width="1268" height="856" fill="#252d39"/><text x="28" y="44" fill="white" font-size="27" font-family="Arial">SkeekS · логотипы продуктов</text>${cells.join('')}</svg>`;
await sharp(Buffer.from(preview)).png().toFile(path.join(root,'preview.png'));
})();
