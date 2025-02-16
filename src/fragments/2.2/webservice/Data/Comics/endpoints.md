D'accord, voici ta liste de endpoints :

### **CRUD**
#### **BD (`comic.json`)**
- `GET /comics` → Liste toutes les BD
- `POST /comics` → Crée une nouvelle BD
- `GET /comics/{id}` → Récupère une BD
- `PUT /comics/{id}` → Met à jour une BD
- `DELETE /comics/{id}` → Supprime une BD

#### **Planches (`strip.json`)**
- `GET /comics/{id}/strips` → Liste les planches d'une BD
- `POST /comics/{id}/strips` → Ajoute une planche
- `GET /comics/{id}/strips/{stripId}` → Récupère une planche
- `PUT /comics/{id}/strips/{stripId}` → Met à jour une planche
- `DELETE /comics/{id}/strips/{stripId}` → Supprime une planche

#### **Cases (`panel-X.json`)**
- `GET /comics/{id}/strips/{stripId}/panels` → Liste les cases d'une planche
- `POST /comics/{id}/strips/{stripId}/panels` → Ajoute une case
- `GET /comics/{id}/strips/{stripId}/panels/{panelId}` → Récupère une case
- `PUT /comics/{id}/strips/{stripId}/panels/{panelId}` → Modifie une case
- `DELETE /comics/{id}/strips/{stripId}/panels/{panelId}` → Supprime une case

### **Endpoints avancés**
- `GET /comics/{id}/export` → Génère un JSON complet de la BD
- `POST /comics/{id}/fork` → Crée une version alternative d’une BD
- `PATCH /comics/{id}/strips/{stripId}/panels/{panelId}/bubbles` → Ajoute/supprime des bulles

Tu veux ajouter des contraintes sur les IDs (slug, UUID, etc.), ou des règles spécifiques pour l’ajout/suppression des bulles ?



## Prochaine question
je vais devoir fournir une version simplifiées aux étudiants débutants, qui consistera en un seul JSON contenant les données fusionnées de comic et de strip, avec un attribut panels contenant un tableau listant les panels :
```
{
    "comic": {
        "title": "Les pirates du code",
        "author": "moi",
        "created": "2025-02-01"
    },
    "strip": {
        "title": "#001 Trois blagues",
        "created": "2025-02-02"
    },
    "panels": [ ]
}
```

Peux-tu me proposer une classe repository reposant sur notre FileStorage et qui gèrerait un CRUD général sur ça ? Comment l'appeler pour éviter tout conflit avec ComicRepository ?