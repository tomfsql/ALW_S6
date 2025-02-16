let lineHeight;

const Config = {
    pixelRatio: 2,
    panels: {
        qty: 4, // sera redéfini dynamiquement à la création de Comic
        w: 240,
        h: 320,
        border: 2
    },
    comic: {
        margin: 10,
        get w() {
            /*margin +*/
            return Config.comic.margin * (2 + Config.panels.qty - 1) + (Config.panels.border*2 + Config.panels.w) * Config.panels.qty;
        },
        get h() {
            return Config.panels.border*2 + Config.comic.margin*2 + Config.panels.h + Config.bubble.lineHeight;
        }
    },
    bubble: {
        border: 2,
        margin: 8,
        padding: 4,
        tail: { size: 10 },
        get lineHeight() {
            // calculer la hauteur de ligne (la première fois)
            if (lineHeight === undefined) {
                let canvas = document.createElement('canvas');
                let ctx = canvas.getContext("2d");
                ctx.font = Config.font.settings;

                let metrics = ctx.measureText('Pp');
                lineHeight = metrics.actualBoundingBoxAscent + metrics.actualBoundingBoxDescent + Config.bubble.border;
                // lineHeight = metrics.fontBoundingBoxAscent + metrics.fontBoundingBoxDescent;
            }
            return lineHeight;
        }
    },
    font: {
        // name: "cursive",

        name: "Comic Neue Regular",
        url: "Public/fonts/ComicNeue-Regular.woff2",

        size: 14,
        get settings() {
            return `${Config.font.size}px "${Config.font.name}"`;
        }
    },
    assets: {
        background: 'Public/images/warguild-expanded.png',

        maximeColère: 'Public/images/maxime-colère.png',
        maximeParle: 'Public/images/maxime-parle.png',
        maximeSilence: 'Public/images/maxime-silence.png',
        maximeSourit: 'Public/images/maxime-sourit.png',
        maximeSurprise: 'Public/images/maxime-surprise.png',

        maximeColèreBrasOuverts: 'Public/images/maxime-colère-bras-ouverts.png',
        maximeParleBrasOuverts: 'Public/images/maxime-parle-bras-ouverts.png',
        maximeSilenceBrasOuverts: 'Public/images/maxime-silence-bras-ouverts.png',
        maximeSouritBrasOuverts: 'Public/images/maxime-sourit-bras-ouverts.png',

        maximeColèreIndex: 'Public/images/maxime-colère-index.png',
        maximeSouritIndex: 'Public/images/maxime-sourit-index.png',
    }
};

export default Config;