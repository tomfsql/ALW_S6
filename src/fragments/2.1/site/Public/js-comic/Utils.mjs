// Télécharger une police de caractère
export async function loadFont(fontName, fontUrl) {
    const font = new FontFace(fontName, `url(${fontUrl})`);
    await font.load();
    document.fonts.add(font);
    return font;
}

// Télécharger une image
export async function loadImage(url) {
    return new Promise((resolve, reject) => {
        const img = new Image();
        img.onload = () => resolve(img); // Résout la promesse lorsque l'image est chargée
        img.onerror = (error) => reject(error); // Rejette la promesse en cas d'erreur
        img.src = url;
    });
}

// Nombre entier aléatoire entre 2 bornes (incluses)
export function randomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1) + min);
}