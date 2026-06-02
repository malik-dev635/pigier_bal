Tu es un expert Laravel 11. Génère un système de vote complet pour
une cérémonie de remise de prix académique appelée
"Pigier's Élites Awards — Bal de Fin d'Année 2026".

═══════════════════════════════════════
STACK TECHNIQUE
═══════════════════════════════════════

- Laravel 11 + MySQL
- Livewire 3 + Alpine.js
- Tailwind CSS
- Spatie Laravel Permission (rôles)

═══════════════════════════════════════
IDENTITÉ VISUELLE — DA OBLIGATOIRE
═══════════════════════════════════════
Inspire-toi de l'affiche officielle jointe.
Respecte scrupuleusement ces règles sur TOUTES les vues :

Couleurs :
--bg-primary : #050608 (fond noir jais)
--bg-card : #0D0D0F (fond des cartes)
--bg-surface : #141418 (surfaces secondaires)
--gold-main : #D4A843 (or principal — titres, bordures, CTA)
--gold-light : #F0CC6E (or clair — highlights, hover)
--gold-dark : #7A5C18 (or sombre — bordures subtiles)
--gold-bright : #FAE08A (or vif — badges, accents)
--text-white : #FFFFFF
--text-offwhite : #F5EDD6 (texte principal sur fond noir)
--text-muted : #7A7A8A (texte secondaire)

Typographie :

- Titres principaux : font-family Georgia, serif — bold, letter-spacing large
- Sous-titres : Cinzel ou Playfair Display (Google Fonts)
- Corps / UI : Inter ou Calibri
- Taille base : 16px

Composants UI :

- Fond général : #050608 sur toutes les pages
- Cards : bg #0D0D0F, border 1px solid #7A5C18,
  coins dorés décoratifs (::before/::after CSS)
- Bouton primaire : bg #D4A843, text #050608, bold,
  hover bg #F0CC6E, transition 200ms
- Bouton danger : border #D4A843, text #D4A843,
  hover bg #D4A843 text #050608
- Inputs : bg #141418, border #7A5C18, text #F5EDD6,
  focus border #D4A843, outline none
- Barre latérale : bg #0D0D0F, bordure droite 1px #7A5C18,
  items actifs : text #D4A843 + barre gauche 3px #D4A843
- Badges : bg #D4A843 text #050608 (succès),
  bg #7A5C18 text #FAE08A (neutre)
- Séparateurs : ligne 1px #7A5C18, avec variante double ligne or
- Barre top : 4px solid #D4A843 en haut de chaque page
- Scrollbar custom : track #0D0D0F, thumb #7A5C18

Effets :

- Hover cards : border-color #D4A843, box-shadow 0 0 20px #D4A84330
- Animations entrée : fade-in + slide-up léger (200ms ease)
- Bouton voté : bg #7A5C18, text #FAE08A, cursor not-allowed,
  icône ✓ devant le texte

═══════════════════════════════════════
RÔLES UTILISATEURS
═══════════════════════════════════════

- admin : accès total (CRUD tout)
- professeur : vote uniquement sur catégories "prof"
- eleve : vote uniquement sur catégories "élève"

═══════════════════════════════════════
MODÈLES & MIGRATIONS
═══════════════════════════════════════

1. Category
   - id, name, description, slug
   - voter_type : enum('eleve','professeur','both')
   - is_active : boolean (ouvrir/fermer le vote)
   - max_nominees : integer (défaut 5)
   - requires_proof : boolean (défaut false)
   - proof_type : enum('url','file','both') nullable
   - timestamps

2. Nominee
   - id, category_id (FK)
   - first_name, last_name
   - photo : nullable (chemin fichier uploadé)
   - class, description (nullable)
   - proof_url : nullable (lien YouTube, Drive, portfolio…)
   - proof_file : nullable (chemin fichier : PDF, ZIP, image)
   - is_active : boolean
   - timestamps

3. Vote
   - id, nominee_id (FK), user_id (FK), category_id (FK)
   - ip_address
   - timestamps
   - UNIQUE(user_id, category_id) → 1 vote par user par catégorie

4. User (extend défaut Laravel)
   - role : enum('admin','professeur','eleve')
   - class : nullable
   - phone : nullable

═══════════════════════════════════════
LOGIQUE PREUVE (requires_proof)
═══════════════════════════════════════

- Si category.requires_proof = true :
  → Champ proof_url ET/OU proof_file obligatoires
  selon proof_type lors de la saisie du nominé
  → Sur la page de vote, bouton "Voir la preuve" visible
  avant de voter (ouvre lien ou télécharge fichier)
  → Sans preuve soumise = nominé non visible au vote

- Si category.requires_proof = false :
  → Aucun champ preuve, nominé visible dès is_active = true

Catégories avec preuve (à seeder) :

- Meilleur Photographe / Vidéaste → proof_type: both
- Artiste de l'Année → proof_type: both
- Prix Innovation Digitale → proof_type: both
- Meilleur Entrepreneur Junior → proof_type: file
- Meilleur Club de l'Année → proof_type: file

═══════════════════════════════════════
FONCTIONNALITÉS PAR RÔLE
═══════════════════════════════════════

ADMIN (/admin) :
□ CRUD Catégories
→ nom, description, voter_type, is_active,
requires_proof, proof_type
□ CRUD Nominés par catégorie
→ nom, prénom, photo (upload), classe, description
→ si requires_proof : champ URL et/ou upload fichier
□ Ouvrir / fermer le vote par catégorie (toggle)
□ Dashboard temps réel (Livewire polling 5s) :
→ total votes, progression par catégorie,
top nominé par catégorie
□ Résultats : classement avec barres de progression
et pourcentages par catégorie
□ Export résultats CSV
□ Gestion utilisateurs (créer comptes élèves/profs)
□ Réinitialiser les votes d'une catégorie

PROFESSEUR (/vote) :
□ Voit uniquement voter_type IN('professeur','both')
□ 1 vote par catégorie
□ Bouton "Voir la preuve" si requires_proof
□ Historique de ses votes
□ Badge "✓ Voté" après vote — ne peut plus revenir

ÉLÈVE (/vote) :
□ Voit uniquement voter_type IN('eleve','both')
□ 1 vote par catégorie
□ Bouton "Voir la preuve" si requires_proof
□ Historique de ses votes
□ Badge "✓ Voté" après vote — ne peut plus revenir

═══════════════════════════════════════
CATÉGORIES À SEEDER
═══════════════════════════════════════
voter_type ELEVE :

- Meilleur Badeur
- Meilleur Entrepreneur Junior (requires_proof, file)
- Artiste de l'Année (requires_proof, both)
- Meilleur Club de l'Année (requires_proof, file)
- Meilleur Photographe / Vidéaste (requires_proof, both)
- Prix Innovation Digitale (requires_proof, both)
- Prix Engagement Solidaire (requires_proof, file)
- Alumni de l'Année
- Meilleur(e) Orateur / Oratrice

voter_type PROFESSEUR :

- Événement Académique de l'Année
- Professeur le Plus Marquant de l'Année
- Major de Promotion

voter_type BOTH :

- Meilleur Leadership
- Personnalité la Plus Inspirante

═══════════════════════════════════════
SÉCURITÉ & CONTRAINTES
═══════════════════════════════════════

- Middleware auth sur toutes les routes de vote
- Gate : 1 vote par user par catégorie (server-side)
- Vérification voter_type au moment du vote (server-side)
- Nominé sans preuve non visible si requires_proof = true
- Désactiver bouton vote après soumission (Alpine.js)
- is_active = false → catégorie visible, vote bloqué

═══════════════════════════════════════
INTERFACE VOTE — Livewire
═══════════════════════════════════════

- /vote : grille des catégories accessibles au user
- Chaque catégorie : card avec statut (ouvert/clôturé)
- Clic catégorie → grille des nominés
- Chaque nominé : photo + nom + classe
  - bouton "Voir la preuve" si applicable
  - bouton "Voter"
- Après vote : badge "✓ Voté" sur le nominé choisi,
  tous les autres boutons désactivés

═══════════════════════════════════════
GÉNÈRE DANS CET ORDRE
═══════════════════════════════════════

1. Migrations (dans l'ordre des dépendances)
2. Models avec relations, casts et scopes
3. Seeders (catégories + 1 admin test)
4. Policies (VotePolicy, CategoryPolicy, NomineePolicy)
5. Livewire Components :
   - Admin/CategoryManager (CRUD + toggle)
   - Admin/NomineeManager (CRUD + uploads)
   - Admin/VoteDashboard (temps réel polling)
   - Vote/CategoryList
   - Vote/NomineeCard (avec preuve)
6. Controllers (Auth, AdminController)
7. Routes web.php (groupes middleware par rôle)
8. Layouts Blade admin + vote (DA noir/or ci-dessus)
9. Vues Blade principales
