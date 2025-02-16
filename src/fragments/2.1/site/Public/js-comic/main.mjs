import Comic from "./Comic.mjs";

// Définition du storyboard à afficher
let comicData = {
    comic: {
        title: "Les pirates du code",
        author: "Yannick Joly",
        created: "2025-02-01"
    },
    strip: {
        title: "#001 Trois blagues",
        created: "2025-02-02"
    },
    panels: [
        {
            background: "background",
            actors: {
                left: "maximeColèreIndex",
                center: "maximeSilence",
                right: "maximeParleBrasOuverts"
            },
            bubbles: [
                {
                    speaker: "left",
                    text: "Votre code est sans nul\ndoute le plus pitoyable\nque j'ai vu tourner !"
                },
                {
                    speaker: "right",
                    text: "Au moins, il tourne !"
                }
            ]
        },
        {
            backgroundPosition: {
                x: 90
            },
            actors: {
                center: "maximeParle",
                right: "maximeSouritBrasOuverts"
            },
            bubbles: [
                {
                    speaker: "right",
                    text: "Mec, j'ai piqué ton code !"
                },
                {
                    speaker: "center",
                    text: "Ce code n'est pas de moi !"
                }
            ]
        },
        {
            bubbles: [
                {
                    speaker: "center",
                    text: "Pourquoi on code\ntoujours de nuit ?"
                },
                {
                    speaker: "right",
                    text: "Parce que c'est quand\nles bugs dorment !"
                }
            ]
        }
    ]
};

// Récupérer le contexte du canvas qui va accueillir la BD
let canvas = document.getElementsByTagName('canvas')[0];
let ctx = canvas.getContext('2d');

// Initialiser la BD
let comic = new Comic(canvas, ctx, comicData);

// Les données peuvent aussi être transmises en plusieurs fois sous une forme du genre :
// let comic = new Comic(canvas, ctx);
// comic.setComic(mainData);
// comic.setPanel(panel1Data);
// comic.setPanel(panel2Data);
// comic.setPanel(panel3Data);

// Afficher les scènes dans les cases (asynchrone)
comic.draw();
