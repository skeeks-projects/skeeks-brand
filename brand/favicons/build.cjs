// Vector favicon adaptation of the SkeekS bulb; the full corporate logo stays unchanged.
const fs = require('node:fs');
const path = require('node:path');
const sharp = require('sharp');
const root = __dirname;
const products = [['skeeks', 'SkeekS', '#8dce42'], ['platform', 'Платформа', '#a6de58'], ['goods', 'Товары', '#efd740'], ['ai', 'AI', '#64dcdb']];
function mark(color, corporate, small = false) {
  const colors = corporate ? ['#ee4d7d','#39afe0','#efd740','#8dce42','#28c3c5'] : Array(5).fill(color);
  const rays = ['M14 53 24 55','M27 26 34 35','M64 12 64 24','M101 26 94 35','M114 53 104 55'];
  return `<rect width="128" height="128" rx="27" fill="#141b25"/><g fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M64 38C49 38 39 49 39 63C39 74 46 81 51 89L51 98Q51 102 56 102H72Q77 102 77 98L77 89C82 81 89 74 89 63C89 49 79 38 64 38Z" stroke="${color}" stroke-width="${small ? 9 : 7}"/><path d="M56 111H72" stroke="${color}" stroke-width="${small ? 7 : 5}"/>${rays.map((d,i)=>`<path d="${d}" stroke="${colors[i]}" stroke-width="${small ? 7 : 5}"/>`).join('')}</g>`;
}
function svg(content, size=128) { return `<svg xmlns="http://www.w3.org/2000/svg" width="${size}" height="${size}" viewBox="0 0 128 128">${content}</svg>`; }
async function main() {
  const tiles=[];
  for(const [key,label,color] of products) {
    const dir=path.join(root,key); fs.mkdirSync(dir,{recursive:true});
    const vector=svg(mark(color,key==='skeeks'));
    fs.writeFileSync(path.join(dir,'favicon.svg'),vector);
    const frames=[];
    for(const size of [16,32,48,120]) {
      const png=await sharp(Buffer.from(svg(mark(color,key==='skeeks',size===16)))).resize(size,size).png().toBuffer();
      fs.writeFileSync(path.join(dir,`favicon-${size}.png`),png);
      if(size!==120) frames.push({size,png});
    }
    const header=Buffer.alloc(6+16*frames.length); header.writeUInt16LE(1,2);header.writeUInt16LE(frames.length,4);
    let offset=header.length;
    frames.forEach(({size,png},i)=>{const p=6+i*16;header[p]=size;header[p+1]=size;header.writeUInt16LE(1,p+4);header.writeUInt16LE(32,p+6);header.writeUInt32LE(png.length,p+8);header.writeUInt32LE(offset,p+12);offset+=png.length;});
    fs.writeFileSync(path.join(dir,'favicon.ico'),Buffer.concat([header,...frames.map(f=>f.png)]));
    const x=40+products.findIndex(p=>p[0]===key)*240;
    tiles.push(`<g transform="translate(${x},54)"><svg x="44" width="128" height="128" viewBox="0 0 128 128">${mark(color,key==='skeeks')}</svg><text x="108" y="162" text-anchor="middle" fill="#f4f6fb" font-family="Arial" font-size="22">${label}</text><rect y="190" width="216" height="56" rx="12" fill="#f4f5f8"/><rect y="256" width="216" height="56" rx="12" fill="#252b36"/>${[206,272].map(y=>[16,32].map((s,i)=>`<image x="${i?126:60}" y="${y+(32-s)/2}" width="${s}" height="${s}" href="data:image/png;base64,${fs.readFileSync(path.join(dir,`favicon-${s}.png`)).toString('base64')}"/>`).join('')).join('')}</g>`);
  }
  const preview=`<svg xmlns="http://www.w3.org/2000/svg" width="1040" height="430"><rect width="1040" height="430" fill="#0d1118"/>${tiles.join('')}<text x="520" y="404" text-anchor="middle" fill="#9da8b9" font-family="Arial" font-size="15">16 и 32 px · светлая и тёмная панель браузера</text></svg>`;
  await sharp(Buffer.from(preview)).png().toFile(path.join(root,'preview.png'));
}
main().catch(e=>{console.error(e);process.exit(1)});
