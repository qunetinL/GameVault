<main class="section">
    <header class="section__header">
        <div class="section__titles">
            <h1>Politique de confidentialité</h1>
            <p>Dernière mise à jour : <?= date('d/m/Y') ?></p>
        </div>
    </header>

    <div style="max-width: 800px; margin-top: 2rem; line-height: 1.8;">

        <div style="background: var(--card); padding: 2rem; border-radius: 1rem; border: 1px solid var(--border); margin-bottom: 1.5rem;">
            <h2 style="margin-bottom: 1rem;">1. Identité du responsable du traitement</h2>
            <p>Le responsable du traitement des données personnelles est GameVault, projet réalisé dans le cadre de la formation DWWM.</p>
            <p>Contact : <a href="mailto:contact@gamevault.fr" style="color: var(--primary);">contact@gamevault.fr</a></p>
        </div>

        <div style="background: var(--card); padding: 2rem; border-radius: 1rem; border: 1px solid var(--border); margin-bottom: 1.5rem;">
            <h2 style="margin-bottom: 1rem;">2. Données collectées</h2>
            <p>Dans le cadre de l'utilisation de la plateforme GameVault, nous collectons les données suivantes :</p>
            <ul style="margin: 1rem 0; padding-left: 1.5rem;">
                <li><strong>Nom d'utilisateur</strong> : choisi lors de l'inscription, utilisé pour l'identification sur la plateforme.</li>
                <li><strong>Adresse email</strong> : utilisée pour l'authentification et la communication.</li>
                <li><strong>Mot de passe hashé</strong> : stocké sous forme de hash bcrypt, jamais en clair.</li>
                <li><strong>Collection de jeux</strong> : les jeux ajoutés à votre collection personnelle.</li>
                <li><strong>Messages</strong> : les messages échangés via le système de chat.</li>
                <li><strong>Activité</strong> : sessions de jeu organisées, votes, invitations et historique de connexion.</li>
                <li><strong>Date d'inscription et de consentement</strong> : horodatage de la création du compte et de l'acceptation des conditions.</li>
            </ul>
        </div>

        <div style="background: var(--card); padding: 2rem; border-radius: 1rem; border: 1px solid var(--border); margin-bottom: 1.5rem;">
            <h2 style="margin-bottom: 1rem;">3. Finalité du traitement</h2>
            <p>Les données collectées sont utilisées pour :</p>
            <ul style="margin: 1rem 0; padding-left: 1.5rem;">
                <li>Permettre la création et la gestion de votre compte utilisateur.</li>
                <li>Gérer votre collection de jeux de société.</li>
                <li>Organiser et participer à des sessions de jeu.</li>
                <li>Permettre la communication entre utilisateurs via le chat.</li>
                <li>Fournir des statistiques d'utilisation.</li>
                <li>Assurer la sécurité de la plateforme (protection contre le brute-force, gestion des sessions).</li>
            </ul>
        </div>

        <div style="background: var(--card); padding: 2rem; border-radius: 1rem; border: 1px solid var(--border); margin-bottom: 1.5rem;">
            <h2 style="margin-bottom: 1rem;">4. Base légale du traitement</h2>
            <p>Le traitement de vos données repose sur :</p>
            <ul style="margin: 1rem 0; padding-left: 1.5rem;">
                <li><strong>Le consentement (Art. 6.1.a du RGPD)</strong> : vous consentez au traitement de vos données lors de votre inscription en acceptant les présentes conditions. Ce consentement est horodaté.</li>
                <li><strong>L'intérêt légitime (Art. 6.1.f du RGPD)</strong> : pour les mesures de sécurité (protection contre le brute-force, journalisation des activités suspectes) nécessaires au bon fonctionnement de la plateforme.</li>
            </ul>
        </div>

        <div style="background: var(--card); padding: 2rem; border-radius: 1rem; border: 1px solid var(--border); margin-bottom: 1.5rem;">
            <h2 style="margin-bottom: 1rem;">5. Durée de conservation</h2>
            <p>Vos données personnelles sont conservées pendant toute la durée de votre inscription sur la plateforme.</p>
            <p>En cas de suppression de votre compte, toutes vos données sont effacées immédiatement de la base de données et du cache Redis, conformément au droit à l'effacement (Art. 17 du RGPD).</p>
            <p>Les logs de sécurité (tentatives de connexion) sont conservés pendant 15 minutes maximum.</p>
        </div>

        <div style="background: var(--card); padding: 2rem; border-radius: 1rem; border: 1px solid var(--border); margin-bottom: 1.5rem;">
            <h2 style="margin-bottom: 1rem;">6. Droits des utilisateurs</h2>
            <p>Conformément au RGPD, vous disposez des droits suivants :</p>
            <ul style="margin: 1rem 0; padding-left: 1.5rem;">
                <li><strong>Droit d'accès (Art. 15)</strong> : vous pouvez consulter l'ensemble de vos données personnelles depuis votre profil.</li>
                <li><strong>Droit de rectification (Art. 16)</strong> : vous pouvez modifier votre nom d'utilisateur et votre adresse email depuis votre profil.</li>
                <li><strong>Droit à l'effacement (Art. 17)</strong> : vous pouvez supprimer votre compte et toutes les données associées depuis votre profil.</li>
                <li><strong>Droit à la portabilité (Art. 20)</strong> : vous pouvez exporter l'ensemble de vos données au format JSON depuis votre profil.</li>
                <li><strong>Droit d'opposition (Art. 21)</strong> : vous pouvez vous opposer au traitement de vos données en nous contactant.</li>
            </ul>
            <p>Ces droits peuvent être exercés directement depuis votre <a href="/profile" style="color: var(--primary);">page de profil</a> ou en nous contactant à l'adresse <a href="mailto:contact@gamevault.fr" style="color: var(--primary);">contact@gamevault.fr</a>.</p>
        </div>

        <div style="background: var(--card); padding: 2rem; border-radius: 1rem; border: 1px solid var(--border); margin-bottom: 1.5rem;">
            <h2 style="margin-bottom: 1rem;">7. Cookies utilisés</h2>
            <p>GameVault utilise uniquement des cookies essentiels au fonctionnement de la plateforme :</p>
            <ul style="margin: 1rem 0; padding-left: 1.5rem;">
                <li><strong>Cookie de session (PHPSESSID)</strong> : permet de maintenir votre session de connexion active. Ce cookie est supprimé à la fermeture du navigateur.</li>
                <li><strong>Cookie CSRF</strong> : protège contre les attaques de type Cross-Site Request Forgery.</li>
                <li><strong>Cookie de consentement (cookie_consent)</strong> : enregistre votre acceptation de l'utilisation des cookies (stocké en localStorage).</li>
            </ul>
            <p>Aucun cookie de traçage, de publicité ou d'analyse tiers n'est utilisé.</p>
        </div>

        <div style="background: var(--card); padding: 2rem; border-radius: 1rem; border: 1px solid var(--border); margin-bottom: 1.5rem;">
            <h2 style="margin-bottom: 1rem;">8. Contact</h2>
            <p>Pour toute question relative à la protection de vos données personnelles ou pour exercer vos droits, vous pouvez nous contacter :</p>
            <ul style="margin: 1rem 0; padding-left: 1.5rem;">
                <li>Par email : <a href="mailto:contact@gamevault.fr" style="color: var(--primary);">contact@gamevault.fr</a></li>
            </ul>
        </div>

    </div>
</main>
