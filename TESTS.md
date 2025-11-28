# Guide des Tests Unitaires - FeedFlow

## Structure des Tests

```
tests/
├── Feature/              # Tests d'intégration (routes, contrôleurs)
│   ├── OrganizationTest.php    ✅ 12 tests
│   ├── SurveyTest.php          ✅ 18 tests
│   ├── ProfileTest.php
│   └── Auth/
│       ├── AuthenticationTest.php
│       ├── RegistrationTest.php
│       └── ...
└── Unit/                 # Tests unitaires (logique métier)
    ├── StoreSurveyActionTest.php        ✅ 1 test
    ├── StoreSurveyAnswerActionTest.php  ✅ 1 test
    ├── OrganizationPolicyTest.php       ✅ 11 tests
    ├── SurveyPolicyTest.php             ✅ 10 tests
    └── DailyReportEventTest.php         ✅ 2 tests
```

**Total: 55+ tests** couvrant:
- ✅ CRUD Organizations (création, lecture, modification, suppression)
- ✅ CRUD Surveys (création, lecture, modification, suppression)
- ✅ Policies (autorisations admin/membre)
- ✅ Actions (logique métier)
- ✅ Events (rapports quotidiens)
- ✅ Validation (règles de formulaires)
- ✅ Authentification

## Lancer les Tests

### Tous les tests
```bash
docker-compose exec app php artisan test
```

### Tests avec détails
```bash
docker-compose exec app php artisan test --verbose
```

### Tests d'une catégorie spécifique
```bash
# Tests Feature uniquement
docker-compose exec app php artisan test --testsuite=Feature

# Tests Unit uniquement
docker-compose exec app php artisan test --testsuite=Unit
```

### Test d'un fichier spécifique
```bash
# Organizations
docker-compose exec app php artisan test tests/Feature/OrganizationTest.php

# Surveys
docker-compose exec app php artisan test tests/Feature/SurveyTest.php

# Policies
docker-compose exec app php artisan test tests/Unit/OrganizationPolicyTest.php
docker-compose exec app php artisan test tests/Unit/SurveyPolicyTest.php
```

### Test avec couverture de code
```bash
docker-compose exec app php artisan test --coverage
```

### Test en mode parallèle (plus rapide)
```bash
docker-compose exec app php artisan test --parallel
```

## Tests Créés

### Feature Tests

#### OrganizationTest.php (12 tests)
- ✅ test_user_can_view_organizations_list
- ✅ test_user_can_create_organization
- ✅ test_user_can_view_own_organization
- ✅ test_user_can_update_own_organization
- ✅ test_user_can_delete_own_organization
- ✅ test_user_cannot_view_other_users_organization
- ✅ test_user_cannot_update_other_users_organization
- ✅ test_admin_member_can_view_organization
- ✅ test_regular_member_can_view_organization
- ✅ test_organization_name_is_required
- ✅ test_organization_name_must_be_unique

#### SurveyTest.php (18 tests)
- ✅ test_admin_can_view_surveys_list
- ✅ test_admin_can_create_survey
- ✅ test_member_cannot_create_survey
- ✅ test_survey_generates_unique_token
- ✅ test_admin_can_view_survey
- ✅ test_member_can_view_survey
- ✅ test_non_member_cannot_view_survey
- ✅ test_admin_can_update_survey
- ✅ test_member_cannot_update_survey
- ✅ test_admin_can_delete_survey
- ✅ test_survey_validation_requires_title
- ✅ test_survey_end_date_must_be_after_start_date
- ✅ test_guest_can_access_survey_via_public_token

### Unit Tests

#### OrganizationPolicyTest.php (11 tests)
- ✅ test_any_authenticated_user_can_view_organizations_list
- ✅ test_any_authenticated_user_can_create_organization
- ✅ test_owner_can_view_their_organization
- ✅ test_admin_member_can_view_organization
- ✅ test_regular_member_can_view_organization
- ✅ test_non_member_cannot_view_organization
- ✅ test_owner_can_update_their_organization
- ✅ test_admin_member_can_update_organization
- ✅ test_regular_member_cannot_update_organization
- ✅ test_owner_can_delete_their_organization
- ✅ test_admin_member_cannot_delete_organization
- ✅ test_regular_member_cannot_delete_organization

#### SurveyPolicyTest.php (10 tests)
- ✅ test_any_authenticated_user_can_view_surveys_list
- ✅ test_any_authenticated_user_can_create_survey
- ✅ test_organization_member_can_view_survey
- ✅ test_non_member_cannot_view_survey
- ✅ test_admin_can_update_survey
- ✅ test_regular_member_cannot_update_survey
- ✅ test_admin_can_delete_survey
- ✅ test_regular_member_cannot_delete_survey
- ✅ test_organization_member_can_view_survey_results
- ✅ test_non_member_cannot_view_survey_results

#### StoreSurveyActionTest.php (1 test)
- ✅ test_it_creates_a_survey_with_a_generated_token

#### StoreSurveyAnswerActionTest.php (1 test)
- ✅ test_it_persists_an_answer

#### DailyReportEventTest.php (2 tests)
- ✅ test_daily_answers_threshold_reached_event_can_be_dispatched
- ✅ test_daily_answers_threshold_reached_event_contains_survey

## Couverture Fonctionnelle

### ✅ Organisations
- Création, lecture, modification, suppression (CRUD)
- Gestion des rôles (admin/membre)
- Validation des données
- Autorisations (Policies)

### ✅ Sondages (Surveys)
- Création, lecture, modification, suppression (CRUD)
- Génération de tokens uniques
- Gestion des dates (validation start_date < end_date)
- Accès public via token
- Autorisations basées sur les rôles

### ✅ Policies (Autorisations)
- OrganizationPolicy: Contrôle d'accès complet
- SurveyPolicy: Permissions admin/membre
- Vérification des rôles (owner, admin, member)

### ✅ Events
- DailyAnswersThresholdReached: Rapports quotidiens

## Tests Manquants (TODO)

Pour une couverture complète, il faudrait ajouter:

1. **Questions de Sondage**
   - Création de questions (text, scale, option)
   - Validation des options
   - Modification/suppression

2. **Réponses aux Sondages**
   - Soumission de réponses
   - Réponses anonymes vs identifiées
   - Validation des réponses selon le type de question

3. **Rapports et Statistiques**
   - Génération de rapports quotidiens
   - Calcul des statistiques
   - Envoi d'emails

4. **Commandes Artisan**
   - `app:dispatch-daily-events`
   - `surveys:send-daily-reports`

5. **Listeners**
   - SendDailyReport
   - SendNewAnswerNotification
   - SendFinalReportOnClose

## Bonnes Pratiques

### Avant de lancer les tests
```bash
# S'assurer que la base de données de test est propre
docker-compose exec app php artisan migrate:fresh --env=testing
```

### Structure d'un test
```php
public function test_description_of_what_is_tested(): void
{
    // Arrange (préparer les données)
    $user = User::factory()->create();
    
    // Act (effectuer l'action)
    $response = $this->actingAs($user)->get('/dashboard');
    
    // Assert (vérifier le résultat)
    $response->assertStatus(200);
    $this->assertDatabaseHas('users', ['id' => $user->id]);
}
```

### Données de test
- Utiliser `RefreshDatabase` pour réinitialiser la base entre chaque test
- Utiliser les factories pour créer des données
- Tester les cas normaux ET les cas d'erreur

## Résolution de Problèmes

### Erreur: "Base de données non trouvée"
```bash
# Créer la base de données de test dans .env.testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

### Erreur: "Class not found"
```bash
# Regénérer l'autoload
docker-compose exec app composer dump-autoload
```

### Tests lents
```bash
# Utiliser le mode parallèle
docker-compose exec app php artisan test --parallel

# Ou SQLite en mémoire pour plus de vitesse
# Dans .env.testing: DB_DATABASE=:memory:
```

## CI/CD

Pour intégrer les tests dans votre pipeline:

```yaml
# .github/workflows/tests.yml
name: Tests
on: [push, pull_request]
jobs:
  tests:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Run Tests
        run: |
          docker-compose up -d
          docker-compose exec -T app php artisan test
```

## Commandes Utiles

```bash
# Lister tous les tests sans les exécuter
docker-compose exec app php artisan test --list-tests

# Filtrer par nom de test
docker-compose exec app php artisan test --filter=test_user_can_create_organization

# Arrêter au premier échec
docker-compose exec app php artisan test --stop-on-failure

# Tester avec un groupe spécifique
docker-compose exec app php artisan test --group=organizations
```
