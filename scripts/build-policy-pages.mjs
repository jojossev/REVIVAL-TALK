import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const outDir = path.join(__dirname, '../database/seeders/content');

const link = (text, url) =>
  `<a href="${url}" target="_blank" rel="noopener noreferrer">${text}</a>`;

const SITE = link('www.revival-talk.com', 'https://www.revival-talk.com');
const CONTACT = link('www.revival-business.com', 'https://www.revival-business.com');

function p(text) {
  return `<p>${text}</p>`;
}

function h2(text) {
  return `<h2>${text}</h2>`;
}

function h3(text) {
  return `<h3>${text}</h3>`;
}

function ul(items) {
  return `<ul>${items.map((i) => `<li>${i}</li>`).join('')}</ul>`;
}

function header(title, updated, introParagraphs) {
  return [
    '<div class="revival-legal-page">',
    `<h1>${title}</h1>`,
    '<p><strong>REVIVAL TALK</strong></p>',
    `<p><em>Dernière mise à jour : ${updated}</em></p>`,
    ...introParagraphs.map(p),
  ].join('');
}

const privacyPolicyHtml =
  header('POLITIQUE DE CONFIDENTIALITÉ', '20 Mai 2026', [
    `Bienvenue sur REVIVAL TALK, plateforme numérique d'information, de médias et de diffusion de contenus accessible via : ${SITE}.`,
    'La protection de vos données personnelles, de votre vie privée et de votre sécurité numérique constitue une priorité fondamentale pour REVIVAL TALK.',
    'La présente Politique de Confidentialité explique de manière transparente comment nous collectons, utilisons, stockons, protégeons et partageons les informations des utilisateurs qui accèdent à notre site web, nos applications mobiles, nos services numériques et nos plateformes associées.',
    'En utilisant nos services, vous acceptez les pratiques décrites dans cette Politique de Confidentialité.',
  ]) +
  h2('1. IDENTITÉ DE LA PLATEFORME') +
  p("REVIVAL TALK est une plateforme numérique d'information, de médias, d'actualités et de contenus multimédias exploitée conformément aux lois applicables en République Démocratique du Congo.") +
  p('Notre plateforme peut proposer :') +
  ul([
    "Des articles d'actualité", 'Des reportages', 'Des contenus multimédias', 'Des vidéos',
    'Des émissions en direct', 'Des interviews', 'Des podcasts', 'Des newsletters',
    'Des commentaires', "Des alertes d'actualité", 'Des contenus communautaires',
    'Des espaces publicitaires', 'Des fonctionnalités interactives',
  ]) +
  h2('2. NOTRE ENGAGEMENT EN MATIÈRE DE CONFIDENTIALITÉ') +
  p('REVIVAL TALK s\'engage à :') +
  ul([
    'Respecter la confidentialité des utilisateurs',
    'Protéger les données personnelles contre les accès non autorisés',
    'Utiliser les données de manière légitime, transparente et sécurisée',
    'Respecter les principes fondamentaux de protection des données',
    'Respecter les lois applicables en République Démocratique du Congo relatives aux technologies numériques, aux communications électroniques, aux médias numériques et à la protection de la vie privée',
  ]) +
  p('Nous appliquons également plusieurs standards internationaux de sécurité numérique afin d\'assurer une meilleure protection des informations de nos utilisateurs.') +
  h2('3. INFORMATIONS QUE NOUS COLLECTONS') +
  p('Nous pouvons collecter différentes catégories d\'informations selon votre utilisation de nos services.') +
  h3('3.1 Informations fournies directement par l\'utilisateur') +
  p('Lorsque vous créez un compte, contactez notre équipe ou utilisez certaines fonctionnalités, nous pouvons collecter :') +
  ul(['Nom et prénom', 'Adresse e-mail', 'Numéro de téléphone', 'Photo de profil', 'Pays ou ville', 'Informations de connexion', 'Préférences linguistiques', 'Messages envoyés au support', 'Commentaires publiés sur la plateforme', 'Informations fournies lors de participations à des concours, sondages ou événements']) +
  h3('3.2 Informations collectées automatiquement') +
  p('Lors de votre navigation sur notre plateforme, certaines informations peuvent être collectées automatiquement :') +
  ul(['Adresse IP', 'Type d\'appareil', 'Système d\'exploitation', 'Type de navigateur', 'Langue du navigateur', 'Pages consultées', 'Temps de navigation', 'Données de performance', 'Historique d\'interaction avec les contenus', 'Données analytiques', 'Données de géolocalisation approximative']) +
  p('Ces informations nous permettent d\'améliorer nos services, notre sécurité et l\'expérience utilisateur.') +
  h3('3.3 Cookies et technologies similaires') +
  p('Notre plateforme utilise des cookies et technologies similaires afin de :') +
  ul(['Maintenir votre session active', 'Améliorer les performances du site', 'Sauvegarder vos préférences', 'Personnaliser les contenus', 'Mesurer l\'audience', 'Afficher des publicités pertinentes', 'Renforcer la sécurité de la plateforme']) +
  p('Vous pouvez configurer votre navigateur afin de refuser certains cookies. Toutefois, certaines fonctionnalités pourraient ne plus fonctionner correctement.') +
  h2('4. FINALITÉS DE L\'UTILISATION DES DONNÉES') +
  p('Les informations collectées peuvent être utilisées pour :') +
  ul(['Fournir et exploiter nos services numériques', 'Personnaliser l\'expérience utilisateur', 'Diffuser des contenus adaptés', 'Gérer les comptes utilisateurs', 'Envoyer des notifications d\'actualité', 'Répondre aux demandes des utilisateurs', 'Assurer la sécurité des systèmes', 'Prévenir les fraudes et abus', 'Réaliser des statistiques et analyses', 'Améliorer les performances techniques', 'Respecter nos obligations légales', 'Développer de nouveaux services numériques']) +
  h2('5. BASE LÉGALE DU TRAITEMENT DES DONNÉES') +
  p('Le traitement des données personnelles repose notamment sur :') +
  ul(['Le consentement de l\'utilisateur', 'L\'exécution des services demandés', 'Les intérêts légitimes liés à l\'exploitation sécurisée de la plateforme', 'Les obligations légales et réglementaires applicables en République Démocratique du Congo']) +
  h2('6. PROTECTION DES MINEURS') +
  p('REVIVAL TALK n\'est pas destiné aux enfants de moins de 13 ans sans supervision parentale.') +
  p('Nous ne collectons pas volontairement des données personnelles auprès de mineurs sans autorisation parentale lorsque cela est requis par la loi.') +
  p('Les parents ou tuteurs peuvent nous contacter pour demander la suppression des informations d\'un mineur.') +
  h2('7. PARTAGE DES INFORMATIONS') +
  p('Nous ne vendons pas les données personnelles des utilisateurs.') +
  p('Cependant, certaines informations peuvent être partagées dans les situations suivantes :') +
  h3('7.1 Prestataires techniques') +
  p('Nous pouvons collaborer avec des fournisseurs tiers pour :') +
  ul(['Hébergement cloud', 'Services analytiques', 'Notifications push', 'Publicité', 'Maintenance technique', 'Sécurité informatique', 'Services de streaming', 'Diffusion vidéo', 'Services marketing']) +
  p('Ces partenaires sont tenus de protéger les données conformément à des obligations strictes de confidentialité.') +
  h3('7.2 Obligations légales') +
  p('Nous pouvons divulguer certaines informations lorsque cela est exigé :') +
  ul(['Par une autorité judiciaire', 'Par les forces de l\'ordre', 'Par une autorité administrative compétente', 'Pour protéger nos droits légaux', 'Pour prévenir une activité illégale ou frauduleuse', 'Pour garantir la sécurité de nos utilisateurs et de nos infrastructures numériques']) +
  h2('8. PUBLICITÉS ET SERVICES TIERS') +
  p('Notre plateforme peut afficher des contenus publicitaires ou intégrer des services tiers tels que :') +
  ul(['Réseaux publicitaires', 'Vidéos externes', 'Réseaux sociaux', 'Outils analytiques', 'Services de streaming', 'Plateformes de diffusion vidéo']) +
  p('Ces services tiers peuvent collecter certaines informations conformément à leurs propres politiques de confidentialité.') +
  p('Nous encourageons les utilisateurs à consulter les politiques de confidentialité de ces services externes.') +
  h2('9. CONSERVATION DES DONNÉES') +
  p('Les données personnelles sont conservées uniquement pendant la durée nécessaire :') +
  ul(['Au fonctionnement des services', 'Au respect des obligations légales', 'À la résolution des litiges', 'À la sécurité des systèmes', 'À l\'amélioration de la plateforme']) +
  p('À l\'expiration des délais nécessaires, les données peuvent être supprimées, anonymisées ou archivées de manière sécurisée.') +
  h2('10. SÉCURITÉ DES DONNÉES') +
  p('REVIVAL TALK met en œuvre plusieurs mesures de sécurité techniques et organisationnelles visant à protéger les informations contre :') +
  ul(['Les accès non autorisés', 'Les pertes de données', 'Les modifications illégales', 'Les divulgations abusives', 'Les cyberattaques', 'Les intrusions malveillantes']) +
  p('Ces mesures incluent notamment :') +
  ul(['Chiffrement des données', 'Pare-feu de sécurité', 'Surveillance des serveurs', 'Contrôle d\'accès', 'Sauvegardes sécurisées', 'Protocoles HTTPS', 'Sécurisation des comptes administrateurs']) +
  p('Malgré nos efforts, aucun système informatique ne peut garantir une sécurité absolue.') +
  h2('11. TRANSFERT INTERNATIONAL DES DONNÉES') +
  p('Certaines données peuvent être hébergées ou traitées en dehors de la République Démocratique du Congo via des infrastructures cloud internationales.') +
  p('Dans ce cas, REVIVAL TALK veille à appliquer des mesures de protection appropriées afin de garantir la confidentialité et la sécurité des informations transférées.') +
  h2('12. DROITS DES UTILISATEURS') +
  p('Conformément aux principes applicables en matière de protection de la vie privée, les utilisateurs peuvent demander :') +
  ul(['L\'accès à leurs données personnelles', 'La correction des informations inexactes', 'La suppression de certaines données', 'La limitation du traitement', 'L\'opposition à certaines utilisations', 'Le retrait du consentement', 'La fermeture de leur compte']) +
  p('Les demandes peuvent être adressées à notre équipe via les coordonnées indiquées ci-dessous.') +
  h2('13. NEWSLETTERS ET COMMUNICATIONS') +
  p('Les utilisateurs peuvent recevoir :') +
  ul(['Alertes d\'actualité', 'Newsletters', 'Notifications push', 'Communications promotionnelles', 'Informations importantes concernant les services']) +
  p('Chaque utilisateur peut se désabonner à tout moment via les paramètres du compte ou les liens de désinscription.') +
  h2('14. COMMENTAIRES ET CONTENUS PUBLIÉS') +
  p('Les utilisateurs sont responsables des contenus qu\'ils publient sur la plateforme.') +
  p('REVIVAL TALK se réserve le droit de modérer, supprimer, restreindre, suspendre ou signaler tout contenu :') +
  ul(['illégal', 'diffamatoire', 'haineux', 'violent', 'trompeur', 'contraire aux lois de la République Démocratique du Congo', 'contraire aux présentes politiques ou Conditions d\'Utilisation']) +
  h2('15. LIENS VERS DES SITES TIERS') +
  p('Notre plateforme peut contenir des liens vers des sites externes.') +
  p('REVIVAL TALK n\'est pas responsable du contenu, des pratiques, des politiques de confidentialité ou des services de ces plateformes tierces.') +
  p('L\'utilisation de ces sites se fait sous la responsabilité de l\'utilisateur.') +
  h2('16. MODIFICATIONS DE LA POLITIQUE DE CONFIDENTIALITÉ') +
  p('Nous pouvons modifier cette Politique de Confidentialité à tout moment afin de respecter l\'évolution des lois, améliorer la transparence, intégrer de nouveaux services ou renforcer la protection des utilisateurs.') +
  p('La version mise à jour sera publiée sur cette page avec la date de révision.') +
  h2('17. CONFORMITÉ AUX LOIS DE LA RDC') +
  p('REVIVAL TALK s\'engage à respecter les lois de la République Démocratique du Congo, les règles relatives aux communications numériques, les réglementations sur la cybersécurité, les principes fondamentaux de protection de la vie privée et les obligations applicables aux plateformes numériques et médias en ligne.') +
  p('En utilisant nos services, vous acceptez également de respecter les lois en vigueur dans votre juridiction.') +
  h2('18. CONTACT') +
  p('Pour toute question relative à cette Politique de Confidentialité ou au traitement des données personnelles, vous pouvez nous contacter :') +
  p(`<strong>REVIVAL TALK</strong><br>Site officiel : ${SITE}<br>Contact : ${CONTACT}<br>Ville : Kinshasa, République Démocratique du Congo`) +
  h2('19. ACCEPTATION DE LA POLITIQUE') +
  p('En accédant à notre plateforme ou en utilisant les services de REVIVAL TALK, vous reconnaissez avoir lu, compris et accepté la présente Politique de Confidentialité.') +
  '</div>';

const termsHtml =
  header('CONDITIONS GÉNÉRALES D\'UTILISATION', '20 Mai 2026', [
    `Bienvenue sur REVIVAL TALK, plateforme numérique d'information, de médias et de diffusion de contenus accessible via : ${SITE}.`,
    'Les présentes Conditions Générales d\'Utilisation définissent les règles applicables à l\'accès et à l\'utilisation des services proposés par REVIVAL TALK.',
    'En accédant à notre plateforme, en créant un compte ou en utilisant nos services, vous reconnaissez avoir lu, compris et accepté l\'intégralité des présentes Conditions Générales d\'Utilisation.',
    'Si vous n\'acceptez pas ces conditions, vous devez cesser immédiatement l\'utilisation de nos services.',
  ]) +
  h2('1. PRÉSENTATION DE LA PLATEFORME') +
  p('REVIVAL TALK est une plateforme numérique d\'information et de médias permettant notamment :') +
  ul(['La consultation d\'articles d\'actualité', 'La diffusion de contenus multimédias', 'Le streaming vidéo et audio', 'Les émissions en direct', 'Les podcasts', 'Les commentaires et interactions communautaires', 'Les notifications d\'actualité', 'Les newsletters', 'Les espaces publicitaires', 'Les contenus interactifs', 'Les services numériques liés aux médias et à l\'information']) +
  p('La plateforme peut être accessible via :') +
  ul(['Site web', 'Applications mobiles', 'Réseaux sociaux', 'Services tiers partenaires', 'Plateformes connectées']) +
  h2('2. ACCEPTATION DES CONDITIONS') +
  p('En utilisant REVIVAL TALK, vous acceptez :') +
  ul(['Les présentes Conditions Générales d\'Utilisation', 'Notre Politique de Confidentialité', 'Toutes les règles, politiques et directives publiées sur la plateforme']) +
  p('Votre utilisation continue des services constitue une acceptation permanente des présentes conditions.') +
  h2('3. CONDITIONS D\'ACCÈS AUX SERVICES') +
  p('L\'accès à certaines fonctionnalités peut nécessiter :') +
  ul(['La création d\'un compte', 'La fourniture d\'informations exactes', 'La vérification de votre identité', 'Le respect des règles communautaires']) +
  p('Vous êtes responsable des informations fournies lors de votre inscription.') +
  p('REVIVAL TALK se réserve le droit de suspendre ou refuser tout compte contenant des informations fausses, trompeuses ou incomplètes.') +
  h2('4. RESPONSABILITÉS DE L\'UTILISATEUR') +
  p('En utilisant la plateforme, vous vous engagez à :') +
  ul(['Respecter les lois applicables', 'Respecter les droits des autres utilisateurs', 'Utiliser les services de manière légale et responsable', 'Ne pas perturber le fonctionnement de la plateforme', 'Ne pas diffuser de contenus illicites', 'Ne pas utiliser la plateforme à des fins frauduleuses', 'Ne pas tenter d\'accéder illégalement aux systèmes informatiques']) +
  p('Vous êtes seul responsable des contenus, messages, commentaires ou publications que vous partagez via REVIVAL TALK.') +
  h2('5. CONTENUS INTERDITS') +
  p('Il est strictement interdit de publier, partager, transmettre ou diffuser des contenus :') +
  ul(['Illégaux', 'Violents', 'Diffamatoires', 'Trompeurs', 'Frauduleux', 'Pornographiques', 'Haineux', 'Racistes', 'Discriminatoires', 'Incitant à la violence', 'Portant atteinte à la sécurité nationale', 'Contraires aux lois de la République Démocratique du Congo', 'Violant les droits de propriété intellectuelle', 'Contenant des logiciels malveillants ou virus']) +
  p('REVIVAL TALK se réserve le droit de supprimer immédiatement tout contenu jugé inapproprié.') +
  h2('6. MODÉRATION ET SUSPENSION') +
  p('REVIVAL TALK peut, à sa seule discrétion :') +
  ul(['Supprimer un contenu', 'Suspendre un compte', 'Restreindre certaines fonctionnalités', 'Bannir un utilisateur', 'Signaler certaines activités aux autorités compétentes']) +
  p('Ces mesures peuvent être prises sans préavis lorsque cela est nécessaire pour la sécurité de la plateforme, la protection des utilisateurs, le respect des lois ou la prévention des abus.') +
  h2('7. PROPRIÉTÉ INTELLECTUELLE') +
  p('Tous les contenus présents sur REVIVAL TALK, notamment logos, marques, articles, images, vidéos, podcasts, éléments graphiques, interfaces, codes sources, designs et bases de données, sont protégés par les lois relatives à la propriété intellectuelle.') +
  p('Sauf autorisation écrite préalable, il est interdit de copier, reproduire, modifier, distribuer, revendre ou exploiter commercialement tout ou partie des contenus de la plateforme.') +
  h2('8. LICENCE D\'UTILISATION LIMITÉE') +
  p('REVIVAL TALK accorde aux utilisateurs une licence limitée, non exclusive, révocable et non transférable pour accéder aux services à des fins personnelles et non commerciales.') +
  p('Cette licence ne confère aucun droit de propriété sur les contenus ou technologies de la plateforme.') +
  h2('9. PUBLICITÉS ET SERVICES TIERS') +
  p('Notre plateforme peut afficher des publicités, des contenus sponsorisés, des liens externes ou des services tiers.') +
  p('REVIVAL TALK n\'est pas responsable des contenus tiers, des produits ou services proposés par des partenaires, des politiques des plateformes externes ou des dommages liés à l\'utilisation de services tiers.') +
  p('Les utilisateurs accèdent aux services externes sous leur propre responsabilité.') +
  h2('10. EXACTITUDE DES INFORMATIONS') +
  p('REVIVAL TALK s\'efforce de fournir des informations fiables et régulièrement mises à jour.') +
  p('Cependant, nous ne garantissons pas l\'exactitude absolue, l\'exhaustivité, l\'absence d\'erreurs ou la disponibilité permanente des contenus.') +
  p('Les informations publiées sont fournies à titre informatif et peuvent évoluer à tout moment.') +
  h2('11. DISPONIBILITÉ DES SERVICES') +
  p('Nous nous efforçons d\'assurer un accès continu à nos services.') +
  p('Toutefois, REVIVAL TALK peut interrompre temporairement ou définitivement certains services notamment pour maintenance, mise à jour, sécurité, incident technique ou force majeure.') +
  p('Nous ne garantissons pas une disponibilité ininterrompue des services.') +
  h2('12. LIMITATION DE RESPONSABILITÉ') +
  p('Dans les limites autorisées par les lois applicables, REVIVAL TALK ne pourra être tenu responsable des interruptions de service, pertes de données, dommages indirects, erreurs techniques, contenus publiés par les utilisateurs, décisions prises sur base des contenus diffusés ou pertes financières ou commerciales.') +
  p('L\'utilisation des services se fait sous la responsabilité exclusive de l\'utilisateur.') +
  h2('13. PROTECTION DES DONNÉES PERSONNELLES') +
  p('La collecte et le traitement des données personnelles sont régis par notre Politique de Confidentialité.') +
  p('En utilisant REVIVAL TALK, vous acceptez les pratiques décrites dans cette politique.') +
  h2('14. COMPTES UTILISATEURS') +
  p('Les utilisateurs sont responsables de la confidentialité de leurs identifiants, de la sécurité de leurs comptes et des activités effectuées depuis leurs comptes.') +
  p('Vous devez immédiatement signaler toute utilisation non autorisée de votre compte.') +
  p('REVIVAL TALK peut suspendre tout compte présentant des risques de sécurité ou d\'abus.') +
  h2('15. NEWSLETTERS ET NOTIFICATIONS') +
  p('Les utilisateurs peuvent recevoir newsletters, alertes d\'actualité, notifications push, communications promotionnelles ou informations importantes liées aux services.') +
  p('Les utilisateurs peuvent se désabonner à tout moment.') +
  h2('16. LIENS EXTERNES') +
  p('La plateforme peut contenir des liens vers des sites tiers.') +
  p('REVIVAL TALK ne contrôle pas ces plateformes externes et n\'est pas responsable de leurs contenus, pratiques, politiques ou services.') +
  h2('17. FORCE MAJEURE') +
  p('REVIVAL TALK ne pourra être tenu responsable en cas d\'inexécution ou de retard résultant notamment de catastrophes naturelles, conflits, cyberattaques, pannes réseaux, coupures électriques, actions gouvernementales ou événements indépendants de notre volonté.') +
  h2('18. MODIFICATION DES CONDITIONS') +
  p('REVIVAL TALK peut modifier les présentes Conditions Générales d\'Utilisation à tout moment.') +
  p('Les modifications prennent effet dès leur publication sur la plateforme.') +
  p('Il appartient aux utilisateurs de consulter régulièrement cette page.') +
  h2('19. RÉSILIATION') +
  p('Nous nous réservons le droit de suspendre ou résilier l\'accès à nos services en cas de violation des présentes conditions, d\'utilisation abusive, d\'activité frauduleuse ou de risque pour la sécurité de la plateforme.') +
  p('Certaines dispositions continueront à s\'appliquer après résiliation, notamment celles relatives à la responsabilité et à la propriété intellectuelle.') +
  h2('20. DROIT APPLICABLE ET JURIDICTION') +
  p('Les présentes Conditions Générales d\'Utilisation sont régies par les lois de la République Démocratique du Congo.') +
  p('Tout litige relatif à l\'utilisation de REVIVAL TALK sera soumis aux juridictions compétentes de Kinshasa, sauf disposition légale contraire.') +
  h2('21. CONTACT') +
  p('Pour toute question relative aux présentes Conditions Générales d\'Utilisation, vous pouvez nous contacter :') +
  p(`<strong>REVIVAL TALK</strong><br>Site officiel : ${SITE}<br>Contact : ${CONTACT}<br>Ville : Kinshasa, République Démocratique du Congo`) +
  h2('22. ACCEPTATION DES CONDITIONS') +
  p('En utilisant les services de REVIVAL TALK, vous reconnaissez avoir lu, compris et accepté l\'intégralité des présentes Conditions Générales d\'Utilisation.') +
  '</div>';

fs.mkdirSync(outDir, { recursive: true });
fs.writeFileSync(path.join(outDir, 'privacy_policy_fr.html'), privacyPolicyHtml, 'utf8');
fs.writeFileSync(path.join(outDir, 'terms_conditions_fr.html'), termsHtml, 'utf8');
console.log('Policy HTML files generated.');
