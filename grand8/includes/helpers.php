<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function base_url(string $path = ''): string
{
    $base = rtrim(APP_BASE_URL, '/');
    $path = ltrim($path, '/');

    return $path === '' ? $base . '/' : $base . '/' . $path;
}

function asset_url(string $path = ''): string
{
    return base_url('assets/' . ltrim($path, '/'));
}

function current_lang(): string
{
    global $currentLang;
    return $currentLang ?? DEFAULT_LANG;
}

function switch_lang_url(string $lang): string
{
    $allowed = ['fr', 'en'];

    if (!in_array($lang, $allowed, true)) {
        $lang = DEFAULT_LANG;
    }

    $path = strtok($_SERVER['REQUEST_URI'] ?? '', '?');
    $query = $_GET;
    $query['lang'] = $lang;

    return $path . '?' . http_build_query($query);
}

function nav_current(string $page, string $currentPage): string
{
    return $page === $currentPage ? 'active' : '';
}

function load_translations(string $lang): array
{
    $translations = [
        'fr' => [
            'nav' => [
                'home' => 'Accueil',
                'club' => 'Club',
                'events' => 'Événements',
                'blog' => 'Blog',
                'join' => 'Adhésion',
                'contact' => 'Contact',
                'joinCta' => 'Rejoindre le Club',
            ],
            'common' => [
                'language' => 'Langue',
            ],
            'home' => [
                'hero' => [
                    'eyebrow' => 'Grand 8 Billiard Club',
                    'titlePrefix' => 'Grand',
                    'titleAccent' => '8',
                    'subtitle' => 'Club de billard • Bardo, Tunisie',
                    'ctaJoin' => 'Rejoindre le Club',
                    'ctaExplore' => 'Explorer les événements',
                ],
                'eventsSection' => [
                    'eyebrow' => 'À venir',
                    'title' => 'Événements & tournois',
                    'description' => 'Inscrivez-vous au prochain tournoi, stage d\'entraînement ou soirée amicale à Bardo.',
                    'empty' => 'Aucun événement à venir pour le moment.',
                    'viewDetails' => 'Voir les détails',
                ],
                'cta' => [
                    'title' => 'Organisez un événement signature avec nous',
                    'description' => 'Grand 8 collabore avec des marques et des communautés pour créer des expériences premium : showcases caritatifs, soirées VIP et tournois d\'élite.',
                    'primaryLabel' => 'Organiser un événement',
                    'secondaryLabel' => 'Voir tous les événements',
                ],
                'blogSection' => [
                    'eyebrow' => 'Derniers',
                    'title' => 'Le billard en Tunisie',
                    'description' => 'Conseils, entraînements et actualités du club, sélectionnés par l\'équipe Grand 8.',
                    'readMore' => 'Lire l’article',
                ],
                'why' => [
                    'eyebrow' => 'Pourquoi Grand 8',
                    'title' => 'Un lieu d\'élite pour le billard',
                    'description' => 'Installations premium, coaching certifié et une communauté fondée sur le respect et la progression.',
                    'items' => [
                        ['title' => 'Installations professionnelles', 'description' => '4 tables professionnelles, éclairage précis et matériel choisi pour le jeu en compétition.'],
                        ['title' => 'Coaching & entraînement', 'description' => 'Plans personnalisés, analyse vidéo et ateliers pour tous les niveaux, du débutant au pro.'],
                        ['title' => 'Communauté & ambiance', 'description' => 'Un espace convivial avec lounge, snacks et un calendrier réservé aux membres.'],
                        ['title' => 'Circuit compétitif', 'description' => 'Ligues classées, qualifications nationales et un vrai chemin vers les tournois tunisiens.'],
                    ],
                ],
                'membership' => [
                    'title' => 'Types d\'adhésion & avantages',
                    'items' => [
                        ['label' => 'Joueur', 'text' => 'accès compétitif, matchs classés et suivi des performances.'],
                        ['label' => 'Membre', 'text' => 'accès illimité au lounge, réductions sur l\'entraînement et événements communautaires.'],
                        ['label' => 'VIP', 'text' => 'réservation prioritaire, coaching privé et tournois exclusifs.'],
                    ],
                ],
                'training' => [
                    'title' => 'Entraînement & coaching',
                    'description' => 'Cliniques hebdomadaires, coaching 1:1 et plans de performance avec analyse vidéo. Progressez avec un parcours adapté à vos objectifs.',
                    'cta' => 'Postuler au coaching',
                ],
                'competition' => [
                    'eyebrow' => 'Compétition',
                    'title' => 'Règlement & inscriptions',
                    'description' => 'Formats clairs, scoring transparent et inscription simplifiée pour chaque événement Grand 8.',
                    'items' => [
                        ['title' => 'Formats structurés', 'description' => 'Phase de groupes + élimination directe, tables arbitrées et tableaux de score.'],
                        ['title' => 'Process d\'inscription', 'description' => 'Inscription en ligne, confirmation et check-in dédié le jour de l\'événement.'],
                        ['title' => 'Code de conduite', 'description' => 'Respect, fair-play et communication saine pendant tous les matchs.'],
                    ],
                ],
                'gallery' => [
                    'eyebrow' => 'Galerie',
                    'title' => 'Dans Grand 8',
                    'description' => 'Un aperçu des tables, du lounge et de l\'énergie du club.',
                ],
                'sponsors' => [
                    'eyebrow' => 'Sponsors',
                    'title' => 'Nos partenaires',
                    'description' => 'Des espaces sponsor sont disponibles pour des partenaires locaux et internationaux.',
                ],
                'faq' => [
                    'eyebrow' => 'FAQ',
                    'title' => 'Questions fréquentes',
                    'description' => 'Tout ce qu\'il faut savoir avant de visiter Grand 8.',
                    'items' => [
                        ['q' => 'Dois-je être membre pour jouer ?', 'a' => 'Les invités sont les bienvenus aux horaires d\'ouverture. L\'adhésion donne accès aux réservations prioritaires et aux tournois.'],
                        ['q' => 'Quelles disciplines sont disponibles ?', 'a' => '8-ball, 9-ball, 10-ball et des exercices d\'entraînement personnalisés sont disponibles sur place.'],
                        ['q' => 'Les débutants peuvent-ils rejoindre ?', 'a' => 'Oui. Notre équipe aide les nouveaux joueurs à construire de solides bases.'],
                        ['q' => 'Comment sponsoriser un événement ?', 'a' => 'Contactez-nous via le formulaire de partenariat et nous vous enverrons le dossier sponsor.'],
                    ],
                ],
            ],
            'club' => [
                'heading' => [
                    'eyebrow' => 'Club',
                    'title' => 'Grand 8 est fait pour les champions et la communauté',
                    'description' => 'Fondé à Bardo, nous combinons des installations premium et une ambiance conviviale pour les passionnés de billard en Tunisie.',
                ],
                'story' => [
                    'title' => 'Notre histoire & mission',
                    'description' => 'Grand 8 a commencé en 2015 avec une mission : élever le niveau du billard en Tunisie. Nous organisons des tournois, développons les jeunes talents et offrons une expérience premium à chaque joueur.',
                    'values' => [
                        ['title' => 'Esprit sportif', 'description' => 'Nous créons un environnement respectueux et inclusif.'],
                        ['title' => 'Précision', 'description' => 'Matériel haut de gamme et coaching pour des performances constantes.'],
                        ['title' => 'Communauté', 'description' => 'Les membres se retrouvent via événements, ligues et soirées.'],
                        ['title' => 'Progression', 'description' => 'Des programmes du débutant au compétiteur confirmé.'],
                    ],
                ],
                'hours' => [
                    'title' => 'Horaires d’ouverture',
                    'items' => [
                        'Lundi - Jeudi : 10:00 — 00:00',
                        'Vendredi : 10:00 — 01:00',
                        'Samedi : 10:00 — 03:00',
                        'Dimanche : 10:00 — 02:00',
                    ],
                ],
                'facilities' => [
                    'title' => 'Installations',
                    'items' => [
                        '4 tables de billard premium',
                        'Queues pro, craies et accessoires',
                        'Salle de coaching & écran d’analyse',
                        'Espace lounge, snacks et rafraîchissements',
                    ],
                ],
                'rules' => [
                    'title' => 'Règlement du club',
                    'items' => [
                        'Respectez le matériel et les autres joueurs.',
                        'Jouez fair-play et respectez les décisions des arbitres.',
                        'Gardez le club propre et organisé.',
                        'Prévenez l’équipe pour les inscriptions aux tournois.',
                    ],
                ],
                'services' => [
                    'title' => 'Services membres',
                    'items' => [
                        'Casiers de rangement',
                        'Réservation prioritaire des tables',
                        'Réductions entraînement & tournois',
                        'Classement mensuel',
                    ],
                ],
                'cta' => [
                    'title' => 'Prêt à rejoindre la famille Grand 8 ?',
                    'description' => 'Faites votre demande d’adhésion ou contactez-nous pour une visite privée du club et des installations.',
                    'primaryLabel' => 'Postuler',
                    'secondaryLabel' => 'Nous contacter',
                ],
            ],
            'join' => [
                'heading' => [
                    'eyebrow' => 'Adhésion',
                    'title' => 'Devenir membre de Grand 8',
                    'description' => 'Parlez-nous de votre expérience et de vos objectifs. Nous vous contacterons avec les prochaines étapes.',
                ],
                'why' => [
                    'title' => 'Pourquoi rejoindre ?',
                    'items' => [
                        'Accédez à des installations premium et à des plans de coaching.',
                        'Participez à des ligues classées et à des tournois en Tunisie.',
                        'Rencontrez des sponsors, des coachs et des joueurs d’élite.',
                    ],
                ],
            ],
            'contact' => [
                'heading' => [
                    'eyebrow' => 'Contact',
                    'title' => 'Construisons l’avenir du billard en Tunisie',
                    'description' => 'Contactez-nous pour les sponsors, partenariats ou pour en savoir plus sur Grand 8.',
                ],
                'club' => [
                    'title' => 'Contact du club',
                    'items' => [
                        'Adresse : Bardo, Tunisie',
                        'Téléphone : +216 23 272 590',
                        'Email : contact@grand-8.club',
                    ],
                ],
                'highlights' => [
                    'title' => 'Highlights sponsors',
                    'description' => 'Nous collaborons avec des marques, partenaires média et entreprises locales pour créer des expériences premium autour du billard.',
                ],
                'form' => [
                    'title' => 'Formulaire sponsor / partenariat',
                    'description' => 'Parlez-nous de votre vision et de vos objectifs de partenariat.',
                ],
            ],
            'blog' => [
                'heading' => [
                    'eyebrow' => 'Blog',
                    'title' => 'Histoires autour des tables',
                    'description' => 'Découvrez des conseils d’entraînement, des actus tournois et la scène billard en Tunisie.',
                ],
            ],
            'events' => [
                'heading' => [
                    'eyebrow' => 'Événements',
                    'title' => 'Tournois, ligues et soirées communauté',
                    'description' => 'Filtrez les événements à venir, passés et tournois. Inscrivez-vous instantanément ou collaborez avec nous pour organiser un événement.',
                ],
                'cta' => [
                    'title' => 'Organiser un événement avec Grand 8',
                    'description' => 'Partenariat avec le club pour un tournoi premium ou une showcase corporate à Bardo.',
                    'primaryLabel' => 'Organiser un événement',
                    'secondaryLabel' => 'Rejoindre le club',
                ],
            ],
            'eventRegister' => [
                'heading' => [
                    'eyebrow' => 'Inscription',
                    'title' => 'Inscription à l’événement',
                ],
                'closed' => [
                    'title' => 'Inscriptions fermées',
                    'description' => 'Cet événement n’est pas à venir, les inscriptions sont donc désactivées.',
                ],
            ],
            'footer' => [
                'description' => 'Club de billard premium à Bardo, Tunisie. Entraînement, tournois et communauté pour tous les niveaux.',
                'explore' => 'Explorer',
                'clubInfo' => 'Infos Club',
                'events' => 'Événements',
                'blog' => 'Blog',
                'join' => 'Adhésion',
                'contact' => 'Contact',
                'newsletter' => 'Newsletter',
                'newsletterText' => 'Recevez les nouveautés sur les tournois, événements et offres exclusives.',
                'emailUs' => 'Nous écrire',
                'copyright' => 'Tous droits réservés.',
            ],
        ],
        'en' => [
            'nav' => [
                'home' => 'Home',
                'club' => 'Club',
                'events' => 'Events',
                'blog' => 'Blog',
                'join' => 'Join',
                'contact' => 'Contact',
                'joinCta' => 'Join the Club',
            ],
            'common' => [
                'language' => 'Language',
            ],
            'home' => [
                'hero' => [
                    'eyebrow' => 'Grand 8 Billiard Club',
                    'titlePrefix' => 'Grand',
                    'titleAccent' => '8',
                    'subtitle' => 'Billiard Club • Bardo, Tunisia',
                    'ctaJoin' => 'Join the Club',
                    'ctaExplore' => 'Explore Events',
                ],
                'eventsSection' => [
                    'eyebrow' => 'Upcoming',
                    'title' => 'Events & tournaments',
                    'description' => 'Register for the next tournament, training clinic, or friendly league night in Bardo.',
                    'empty' => 'No upcoming events available at the moment.',
                    'viewDetails' => 'View details',
                ],
                'cta' => [
                    'title' => 'Host a signature event with us',
                    'description' => 'Grand 8 partners with brands and communities to host premium billiard experiences, from charity showcases to elite tournaments.',
                    'primaryLabel' => 'Host an Event',
                    'secondaryLabel' => 'View all events',
                ],
                'blogSection' => [
                    'eyebrow' => 'Latest',
                    'title' => 'Billiards in Tunisia',
                    'description' => 'Insights, training tips, and club updates curated by the Grand 8 team.',
                    'readMore' => 'Read article',
                ],
                'why' => [
                    'eyebrow' => 'Why Grand 8',
                    'title' => 'An elite home for billiards',
                    'description' => 'Premium facilities, certified coaching, and a community built around sportsmanship and growth.',
                    'items' => [
                        ['title' => 'Professional facilities', 'description' => '4 professional tables, precision lighting, and equipment curated for tournament play.'],
                        ['title' => 'Coaching & training', 'description' => 'Personalized training plans, video analysis, and cue clinics for every level from beginner to pro.'],
                        ['title' => 'Community & culture', 'description' => 'A welcoming space with lounges, snacks, and a member-only calendar of social nights.'],
                        ['title' => 'Competitive circuit', 'description' => 'Ranked leagues, national qualifiers, and a direct pathway to Tunisian tournaments.'],
                    ],
                ],
                'membership' => [
                    'title' => 'Membership types & benefits',
                    'items' => [
                        ['label' => 'Player', 'text' => 'competitive access, ranked matches, and performance tracking.'],
                        ['label' => 'Member', 'text' => 'unlimited lounge access, training discounts, and community events.'],
                        ['label' => 'VIP', 'text' => 'priority table booking, private coaching, and exclusive tournaments.'],
                    ],
                ],
                'training' => [
                    'title' => 'Training & coaching',
                    'description' => 'Weekly clinics, 1:1 coaching, and performance plans with video analysis. Build a growth path tailored to your goals.',
                    'cta' => 'Apply for coaching',
                ],
                'competition' => [
                    'eyebrow' => 'Competition',
                    'title' => 'Tournament rules & registration',
                    'description' => 'Clear formats, transparent scoring, and streamlined registration for every Grand 8 event.',
                    'items' => [
                        ['title' => 'Structured formats', 'description' => 'Group stage + knockout brackets with refereed tables and live scoreboards.'],
                        ['title' => 'Registration flow', 'description' => 'Online sign-up, entry confirmation, and dedicated check-in desk on event days.'],
                        ['title' => 'Code of conduct', 'description' => 'Sportsmanship, fair play, and respectful communication across all matches.'],
                    ],
                ],
                'gallery' => [
                    'eyebrow' => 'Gallery',
                    'title' => 'Inside Grand 8',
                    'description' => 'A glimpse into the tables, lounges, and energy at the club.',
                ],
                'sponsors' => [
                    'eyebrow' => 'Sponsors',
                    'title' => 'Partners who power the game',
                    'description' => 'Sponsor slots available for local and international partners.',
                ],
                'faq' => [
                    'eyebrow' => 'FAQ',
                    'title' => 'Frequently asked questions',
                    'description' => 'Everything you need to know before visiting Grand 8.',
                    'items' => [
                        ['q' => 'Do I need to be a member to play?', 'a' => 'Guests are welcome during open hours. Membership unlocks priority booking and tournaments.'],
                        ['q' => 'What disciplines are available?', 'a' => '8-ball, 9-ball, 10-ball, and custom training drills are available on-site.'],
                        ['q' => 'Can beginners join?', 'a' => 'Absolutely. Our coaching team helps new players build solid fundamentals.'],
                        ['q' => 'How do I sponsor an event?', 'a' => 'Reach out via the partnership form and we will share the sponsorship deck.'],
                    ],
                ],
            ],
            'club' => [
                'heading' => [
                    'eyebrow' => 'Club',
                    'title' => 'Grand 8 is built for champions and community',
                    'description' => 'Founded in Bardo, we blend premium facilities with a welcoming community for billiards lovers across Tunisia.',
                ],
                'story' => [
                    'title' => 'Our story & mission',
                    'description' => 'Grand 8 began in 2015 with a mission to elevate billiards in Tunisia. We host high-level tournaments, cultivate youth talent, and create a premium experience for every player.',
                    'values' => [
                        ['title' => 'Sportsmanship', 'description' => 'We build a respectful, inclusive environment for players.'],
                        ['title' => 'Precision', 'description' => 'Top-tier equipment and coaching support consistent performance.'],
                        ['title' => 'Community', 'description' => 'Members connect through events, leagues, and social nights.'],
                        ['title' => 'Growth', 'description' => 'Programs for beginners through professional competitors.'],
                    ],
                ],
                'hours' => [
                    'title' => 'Opening hours',
                    'items' => [
                        'Monday - Thursday: 10:00 — 00:00',
                        'Friday: 10:00 — 01:00',
                        'Saturday: 10:00 — 03:00',
                        'Sunday: 10:00 — 02:00',
                    ],
                ],
                'facilities' => [
                    'title' => 'Facilities',
                    'items' => [
                        '4 premium pool tables',
                        'Professional cues, chalk, and accessories',
                        'Private coaching room & analysis screen',
                        'Lounge seating, snacks, and refreshments',
                    ],
                ],
                'rules' => [
                    'title' => 'Club rules',
                    'items' => [
                        'Respect the equipment and other players.',
                        'Practice fair play and follow referee calls.',
                        'Keep the club clean and organized.',
                        'Notify staff for tournament registration.',
                    ],
                ],
                'services' => [
                    'title' => 'Member services',
                    'items' => [
                        'Equipment storage lockers',
                        'Priority table booking',
                        'Training & tournament discounts',
                        'Monthly ranking leaderboard',
                    ],
                ],
                'cta' => [
                    'title' => 'Ready to join the Grand 8 family?',
                    'description' => 'Apply for membership or reach out for a private tour of the club and facilities.',
                    'primaryLabel' => 'Apply now',
                    'secondaryLabel' => 'Contact us',
                ],
            ],
            'join' => [
                'heading' => [
                    'eyebrow' => 'Join',
                    'title' => 'Become part of Grand 8',
                    'description' => 'Tell us about your experience and goals. We\'ll contact you with the next steps for membership.',
                ],
                'why' => [
                    'title' => 'Why join?',
                    'items' => [
                        'Access premium facilities and coaching plans.',
                        'Compete in ranked leagues and Tunisian tournaments.',
                        'Network with sponsors, coaches, and elite players.',
                    ],
                ],
            ],
            'contact' => [
                'heading' => [
                    'eyebrow' => 'Contact',
                    'title' => 'Let’s build the future of billiards in Tunisia',
                    'description' => 'Reach out for sponsorships, partnerships, or to learn more about Grand 8.',
                ],
                'club' => [
                    'title' => 'Club contact',
                    'items' => [
                        'Address: Bardo, Tunisia',
                        'Phone: +216 23 272 590',
                        'Email: contact@grand-8.club',
                    ],
                ],
                'highlights' => [
                    'title' => 'Sponsor highlights',
                    'description' => 'We collaborate with brands, media partners, and local businesses to create premium billiard experiences.',
                ],
                'form' => [
                    'title' => 'Sponsor / partnership form',
                    'description' => 'Tell us about your partnership vision and goals.',
                ],
            ],
            'blog' => [
                'heading' => [
                    'eyebrow' => 'Blog',
                    'title' => 'Stories from the tables',
                    'description' => 'Explore training tips, tournament updates, and the billiard scene across Tunisia.',
                ],
            ],
            'events' => [
                'heading' => [
                    'eyebrow' => 'Events',
                    'title' => 'Tournaments, leagues, and community nights',
                    'description' => 'Filter upcoming, past, and tournament events. Register instantly or partner with us to host an event.',
                ],
                'cta' => [
                    'title' => 'Host an event with Grand 8',
                    'description' => 'Partner with the club for a premium tournament or corporate showcase in Bardo.',
                    'primaryLabel' => 'Host an event',
                    'secondaryLabel' => 'Join the club',
                ],
            ],
            'eventRegister' => [
                'heading' => [
                    'eyebrow' => 'Register',
                    'title' => 'Event registration',
                ],
                'closed' => [
                    'title' => 'Registration closed',
                    'description' => 'This event is not upcoming, so registrations are disabled.',
                ],
            ],
            'footer' => [
                'description' => 'Premium billiard club in Bardo, Tunisia. Training, tournaments, and community for every player level.',
                'explore' => 'Explore',
                'clubInfo' => 'Club Info',
                'events' => 'Events',
                'blog' => 'Blog',
                'join' => 'Join',
                'contact' => 'Contact',
                'newsletter' => 'Newsletter',
                'newsletterText' => 'Get updates about tournaments, events, and exclusive offers.',
                'emailUs' => 'Email us',
                'copyright' => 'All rights reserved.',
            ],
        ],
    ];

    return $translations[$lang] ?? $translations[DEFAULT_LANG];
}

function t(string $key): string
{
    static $cache = [];

    $lang = current_lang();

    if (!isset($cache[$lang])) {
        $cache[$lang] = load_translations($lang);
    }

    $segments = explode('.', $key);
    $value = $cache[$lang];

    foreach ($segments as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $key;
        }
        $value = $value[$segment];
    }

    return is_string($value) ? $value : $key;
}

function home_stats(): array
{
    return [
        [
            'label' => current_lang() === 'fr' ? 'Événements organisés' : 'Events hosted',
            'value' => 28,
        ],
        [
            'label' => current_lang() === 'fr' ? 'Membres au total' : 'Total members',
            'value' => 34,
        ],
        [
            'label' => current_lang() === 'fr' ? 'Depuis' : 'Since',
            'value' => 2015,
        ],
        [
            'label' => current_lang() === 'fr' ? 'Joueurs actifs' : 'Active players',
            'value' => 16,
        ],
    ];
}

function home_posts(): array
{
    if (current_lang() === 'fr') {
        return [
            [
                'slug' => 'billiards-tunisia-scene',
                'title' => 'L’essor du billard en Tunisie',
                'excerpt' => 'Des salles de quartier aux classements nationaux, découvrez comment la scène tunisienne évolue.',
                'category' => 'Billard en Tunisie',
                'date' => '4 mai 2024',
            ],
            [
                'slug' => 'championship-shot-routine',
                'title' => 'Une routine de tir de champion',
                'excerpt' => 'Construisez une routine régulière qui améliore la précision et la gestion du stress.',
                'category' => 'Entraînement & Conseils',
                'date' => '18 mai 2024',
            ],
            [
                'slug' => 'grand-8-open-preview',
                'title' => 'Grand 8 Open : à quoi s’attendre',
                'excerpt' => 'Le tournoi phare revient. Voici le format, les récompenses et l’expérience joueur.',
                'category' => 'Tournois',
                'date' => '2 juin 2024',
            ],
        ];
    }

    return [
        [
            'slug' => 'billiards-tunisia-scene',
            'title' => 'The Rise of Billiards in Tunisia',
            'excerpt' => 'From neighborhood halls to national rankings, discover how the Tunisian billiard scene is evolving.',
            'category' => 'Billiard in Tunisia',
            'date' => 'May 4, 2024',
        ],
        [
            'slug' => 'championship-shot-routine',
            'title' => 'A Championship Shot Routine',
            'excerpt' => 'Build a consistent routine that improves accuracy and keeps your nerves steady in tournaments.',
            'category' => 'Training & Tips',
            'date' => 'May 18, 2024',
        ],
        [
            'slug' => 'grand-8-open-preview',
            'title' => 'Grand 8 Open: What to Expect',
            'excerpt' => 'The flagship tournament is back. Here is the format, prizes, and player experience.',
            'category' => 'Tournaments',
            'date' => 'June 2, 2024',
        ],
    ];
}

function fetch_events(array $params = []): array
{
    $url = API_BASE_URL . '/events-list.php';

    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }

    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 10,
            'ignore_errors' => true,
        ],
    ]);

    $response = @file_get_contents($url, false, $context);

    if ($response === false) {
        return [];
    }

    $data = json_decode($response, true);

    if (
        !is_array($data) ||
        empty($data['success']) ||
        !isset($data['events']) ||
        !is_array($data['events'])
    ) {
        return [];
    }

    return $data['events'];
}

function is_upcoming_event(array $event): bool
{
    $status = strtolower(trim((string)($event['status'] ?? '')));
    $eventDateRaw = (string)($event['event_date'] ?? '');
    $isActive = (int)($event['is_active'] ?? 0) === 1;

    $allowedStatuses = ['upcoming', 'published', 'active', 'scheduled'];

    if (!$isActive) {
        return false;
    }

    if (in_array($status, $allowedStatuses, true)) {
        return true;
    }

    if ($eventDateRaw !== '') {
        $eventTimestamp = strtotime($eventDateRaw);
        if ($eventTimestamp !== false) {
            return $eventTimestamp >= strtotime(date('Y-m-d'));
        }
    }

    return false;
}

function home_upcoming_events(int $limit = 3): array
{
    $events = fetch_events(['active' => '1']);

    $filtered = array_values(array_filter($events, static function (array $event): bool {
        return is_upcoming_event($event);
    }));

    usort($filtered, static function (array $a, array $b): int {
        $dateA = strtotime((string)($a['event_date'] ?? '9999-12-31')) ?: PHP_INT_MAX;
        $dateB = strtotime((string)($b['event_date'] ?? '9999-12-31')) ?: PHP_INT_MAX;
        return $dateA <=> $dateB;
    });

    return array_slice($filtered, 0, $limit);
}