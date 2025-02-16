import Bubble from "./Bubble.mjs";
import Config from "./Config.mjs";
import Position from "./Position.mjs";
import { loadFont, loadImage, randomInt } from "./Utils.mjs";


export default class Comic {

    // initialiser les cases
    constructor(canvas, ctx, data = undefined) {
        this.canvas = canvas;
        this.ctx = ctx;
        this.data = data;
        this.init = false;

        // Se souvenir de la scène d'une case à l'autre
        this.current = {
            background: undefined,
            backgroundPosition: undefined,
            actors: undefined
        }

        // début du chargement des ressources
        this.images = [];
    }

    setComic(data) {
        this.data = data;
    }

    setPanel(data) {
        if (this.data === undefined) {
            throw new Error("Can't set panel data: must define comic data first via setComic().");
        }
        if (Array.isArray(this.data.panels) == false) {
            this.data.panels = [];
        }
        this.data.panels.push(data);
    }

    async initialize() {
        this.init = true;

        // définir dynamiquement le nombre de cases dans Config
        Config.panels.qty = this.data.panels.length;

        // adapter le <canvas> à la taille configurée
        this.canvas.width = Config.comic.w;
        this.canvas.height = Config.comic.h;

        // remplir le canvas de blanc
        this.ctx.fillStyle = '#fff';
        this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);

        // ne pas flouter les images lors de mises à l'échelle
        this.ctx.imageSmoothingEnabled = false;

        // dessiner le cadre de chaque case (panel)
        for (let i = 0; i < Config.panels.qty; i++) {
            this.ctx.fillStyle = '#000';
            this.ctx.fillRect(...Object.values(this.getPanelRegion(i, true)));
            this.ctx.fillStyle = '#fff';
            this.ctx.fillRect(...Object.values(this.getPanelRegion(i, false)));
        }

        // attendre la fin du chargement des ressources pour travailler
        if (Config.font?.url)
            await loadFont(Config.font.name, Config.font.url);
        this.images = await this.loadAssets();
    }

    getPanelRegion(no, withBorder = false) {
        return {
            x: Config.comic.margin + (Config.comic.margin + Config.panels.w + Config.panels.border * 2) * no + (withBorder ? 0 : Config.panels.border),
            y: Config.comic.margin + Config.bubble.lineHeight + (withBorder ? 0 : Config.panels.border),
            w: Config.panels.w + (withBorder ? Config.panels.border * 2 : 0),
            h: Config.panels.h + (withBorder ? Config.panels.border * 2 : 0)
        };
        // let region = new Path2D();
        // region.rect(
        //     Config.comic.margin + (Config.comic.margin + Config.panels.w) * i + (withBorder ? 0 : Config.panels.border),
        //     Config.comic.margin + (withBorder ? 0 : Config.panels.border),
        //     Config.panels.w + (withBorder ? Config.panels.border * 2 : 0),
        //     Config.panels.h + (withBorder ? Config.panels.border * 2 : 0)
        // );
        // return region;
    }

    async loadAssets() {
        let images = [];
        for (const [name, url] of Object.entries(Config.assets)) {
            const img = await loadImage(url);
            images[name] = img;
        }
        return images;
    }

    async draw() {
        if (this.init === false)
            await this.initialize()

        // afficher le titre
        this.ctx.font = Config.font.settings;
        this.ctx.fillStyle = '#000';
        this.ctx.textBaseline = 'top';
        this.ctx.fillText(this.data.comic.title + ' ' + this.data.strip.title, Config.comic.margin, Config.comic.margin);

        this.ctx.textAlign = "right";
        this.ctx.fillText(this.data.comic.author, Config.comic.w - Config.comic.margin, Config.comic.margin);
        this.ctx.textAlign = "left";

        // dessiner la scène de chaque case
        for (let i = 0; i < Config.panels.qty; i++) {
            this.ctx.save();
            this.drawScene(i);
            this.ctx.restore();
        }
    }

    async drawScene(panelNum) {
        this.ctx.save();

        // on dessine uniquement dans la zone réservée à cette case (panel)
        const region = this.getPanelRegion(panelNum);
        this.ctx.rect(...Object.values(region));
        this.ctx.clip();

        // si la case n'a pas été définie : rien à dessiner
        let panel = this.data.panels[panelNum];
        if (panel == undefined)
            exit;

        // pour une case définie, si le fond ou les acteurs ne sont pas
        // redéfinis, on reprend les mêmes qu'à la case d'avant
        if(panel.background != undefined)
            this.current.background = panel.background;
        if(panel.backgroundPosition != undefined)
            this.current.backgroundPosition = panel.backgroundPosition;
        if(panel.actors != undefined)
            this.current.actors = panel.actors;

        // dessiner le fond
        this.drawBackground(this.images[this.current.background], region, this.current.backgroundPosition)
        for (const [position, name] of Object.entries(this.current.actors)) {
            this.drawActor(this.images[name], region, Position[position])
        }

        // dessiner les bulles (s'il y en a) les unes en dessous des autres
        this.ctx.translate(region.x, region.y);
        let y = 0;
        for (const bubble of panel.bubbles ?? []) {
            y = (new Bubble(this.ctx, y + Config.bubble.margin, Position[bubble.speaker], bubble.text)).draw();
        }

        this.ctx.restore();
    }

    drawBackground(image, region, position = undefined) {
        let bgWidth = image.width;
        let view = {
            w: Config.panels.w / Config.pixelRatio,
            h: Config.panels.h / Config.pixelRatio
        }

        // On choisis aléatoirement quelle zone du fond sera affichée
        let xSrc;
        if (position == undefined)
            xSrc = randomInt(0, bgWidth - view.w);
        else
            xSrc = position.x;

        this.ctx.save();
        this.ctx.translate(region.x, region.y);
        this.ctx.scale(Config.pixelRatio, Config.pixelRatio);
        this.ctx.drawImage(
            image,
            xSrc, 0, view.w, view.h, // xywh source
            0, 0, view.w, view.h, // xywh destination

        );
        this.ctx.restore();
    }

    drawActor(image, region, position) {
        this.ctx.save();

        // se placer au centre en bas de la case (zone de référence pour les acteurs)
        this.ctx.translate(
            region.x + region.w / 2 + position.x,
            region.y + region.h + position.y
        );

        this.ctx.scale(Config.pixelRatio, Config.pixelRatio);

        if (position.flipped) {
            this.ctx.scale(-1, 1);
        }

        this.ctx.drawImage(
            image,
            -image.width / 2,
            -image.height
        );
        this.ctx.restore();
    }
}