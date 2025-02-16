import Config from "./Config.mjs";
import Position from "./Position.mjs";

export default class Bubble
{
    constructor(ctx, y, pos, text) {
        this.ctx = ctx;
        this.y = y;
        this.pos = pos;
        this.text = text.split("\n");
        this.h = undefined;
    }

    draw() {
        let y = this.y;
        let x;
        if (this.pos === Position.left) {
            x = 8;
        } else if (this.pos === Position.center) {
            x = Config.panels.w / 8;
        } else { // Position.right
            x = Config.panels.w / 4 - 8;
        }
        let w = Config.panels.w * 3 / 4;
        let h = (Config.bubble.lineHeight) * this.text.length + Config.bubble.padding * 2;

        this.drawGenericBubbleLayer(x, y, w, h, true);
        this.drawGenericBubbleLayer(x, y, w, h, false);
        this.drawText(x + w / 2, y);

        // donne le y de son bas
        return y + h + this.tailHeight(Config.bubble.border);
    }

    drawGenericBubbleLayer(x, y, w, h, border = false) {
        let b = border ? Config.bubble.border : 0;
        let radius = Config.bubble.border * 3;

        this.ctx.beginPath();
        this.ctx.fillStyle = border ? '#000' : '#fff';
        this.ctx.roundRect(x - b, y - b, w + b * 2, h + b * 2, radius + b);
        
        let tailX;
        if (this.pos === Position.left) {
            tailX = x + radius * 3;
        } else if (this.pos === Position.center) {
            tailX = x + w / 2;
        } else { // Position.right
            tailX = x + w - radius * 3;
        }
        this.drawBubbleTail(tailX, y + h, b);

        
        this.ctx.closePath();
        this.ctx.fill('nonzero');
    }

    drawBubbleTail(x, y, border) {
        this.ctx.moveTo(x - Config.bubble.tail.size / 2 - border, y);
        this.ctx.lineTo(x + Config.bubble.tail.size / 2 + border, y);
        this.ctx.lineTo(x, y + this.tailHeight(border));
    }

    tailHeight(border) {
        // mathématiquement, il faudrait border * 2, mais
        // esthétiquement, une virgule affinée au bout rend mieux ici
        return Config.bubble.tail.size + border * 1.5;
    }

    drawText(x, y) {
        this.ctx.font = Config.font.settings;
        this.ctx.fillStyle = '#000';
        this.ctx.textBaseline = 'top';
        y+=Config.bubble.padding;

        this.text.forEach(textLine => {
            let metrics = this.ctx.measureText(textLine);
            this.ctx.fillText(textLine, x - metrics.width / 2, y);
            y+=Config.bubble.lineHeight;
        });
    }
}