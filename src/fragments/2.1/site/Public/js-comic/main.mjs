import Comic from "./Comic.mjs";

// Récupérer le storyboard JSON à afficher
const jsonUrl = document.getElementById('comicJson').href;
let response = await fetch(jsonUrl);
let comicData = await response.json()

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
