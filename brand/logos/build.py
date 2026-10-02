"""Rebuild the approved SkeekS header lockups. Requires fonttools."""
from pathlib import Path
import base64, json
from fontTools.ttLib import TTFont
from fontTools.varLib.instancer import instantiateVariableFont
from fontTools.pens.svgPathPen import SVGPathPen

ROOT = Path(__file__).resolve().parent
SOURCE = ROOT / 'sources'
font = TTFont(SOURCE / 'Nunito.ttf')
fonts = {w: instantiateVariableFont(font, {'wght': w}, inplace=False) for w in [400,800]}
bulb = base64.b64encode((SOURCE / 'skeeks-footer-logo.png').read_bytes()).decode()
products = [('skeeks',None,None),('platform','Платформа','#a6de58'),('goods','Товары','#efd740'),('ai','AI','#64dcdb')]

def lettering(text, size, weight, x, baseline, color, tracking=-.025):
    f=fonts[weight]; gs=f.getGlyphSet(); cmap=f.getBestCmap(); scale=size/f['head'].unitsPerEm
    cursor=0; paths=[]
    for c in text:
        glyph=gs[cmap[ord(c)]]; pen=SVGPathPen(gs); glyph.draw(pen)
        paths.append(f'<path transform="translate({cursor:.3f} 0)" d="{pen.getCommands()}"/>')
        cursor+=glyph.width+tracking*size/scale
    return f'<g fill="{color}" transform="translate({x} {baseline}) scale({scale} {-scale})">'+''.join(paths)+'</g>',cursor*scale

manifest=[]
for slug,label,accent in products:
    folder=ROOT/slug; folder.mkdir(exist_ok=True)
    for variant,color in [('on-dark','#ffffff'),('on-light','#141b25')]:
        # Dimensions mirror platform.css: bulb H=100, gap=.16H, brand=.34H, product=.57H.
        x=120.377; body=f'<image x="8" y="8" width="96.377" height="100" href="data:image/png;base64,{bulb}"/>'
        if label:
            upper,uw=lettering('skeeks',34,400,x,39,color)
            lower,lw=lettering(label,57,800,x,100,color)
            body+=upper+f'<rect x="{x+uw+16}" y="24.5" width="32" height="5.5" rx="2" fill="{accent}"/>'+lower
            width=max(x+uw+48,x+lw)+8
        else:
            title,tw=lettering('skeeks',57,400,x,100,color)
            body+=title; width=x+tw+8
        width=round(width,2)
        svg=f'<svg xmlns="http://www.w3.org/2000/svg" width="{width}" height="116" viewBox="0 0 {width} 116" role="img" aria-label="SkeekS {label or ""}">{body}</svg>'
        (folder/f'logo-{variant}.svg').write_text(svg,encoding='utf-8')
        manifest.append({'product':slug,'label':'SkeekS'+(' '+label if label else ''),'variant':variant,'accent':accent,'width':width,'height':116})
(ROOT/'manifest.json').write_text(json.dumps(manifest,ensure_ascii=False,indent=2),encoding='utf-8')
