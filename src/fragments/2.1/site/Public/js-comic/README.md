# Webwebcomic

## format JSON

Chaque fichier JSON est constitué d'un objet contenant 3 propriétés, chacune décrite plus en détail plus bas~:

- `comic` (object) : infos de la BD ("série") ;
- `strip` (object) : infos de la planche ("page") ;
- `panels` (array) : liste des cases de la planche ;

### BD / *Comics*

Un objet avec les propriétés suivantes :
- `title` (string) : titre de la BD
- `author` (string) : auteur de la BD
- `created` (string) : date de création

### Planche / *Strip*

- `title` (string) : titre de la planche / de la page / du *strip* (généralement numéroté)
- `created` (string) : date de création

### Cases / *Panels*
Les cases peuvent aussi être définies de façon indépendante (`.setPanel(...)`). Chaque case est un objet avec les propriétés suivantes :

- `background` (string) : le nom du fond (de la scène) à afficher. Ce nom doit être celui d'un *asset* défini dans `Config.mjs` ;
- `backgroundPosition` (object) : un objet sous la forme `{ x: 50 }` où `x` détermine la position (en pixel) à laquelle se placer dans le *background* ;
- `actors` (object) : un objet listant les acteurs présent pour cette case à chaque emplacement défini ;
- `bubbles` (array) : liste des bulles de discussion pour cette case.

Chacun de ces 4 éléments est **facultatif**, mais :
- chaque case héritera des éléments de la case précédente, sauf si une nouvelle valeur est définie
- seule exception : les `bubbles`, qui ne seront pas répétées
- la première case nécessite un `background`
- sans `backgroundPosition` sur les premières cases, une position aléatoire est choisie

### Acteurs / *Actors*

Le système prévoit 3 emplacements pour des acteurs (il y a donc au maximum 3 acteurs présents dans une case) :

- `left`
- `center`
- `right`

Chacun peut prendre la valeur d'un *asset* (une image) défini dans `Config.mjs`. Si l'un des 3 est omis, l'emplacement sera laissé vide.

Si l'objet acteur est omis, la case reprendra les mêmes acteurs que la case précédente (continuité de la scène).

### Bulles / *Bubbles*

Chaque bulle est un objet avec 2 propriétés :

- `speaker` : reprend les noms d'emplacements (`left`, `center`, `right`) pour indiquer où pointera la bulle ;
- `text` : le texte affiché dans la bulle.

Les bulles seront affichées dans l'ordre dans lequel elles ont été définies.